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

<div id="message_{$message->id_quantity_discount_rule_message|intval}_container" class="col-lg-12 message">
	<input type="hidden" name="message[{$message->id_quantity_discount_rule_message|intval}]" value="{$message->id_quantity_discount_rule_message|intval}" />
	<div class="row">
		<div class="col-lg-2">
			<a class="btn btn-default" href="javascript:removeMessage('{$message->id_quantity_discount_rule_message|intval}');">
				<i class="icon-remove text-danger"></i> {l s='Remove message' mod='quantitydiscountpro'}
			</a>
		</div>
		<div class="col-lg-10">
			<div class="form-group">
				<label class="control-label col-lg-1">{l s='Hook' mod='quantitydiscountpro'}</label>
				<div class="col-lg-11">
					<select name="message_hook_name[{$message->id_quantity_discount_rule_message|intval}]">
						<option value="0">{l s='-- Choose --' mod='quantitydiscountpro'}</option>
						<optgroup label="{l s='Product page hooks' mod='quantitydiscountpro'}">
							<option value="hookDisplayLeftColumnProduct" {if isset($message->hook_name) && $message->hook_name == hookDisplayLeftColumnProduct}selected="selected"{/if}>{l s='displayLeftColumnProduct (Display in left column on product page)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayRightColumnProduct" {if isset($message->hook_name) && $message->hook_name == hookDisplayRightColumnProduct}selected="selected"{/if}>{l s='displayRightColumnProduct (Display in right column on product page)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayProductButtons" {if isset($message->hook_name) && $message->hook_name == hookDisplayProductButtons}selected="selected"{/if}>{l s='displayProductButtons (Display in action buttons on product page)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayProductTab" {if isset($message->hook_name) && $message->hook_name == hookDisplayProductTab}selected="selected"{/if}>{l s='displayProductTab (Display title in product page tabs)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayProductTabContent" {if isset($message->hook_name) && $message->hook_name == hookDisplayProductTabContent}selected="selected"{/if}>{l s='displayProductTabContent (Display content in product page tabs)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayFooterProduct" {if isset($message->hook_name) && $message->hook_name == hookDisplayFooterProduct}selected="selected"{/if}>{l s='displayFooterProduct (Display under the product description)' mod='quantitydiscountpro'}</option>
						</optgroup>
						<optgroup label="{l s='Category page hooks' mod='quantitydiscountpro'}">
							<option value="hookDisplayProductPriceBlock" {if isset($message->hook_name) && $message->hook_name == hookDisplayProductPriceBlock}selected="selected"{/if}>{l s='displayProductPriceBlock (Display in each product)' mod='quantitydiscountpro'}</option>
						</optgroup>
						<optgroup label="{l s='Common hooks' mod='quantitydiscountpro'}">
							<option value="hookDisplayLeftColumn" {if isset($message->hook_name) && $message->hook_name == hookDisplayLeftColumn}selected="selected"{/if}>{l s='displayLeftColumn (Display in left column)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayRightColumn" {if isset($message->hook_name) && $message->hook_name == hookDisplayRightColumn}selected="selected"{/if}>{l s='displayRightColumn (Display in right column)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayTop" {if isset($message->hook_name) && $message->hook_name == hookDisplayTop}selected="selected"{/if}>{l s='displayTop (Display in top)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayFooter" {if isset($message->hook_name) && $message->hook_name == hookDisplayFooter}selected="selected"{/if}>{l s='displayFooter (Display in footer)' mod='quantitydiscountpro'}</option>
						</optgroup>
						<optgroup label="{l s='Shopping Cart hooks' mod='quantitydiscountpro'}">
							<option value="hookShoppingCart" {if isset($message->hook_name) && $message->hook_name == hookShoppingCart}selected="selected"{/if}>{l s='shoppingCart (Display in checkout)' mod='quantitydiscountpro'}</option>
							<option value="hookShoppingCartExtra" {if isset($message->hook_name) && $message->hook_name == hookShoppingCartExtra}selected="selected"{/if}>{l s='shoppingCartExtra (Display in checkout)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayBeforeCarrier" {if isset($message->hook_name) && $message->hook_name == hookDisplayBeforeCarrier}selected="selected"{/if}>{l s='displayBeforeCarrier (Display before carrier list)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayPaymentTop" {if isset($message->hook_name) && $message->hook_name == hookDisplayPaymentTop}selected="selected"{/if}>{l s='displayPaymentTop (Display before payment methods)' mod='quantitydiscountpro'}</option>
						</optgroup>
						<optgroup label="{l s='Custom hooks' mod='quantitydiscountpro'}">
							<option value="hookDisplayQuantityDiscountProCustom1" {if isset($message->hook_name) && $message->hook_name == hookDisplayQuantityDiscountProCustom1}selected="selected"{/if}>{l s='displayQuantityDiscountProCustom1 (Custom 1)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayQuantityDiscountProCustom2" {if isset($message->hook_name) && $message->hook_name == hookDisplayQuantityDiscountProCustom2}selected="selected"{/if}>{l s='displayQuantityDiscountProCustom2 (Custom 2)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayQuantityDiscountProCustom3" {if isset($message->hook_name) && $message->hook_name == hookDisplayQuantityDiscountProCustom3}selected="selected"{/if}>{l s='displayQuantityDiscountProCustom3 (Custom 3)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayQuantityDiscountProCustom4" {if isset($message->hook_name) && $message->hook_name == hookDisplayQuantityDiscountProCustom4}selected="selected"{/if}>{l s='displayQuantityDiscountProCustom4 (Custom 4)' mod='quantitydiscountpro'}</option>
							<option value="hookDisplayQuantityDiscountProCustom5" {if isset($message->hook_name) && $message->hook_name == hookDisplayQuantityDiscountProCustom5}selected="selected"{/if}>{l s='displayQuantityDiscountProCustom5 (Custom 5)' mod='quantitydiscountpro'}</option>
						</optgroup>
					</select>
				</div>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-1">{l s='Message' mod='quantitydiscountpro'}</label>
				<div class="col-lg-11">
					{foreach from=$languages item=language}
						{if $languages|count > 1}
							<div class="row">
								<div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $display_language}style="display:none"{/if}>
									<div class="col-lg-11">
						{/if}
										<textarea name="message_message[{$message->id_quantity_discount_rule_message|intval}][{$language.id_lang|intval}]" class="rte autoload_rte_{$message->id_quantity_discount_rule_message|intval}">{if isset($message->message[$language.id_lang|intval])}{$message->message[$language.id_lang|intval]|escape:'html':'UTF-8'}{/if}</textarea>
						{if $languages|count > 1}
									</div>
									<div class="col-lg-1">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											{$language.iso_code|escape:'html':'UTF-8'}
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											{foreach from=$languages item=language}
											<li><a href="javascript:hideOtherLanguage({$language.id_lang|escape:'html':'UTF-8'});" tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a></li>
											{/foreach}
										</ul>
									</div>
								</div>
							</div>
						{/if}
					{/foreach}
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	{if isset($tinymce) && $tinymce}
		var iso = '{$iso|addslashes|escape:'htmlall':'UTF-8'}';
		var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes|escape:'htmlall':'UTF-8'}';
		var ad = '{$ad|addslashes|escape:'htmlall':'UTF-8'}';
	{/if}

	$(document).ready(function(){
		{block name="autoload_tinyMCE"}
			tinySetup({
				editor_selector :"autoload_rte_{$message->id_quantity_discount_rule_message|intval}",
				force_p_newlines: false,
				force_br_newlines: true,
			});
		{/block}
	});
</script>