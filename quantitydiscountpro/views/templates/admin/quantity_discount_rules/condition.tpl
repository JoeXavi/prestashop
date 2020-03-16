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

<div id="condition_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_container" class="col-lg-12 condition">
    <input type="hidden" id="condition_group[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->id_quantity_discount_rule_group|intval}" />
    <div class="row">
        <div class="col-lg-2">
            <a class="btn btn-default" href="javascript:removeCondition('{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}');">
                <i class="icon-remove text-danger"></i> {l s='Remove condition' mod='quantitydiscountpro'}
            </a>
        </div>
        <div class="col-lg-10">
            <div class="form-group">
                <label class="control-label col-lg-1"><span>{l s='Condition' mod='quantitydiscountpro'}</span></label>
                <div class="col-lg-11">
                    <select name="condition_id_type[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                        <option value="">{l s='-- Choose --' mod='quantitydiscountpro'}</option>
                        <optgroup label="{l s='Customer' mod='quantitydiscountpro'}">
                            <option value="1" {if isset($condition->id_type) && $condition->id_type|intval == 1}selected="selected"{/if}>{l s='Single customer' mod='quantitydiscountpro'}</option>
                            {if isset($condition->group) && (($condition->group.unselected|@count) + ($condition->group.selected|@count)) > 1}<option value="13" {if isset($condition->id_type) && $condition->id_type|intval == 13}selected="selected"{/if}>{l s='Customer group' mod='quantitydiscountpro'}</option>{/if}
                            <option value="2" {if isset($condition->id_type) && $condition->id_type|intval == 2}selected="selected"{/if}>{l s='Customer must be subscribed to newsletter' mod='quantitydiscountpro'}</option>
                            <option value="3" {if isset($condition->id_type) && $condition->id_type|intval == 3}selected="selected"{/if}>{l s='Customer signed up between a date' mod='quantitydiscountpro'}</option>
                            <option value="4" {if isset($condition->id_type) && $condition->id_type|intval == 4}selected="selected"{/if}>{l s='Orders done by customer' mod='quantitydiscountpro'}</option>
                            <option value="5" {if isset($condition->id_type) && $condition->id_type|intval == 5}selected="selected"{/if}>{l s='Amount spent by customer' mod='quantitydiscountpro'}</option>
                            <option value="6" {if isset($condition->id_type) && $condition->id_type|intval == 6}selected="selected"{/if}>{l s='On customer first order' mod='quantitydiscountpro'}</option>
                            <option value="19" {if isset($condition->id_type) && $condition->id_type|intval == 19}selected="selected"{/if}>{l s='Days of membership' mod='quantitydiscountpro'}</option>
                            <option value="20" {if isset($condition->id_type) && $condition->id_type|intval == 20}selected="selected"{/if}>{l s='On customer birthday' mod='quantitydiscountpro'}</option>
                            {if isset($condition->gender) && (($condition->gender.unselected|@count) + ($condition->gender.selected|@count)) > 0}<option value="21" {if isset($condition->id_type) && $condition->id_type|intval == 21}selected="selected"{/if}>{l s='Customer gender' mod='quantitydiscountpro'}</option>{/if}
                            <option value="23" {if isset($condition->id_type) && $condition->id_type|intval == 23}selected="selected"{/if}>{l s='Customer age' mod='quantitydiscountpro'}</option>
                            <option value="25" {if isset($condition->id_type) && $condition->id_type|intval == 25}selected="selected"{/if}>{l s='Customer has VAT' mod='quantitydiscountpro'}</option>
                        </optgroup>
                        <optgroup label="{l s='Cart/Products' mod='quantitydiscountpro'}">
                            <option value="8" {if isset($condition->id_type) && $condition->id_type|intval == 8}selected="selected"{/if}>{l s='Total cart amount' mod='quantitydiscountpro'}</option>
                            <option value="9" {if isset($condition->id_type) && $condition->id_type|intval == 9}selected="selected"{/if}>{l s='Cart weight' mod='quantitydiscountpro'}</option>
                            <option value="10" {if isset($condition->id_type) && $condition->id_type|intval == 10}selected="selected"{/if}>{l s='Products in the cart' mod='quantitydiscountpro'}</option>
                            {if isset($condition->zone) && (($condition->zone.unselected|@count) + ($condition->zone.selected|@count)) > 1}<option value="18" {if isset($condition->id_type) && $condition->id_type|intval == 18}selected="selected"{/if}>{l s='Delivery zone' mod='quantitydiscountpro'}</option>{/if}
                            {if isset($condition->country) && (($condition->country.unselected|@count) + ($condition->country.selected|@count)) > 1}<option value="11" {if isset($condition->id_type) && $condition->id_type|intval == 11}selected="selected"{/if}>{l s='Delivery country' mod='quantitydiscountpro'}</option>{/if}
                            {if isset($condition->state) && (($condition->state.unselected|@count) + ($condition->state.selected|@count)) > 1}<option value="24" {if isset($condition->id_type) && $condition->id_type|intval == 24}selected="selected"{/if}>{l s='Delivery state' mod='quantitydiscountpro'}</option>{/if}
                            {if isset($condition->carrier) && (($condition->carrier.unselected|@count) + ($condition->carrier.selected|@count)) > 1}<option value="12" {if isset($condition->id_type) && $condition->id_type|intval == 12}selected="selected"{/if}>{l s='Carrier' mod='quantitydiscountpro'}</option>{/if}
                            {if isset($condition->shop) && (($condition->shop.unselected|@count) + ($condition->shop.selected|@count)) > 1}<option value="14" {if isset($condition->id_type) && $condition->id_type|intval == 14}selected="selected"{/if}>{l s='Shop selection' mod='quantitydiscountpro'}</option>{/if}
                            {if isset($condition->currency) && (($condition->currency.unselected|@count) + ($condition->currency.selected|@count)) > 1}<option value="22" {if isset($condition->id_type) && $condition->id_type|intval == 22}selected="selected"{/if}>{l s='Currency' mod='quantitydiscountpro'}</option>{/if}
                        </optgroup>
                        <optgroup label="{l s='Other' mod='quantitydiscountpro'}">
                            <option value="26" {if isset($condition->id_type) && $condition->id_type|intval == 26}selected="selected"{/if}>{l s='Day of the week' mod='quantitydiscountpro'}</option>
                            <option value="27" {if isset($condition->id_type) && $condition->id_type|intval == 27}selected="selected"{/if}>{l s='String in the URL' mod='quantitydiscountpro'}</option>
                        </optgroup>
                    </select>
                </div>
            </div>


            <div class="form-group condition_type_options_1">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to selected customer' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_1">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-user"></i></span>
                                <input type="hidden" id="condition_id_customer_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" name="condition_id_customer[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->id_customer|intval}" />
                                <input type="text" id="customerFilter_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-xlarge" name="condition_customer_filter[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_filter|escape:'html':'UTF-8'}" />
                                <span class="input-group-addon"><i class="icon-search"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_2">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to customers who are subscribed or not subscribed to newsletter' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_2">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_customer_newsletter_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_customer_newsletter[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_newsletter_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->customer_newsletter|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_customer_newsletter_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_customer_newsletter[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_newsletter_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->customer_newsletter|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_customer_newsletter_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_3">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to customers who signed up between a date interval' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_3">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='From' mod='quantitydiscountpro'}</span>
                                <input type="text" class="datepicker input-medium" name="condition_customer_signedup_date_from[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                value="{if isset($condition->customer_signedup_date_from)}{$condition->customer_signedup_date_from|escape:'html':'UTF-8'}{/if}" />
                                <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='To' mod='quantitydiscountpro'}</span>
                                <input type="text" class="datepicker input-medium" name="condition_customer_signedup_date_to[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                value="{if isset($condition->customer_signedup_date_to)}{$condition->customer_signedup_date_to|escape:'html':'UTF-8'}{/if}" />
                                <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_4">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to customers who have done a number of orders in last X days or between a date interval' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_4">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-1">
                            <select name="condition_customer_orders_nb_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->customer_orders_nb_operator|intval == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->customer_orders_nb_operator|intval == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->customer_orders_nb_operator|intval == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Number of orders' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_customer_orders_nb[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_orders_nb|intval}" />
                            </div>
                        </div>
                    </div>
                    <p class="inline-label">{l s='- Between -' mod='quantitydiscountpro'}</p>
                    <div class="panel">
                        <div class="row row-margin-top">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='Last X days' mod='quantitydiscountpro'}</span>
                                    <input type="text" name="condition_customer_orders_nb_days[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_orders_nb_days|intval}" />
                                </div>
                            </div>
                        </div>
                        <p class="inline-label">{l s='- OR -' mod='quantitydiscountpro'}</p>
                        <div class="row row-margin-top">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='From' mod='quantitydiscountpro'}</span>
                                    <input type="text" class="datepicker input-medium" name="condition_customer_orders_nb_date_from[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                    value="{if isset($condition->customer_orders_nb_date_from)}{$condition->customer_orders_nb_date_from|escape:'html':'UTF-8'}{/if}" />
                                    <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='To' mod='quantitydiscountpro'}</span>
                                    <input type="text" class="datepicker input-medium" name="condition_customer_orders_nb_date_to[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                    value="{if isset($condition->customer_orders_nb_date_to)}{$condition->customer_orders_nb_date_to|escape:'html':'UTF-8'}{/if}" />
                                    <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_5">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to customers who have spent an amount in last X orders, last X days or between a date interval' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_5">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-1">
                            <select name="condition_customer_orders_amount_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->customer_orders_amount_operator|intval == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->customer_orders_amount_operator|intval == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->customer_orders_amount_operator|intval == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="condition_customer_orders_amount[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_orders_amount|floatval}" />
                        </div>
                        <div class="col-lg-2">
                            <select name="condition_customer_orders_amount_currency[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                            {foreach from=$currencies item='currency'}
                                <option value="{$currency.id_currency|intval}"
                                {if $condition->customer_orders_amount_currency|intval == $currency.id_currency
                                    || (!$condition->customer_orders_amount_currency|intval && $currency.id_currency == $defaultCurrency)}
                                    selected="selected"
                                {/if}
                                >
                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                </option>
                            {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row row-margin-top">
                        <div class="col-lg-3">
                            <select name="condition_customer_orders_amount_tax[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->customer_orders_amount_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->customer_orders_amount_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="condition_customer_orders_amount_shipping[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->customer_orders_amount_shipping|intval == 0}selected="selected"{/if}>{l s='Shipping excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->customer_orders_amount_shipping|intval == 1}selected="selected"{/if}>{l s='Shipping included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="condition_customer_orders_amount_discount[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->customer_orders_amount_discount|intval == 0}selected="selected"{/if}>{l s='Discounts excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->customer_orders_amount_discount|intval == 1}selected="selected"{/if}>{l s='Discounts included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                    </div>
                    <p class="inline-label">{l s='- IN -' mod='quantitydiscountpro'}</p>
                    <div class="row row-margin-top">
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Last X orders' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_customer_orders_amount_orders[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_orders_amount_orders|intval}" />
                            </div>
                        </div>
                    </div>
                    <p class="inline-label">{l s='- Between -' mod='quantitydiscountpro'}</p>
                    <div class="panel">
                        <div class="row row-margin-top">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='Last X days' mod='quantitydiscountpro'}</span>
                                    <input type="text" name="condition_customer_orders_amount_days[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_orders_amount_days|intval}" />
                                </div>
                            </div>
                        </div>
                        <p class="inline-label">{l s='- OR -' mod='quantitydiscountpro'}</p>
                        <div class="row row-margin-top">
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='From' mod='quantitydiscountpro'}</span>
                                    <input type="text" class="datepicker input-medium" name="condition_customer_orders_amount_date_from[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                    value="{$condition->customer_orders_amount_date_from|escape:'html':'UTF-8'}" />
                                    <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='To' mod='quantitydiscountpro'}</span>
                                    <input type="text" class="datepicker input-medium" name="condition_customer_orders_amount_date_to[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                    value="{$condition->customer_orders_amount_date_to|escape:'html':'UTF-8'}" />
                                    <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                                </div>
                            </div>
                        </div>

                        <p class="inline-label">{l s='- OR -' mod='quantitydiscountpro'}</p>
                        <div class="row row-margin-top">
                            <div class="col-lg-12">
                                {*
                                <p class="radio">
                                    <label for="condition_customer_orders_amount_interval_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                        <input type="radio" name="condition_customer_orders_amount_interval[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_orders_amount_interval_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->customer_orders_amount_interval == '1'}checked="checked"{/if} />
                                        {l s='Previous day' mod='quantitydiscountpro'}
                                    </label>
                                </p>
                                <p class="radio">
                                    <label for="condition_customer_orders_amount_interval_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                        <input type="radio" name="condition_customer_orders_amount_interval[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_orders_amount_interval_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="2" {if $condition->customer_orders_amount_interval == '2'}checked="checked"{/if} />
                                        {l s='Last week' mod='quantitydiscountpro'}
                                    </label>
                                </p>
                                <p class="radio">
                                    <label for="condition_customer_orders_amount_interval_3_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                        <input type="radio" name="condition_customer_orders_amount_interval[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_orders_amount_interval_3_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="3" {if $condition->customer_orders_amount_interval == '3'}checked="checked"{/if} />
                                        {l s='Last 15 days' mod='quantitydiscountpro'}
                                    </label>
                                </p>
                                *}
                                <p class="radio">
                                    <label for="condition_customer_orders_amount_interval_4_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                        <input type="radio" name="condition_customer_orders_amount_interval[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_orders_amount_interval_4_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="4" {if $condition->customer_orders_amount_interval == '4'}checked="checked"{/if} />
                                        {l s='Last month' mod='quantitydiscountpro'}
                                    </label>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_6">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only on customers\' first order or not on first order' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_6">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_customer_first_order_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_customer_first_order[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_first_order_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->customer_first_order|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_customer_first_order_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_customer_first_order[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_first_order_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->customer_first_order|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_customer_first_order_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_8">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to the carts with the amount defined' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_8">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-1">
                            <select name="condition_cart_amount_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->cart_amount_operator|intval == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->cart_amount_operator|intval == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->cart_amount_operator|intval == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="condition_cart_amount[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->cart_amount|floatval}" />
                        </div>
                        <div class="col-lg-2">
                            <select name="condition_cart_amount_currency[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                            {foreach from=$currencies item='currency'}
                                <option value="{$currency.id_currency|intval}"
                                {if $condition->cart_amount_currency|intval == $currency.id_currency
                                    || (!$condition->cart_amount_currency|intval && $currency.id_currency == $defaultCurrency)}
                                    selected="selected"
                                {/if}
                                >
                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                </option>
                            {/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="row row-margin-top">
                        <div class="col-lg-3">
                            <select name="condition_cart_amount_tax[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->cart_amount_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->cart_amount_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="condition_cart_amount_shipping[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->cart_amount_shipping|intval == 0}selected="selected"{/if}>{l s='Shipping excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->cart_amount_shipping|intval == 1}selected="selected"{/if}>{l s='Shipping included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="condition_cart_amount_discount[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->cart_amount_discount|intval == 0}selected="selected"{/if}>{l s='Discounts excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->cart_amount_discount|intval == 1}selected="selected"{/if}>{l s='Discounts included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_9">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to the carts with the weight defined' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_9">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-1">
                            <select name="condition_cart_weight_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->cart_weight_operator == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->cart_weight_operator == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->cart_weight_operator == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon">{Configuration::get('PS_WEIGHT_UNIT')|escape:'html':'UTF-8'}</span>
                                <input type="text" name="condition_cart_weight[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->cart_weight|floatval}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to the carts with an amount of selected products. Offer a discount if customer purchases more than 200€ p.ex. from a selected category.' mod='quantitydiscountpro'}</li>
                            <li>{l s='Apply rule only to the carts with a number of selected products. Offer a discount if customer purchases more than 10 items p.ex. from a selected category.' mod='quantitydiscountpro'}</li>
                            <li>{l s='Apply rule only to the carts with a different number of products. Offer a discount if customer purchases more than 10 different products p.ex. from a selected category.' mod='quantitydiscountpro'}</li>
                            <li>{l s='Apply rule only to the carts with a number of products of the same category. Offer a discount if customer purchases more than 10 items p.ex. from the same category.' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-1">
                            <select name="condition_products_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->products_operator|intval == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->products_operator|intval == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->products_operator|intval == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Amount of selected products in cart' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_products_amount[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->products_amount|floatval}" />
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <select name="condition_products_amount_currency[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                            {foreach from=$currencies item='currency'}
                                <option value="{$currency.id_currency|intval}"
                                {if $condition->products_amount_currency|intval == $currency.id_currency
                                    || (!$condition->products_amount_currency|intval && $currency.id_currency == $defaultCurrency)}
                                    selected="selected"
                                {/if}
                                >
                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                </option>
                            {/foreach}
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select name="condition_products_amount_tax[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->products_amount_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                <option value="1" {if $condition->products_amount_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                            </select>
                        </div>
                        <div class="col-lg-12 help-block">{l s='If cart amount is higher, lower or equal to the amount defined, rule will be applied' mod='quantitydiscountpro'}</div>
                    </div>
                    <div class="row row-margin-top">
                        <div class="col-lg-1">
                            <select name="condition_products_nb_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->products_nb_operator == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->products_nb_operator == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->products_nb_operator == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Quantity of selected products in cart' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_products_nb[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->products_nb|intval}" />
                            </div>
                        </div>
                        <div class="col-lg-12 help-block">{l s='Number of selected products in the cart considering all the items from the selected products' mod='quantitydiscountpro'}</div>
                    </div>
                </div>
            </div>

            {*
            <div class="form-group condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <div class="col-lg-5">
                            <label class="control-label">{l s='All products must be the same' mod='quantitydiscountpro'}</label>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_products_nb_same_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_products_nb_same[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_nb_same_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->products_nb_same|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_products_nb_same_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_products_nb_same[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_nb_same_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->products_nb_same|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_products_nb_same_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 help-block">{l s='You could apply a discount if customer purchases X products of any type or only if customer purchases X items of the same product.' mod='quantitydiscountpro'}</div>
                    </div>
                </div>
            </div>
            *}

            <div class="form-group condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <div class="col-lg-1">
                            <select name="condition_products_nb_dif_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->products_nb_dif_operator == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->products_nb_dif_operator == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->products_nb_dif_operator == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Number of different products from selected products in cart' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_products_nb_dif[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->products_nb_dif|intval}" />
                            </div>
                        </div>
                        <div class="col-lg-12 help-block">{l s='You could only apply a discount if customer purchases X different products. For example, if you set 3, rule will be applied if customer purchases 1 shirt model A,  1 shirt model B and 3 shirts model C, but not 2 shirts model A and 4 shirts model B, because there aren\'t 3 different products ' mod='quantitydiscountpro'}</div>
                    </div>
                    <div class="row row-margin-top">
                        <div class="col-lg-1">
                            <select name="condition_products_nb_dif_cat_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->products_nb_dif_cat_operator == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->products_nb_dif_cat_operator == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->products_nb_dif_cat_operator == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Quantity of products from the same category in cart' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_products_nb_dif_cat[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->products_nb_dif_cat|intval}" />
                            </div>
                        </div>
                        <div class="col-lg-12 help-block">{l s='You could only apply a discount if customer purchases X products from the same product default category. For example rule will be applied if customer purchases 3 shirts of any model of category "Vintage shirts"' mod='quantitydiscountpro'}</div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_8 condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-5">
                            <label class="control-label">{l s='Consider products with special price' mod='quantitydiscountpro'}</label>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_apply_discount_to_special_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_apply_discount_to_special[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_apply_discount_to_special_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->apply_discount_to_special|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_apply_discount_to_special_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_apply_discount_to_special[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_apply_discount_to_special_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->apply_discount_to_special|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_apply_discount_to_special_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                            <div class="help-block">{l s='If disabled, products with special price will be discarded' mod='quantitydiscountpro'}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-5">
                            <label class="control-label">{l s='Consider products with different attributes as the same product' mod='quantitydiscountpro'}</label>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_products_nb_same_attributes_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_products_nb_same_attributes[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_nb_same_attributes_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->products_nb_same_attributes|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_products_nb_same_attributes_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_products_nb_same_attributes[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_nb_same_attributes_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->products_nb_same_attributes|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_products_nb_same_attributes_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                            <div class="help-block">{l s='If enabled, 1 item Product A - Color A and 1 from Product A - Color B would be considered as 2 items from Product A. If disabled each item would be considered as different products' mod='quantitydiscountpro'}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-5">
                            <label class="control-label">{l s='All products in the cart must met the selection' mod='quantitydiscountpro'}</label>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_products_all_met_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_products_all_met[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_all_met_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->products_all_met|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_products_all_met_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_products_all_met[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_all_met_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->products_all_met|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_products_all_met_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                            <div class="help-block">{l s='If any of the products is not within the selected filters, rule won\'t be applied. For example if you want to apply a discount for product X but cart contains product X and product Y, if you enable this option the discount will not be applied, it will only be applied to those carts which only contains product X.' mod='quantitydiscountpro'}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_4 condition_type_options_5">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row row-margin-top">
                        <p class="inline-label condition_type_options_4">{l s='- With the order states -' mod='quantitydiscountpro'}</p>
                        <div class="col-lg-12">
                            <div id="order_state_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected order states' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_order_state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="order_state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->order_state.unselected item='order_state'}
                                                    <option value="{$order_state.id_order_state|intval}">{$order_state.name|escape:'html':'UTF-8'} (ID: {$order_state.id_order_state|intval})</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#order_state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_order_state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="order_state_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix">{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected order states' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_order_state_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_order_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_order_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_order_state_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="order_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->order_state.selected item='order_state'}
                                                    <option value="{$order_state.id_order_state|intval}">{$order_state.name|escape:'html':'UTF-8'} (ID: {$order_state.id_order_state|intval})</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#order_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_order_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="order_state_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix"><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_4 condition_type_options_10">
                <div class="col-lg-11 col-lg-offset-1">
                    <p class="inline-label condition_type_options_4">{l s='- With the selected products bought -' mod='quantitydiscountpro'}</p>
                    <div class="panel">
                        <div class="panel-heading">{l s='Product filters' mod='quantitydiscountpro'}</div>

                        <div class="row row-margin-top condition_type_options_4">
                            <div class="col-lg-1">
                                <select name="condition_customer_orders_nb_prod_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                    <option value="0" {if $condition->customer_orders_nb_prod_operator|intval == 0}selected="selected"{/if}>>=</option>
                                    <option value="1" {if $condition->customer_orders_nb_prod_operator|intval == 1}selected="selected"{/if}>=</option>
                                    <option value="2" {if $condition->customer_orders_nb_prod_operator|intval == 2}selected="selected"{/if}><=</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-addon">{l s='Number of products bought' mod='quantitydiscountpro'}</span>
                                    <input type="text" name="condition_customer_orders_nb_prod[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_orders_nb_prod|intval}" />
                                </div>
                            </div>
                        </div>

                        {if isset($condition->product) && (($condition->product.unselected|@count) + ($condition->product.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by product' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_product_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_filter_by_product[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_product_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_product|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_filter_by_product_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_filter_by_product[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_product_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_product|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_filter_by_product_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row">
                                        <div id="product_restriction_div" class="row">
                                            <table class="table">
                                                <tr>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Unselected products' mod='quantitydiscountpro'}</p>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                        </div>
                                                        <select id="product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->product.unselected|json_encode};
                                                                var product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                                for (var i = 0; i < product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                    product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_product + "' > " + product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + "  (ID: "+product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_product+(product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].reference ? " - Reference: "+product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].reference : "")+")</option>";
                                                                }
                                                                $("#product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                                $('#product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_product_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="product_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                    </td>
                                                    <td class="col-xs-6">
                                                        <p>{l s='Selected products' mod='quantitydiscountpro'}</p>
                                                        <input type="hidden" name="condition_product_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                            <input type="text" class="search_select" id="search_product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                        </div>
                                                        <select name="condition_product_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                        </select>
                                                        <script type="text/javascript">
                                                            {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                                var product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->product.selected|json_encode};
                                                                var product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                                for (var i = 0; i < product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                    product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_product + "' > " + product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + "  (ID: "+product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_product+(product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].reference ? " - Reference: "+product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].reference : "")+")</option>";
                                                                }
                                                                $("#product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                                $('#product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_product_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                        </script>
                                                        <a id="product_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($condition->attribute) && (($condition->attribute.unselected|@count) + ($condition->attribute.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by attribute' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_attribute_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_filter_by_attribute[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_attribute_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_attribute|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_filter_by_attribute_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_filter_by_attribute[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_attribute_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_attribute|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_filter_by_attribute_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row" id="attribute_restriction_div">
                                        <table class="table">
                                            <tr>
                                                <td class="col-xs-6">
                                                    <p>{l s='Unselected attributes' mod='quantitydiscountpro'}</p>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select id="attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            var attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->attribute.unselected|json_encode};
                                                            var attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                            for (var i = 0; i < attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_attribute + "' > " + attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + "  (ID: "+attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_attribute+")</option>";
                                                            }
                                                            $("#attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                            $('#attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_attribute_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                        {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="attribute_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                </td>
                                                <td class="col-xs-6">
                                                    <p>{l s='Selected attributes' mod='quantitydiscountpro'}</p>
                                                    <input type="hidden" name="condition_attribute_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select name="condition_attribute_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            var attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->attribute.selected|json_encode};
                                                            var attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                            for (var i = 0; i < attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_attribute + "' > " + attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + "  (ID: "+attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_attribute+")</option>";
                                                            }
                                                            $("#attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                            $('#attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_attribute_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                        {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="attribute_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($condition->feature) && (($condition->feature.unselected|@count) + ($condition->feature.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by feature' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_feature_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_filter_by_feature[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_feature_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_feature|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_filter_by_feature_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_filter_by_feature[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_feature_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_feature|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_filter_by_feature_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row" id="feature_restriction_div">
                                        <table class="table">
                                            <tr>
                                                <td class="col-xs-6">
                                                    <p>{l s='Unselected features' mod='quantitydiscountpro'}</p>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select id="feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            var feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->feature.unselected|json_encode};
                                                            var feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                            for (var i = 0; i < feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_feature + "' > " + feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + " (ID: " + feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_feature + ")</option>";
                                                            }
                                                            $("#feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                            $('#feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_feature_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                        {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="feature_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                </td>
                                                <td class="col-xs-6">
                                                    <p>{l s='Selected features' mod='quantitydiscountpro'}</p>
                                                    <input type="hidden" name="condition_feature_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select name="condition_feature_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            var feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->feature.selected|json_encode};
                                                            var feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                            for (var i = 0; i < feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_feature + "' > " + feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + " (ID: " + feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_feature + ")</option>";
                                                            }
                                                            $("#feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                            $('#feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_feature_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                        {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="feature_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($condition->category) && (($condition->category.unselected|@count) + ($condition->category.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by category' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_category_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_filter_by_category[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_category_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_category|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_filter_by_category_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_filter_by_category[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_category_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_category|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_filter_by_category_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row" id="category_restriction_div">
                                        <table class="table">
                                            <tr>
                                                <td class="col-xs-6">
                                                    <p>{l s='Unselected categories' mod='quantitydiscountpro'}</p>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select id="category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            var category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->category.unselected|json_encode};
                                                            var category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                            for (var i = 0; i < category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_category + "' > " + category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + " (ID: " + category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_category + ")</option>";
                                                            }
                                                            $("#category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                            $('#category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_category_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                        {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="category_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                </td>
                                                <td class="col-xs-6">
                                                    <p>{l s='Selected categories' mod='quantitydiscountpro'}</p>
                                                    <input type="hidden" name="condition_category_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select name="condition_category_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            var category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values = {$condition->category.selected|json_encode};
                                                            var category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options = '';
                                                            for (var i = 0; i < category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values.length; i++) {
                                                                category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options += "<option value='" + category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_category + "' > " + category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].name + " (ID: " + category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_values[i].id_category + ")</option>";
                                                            }
                                                            $("#category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").html(category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_options);
                                                            $('#category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_category_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                        {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="category_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Consider only product default category' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_products_default_category_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_products_default_category[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_default_category_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->products_default_category|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_products_default_category_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_products_default_category[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_products_default_category_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->products_default_category|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_products_default_category_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                            <div class="help-block">{l s='CAUTION! If you disable this option and select more than one category, discounts can be duplicated if a product belongs to more than 1 selected category' mod='quantitydiscountpro'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($condition->supplier) && (($condition->supplier.unselected|@count) + ($condition->supplier.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by supplier' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_supplier_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_filter_by_supplier[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_supplier_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_supplier|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_filter_by_supplier_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_filter_by_supplier[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_supplier_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_supplier|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_filter_by_supplier_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row" id="supplier_restriction_div">
                                        <table class="table">
                                            <tr>
                                                <td class="col-xs-6">
                                                    <p>{l s='Unselected suppliers' mod='quantitydiscountpro'}</p>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_supplier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select id="supplier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                        {foreach from=$condition->supplier.unselected item='supplier'}
                                                            <option value="{$supplier.id_supplier|intval}">{$supplier.name|escape:'html':'UTF-8'} (ID: {$supplier.id_supplier|escape:'html':'UTF-8'})</option>
                                                        {/foreach}
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            $('#supplier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_supplier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="supplier_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                </td>
                                                <td class="col-xs-6">
                                                    <p>{l s='Selected suppliers' mod='quantitydiscountpro'}</p>
                                                    <input type="hidden" name="condition_supplier_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_supplier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_supplier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select name="condition_supplier_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="supplier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                        {foreach from=$condition->supplier.selected item='supplier'}
                                                            <option value="{$supplier.id_supplier|intval}">{$supplier.name|escape:'html':'UTF-8'} (ID: {$supplier.id_supplier|escape:'html':'UTF-8'})</option>
                                                        {/foreach}
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            $('#supplier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_supplier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="supplier_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($condition->manufacturer) && (($condition->manufacturer.unselected|@count) + ($condition->manufacturer.selected|@count)) > 0}
                            <div class="row row-margin-top">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <label class="control-label">{l s='Filter by manufacturer' mod='quantitydiscountpro'}</label>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_manufacturer_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                    <input type="radio" name="condition_filter_by_manufacturer[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_manufacturer_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_manufacturer|intval}checked="checked"{/if} />
                                                    <label class="t" for="condition_filter_by_manufacturer_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                    <input type="radio" name="condition_filter_by_manufacturer[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_manufacturer_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_manufacturer|intval}checked="checked"{/if}/>
                                                    <label class="t" for="condition_filter_by_manufacturer_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                    <a class="slide-button btn"></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 row-margin-top">
                                    <div class="row" id="manufacturer_restriction_div">
                                        <table class="table">
                                            <tr>
                                                <td class="col-xs-6">
                                                    <p>{l s='Unselected manufacturers' mod='quantitydiscountpro'}</p>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_manufacturer_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select id="manufacturer_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                        {foreach from=$condition->manufacturer.unselected item='manufacturer'}
                                                            <option value="{$manufacturer.id_manufacturer|intval}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
                                                        {/foreach}
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            $('#manufacturer_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_manufacturer_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="manufacturer_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                                </td>
                                                <td class="col-xs-6">
                                                    <p>{l s='Selected manufacturers' mod='quantitydiscountpro'}</p>
                                                    <input type="hidden" name="condition_manufacturer_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_manufacturer_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                        <input type="text" class="search_select" id="search_manufacturer_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                                    </div>
                                                    <select name="condition_manufacturer_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="manufacturer_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                        {foreach from=$condition->manufacturer.selected item='manufacturer'}
                                                            <option value="{$manufacturer.id_manufacturer|intval}">{$manufacturer.name|escape:'html':'UTF-8'}</option>
                                                        {/foreach}
                                                    </select>
                                                    <script type="text/javascript">
                                                        {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                            $('#manufacturer_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_manufacturer_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                            {if !$ajax}{literal}});{/literal}{/if}
                                                    </script>
                                                    <a id="manufacturer_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                                </td>
                                            </tr>
                                        </table>
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
                                            <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_price_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                <input type="radio" name="condition_filter_by_price[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_price_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_price|intval}checked="checked"{/if} />
                                                <label class="t" for="condition_filter_by_price_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="condition_filter_by_price[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_price_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_price|intval}checked="checked"{/if}/>
                                                <label class="t" for="condition_filter_by_price_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
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
                                        <input type="text" name="condition_product_price_from[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->product_price_from|floatval}" />
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="condition_product_price_from_currency[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" data-group="currency2[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                            {foreach from=$currencies item='currency'}
                                                <option value="{$currency.id_currency|intval}"
                                                    {if $condition->product_price_from_currency|intval == $currency.id_currency
                                                    || (!$condition->product_price_from_currency|intval && $currency.id_currency == $defaultCurrency)}
                                                        selected="selected"
                                                    {/if}
                                                >
                                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="condition_product_price_from_tax[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" data-group="tax2[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                            <option value="0" {if $condition->product_price_from_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                            <option value="1" {if $condition->product_price_from_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row row-margin-top">
                                    <div class="col-lg-offset-1 col-lg-4">
                                        <label class="control-label">{l s='To' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" name="condition_product_price_to[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->product_price_to|floatval}" />
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="condition_product_price_to_currency[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" data-group="currency2[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                            {foreach from=$currencies item='currency'}
                                                <option value="{$currency.id_currency|intval}"
                                                    {if $condition->product_price_to_currency|intval == $currency.id_currency
                                                    || (!$condition->product_price_to_currency|intval && $currency.id_currency == $defaultCurrency)}
                                                        selected="selected"
                                                    {/if}
                                                >
                                                    {$currency.iso_code|escape:'html':'UTF-8'}
                                                </option>
                                            {/foreach}
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <select name="condition_product_price_to_tax[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" data-group="tax2[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                            <option value="0" {if $condition->product_price_to_tax|intval == 0}selected="selected"{/if}>{l s='Tax excluded' mod='quantitydiscountpro'}</option>
                                            <option value="1" {if $condition->product_price_to_tax|intval == 1}selected="selected"{/if}>{l s='Tax included' mod='quantitydiscountpro'}</option>
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
                                            <span class="switch prestashop-switch fixed-width-lg" id="condition_filter_by_stock_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                                <input type="radio" name="condition_filter_by_stock[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_stock_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->filter_by_stock|intval}checked="checked"{/if} />
                                                <label class="t" for="condition_filter_by_stock_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                                <input type="radio" name="condition_filter_by_stock[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_filter_by_stock_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->filter_by_stock|intval}checked="checked"{/if}/>
                                                <label class="t" for="condition_filter_by_stock_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                                <a class="slide-button btn"></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-12 row-margin-top">
                                <div class="row">
                                    <div class="col-lg-5">
                                        <label class="control-label">{l s='Only consider products with this stock' mod='quantitydiscountpro'}</label>
                                    </div>
                                    <div class="col-lg-1">
                                        <select name="condition_product_stock_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                            <option value="0" {if $condition->product_stock_operator|intval == 0}selected="selected"{/if}>>=</option>
                                            <option value="1" {if $condition->product_stock_operator|intval == 1}selected="selected"{/if}>=</option>
                                            <option value="2" {if $condition->product_stock_operator|intval == 2}selected="selected"{/if}><=</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" name="condition_product_stock_amount[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->product_stock_amount|floatval}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_11">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to the orders delivered to selected countries.' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_11">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="country_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected countries' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_country_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="country_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->country.unselected item='country'}
                                                    <option value="{$country.id_country|intval}">{$country.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#country_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_country_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="country_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected countries' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_country_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_country_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_country_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_country_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="country_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->country.selected item='country'}
                                                    <option value="{$country.id_country|intval}">{$country.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#country_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_country_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="country_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_18">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to the orders delivered to selected zones.' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_18">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="zone_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected zones' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_zone_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="zone_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->zone.unselected item='zone'}
                                                    <option value="{$zone.id_zone|intval}">{$zone.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#zone_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_zone_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="zone_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected zones' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_zone_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_zone_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_zone_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_zone_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="zone_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->zone.selected item='zone'}
                                                    <option value="{$zone.id_zone|intval}">{$zone.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#zone_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_zone_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="zone_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_12">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to the orders delivered with selected carriers.' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_12">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="carrier_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected carriers' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_carrier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="carrier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->carrier.unselected item='carrier'}
                                                    <option value="{$carrier.id_carrier|intval}">{$carrier.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#carrier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_carrier_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="carrier_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected carriers' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_carrier_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_carrier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_carrier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_carrier_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="carrier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->carrier.selected item='carrier'}
                                                    <option value="{$carrier.id_carrier|intval}">{$carrier.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#carrier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_carrier_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="carrier_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {if isset($condition->group) && (($condition->group.unselected|@count) + ($condition->group.selected|@count)) > 0}
            <div class="form-group condition_type_options_13">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to selected groups' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_13">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="group_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected groups' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_group_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="group_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->group.unselected item='group'}
                                                    <option value="{$group.id_group|intval}">{$group.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#group_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_group_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="group_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected groups' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_group_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_group_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_group_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_group_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" class="input-large" id="group_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" multiple>
                                                {foreach from=$condition->group.selected item='group'}
                                                    <option value="{$group.id_group|intval}">{$group.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#group_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_group_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="group_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row row-margin-top">
                                <label class="control-label col-lg-4">{l s='Consider only default customer group' mod='quantitydiscountpro'}</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <span class="switch prestashop-switch fixed-width-lg" id="condition_customer_default_group_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                            <input type="radio" name="condition_customer_default_group[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_default_group_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->customer_default_group|intval}checked="checked"{/if} />
                                            <label class="t" for="condition_customer_default_group_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                            <input type="radio" name="condition_customer_default_group[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_default_group_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->customer_default_group|intval}checked="checked"{/if}/>
                                            <label class="t" for="condition_customer_default_group_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                            <a class="slide-button btn"></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}

            {if isset($condition->shop) && (($condition->shop.unselected|@count) + ($condition->shop.selected|@count)) > 0}
            <div class="form-group condition_type_options_14">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="shop_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected shops' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_shop_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="shop_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" multiple>
                                                {foreach from=$condition->shop.unselected item='shop'}
                                                    <option value="{$shop.id_shop|intval}">{$shop.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#shop_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_shop_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="shop_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected shops' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_shop_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_shop_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_shop_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_shop_select[]" id="shop_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" multiple>
                                                {foreach from=$condition->shop.selected item='shop'}
                                                    <option value="{$shop.id_shop|intval}">{$shop.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#shop_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_shop_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="shop_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/if}

            <div class="form-group condition_type_options_19">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only to customers who have more than X days of membership' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_19">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-1">
                            <select name="condition_customer_membership_operator[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]">
                                <option value="0" {if $condition->customer_membership_operator|intval == 0}selected="selected"{/if}>>=</option>
                                <option value="1" {if $condition->customer_membership_operator|intval == 1}selected="selected"{/if}>=</option>
                                <option value="2" {if $condition->customer_membership_operator|intval == 2}selected="selected"{/if}><=</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='Number of days' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_customer_membership[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->customer_membership|intval}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_20">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="alert alert-info">
                        <h4>{l s='Which rules can you create with this condition?' mod='quantitydiscountpro'}</h4>
                        <ol style="list-style-type: disc">
                            <li>{l s='Apply rule only on customers\' birthday' mod='quantitydiscountpro'}</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="form-group condition_type_options_20">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_customer_birthday_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_customer_birthday[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_birthday_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->customer_birthday|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_customer_birthday_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_customer_birthday[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_birthday_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->customer_birthday|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_customer_birthday_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_21">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="gender_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected genders' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_gender_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="gender_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->gender.unselected item='gender'}
                                                    <option value="{$gender.id_gender|intval}">{$gender.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#gender_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_gender_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="gender_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected genders' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_gender_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_gender_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_gender_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_gender_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" class="input-large" id="gender_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" multiple>
                                                {foreach from=$condition->gender.selected item='gender'}
                                                    <option value="{$gender.id_gender|intval}">{$gender.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#gender_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_gender_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="gender_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_22">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="currency_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected currencies' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_currency_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="currency_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->currency.unselected item='currency'}
                                                    <option value="{$currency.id_currency|intval}">{$currency.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#currency_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_currency_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="currency_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected currencies' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_currency_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_currency_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_currency_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_currency_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" class="input-large" id="currency_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" multiple>
                                                {foreach from=$condition->currency.selected item='currency'}
                                                    <option value="{$currency.id_currency|intval}">{$currency.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#currency_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_currency_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="currency_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'}</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group condition_type_options_23">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='From' mod='quantitydiscountpro'}</span>
                                <input type="text" class="input-medium" name="condition_customer_years_from[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                value="{if isset($condition->customer_years_from)}{$condition->customer_years_from|escape:'html':'UTF-8'}{/if}" />
                                <span class="input-group-addon">{l s='years' mod='quantitydiscountpro'}</span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='To' mod='quantitydiscountpro'}</span>
                                <input type="text" class="input-medium" name="condition_customer_years_to[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]"
                                value="{if isset($condition->customer_years_to)}{$condition->customer_years_to|escape:'html':'UTF-8'}{/if}" />
                                <span class="input-group-addon">{l s='years' mod='quantitydiscountpro'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_24">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="state_restriction_div" class="row">
                                <table class="table">
                                    <tr>
                                        <td class="col-xs-6">
                                            <p>{l s='Unselected states' mod='quantitydiscountpro'}</p>
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select id="state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->state.unselected item='state'}
                                                    <option value="{$state.id_state|intval}">{$state.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_state_select_1_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="state_select_add_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" >{l s='Add' mod='quantitydiscountpro'} <i class="icon-arrow-right"></i></a>
                                        </td>
                                        <td class="col-xs-6">
                                            <p>{l s='Selected states' mod='quantitydiscountpro'}</p>
                                            <input type="hidden" name="condition_state_select_json[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="condition_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}_json" />
                                            <div class="input-group">
                                                <span class="input-group-addon">{l s='Search' mod='quantitydiscountpro'}</span>
                                                <input type="text" class="search_select" id="search_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" autocomplete="off">
                                            </div>
                                            <select name="condition_state_select[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}][]" id="state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="input-large" multiple>
                                                {foreach from=$condition->state.selected item='state'}
                                                    <option value="{$state.state|intval}">{$state.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </select>
                                            <script type="text/javascript">
                                                {if !$ajax}{literal}$(window).load(function() {{/literal}{/if}
                                                    $('#state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}').filterByText('#search_state_select_2_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}', true);
                                                    {if !$ajax}{literal}});{/literal}{/if}
                                            </script>
                                            <a id="state_select_remove_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" class="btn btn-default btn-block clearfix" ><i class="icon-arrow-left"></i> {l s='Remove' mod='quantitydiscountpro'} </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_25">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <span class="switch prestashop-switch fixed-width-lg" id="condition_customer_vat_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">
                                    <input type="radio" name="condition_customer_vat[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_vat_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="1" {if $condition->customer_vat|intval}checked="checked"{/if} />
                                    <label class="t" for="condition_customer_vat_on_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='Yes' mod='quantitydiscountpro'}</label>
                                    <input type="radio" name="condition_customer_vat[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" id="condition_customer_vat_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" value="0" {if !$condition->customer_vat|intval}checked="checked"{/if}/>
                                    <label class="t" for="condition_customer_vat_off_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}">{l s='No' mod='quantitydiscountpro'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group condition_type_options_26">
                <div class="col-lg-11 col-lg-offset-1">
                    <input type="hidden" id="condition_schedule_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}" name="condition_schedule[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value=""/>
                    <div id="scheduleContainer"></div>
                    <script>
                        $('document').ready(function() {
                            var businessHoursManager = $("#scheduleContainer").businessHours({
                                {if isset($condition->schedule) && $condition->schedule != ''}operationTime:{$condition->schedule|escape:'quotes':'UTF-8'},{/if}
                                weekdays:[{l s="'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'" mod='quantitydiscountpro'}],
                                defaultOperationTimeFrom:"00:00",
                                defaultOperationTimeTill:"23:59",
                                postInit:function(){
                                    $('.operationTimeFrom, .operationTimeTill').timepicker({
                                        'timeFormat': 'H:i',
                                        'step': 15
                                    });
                                },
                                dayTmpl:'<div class="dayContainer col-xs-3 col-md-2 col-lg-1"><div class="weekday"></div>' +
                                    '<div data-original-title="" class="colorBox"><input type="checkbox" class="invisible operationState"></div>' +
                                    '<div class="operationDayTimeContainer">' +
                                        '<div class="operationTime input-group"><span class="input-group-addon"><i class="icon icon-sun"></i></span><input type="text" name="startTime" class="mini-time form-control operationTimeFrom" value=""></div>' +
                                        '<div class="operationTime input-group"><span class="input-group-addon"><i class="icon icon-moon"></i></span><input type="text" name="endTime" class="mini-time form-control operationTimeTill" value=""></div>' +
                                    '</div></div>'
                            });

                            $("#condition_schedule_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").val(JSON.stringify(businessHoursManager.serialize()));

                            $('.dayContainer .operationState').change(function() {
                                $("#condition_schedule_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").val(JSON.stringify(businessHoursManager.serialize()));
                            });

                            $('.dayContainer .mini-time').change(function() {
                                $("#condition_schedule_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}").val(JSON.stringify(businessHoursManager.serialize()));
                            });
                        });
                    </script>
                </div>
            </div>

            <div class="form-group condition_type_options_27">
                <div class="col-lg-11 col-lg-offset-1">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon">{l s='String' mod='quantitydiscountpro'}</span>
                                <input type="text" name="condition_url_string[{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}]" value="{$condition->url_string|escape:'html':'UTF-8'}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{literal}
<script type="text/javascript">
    $(document).ready(function() {

        hideAllConditions($('#condition_{/literal}{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}{literal}_container'));
        $('select[name="condition_id_type[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]').each(function() {
            $(this).change(function() {toggleConditions($(this))});
            toggleConditions($(this));
        });

        $('#customerFilter{/literal}_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}{literal}')
            .autocomplete(
                'ajax-tab.php', {
                    minChars: 2,
                    max: 50,
                    width: 500,
                    selectFirst: false,
                    scroll: false,
                    dataType: 'json',
                    formatItem: function(data, i, max, value, term) {
                        return value;
                    },
                    parse: function(data) {
                        var mytab = new Array();
                        for (var i = 0; i < data.length; i++)
                            mytab[mytab.length] = { data: data[i], value: data[i].cname + ' (' + data[i].email + ')' };
                        return mytab;
                    },
                    extraParams: {
                        controller: 'AdminQuantityDiscountRules',
                        token: currentToken,
                        customerFilter: 1
                    }
                }
            )
            .result(function(event, data, formatted) {
                $('#condition_id_customer{/literal}_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}{literal}').val(data.id_customer);
                $('#customerFilter{/literal}_{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}{literal}').val(data.cname + ' (' + data.email + ')');
            }
        );

        var dateFields = [
            '.datepicker[name="condition_customer_signedup_date_from[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_signedup_date_to[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_orders_nb_date_from[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_orders_nb_date_to[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_orders_amount_date_from[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_orders_amount_date_to[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_orders_nb_date_from[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            '.datepicker[name="condition_customer_orders_nb_date_to[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]"]',
            ];

        {/literal}

        {if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
            $(dateFields.join(', ')).datepicker({
                prevText: '',
                nextText: '',
                dateFormat: 'yy-mm-dd',
                // Define a custom regional settings in order to use PrestaShop translation tools
                currentText: currentText,
                closeText:closeText,
                ampm: false,
                amNames: ['AM', 'A'],
                pmNames: ['PM', 'P'],
                timeFormat: 'hh:mm:ss tt',
                timeSuffix: '',
                timeOnlyTitle: timeOnlyTitle,
                timeText: timeText,
                hourText: hourText,
                minuteText: minuteText,
            });
        {else}
            $(dateFields.join(', ')).datetimepicker({
                prevText: '',
                nextText: '',
                dateFormat: 'yy-mm-dd',
                // Define a custom regional settings in order to use PrestaShop translation tools
                currentText: currentText,
                closeText:closeText,
                ampm: false,
                amNames: ['AM', 'A'],
                pmNames: ['PM', 'P'],
                timeFormat: 'hh:mm:ss tt',
                timeSuffix: '',
                timeOnlyTitle: timeOnlyTitle,
                timeText: timeText,
                hourText: hourText,
                minuteText: minuteText,
            });
        {/if}
        {literal}

        // Swap fields
        for (i in condition_selectors) {
            $('#' + condition_selectors[i] + '_select_remove_{/literal}{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}{literal}').click(function() { removeQuantityDiscountOption(this); });
            $('#' + condition_selectors[i] + '_select_add_{/literal}{$condition->id_quantity_discount_rule_group|intval}_{$condition->id_quantity_discount_rule_condition|intval}{literal}').click(function() { addQuantityDiscountOption(this); });
        }

        toggleFilters();

        // Uncheck radio
        if ($("input:radio[name='condition_customer_orders_amount_interval[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]']").is(':checked')) {
            $("input:radio[name='condition_customer_orders_amount_interval[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]']").attr('previousValue', 'checked');
        } else {
            $("input:radio[name='condition_customer_orders_amount_interval[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]']").attr('previousValue', false);
        }

        $("input:radio[name='condition_customer_orders_amount_interval[{/literal}{$condition->id_quantity_discount_rule_group|intval}][{$condition->id_quantity_discount_rule_condition|intval}{literal}]']").click(function() {
            var previousValue = $(this).attr('previousValue');
            var name = $(this).attr('name');

            if (previousValue == 'checked')
            {
                $(this).removeAttr('checked');
                $(this).attr('previousValue', false);
            }
            else
            {
                $("input[name='"+name+"']:radio").attr('previousValue', false);
                $(this).attr('previousValue', 'checked');
            }
        });
    });

    $('[name^=condition_filter_by_]').on('change', function() {
        toggleFilters();
    });

</script>
{/literal}
