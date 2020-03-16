{**
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
*}

{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
	<fieldset>
		<legend>{l s='Information' mod='quantitydiscountpro'}</legend>
		<div>{l s='You can contact developers at' mod='quantitydiscountpro'} <a href="http://addons.prestashop.com/contact-community.php?id_product=9129" target="_blank">http://addons.prestashop.com/contact-community.php?id_product=9129</a></div>
	</fieldset>
	<br />
{else}
	<div class="panel col-lg-12 quantity-discount-pro">
		<div class="panel-heading">
			{l s='Information' mod='quantitydiscountpro'}
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<i class="icon-pencil"></i> {l s='You can contact developers at' mod='quantitydiscountpro'} <a href="http://addons.prestashop.com/contact-community.php?id_product=9129" target="_blank">http://addons.prestashop.com/contact-community.php?id_product=9129</a>
			</div>
			<div class="form-group">
				<i class="icon-book"></i> <a href="{$base_url|escape:'html':'UTF-8'}modules/{$module_name|escape:'html':'UTF-8'}/readme_en.pdf" target="_blank">{l s='Module manual' mod='quantitydiscountpro'}</a>
			</div>
		</div>
	</div>
	<div style="clear:both"></div>
{/if}