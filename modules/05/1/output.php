<?php
$link_type = "REX_VALUE[1]";
$forward_url = "";

if($link_type == "link") {
	$link = "REX_VALUE[2]";
	if($link != "") {
		if(\rex::isBackend()) {
			print "Weiterleitung zu URL <a href='". $link ."'>". $link ."</a>";
		}
		else {
			$forward_url = $link;
		}
	}
}
else if($link_type == "download") {
	$link = rex_url::media("REX_MEDIA[1]");
	if($link != "") {
		if(\rex::isBackend()) {
			print "Weiterleitung zu Datei <a href='". $link ."'>REX_MEDIA[1]</a>";
		}
		else {
			$forward_url = $link;
		}
	}
}
else if($link_type == "d2u_machinery_machine") {
	if(rex_addon::get('d2u_machinery')->isAvailable()) {
		$machine_id = "REX_VALUE[3]";
		if($machine_id > 0) {
			$machine = new Machine($machine_id, rex_clang::getCurrentId());
			if(\rex::isBackend()) {
				print "Weiterleitung zu D2U Machinen - Maschine <a href='". $machine->getUrl(TRUE) ."'>". $machine->name ."</a>";
			}
			else {
				$forward_url = $machine->getUrl(TRUE);
			}
		}
	}
	else {
		print "Das D2U Maschinen Addon muss installiert und aktiviert werden.";
	}
}
else if($link_type == "d2u_machinery_industry_sector") {
	if(rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
		$industry_sector_id = "REX_VALUE[4]";
		if($industry_sector_id > 0) {
			$industry_sector = new IndustrySector($industry_sector_id, rex_clang::getCurrentId());
			if(\rex::isBackend()) {
				print "Weiterleitung zu D2U Machinen - Branche <a href='". $industry_sector->getUrl(TRUE) ."'>". $industry_sector->name ."</a>";
			}
			else {
				$forward_url = $industry_sector->getUrl(TRUE);
			}
		}
	}
	else {
		print "Das D2U Maschinen Addon - Branchen Plugin muss installiert und aktiviert werden.";
	}
}
else if($link_type == "d2u_machinery_used_machine") {
	if(rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
		$used_machine_id = "REX_VALUE[5]";
		if($used_machine_id > 0) {
			$used_machine = new UsedMachine($used_machine_id, rex_clang::getCurrentId());
			if(\rex::isBackend()) {
				print "Weiterleitung zu D2U Machinen - Branche <a href='". $used_machine->getUrl(TRUE) ."'>". $used_machine->name ."</a>";
			}
			else {
				$forward_url = $used_machine->getUrl(TRUE);
			}
		}
	}
	else {
		print "Das D2U Maschinen Addon - Gebrauchtmaschinen Plugin muss installiert und aktiviert werden.";
	}
}
else if($link_type == "d2u_immo_property") {
	if(rex_addon::get('d2u_immo')->isAvailable()) {
		$property_id = "REX_VALUE[6]";
		if($property_id > 0) {
			$property = new Property($property_id, rex_clang::getCurrentId());
			if(\rex::isBackend()) {
				print "Weiterleitung zu D2U Immobilien - Immobilie <a href='". $property->getUrl(TRUE) ."'>". $property->name ."</a>";
			}
			else {
				$forward_url = $property->getUrl(TRUE);
			}
		}
	}
	else {
		print "Das D2U Immobilien Addon muss installiert und aktiviert werden.";
	}
}
else { // Backward compatibility module Version <= 3
	$article_id = "REX_LINK[1]";
	if($article_id > 0 && rex_article::get($article_id) instanceof rex_article) {
		if(\rex::isBackend()) {
			print "Weiterleitung zu Artikel: <a href='". rex_url::backendPage('content/edit', ['article_id'=>$article_id, 'clang'=> rex_clang::getCurrentId()]) ."'>"
			. rex_article::get($article_id)->getValue('name') ." (Artikel ID ". $article_id .")</a>";
		}
		else {
			$forward_url = rex_getUrl($article_id);
		}
	}
}

// Forward
if(!\rex::isBackend() && $forward_url != "") {
	header('Location: '. $forward_url);
	header("Status: 301");
   	exit();
}