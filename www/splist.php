<?php

$config = SimpleSAML_Configuration::getInstance();


$t = new SimpleSAML_XHTML_Template($config, 'startpage:splist.tpl.php');
$t->data['header'] = $t->t('{startpage:startpage:splist_header}');
$t->data['pageid'] = 'splist';

$t->data['head']  = '<link rel="stylesheet" type="text/css" href="/'. $t->data['baseurlpath']. 'module/startpage/resources/style.css" />';

$t->data['spl'] = array();

$metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

$metaentries = array_merge($metadata->getList('saml20-sp-remote'), $metadata->getList('shib13-sp-remote'), $metadata->getList('adfs-sp-remote'));

foreach($metaentries as $spentityid=>$c){
	if(empty($c['startpage.logo'])){
		continue;
	}

	// TODO
	// Fix to be multilang compatible
	if(is_array($c['name'])){
		$name = $c['name']['en'];
	}else{
		$name = $c['name'];
	}

	if(is_array($c['startpage.logo'])){
		$logo = $c['startpage.logo']['en'];
	}else{
		$logo = $c['startpage.logo'];
	}

	$t->data['spl'][] = array('link' => $c['startpage.link'], 'name' => $name, 'logo' => $logo);
}

$t->show();

