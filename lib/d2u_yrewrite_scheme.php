<?php
/**
 * Rewrite scheme allowing urls with russian, chinese or other letters
 */
class d2u_yrewrite_scheme extends rex_yrewrite_scheme {
	/**
	 * Rewrites String
	 * @param string $string String to rewrite
	 * @param int $clang Redaxo language ID
	 * @return string Rewritten string
	 */
    public function normalize($string, $clang = 0) {
		$final_string = parent::normalize(strip_tags(trim($string)));

		// In case settings require URL encode or normalizing the standard way failed
		if(($clang > 0 && rex_config::get('d2u_helper', 'rewrite_scheme_clang_'. $clang, 'standard') == 'urlencode') || ($final_string == "" || $final_string == "-")) {
			$string = str_replace(
				['й'],
				['и'],
				mb_strtolower($string)
			);
			$final_string = preg_replace('/[+-]+/', '-', $string);
		}

		return $final_string;
    }
}