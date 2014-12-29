#MVentory_XAttribute

##Features

* Create a widget that allows the admin passes product id (or product sku) and product attribute code to display product attributes as tabs

##Installation

1. Upload files to your magento. There is also a modman file if you want to use modman.
1.1 If you use modman, just upload the files to  .modman/Xattribute/ and then issue a
`$./modman deploy Xattribute`.
2. Go to CMS > Pages > Content and click the insert widget button. A dialog will appear, select "CAttribute Tab" from the list.
You can then enter your product_is (or product sku) and attribute codes (separated by comma) and finally click Insert Widget. If viewing the Content tab in text mode you should now see a line like {{widget type="xattribute/attributeTabs" entity_id="721576....." attribute_code="url_key,status,sku"}} or {{widget type="xattribute/attributeTabs" sku="ABC....." attribute_code="url_key,status,sku"}}. Save the page.
3. Go to the cms page of your site, the product attribute tabs should now be visible.