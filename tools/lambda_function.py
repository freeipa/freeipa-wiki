#!/usr/bin/env python3
import boto3
import logging
import time

class BackupError(Exception):
    pass

ec2_configuration = {
    'ec2_region': 'eu-central-1',
    'controller_instance_name': 'freeipa-org-ocp-controller',
    'timeout': 120,
}

commands = [
    {'path': '/root/bin/freeipa-planet-rebuild.sh'},
    {'path': '/root/bin/freeipa-org-wiki-backup.sh'},
]

def run_scripts(debug):
    logger = logging.getLogger('www.freeipa.org')
    if debug:
        logger.setLevel(logging.DEBUG)
        level = logging.DEBUG
    else:
        level = logging.WARNING

    handler = logging.StreamHandler()
    handler.setLevel(level)
    formatter = logging.Formatter('%(levelname)s: %(message)s')
    handler.setFormatter(formatter)
    logger.addHandler(handler)

    searched_name = ec2_configuration['controller_instance_name']
    logger.debug("Searching for instance with name '{}'".format(searched_name))
    ec2 = boto3.resource('ec2', region_name=ec2_configuration['ec2_region'])
    instances = ec2.instances.filter(Filters=[{
        'Name': 'tag:Name',
        'Values': [searched_name]}])
    instances = list(instances.all())

    if len(instances) != 1:
        raise BackupError("There must be just one instance named '{}'. Found {}.".format(
            searched_name, len(instances)))

    controller_instance = instances[0]
    logger.debug("Found controller instance {}".format(controller_instance.id))

    ssm_client = boto3.client('ssm')
    command_paths = [c['path'] for c in commands]
    logger.debug("Executing commands: '{}'".format("', '".join(command_paths)))

    resp = ssm_client.send_command(
        DocumentName="AWS-RunShellScript",
        Parameters={'commands': command_paths},
        InstanceIds=[controller_instance.id],
        TimeoutSeconds=ec2_configuration['timeout'],
        Comment="Execute FreeIPA OCP commands"
        )
    command_id = resp['Command']['CommandId']

    logger.debug("Waiting until command {} finishes".format(command_id))
    command_result = None
    while True:
        r = ssm_client.list_commands(CommandId=command_id)
        command_result = r['Commands'][0]
        if command_result['CompletedCount'] > 0:
            status = command_result['Status']
            logger.debug("Done with status {}, finish waiting".format(status))
            break
        time.sleep(5)

    logger.debug("Get invocation details")
    command_details = ssm_client.get_command_invocation(CommandId=command_id,
                                                        InstanceId=controller_instance.id)
    logger.debug("Command run from {} to {}, with status details {}".format(
        command_details['ExecutionStartDateTime'],
        command_details['ExecutionEndDateTime'],
        command_details['StatusDetails']))
    logger.debug("Command Standard output: {}".format(command_details['StandardOutputContent']))
    if command_details['StandardErrorContent']:
        logger.warning("Command Error output: {}".format(command_details['StandardErrorContent']))

    if command_result['Status'] != 'Success':
        raise BackupError("Backup Command failed: {}".format(command_details['StandardErrorContent']))

    logger.info("Backup finished with status {}".format(command_result['Status']))

if __name__ == '__main__':
    run_scripts(debug=True)

# Triggered by Amazon AWS
def handler(event, context):
    print("Run FreeIPA OCP Lambda", event, context)
    run_scripts(debug=False)
    print("FreeIPA OCP Lambda finished")
    return {'message': "FreeIPA OCP Lambda sucessful"}
