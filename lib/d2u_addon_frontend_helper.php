<?php
/**
 * Offers methods for Redaxo frontend. Mostly for Addons published by
 * www.design-to-use.de.
 */
class d2u_addon_frontend_helper {
	/**
	 * Formats a string for Frontend: redaxo:// URLs are replaced und if needed
	 * MarkItUp Editor formats are added.
	 * @param string $html string formatted by editor chosen in D2U Helper settings
	 * @return string Correctly formatted HTML String
	 */
	public static function prepareEditorField($html) {
		if(rex_config::get('d2u_helper', 'editor', '') == 'markitup' && rex_addon::get('markitup')->isAvailable()) {
			$html = markitup::parseOutput ('markdown', $html);
		}
		else if(rex_config::get('d2u_helper', 'editor', '') == 'markitup_textile' && rex_addon::get('markitup')->isAvailable()) {
			$html = markitup::parseOutput ('textile', $html);
		}

		// Convert redaxo://123 to URL
		$final_html = preg_replace_callback(
					'@redaxo://(\d+)(?:-(\d+))?/?@i',
					create_function(
							'$matches',
							'return rex_getUrl($matches[1], isset($matches[2]) ? $matches[2] : "");'
					),
					$html
			);
		
		return $final_html;
	}
}
