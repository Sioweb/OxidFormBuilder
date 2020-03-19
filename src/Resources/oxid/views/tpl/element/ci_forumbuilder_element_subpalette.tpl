<div id="formbuilder-subpalette-[{$form.id}]_[{$id}]" data-subpalette-container="[{$id}]" class="formbuilder-subpalette[{if isset($class)}] [{$class}][{/if}]">
    [{foreach from=$fields item=field}]
    [{$field->raw}]
    [{/foreach}]
</div>