<?php

$link_type = 'REX_VALUE[1]';
$forward_url = '';

if ('link' === $link_type) { /** @phpstan-ignore-line */
    $link = 'REX_VALUE[2]';
    if ('' !== $link) { /** @phpstan-ignore-line */
        if (\rex::isBackend()) {
            echo "Weiterleitung zu URL <a href='". $link ."'>". $link .'</a>';
        } else {
            $forward_url = $link;
        }
    }
} elseif ('download' === $link_type) { /** @phpstan-ignore-line */
    $link = rex_url::media('REX_MEDIA[1]');
    if ('' !== $link) { /** @phpstan-ignore-line */
        if (\rex::isBackend()) {
            echo "Weiterleitung zu Datei <a href='". $link ."'>REX_MEDIA[1]</a>";
        } else {
            $forward_url = $link;
        }
    }
} elseif ('d2u_machinery_machine' === $link_type) { /** @phpstan-ignore-line */
    if (rex_addon::get('d2u_machinery')->isAvailable()) {
        $machine_id = (int) 'REX_VALUE[3]';
        if ($machine_id > 0) { /** @phpstan-ignore-line */
            $machine = new Machine($machine_id, rex_clang::getCurrentId());
            if (\rex::isBackend()) {
                echo "Weiterleitung zu D2U Machinen - Maschine <a href='". $machine->getUrl(true) ."'>". $machine->name .'</a>';
            } else {
                $forward_url = $machine->getUrl(true);
            }
        }
    } else {
        echo 'Das D2U Maschinen Addon muss installiert und aktiviert werden.';
    }
} elseif ('d2u_machinery_industry_sector' === $link_type) { /** @phpstan-ignore-line */
    if (rex_plugin::get('d2u_machinery', 'industry_sectors')->isAvailable()) {
        $industry_sector_id = (int) 'REX_VALUE[4]';
        if ($industry_sector_id > 0) { /** @phpstan-ignore-line */
            $industry_sector = new IndustrySector($industry_sector_id, rex_clang::getCurrentId());
            if (\rex::isBackend()) {
                echo "Weiterleitung zu D2U Machinen - Branche <a href='". $industry_sector->getUrl(true) ."'>". $industry_sector->name .'</a>';
            } else {
                $forward_url = $industry_sector->getUrl(true);
            }
        }
    } else {
        echo 'Das D2U Maschinen Addon - Branchen Plugin muss installiert und aktiviert werden.';
    }
} elseif ('d2u_machinery_used_machine' === $link_type) { /** @phpstan-ignore-line */
    if (rex_plugin::get('d2u_machinery', 'used_machines')->isAvailable()) {
        $used_machine_id = (int) 'REX_VALUE[5]';
        if ($used_machine_id > 0) { /** @phpstan-ignore-line */
            $used_machine = new UsedMachine($used_machine_id, rex_clang::getCurrentId());
            if (\rex::isBackend()) {
                echo "Weiterleitung zu D2U Machinen - Branche <a href='". $used_machine->getUrl(true) ."'>". $used_machine->name .'</a>';
            } else {
                $forward_url = $used_machine->getUrl(true);
            }
        }
    } else {
        echo 'Das D2U Maschinen Addon - Gebrauchtmaschinen Plugin muss installiert und aktiviert werden.';
    }
} elseif ('d2u_immo_property' === $link_type) { /** @phpstan-ignore-line */
    if (rex_addon::get('d2u_immo')->isAvailable()) {
        $property_id = (int) 'REX_VALUE[6]';
        if ($property_id > 0) { /** @phpstan-ignore-line */
            $property = new \D2U_Immo\Property($property_id, rex_clang::getCurrentId());
            if (\rex::isBackend()) {
                echo "Weiterleitung zu D2U Immobilien - Immobilie <a href='". $property->getUrl(true) ."'>". $property->name .'</a>';
            } else {
                $forward_url = $property->getUrl(true);
            }
        }
    } else {
        echo 'Das D2U Immobilien Addon muss installiert und aktiviert werden.';
    }
} elseif ('d2u_courses_category' === $link_type) { /** @phpstan-ignore-line */
    if (rex_addon::get('d2u_courses')->isAvailable()) {
        $category_id = (int) 'REX_VALUE[6]';
        if ($category_id > 0) { /** @phpstan-ignore-line */
            $category = new \D2U_Courses\Category($category_id);
            if (\rex::isBackend()) {
                echo "Weiterleitung zu D2U Veranstaltungen - Kategorie <a href='". $category->getUrl(true) ."'>". $category->name .'</a>';
            } else {
                if ($category->isOnline()) {
                    $forward_url = $category->getUrl(true);
                }
            }
        }
    } else {
        echo 'Das D2U Veranstaltungen Addon muss installiert und aktiviert werden.';
    }
} else { // Backward compatibility module Version <= 3
    $article_id = (int) 'REX_LINK[1]';
    $params = htmlspecialchars_decode('REX_VALUE[8]');
    $anchor = 'REX_VALUE[7]';
    if ($article_id > 0 && rex_article::get($article_id) instanceof rex_article) { /** @phpstan-ignore-line */
        if (\rex::isBackend()) {
            echo "Weiterleitung zu Artikel: <a href='". rex_url::backendPage('content/edit', ['article_id' => $article_id, 'clang' => rex_clang::getCurrentId()]) ."'>"
                . rex_article::get($article_id)->getValue('name') .' (Artikel ID '. $article_id .')</a>';
            if ('' !== $params) {
                echo '<br>Zusätzliche Parameter: '. $params;
            }
            if ('' !== $anchor) { /** @phpstan-ignore-line */
                echo '<br>Name Anker: '. $anchor;
            }
        } else {
            $link = rex_getUrl($article_id);
            $forward_url = $link . ('' !== $params ? (false === strstr($link, '?') ? '?' : '&') . $params : '') . ('' !== $anchor ? '#'. $anchor : ''); /** @phpstan-ignore-line */
        }
    }
}

// Forward
if (!\rex::isBackend() && '' !== $forward_url) { /** @phpstan-ignore-line */
    header('Location: '. $forward_url);
    header('Status: 301');
    exit;
}
