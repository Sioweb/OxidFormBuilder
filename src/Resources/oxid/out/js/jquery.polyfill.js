
(function ($) {

	"use strict";

	function toType(obj) {
		if (obj == null) {
			return obj + "";
		}

		return typeof obj === "object" ?
			class2type[toString.call(obj)] || "object" :
			typeof obj;
	}

	// Multifunctional method to get and set values of a collection
	// The value/s can optionally be executed if it's a function
	var access = function (elems, fn, key, value, chainable, emptyGet, raw) {
		var i = 0,
			len = elems.length,
			bulk = key == null;

		// Sets many values
		if (toType(key) === "object") {
			chainable = true;
			for (i in key) {
				access(elems, fn, i, key[i], true, emptyGet, raw);
			}

			// Sets one value
		} else if (value !== undefined) {
			chainable = true;

			if (typeof value !== "function") {
				raw = true;
			}

			if (bulk) {

				// Bulk operations run against the entire set
				if (raw) {
					fn.call(elems, value);
					fn = null;

					// ...except when executing function values
				} else {
					bulk = fn;
					fn = function (elem, _key, value) {
						return bulk.call(jQuery(elem), value);
					};
				}
			}

			if (fn) {
				for (; i < len; i++) {
					fn(
						elems[i], key, raw ?
							value :
							value.call(elems[i], i, fn(elems[i], key))
					);
				}
			}
		}

		if (chainable) {
			return elems;
		}

		// Gets
		if (bulk) {
			return fn.call(elems);
		}

		return len ? fn(elems[0], key) : emptyGet;
	};

	var rfocusable = /^(?:input|select|textarea|button)$/i,
		rclickable = /^(?:a|area)$/i;

	jQuery.fn.extend({
		prop: function (name, value) {
			return access(this, jQuery.prop, name, value, arguments.length > 1);
		},

		removeProp: function (name) {
			return this.each(function () {
				delete this[jQuery.propFix[name] || name];
			});
		}
	});

	jQuery.extend({
		prop: function (elem, name, value) {
			var ret, hooks,
				nType = elem.nodeType;

			// Don't get/set properties on text, comment and attribute nodes
			if (nType === 3 || nType === 8 || nType === 2) {
				return;
			}

			if (nType !== 1 || !jQuery.isXMLDoc(elem)) {

				// Fix name and attach hooks
				name = jQuery.propFix[name] || name;
				hooks = jQuery.propHooks[name];
			}

			if (value !== undefined) {
				if (hooks && "set" in hooks &&
					(ret = hooks.set(elem, value, name)) !== undefined) {
					return ret;
				}

				return (elem[name] = value);
			}

			if (hooks && "get" in hooks && (ret = hooks.get(elem, name)) !== null) {
				return ret;
			}

			return elem[name];
		},

		propHooks: {
			tabIndex: {
				get: function (elem) {

					// Support: IE <=9 - 11+
					// elem.tabIndex doesn't always return the
					// correct value when it hasn't been explicitly set
					// https://web.archive.org/web/20141116233347/http://fluidproject.org/blog/2008/01/09/getting-setting-and-removing-tabindex-values-with-javascript/
					// Use proper attribute retrieval(#12072)
					var tabindex = jQuery.find.attr(elem, "tabindex");

					if (tabindex) {
						return parseInt(tabindex, 10);
					}

					if (
						rfocusable.test(elem.nodeName) ||
						rclickable.test(elem.nodeName) &&
						elem.href
					) {
						return 0;
					}

					return -1;
				}
			}
		},

		propFix: {
			"for": "htmlFor",
			"class": "className"
		}
	});

	// Support: IE <=11+
	// Accessing the selectedIndex property forces the browser to respect
	// setting selected on the option. The getter ensures a default option
	// is selected when in an optgroup. ESLint rule "no-unused-expressions"
	// is disabled for this code since it considers such accessions noop.
	if (document.documentMode) {
		jQuery.propHooks.selected = {
			get: function (elem) {

				var parent = elem.parentNode;
				if (parent && parent.parentNode) {
					// eslint-disable-next-line no-unused-expressions
					parent.parentNode.selectedIndex;
				}
				return null;
			},
			set: function (elem) {


				var parent = elem.parentNode;
				if (parent) {
					// eslint-disable-next-line no-unused-expressions
					parent.selectedIndex;

					if (parent.parentNode) {
						// eslint-disable-next-line no-unused-expressions
						parent.parentNode.selectedIndex;
					}
				}
			}
		};
	}

	jQuery.each([
		"tabIndex",
		"readOnly",
		"maxLength",
		"cellSpacing",
		"cellPadding",
		"rowSpan",
		"colSpan",
		"useMap",
		"frameBorder",
		"contentEditable"
	], function () {
		jQuery.propFix[this.toLowerCase()] = this;
	});
})(jQuery);