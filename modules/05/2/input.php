Aus welcher Sprache soll der Artikel übernommen werden?
<select name="REX_INPUT_VALUE[1]" class="form-control">
	<?php
        foreach (rex_clang::getAll() as $rex_clang) {
            if ($rex_clang->getId() === rex_clang::getCurrentId()) {
                continue;
            }
            echo '<option value="'. $rex_clang->getId() .'" ';
            if ('REX_VALUE[1]' === $rex_clang->getId()) { /** @phpstan-ignore-line */
                echo 'selected="selected" ';
            }
            echo '>'. $rex_clang->getName() .'</option>';
        }
    ?>
</select>