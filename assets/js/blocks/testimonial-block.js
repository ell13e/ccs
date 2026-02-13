/**
 * Testimonial block: select a testimonial and choose layout / image / rating.
 * Rendered on the server.
 *
 * @package CCS_WP_Theme
 */

(function (blocks, element, components, blockEditor, serverSideRender, i18n) {
	var el = element.createElement;
	var __ = i18n.__;
	var SelectControl = components.SelectControl;
	var PanelBody = components.PanelBody;
	var ToggleControl = components.ToggleControl;
	var useBlockProps = blockEditor.useBlockProps;
	var InspectorControls = blockEditor.InspectorControls;
	var ServerSideRender = serverSideRender.ServerSideRender;
	var Placeholder = components.Placeholder;

	var testimonials = (window.ccsTestimonialBlock && window.ccsTestimonialBlock.testimonials) || [];
	var layouts = (window.ccsTestimonialBlock && window.ccsTestimonialBlock.layouts) || [
		{ value: 'card', label: 'Card' },
		{ value: 'inline', label: 'Inline' },
		{ value: 'minimal', label: 'Minimal' },
	];

	blocks.registerBlockType('ccs/testimonial', {
		apiVersion: 2,
		title: __('Testimonial', 'ccs-wp-theme'),
		description: __('Display a testimonial from your testimonial library.', 'ccs-wp-theme'),
		category: 'widgets',
		icon: 'format-quote',
		keywords: [__('testimonial', 'ccs-wp-theme'), __('quote', 'ccs-wp-theme')],
		supports: {
			html: false,
			align: ['wide', 'full'],
		},
		attributes: {
			testimonialId: {
				type: 'number',
				default: 0,
			},
			layout: {
				type: 'string',
				default: 'card',
				enum: ['card', 'inline', 'minimal'],
			},
			showImage: {
				type: 'boolean',
				default: true,
			},
			showRating: {
				type: 'boolean',
				default: true,
			},
		},

		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps();
			var id = attributes.testimonialId;
			var layout = attributes.layout;
			var showImage = attributes.showImage;
			var showRating = attributes.showRating;
			var selectedLabel = '';
			if (id && testimonials.length) {
				var found = testimonials.find(function (t) { return t.value === id; });
				if (found) selectedLabel = found.label;
			}

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						PanelBody,
						{ title: __('Testimonial settings', 'ccs-wp-theme'), initialOpen: true },
						el(SelectControl, {
							label: __('Testimonial', 'ccs-wp-theme'),
							value: id,
							options: testimonials,
							onChange: function (val) {
								setAttributes({ testimonialId: val ? parseInt(val, 10) : 0 });
							},
						}),
						el(SelectControl, {
							label: __('Layout', 'ccs-wp-theme'),
							value: layout,
							options: layouts,
							onChange: function (val) {
								setAttributes({ layout: val });
							},
						}),
						el(ToggleControl, {
							label: __('Show photo', 'ccs-wp-theme'),
							checked: showImage,
							onChange: function (val) {
								setAttributes({ showImage: val });
							},
						}),
						el(ToggleControl, {
							label: __('Show rating', 'ccs-wp-theme'),
							checked: showRating,
							onChange: function (val) {
								setAttributes({ showRating: val });
							},
						})
					)
				),
				el(
					'div',
					blockProps,
					id
						? el(
								ServerSideRender,
								{
									block: 'ccs/testimonial',
									attributes: {
										testimonialId: id,
										layout: layout,
										showImage: showImage,
										showRating: showRating,
									},
								}
							)
						: el(
								Placeholder,
								{
									className: 'ccs-testimonial-block-placeholder',
									label: __('Testimonial', 'ccs-wp-theme'),
									instructions: __('Choose a testimonial from the block settings (sidebar).', 'ccs-wp-theme'),
								}
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
	window.wp.serverSideRender,
	window.wp.i18n
);
