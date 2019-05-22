
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="ciadminformelementmain">
</form>

<form class="formbuilder" name="myedit" enctype="multipart/form-data" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
    
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="ciadminformelementmain">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="editval[ci_form_element__oxid]" value="[{ $oxid }]">
    <input type="hidden" name="sorting" value="">
    <input type="hidden" name="stable" value="">
    <input type="hidden" name="starget" value="">

    <div class="formbuilder-inner">
        [{ $form }]
    </div>
</form>
</div>

[{oxscript include="js/libs/jquery.min.js"}]
[{oxscript include="js/libs/jquery-ui.min.js"}]
[{oxscript include="../../../modules/ci-haeuser/FormBuilder/out/js/jquery.formbuilder_options.js"}]
[{oxscript include="../../../modules/ci-haeuser/FormBuilder/out/js/jquery.formbuilder_subpalette.js"}]

<!-- START new promotion button -->
<div class="actions">
[{strip}]

  <ul>
    <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.new" href="#" onClick="Javascript:top.oxid.admin.editThis( -1 );return false" target="edit">[{ oxmultilang ident="TOOLTIPS_NEW_FORMBUILDER_ELEMENT" }]</a> |</li>
    [{include file="bottomnavicustom.tpl"}]

    [{ if $sHelpURL }]
    [{* HELP *}]
    <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.help" href="[{ $sHelpURL }]/[{ 	$oViewConf->getActiveClassName()|oxlower }].html" OnClick="window.open('[{ $sHelpURL }]/[{ 	$oViewConf->getActiveClassName()|lower }].html','OXID_Help','width=800,height=600,resizable=no,scrollbars=yes');return false;">[{ oxmultilang ident="TOOLTIPS_OPENHELP" }]</a></li>
    [{/if}]
  </ul>
[{/strip}]
</div>

<!-- END new promotion button -->

[{include file="bottomitem.tpl"}]

<script>
jQuery.noConflict();
(function($) {$(function() {

    if($('[data-subpalette]').length) {
        $('[data-subpalette]').formbuilder_subpalette();
    }

	if($('.formbuilder-element-optionswidget').length) {
		var $formbuilderOptions = $('.formbuilder-element-optionswidget').formbuilder_options({
			elementid: '[{$oxid}]',
			removeField: function($el, $formbuilderObj) {
			}
		})[0].formbuilder_options;

		[{include file="formbuilder_jquery_ui.tpl"}]
	}
});})(jQuery);
</script>