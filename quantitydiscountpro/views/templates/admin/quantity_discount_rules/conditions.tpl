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

{if isset($show_button) && $show_button}
    <div class="form-group">
        <div class="col-lg-12">
            <a href="javascript:addConditionGroup();" class="btn btn-default">
                <i class="icon-plus-sign"></i> {l s='Add new group of conditions' mod='quantitydiscountpro'}
            </a>
        </div>
    </div>
{/if}

<div id="conditions" class="form-group">
    {if isset($conditions) && $conditions|@count}
        {foreach from=$conditions item='condition_group'}
            <div class="panel">
                <div class="row-margin-bottom">
                    <a onclick="javascript:removeConditionGroup(this)" class="btn btn-default">
                        <i class="icon-remove text-danger"></i> {l s='Remove group of conditions' mod='quantitydiscountpro'}
                    </a>
                </div>
                <div class="form-group form-control-static">
                    <div class="conditions_container">
                        {foreach from=$condition_group item='condition'}
                            {include file="$tpl_dir/quantity_discount_rules/condition.tpl" condition=$condition}
                        {/foreach}
                    </div>
                    <div class="col-lg-offset-3 col-lg-7" id="condition_new_condition_loader">
                        <div class="spinner" style="display:none"></div>
                    </div>
                    <div class="col-lg-2">
                        <a onclick="javascript:addCondition(this);" class="btn btn-default pull-right">
                            <i class="icon-plus-sign"></i> {l s='Add new condition' mod='quantitydiscountpro'}
                        </a>
                    </div>
                </div>
            </div>
        {/foreach}
    {/if}
</div>
