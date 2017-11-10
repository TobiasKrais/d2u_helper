<?php
/**
 * Rewrite scheme for www.kaltenbach.com
 */
class d2u_yrewrite_scheme extends rex_yrewrite_scheme {
	/**
	 * Rewrites String
	 * @param string $string String to rewrite
	 * @param int $clang Redaxo language ID
	 * @return string Rewritten string
	 */
    public function normalize($string, $clang = 0) {
		$string = str_replace(
			['Ä',  'Ö',  'Ü',  'ä',  'ö',  'ü',  'ß',  'À', 'à', 'Á', 'á', 'ç', 'È', 'è', 'É', 'é', 'ë', 'Ì', 'ì', 'Í', 'í', 'Ï', 'ï', 'Ò', 'ô', 'ò', 'Ó', 'ó', 'Ù', 'ù', 'Ú', 'ú', 'Č', 'č', 'Ł', 'ł', 'ž', '/', '®', '©', '™', ':', '#', '$', '%', '&', '(', ')', '*', '+', ',', '.', '/', '!', ';', '<', '=', '>', '?', '@', '[', ']', '^', '_', '`', '{', '}', '~', '–', '’', '¿', '”', '“', ' '],
			['Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', 'ss', 'A', 'a', 'A', 'a', 'c', 'E', 'e', 'E', 'e', 'e', 'I', 'i', 'I', 'i', 'I', 'i', 'O', 'o', 'o', 'O', 'o', 'U', 'u', 'U', 'u', 'C', 'c', 'L', 'l', 'z', '-', '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '',  '-'],
			$string
		);
		
		$parent_string = parent::normalize($string);
		
		// In case settings require URL encode or normalizing the standard way failed
		if(rex_config::get('d2u_helper', 'rewrite_scheme_clang_'. $clang, 'standard') == 'urlencode' || ($parent_string == "" || $parent_string == "-")) {
			$string = strtolower(trim($string));
			$string = preg_replace('/[+-]+/', '-', $string);

			return $string;
		}

        return $parent_string;
    }
}