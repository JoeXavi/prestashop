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

<div class="panel" id="quantity-discount-pro">
	<h3><i class="icon-tag"></i> {l s='Quantity discount rule' mod='quantitydiscountpro'}</h3>
	<div class="productTabs">
		<ul class="tab nav nav-tabs">
			<li class="tab-row">
				<a class="tab-page" id="quantity_discount_rule_link_informations" href="javascript:displayQuantityDiscountTab('informations');"><i class="icon-info"></i> {l s='Information' mod='quantitydiscountpro'}</a>
			</li>
			<li class="tab-row">
				<a class="tab-page" id="quantity_discount_rule_link_conditions" href="javascript:displayQuantityDiscountTab('conditions');"><i class="icon-random"></i> {l s='Conditions' mod='quantitydiscountpro'}</a>
			</li>
			<li class="tab-row">
				<a class="tab-page" id="quantity_discount_rule_link_actions" href="javascript:displayQuantityDiscountTab('actions');"><i class="icon-wrench"></i> {l s='Actions' mod='quantitydiscountpro'}</a>
			</li>
			<li class="tab-row">
				<a class="tab-page" id="quantity_discount_rule_link_messages" href="javascript:displayQuantityDiscountTab('messages');"><i class="icon-envelope"></i> {l s='Messages' mod='quantitydiscountpro'}</a>
			</li>
			{*
			<li class="tab-row">
				<a class="tab-page" id="quantity_discount_rule_link_popup" href="javascript:displayQuantityDiscountTab('messages');"><i class="icon-envelope"></i> {l s='Popup' mod='quantitydiscountpro'}</a>
			</li>
			*}
		</ul>
	</div>
	<form action="{$currentIndex|escape:'html':'UTF-8'}&amp;token={$currentToken|escape:'html':'UTF-8'}&amp;addquantity_discount_rule" id="quantity_discount_rule_form" class="form-horizontal" method="post">
		{if $currentObject->id}<input type="hidden" name="id_quantity_discount_rule" value="{$currentObject->id|intval}" />{/if}
		<input type="hidden" id="currentFormTab" name="currentFormTab" value="{if isset($smarty.post.currentFormTab)}{$smarty.post.currentFormTab|escape:'quotes':'UTF-8'}{else}informations{/if}" />
		<div id="quantity_discount_rule_informations" class="panel quantity_discount_rule_tab">
			{include file="$tpl_dir/quantity_discount_rules/informations.tpl"}
		</div>
		<div id="quantity_discount_rule_conditions" class="panel quantity_discount_rule_tab">
			{include file="$tpl_dir/quantity_discount_rules/conditions.tpl"}
		</div>
		<div id="quantity_discount_rule_actions" class="panel quantity_discount_rule_tab">
			{include file="$tpl_dir/quantity_discount_rules/actions.tpl"}
		</div>
		<div id="quantity_discount_rule_messages" class="panel quantity_discount_rule_tab">
			{include file="$tpl_dir/quantity_discount_rules/messages.tpl"}
		</div>
		{*
		<div id="quantity_discount_rule_popup" class="panel quantity_discount_rule_tab">
			{include file="$tpl_dir/quantity_discount_rules/popup.tpl"}
		</div>
		*}
		<button type="submit" class="btn btn-default pull-right" name="submitAddquantity_discount_rule" id="{$table|escape:'html':'UTF-8'}_form_submit_btn">{l s='Save' mod='quantitydiscountpro'}
		</button>
	</form>

	<script type="text/javascript">
		var product_rule_groups_counter = {if isset($product_rule_groups_counter)}{$product_rule_groups_counter|intval}{else}0{/if};
		var product_rule_counters = new Array();
		var condition_groups_counter = {if isset($condition_groups_counter)}{$condition_groups_counter|intval}{else}0{/if};
		var condition_counters = new Array();
		{foreach from=$condition_counters item=condition_counter key=k}
			condition_counters[{$k|escape:'quotes':'UTF-8'}] = {$condition_counter|escape:'quotes':'UTF-8'};
		{/foreach}
		var action_counter = {if isset($action_counter)}{$action_counter|intval}{else}0{/if};
		var message_counter = {if isset($message_counter)}{$message_counter|intval}{else}0{/if};
		var currentToken = '{$currentToken|escape:'quotes':'UTF-8'}';
		var currentFormTab = '{if isset($smarty.post.currentFormTab)}{$smarty.post.currentFormTab|escape:'quotes':'UTF-8'}{else}informations{/if}';
		var currentText = '{l s='Now' js=1 mod='quantitydiscountpro'}';
		var closeText = '{l s='Done' js=1 mod='quantitydiscountpro'}';
		var timeOnlyTitle = '{l s='Choose Time' js=1 mod='quantitydiscountpro'}';
		var timeText = '{l s='Time' js=1 mod='quantitydiscountpro'}';
		var hourText = '{l s='Hour' js=1 mod='quantitydiscountpro'}';
		var minuteText = '{l s='Minute' js=1 mod='quantitydiscountpro'}';

		var languages = new Array();
		{foreach from=$languages item=language key=k}
			languages[{$k|intval}] = {
				id_lang: {$language.id_lang|escape:'html':'UTF-8'},
				iso_code: '{$language.iso_code|escape:'quotes':'UTF-8'}',
				name: '{$language.name|escape:'quotes':'UTF-8'}'
			};
		{/foreach}
		displayFlags(languages, {$display_language|escape:'html':'UTF-8'});

		{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
			function hideOtherLanguage(id)
			{
				$('.translatable-field').hide();
				$('.lang-' + id).show();

				var id_old_language = id_language;
				id_language = id;

				if (id_old_language != id) {
					changeEmployeeLanguage();
				}

				$('#current_product').html($('#name_' + id_language).val());
			}
		{/if}

	</script>
	<script type="text/javascript" src="{$module_path|escape:'html':'UTF-8'}views/js/admin.js"></script>

	{include file="$tpl_dir/quantity_discount_rules/footer_toolbar.tpl"}
</div>
