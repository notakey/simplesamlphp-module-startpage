<?php

$config = SimpleSAML_Configuration::getInstance();
$spconfig = \SimpleSAML_Configuration::getOptionalConfig('module_startpage.php');

$copts = ['s:pageTitle', 's:pageSubtitle', 'b:showLogout', 's:forgotPasswordUrl', 's:helpUrl'];

$t = new SimpleSAML_XHTML_Template($config, 'startpage:splist.tpl.php');
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
            $t->data['config'][$confOpt] = $spconfig->getBoolean($confOpt, "");
            break;
    }
}

$metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

$metaentries = array_merge($metadata->getList('saml20-sp-remote'), $metadata->getList('shib13-sp-remote'), $metadata->getList('adfs-sp-remote'));

foreach ($metaentries as $spentityid => $c) {
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
