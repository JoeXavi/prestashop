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

<div id="actions" class="form-group">
	{if isset($actions) && $actions|@count}
		<div class="panel" id="condition-selected">
			<div class="form-group form-control-static">
				<div class="actions_container">
					{foreach name=actions from=$actions item='action'}
						{include file="$tpl_dir/quantity_discount_rules/action.tpl" action=$action first=$smarty.foreach.actions.iteration}
					{/foreach}
				</div>
				<div class="col-lg-offset-3 col-lg-7" id="action_condition_new_action_loader"><div class="spinner"></div></div>
				<div class="col-lg-2" id="action_condition_new_action">
					<a onclick="javascript:addActionProduct(this);" class="btn btn-default pull-right">
						<i class="icon-plus-sign"></i> {l s='Add new action' mod='quantitydiscountpro'}
					</a>
				</div>
			</div>
		</div>
	{/if}
</div>

<script type="text/javascript">
	var chooseAnotherAction = "{l s='Please choose first which products have to be bought (Buy X)' mod='quantitydiscountpro'}";
    var discountAlreadyDefined = "{l s='You can\'t define more than one discount for this action' mod='quantitydiscountpro'}";
    var chooseActionBeforeAdding = "{l s='You have to choose the action before adding a new one' mod='quantitydiscountpro'}";
</script>
