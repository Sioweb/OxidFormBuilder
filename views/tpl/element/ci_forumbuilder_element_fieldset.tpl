<fieldset id="formbuilder-fieldset-[{$form.id}]_[{$id}]" class="formbuilder-fieldset[{if isset($class)}] [{$class}][{/if}]">
    [{foreach from=$fields item=field}]
    [{$field->raw}]
    [{/foreach}]
</fieldset>