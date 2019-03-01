<fieldset id="formbuilder-fieldset-[{$id}]"class="formbuilder-fieldset[{if isset($class)}] [{$class}][{/if}]">
    [{foreach from=$fields item=field}]
    [{$field->raw}]
    [{/foreach}]
</fieldset>