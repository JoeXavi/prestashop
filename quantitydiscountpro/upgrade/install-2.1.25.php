<?php
/**
* Quantity Discount Pro
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2019 idnovate.com
*  @license   See above
*/

function upgrade_module_2_1_25($module)
{
    if (version_compare(_PS_VERSION_, '1.7', '>=') && $module->active) {
        if (!file_exists(_PS_OVERRIDE_DIR_.'classes/checkout')) {
            mkdir(_PS_OVERRIDE_DIR_.'classes/checkout', 0777, true);
        }

        $module->copyOverrideFolder();

        $module->removeOverride('CheckoutAddressesStep');
        $module->addOverride('CheckoutAddressesStep');
    }

    return true;
}
