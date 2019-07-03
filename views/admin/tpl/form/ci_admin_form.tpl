[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
		[{assign var="readonly" value="readonly disabled"}]
[{else}]
		[{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
	[{$oViewConf->getHiddenSid()}]
	<input type="hidden" name="oxid" value="[{ $oxid }]">
	<input type="hidden" name="cl" value="ciadminformmain">
</form>

<form class="formbuilder" name="myedit" enctype="multipart/form-data" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
	[{$oViewConf->getHiddenSid()}]
	<input type="hidden" name="cl" value="ciadminformmain">
	<input type="hidden" name="fnc" value="">
	<input type="hidden" name="oxid" value="[{ $oxid }]">
	<input type="hidden" name="editval[ci_form__oxid]" value="[{ $oxid }]">
	<input type="hidden" name="sorting" value="">
	<input type="hidden" name="stable" value="">
	<input type="hidden" name="starget" value="">

	<div class="formbuilder-inner w50">
		[{if $oxid != '-1'}]
		<div class="formbuilder-fieldset">
			<div class="formbuilder-element-raw">
				<h3>Formular in einem Template einbinden</h3>
				[{literal}][{ formbuilder oxid="[{/literal}][{$oxid}][{literal}]" }][{/literal}]
			</div>
		</div>
		[{/if}]
		[{$form}]
	</div>
	<div class="formbuilder-inner w50">
		<div class="formbuilder-fieldset">
			<div class="formbuilder-element-raw">
				<h3>[{oxmultilang ident="FORMBUILDER_FIELDCONFIG_HEADLINE"}]</h3>

				[{ if $removedFields|@count }]
				<div class="formbuilder-removed-elements">
					<div class="formbuilder-fieldconfig-element">
						<p class="warning">[{oxmultilang ident="FORMBUILDER_FIELDS_NOT_FOUND" args=$removedFields|@count}]</p>
					</div>
				</div>
				[{/if}]

				<div class="formbuilder-unapplied-elements[{if $unapplied|@count gt 0}] visible[{/if}]">
					<h4>[{oxmultilang ident="FORMBUILDER_HELP_NOT_APPLIED_ITEMS"}]</h4>
					[{foreach from=$unapplied item=field}]
					<div class="formbuilder-fieldconfig-element">
						<input type="hidden" name="fieldset[x][fields][]" value="[{$field.OXID}]">
						<ul class="formbuilder-fieldconfig-field-controlls">
							<li data-fieldconfig-field-remove>[ - ]</li>
							<li data-fieldconfig-field-edit='{"oxformid":"[{$oxid}]","oxid":"[{$field.OXID}]","palette":"[{$field.OXTYPE}]"}'>[ e ]</li>
						</ul>
						[{assign var="inputTypeString" value=$field.OXTYPE}]
						[{if $field.OXLABEL == ''}]
						[{oxmultilang ident="FORMBUILDER_VALUE_OXTYPE_$inputTypeString"}]
						[{else}]
						[{$field.OXLABEL}]
						[{/if}] ([{$field.OXTITLE}][{if $field.OXLABEL != ''}], [{oxmultilang ident="FORMBUILDER_VALUE_OXTYPE_$inputTypeString"}][{/if}])
					</div>
					[{/foreach}]
					<p>[{oxmultilang ident="FORMBUILDER_HELP_DRAG_ITEMS_INTO_FIELDSETS"}]</p>
				</div>

				[{if $fieldconfig|@count == 0}]
				[{oxmultilang ident="FORMBUILDER_WARNING_NO_FORM_SELECTED"}]
				[{/if}]
				[{foreach from=$fieldconfig item=formelement key=fieldsetIndex}]
				<fieldset class="formbuilder-fieldconfig">
					<legend><input type="text" name="fieldset[[{$fieldsetIndex}]][legend]" value="[{$formelement.legend}]"></legend>
					<div data-fieldconfig-sort></div>
					<div class="formbuilder-fieldconfig-elements">
						[{if $formelement.fields|@count == 0}]
						[{oxmultilang ident="FORMBUILDER_WARNING_NO_FIELDS_APPLYED"}]
						[{/if}]
						[{if $fieldconfig}]
						[{foreach from=$formelement.fields item=field}]
						<div class="formbuilder-fieldconfig-element">
							<input type="hidden" name="fieldset[[{$fieldsetIndex}]][fields][]" value="[{$field.OXID}]">
							<ul class="formbuilder-fieldconfig-field-controlls">
								<li data-fieldconfig-field-remove>[ - ]</li>
								<li data-fieldconfig-field-edit='{"oxformid":"[{$oxid}]","oxid":"[{$field.OXID}]","palette":"[{$field.OXTYPE}]"}'>[ e ]</li>
							</ul>
							[{assign var="inputTypeString" value=$field.OXTYPE}]
							[{if $field.OXLABEL == ''}]
							[{oxmultilang ident="FORMBUILDER_VALUE_OXTYPE_$inputTypeString"}]
							[{else}]
							[{$field.OXLABEL}]
							[{/if}] ([{$field.OXTITLE}][{if $field.OXLABEL != ''}], [{oxmultilang ident="FORMBUILDER_VALUE_OXTYPE_$inputTypeString"}][{/if}])
						</div>
						[{/foreach}]
						[{/if}]
					</div>
					<div class="formbuilder-fieldconfig-form">
						<h4>Feld konfigurieren</h4>
					</div>
					[{if $formelement.fields|@count gt 0}]
					<ul class="formbuilder-fieldconfig-controlls">
						<li data-fieldconfig-add>[ + ]</li>
						<li data-fieldconfig-remove>[ - ]</li>
					</ul>
					[{/if}]
				</fieldset>
				[{/foreach}]
			</div>
		</div>
	</div>
</form>
</div>

<!-- START new promotion button -->
<div class="actions">
[{strip}]

	<ul>
		<li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.new" href="#" onClick="Javascript:top.oxid.admin.editThis( -1 );return false" target="edit">[{ oxmultilang ident="TOOLTIPS_NEW_FORMBUILDER_FORM" }]</a> |</li>
		[{include file="bottomnavicustom.tpl"}]

		[{ if $sHelpURL }]
		[{* HELP *}]
		<li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.help" href="[{ $sHelpURL }]/[{ 	$oViewConf->getActiveClassName()|oxlower }].html" OnClick="window.open('[{ $sHelpURL }]/[{ 	$oViewConf->getActiveClassName()|lower }].html','OXID_Help','width=800,height=600,resizable=no,scrollbars=yes');return false;">[{ oxmultilang ident="TOOLTIPS_OPENHELP" }]</a></li>
		[{/if}]
	</ul>
[{/strip}]
</div>

<!-- END new promotion button -->
[{oxscript include="../../../modules/ci-haeuser/FormBuilder/out/js/jquery.formbuilder.js"}]
[{oxscript include="../../../modules/ci-haeuser/FormBuilder/out/js/jquery.formbuilder_options.js"}]
[{oxscript include="../../../modules/ci-haeuser/FormBuilder/out/js/jquery.formbuilder_subpalette.js"}]

[{include file="bottomitem.tpl"}]

<script>
jQuery.noConflict();
(function($) {$(function() {

	if($('[data-subpalette]').length) {
		$('[data-subpalette]').each(function() {
			$(this).formbuilder_subpalette();
		});
	}

	if($('.formbuilder-fieldconfig').length) {
		var $formBuilder = $('.formbuilder-fieldconfig').formbuilder({
			formid: '[{$oxid}]',
			editFieldController: 'index.php?cl=ciadminformelementmain&fnc=elementEdit&force_admin_sid=' + $('[name="force_admin_sid"]').val() + '&stoken=' + $('[name="stoken"]').val(),
			saveFieldController: 'index.php?cl=ciadminformelementmain&fnc=elementSave&oxformid=[{$oxid}]&force_admin_sid=' + $('[name="force_admin_sid"]').val() + '&stoken=' + $('[name="stoken"]').val(),
			removeField: function($el, $formbuilderObj) {
				var $element = $el.closest($formbuilderObj.handler.element).insertBefore('.formbuilder-unapplied-elements p'),
					$input = $element.find('input');
				$input.attr('name', $input.attr('name').replace('[fields]', '[unapplied]'));
				$('.formbuilder-unapplied-elements').addClass('visible');
			},
			dialogOpen: function($handler, $formbuilderObj) {
				if($handler.find('[data-subpalette]').length) {
					$handler.find('[data-subpalette]').formbuilder_subpalette();
				}
			}
		})[0].formbuilder;
		/**/

		[{include file="formbuilder_jquery_ui.tpl"}]

		/**/
		$('.formbuilder-unapplied-elements').sortable({
			items: '.formbuilder-fieldconfig-element',
			connectWith: '.formbuilder-fieldconfig .formbuilder-fieldconfig-elements',
			update: function() {
				if(!$('.formbuilder-unapplied-elements .formbuilder-fieldconfig-element').length) {
					$('.formbuilder-unapplied-elements').removeClass('visible');
				} else {
					$('.formbuilder-unapplied-elements').each(function (key, item) {
						var $item = $(item).find('input');
						$item.each(function (index, input) {
							var $input = $(input);
							$input.attr('name', $input.attr('name').replace(/fieldset\[[0-9x]+\](.*)/, function (match, p1) {
								return 'fieldset[x]' + p1;
							}));
						});
					});
				}
				$formBuilder.resetForm();
			}
		});
		/**/
	}
});})(jQuery);
</script>