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

/********** CONDITIONS **********/
var condition_selectors = new Array('country', 'carrier', 'group', 'cart_rule', 'shop', 'product', 'category', 'attribute', 'feature', 'zone', 'state', 'manufacturer', 'supplier', 'order_state', 'gender', 'currency');

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
    condition_group_id = $(element).parent().parent().find('input[id^="condition_group"]').val();
    $(element).parent().parent().find("#condition_new_condition_loader .spinner").show();

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
        'ajax-tab.php', {
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
            $(element).parent().parent().find("#condition_new_condition_loader .spinner").hide();
        }
    )
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
    var to = $('#'+$(item).attr('id').replace('remove', '1'));
    var from = $('#'+$(item).attr('id').replace('remove', '2'));

    var selected = from.find('option:selected');
    var selectedVal = [];
    selected.each(function(){
        selectedVal.push($(this).val());
    });

    var options = from.data('options');
    var tempOption = [];

    var targetOptions = to.data('options');

    $.each(options, function(i) {
        var option = options[i];
        if($.inArray(option.value, selectedVal) === -1) {
            tempOption.push(option);
        } else {
            targetOptions.push(option);
        }

    });

    from.find('option:selected').remove().appendTo(to);

    to.data('options', targetOptions);
    from.data('options', tempOption);
}

function addQuantityDiscountOption(item) {
    var to = $('#'+$(item).attr('id').replace('add', '2'));
    var from = $('#'+$(item).attr('id').replace('add', '1'));

    var selected = from.find('option:selected');
    var selectedVal = [];
    selected.each(function(){
        selectedVal.push($(this).val());
    });

    var options = from.data('options');
    var tempOption = [];

    var targetOptions = to.data('options');

    $.each(options, function(i) {
        var option = options[i];
        if($.inArray(option.value, selectedVal) === -1) {
            tempOption.push(option);
        } else {
            targetOptions.push(option);
        }

    });

    from.find('option:selected').remove().appendTo(to);

    to.data('options', targetOptions);
    from.data('options', tempOption);
}

function toggleActionFilters() {
    $('[name^=action_filter_by_]:checked').each(function() {
        if ($(this).val() == 0) {
            $(this).parents().eq(4).next().hide();
        } else {
            $(this).parents().eq(4).next().show();

        }
    });
}

function hideAllConditions(element) {
    $("#"+element.attr('id')+" div[class^='condition_type_options_']").hide();
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

// Main form submit
$('#quantity_discount_rule_form').submit(function() {
    if ($('#customerFilter').val() == '') {
        $('#condition_id_customer').val('0');
    }

    for (i in condition_selectors) {
        $('[id^=' + condition_selectors[i] + '_select_2]').each(function() {
            $(this).find('option').each(function() {
                $(this).prop('selected', true);
            });

            if ($(this).val()) {
                $('[id*="_' + $(this).attr('id') + '_json"]').val(JSON.stringify($(this).val()));
            }

            $(this).remove();
        });
    }
});

/********** ACTIONS **********/
var action_selectors = new Array('product', 'category', 'attribute', 'feature', 'manufacturer', 'supplier', 'carrier');

function addActionProduct(element)
{
    $("select[name^='action_id_type']").each(function() {
        if ($(this).val() == 0) {
            alert(chooseActionBeforeAdding);

            return false;
        }
    });

    $("#action_condition_new_action_loader .spinner").show();
    action_counter += 1;

    $.get(
        'ajax-tab.php', {
            controller:'AdminQuantityDiscountRules',
            token:currentToken,
            newAction: 1,
            action_id: action_counter,
            type: $("select[name^='action_id_type']").first().val()
        },
        function(content) {
            if (content != "") {
                $(element).parent().parent().find('.actions_container').append(content);
            }
            $("#action_condition_new_action_loader .spinner").hide();
        }
    );
}

function removeActionProduct(id) {
    $('#action_'+id+'_container').remove();
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
    $("#condition_"+id[0]+"_"+id[1]+"_container div[class*='condition_type_options_hide_']").show();
    $("#condition_"+id[0]+"_"+id[1]+"_container div.condition_type_options_hide_" + value).hide();
}

toggleQuantityDiscountFilter($('#product_restriction'));

function displayQuantityDiscountTab(tab)
{
    $('.quantity_discount_rule_tab').hide();
    $('.tab-row.active').removeClass('active');
    $('#quantity_discount_rule_' + tab).show();
    $('#quantity_discount_rule_link_' + tab).parent().addClass('active');
    $('#currentFormTab').val(tab);
}

$('.quantity_discount_rule_tab').hide();
$('.tab-row.active').removeClass('active');
$('#quantity_discount_rule_' + currentFormTab).show();
$('#quantity_discount_rule_link_' + currentFormTab).parent().addClass('active');

//Used to prevent a user from selecting Buy X from one rule and change the Buy X from another rule.
//Then the other actions are removed
var buyXAlreadySelected = 0;
function showHideActionOptions()
{
    var removeOtherActions = true;

    //Used for "Product pack" rules. You can only define one discount
    var discountDefined = false;

    $("#action_condition_new_action_loader .spinner").hide();
    $("[id='action_condition_new_action']").hide();

    $("select[name^='action_id_type']").each(function(index) {
        id = $(this).attr('name').replace( /(^.*\[|\].*$)/g, '' );

        if (index && removeOtherActions) {
            $('#action_'+id+'_container').remove();
        }

        $("[id ^='action_condition_'][id $='["+id+"]']").hide();
        value = $("select[name='action_id_type["+id+"]']").val();

        if (value == 1) {
            // Shipping cost - Fixed discount
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_1["+id+"]']").show();
            $("[id='action_condition_filter_by_carrier["+id+"]']").show();
        } else if (value == 5) {
            // Shipping cost - Percentage discount
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_1["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_filter_by_carrier["+id+"]']").show();
        } else if (value == 2) {
            // Order amount - Fixed discount
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_1["+id+"]']").show();
            $("[id='action_condition_amount_shipping["+id+"]']").show();
        } else if (value == 3) {
            // Order amount - Percentage discount
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_1["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_percent_shipping["+id+"]']").show();
            $("[id='action_condition_percent_discount["+id+"]']").show();
        } else if (value == 27) {
            // Product discount - Fixed discount
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_2["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 0) {
                $("[id='action_condition_default_category["+id+"]']").hide();
            } else {
                $("[id='action_condition_default_category["+id+"]']").show();
            }
            $("[id='action_condition_sort["+id+"]']").show();
            $("[id='action_group_products_by_all["+id+"]']").prop('checked', true);
        } else if (value == 28) {
            // Product discount - Percentage discount
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_1["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_2["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 0) {
                $("[id='action_condition_default_category["+id+"]']").hide();
            } else {
                $("[id='action_condition_default_category["+id+"]']").show();
            }
            $("[id='action_condition_sort["+id+"]']").show();

            $("[id='action_group_products_by_all["+id+"]']").prop('checked', true);
        } else if (value == 29) {
            // Product discount - Fixed price
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_3["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 0) {
                $("[id='action_condition_default_category["+id+"]']").hide();
            } else {
                $("[id='action_condition_default_category["+id+"]']").show();
            }
            $("[id='action_condition_sort["+id+"]']").show();

            $("[id='action_group_products_by_all["+id+"]']").prop('checked', true);
        } else if (value == 6) {
            // Buy X Get Y - Fixed discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_2["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 7) {
            // Buy X Get Y - Percentage discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_1["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_1["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_2["+id+"]']").show();
            $("[id='action_condition_attributes["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 8) {
            // Buy X Get Y - Fixed price
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_3["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 31) {
            // Buy X Get Y - Gift product
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_4["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }
        } else if (value == 12) {
            // All products after X - Fixed discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_2["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_1["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_repeat_label_2["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 13) {
            // All products after X - Percentage discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_2["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_1["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_3["+id+"]']").show();
            $("[id='action_condition_attributes["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_repeat_label_2["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 14) {
            // All products after X - Fixed price
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_2["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_4["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_repeat_label_2["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 15) {
            // Each group of X - Fixed discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_3["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_1["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 16) {
            // Each group of X - Percentage discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_3["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_3["+id+"]']").show();
            $("[id='action_condition_attributes["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 17) {
            // Each group of X - Fixed price
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_3["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_4["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 18) {
            // Each X-th after Y - Fixed discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_4["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_2["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_1["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 19) {
            // Each X-th after Y - Percentage discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_4["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_2["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_1["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_3["+id+"]']").show();
            $("[id='action_condition_attributes["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 20) {
            // Each X-th after Y - Fixed price
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_4["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_2["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_4["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 21) {
            // Each X spent (over Z) Get Y - Fixed discount
            $("[id='action_condition_buy_amount["+id+"]']").show();
            $("[id='action_condition_amount_label_5["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_shipping["+id+"]']").show();
            $("[id='action_condition_buy_over["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
        } else if (value == 35) {
            // Each X spent (over Z) Get Y - Fixed discount
            $("[id='action_condition_amount_label_5["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_shipping["+id+"]']").show();
            $("[id='action_condition_buy_over["+id+"]']").show();
            $("[id='action_condition_free_gift["+id+"]']").show();
            $("[id='action_condition_free_gift_label_1["+id+"]']").show();
        } else if (value == 26) {
            // X spent (over Z) Get Y - Percentage discount
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_3["+id+"]']").show();
            $("[id='action_condition_buy_over["+id+"]']").show();
            $("[id='action_condition_amount_shipping["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
        } else if (value == 22) {
            // Buy X
            if (buyXAlreadySelected == 0 || buyXAlreadySelected == 22) {
                removeOtherActions = false;
            } else {
                removeOtherActions = true;
            }

            buyXAlreadySelected = 22;

            $("[id='action_condition_new_action']").show();
            if (index) {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            } else {
                if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                    $("[id='action_condition_attributes["+id+"]']").show();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                } else if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'category') {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").show();
                } else {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                }
            }
        } else if (value == 23) {
            // Buy X
            if (buyXAlreadySelected == 0 || buyXAlreadySelected == 23) {
                removeOtherActions = false;
            } else {
                removeOtherActions = true;
            }

            buyXAlreadySelected = 23;

            if (index) {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_1["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            } else {
                if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                    $("[id='action_condition_attributes["+id+"]']").show();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                } else if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'category') {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").show();
                } else {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                }
            }
        } else if (value == 100) {
            // Get a discount on A - Fixed discount
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_2["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 101) {
            // Get a discount on A - Percentage discount
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_1["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_2["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_1["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 102) {
            // Get a discount on A - Fixed price
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_3["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            }

            if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                $("[id='action_condition_attributes["+id+"]']").show();
            } else {
                $("[id='action_condition_attributes["+id+"]']").hide();
            }
        } else if (value == 103) {
            // Free gift
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_3["+id+"]']").show();
            $("[id='action_condition_free_gift["+id+"]']").show();
            $("[id='action_condition_free_gift_label_1["+id+"]']").show();
        } else if (value == 30) {
            // Free gift
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_3["+id+"]']").show();
            $("[id='action_condition_free_gift["+id+"]']").show();
            $("[id='action_condition_free_gift_label_1["+id+"]']").show();
        } else if (value == 32) {
            // Buy more than X and get discount in all units - Fixed discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_5["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_6["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            } else {
                if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                    $("[id='action_condition_attributes["+id+"]']").show();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                } else if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'category') {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").show();
                } else {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                }
            }
        } else if (value == 33) {
            // Buy more than X and get discount in all units - Percentage discount
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_5["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_1["+id+"]']").show();
            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_4["+id+"]']").show();
            $("[id='action_condition_attributes["+id+"]']").show();
            $("[id='action_condition_regular_price["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            } else {
                if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                    $("[id='action_condition_attributes["+id+"]']").show();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                } else if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'category') {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").show();
                } else {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                }
            }
        } else if (value == 34) {
            // Buy more than X and get discount in all units - Fixed price
            $("[id='action_condition_group_by["+id+"]']").show();
            $("[id='action_condition_buy["+id+"]']").show();
            $("[id='action_condition_buy_label_5["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_7["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_repeat_label_2["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();
            $("[id='action_condition_filters["+id+"]']").show();

            if ($("input[name='action_filter_by_category["+id+"]']:checked").val() == 1) {
                $("[id='action_condition_default_category["+id+"]']").show();
            } else {
                if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'product') {
                    $("[id='action_condition_attributes["+id+"]']").show();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                } else if ($("input[name='action_group_products_by["+id+"]']:checked").val() == 'category') {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").show();
                } else {
                    $("[id='action_condition_attributes["+id+"]']").hide();
                    $("[id='action_condition_default_category["+id+"]']").hide();
                }
            }
        } else if (value == 36) {
            // Free gift
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_3["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_3["+id+"]']").show();
            $("[id='action_condition_free_gift["+id+"]']").show();
            $("[id='action_condition_free_gift_label_2["+id+"]']").show();
        } else if (value == 104) {
            // Get a discount on A - Fixed discount
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            if (discountDefined) {
                alert(discountAlreadyDefined);
                $('#action_'+id+'_container').remove();
            }

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_amount_label_1["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();
            $("[id='action_condition_repeat_label_1["+id+"]']").show();
            $("[id='action_condition_repeat["+id+"]']").show();

            discountDefined = true;
        } else if (value == 105) {
            // Get a discount on A - Percentage discount
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            if (discountDefined) {
                alert(discountAlreadyDefined);
                $('#action_'+id+'_container').remove();
            }

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_value["+id+"]']").show();
            $("[id='action_condition_value_label_3["+id+"]']").show();
            $("[id='action_condition_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount["+id+"]']").show();
            $("[id='action_condition_product_maximum_amount_label_2["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            discountDefined = true;
        } else if (value == 106) {
            // Get a discount on A - Fixed price
            if (!index) {
                alert(chooseAnotherAction);
                $(this).val(0);
                return false;
            } else {
                $("[id='action_condition_remove_button["+id+"]']").show();
            }

            if (discountDefined) {
                alert(discountAlreadyDefined);
                $('#action_'+id+'_container').remove();
        	}

            $("[id='action_condition_new_action']").show();

            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_3["+id+"]']").show();
            $("[id='action_condition_sort["+id+"]']").show();

            discountDefined = true;
        } else if (value == 107) {
            $("[id='action_condition_get["+id+"]']").show();
            $("[id='action_condition_get_label_3["+id+"]']").show();
            $("[id='action_condition_amount["+id+"]']").show();
            $("[id='action_condition_reduction_currency["+id+"]']").show();
            $("[id='action_condition_amount_label_3["+id+"]']").show();
            $("[id='action_condition_free_gift["+id+"]']").show();
            $("[id='action_condition_free_gift_label_2["+id+"]']").show();
        }

        $("select[data-group='currency["+id+"]']").on('change', function () {
            $("select[data-group='currency["+id+"]']").val(this.value);
        });
        $("select[data-group='currency2["+id+"]']").on('change', function () {
            $("select[data-group='currency2["+id+"]']").val(this.value);
        });
        $("select[data-group='tax["+id+"]']").on('change', function () {
            $("select[data-group='tax["+id+"]']").val(this.value);
        });
        $("select[data-group='tax2["+id+"]']").on('change', function () {
            $("select[data-group='tax2["+id+"]']").val(this.value);
        });
        $("select[data-group='shipping["+id+"]']").on('change', function () {
            $("select[data-group='shipping["+id+"]']").val(this.value);
        });
    });

    return;
}

gift_product_search= '';
function searchProducts(element)
{
    var matches = element.attr('name').match(/\[(.*?)\]/);
    var id = matches[1];

    if ($('[name="giftProductFilter\['+id+'\]"]').val() == gift_product_search) {
        return;
    }

    gift_product_search = $('#giftProductFilter').val();

    $.ajax({
        type: 'POST',
        headers: { "cache-control": "no-cache" },
        url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
        async: true,
        dataType: 'json',
        data: {
            controller: 'AdminQuantityDiscountRules',
            token: currentToken,
            action: 'searchProducts',
            product_search: $('[name="giftProductFilter\['+id+'\]"]').val()
        },
        success : function(res)
        {
            var products_found = '';
            var attributes_html = '';

            if (res.found) {
                $('[name="gift_products_err\['+id+'\]"]').hide();
                $('[name="gift_products_found\['+id+'\]"]').show();
                $.each(res.products, function() {
                    products_found += '<option value="' + this.id_product + '">' + this.name + (this.combinations.length == 0 ? ' - ' + this.formatted_price : '') + '</option>';

                    attributes_html += '<select class="control-form" id="ipa_' + this.id_product + '['+id+']" style="display:none">';
                    $.each(this.combinations, function() {
                        attributes_html += '<option ' + (this.default_on == 1 ? 'selected="selected"' : '') + ' value="' + this.id_product_attribute + '">' + this.attributes + ' - ' + this.formatted_price + '</option>';
                    });
                    attributes_html += '</select>';
                });

                $('[name="action_gift_product\['+id+'\]"]').html(products_found);
                $('#gift_product_attributes_list_select\\['+id+'\\]').html(attributes_html);

                displayProductAttributes(id);
            } else {
                $('[name="gift_products_found\['+id+'\]"]').hide();
                $('[name="gift_products_err\['+id+'\]"]').html(res.notfound).show();
            }
        }
    });
}

function displayProductAttributes(id) {
    if ($("[id='ipa_" + $("[name='action_gift_product\["+id+"\]'] option:selected").val() + "["+id+"]'] option").length === 0) {
        $('[name="gift_product_attributes_list\['+id+'\]"]').hide();
    } else {
        $('[name="gift_product_attributes_list\['+id+'\]"] select').hide();
        $('[name="gift_product_attributes_list\['+id+'\]"]').show();
        $("[id='ipa_" + $("[name='action_gift_product\["+id+"\]'] option:selected").val() + "["+id+"]']").show();
    }
}

/********** MESSAGE **********/
function addMessage(element) {
    message_counter += 1;

    $.get(
        'ajax-tab.php', {
            controller:'AdminQuantityDiscountRules',
            token:currentToken,
            newMessage: 1,
            lang: id_language,
            message_id: message_counter,
        },
        function(content) {
            if (content != "") {
                $(element).parent().parent().find('.messages_container').append(content);
            }
        }
    );
}

function removeMessage(id) {
    $('#message_'+id+'_container').remove();
}

/* http://www.lessanvaezi.com/filter-select-list-options/ */
jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
    return this.each(function() {
        var select = $(this);
        var options = [];
        select.find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
        select.data('options', options);
        $(textbox).bind('change keyup', function() {
            var options = select.empty().scrollTop(0).data('options');
            var search = $.trim($(this).val());
            var regex = new RegExp(search,'gi');

            var new_options_html = '';
            $.each(options, function(i, option) {
                if(option.text.match(regex) !== null) {
                    new_options_html += '<option value="' + option.value + '">' + option.text + '</option>';
                }
            });

            select.append(new_options_html);

            if (selectSingleMatch === true &&
                select.children().length === 1) {
                select.children().get(0).selected = true;
            } else if (select.children().length > 0) {
                select.children().get(0).selected = false;
            }
        })
    })
};
