<?php
$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$center = 'REX_VALUE[3]' === 'true' ? true : false; /** @phpstan-ignore-line */
$line = 'REX_VALUE[4]' === 'true' ? true : false; /** @phpstan-ignore-line */

// Whitelist HTML tag for headline (defense-in-depth, input is a select)
$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'p'];
$tag = in_array((string) 'REX_VALUE[2]', $allowed_tags, true) ? (string) 'REX_VALUE[2]' : 'h2'; /** @phpstan-ignore-line */
$headline = (string) 'REX_VALUE[1]'; /** @phpstan-ignore-line */

$tagClass = ($center || $line) ? trim(($center ? ' heading-02-1-center' : '') . ($line ? ' heading-02-1-line' : '') . ($center && $line ? ' heading-02-1-line-center' : '')) : '';
?>
<div class="col-12 col-lg-<?= $cols . $offset_lg ?>">
	<<?= $tag ?><?= '' !== $tagClass ? ' class="'. rex_escape($tagClass, 'html_attr') .'"' : '' ?>><?= rex_escape($headline) ?></<?= $tag ?>>
</div>