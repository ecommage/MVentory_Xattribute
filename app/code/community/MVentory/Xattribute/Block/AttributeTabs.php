<?php

class MVentory_Xattribute_Block_AttributeTabs extends Mage_Core_Block_Abstract
    implements Mage_Widget_Block_Interface
{
    protected function _toHtml()
    {
        $html = $this->_prepareHtml();

        return $html;
    }

    /*
     * prepare html before show on frontend
     * @return 
     */
    protected function _prepareHtml()
    {
        $html = '';
        $data = null;

        try{
            // get id and sku of product that are inputed
            $entityId = trim($this->getData('entity_id'));
            $sku = trim($this->getData('sku'));

            // get product by id or sku that are inputed
            $_product = $this->getProductByIdOrSku($entityId,$sku);

            // get array attributes are inputed
            $attributeCodeStr = trim($this->getData('attribute_code'));
            $attributeCodeArr = explode(",", $attributeCodeStr);

            // check for null
            if(!is_null($_product) && count($attributeCodeArr)>0)
            {
                // get contents to show on tab
                $data = $this->_prepareAttributeContent($_product, $attributeCodeArr);

                // assign data to template to show tab
                $block = $this->getLayout()->createBlock('core/template')
                    ->setTemplate('mventory/xattribute/attributetabs.phtml')
                    ->assign('data', $data)
                ;

                $html = $block->toHtml();

                unset($block);
            }

            unset($data);
            unset($_product);
        } catch(Exception $e){
            Mage::log($e->getMessage());
        }

        return $html;
    }

    /*
     * Get product by ID or SKU
     * @param string productId
     * @param string sku
     * @return Catalog_Model_Product
     */
    protected function getProductByIdOrSku($productId=null, $sku=null){

        $tempProduct = null;

        try {
            // check productId for null
            // if productId not null, search product by productId
            if(!is_null($productId)) {
                $tempProduct = Mage::getModel('catalog/product')->load($productId);

                // if product has been found, return product
                if(!is_null($tempProduct->getId()) && !empty($tempProduct->getId())) {
                    return $tempProduct;
                } else {
                    $tempProduct = null;
                }
            }

            // check sku for null and get product by sku if product which is searched by id hasn't been found.
            if(!is_null($sku) && is_null($tempProduct)) {
                $tempProduct = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);

                // return product if product has been found by sku
                if(!is_null($tempProduct->getId()) && !empty($tempProduct->getId())) {
                    return $tempProduct;
                } else {
                    $tempProduct = null;
                }
            }
        } catch(Exception $e) {
            Mage::log($e->getMessage());
        }

        return $tempProduct;
    }

    /*
     * Get all html of all attributes which are inputed
     * @param Catalog_Model_Product $_product
     * @param array attributeCodeArr
     * @return string
     */
    protected function _prepareAttributeContent($_product, $attributeCodeArr){

        $data = array();

        try{
            foreach($attributeCodeArr as $attrCode){
                // check attribute code which inputed by user, exists in product
                $attribute = $_product->getResource()->getAttribute($attrCode);
                if ($attrCode != '' && isset($attribute) && $attribute) {
                    // get data by Attribute Type: example: text, select, textarea, etc...
                    $temp = $this->_getHtmlByAttrType($attribute, $_product);
                    if (!is_null($temp)) {
                        $data[] = $temp[0];
                    }
                }

                unset($attribute);
                unset($temp);
            }
        } catch(Exception $e){
            Mage::log($e->getMessage());
        }

        return count($data) > 0 ? $data : null;
    }

    /*
     * Get html by attribute type, example: text, textarea, select, checkbox
     * @param Catalog_Model_Resource_Product_Attribute $attribute
     * @param Catalog_Model_Resource_Product _product
     * @return string
     */
    protected function _getHtmlByAttrType($attribute, $_product)
    {
        $data = array();

        try{
            //get attribute type, get attribute title, get attribute value of attribute
            $attrType = $attribute->getData('frontend_input');
            $attrTitle = $this->__($attribute->getStoreLabel());
            $attrValue = $attribute->getFrontend()->getValue($_product);

            if (is_null($attrValue) || empty($attrValue)) {
                return null;
            }

            // get data with attribute type
            switch($attrType) {
                case 'checkbox':
                case 'radio':
                case 'select':
                case 'multiselect':
                case 'text':
                case 'textarea':
                    $data[] = array(
                        'tabTitle'=>$attrTitle,
                        'tabContent'=>$attrValue
                    );
                    break;

                default:
                    break;
            }

            unset($attrValue);
            unset($attrTitle);
            unset($attrType);
        } catch(Exception $e){
            Mage::log($e->getMessage());
        }

        return $data;
    }
}