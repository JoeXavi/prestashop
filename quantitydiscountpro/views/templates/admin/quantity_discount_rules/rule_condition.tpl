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

<tr id="condition_group_{$condition_group_id|intval}_tr" class="col-lg-12">
	<td class="col-lg-1">
		<a class="btn btn-default" href="javascript:removeRuleConditionGroup({$condition_group_id|intval});">
			<i class="icon-remove text-danger"></i>
		</a>
	</td>
	<td class="col-lg-11">
		<div class="form-group">
			<label class="control-label col-lg-2">
				<span>
					{l s='Condition' mod='quantitydiscountpro'}
				</span>
			</label>
			<div class="col-lg-10">
				<select name="condition_type[{$condition_group_id|intval}]">
					<option value="">{l s='-- Choose --' mod='quantitydiscountpro'}</option>
					<option value="1" {if 0 == 0}selected="selected"{/if}>{l s='Limit to a single customer' mod='quantitydiscountpro'}</option>
					<option value="2" {if 0 == 0}selected="selected"{/if}>{l s='Customers must be suscribed to newsletter' mod='quantitydiscountpro'}</option>
					<option value="2" {if 0 == 0}selected="selected"{/if}>{l s='Customers signed up before/after a date' mod='quantitydiscountpro'}</option>
					<option value="2" {if 0 == 0}selected="selected"{/if}>{l s='Customers with more than X orders' mod='quantitydiscountpro'}</option>
					<option value="2" {if 0 == 0}selected="selected"{/if}>{l s='Customers with more than X amount spent' mod='quantitydiscountpro'}</option>
					<option value="2" {if 0 == 0}selected="selected"{/if}>{l s='Only for first order' mod='quantitydiscountpro'}</option>
					<option value="3" {if 0 == 1}selected="selected"{/if}>{l s='Number of products in cart' mod='quantitydiscountpro'}</option>
					<option value="4" {if 0 == 2}selected="selected"{/if}>{l s='Cart amount' mod='quantitydiscountpro'}</option>
					<option value="5" {if 0 == 2}selected="selected"{/if}>{l s='Cart weight' mod='quantitydiscountpro'}</option>
					<option value="6" {if 0 == 2}selected="selected"{/if}>{l s='Country selection' mod='quantitydiscountpro'}</option>
					<option value="7" {if 0 == 2}selected="selected"{/if}>{l s='Buy X, get Y free or with discount (3x2, 6x4, 3 and 1 with discount, etc.)' mod='quantitydiscountpro'}</option>
				</select>
			</div>
		</div>
		<div class="form-group" id="condition_type_options_1">
			<div class="col-lg-10 col-lg-offset-2">
				<div class="input-group col-lg-12">
					<span class="input-group-addon"><i class="icon-user"></i></span>
					<input type="hidden" id="id_customer" name="id_customer" value="{$condition['id_customer']|intval}" />
					<input type="text" id="customerFilter" class="input-xlarge" name="customerFilter" value="{$customerFilter|escape:'html':'UTF-8'}" />
					<span class="input-group-addon"><i class="icon-search"></i></span>
				</div>
			</div>
		</div>

		<div class="form-group" id="condition_type_options_2">
			<div class="col-lg-10 col-lg-offset-2">
				<div class="input-group col-lg-12">
					<span class="switch prestashop-switch fixed-width-lg" id="nb_products_cart_restriction_{$condition_group_id|intval}">
						<input type="radio" name="nb_products_cart_restriction[{$condition_group_id|intval}]" id="nb_products_cart_restriction_on_{$condition_group_id|intval}" value="1"  />
						<label class="t" for="nb_products_cart_restriction_on_{$condition_group_id|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
						<input type="radio" name="nb_products_cart_restriction[{$condition_group_id|intval}]" id="nb_products_cart_restriction_off_{$condition_group_id|intval}" value="0"   />
						<label class="t" for="nb_products_cart_restriction_off_{$condition_group_id|intval}">{l s='No' mod='quantitydiscountpro'}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
			</div>
		</div>


		<div class="form-group" id="condition_type_options_6">
			{if $countries.unselected|@count + $countries.selected|@count > 1}
				<div class="form-group">
					<label class="control-label col-lg-3">
						<span class="label-tooltip" data-toggle="tooltip"
							title="{l s='This restriction applies to the country of delivery selected.' mod='quantitydiscountpro'}">
							{l s='Country selection' mod='quantitydiscountpro'}
						</span>
					</label>
					<div class="col-lg-9">
						<p class="checkbox">
							<label>
								<input type="checkbox" id="country_restriction" name="country_restriction" value="1" {if $countries.unselected|@count}checked="checked"{/if} />
								{l s='Country selection' mod='quantitydiscountpro'}
							</label>
						</p>
						<div id="country_restriction_div">
							<br />
							<table class="table">
								<tr>
									<td>
										<p>{l s='Unselected countries' mod='quantitydiscountpro'}</p>
										<select id="country_select_1" multiple>
											{foreach from=$countries.unselected item='country'}
												<option value="{$country.id_country|intval}">&nbsp;{$country.name|escape}</option>
											{/foreach}
										</select>
										<a id="country_select_add" class="btn btn-block clearfix">{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
									</td>
									<td>
										<p>{l s='Selected countries' mod='quantitydiscountpro'}</p>
										<select name="country_select[]" id="country_select_2" class="input-large" multiple>
											{foreach from=$countries.selected item='country'}
												<option value="{$country.id_country|intval}">&nbsp;{$country.name|escape}</option>
											{/foreach}
										</select>
										<a id="country_select_remove" class="btn btn-block clearfix"><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			{/if}
		</div>

	</td>
</tr>

{*
<div class="form-group" id="nb_products_cart_restriction_div">
	<div class="col-lg-9 col-lg-offset-3">
		<div class="row">
			<div class="col-lg-1">
				<select name="nb_products_cart_operator">
					<option value="0" {if $a('nb_products_cart_operator') == 0}selected="selected"{/if}>>=</option>
					<option value="1" {if $a('nb_products_cart_operator') == 1}selected="selected"{/if}>=</option>
					<option value="2" {if $a('nb_products_cart_operator') == 2}selected="selected"{/if}><=</option>
				</select>
			</div>
			<div class="col-lg-3">
				<input type="text" name="nb_products_cart" value="{$a('nb_products_cart')|intval}" />
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3">
		<span class="label-tooltip" data-toggle="tooltip"
			title="{l s='You can choose a minimum amount for the cart either with or without the taxes and shipping.' mod='quantitydiscountpro'}">
			{l s='Cart amount' mod='quantitydiscountpro'}
		</span>
	</label>
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<span class="switch prestashop-switch fixed-width-lg" id="cart_amount_restriction">
					<input type="radio" name="cart_amount_restriction" id="cart_amount_restriction_on" value="1" {if $a('cart_amount_restriction')|intval}checked="checked"{/if} />
					<label class="t" for="cart_amount_restriction_on">{l s='Enable' mod='quantitydiscountpro'}</label>
					<input type="radio" name="cart_amount_restriction" id="cart_amount_restriction_off" value="0"  {if !$a('cart_amount_restriction')|intval}checked="checked"{/if} />
					<label class="t" for="cart_amount_restriction_off">{l s='Disable' mod='quantitydiscountpro'}</label>
					<a class="slide-button btn"></a>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="form-group" id="cart_amount_restriction_div">
	<div class="col-lg-9 col-lg-offset-3">
		<div class="row">
			<div class="col-lg-1">
				<select name="cart_amount_operator">
					<option value="0" {if $a('cart_amount_operator') == 0}selected="selected"{/if}>>=</option>
					<option value="1" {if $a('cart_amount_operator') == 1}selected="selected"{/if}>=</option>
					<option value="2" {if $a('cart_amount_operator') == 2}selected="selected"{/if}><=</option>
				</select>
			</div>
			<div class="col-lg-3">
				<input type="text" name="cart_amount" value="{$a('cart_amount')|floatval}" />
			</div>
			<div class="col-lg-2">
				<select name="cart_amount_currency">
				{foreach from=$currencies item='currency'}
					<option value="{$currency.id_currency|intval}"
					{if $a('cart_amount_currency') == $currency.id_currency
						|| (!$a('cart_amount_currency') && $currency.id_currency == $defaultCurrency)}
						selected="selected"
					{/if}
					>
						{$currency.iso_code}
					</option>
				{/foreach}
				</select>
			</div>
			<div class="col-lg-3">
				<select name="cart_amount_tax">
					<option value="0" {if $a('cart_amount_tax') == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
					<option value="1" {if $a('cart_amount_tax') == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
				</select>
			</div>
			<div class="col-lg-3">
				<select name="cart_amount_shipping">
					<option value="0" {if $a('cart_amount_shipping') == 0}selected="selected"{/if}>{l s='Shipping excluded' mod='quantitydiscountpro'}</option>
					<option value="1" {if $a('cart_amount_shipping') == 1}selected="selected"{/if}>{l s='Shipping included' mod='quantitydiscountpro'}</option>
				</select>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-lg-3">
		{l s='Cart weight' mod='quantitydiscountpro'}
	</label>
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<span class="switch prestashop-switch fixed-width-lg" id="cart_weight_restriction">
					<input type="radio" name="cart_weight_restriction" id="cart_weight_restriction_on" value="1" {if $a('cart_weight_restriction')|intval}checked="checked"{/if} />
					<label class="t" for="cart_weight_restriction_on">{l s='Enable' mod='quantitydiscountpro'}</label>
					<input type="radio" name="cart_weight_restriction" id="cart_weight_restriction_off" value="0"  {if !$a('cart_weight_restriction')|intval}checked="checked"{/if} />
					<label class="t" for="cart_weight_restriction_off">{l s='Disable' mod='quantitydiscountpro'}</label>
					<a class="slide-button btn"></a>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="form-group" id="cart_weight_restriction_div">
	<div class="col-lg-9 col-lg-offset-3">
		<div class="row">
			<div class="col-lg-1">
				<select name="cart_weight_operator">
					<option value="0" {if $a('cart_weight_operator') == 0}selected="selected"{/if}>>=</option>
					<option value="1" {if $a('cart_weight_operator') == 1}selected="selected"{/if}>=</option>
					<option value="2" {if $a('cart_weight_operator') == 2}selected="selected"{/if}><=</option>
				</select>
			</div>
			<div class="col-lg-3">
				<input type="text" name="cart_weight" value="{$a('cart_weight')|floatval}" />
			</div>
		</div>
	</div>
</div>

{if $carriers.unselected|@count + $carriers.selected|@count > 1}
	<div class="form-group">
		<label class="control-label col-lg-3">
			<span class="label-tooltip" data-toggle="tooltip"
				title="{l s='One of these carriers have to be selected to apply the rule.' mod='quantitydiscountpro'}">
				{l s='Carrier selection' mod='quantitydiscountpro'}
			</span>
		</label>
		<div class="col-lg-9">
			<div class="row">
				<div class="col-lg-12">
					<div>
						<span class="switch prestashop-switch fixed-width-lg" id="carrier_restriction">
							<input type="radio" name="carrier_restriction" id="carrier_restriction_on" value="1" {if $carriers.unselected|@count}checked="checked"{/if} />
							<label class="t" for="carrier_restriction_on">{l s='Enable' mod='quantitydiscountpro'}</label>
							<input type="radio" name="carrier_restriction" id="carrier_restriction_off" value="0" {if !$carriers.unselected|@count}checked="checked"{/if} />
							<label class="t" for="carrier_restriction_off">{l s='Disable' mod='quantitydiscountpro'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
					<div id="carrier_restriction_div">
						<br />
						<table class="table">
							<tr>
								<td>
									<p>{l s='Unselected carriers' mod='quantitydiscountpro'}</p>
									<select id="carrier_select_1" class="input-large" multiple>
										{foreach from=$carriers.unselected item='carrier'}
											<option value="{$carrier.id_reference|intval}">&nbsp;{$carrier.name|escape}</option>
										{/foreach}
									</select>
									<a id="carrier_select_add" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
								</td>
								<td>
									<p>{l s='Selected carriers' mod='quantitydiscountpro'}</p>
									<select name="carrier_select[]" id="carrier_select_2" class="input-large" multiple>
										{foreach from=$carriers.selected item='carrier'}
											<option value="{$carrier.id_reference|intval}">&nbsp;{$carrier.name|escape}</option>
										{/foreach}
									</select>
									<a id="carrier_select_remove" class="btn btn-default btn-block clearfix"><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}

{if $groups.unselected|@count + $groups.selected|@count > 1}
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Customer group selection' mod='quantitydiscountpro'}
		</label>
		<div class="col-lg-9">
			<div class="row">
				<div class="col-lg-12">
					<div>
						<span class="switch prestashop-switch fixed-width-lg" id="group_restriction">
							<input type="radio" name="group_restriction" id="group_restriction_on" value="1" {if $groups.unselected|@count}checked="checked"{/if} />
							<label class="t" for="group_restriction_on">{l s='Enable' mod='quantitydiscountpro'}</label>
							<input type="radio" name="group_restriction" id="group_restriction_off" value="0" {if !$groups.unselected|@count}checked="checked"{/if} />
							<label class="t" for="group_restriction_off">{l s='Disable' mod='quantitydiscountpro'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
					<div id="group_restriction_div">
						<br />
						<table class="table">
							<tr>
								<td>
									<p>{l s='Unselected groups' mod='quantitydiscountpro'}</p>
									<select id="group_select_1" class="input-large" multiple>
										{foreach from=$groups.unselected item='group'}
											<option value="{$group.id_group|intval}">&nbsp;{$group.name|escape}</option>
										{/foreach}
									</select>
									<a id="group_select_add" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
								</td>
								<td>
									<p>{l s='Selected groups' mod='quantitydiscountpro'}</p>
									<select name="group_select[]" class="input-large" id="group_select_2" multiple>
										{foreach from=$groups.selected item='group'}
											<option value="{$group.id_group|intval}">&nbsp;{$group.name|escape}</option>
										{/foreach}
									</select>
									<a id="group_select_remove" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}

{if $cart_rules.unselected|@count + $cart_rules.selected|@count > 0}
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Compatibility with other cart rules' mod='quantitydiscountpro'}
		</label>
		<div class="col-lg-9">
			<div class="row">
				<div class="col-lg-12">
					<div>
						<span class="switch prestashop-switch fixed-width-lg" id="cart_rule_restriction">
							<input type="radio" name="cart_rule_restriction" id="cart_rule_restriction_on" value="1" {if $cart_rules.unselected|@count}checked="checked"{/if} />
							<label class="t" for="cart_rule_restriction_on">{l s='Enable' mod='quantitydiscountpro'}</label>
							<input type="radio" name="cart_rule_restriction" id="cart_rule_restriction_off" value="0" {if !$cart_rules.unselected|@count}checked="checked"{/if} />
							<label class="t" for="cart_rule_restriction_off">{l s='Disable' mod='quantitydiscountpro'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
					<div id="cart_rule_restriction_div">
						<br />
						<table  class="table">
							<tr>
								<td>
									<p>{l s='Uncombinable cart rules' mod='quantitydiscountpro'}</p>
									<select id="cart_rule_select_1" multiple="">
										{foreach from=$cart_rules.unselected item='cart_rule'}
											<option value="{$cart_rule.id_cart_rule|intval}">&nbsp;{$cart_rule.name|escape}</option>
										{/foreach}
									</select>
									<a id="cart_rule_select_add" class="btn btn-default btn-block clearfix">{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
								</td>
								<td>
									<p>{l s='Combinable cart rules' mod='quantitydiscountpro'}</p>
									<select name="cart_rule_select[]" id="cart_rule_select_2" multiple>
										{foreach from=$cart_rules.selected item='cart_rule'}
											<option value="{$cart_rule.id_cart_rule|intval}">&nbsp;{$cart_rule.name|escape}</option>
										{/foreach}
									</select>
									<a id="cart_rule_select_remove" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
{/if}

<div class="form-group">
	<label class="control-label col-lg-3">
		{l s='Products/categories restrictions' mod='quantitydiscountpro'}
	</label>
	<div class="col-lg-9">
		<div class="row">
			<div class="col-lg-12">
				<div>
					<span class="switch prestashop-switch fixed-width-lg" id="product_restriction">
						<input type="radio" name="product_restriction" id="product_restriction_on" value="1" {if $product_rule_groups|@count}checked="checked"{/if} />
						<label class="t" for="product_restriction_on">{l s='Enable' mod='quantitydiscountpro'}</label>
						<input type="radio" name="product_restriction" id="product_restriction_off" value="0" {if !$product_rule_groups|@count}checked="checked"{/if} />
						<label class="t" for="product_restriction_off">{l s='Disable' mod='quantitydiscountpro'}</label>
						<a class="slide-button btn"></a>
					</span>
				</div>
				<div id="product_restriction_div">
					<br />
					<table id="product_rule_group_table" class="table">
						{foreach from=$product_rule_groups item='product_rule_group'}
							{$product_rule_group}
						{/foreach}
					</table>
					<a href="javascript:addProductRuleGroup();" class="btn btn-default ">
						<i class="icon-plus-sign"></i> {l s='Product selection' mod='quantitydiscountpro'}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

{if $shops.unselected|@count + $shops.selected|@count > 1}
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Shop selection' mod='quantitydiscountpro'}
		</label>
		<div class="col-lg-9">
			<p class="checkbox">
				<label>
					<input type="checkbox" id="shop_restriction" name="shop_restriction" value="1" {if $shops.unselected|@count}checked="checked"{/if} />
					{l s='Shop selection' mod='quantitydiscountpro'}
				</label>
			</p>
			<div id="shop_restriction_div">
				<br/>
				<table class="table">
					<tr>
						<td>
							<p>{l s='Unselected shops' mod='quantitydiscountpro'}</p>
							<select id="shop_select_1" multiple>
								{foreach from=$shops.unselected item='shop'}
									<option value="{$shop.id_shop|intval}">&nbsp;{$shop.name|escape}</option>
								{/foreach}
							</select>
							<a id="shop_select_add" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
						</td>
						<td>
							<p>{l s='Selected shops' mod='quantitydiscountpro'}</p>
							<select name="shop_select[]" id="shop_select_2" multiple>
								{foreach from=$shops.selected item='shop'}
									<option value="{$shop.id_shop|intval}">&nbsp;{$shop.name|escape}</option>
								{/foreach}
							</select>
							<a id="shop_select_remove" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
{/if}
*}

{literal}
<script type="text/javascript">
	hideAllConditions($('#condition_group_{/literal}{$condition_group_id|intval}{literal}_tr'));
	$('select[name^="condition_type"]').each(function() {
		$(this).change(function() {toggleConditions($(this))});
	});
</script>
{/literal}