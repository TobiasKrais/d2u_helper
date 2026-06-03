<?php
$heading = 'REX_VALUE[1]';
$bg_color = \TobiasKrais\D2UHelper\BackendHelper::sanitizeHexColor('REX_VALUE[2]', '#c41e1e'); /** @phpstan-ignore-line */
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
                            echo '<img src="'. rex_url::media($picture) .'" alt="'. rex_escape((string) $media->getTitle(), 'html_attr') .'" loading="lazy">';
                            echo '</div>';
                        }
                    } ?>
					<?php if ('' !== $heading) { /** @phpstan-ignore-line */ ?>
					<h2 class="contact-heading"><?= rex_escape($heading) ?></h2>
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
							<a href="tel:<?= rex_escape(str_replace(' ', '', $phone), 'html_attr') ?>"><?= rex_escape($phone) ?></a>
						</div>
						<?php } ?>
						<?php if ('' !== $email) { /** @phpstan-ignore-line */ ?>
						<div class="contact-info-item">
							<span class="fa-icon fa-envelope contact-info-icon"></span>
							<a href="mailto:<?= rex_escape($email, 'html_attr') ?>"><?= rex_escape($email) ?></a>
						</div>
						<?php } ?>
						<?php if ($contact_article instanceof rex_article) { ?>
						<div class="contact-info-item">
							<span class="fa-icon fa-file contact-info-icon"></span>
							<a href="<?= $contact_article->getUrl() ?>"><?= '' !== $contact_link_text ? rex_escape($contact_link_text) : rex_escape((string) $contact_article->getName()) ?></a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
