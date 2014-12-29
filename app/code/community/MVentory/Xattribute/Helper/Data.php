<?php
/**
 * Widget Helper
 */
class MVentory_Xattribute_Helper_Data extends Mage_Core_Helper_Abstract
{
    // function replace all spaces to underline    
    public function getTitleAttribute($title){
        $value = preg_replace("/[ ]/", "_", $title);
        return strtolower($value);
    }
}