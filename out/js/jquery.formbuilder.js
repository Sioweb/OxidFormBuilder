
(function ($) {

	"use strict";

	var pluginName = 'formbuilder',
		PluginClass;


	/* Enter PluginOptions */
	$[pluginName + 'Default'] = {
		container: window,
		handler: {
			element: '.formbuilder-fieldconfig-element',
			elements: '.formbuilder-fieldconfig-elements',
			addFieldConfig: '.formbuilder-fieldconfig-controlls [data-fieldconfig-add]',
			removeFieldConfig: '.formbuilder-fieldconfig-controlls [data-fieldconfig-remove]',
			fieldsetSort: '[data-fieldconfig-sort]',
			fieldRemove: '[data-fieldconfig-field-remove]'
		},
		removeField: function ($el, formbuilderObj) { }
	};

	PluginClass = function () {
		var selfObj = this;
		this.item = false;
		this.initOptions = new Object($[pluginName + 'Default']);


		this.init = function (elem) {
			selfObj = this;

			if (!this.container) {
				this.container = window;
			}
			this.elem = elem;
			this.item = $(this.elem);
			this.container = $(this.container);

			this.loaded();
		};

		this.getHandler = function (handler) {
			var forceNoCache = arguments[1] || false;
			if (selfObj.handler['$' + handler] === undefined || forceNoCache) {
				if (!selfObj.setupHandler(handler)) {
					return false;
				}
			}
			return selfObj.handler['$' + handler];
		};

		this.setupHandler = function (handler) {
			var selector = arguments[1] || selfObj.handler[handler];

			if (selfObj.handler[handler] !== undefined) {
				selfObj.handler['$' + handler] = selfObj.item.find(selector);
			} else {
				return;
			}

			if (!selfObj.handler['$' + handler].length) {
				return;
			}

			return true;
		};

		this.loaded = function () {
			selfObj.setupSortable();
			selfObj.setupDroppable();
			selfObj.setupControlls();

			selfObj.lateBinding();

			if (selfObj.getHandler('fieldsetSort')) {
				selfObj.item.parent().sortable({
					handle: selfObj.handler.fieldsetSort,
					update: function () {
						selfObj.resetForm();
					}
				});
			}
		};

		this.lateBinding = function () {
			selfObj.setupFieldControlls();
		};

		this.setupSortable = function () {
			if (!selfObj.getHandler('elements')) {
				return;
			}
			selfObj.getHandler('elements').sortable({
				connectWith: selfObj.handler.elements,
				update: function () {
					selfObj.resetForm();
				}
			});
		};

		this.setupDroppable = function () {
			selfObj.getHandler('elements').droppable();
		};

		this.resetForm = function () {
			$('.formbuilder-fieldconfig').each(function (key, item) {
				var $item = $(item).find('input');
				$item.each(function (index, input) {
					var $input = $(input);
					$input.attr('name', $input.attr('name').replace(/fieldset\[[0-9x]+\](.*)/, function (match, p1) {
						return 'fieldset[' + key + ']' + p1;
					}));
				});
			});

			selfObj.lateBinding();
		};

		this.setupControlls = function () {
			if (!selfObj.getHandler('addFieldConfig')) {
				return;
			}
			selfObj.getHandler('addFieldConfig')
				.unbind('click').click(function () {
					var $newItem = selfObj.item.clone().insertAfter(selfObj.item),
						$formbuilder = $newItem.formbuilder($.extend(true, {}, selfObj.extra));

					selfObj.resetForm();
					$formbuilder[0].formbuilder.getHandler('elements').html('');
				});

			selfObj.getHandler('removeFieldConfig')
				.unbind('click').click(function () {
					if(selfObj.getHandler('element', true)) {
						selfObj.getHandler('element').each(function() {
							selfObj.removeField($(this), selfObj);
						});
					}
					selfObj.item.remove();
					selfObj.resetForm();
				});
		};

		this.setupFieldControlls = function () {
			if (!selfObj.getHandler('fieldRemove', true)) {
				return;
			}

			selfObj.getHandler('fieldRemove', true)
				.unbind('click').click(function () {
					selfObj.removeField($(this), selfObj);
					selfObj.resetForm();
				});
		};
	};

	$[pluginName] = $.fn[pluginName] = function (settings) {
		var element = typeof this === 'function' ? $('html') : this,
			newData = arguments[1] || {},
			returnElement = [];

		returnElement[0] = element.each(function (k, i) {
			var pluginClass = $.data(this, pluginName);

			if (!settings || typeof settings === 'object' || settings === 'init') {

				if (!pluginClass) {
					if (settings === 'init') {
						settings = arguments[1] || {};
					}
					pluginClass = new PluginClass();

					var newOptions = new Object(pluginClass.initOptions);

					/* Space to reset some standart options */

					/***/
					pluginClass.extra = {};
					if (settings) {
						pluginClass.extra = settings;
						newOptions = $.extend(true, {}, newOptions, settings);
					}
					pluginClass = $.extend(newOptions, pluginClass);
					/** Initialisieren. */
					this[pluginName] = pluginClass;
					pluginClass.init(this);

					if (element[0].nodeName.toLowerCase() !== 'html') {
						$.data(this, pluginName, pluginClass);
					}
				} else {
					pluginClass.init(this, 1);
					if (element[0].nodeName.toLowerCase() !== 'html') {
						$.data(this, pluginName, pluginClass);
					}
				}
			} else if (!pluginClass) {
				return;
			} else if (pluginClass[settings]) {
				var method = settings;
				returnElement[1] = pluginClass[method](newData);
			} else {
				return;
			}
		});

		if (returnElement[1] !== undefined) {
			return returnElement[1];
		}

		return returnElement[0];
	};
})(jQuery);