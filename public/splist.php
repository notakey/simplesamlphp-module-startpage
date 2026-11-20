<?php

declare(strict_types=1);

use SimpleSAML\Auth;
use SimpleSAML\Configuration;
use SimpleSAML\Metadata\MetaDataStorageHandler;
use SimpleSAML\Module;
use SimpleSAML\XHTML\Template;

$config = Configuration::getInstance();
$spconfig = Configuration::getOptionalConfig('module_startpage.php');

$metadata = MetaDataStorageHandler::getMetadataHandler();
$t = new Template($config, 'startpage:splist.twig');

$t->data['logout_url'] = null;
$t->data['username'] = null;

if ($spconfig->getOptionalBoolean('authenticate', true)) {
    $idp_array = $metadata->getList('saml20-idp-hosted');

    $auth_source = '';
    $userid_attr = 'uid';

    foreach ($idp_array as $idp) {
        if (isset($idp['host']) && $idp['host'] == '__DEFAULT__') {
            $auth_source = $idp['auth'];
            if (isset($idp['userid.attribute'])) {
                $userid_attr = $idp['userid.attribute'];
            }
        }
    }

    if (empty($auth_source)) {
        throw new Exception('Missing default IdP configuration');
    }

    $auth = new Auth\Simple($auth_source);
    $auth->requireAuth();

    $attributes = $auth->getAttributes();
    if (!isset($attributes[$userid_attr])) {
        throw new Exception('Invalid default IdP configuration');
    }

    $t->data['logout_url'] = $auth->getLogoutURL();
    $t->data['username'] = $attributes[$userid_attr][0];
}

$t->data['config'] = [
    'pageTitle' => $spconfig->getOptionalString('pageTitle', ''),
    'pageSubtitle' => $spconfig->getOptionalString('pageSubtitle', ''),
    'showLogout' => $spconfig->getOptionalBoolean('showLogout', false),
    'forgotPasswordUrl' => $spconfig->getOptionalString('forgotPasswordUrl', ''),
    'helpUrl' => $spconfig->getOptionalString('helpUrl', ''),
    'authenticate' => $spconfig->getOptionalBoolean('authenticate', false),
];

$defaultLogo = Module::getModuleURL('startpage/assets/resources/sad_cat_sso.png');

$metaentries = array_merge(
    $metadata->getList('saml20-sp-remote'),
    $metadata->getList('adfs-sp-remote'),
);

$spl = [];
foreach ($metaentries as $c) {
    if (empty($c['startpage.link'])) {
        continue;
    }

    // TODO: make multilang compatible
    $name = $c['name'] ?? $c['entityid'];
    if (is_array($name)) {
        $name = array_pop($name);
    }

    $logo = $defaultLogo;
    if (isset($c['startpage.logo']) && is_array($c['startpage.logo'])) {
        $logo = '/userlogos/' . ($c['startpage.logo']['en'] ?? reset($c['startpage.logo']));
    } elseif (!empty($c['startpage.logo']) && $c['startpage.logo'] != 'nologo.png') {
        $logo = '/userlogos/' . $c['startpage.logo'];
    }

    $spl[] = ['link' => $c['startpage.link'], 'name' => (string) $name, 'logo' => $logo];
}

$t->data['spl'] = $spl;

$t->send();
