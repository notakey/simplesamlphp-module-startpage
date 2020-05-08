<?php

$config = \SimpleSAML\Configuration::getInstance();
$spconfig = \SimpleSAML\Configuration::getOptionalConfig('module_startpage.php');

$metadata = \SimpleSAML\Metadata\MetaDataStorageHandler::getMetadataHandler();
$t = new \SimpleSAML\XHTML\Template($config, 'startpage:splist.tpl.php');

if ($spconfig->getBoolean('authenticate', true)) {
    $idp_array = $metadata->getList('saml20-idp-hosted');

    $auth_source = '';
    $userid_attr = 'uid';

    foreach ($idp_array as $idp) {
        if ($idp['host'] == '__DEFAULT__') {
            $auth_source = $idp['auth'];
            if (isset($idp['userid.attribute'])) {
                $userid_attr = $idp['userid.attribute'];
            }
        }
    }

    if (empty($auth_source)) {
        throw new \Exception('Missing default IdP configuration');
    }

    $auth = new \SimpleSAML\Auth\Simple($auth_source);
    $auth->requireAuth();

    $attributes = $auth->getAttributes();
    if (!isset($attributes[$userid_attr])) {
        throw new \Exception('Invalid default IdP configuration');
    }

    $t->data['logout_url'] = $auth->getLogoutURL();
    $t->data['username'] = $attributes[$userid_attr][0];
}

// \SimpleSAML\Session::useTransientSession();

$copts = ['s:pageTitle', 's:pageSubtitle', 'b:showLogout', 's:forgotPasswordUrl', 's:helpUrl', 'b:authenticate'];


$t->data['header'] = $t->t('{startpage:startpage:splist_header}');
$t->data['pageid'] = 'splist';

$t->data['head']  = '<link rel="stylesheet" type="text/css" href="/' . $t->data['baseurlpath'] . 'module/startpage/resources/style.css" />';

$t->data['spl'] = array();

$t->data['config'] = array();

foreach ($copts as $c) {
    list($dataType, $confOpt) = explode(':', $c, 2);
    switch ($dataType) {
        case "s":
            $t->data['config'][$confOpt] = $spconfig->getString($confOpt, "");
            break;
        case "b":
            $t->data['config'][$confOpt] = $spconfig->getBoolean($confOpt, false);
            break;
    }
}



$metaentries = array_merge($metadata->getList('saml20-sp-remote'), $metadata->getList('shib13-sp-remote'), $metadata->getList('adfs-sp-remote'));

foreach ($metaentries as $spentityid => $c) {
    if (!isset($c['startpage.link'])) {
        continue;
    }

    // TODO
    // Fix to be multilang compatible
    if (is_array($c['name'])) {
        $name = array_pop($c['name']);
    } else {
        $name = $c['name'];
    }

    if (is_array($c['startpage.logo'])) {
        $logo = '/userlogos/' . $c['startpage.logo']['en'];
    } else {
        if (isset($c['startpage.logo']) && !empty($c['startpage.logo']) && $c['startpage.logo'] != 'nologo.png') {
            $logo = '/userlogos/' . $c['startpage.logo'];
        } else {
            $logo = '/sso/module.php/startpage/resources/sad_cat_sso.png';
        }
    }

    $t->data['spl'][] = array('link' => $c['startpage.link'], 'name' => $name, 'logo' => $logo);
}

$t->show();
