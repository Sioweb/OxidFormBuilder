if(window['jQuery']) {
(function ($) {

	"use strict";

	var pluginName = 'formbuilder_options',
		PluginClass;


	/* Enter PluginOptions */
	$[pluginName + 'Default'] = {
		container: window,
		handler: {
			optionsContainer: '.formbuilder-element-optionswidget-input',
			optionsAdd: '[data-formbuilder-options-add]',
			optionsRemove: '[data-formbuilder-options-remove]'
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
			selfObj.lateBinding();
		};

		this.lateBinding = function () {
			selfObj.setupOptionsControlls();
		};

		this.resetForm = function () {
			selfObj.getHandler('optionsContainer', true).each(function (key, item) {
				var $item = $(item).find('input');
				$item.each(function (index, input) {
					var $input = $(input),
						$label = $input.closest('label');

					$input.attr('name', $input.attr('name').replace(/options\]\[[0-9x]+\](.*)/, function (match, p1) {
						return 'options][' + key + ']' + p1;
					}));
					$input.attr('id', $input.attr('id').replace(/_[0-9]+(_[a-z]+)/, function (match, p1) {
						return '_' + key + p1;
					}));
					$label.attr('for', $label.attr('for').replace(/_[0-9]+(_[a-z]+)/, function (match, p1) {
						return '_' + key + p1;
					}));
				});
			});

			selfObj.lateBinding();
		};

		this.setupOptionsControlls = function () {
			if(!selfObj.getHandler('optionsRemove')) {
				return;
			}

			selfObj.getHandler('optionsRemove', true)
				.unbind('click').click(function () {
					var $el = $(this),
						$parent = $el.closest(selfObj.handler.optionsContainer);
					
					selfObj.removeField($(this), selfObj);
					$parent.remove();
					selfObj.resetForm();
				});
				
			if(!selfObj.getHandler('optionsAdd')) {
				return;
			}
				
			selfObj.getHandler('optionsAdd', true)
				.unbind('click').click(function () {
					var $el = $(this),
						$parent = $el.closest(selfObj.handler.optionsContainer),
						$newItem = $parent.clone().insertAfter($parent);

					selfObj.loaded();
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
})(jQuery);}