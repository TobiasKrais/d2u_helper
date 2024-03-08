<?php

namespace TobiasKrais\D2UHelper;

/**
 * Support for D2U Translation Helper.
 */
interface ITranslationHelper
{
    /**
     * Get objects concerning translation updates.
     * @param int $clang_id Redaxo language ID
     * @param string $type 'update' or 'missing'
     * @return mixed[] Array with objects of the implementing class
     */
    public static function getTranslationHelperObjects($clang_id, $type);
}
