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

{if $show_toolbar}
<div class="panel-footer" id="toolbar-footer">
	{foreach from=$toolbar_btn item=btn key=k}
		{if $k != 'modules-list'}
			<a id="desc-{$table|escape:'html':'UTF-8'}-{if isset($btn.imgclass)}{$btn.imgclass|escape:'html':'UTF-8'}{else}{$k|escape:'html':'UTF-8'}{/if}" class="btn btn-default{if $k=='save' || $k=='save-and-stay'} pull-right{/if}{if isset($btn.target) && $btn.target} _blank{/if}" href="{if isset($btn.href)}{$btn.href|escape:'html':'UTF-8'}{else}#{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js|escape:'html':'UTF-8'}"{/if}>
				<i class="process-icon-{if isset($btn.imgclass)}{$btn.imgclass|escape:'html':'UTF-8'}{else}{$k|escape:'html':'UTF-8'}{/if}{if isset($btn.class)} {$btn.class|escape:'html':'UTF-8'}{/if}"></i> <span {if isset($btn.force_desc) && $btn.force_desc|escape:'html':'UTF-8' == true } class="locked" {/if}>{$btn.desc|escape:'html':'UTF-8'}</span>
			</a>
		{/if}
	{/foreach}

	<script type="text/javascript">
	//<![CDATA[
		var submited = false

		//get reference on save link
		btn_save = $('#desc-{$table|escape:'html':'UTF-8'}-save');

		//get reference on form submit button
		btn_submit = $('#{$table|escape:'html':'UTF-8'}_form_submit_btn');

		if (btn_save.length > 0 && btn_submit.length > 0)
		{
			//get reference on save and stay link
			btn_save_and_stay = $('#desc-{$table|escape:'html':'UTF-8'}-save-and-stay');

			//get reference on current save link label
			lbl_save = $('#desc-{$table|escape:'html':'UTF-8'}-save');

			//override save link label with submit button value
			if (btn_submit.html().length > 0)
				lbl_save.find('span').html(btn_submit.html());

			if (btn_save_and_stay.length > 0)
			{
				//get reference on current save link label
				lbl_save_and_stay = $('#desc-{$table|escape:'html':'UTF-8'}-save-and-stay');

				//override save and stay link label with submit button value
				if (btn_submit.html().length > 0 && lbl_save_and_stay && !lbl_save_and_stay.hasClass('locked'))
					lbl_save_and_stay.find('span').html(btn_submit.html() + " {l s='and stay' mod='quantitydiscountpro'} ");
			}

			//hide standard submit button
			btn_submit.hide();
			//bind enter key press to validate form
			$('#{$table|escape:'html':'UTF-8'}_form').find('input').keypress(function (e) {
				if (e.which == 13 && e.target.localName != 'textarea' && !$(e.target).parent().hasClass('tagify-container'))
					$('#desc-{$table|escape:'html':'UTF-8'}-save').click();
			});
			//submit the form
			{block name=formSubmit}
				btn_save.click(function() {
					// Avoid double click
					if (submited)
						return false;
					submited = true;

					if ($(this).attr('href').replace('#', '').replace(/\s/g, '') != '')
						return true;

					//add hidden input to emulate submit button click when posting the form -> field name posted
					btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'" value="1" />');

					$('#{$table|escape:'html':'UTF-8'}_form').submit();
					return false;
				});

				if (btn_save_and_stay)
				{
					btn_save_and_stay.click(function() {
						if ($(this).attr('href').replace('#', '').replace(/\s/g, '') != '')
							return true;

						//add hidden input to emulate submit button click when posting the form -> field name posted
						btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndStay" value="1" />');

						$('#{$table|escape:'html':'UTF-8'}_form').submit();
						return false;
					});
				}
			{/block}
		}
	//]]>
	</script>
</div>
{/if}
