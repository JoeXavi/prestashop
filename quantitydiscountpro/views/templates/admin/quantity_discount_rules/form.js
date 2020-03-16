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
*  @copyright 2016 idnovate.com
*  @license   See above
*/

/********** CONDITIONS **********/
function addConditionGroup()
{
	condition_group_id = 1;
	condition_counters_length = condition_counters.length;
	condition_counters[condition_counters_length] = 1;

	$.get(
		'ajax-tab.php', {
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newConditionGroup: 1,
			condition_id: (!isNaN(condition_counters[condition_group_id]) ? condition_counters[condition_group_id] : '1'),
			condition_group_id: (condition_counters_length ? condition_counters_length : '1'),
		},
		function(content) {
			if (content != "") {
				$('#conditions').append(content);
			}
		}
	);
}

function addCondition(element)
{
	condition_group_id = $(element).parent().parent().find('input[name^="condition_group"]').val();
	if (isNaN(condition_group_id)) {
		condition_group_id = 1;
		condition_counters[condition_group_id] = 1;
	} else {
		if (isNaN(condition_counters[condition_group_id])) {
			condition_counters[condition_group_id] = 1;
		} else {
			condition_counters[condition_group_id] += 1;
		}
	}

	$.get(
		'ajax-tab.php',	{
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newCondition:1,
			condition_id: (!isNaN(condition_counters[condition_group_id]) ? condition_counters[condition_group_id] : '1'),
			condition_group_id:condition_group_id,
		},
		function(content) {
			if (content != "") {
				$(element).parent().parent().find('.conditions_container').append(content);
			}
		}
	);
}

function removeConditionGroup(element) {
	condition_group_id = $(element).parent().parent().remove();
}

function removeCondition(id) {
	$('#condition_'+id+'_container').remove();
}

function toggleQuantityDiscountFilter(id) {
	if ($(id).is("input")) {
		if ($(id).prop('checked'))
			$('#' + $(id).attr('id') + '_div').show(400);
		else
			$('#' + $(id).attr('id') + '_div').hide(200);
	} else {
		if ($("input[name='"+$(id).attr('id')+"']:checked").val() == 1)
			$('#' + $(id).attr('id') + '_div').show(400);
		else
			$('#' + $(id).attr('id') + '_div').hide(200);
	}
}

function removeQuantityDiscountOption(item) {
	var id_selected = $(item).attr('id').replace('remove', '2');
	var id_unselected = $(item).attr('id').replace('remove', '1');
	$('#' + id_selected + ' option:selected').remove().appendTo('#' + id_unselected);
}

function addQuantityDiscountOption(item) {
	var id_selected = $(item).attr('id').replace('add', '2');
	var id_unselected = $(item).attr('id').replace('add', '1');
	$('#' + id_unselected + ' option:selected').remove().appendTo('#' + id_selected);
}

function toggleFilters() {
	$('[name^=condition_filter_by_]:checked').each(function() {
		if ($(this).val() == 0) {
			$(this).parents().eq(4).next().hide();
		} else {
			$(this).parents().eq(4).next().show();

		}
	});
}

/********** ACTIONS **********/
function addAction(element) {
	action_counter += 1;

	$.get(
		'ajax-tab.php',	{
			controller:'AdminQuantityDiscountRules',
			token:currentToken,
			newAction:1,
			action_id:action_counter,
		},
		function(content) {
			if (content != "") {
				$(element).parent().parent().find('.actions_container').append(content);
			}
		}
	);
}

function removeAction(id) {
	$('#action_'+id+'_container').remove();
}

function hideAllConditions(element) {
	$("#"+element.attr('id')+" div[class^='condition_type_options_']").hide();
}

function hideAllActions(element) {
	$("#"+element.attr('id')+" div[class*='action_apply_discount_to_']").hide();
}

function toggleConditions(element) {
	var id_value = element.attr('name');
	var pattern = /\[(.*?)\]/g;
	var id = [];
	var match;
	while ((match = pattern.exec(id_value)) != null) {
		id.push(match[1]);
	}

	var value = element.val();

	$("#condition_"+id[0]+"_"+id[1]+"_container [class*='condition_type_options_']").hide();
	$("#condition_"+id[0]+"_"+id[1]+"_container .condition_type_options_" + value).show();
	$("#condition_"+id[0]+"_"+id[1]+"_container [class*='condition_type_options_hide_']").show();
	$("#condition_"+id[0]+"_"+id[1]+"_container .condition_type_options_hide_" + value).hide();
}

function toggleActions(element) {
	showHideActionOptions();
}

function toggleActionsSelectors(element) {
	showHideActionOptions();
}

toggleQuantityDiscountFilter($('#product_restriction'));
var conditions = new Array('country', 'carrier', 'group', 'cart_rule', 'shop', 'product', 'category', 'attribute', 'manufacturer', 'supplier', 'zone', 'order_state');

// Main form submit
$('#quantity_discount_rule_form').submit(function() {
	if ($('#customerFilter').val() == '') {
		$('#condition_id_customer').val('0');
	}

	for (i in conditions) {
		$('[id^=' + conditions[i] + '_select_2]').each(function() {
			$(this).find('option').each(function() {
				$(this).prop('selected', true);
			});

			if ($(this).val()) {
				$('[id*="_' + $(this).attr('id') + '_json"]').val(JSON.stringify($(this).val()));
			}

			$(this).remove();
		});
	}

	$('.product_rule_toselect option').each(function() {
		$(this).prop('selected', true);
	});
});

function displayQuantityDiscountTab(tab)
{
	$('.quantity_discount_rule_tab').hide();
	$('.tab-row.active').removeClass('active');
	$('#quantity_discount_rule_' + tab).show();
	$('#quantity_discount_rule_link_' + tab).parent().addClass('active');
	$('#currentFormTab').val(tab);

	if (tab == 'actions') {
		showHideActionOptions();
	}
}

$('.quantity_discount_rule_tab').hide();
$('.tab-row.active').removeClass('active');
$('#quantity_discount_rule_' + currentFormTab).show();
$('#quantity_discount_rule_link_' + currentFormTab).parent().addClass('active');
showHideConditionOptions();
showHideActionOptions();

function showHideConditionOptions() {
	$("input[name^='condition_group_products_by']:checked").each(function() {
		value = $(this).val();
		var id_value = $(this).attr('id').substring('condition_group_products_by_'.length);
		var id = id_value.split('_');
		if (value == 'category' || $("select[name^='condition_id_type["+id[1]+"]["+id[2]+"]").val() == 24) {
			$('#consider_categories_as_one_'+id[1]+'_'+id[2]).show();
		} else {
			$('#consider_categories_as_one_'+id[1]+'_'+id[2]).hide();
		}
	})
}

function showHideActionOptions()
{
	value = $("select[name^='action_id_type']").val();

	$('select[name^="condition_id_type"]').each(function() {
		if ($(this).val() > 0) {
			$('#no-condition-selected').hide();
			$('#condition-selected').show();
		} else {
			$('#no-condition-selected').show();
			$('#condition-selected').hide();
		}

		/** Display/hide fields depending on condition */
		$("div[id^='action_condition_']").hide();

		if ($(this).val() == 16) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();

			if (value > 0) {
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_limit']").show();
				$("[id='action_condition_special']").show();
			}

			if (value == 2) {
				/** Amount */
				$("select[name^='action_id_type']").children("option:eq(2)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_repeat']").show();

			} else if (value == 3) {
				/** Percentage */
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_regular_price']").show();
			} else if (value == 4) {
				$("select[name^='action_id_type']").children("option:eq(4)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 17) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();
			$("select[name^='action_id_type']").children("option[value=4]").show();

			if (value > 0) {
				$("[id='action_condition_maximum_amount']").show();
			}

			//$("[id='action_condition_sort']").show();
			//$("[id='action_condition_limit']").show();

			if (value == 2) {
				$("select[name^='action_id_type']").children("option:eq(2)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_special']").show();
			} else if (value == 3) {
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_regular_price']").show();
			} else if (value == 4) {
				$("select[name^='action_id_type']").children("option:eq(4)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 23) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();
			$("select[name^='action_id_type']").children("option[value=4]").show();

			if (value > 0) {
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_special']").show();
				$("[id='action_condition_sort']").show().find('*').show();
				$("[id='action_condition_limit']").show();
			}

			if (value == 2) {
				/** Amount */
				$("select[name^='action_id_type']").children("option:eq(2)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
			} else if (value == 3) {
				/** Percentage */
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_regular_price']").show();
			} else if (value == 4) {
				$("select[name^='action_id_type']").children("option:eq(4)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_repeat']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 24) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();
			$("select[name^='action_id_type']").children("option[value=2]").hide();
			$("select[name^='action_id_type']").children("option[value=4]").hide();

			/** if (value == 2) {
				// Amount
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_special']").show();
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_limit']").show();
			} else */ if (value == 3) {
				//Percentage
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_only_categories']").show();
				$("[id='action_condition_limit']").show();
				$("[id='action_condition_product_price']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_special']").show();
				$("[id='action_condition_regular_price']").show();
			/**
			} else if (value == 4) {
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_special']").show();
			*/
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 26) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();
			$("select[name^='action_id_type']").children("option[value=3]").hide();

			if (value > 0) {
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_special']").show();
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_limit']").show();
			}

			if (value == 2) {
				/** Amount */
			} else if (value == 4) {
				$("select[name^='action_id_type']").children("option:eq(4)").attr('selected', 'selected');
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_repeat']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 27) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();
			$("select[name^='action_id_type']").children("option[value=2]").hide();
			$("select[name^='action_id_type']").children("option[value=4]").hide();

			if (value == 3) {
				/** Percentage */
				$("[id='action_condition_value']").show();
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_only_products']").show();
				$("[id='action_condition_only_categories']").show();
				$("[id='action_condition_limit']").show();
				$("[id='action_condition_product_price']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_special']").show();
				$("[id='action_condition_regular_price']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 28) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();
			$("select[name^='action_id_type']").children("option[value=4]").hide();

			if (value > 0) {
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_limit']").show();
			}

			if (value == 2) {
				/** Amount */
				$("select[name^='action_id_type']").children("option:eq(2)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
			} else if (value == 3) {
				/** Percentage */
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_regular_price']").show();
			} else if (value == 4) {
				$("select[name^='action_id_type']").children("option:eq(4)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else if ($(this).val() == 29) {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=1]").hide();

			if (value > 0) {
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_special']").show();
			}

			if (value == 2) {
				/** Amount */
				$("select[name^='action_id_type']").children("option:eq(2)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_repeat']").show();
			} else if (value == 3) {
				/** Percentage */
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_repeat']").show();
				$("[id='action_condition_regular_price']").show();
			} else if (value == 4) {
				$("select[name^='action_id_type']").children("option:eq(4)").attr('selected', 'selected');
				$("[id='action_condition_sort']").show();
				$("[id='action_condition_amount']").show();
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}

			return false;
		} else {
			$("select[name^='action_id_type']").children("option").show();
			$("select[name^='action_id_type']").children("option[value=4]").hide();

			if (value == 1) {
				//Free shipping
				$("[id='action_condition_maximum_amount']").show();
			} else if (value == 2) {
				//Amount
				$("select[name^='action_id_type']").children("option:eq(2)").attr('selected', 'selected');
				$("[id='action_condition_amount']").show();
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_apply']").show();
				$("[id='action_condition_apply_order']").hide();
				if ($("[id='action_condition_apply'] input[type='radio']:checked").val() == 'order') {
					$("[id='action_condition_sort']").hide();
					$("[id='action_condition_only_categories']").hide();
					$("[id='action_condition_only_products']").hide();
					$("[id='action_condition_limit']").hide();
					$("[id='action_condition_all']").hide();
					$("[id='action_condition_special']").hide();
					$("[id='action_condition_apply_order']").show();
				} else if ($("[id='action_condition_apply'] input[type='radio']:checked").val() == 'product') {
					$("[id='action_condition_sort']").show();
					$("[id='action_condition_only_categories']").show();
					$("[id='action_condition_only_products']").show();
					$("[id='action_condition_limit']").show();
					$("[id='action_condition_all']").show();
					$("[id='action_condition_special']").show();
					$("[id='action_condition_apply_order']").hide();
				}
			} else if (value == 3) {
				//Percentage
				$("select[name^='action_id_type']").children("option:eq(3)").attr('selected', 'selected');
				$("[id='action_condition_value']").show();
				$("[id='action_condition_maximum_amount']").show();
				$("[id='action_condition_apply']").show();
				if ($("[id='action_condition_apply'] input[type='radio']:checked").val() == 'order') {
					$("[id='action_condition_apply_order']").show();
					$("[id='action_condition_sort']").hide();
					$("[id='action_condition_only_categories']").hide();
					$("[id='action_condition_only_products']").hide();
					$("[id='action_condition_limit']").hide();
					$("[id='action_condition_all']").hide();
					$("[id='action_condition_special']").hide();
				} else if ($("[id='action_condition_apply'] input[type='radio']:checked").val() == 'product') {
					$("[id='action_condition_apply_order']").hide();
					$("[id='action_condition_sort']").show();
					$("[id='action_condition_only_categories']").show();
					$("[id='action_condition_only_products']").show();
					$("[id='action_condition_limit']").show();
					$("[id='action_condition_product_price']").show();
					$("[id='action_condition_all']").show();
					$("[id='action_condition_special']").show();
					$("[id='action_condition_regular_price']").show();
				}
			} else {
				$("select[name^='action_id_type']").children("option:eq(0)").attr('selected', 'selected').triggerHandler('change');
			}
		}
	});
}

var date = new Date();
var hours = date.getHours();
if (hours < 10) {
	hours = "0" + hours;
}
var mins = date.getMinutes();
if (mins < 10) {
	mins = "0" + mins;
}
var secs = date.getSeconds();
if (secs < 10) {
	secs = "0" + secs;
}

/* Hide/Display code field */
hideDisplayCodeField();
$('#code').keyup(function() {
	hideDisplayCodeField();
});

function hideDisplayCodeField() {
	if (!$('#code').val()) {
		$('#code_prefix').parents().eq(3).show();
	} else {
		$('#code_prefix').parents().eq(3).hide();
		$('#code_prefix').val('');
	}
}