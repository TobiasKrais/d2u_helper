<?php
$seconds = 'REX_VALUE[2]' ?: 20;
?>
<div class="col-12">
	<div class="marquee text-intro subtle">
		<span style="animation: marquee <?= $seconds ?>s linear infinite; -moz-animation: marquee <?= $seconds ?>s linear infinite;-webkit-animation: marquee <?= $seconds ?>s linear infinite;">
			REX_VALUE[id=1 output=html]
		</span>
	</div>
</div>