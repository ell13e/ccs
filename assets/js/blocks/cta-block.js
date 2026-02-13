/**
 * CTA block: heading, subheading, button, optional phone. Live preview in editor.
 *
 * @package CCS_WP_Theme
 */

(function (blocks, element, components, blockEditor, i18n) {
	var el = element.createElement;
	var __ = i18n.__;
	var RichText = blockEditor.RichText;
	var TextControl = components.TextControl;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;
	var PanelBody = components.PanelBody;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;

	var styles = (window.ccsCtaBlock && window.ccsCtaBlock.styles) || [
		{ value: 'primary', label: 'Primary' },
		{ value: 'secondary', label: 'Secondary' },
		{ value: 'dark', label: 'Dark' },
	];
	var phone = (window.ccsCtaBlock && window.ccsCtaBlock.phone) || '';
	var phoneHref = (window.ccsCtaBlock && window.ccsCtaBlock.phoneHref) || '';

	blocks.registerBlockType('ccs/cta', {
		apiVersion: 2,
		title: __('Call to Action', 'ccs-wp-theme'),
		description: __('Heading, subheading, and buttons with style options.', 'ccs-wp-theme'),
		category: 'design',
		icon: 'megaphone',
		keywords: [__('CTA', 'ccs-wp-theme'), __('call to action', 'ccs-wp-theme'), __('button', 'ccs-wp-theme')],
		supports: {
			html: false,
			align: ['wide', 'full'],
		},
		attributes: {
			heading: {
				type: 'string',
				default: '',
			},
			subheading: {
				type: 'string',
				default: '',
			},
			buttonText: {
				type: 'string',
				default: '',
			},
			buttonUrl: {
				type: 'string',
				default: '',
			},
			style: {
				type: 'string',
				default: 'primary',
				enum: ['primary', 'secondary', 'dark'],
			},
			showPhone: {
				type: 'boolean',
				default: false,
			},
		},

		edit: function (props) {
			var attrs = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps({
				className: 'ccs-cta ccs-cta--' + (attrs.style || 'primary'),
			});

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						PanelBody,
						{ title: __('Button', 'ccs-wp-theme'), initialOpen: true },
						el(TextControl, {
							label: __('Button text', 'ccs-wp-theme'),
							value: attrs.buttonText,
							onChange: function (val) { setAttributes({ buttonText: val || '' }); },
						}),
						el(TextControl, {
							label: __('Button URL', 'ccs-wp-theme'),
							value: attrs.buttonUrl,
							onChange: function (val) { setAttributes({ buttonUrl: val || '' }); },
							type: 'url',
						}),
						el(SelectControl, {
							label: __('Style', 'ccs-wp-theme'),
							value: attrs.style || 'primary',
							options: styles,
							onChange: function (val) { setAttributes({ style: val }); },
						}),
						el(ToggleControl, {
							label: __('Show phone button', 'ccs-wp-theme'),
							help: phone ? __('Uses site phone from Customizer.', 'ccs-wp-theme') : __('Set phone in Customizer → Contact.', 'ccs-wp-theme'),
							checked: !!attrs.showPhone,
							onChange: function (val) { setAttributes({ showPhone: val }); },
						})
					)
				),
				el(
					'div',
					blockProps,
					el('div', { className: 'ccs-cta__inner' },
						el(RichText, {
							tagName: 'h2',
							className: 'ccs-cta__heading',
							placeholder: __('Heading…', 'ccs-wp-theme'),
							value: attrs.heading,
							onChange: function (val) { setAttributes({ heading: val }); },
							multiline: false,
							allowedFormats: ['core/bold', 'core/italic'],
						}),
						el(RichText, {
							tagName: 'p',
							className: 'ccs-cta__subheading',
							placeholder: __('Subheading…', 'ccs-wp-theme'),
							value: attrs.subheading,
							onChange: function (val) { setAttributes({ subheading: val }); },
							multiline: false,
							allowedFormats: ['core/bold', 'core/italic'],
						}),
						el('div', { className: 'ccs-cta__actions' },
							attrs.buttonText && attrs.buttonUrl
								? el('a', {
									href: '#',
									className: 'ccs-cta__btn ccs-cta__btn--primary',
									onClick: function (e) { e.preventDefault(); },
									role: 'presentation',
								}, attrs.buttonText)
								: null,
							attrs.showPhone && phone
								? el('a', {
									href: phoneHref || '#',
									className: 'ccs-cta__btn ccs-cta__btn--phone',
									onClick: function (e) { e.preventDefault(); },
									role: 'presentation',
								}, phone)
								: null,
							(!attrs.buttonText || !attrs.buttonUrl) && !attrs.showPhone
								? el('span', { className: 'ccs-cta__actions-hint' }, __('Add button text/URL or enable phone in block settings.', 'ccs-wp-theme'))
								: null
						)
					)
				)
			);
		},

		save: function () {
			return null;
		},
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.components,
	window.wp.blockEditor,
	window.wp.i18n
);
