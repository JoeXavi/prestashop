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

<div id="action_{$action->id_quantity_discount_rule_action|intval}_container" class="col-lg-12 action">
    <input type="hidden" name="action[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->id_quantity_discount_rule_action|intval}" />
    <div class="row">
        <div class="col-lg-2">
            <a class="btn btn-default" id="action_condition_remove_button[{$action->id_quantity_discount_rule_action|intval}]" href="javascript:removeActionProduct('{$action->id_quantity_discount_rule_action|intval}');">
                <i class="icon-remove text-danger"></i> {l s='Remove action' mod='quantitydiscountpro'}
            </a>
        </div>
        <div class="col-lg-10">
            <div class="form-group">
                <label class="control-label col-lg-1">
                    <span>
                        {l s='Action' mod='quantitydiscountpro'}
                    </span>
                </label>
                <div class="col-lg-11">
                    <select name="action_id_type[{$action->id_quantity_discount_rule_action|intval}]">
                        <option value="0">{l s='-- Choose --' mod='quantitydiscountpro'}</option>
                        {if $smarty.foreach.actions.iteration == 1}
                        <optgroup label="{l s='Shipping cost' mod='quantitydiscountpro'}">
                            <option value="1" {if isset($action->id_type) && $action->id_type|intval == 1}selected="selected"{/if}>{l s='Shipping cost - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="5" {if isset($action->id_type) && $action->id_type|intval == 5}selected="selected"{/if}>{l s='Shipping cost - Percentage discount' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Order amount' mod='quantitydiscountpro'}">
                            <option value="2" {if isset($action->id_type) && $action->id_type|intval == 2}selected="selected"{/if}>{l s='Order amount - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="3" {if isset($action->id_type) && $action->id_type|intval == 3}selected="selected"{/if}>{l s='Order amount - Percentage discount' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Product discount' mod='quantitydiscountpro'}">
                            <option value="27" {if isset($action->id_type) && $action->id_type|intval == 27}selected="selected"{/if}>{l s='Product discount - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="28" {if isset($action->id_type) && $action->id_type|intval == 28}selected="selected"{/if}>{l s='Product discount - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="29" {if isset($action->id_type) && $action->id_type|intval == 29}selected="selected"{/if}>{l s='Product discount - Fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Buy X units - Get Y units (each M-th)' mod='quantitydiscountpro'}">
                            <option value="6" {if isset($action->id_type) && $action->id_type|intval == 6}selected="selected"{/if}>{l s='Buy X Get Y - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="7" {if isset($action->id_type) && $action->id_type|intval == 7}selected="selected"{/if}>{l s='Buy X Get Y - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="8" {if isset($action->id_type) && $action->id_type|intval == 8}selected="selected"{/if}>{l s='Buy X Get Y - Fixed price' mod='quantitydiscountpro'}</option>
                            <option value="31" {if isset($action->id_type) && $action->id_type|intval == 31}selected="selected"{/if}>{l s='Buy X Get Y - Gift product (by product)' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Buy more than X units and get discount in all units' mod='quantitydiscountpro'}">
                            <option value="32" {if isset($action->id_type) && $action->id_type|intval == 32}selected="selected"{/if}>{l s='Buy more than X units and get discount in all units - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="33" {if isset($action->id_type) && $action->id_type|intval == 33}selected="selected"{/if}>{l s='Buy more than X units and get discount in all units - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="34" {if isset($action->id_type) && $action->id_type|intval == 34}selected="selected"{/if}>{l s='Buy more than X units and get discount in all units - Fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='All products after X' mod='quantitydiscountpro'}">
                            <option value="12" {if isset($action->id_type) && $action->id_type|intval == 12}selected="selected"{/if}>{l s='All products after X - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="13" {if isset($action->id_type) && $action->id_type|intval == 13}selected="selected"{/if}>{l s='All products after X - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="14" {if isset($action->id_type) && $action->id_type|intval == 14}selected="selected"{/if}>{l s='All products after X - Fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Each group of X' mod='quantitydiscountpro'}">
                            <option value="15" {if isset($action->id_type) && $action->id_type|intval == 15}selected="selected"{/if}>{l s='Each group of X - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="16" {if isset($action->id_type) && $action->id_type|intval == 16}selected="selected"{/if}>{l s='Each group of X - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="17" {if isset($action->id_type) && $action->id_type|intval == 17}selected="selected"{/if}>{l s='Each group of X - Fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Each X-th after Y' mod='quantitydiscountpro'}">
                            <option value="18" {if isset($action->id_type) && $action->id_type|intval == 18}selected="selected"{/if}>{l s='Each X-th after Y - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="19" {if isset($action->id_type) && $action->id_type|intval == 19}selected="selected"{/if}>{l s='Each X-th after Y - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="20" {if isset($action->id_type) && $action->id_type|intval == 20}selected="selected"{/if}>{l s='Each X-th after Y - Fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Each X spent (over Z) Get Y' mod='quantitydiscountpro'}">
                            <option value="21" {if isset($action->id_type) && $action->id_type|intval == 21}selected="selected"{/if}>{l s='Each X spent (over Z) - Get Y (fixed discount)' mod='quantitydiscountpro'}</option>
                            <option value="35" {if isset($action->id_type) && $action->id_type|intval == 35}selected="selected"{/if}>{l s='Each X spent (over Z) - Get free gift' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='X spent (over Z) Get Y' mod='quantitydiscountpro'}">
                            <option value="26" {if isset($action->id_type) && $action->id_type|intval == 26}selected="selected"{/if}>{l s='X spent (over Z) Get Y - Percentage discount' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Add a product' mod='quantitydiscountpro'}">
                            <option value="36" {if isset($action->id_type) && $action->id_type|intval == 36}selected="selected"{/if}>{l s='Add a product with fixed price' mod='quantitydiscountpro'}</option>
                            <option value="30" {if isset($action->id_type) && $action->id_type|intval == 30}selected="selected"{/if}>{l s='Add a selected product as a gift' mod='quantitydiscountpro'}</option>
                            <option value="40" {if isset($action->id_type) && $action->id_type|intval == 40}selected="selected"{/if}>{l s='Add a product as a gift' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Spent more than X amount in selected products' mod='quantitydiscountpro'}">
                            <option value="37" {if isset($action->id_type) && $action->id_type|intval == 37}selected="selected"{/if}>{l s='Spent more than X amount in selected products - Get a fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="38" {if isset($action->id_type) && $action->id_type|intval == 38}selected="selected"{/if}>{l s='Spent more than X amount in selected products - Get a percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="39" {if isset($action->id_type) && $action->id_type|intval == 39}selected="selected"{/if}>{l s='Spent more than X amount in selected products - Fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        {/if}
                        {if $smarty.foreach.actions.iteration == 1 || $action->id_type == '22' || $action->id_type == '100' || $action->id_type == '101' || $action->id_type == '102' || $action->id_type == '103' || $action->id_type == '107' || (isset($type) && $type == '22')}
                        <optgroup label="{l s='Buy X, Y, Z.. and get a discount on A, B, C...' mod='quantitydiscountpro'}">
                            <option value="22" {if isset($action->id_type) && $action->id_type|intval == 22}selected="selected"{/if}>{l s='Buy X' mod='quantitydiscountpro'}</option>
                            <option value="100" {if isset($action->id_type) && $action->id_type|intval == 100}selected="selected"{/if}>{l s='Get a discount on A - Fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="101" {if isset($action->id_type) && $action->id_type|intval == 101}selected="selected"{/if}>{l s='Get a discount on A - Percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="102" {if isset($action->id_type) && $action->id_type|intval == 102}selected="selected"{/if}>{l s='Get a discount on A - Fixed price' mod='quantitydiscountpro'}</option>
                            <option value="107" {if isset($action->id_type) && $action->id_type|intval == 107}selected="selected"{/if}>{l s='Add a product to the cart with fixed price' mod='quantitydiscountpro'}</option>
                            <option value="103" {if isset($action->id_type) && $action->id_type|intval == 103}selected="selected"{/if}>{l s='Add a product to the cart as a gift' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        {/if}
                        {if $smarty.foreach.actions.iteration == 1 || $action->id_type == '23' || $action->id_type == '104' || $action->id_type == '105' || $action->id_type == '106' || (isset($type) && $type == '23')}
                        <optgroup label="{l s='Product pack' mod='quantitydiscountpro'}">
                            <option value="23" {if isset($action->id_type) && $action->id_type|intval == 23}selected="selected"{/if}>{l s='Buy X' mod='quantitydiscountpro'}</option>
                            <option value="104" {if isset($action->id_type) && $action->id_type|intval == 104}selected="selected"{/if}>{l s='Get a fixed discount' mod='quantitydiscountpro'}</option>
                            <option value="105" {if isset($action->id_type) && $action->id_type|intval == 105}selected="selected"{/if}>{l s='Get a percentage discount' mod='quantitydiscountpro'}</option>
                            <option value="106" {if isset($action->id_type) && $action->id_type|intval == 106}selected="selected"{/if}>{l s='Get a fixed price' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        {/if}
                    </select>
                </div>
            </div>

            <div class="form-group" id="action_condition_group_by[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">{l s='Group products by' mod='quantitydiscountpro'}</label>
                        <div class="col-lg-9">
                            <p class="radio" style="display: none">
                                <input type="radio" name="action_group_products_by[{$action->id_quantity_discount_rule_action|intval}]" id="action_group_products_by_0_{$action->id_quantity_discount_rule_action|intval}" value="" {if !$action->group_products_by }checked="checked"{/if} />
                            </p>
                            <p class="radio">
                                <label for="action_group_products_by_product_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_group_products_by[{$action->id_quantity_discount_rule_action|intval}]" id="action_group_products_by_product_{$action->id_quantity_discount_rule_action|intval}" value="product" {if $action->group_products_by == 'product'}checked="checked"{/if} />
                                    {l s='Product' mod='quantitydiscountpro'}
                                </label>
                            </p>
                            <p class="radio">
                                <label for="action_group_products_by_category_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_group_products_by[{$action->id_quantity_discount_rule_action|intval}]" id="action_group_products_by_category_{$action->id_quantity_discount_rule_action|intval}" value="category" {if $action->group_products_by == 'category'}checked="checked"{/if} />
                                    {l s='Category' mod='quantitydiscountpro'}
                                </label>
                            </p>
                            <p class="radio">
                                <label for="action_group_products_by_supplier_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_group_products_by[{$action->id_quantity_discount_rule_action|intval}]" id="action_group_products_by_supplier_{$action->id_quantity_discount_rule_action|intval}" value="supplier" {if $action->group_products_by == 'supplier'}checked="checked"{/if} />
                                    {l s='Supplier' mod='quantitydiscountpro'}
                                </label>
                            </p>
                            <p class="radio">
                                <label for="action_group_products_by_manufacturer_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_group_products_by[{$action->id_quantity_discount_rule_action|intval}]" id="action_group_products_by_manufacturer_{$action->id_quantity_discount_rule_action|intval}" value="manufacturer" {if $action->group_products_by == 'manufacturer'}checked="checked"{/if} />
                                    {l s='Manufacturer' mod='quantitydiscountpro'}
                                </label>
                            </p>
                            <p class="radio">
                                <label for="action_group_products_by_all_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_group_products_by[{$action->id_quantity_discount_rule_action|intval}]" id="action_group_products_by_all_{$action->id_quantity_discount_rule_action|intval}" value="all" {if $action->group_products_by == 'all'}checked="checked"{/if} />
                                    {l s='All selected' mod='quantitydiscountpro'}
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_spent[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span>{l s='Amount spent in each group of products' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-1">
                                    <label class="control-label">{l s='From' mod='quantitydiscountpro'}</label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" name="action_spent_amount_from[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->spent_amount_from|floatval}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2">
                                    <select name="action_spent_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="spent_currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->spent_currency == $currency.id_currency || (!$action->spent_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="action_spent_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="spent_tax[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->spent_tax == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->spent_tax == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-margin-top">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-offset-4 col-lg-1">
                                    <label class="control-label">{l s='To' mod='quantitydiscountpro'}</label>
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" name="action_spent_amount_to[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->spent_amount_to|floatval}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2">
                                    <select name="action_spent_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="spent_currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->spent_currency == $currency.id_currency || (!$action->spent_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="action_spent_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="spent_tax[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->spent_tax == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->spent_tax == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_buy[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_buy_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='Buy X units' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_buy_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='All products after X' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_buy_label_3[{$action->id_quantity_discount_rule_action|intval}]">{l s='Each group of X' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_buy_label_4[{$action->id_quantity_discount_rule_action|intval}]">{l s='Each X-th' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_buy_label_5[{$action->id_quantity_discount_rule_action|intval}]">{l s='Buy X units or more' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_buy_label_6[{$action->id_quantity_discount_rule_action|intval}]">{l s='Add X units as a gift' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" name="action_products_nb_each[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->products_nb_each|intval}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_get[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_get_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='And get Y units of them' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_get_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='After Y' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_get_label_3[{$action->id_quantity_discount_rule_action|intval}]">{l s='Get Y units' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_get_label_4[{$action->id_quantity_discount_rule_action|intval}]">{l s='And get Y free units' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" id="action_apply_discount_to_nb[{$action->id_quantity_discount_rule_action|intval}]" class="input-mini" name="action_apply_discount_to_nb[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->apply_discount_to_nb|intval}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_buy_amount[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span>{l s='Get Y' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <input type="text" name="action_reduction_buy_amount[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->reduction_buy_amount|floatval}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2"">
                                    <select name="action_reduction_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->reduction_currency == $currency.id_currency || (!$action->reduction_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="action_reduction_buy_amount_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="tax[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_buy_amount_tax == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_buy_amount_tax == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_amount[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_amount_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='Apply an amount discount of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='With an amount discount of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_3[{$action->id_quantity_discount_rule_action|intval}]">{l s='With a price of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_4[{$action->id_quantity_discount_rule_action|intval}]">{l s='Set price of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_5[{$action->id_quantity_discount_rule_action|intval}]">{l s='Each X spent' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_6[{$action->id_quantity_discount_rule_action|intval}]">{l s='And apply to all units an amount discount of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_7[{$action->id_quantity_discount_rule_action|intval}]">{l s='And get all units with a price of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_amount_label_8[{$action->id_quantity_discount_rule_action|intval}]">{l s='With a maximum amount of' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <input type="text" id="action_reduction_amount[{$action->id_quantity_discount_rule_action|intval}]" name="action_reduction_amount[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->reduction_amount|floatval}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2" id="action_condition_reduction_currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    <select name="action_reduction_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->reduction_currency == $currency.id_currency || (!$action->reduction_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="action_reduction_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="tax[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_tax == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_tax == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3" id="action_condition_amount_shipping[{$action->id_quantity_discount_rule_action|intval}]">
                                    <select name="action_reduction_shipping[{$action->id_quantity_discount_rule_action|intval}]" data-group="shipping[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_shipping == 0}selected="selected"{/if}>{l s='Shipping excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_shipping == 1}selected="selected"{/if}>{l s='Shipping included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_value[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_value_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='Percentage discount' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_value_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='With a percentage discount of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_value_label_3[{$action->id_quantity_discount_rule_action|intval}]">{l s='Apply a percentage discount of' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_value_label_4[{$action->id_quantity_discount_rule_action|intval}]">{l s='And apply to all units a percentage discount of' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">%</span>
                                        <input type="text" id="action_reduction_percent[{$action->id_quantity_discount_rule_action|intval}]" class="input-mini" name="action_reduction_percent[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->reduction_percent|floatval}" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <select name="action_reduction_percent_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="tax[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_percent_tax == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_percent_tax == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3" id="action_condition_percent_shipping[{$action->id_quantity_discount_rule_action|intval}]">
                                    <select name="action_reduction_percent_shipping[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_percent_shipping == 0}selected="selected"{/if}>{l s='Shipping excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_percent_shipping == 1}selected="selected"{/if}>{l s='Shipping included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3" id="action_condition_percent_discount[{$action->id_quantity_discount_rule_action|intval}]">
                                    <select name="action_reduction_percent_discount[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_percent_discount|intval == 0}selected="selected"{/if}>{l s='Discounts excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_percent_discount|intval == 1}selected="selected"{/if}>{l s='Discounts included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_buy_over[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span>{l s='In amount over Z' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <input type="text" id="action_reduction_buy_over[{$action->id_quantity_discount_rule_action|intval}]" name="action_reduction_buy_over[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->reduction_buy_over|floatval}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2"">
                                    <select name="action_reduction_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->reduction_currency == $currency.id_currency || (!$action->reduction_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <select name="action_reduction_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="tax[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_tax == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_tax == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3" id="action_condition_amount_shipping[{$action->id_quantity_discount_rule_action|intval}]">
                                    <select name="action_reduction_shipping[{$action->id_quantity_discount_rule_action|intval}]" data-group="shipping[{$action->id_quantity_discount_rule_action|intval}]">
                                        <option value="0" {if $action->reduction_shipping == 0}selected="selected"{/if}>{l s='Shipping excluded' mod='quantitydiscountpro'}</option>
                                        <option value="1" {if $action->reduction_shipping == 1}selected="selected"{/if}>{l s='Shipping included' mod='quantitydiscountpro'}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_regular_price[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">{l s='Compute discount from product price without specific price' mod='quantitydiscountpro'}</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="action_apply_discount_to_regular_price_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_apply_discount_to_regular_price[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_regular_price_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->apply_discount_to_regular_price|intval}checked="checked"{/if} />
                                    <label class="t" for="action_apply_discount_to_regular_price_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="action_apply_discount_to_regular_price[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_regular_price_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->apply_discount_to_regular_price|intval}checked="checked"{/if}/>
                                    <label class="t" for="action_apply_discount_to_regular_price_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 help-block">
                                    {l s='If enabled, the percentage discount will be computed from the regular product price without any specific price.' mod='quantitydiscountpro'}<br />
                                    {l s='It will only apply this discount if it\'s higher than specific price discount. If don\'t, specific price discount will be applied.' mod='quantitydiscountpro'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_maximum_amount[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">{l s='Voucher maximum amount' mod='quantitydiscountpro'}</label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <input type="text" id="action_reduction_max_amount[{$action->id_quantity_discount_rule_action|intval}]" name="action_reduction_max_amount[{$action->id_quantity_discount_rule_action|intval}]" value="{if $action->reduction_max_amount > 0}{$action->reduction_max_amount|floatval}{/if}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2">
                                    <select name="action_reduction_max_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->reduction_max_currency == $currency.id_currency || (!$action->reduction_max_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 help-block">
                                    {l s='Discount will not be higher than this value' mod='quantitydiscountpro'}<br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_product_maximum_amount[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_product_maximum_amount_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='Voucher maximum amount per product unit' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_product_maximum_amount_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='Voucher maximum amount per pack' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-2">
                                    <input type="text" id="action_reduction_product_max_amount[{$action->id_quantity_discount_rule_action|intval}]" name="action_reduction_product_max_amount[{$action->id_quantity_discount_rule_action|intval}]" value="{if $action->reduction_product_max_amount > 0}{$action->reduction_product_max_amount|floatval}{/if}" onchange="this.value = this.value.replace(/,/g, '.');" />
                                </div>
                                <div class="col-lg-2">
                                    <select name="action_reduction_max_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency[{$action->id_quantity_discount_rule_action|intval}]">
                                    {foreach from=$currencies item='currency'}
                                        <option value="{$currency.id_currency|intval}" {if $action->reduction_max_currency == $currency.id_currency || (!$action->reduction_max_currency && $currency.id_currency == $defaultCurrency)}selected="selected"{/if}>{$currency.iso_code|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 help-block">
                                    {l s='Discount per product unit will not be higher than this value' mod='quantitydiscountpro'}<br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_all[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">{l s='Apply discount to X product units' mod='quantitydiscountpro'}</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="action_apply_discount_to_all_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_apply_discount_to_all[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_all_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->apply_discount_to_all|intval}checked="checked"{/if} />
                                    <label class="t" for="action_apply_discount_to_all_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="action_apply_discount_to_all[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_all_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->apply_discount_to_all|intval}checked="checked"{/if}/>
                                    <label class="t" for="action_apply_discount_to_all_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 help-block">{l s='If enabled, reduction will be computed from all the items from a same product. If disabled, only 1 item per product will be computed.' mod='quantitydiscountpro'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_attributes[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">{l s='Consider products with different attributes as the same product' mod='quantitydiscountpro'}</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="action_products_nb_same_attributes_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_products_nb_same_attributes[{$action->id_quantity_discount_rule_action|intval}]" id="action_products_nb_same_attributes_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->products_nb_same_attributes|intval}checked="checked"{/if} />
                                    <label class="t" for="action_products_nb_same_attributes_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="action_products_nb_same_attributes[{$action->id_quantity_discount_rule_action|intval}]" id="action_products_nb_same_attributes_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->products_nb_same_attributes|intval}checked="checked"{/if}/>
                                    <label class="t" for="action_products_nb_same_attributes_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 help-block">{l s='If enabled, 1 item Product A - Color A and 1 from Product A - Color B would be considered as 2 items from Product A. If disabled each item would be considered as different products' mod='quantitydiscountpro'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_repeat[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_repeat_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='Times to repeat the discount' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_repeat_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='Apply discount to this number of products' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <p class="radio">
                                <label for="nb_repetitions_infinite">
                                    <input type="radio" name="action_nb_repetitions[{$action->id_quantity_discount_rule_action|intval}]" value="infinite" {if $action->nb_repetitions == 'infinite'}checked="checked"{/if} />
                                    {l s='As many as possible' mod='quantitydiscountpro'}
                                </label>
                                {*
                                <div class="row">
                                    <div class="col-lg-12 help-block">{l s='Promotion will be applied as many times as possible. P.ex. if you define a 3x2 promotion, it will be applied to 3x2, 6x4, 9x6, etc.' mod='quantitydiscountpro'}</div>
                                </div>
                                *}
                            </p>
                            <p class="radio">
                                <label for="nb_repetitions_custom">
                                    <input type="radio" name="action_nb_repetitions[{$action->id_quantity_discount_rule_action|intval}]" value="custom" {if $action->nb_repetitions == 'custom'}checked="checked"{/if} />
                                    {l s='Number of times (at maximum):' mod='quantitydiscountpro'}
                                </label>
                            </p>
                            <div class="row row-margin-top">
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input type="text" name="action_nb_repetitions_custom[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->nb_repetitions_custom|intval}" />
                                    </div>
                                </div>
                                {*
                                <div class="row">
                                    <div class="col-lg-12 help-block">{l s='Promotion will be applied the number of times defined. P.ex. if you define a 3x2 promotion and 2 repetitions, it will be applied to 3x2 and 6x4 but not to 9x6, 12x8, etc.' mod='quantitydiscountpro'}</div>
                                </div>
                                *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_sort[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">{l s='Start computing discount from:' mod='quantitydiscountpro'}</label>
                        <div class="col-lg-9">
                            <p class="radio">
                                <label for="apply_discount_sort_cheapest_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_apply_discount_sort[{$action->id_quantity_discount_rule_action|intval}]" id="apply_discount_sort_cheapest_{$action->id_quantity_discount_rule_action|intval}" value="cheapest" {if $action->apply_discount_sort == 'cheapest'}checked="checked"{/if} />
                                     {l s='Cheapest products first' mod='quantitydiscountpro'}
                                </label>
                            </p>
                            <p class="radio">
                                <label for="apply_discount_sort_expensive_{$action->id_quantity_discount_rule_action|intval}">
                                    <input type="radio" name="action_apply_discount_sort[{$action->id_quantity_discount_rule_action|intval}]" id="apply_discount_sort_expensive_{$action->id_quantity_discount_rule_action|intval}" value="expensive" {if $action->apply_discount_sort == 'expensive'}checked="checked"{/if} />
                                     {l s='Most expensive products first' mod='quantitydiscountpro'}
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_filters[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="panel">
                        <div class="panel-heading">{l s='Apply rule to products that match these filters' mod='quantitydiscountpro'}</div>
                        <div class="row row-margin-top">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Include products with specific price' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            <span class="switch prestashop-switch fixed-width-lg" id="action_apply_discount_to_special_{$action->id_quantity_discount_rule_action|intval}">
                                                <input type="radio" name="action_apply_discount_to_special[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_special_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->apply_discount_to_special|intval}checked="checked"{/if} />
                                                <label class="t" for="action_apply_discount_to_special_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="action_apply_discount_to_special[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_special_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->apply_discount_to_special|intval}checked="checked"{/if}/>
                                                <label class="t" for="action_apply_discount_to_special_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 help-block">{l s='If disabled, none additional discount will be applied to these products and they will not be considered in rule' mod='quantitydiscountpro'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {if isset($action->product) && (($action->product.unselected|@count) + ($action->product.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by product' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_product_{$action->id_quantity_discount_rule_action|intval}">
                                                    <input type="radio" name="action_filter_by_product[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_product_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_product|intval}checked="checked"{/if} />
                                                    <label class="t" for="action_filter_by_product_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="action_filter_by_product[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_product_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_product|intval}checked="checked"{/if}/>
                                                    <label class="t" for="action_filter_by_product_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="product_restriction_div">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected products' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_product_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="product_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var product_select_1_{$action->id_quantity_discount_rule_action|intval}_values = {$action->product.unselected|json_encode};
                                                                var product_select_1_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < product_select_1_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    product_select_1_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + product_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_product + "' > " + product_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+product_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_product+(product_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].reference ? " - Reference: "+product_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].reference : "")+")</option>";
                                                                }
                                                                $("#product_select_1_{$action->id_quantity_discount_rule_action|intval}").html(product_select_1_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#product_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_product_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="product_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected products' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="action_product_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_product_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_product_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="action_product_select[{$action->id_quantity_discount_rule_action|intval}][]" id="product_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var product_select_2_{$action->id_quantity_discount_rule_action|intval}_values = {$action->product.selected|json_encode};
                                                                var product_select_2_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < product_select_2_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    product_select_2_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + product_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_product + "' > " + product_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+product_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_product+(product_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].reference ? " - Reference: "+product_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].reference : "")+")</option>";
                                                                }
                                                                $("#product_select_2_{$action->id_quantity_discount_rule_action|intval}").html(product_select_2_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#product_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_product_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="product_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}


                        {if isset($action->attribute) && (($action->attribute.unselected|@count) + ($action->attribute.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by attribute' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_attribute_{$action->id_quantity_discount_rule_action|intval}">
                                                    <input type="radio" name="action_filter_by_attribute[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_attribute_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_attribute|intval}checked="checked"{/if} />
                                                    <label class="t" for="action_filter_by_attribute_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="action_filter_by_attribute[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_attribute_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_attribute|intval}checked="checked"{/if}/>
                                                    <label class="t" for="action_filter_by_attribute_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="attribute_restriction_div">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected attributes' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_attribute_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="attribute_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_values = {$action->attribute.unselected|json_encode};
                                                                var attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_attribute + "' > " + attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_attribute+")</option>";
                                                                }
                                                                $("#attribute_select_1_{$action->id_quantity_discount_rule_action|intval}").html(attribute_select_1_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#attribute_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_attribute_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="attribute_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected attributes' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="action_attribute_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_attribute_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="actionattribute_select[{$action->id_quantity_discount_rule_action|intval}][]" id="attribute_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_values = {$action->attribute.selected|json_encode};
                                                                var attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_attribute + "' > " + attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_attribute+")</option>";
                                                                }
                                                                $("#attribute_select_2_{$action->id_quantity_discount_rule_action|intval}").html(attribute_select_2_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#attribute_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_attribute_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="attribute_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($action->feature) && (($action->feature.unselected|@count) + ($action->feature.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by feature' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_feature_{$action->id_quantity_discount_rule_action|intval}">
                                                    <input type="radio" name="action_filter_by_feature[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_feature_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_feature|intval}checked="checked"{/if} />
                                                    <label class="t" for="action_filter_by_feature_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="action_filter_by_feature[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_feature_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_feature|intval}checked="checked"{/if}/>
                                                    <label class="t" for="action_filter_by_feature_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="feature_restriction_div">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected features' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_feature_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="feature_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var feature_select_1_{$action->id_quantity_discount_rule_action|intval}_values = {$action->feature.unselected|json_encode};
                                                                var feature_select_1_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < feature_select_1_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    feature_select_1_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + feature_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_feature + "' > " + feature_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+feature_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_feature+")</option>";
                                                                }
                                                                $("#feature_select_1_{$action->id_quantity_discount_rule_action|intval}").html(feature_select_1_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#feature_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_feature_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="feature_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected features' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="action_feature_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_feature_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_feature_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="actionfeature_select[{$action->id_quantity_discount_rule_action|intval}][]" id="feature_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var feature_select_2_{$action->id_quantity_discount_rule_action|intval}_values = {$action->feature.selected|json_encode};
                                                                var feature_select_2_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < feature_select_2_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    feature_select_2_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + feature_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_feature + "' > " + feature_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+feature_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_feature+")</option>";
                                                                }
                                                                $("#feature_select_2_{$action->id_quantity_discount_rule_action|intval}").html(feature_select_2_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#feature_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_feature_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="feature_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($action->category) && (($action->category.unselected|@count) + ($action->category.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by category' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_category_{$action->id_quantity_discount_rule_action|intval}">
                                                    <input type="radio" name="action_filter_by_category[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_category_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_category|intval}checked="checked"{/if} />
                                                    <label class="t" for="action_filter_by_category_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="action_filter_by_category[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_category_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_category|intval}checked="checked"{/if}/>
                                                    <label class="t" for="action_filter_by_category_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="category_restriction_div">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected categories' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_category_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="category_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var category_select_1_{$action->id_quantity_discount_rule_action|intval}_values = {$action->category.unselected|json_encode};
                                                                var category_select_1_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < category_select_1_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    category_select_1_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + category_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_category + "' > " + category_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+category_select_1_{$action->id_quantity_discount_rule_action|intval}_values[i].id_category+")</option>";
                                                                }
                                                                $("#category_select_1_{$action->id_quantity_discount_rule_action|intval}").html(category_select_1_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#category_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_category_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="category_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected categories' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="action_category_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_category_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_category_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="actioncategory_select[{$action->id_quantity_discount_rule_action|intval}][]" id="category_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var category_select_2_{$action->id_quantity_discount_rule_action|intval}_values = {$action->category.selected|json_encode};
                                                                var category_select_2_{$action->id_quantity_discount_rule_action|intval}_options = '';
                                                                for (var i = 0; i < category_select_2_{$action->id_quantity_discount_rule_action|intval}_values.length; i++) {
                                                                    category_select_2_{$action->id_quantity_discount_rule_action|intval}_options += "<option value='" + category_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_category + "' > " + category_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].name + "  (ID: "+category_select_2_{$action->id_quantity_discount_rule_action|intval}_values[i].id_category+")</option>";
                                                                }
                                                                $("#category_select_2_{$action->id_quantity_discount_rule_action|intval}").html(category_select_2_{$action->id_quantity_discount_rule_action|intval}_options);
                                                                $('#category_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_category_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="category_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top" id="action_condition_default_category[{$action->id_quantity_discount_rule_action|intval}]">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Consider only the product default category' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            <span class="switch prestashop-switch fixed-width-lg" id="action_products_default_category_{$action->id_quantity_discount_rule_action|intval}">
                                                <input type="radio" name="action_products_default_category[{$action->id_quantity_discount_rule_action|intval}]" id="action_products_default_category_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->products_default_category|intval}checked="checked"{/if} />
                                                <label class="t" for="action_products_default_category_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="action_products_default_category[{$action->id_quantity_discount_rule_action|intval}]" id="action_products_default_category_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->products_default_category|intval}checked="checked"{/if}/>
                                                <label class="t" for="action_products_default_category_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 help-block">{l s='CAUTION! If you disable this option and select more than one category, discounts can be duplicated if a product belongs to more than 1 selected category' mod='quantitydiscountpro'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($action->supplier) && (($action->supplier.unselected|@count) + ($action->supplier.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by supplier' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_supplier_{$action->id_quantity_discount_rule_action|intval}">
                                                    <input type="radio" name="action_filter_by_supplier[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_supplier_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_supplier|intval}checked="checked"{/if} />
                                                    <label class="t" for="action_filter_by_supplier_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="action_filter_by_supplier[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_supplier_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_supplier|intval}checked="checked"{/if}/>
                                                    <label class="t" for="action_filter_by_supplier_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="supplier_restriction_div">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected suppliers' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_supplier_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="supplier_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                            {foreach from=$action->supplier.unselected item='supplier'}
                                                                <option value="{$supplier.id_supplier|intval}">{$supplier.name|escape:'html':'UTF-8'} (ID: {$supplier.id_supplier|escape:'html':'UTF-8'})</option>
                                                            {/foreach}
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                $('#supplier_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_supplier_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                                {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="supplier_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected suppliers' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="action_supplier_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_supplier_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_supplier_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="actionsupplier_select[{$action->id_quantity_discount_rule_action|intval}][]" id="supplier_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                            {foreach from=$action->supplier.selected item='supplier'}
                                                                <option value="{$supplier.id_supplier|intval}">{$supplier.name|escape:'html':'UTF-8'} (ID: {$supplier.id_supplier|escape:'html':'UTF-8'})</option>
                                                            {/foreach}
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                $('#supplier_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_supplier_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                                {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="supplier_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($action->manufacturer) && (($action->manufacturer.unselected|@count) + ($action->manufacturer.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by manufacturer' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_manufacturer_{$action->id_quantity_discount_rule_action|intval}">
                                                    <input type="radio" name="action_filter_by_manufacturer[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_manufacturer_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_manufacturer|intval}checked="checked"{/if} />
                                                    <label class="t" for="action_filter_by_manufacturer_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="action_filter_by_manufacturer[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_manufacturer_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_manufacturer|intval}checked="checked"{/if}/>
                                                    <label class="t" for="action_filter_by_manufacturer_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="manufacturer_restriction_div">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected manufacturers' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_manufacturer_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="manufacturer_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                            {foreach from=$action->manufacturer.unselected item='manufacturer'}
                                                                <option value="{$manufacturer.id_manufacturer|intval}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
                                                            {/foreach}
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                $('#manufacturer_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_manufacturer_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                                {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="manufacturer_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected manufacturers' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="action_manufacturer_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_manufacturer_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_manufacturer_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="actionmanufacturer_select[{$action->id_quantity_discount_rule_action|intval}][]" id="manufacturer_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                            {foreach from=$action->manufacturer.selected item='manufacturer'}
                                                                <option value="{$manufacturer.id_manufacturer|intval}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
                                                            {/foreach}
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                $('#manufacturer_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_manufacturer_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                                {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="manufacturer_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        <div class="row row-margin-top">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Filter by price' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_price_{$action->id_quantity_discount_rule_action|intval}">
                                                <input type="radio" name="action_filter_by_price[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_price_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_price|intval}checked="checked"{/if} />
                                                <label class="t" for="action_filter_by_price_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="action_filter_by_price[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_price_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_price|intval}checked="checked"{/if}/>
                                                <label class="t" for="action_filter_by_price_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 row-margin-top">
                                <div class="row">
                                    <div class="col-lg-offset-1 col-lg-4">
                                        <label class="control-label">{l s='From' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" name="action_product_price_from[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->product_price_from|floatval}" />
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="action_product_price_from_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency2[{$action->id_quantity_discount_rule_action|intval}]">
                                            {foreach from=$currencies item='currency'}
                                                <option value="{$currency.id_currency|intval}"
                                                    {if $action->product_price_from_currency|intval == $currency.id_currency
                                                    || (!$action->product_price_from_currency|intval && $currency.id_currency == $defaultCurrency)}
                                                        selected="selected"
                                                    {/if}
                                                >
                                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="action_product_price_from_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="tax2[{$action->id_quantity_discount_rule_action|intval}]">
                                            <option value="0" {if $action->product_price_from_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                            <option value="1" {if $action->product_price_from_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row row-margin-top">
                                    <div class="col-lg-offset-1 col-lg-4">
                                        <label class="control-label">{l s='To' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" name="action_product_price_to[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->product_price_to|floatval}" />
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="action_product_price_to_currency[{$action->id_quantity_discount_rule_action|intval}]" data-group="currency2[{$action->id_quantity_discount_rule_action|intval}]">
                                            {foreach from=$currencies item='currency'}
                                                <option value="{$currency.id_currency|intval}"
                                                    {if $action->product_price_to_currency|intval == $currency.id_currency
                                                    || (!$action->product_price_to_currency|intval && $currency.id_currency == $defaultCurrency)}
                                                        selected="selected"
                                                    {/if}
                                                >
                                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="action_product_price_to_tax[{$action->id_quantity_discount_rule_action|intval}]" data-group="tax2[{$action->id_quantity_discount_rule_action|intval}]">
                                            <option value="0" {if $action->product_price_to_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                            <option value="1" {if $action->product_price_to_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row-margin-top">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Filter by stock' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_stock_{$action->id_quantity_discount_rule_action|intval}">
                                                <input type="radio" name="action_filter_by_stock[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_stock_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_stock|intval}checked="checked"{/if} />
                                                <label class="t" for="action_filter_by_stock_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="action_filter_by_stock[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_stock_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_stock|intval}checked="checked"{/if}/>
                                                <label class="t" for="action_filter_by_stock_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row row-margin-top">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Only consider products with this stock' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-1">
                                        <select name="action_stock_operator[{$action->id_quantity_discount_rule_action|intval}]">
                                            <option value="0" {if $action->stock_operator == 0}selected="selected"{/if}>>=</option>
                                            <option value="1" {if $action->stock_operator == 1}selected="selected"{/if}>=</option>
                                            <option value="2" {if $action->stock_operator == 2}selected="selected"{/if}><=</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">{l s='Stock quantity' mod='quantitydiscountpro'}</span>
                                            <input type="text" name="action_stock[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->stock|floatval}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row-margin-top">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Apply discount only to units in stock' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            <span class="switch prestashop-switch fixed-width-lg" id="action_apply_discount_to_stock_{$action->id_quantity_discount_rule_action|intval}">
                                                <input type="radio" name="action_apply_discount_to_stock[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_stock_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->apply_discount_to_stock|intval}checked="checked"{/if} />
                                                <label class="t" for="action_apply_discount_to_stock_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="action_apply_discount_to_stock[{$action->id_quantity_discount_rule_action|intval}]" id="action_apply_discount_to_stock_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->apply_discount_to_stock|intval}checked="checked"{/if}/>
                                                <label class="t" for="action_apply_discount_to_stock_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group" id="action_condition_free_gift[{$action->id_quantity_discount_rule_action|intval}]">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <label class="control-label col-lg-3">
                            <span id="action_condition_free_gift_label_1[{$action->id_quantity_discount_rule_action|intval}]">{l s='Search a product' mod='quantitydiscountpro'}</span>
                            <span id="action_condition_free_gift_label_2[{$action->id_quantity_discount_rule_action|intval}]">{l s='Of the product' mod='quantitydiscountpro'}</span>
                        </label>
                        <div class="col-lg-9">
                            <div class="input-group col-lg-5">
                                <input type="text" name="giftProductFilter[{$action->id_quantity_discount_rule_action|intval}]" value="{$action->gift_product_filter|escape:'html':'UTF-8'}" />
                                <span class="input-group-addon"><i class="icon-search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <div class="col-lg-12">
                            <div name="gift_products_found[{$action->id_quantity_discount_rule_action|intval}]" {if $action->gift_product_select == ''}style="display:none"{/if}>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{l s='Matching products' mod='quantitydiscountpro'}</label>
                                    <div class="col-lg-5">
                                        <select id="action_gift_product[{$action->id_quantity_discount_rule_action|intval}]" name="action_gift_product[{$action->id_quantity_discount_rule_action|intval}]" onchange="displayProductAttributes({$action->id_quantity_discount_rule_action|intval});" class="control-form">
                                            {if $action->gift_product_select == ''}
                                                <option value="0" selected></option>
                                            {else}
                                                {$action->gift_product_select|escape:'quotes':'UTF-8'}
                                            {/if}
                                        </select>
                                    </div>
                                </div>
                                <div name="gift_product_attributes_list[{$action->id_quantity_discount_rule_action|intval}]" class="form-group" {if !$action->hasAttribute}style="display:none"{/if}>
                                    <label class="control-label col-lg-3">{l s='Available combinations' mod='quantitydiscountpro'}</label>
                                    <div class="col-lg-5" id="gift_product_attributes_list_select[{$action->id_quantity_discount_rule_action|intval}]">
                                        {$action->gift_product_attribute_select|escape:'quotes':'UTF-8'}
                                    </div>
                                </div>
                            </div>
                            <div id="gift_products_err[{$action->id_quantity_discount_rule_action|intval}]" class="alert alert-warning" style="display:none"></div>
                        </div>
                    </div>
                </div>
            </div>

            {if isset($action->carrier) && (($action->carrier.unselected|@count) + ($action->carrier.selected|@count)) > 0}
                <div class="form-group" id="action_condition_filter_by_carrier[{$action->id_quantity_discount_rule_action|intval}]">
                    <div class="col-lg-11 col-lg-offset-1">
                        <div class="row row-margin-top">
                            <div class="col-lg-3">
                                <label class="control-label">{l s='Filter by carrier' mod='quantitydiscountpro'}</label>
                            </div>
                            <div class="col-lg-7">
                                <div class="input-group">
                                    <span class="switch prestashop-switch fixed-width-lg" id="action_filter_by_carrier_{$action->id_quantity_discount_rule_action|intval}">
                                        <input type="radio" name="action_filter_by_carrier[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_carrier_on_{$action->id_quantity_discount_rule_action|intval}" value="1" {if $action->filter_by_carrier|intval}checked="checked"{/if} />
                                        <label class="t" for="action_filter_by_carrier_on_{$action->id_quantity_discount_rule_action|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                        <input type="radio" name="action_filter_by_carrier[{$action->id_quantity_discount_rule_action|intval}]" id="action_filter_by_carrier_off_{$action->id_quantity_discount_rule_action|intval}" value="0" {if !$action->filter_by_carrier|intval}checked="checked"{/if}/>
                                        <label class="t" for="action_filter_by_carrier_off_{$action->id_quantity_discount_rule_action|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-11 col-lg-offset-1">
                        <div class="row row-margin-top">
                            <div id="carrier_restriction_div">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected carriers' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_carrier_select_1_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                            </div>
                                            <select id="carrier_select_1_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                {foreach from=$action->carrier.unselected item='carrier'}
                                                    <option value="{$carrier.id_carrier|intval}">{$carrier.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#carrier_select_1_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_carrier_select_1_{$action->id_quantity_discount_rule_action|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="carrier_select_add_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected carriers' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="action_carrier_select_json[{$action->id_quantity_discount_rule_action|intval}][]" id="action_carrier_select_2_{$action->id_quantity_discount_rule_action|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_carrier_select_2_{$action->id_quantity_discount_rule_action|intval}" autocomplete="off">
                                            </div>
                                            <select name="action_carrier_select[{$action->id_quantity_discount_rule_action|intval}][]" id="carrier_select_2_{$action->id_quantity_discount_rule_action|intval}" class="input-large" multiple>
                                                {foreach from=$action->carrier.selected item='carrier'}
                                                    <option value="{$carrier.id_carrier|intval}">{$carrier.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#carrier_select_2_{$action->id_quantity_discount_rule_action|intval}').filterByText('#search_carrier_select_2_{$action->id_quantity_discount_rule_action|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="carrier_select_remove_{$action->id_quantity_discount_rule_action|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}

        </div>
    </div>
</div>

<script type="text/javascript">
    {literal}
    $(document).ready(function() {
        hideAllActions($('#action_{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}_container'));
        $('select[name="action_id_type[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}]"], [name="action_group_products_by[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}]"], input[name="action_filter_by_category[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}]"]').each(function() {
            $(this).change(function() {
                showHideActionOptions($(this))
            });
        });

        // Swap fields
        for (i in action_selectors) {
            $('#' + action_selectors[i] + '_select_remove_{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}').click(function() {
                removeQuantityDiscountOption(this);
            });
            $('#' + action_selectors[i] + '_select_add_{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}').click(function() {
                addQuantityDiscountOption(this);
            });
        }

        toggleActionFilters();
        showHideActionOptions();
        displayProductAttributes({/literal}{$action->id_quantity_discount_rule_action|intval}{literal});
    });

    $('[name^=action_filter_by_]').change(function() {
        toggleActionFilters();
    });

    $('[name="giftProductFilter[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}]"]').typeWatch({
        captureLength: 1,
        highlight: false,
        wait: 100,
        callback: function(){ searchProducts($('[name="giftProductFilter[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}]"]')); }
    });

    $('#quantity_discount_rule_form').submit(function() {
        $('<input>').attr({
            type: 'hidden',
            name: 'action_gift_product_attribute[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}]',
            value: $('#ipa_'+$('#action_gift_product\\[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}\\] :selected').val()+'\\[{/literal}{$action->id_quantity_discount_rule_action|intval}{literal}\\] :selected').val()
        }).appendTo($('#quantity_discount_rule_form'));
    });
{/literal}
</script>
