/**
 * FAQ accordion block: repeater of question/answer, optional schema.
 *
 * @package CCS_WP_Theme
 */

(function (blocks, element, components, blockEditor, i18n) {
	var el = element.createElement;
	var __ = i18n.__;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var ToggleControl = components.ToggleControl;
	var Button = components.Button;
	var PanelBody = components.PanelBody;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType('ccs/faq', {
		apiVersion: 2,
		title: __('FAQ Accordion', 'ccs-wp-theme'),
		description: __('Expandable frequently asked questions with optional FAQ schema.', 'ccs-wp-theme'),
		category: 'design',
		icon: 'editor-help',
		keywords: [__('FAQ', 'ccs-wp-theme'), __('accordion', 'ccs-wp-theme'), __('questions', 'ccs-wp-theme')],
		attributes: {
			faqs: {
				type: 'array',
				default: [],
			},
			showSchema: {
				type: 'boolean',
				default: true,
			},
		},

		edit: function (props) {
			var attrs = props.attributes;
			var setAttributes = props.setAttributes;
			var faqs = Array.isArray(attrs.faqs) ? attrs.faqs : [];
			var blockProps = useBlockProps({ className: 'ccs-faq ccs-faq--editor' });

			function updateFaq(index, field, value) {
				var next = faqs.slice();
				if (!next[index]) next[index] = { question: '', answer: '' };
				next[index] = { ...next[index], [field]: value };
				setAttributes({ faqs: next });
			}

			function addFaq() {
				setAttributes({ faqs: faqs.concat([{ question: '', answer: '' }]) });
			}

			function removeFaq(index) {
				var next = faqs.filter(function (_, i) { return i !== index; });
				setAttributes({ faqs: next });
			}

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						PanelBody,
						{ title: __('Schema', 'ccs-wp-theme'), initialOpen: true },
						el(ToggleControl, {
							label: __('Output FAQPage schema', 'ccs-wp-theme'),
							help: __('Add JSON-LD for search engines (recommended).', 'ccs-wp-theme'),
							checked: attrs.showSchema !== false,
							onChange: function (val) { setAttributes({ showSchema: val }); },
						})
					)
				),
				el(
					'div',
					blockProps,
					el('div', { className: 'ccs-faq__list' },
						faqs.length === 0
							? el(
								'div',
								{ className: 'ccs-faq__empty' },
								__('Add your first FAQ below.', 'ccs-wp-theme')
							)
							: faqs.map(function (item, index) {
								return el(
									'div',
									{ key: index, className: 'ccs-faq__item ccs-faq__item--edit' },
									el('div', { className: 'ccs-faq__edit-row' },
										el(TextControl, {
											label: __('Question', 'ccs-wp-theme'),
											value: item.question || '',
											onChange: function (v) { updateFaq(index, 'question', v); },
											placeholder: __('Enter question', 'ccs-wp-theme'),
										}),
										el(TextareaControl, {
											label: __('Answer', 'ccs-wp-theme'),
											value: item.answer || '',
											onChange: function (v) { updateFaq(index, 'answer', v); },
											placeholder: __('Enter answer', 'ccs-wp-theme'),
											rows: 3,
										}),
										el(
											Button,
											{
												isDestructive: true,
												isSmall: true,
												onClick: function () { removeFaq(index); },
											},
											__('Remove', 'ccs-wp-theme')
										)
									)
								);
							})
					),
					el(
						Button,
						{
							variant: 'secondary',
							isSmall: true,
							onClick: addFaq,
							className: 'ccs-faq__add',
						},
						__('Add FAQ', 'ccs-wp-theme')
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
