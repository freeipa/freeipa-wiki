#!/bin/bash

if [ ! -r ~/.freeipa_org_ocp_config ]; then
    echo "Cannot read ~/.freeipa_org_ocp_config" >&2
    exit 1
else
    source ~/.freeipa_org_ocp_config
fi

WORK_DIR=`mktemp -d`

function finish {
    rm -rf "$WORK_DIR"
}
trap finish EXIT

BACKUP_ARCHIVE_NAME="freeipa_org_wiki_`date +%Y%m%d-%H%M%S`.tar.gz"
BACKUP_ARCHIVE_PATH="$WORK_DIR/$BACKUP_ARCHIVE_NAME"

export KUBECONFIG=$WORK_DIR/kubeconfig
oc login $OPENSHIFT_SERVER --token="$OPENSHIFT_TOKEN" > /dev/null

if [ $? -ne 0 ]; then
    echo "Cannot login to OpenShift" >&2
    exit 1
fi

oc project $OPENSHIFT_PROJECT > /dev/null

db_pod=`oc get pods -o name | grep freeipa-org-wiki-db | cut -d"/" -f 2`

oc port-forward $db_pod 3306 > /dev/null &
port_forward_ret=$?
port_forward_pid=$!

if [ $port_forward_ret -ne 0 ]; then
    echo "Could not port-forward" >&2
    exit 1
fi

sleep 5
mysqldump -h127.0.0.1 -p3306 -u $OPENSHIFT_DATABASE_USERNAME  -p$OPENSHIFT_DATABASE_PASSWORD --single-transaction --quick --routines --triggers $OPENSHIFT_DATABASE_NAME > $WORK_DIR/$OPENSHIFT_DATABASE_NAME.sql

mysqldump_ret=$?
kill $port_forward_pid > /dev/null

if [ $mysqldump_ret -ne 0 ]; then
    echo "mysqldump failed" >&2
    exit 1
fi

web_pod=`oc get pods -o name | grep freeipa-org-wiki  | grep -v freeipa-org-wiki-db | grep -v build |  cut -d"/" -f 2`

oc rsync -q $web_pod:/opt/app-root/data/images $WORK_DIR/

if [ $? -ne 0 ]; then
    echo "oc rsync failed" >&2
    exit 1
fi

pushd $WORK_DIR > /dev/null
tar -czf $BACKUP_ARCHIVE_PATH $OPENSHIFT_DATABASE_NAME.sql images/ > /dev/null
popd

aws s3 cp $BACKUP_ARCHIVE_PATH s3://$S3_BUCKET/$S3_BUCKET_BACKUP_PATH/

if [ $? -ne 0 ]; then
    echo "aws s3 cp failed" >&2
    exit 1
fi
