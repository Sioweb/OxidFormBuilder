
(function ($) {

	"use strict";

	var pluginName = 'formbuilder_subpalette',
		PluginClass;


	/* Enter PluginOptions */
	$[pluginName + 'Default'] = {
		container: window,
		handler: {
			checkable: '[type="checkbox"],[type="radio"]',
			selectable: 'select'
		},
		toggleField: function(name, $formbuilderSubpaletteObject, defaultField) {}
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
			selfObj.item.change(function() {
				selfObj.isVisible();
			});
			selfObj.isVisible();
		};

		this.isVisible = function() {
			if(selfObj.item.is(selfObj.handler.checkable)) {
				selfObj.toggleField(selfObj.item.is(':checked'), selfObj);
				if(selfObj.item.is(':checked')) {
					$('[data-subpalette-container="' + selfObj.item.data('subpalette') + '"]').addClass('visible');
				} else {
					$('[data-subpalette-container="' + selfObj.item.data('subpalette') + '"]').removeClass('visible');
				}
			} else if (selfObj.item.is(selfObj.handler.selectable)) {
				selfObj.toggleField(selfObj.item.data('subpalette') + '_' + selfObj.item.val(), selfObj);
				$('[data-subpalette-container="' + selfObj.item.data('subpalette') + '_' + selfObj.item.val() + '"]').addClass('visible');
			} else {
				selfObj.toggleField(selfObj.item.data('subpalette') + '_' + selfObj.item.val(), selfObj, true);
				$('[data-subpalette-container="' + selfObj.item.data('subpalette') + '_' + selfObj.item.val() + '"]').addClass('visible');
			}
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