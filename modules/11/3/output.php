<?php
$heading = 'REX_VALUE[1]';
$bg_color = '' === 'REX_VALUE[2]' ? '#c41e1e' : 'REX_VALUE[2]'; /** @phpstan-ignore-line */
$picture = 'REX_MEDIA[1]';
$description = 'REX_VALUE[id=3 output=html]';
$phone = 'REX_VALUE[4]';
$email = 'REX_VALUE[5]';
$contact_link_id = (int) 'REX_LINK[1]';
$contact_link_text = 'REX_VALUE[6]';

$contact_article = rex_article::get($contact_link_id);
?>
<div class="col-12">
	<section class="contact-section" style="background-color: <?= $bg_color ?>;">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-12 col-lg-6">
					<?php if ('' !== $picture) { /** @phpstan-ignore-line */
                        $media = rex_media::get($picture);
                        if ($media instanceof rex_media) {
                            echo '<div class="contact-icon mb-3">';
                            echo '<img src="'. rex_url::media($picture) .'" alt="'. $media->getTitle() .'" loading="lazy">';
                            echo '</div>';
                        }
                    } ?>
					<?php if ('' !== $heading) { /** @phpstan-ignore-line */ ?>
					<h2 class="contact-heading"><?= $heading ?></h2>
					<?php } ?>
					<?php if ('' !== strip_tags($description)) { /** @phpstan-ignore-line */ ?>
					<div class="contact-description"><?= $description ?></div>
					<?php } ?>
				</div>
				<div class="col-12 col-lg-6">
					<div class="contact-info-list">
						<?php if ('' !== $phone) { /** @phpstan-ignore-line */ ?>
						<div class="contact-info-item">
							<span class="fa-icon fa-phone contact-info-icon"></span>
							<a href="tel:<?= str_replace(' ', '', $phone) ?>"><?= $phone ?></a>
						</div>
						<?php } ?>
						<?php if ('' !== $email) { /** @phpstan-ignore-line */ ?>
						<div class="contact-info-item">
							<span class="fa-icon fa-envelope contact-info-icon"></span>
							<a href="mailto:<?= $email ?>"><?= $email ?></a>
						</div>
						<?php } ?>
						<?php if ($contact_article instanceof rex_article) { ?>
						<div class="contact-info-item">
							<span class="fa-icon fa-file contact-info-icon"></span>
							<a href="<?= $contact_article->getUrl() ?>"><?= '' !== $contact_link_text ? $contact_link_text : $contact_article->getName() ?></a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
