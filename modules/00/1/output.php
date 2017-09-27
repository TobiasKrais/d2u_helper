<?php
$line = "REX_VALUE[1]";
if(rex::isBackend()) {
	print "Umbruch mit Linie";
}
else {
	print '<div class="col-12 ">';
	print '<div class="spacer'. ($line == 'line' ? ' line' : '') .'">';
	print '</div>';
	print '</div>';
}
?>