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


<script type="text/javascript">
    var selectAll = "{l s='Select all' mod='quantitydiscountpro' js=1}";
    var deselectAll = "{l s='Deselect all' mod='quantitydiscountpro' js=1}";
    var selected = "{l s='selected' mod='quantitydiscountpro' js=1}";
    var itemsSelected_nil = "{l s='No options selected (rule applied to all values)' mod='quantitydiscountpro' js=1}";
    var itemsSelected = "{l s='selected option (rule applied only to this value)' mod='quantitydiscountpro' js=1}";
    var itemsSelected_plural = "{l s='options selected (rule applied only to these values)' mod='quantitydiscountpro' js=1}";

    var itemsAvailable_nil = "{l s='No items available' mod='quantitydiscountpro' js=1}";
    var itemsAvailable = "{l s='options available' mod='quantitydiscountpro' js=1}";
    var itemsAvailable_plural = "{l s='options available' mod='quantitydiscountpro' js=1}";

    var itemsFiltered_nil = "{l s='No options found' mod='quantitydiscountpro' js=1}";
    var itemsFiltered = "{l s='option found' mod='quantitydiscountpro' js=1}";
    var itemsFiltered_plural = "{l s='options found' mod='quantitydiscountpro' js=1}";

    var searchOptions = "{l s='Search Options' mod='quantitydiscountpro' js=1}";
    var collapseGroup = "{l s='Collapse Group' mod='quantitydiscountpro' js=1}";
    var expandGroup = "{l s='Expand Group' mod='quantitydiscountpro' js=1}";
    var searchAllGroup = "{l s='Select All Group' mod='quantitydiscountpro' js=1}";
    var deselectAllGroup = "{l s='Deselect All Group' mod='quantitydiscountpro' js=1}";
</script>

<div class="form-group">
    <label class="control-label col-lg-3">{l s='Enabled' mod='quantitydiscountpro'}</label>
    <div class="col-lg-9">
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="active" id="active_on" value="1" {if $currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
            <label class="t" for="active_on">{l s='Yes' mod='quantitydiscountpro'}</label>
            <input type="radio" name="active" id="active_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'active')|intval}checked="checked"{/if} />
            <label class="t" for="active_off">{l s='No' mod='quantitydiscountpro'}</label>
            <a class="slide-button btn"></a>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Name' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-8">
                {foreach from=$languages item=language}
                    {if $languages|count > 1}
                    <div class="row">
                        <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $display_language}style="display:none"{/if}>
                            <div class="col-lg-9">
                                {/if}
                                <input id="name_{$language.id_lang|intval}" type="text"  name="name_{$language.id_lang|intval}" value="{$currentTab->getFieldValue($currentObject, 'name', $language.id_lang|intval)|escape:'html':'UTF-8'}">
                                {if $languages|count > 1}
                            </div>
                            <div class="col-lg-2">
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
        <div class="row">
            <div class="col-lg-12 help-block">{l s='This name will be displayed in the cart summary, as well as on the invoice' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Description' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-8">
                <textarea name="description" rows="2" class="textarea-autosize">{$currentTab->getFieldValue($currentObject, 'description')|escape:'html':'UTF-8'}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='This information is private and will never be displayed to the customer' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Family' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-8">
                <select name="id_family">
                    {if $currentTab->families_array|count > 1}
                    <option value="">{l s='-- Choose --' mod='quantitydiscountpro'}</option>
                    {/if}
                    {foreach from=$currentTab->families_array key=id_family item=family}
                        <option value="{$id_family|escape:'quotes':'UTF-8'}" {if {$currentTab->getFieldValue($currentObject, 'id_family')|escape:'html':'UTF-8'} == $id_family|escape:'html':'UTF-8'}selected{/if}>{$family|escape:'html':'UTF-8'}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='Group this rule in this family' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        <span class="label-tooltip" data-toggle="tooltip"
        title="{l s='This is the code users should enter to apply the voucher to a cart. Either create your own code or generate one by clicking on "Generate".' mod='quantitydiscountpro'}">
            {l s='Code' mod='quantitydiscountpro'}
        </span>
    </label>
    <div class="col-lg-9">
        <div class="input-group col-lg-4">
            <input type="text" id="code" name="code" value="{$currentTab->getFieldValue($currentObject, 'code')|escape:'html':'UTF-8'}" />
            <span class="input-group-btn">
                <a href="javascript:gencode(8);" class="btn btn-default"><i class="icon-random"></i> {l s='Generate' mod='quantitydiscountpro'}</a>
            </span>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">
                {l s='Caution! If you leave this field blank, the rule will automatically be applied if the conditions are matched.' mod='quantitydiscountpro'}
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        <span class="label-tooltip" data-toggle="tooltip"
        title="{l s='If the voucher is not yet in the cart, it will be displayed in the cart summary.' mod='quantitydiscountpro'}">
            {l s='Highlight' mod='quantitydiscountpro'}
        </span>
    </label>
    <div class="col-lg-9">
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="highlight" id="highlight_on" value="1" {if $currentTab->getFieldValue($currentObject, 'highlight')|intval}checked="checked"{/if} />
            <label class="t" for="highlight_on">{l s='Yes' mod='quantitydiscountpro'}</label>
            <input type="radio" name="highlight" id="highlight_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'highlight')|intval}checked="checked"{/if} />
            <label class="t" for="highlight_off">{l s='No' mod='quantitydiscountpro'}</label>
            <a class="slide-button btn"></a>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Code prefix' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-1">
                <input type="text" class="input-mini" id="code_prefix" name="code_prefix" value="{$currentTab->getFieldValue($currentObject, 'code_prefix')|escape:'html':'UTF-8'}" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='If you don\'t define a code, cart rules will be created with this prefix.' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Valid' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon">{l s='From' mod='quantitydiscountpro'}</span>
                    <input type="text" class="datepicker input-medium" name="date_from"
                    value="{if $currentTab->getFieldValue($currentObject, 'date_from')}{$currentTab->getFieldValue($currentObject, 'date_from')|escape:'html':'UTF-8'}{else}{$defaultDateFrom|escape:'html':'UTF-8'}{/if}" />
                    <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon">{l s='To' mod='quantitydiscountpro'}</span>
                    <input type="text" class="datepicker input-medium" name="date_to"
                    value="{if $currentTab->getFieldValue($currentObject, 'date_to')}{$currentTab->getFieldValue($currentObject, 'date_to')|escape:'html':'UTF-8'}{else}{$defaultDateTo|escape:'html':'UTF-8'}{/if}" />
                    <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Priority' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-1">
                <input type="text" class="input-mini" name="priority" value="{$currentTab->getFieldValue($currentObject, 'priority')|intval}" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='Less is higher. Rules will be executed according its priority. A cart rule with a priority of "1" will be processed before than a cart rule with a priority of "2"' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Total available' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-1">
                <input type="text" class="input-mini" name="quantity" value="{$currentTab->getFieldValue($currentObject, 'quantity')|intval}" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='Times that this rule can be used by all the customers' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3 required">
        {l s='Total available for each customer' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-1">
                <input type="text" class="input-mini" name="quantity_per_user" value="{$currentTab->getFieldValue($currentObject, 'quantity_per_user')|intval}" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='Times that this can be applied to each customer at maximum' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        <span>
            {l s='Times used' mod='quantitydiscountpro'}
        </span>
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-1">
                <label class="control-label">{$times_used|intval}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='This rule has been used this number of times' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Execute other rules from the same family' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="execute_other_rules" id="execute_other_rules_on" value="1" {if $currentTab->getFieldValue($currentObject, 'execute_other_rules')|intval}checked="checked"{/if} />
                    <label class="t" for="execute_other_rules_on">{l s='Yes' mod='quantitydiscountpro'}</label>
                    <input type="radio" name="execute_other_rules" id="execute_other_rules_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'execute_other_rules')|intval}checked="checked"{/if} />
                    <label class="t" for="execute_other_rules_off">{l s='No' mod='quantitydiscountpro'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='Do you want to continue executing remaining rules from this family with less priority?' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Apply this rule if there is a regular cart rule in the cart or if customer adds a cart rule' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="compatible_cart_rules" id="compatible_cart_rules_on" value="1" {if $currentTab->getFieldValue($currentObject, 'compatible_cart_rules')|intval}checked="checked"{/if} />
                    <label class="t" for="compatible_cart_rules_on">{l s='Yes' mod='quantitydiscountpro'}</label>
                    <input type="radio" name="compatible_cart_rules" id="compatible_cart_rules_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'compatible_cart_rules')|intval}checked="checked"{/if} />
                    <label class="t" for="compatible_cart_rules_off">{l s='No' mod='quantitydiscountpro'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='If a regular PrestaShop cart rule is applied and this option disabled, this rule won\'t be applied or will be removed automatically from cart' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Apply this rule if there is a rule from this module already applied' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="compatible_qdp_rules" id="compatible_qdp_rules_on" value="1" {if $currentTab->getFieldValue($currentObject, 'compatible_qdp_rules')|intval}checked="checked"{/if} />
                    <label class="t" for="compatible_qdp_rules_on">{l s='Yes' mod='quantitydiscountpro'}</label>
                    <input type="radio" name="compatible_qdp_rules" id="compatible_qdp_rules_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'compatible_qdp_rules')|intval}checked="checked"{/if} />
                    <label class="t" for="compatible_qdp_rules_off">{l s='No' mod='quantitydiscountpro'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='If a module rule is applied and this option disabled, this rule won\'t be applied or will be removed automatically from cart' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Apply this rule to products already discounted by previous rules' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="apply_products_already_discounted" id="apply_products_already_discounted_on" value="1" {if $currentTab->getFieldValue($currentObject, 'apply_products_already_discounted')|intval}checked="checked"{/if} />
                    <label class="t" for="apply_products_already_discounted_on">{l s='Yes' mod='quantitydiscountpro'}</label>
                    <input type="radio" name="apply_products_already_discounted" id="apply_products_already_discounted_off" value="0"  {if !$currentTab->getFieldValue($currentObject, 'apply_products_already_discounted')|intval}checked="checked"{/if} />
                    <label class="t" for="apply_products_already_discounted_off">{l s='No' mod='quantitydiscountpro'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='If disabled, if a product has been previously discounted by another module rule won\'t be discounted again by this rule' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">
        {l s='Don\'t apply discount in these modules (separated by \';\')' mod='quantitydiscountpro'}
    </label>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-9">
                <input type="text" name="modules_exceptions" value="{$currentTab->getFieldValue($currentObject, 'modules_exceptions')|escape:'html':'UTF-8'}" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 help-block">{l s='This rule won\'t be applied when any controller from these modules call it' mod='quantitydiscountpro'}</div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        var dateFields = [
            '.datepicker[name="date_from"]',
            '.datepicker[name="date_to"]',
        ];

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
    });
</script>