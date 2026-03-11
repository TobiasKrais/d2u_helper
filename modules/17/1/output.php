<?php

use FriendsOfRedaxo\GooglePlaces\Place;
use FriendsOfRedaxo\GooglePlaces\Review;

$place_id = (int) 'REX_VALUE[1]' ?: 1; /** @phpstan-ignore-line */
$heading = 'REX_VALUE[2]';
$place = Place::get($place_id);
$limit = 10;

if ($place === null) {
    return;
}

$reviewCollection = Review::findFilter($place->getId(), -1, 0, 0, 'publishdate', 'DESC', Review::STATUS_VISIBLE);
$reviews = array_filter($reviewCollection->toArray(), static fn (Review $review) => '' !== trim($review->getText()));

if (count($reviews) > $limit) {
	shuffle($reviews);
	$reviews = array_slice($reviews, 0, $limit);
}

if (count($reviews) > 0) {
?>
<div class="col-12">
	<section class="googleplaces-section py-5">
		<div class="container">
			<?php if ('' !== $heading) { /** @phpstan-ignore-line */ ?>
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h2 class="mb-0"><?= rex_escape($heading) ?></h2>
				<?php if (count($reviews) > 1) { ?>
				<div class="googleplaces-indicators">
					<?php $j = 0; foreach ($reviews as $review) { ?>
					<button type="button" data-bs-target="#googleplacesCarousel" data-bs-slide-to="<?= $j ?>"<?= 0 === $j ? ' class="active" aria-current="true"' : '' ?> aria-label="Review <?= $j + 1 ?>"></button>
					<?php ++$j; } ?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if (count($reviews) > 1) { ?>
			<div id="googleplacesCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="10000">
				<div class="carousel-inner">
					<?php $index = 0; foreach ($reviews as $review) {
					    /** @var Review $review */ ?>
					<div class="carousel-item<?= 0 === $index ? ' active' : '' ?>">
						<div class="googleplaces-quote">
							<p class="googleplaces-text">&ldquo;<?= nl2br(rex_escape($review->getText())) ?>&rdquo;</p>
							<p class="googleplaces-author"><?= rex_escape($review->getAuthorName()) ?></p>
						</div>
					</div>
					<?php ++$index; } ?>
				</div>
			</div>
			<?php } else {
			    /** @var Review $review */
			    $review = $reviews[0];
			    if ($review instanceof Review) { ?>
			<div class="googleplaces-quote">
				<p class="googleplaces-text">&ldquo;<?= nl2br(rex_escape($review->getText())) ?>&rdquo;</p>
				<p class="googleplaces-author"><?= rex_escape($review->getAuthorName()) ?></p>
			</div>
			<?php }
			} ?>
		</div>
	</section>
</div>
<?php
}