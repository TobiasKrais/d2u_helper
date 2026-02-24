<?php
$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' me-lg-auto ms-lg-auto ' : ''; /** @phpstan-ignore-line */

$center = 'REX_VALUE[3]' === 'true' ? true : false; /** @phpstan-ignore-line */
$line = 'REX_VALUE[4]' === 'true' ? true : false; /** @phpstan-ignore-line */

?>
<div class="col-12 col-lg-<?= $cols . $offset_lg ?>">
	<REX_VALUE[2] class="REX_VALUE[2]<?= ($center ? ' heading-02-1-center' : '') . ($line ? ' heading-02-1-line' : '') . ($center && $line ? ' heading-02-1-line-center' : '')  /** @phpstan-ignore-line */ ?>">REX_VALUE[1]</REX_VALUE[2]>
</div>