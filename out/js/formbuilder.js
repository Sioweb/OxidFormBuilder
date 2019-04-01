(function (window) {
	var FormBuilder = (function () {
		var FormBuilder = function (sel, context) {
			return new FormBuilder.fn.newFormbuilder(sel, context);
		};

		FormBuilder.fn = FormBuilder.prototype = {
			constructor: FormBuilder,
			newFormbuilder: function (sel, context) {
				var match, elem;

				if (!sel) return this;

				if (sel != document)
					elem = FormBuilder.extend({ found: FormBuilder.dom(sel, context) }, FormBuilder);
				else
					elem = FormBuilder.extend({ found: { 0: document } }, FormBuilder);

				return elem;
			}
		};

		FormBuilder.extend = FormBuilder.fn.extend = function () {
			var base = {},
				options = arguments[0] || {};

			if (arguments[1] == undefined && typeof arguments[1] != "boolean")
				base = this;

			for (var i = 0; i < arguments.length; i++)
				if (arguments[i] != null)
					for (value in arguments[i])
						base[value] = arguments[i][value];

			return base;
		};

		FormBuilder.extend({
			handler: {
				fieldset: '.formbuilder-fieldset',
				toggleFieldset: '.formbuilder-fieldset[data-toggle] legend',
			},
			getHandler: function (handler) {
				var forceNoCache = arguments[1] || false;
				if (/**/FormBuilder.handler['$' + handler] === undefined || forceNoCache) {
					if (!/**/FormBuilder.setupHandler(handler)) {
						return false;
					}
				}
				return /**/FormBuilder.handler['$' + handler];
			},
			setupHandler: function (handler) {
				var selector = arguments[1] || /**/FormBuilder.handler[handler];

				if (/**/FormBuilder.handler[handler] !== undefined) {
					/**/FormBuilder.handler['$' + handler] = /**/FormBuilder.item.find(selector);
				} else {
					return;
				}

				if (!/**/FormBuilder.handler['$' + handler].length) {
					return;
				}

				return true;
			}
		});

		FormBuilder.init = function () {
			$(function () {
					
				
				if ($('form.formbuilder') === null) {
					return;
				}
				
				FormBuilder.extend({
					item: $('form.formbuilder')
				});

				if(FormBuilder.getHandler('toggleFieldset')) {
					FormBuilder.getHandler('toggleFieldset').click(function () {
						var $el = $(this),
							$fieldset = $el.closest('fieldset');

						$fieldset.attr('data-toggle', !$fieldset.data('toggle')).data('toggle', !$fieldset.data('toggle'));
					});
				}
			})
		};

		return FormBuilder;
	})();

	window.FormBuilder = FormBuilder;
})(window);

FormBuilder.init();