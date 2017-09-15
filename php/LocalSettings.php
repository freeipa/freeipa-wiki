<?php
# This file was automatically generated by the MediaWiki 1.20.3
# installer. If you make manual changes, please keep track in case you
# need to recreate them later.
#
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# http://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

if (getenv('OPENSHIFT_BUILD_NAMESPACE'))
    $on_openshift = true;
else
    $on_openshift = false;

if (!$on_openshift) {
    $wgShowDBErrorBacktrace = true;
}

## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;
#
if ($on_openshift) {
    $wgSitename      = "FreeIPA";
} else {
    $wgSitename      = "[TEST] FreeIPA";
}

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## http://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath       = "";
$wgScriptExtension  = ".php";

$wgArticlePath      = $wgScriptPath . "/page/$1";

## The protocol and server name to use in fully-qualified URLs
if ($on_openshift) {
    $application_domain = getenv('APPLICATION_DOMAIN');
    if ($application_domain)
        $wgServer           = "//" . $application_domain;
} else {
    $wgServer           = "//www.freeipa.org";
}

## The relative URL path to the skins directory
$wgStylePath        = "$wgScriptPath/skins";

## The relative URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogo             = "$wgScriptPath/images/freeipa/freeipa-logo-small.png";
$wgFavicon          = "$wgScriptPath/images/freeipa/favicon.ico";

if (!$on_openshift) {
    $wgShowExceptionDetails = True;
}

## UPO means: this is also a user preference option

$wgEnableEmail      = true;
$wgEnableUserEmail  = true; # UPO

$wgEmergencyContact = "mkosek@redhat.com";
$wgPasswordSender   = "mkosek@redhat.com";

$wgEnotifUserTalk      = false; # UPO
$wgEnotifWatchlist     = false; # UPO
$wgEmailAuthentication = true;

## Database settings
$wgDBtype           = "mysql";
if ($on_openshift) {
    $wgDBname           = getenv("DATABASE_NAME");
    $wgDBserver         = getenv(strtoupper(getenv("DATABASE_SERVICE_NAME"))."_SERVICE_HOST");
    $wgDBport           = getenv(strtoupper(getenv("DATABASE_SERVICE_NAME"))."_SERVICE_PORT");
    $wgDBuser           = getenv('DATABASE_USER');
    $wgDBpassword       = getenv('DATABASE_PASSWORD');
} else {
    $wgDBname           = "www_freeipa_org";
    $wgDBserver         = 'localhost';
    $wgDBuser           = 'www_freeipa_org';
    $wgDBpassword       = 'password';
}

# MySQL specific settings
$wgDBprefix         = "freeipawiki_";

# MySQL table options to use during installation or update
$wgDBTableOptions   = "ENGINE=InnoDB, DEFAULT CHARSET=utf8";

# Experimental charset support for MySQL 5.0.
$wgDBmysql5 = false;

## Shared memory settings
if ($on_openshift) {
    $wgMainCacheType    = CACHE_ACCEL;
    $wgMemCachedServers = array();
}

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads  = true;
$wgUseImageMagick = false;
$wgImageMagickConvertCommand = "/usr/bin/convert";

# InstantCommons allows wiki to use images from http://commons.wikimedia.org
$wgUseInstantCommons  = false;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = "en_US.utf8";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
#$wgHashedUploadDirectory = false;

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
$wgCacheDirectory = "/tmp";

# Site language code, should be one of the list in ./languages/Names.php
$wgLanguageCode = "en";

# Secret keys
if ($on_openshift) {
    # production values
    $wgSecretKey = getenv("MEDIAWIKI_SECRET_KEY");
    $wgUpgradeKey = getenv("MEDIAWIKI_UPGRADE_KEY");
} else {
    # local testing values - do NOT use in production
    $wgSecretKey = "0fbmzmth6bhqazvv3b6py2j04vdiheglmigablrbhpot5asxtcnuxb3jm2fwsdg9";
    $wgUpgradeKey = "mmdr4cnioj6ribec";
}


# Enforce secure login
if ($on_openshift) {
    $wgSecureLogin = false;
}

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook', 'vector':
require_once( "$IP/skins/strapping/strapping.php" );
$wgDefaultSkin = "strapping";

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl  = "";
$wgRightsText = "";
$wgRightsIcon = "";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# Query string length limit for ResourceLoader. You should only set this if
# your web server has a query string length limit (then set it to that limit),
# or if you have suhosin.get.max_value_length set in php.ini (then set it to
# that value)
$wgResourceLoaderMaxQueryLength = -1;

$wgGroupPermissions['*']['edit'] = false;
$wgGroupPermissions['*']['createtalk'] = false;
$wgGroupPermissions['*']['createpage'] = false;
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['autocreateaccount'] = false;

$wgGroupPermissions['user']['applychangetags'] = false;
$wgGroupPermissions['user']['createpage'] = false;
$wgGroupPermissions['user']['changetags'] = false;
$wgGroupPermissions['user']['createtalk'] = false;
$wgGroupPermissions['user']['edit']	= false;
$wgGroupPermissions['user']['minoredit'] = false;
$wgGroupPermissions['user']['move-categorypages'] = false;
$wgGroupPermissions['user']['movefile'] = false;
$wgGroupPermissions['user']['move'] = false;
$wgGroupPermissions['user']['move-subpages'] = false;
$wgGroupPermissions['user']['move-rootuserpages'] = false;
$wgGroupPermissions['user']['reupload-shared'] = false;
$wgGroupPermissions['user']['reupload'] = false;
$wgGroupPermissions['user']['purge'] = false;
$wgGroupPermissions['user']['writeapi'] = false;
$wgGroupPermissions['user']['upload'] = false;

$wgGroupPermissions['editor'] = $wgGroupPermissions['user'];
$wgGroupPermissions['editor']['applychangetags'] = true;
$wgGroupPermissions['editor']['createtalk'] = true;
$wgGroupPermissions['editor']['createpage'] = true;
$wgGroupPermissions['editor']['createaccount'] = true;
$wgGroupPermissions['editor']['changetags'] = true;
$wgGroupPermissions['editor']['createtalk'] = true;
$wgGroupPermissions['editor']['edit']	= true;
$wgGroupPermissions['editor']['minoredit'] = true;
$wgGroupPermissions['editor']['move-categorypages'] = true;
$wgGroupPermissions['editor']['movefile'] = true;
$wgGroupPermissions['editor']['move'] = true;
$wgGroupPermissions['editor']['move-subpages'] = true;
$wgGroupPermissions['editor']['move-rootuserpages'] = true;
$wgGroupPermissions['editor']['reupload-shared'] = true;
$wgGroupPermissions['editor']['reupload'] = true;
$wgGroupPermissions['editor']['purge'] = true;
$wgGroupPermissions['editor']['writeapi'] = true;
$wgGroupPermissions['editor']['upload'] = true;

# End of automatically generated settings.
# Add more configuration options below.

# Search box placement
$wgSearchPlacement['header'] = false;
$wgSearchPlacement['nav'] = true;
$wgSearchPlacement['footer'] = false;

# Add another custom font CSS
$wgResourceModules['skins.strapping']['styles']['strapping/font/font.css'] = array( 'media' => 'screen' );

# File extensions
$wgFileExtensions = array( 'png', 'gif', 'jpg', 'jpeg', 'pdf', 'ogg', 'svg', 'odp', 'odt', 'txt', 'log', 'tar.gz' );

# Extensions
wfLoadExtension('SyntaxHighlight_GeSHi');
wfLoadExtension('ParserFunctions');
require_once "$IP/extensions/ReplaceText/ReplaceText.php";
require_once "$IP/extensions/EmbedVideo/EmbedVideo.php";
require_once "$IP/extensions/DynamicPageList/DynamicPageList.php";
require_once "$IP/extensions/Piwik/Piwik.php";
# https://www.mediawiki.org/wiki/Extension:Cite
wfLoadExtension('Cite');
wfLoadExtension('Nuke');

# Configure extensions
$wgGroupPermissions['sysop']['replacetext'] = true;

# Custom namespaces
$wgExtraNamespaces[500] = "Obsolete";
$wgNamespacesToBeSearchedDefault[500] = false;
$wgExtraNamespaces[501] = "FreeIPAv1";
$wgNamespacesToBeSearchedDefault[501] = true;
$wgExtraNamespaces[502] = "FreeIPAv2";
$wgNamespacesToBeSearchedDefault[502] = true;

# SEO Optimization
#   - namespace 500 (Obsolete) is skipped
$wgSitemapNamespaces = array(0, 1, 2, 6, 8, 10, 12, 14, 501, 502);

# Performance tuning
if ($on_aws) {
    $wgMessageCacheType = CACHE_ACCEL;
    $wgUseLocalMessageCache = true;
    $wgParserCacheType = CACHE_ACCEL;
    $wgEnableSidebarCache = true;
}

#SMTP
$wgSMTP = array (
   'IDHost' => 'www.freeipa.org',
   'host'   => 'localhost',
   'port'   => 25,
   'auth'   => false,
);

# Piwik
$wgPiwikURL = "piwik-freeipaorg.rhcloud.com/";
$wgPiwikIDSite = "1";

# User authentication configuration
# Available choices:
#   - oidc: OpenID Connect based authentication
#   - openid: OpenID based authentication (old and deprecated)
#   - simple: Simple user login
$user_authentication = "simple";

if ($user_authentication == "oidc") {
    # PluggableAuth settings
    # https://www.mediawiki.org/wiki/Extension:PluggableAuth
    wfLoadExtension('PluggableAuth');
    $wgPluggableAuth_EnableAutoLogin = false;
    $wgPluggableAuth_EnableLocalLogin = false;
    $wgPluggableAuth_EnableLocalProperties = false;
    $wgPluggableAuth_Class = 'OpenIDConnect';

    # https://www.mediawiki.org/wiki/Extension:PluggableAuth#Installation
    $wgGroupPermissions['*']['createaccount'] = true;
    $wgGroupPermissions['*']['autocreateaccount'] = true;

    # OpenID_Connect settings
    # https://www.mediawiki.org/wiki/Extension:OpenID_Connect
    wfLoadExtension('OpenIDConnect');
    $wgOpenIDConnect_Config['https://id.fedoraproject.org/openidc/'] = [
        'clientID' => getenv("OIDC_CLIENT_ID"),
        'clientsecret' => getenv("OIDC_CLIENT_SECRET"),
        'name' => "Fedora Authentication",
        'scope' => [ 'openid', 'profile', 'email' ]
    ];
    $wgOpenIDConnect_UseRealNameAsUserName = false;
    $wgOpenIDConnect_UseEmailNameAsUserName = false;
    $wgOpenIDConnect_MigrateUsersByUserName = false;
    $wgOpenIDConnect_MigrateUsersByEmail = true;
    $wgOpenIDConnect_ForceLogout = false;
}
elseif ($user_authentication == "openid")
{
    require_once "$IP/extensions/OpenID/OpenID.php";
    # OpenID
    $wgOpenIDLoginOnly = true;
    $wgOpenIDTrustRoot = 'http://freeipa-org-wiki-freeipa.b9ad.pro-us-east-1.openshiftapps.com/';
    $wgOpenIDMode = 'consumer';
    $wgOpenIDAllowServingOpenIDUserAccounts = false;
    # only allow Fedora for now
    $wgOpenIDProviders = array(
        'fedora' => array(
            'openid-url'  => 'https://id.fedoraproject.org/',
            'large-provider' => true,
            'label' => "Log in using your Fedora account")
        );
    # Do not allow custom names, only those provided from OpenID
    $wgOpenIDAllowNewAccountname = false;
    $wgOpenIDAllowAutomaticUsername = false;
    $wgOpenIDProposeUsernameFromSREG = true;
}
elseif ($user_authentication == "simple")
{
    # no custom settings, yet
}
else
{
    die('Unsupported $user_authentication value: '. $user_authentication);
}
