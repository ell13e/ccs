<?php
/**
 * Base class for meta boxes with multiple field types, nonce, sanitization, and save.
 *
 * Extend this class and pass config to the constructor, then call $this->register() from init.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Meta_Box_Base
 */
abstract class CCS_Meta_Box_Base {

	/**
	 * Meta box ID (used in add_meta_box and as nonce name).
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Meta box title.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Post types this meta box appears on.
	 *
	 * @var array
	 */
	protected $post_types;

	/**
	 * Field definitions: array of array( 'id', 'type', 'label', ... ).
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * Meta box context: normal, side, advanced.
	 *
	 * @var string
	 */
	protected $context;

	/**
	 * Meta box priority: high, core, default, low.
	 *
	 * @var string
	 */
	protected $priority;

	/**
	 * Nonce action (defaults to meta box id).
	 *
	 * @var string
	 */
	protected $nonce_action;

	/**
	 * Nonce name in form.
	 *
	 * @var string
	 */
	protected $nonce_name;

	/**
	 * @param string $id         Meta box ID.
	 * @param string $title      Meta box title.
	 * @param array  $post_types Post type slugs.
	 * @param array  $fields     Field definitions.
	 * @param string $context    Optional. 'normal'|'side'|'advanced'. Default 'normal'.
	 * @param string $priority   Optional. 'high'|'core'|'default'|'low'. Default 'default'.
	 */
	public function __construct( $id, $title, array $post_types, array $fields, $context = 'normal', $priority = 'default' ) {
		$this->id          = sanitize_key( $id );
		$this->title       = $title;
		$this->post_types  = $post_types;
		$this->fields      = $fields;
		$this->context     = $context;
		$this->priority    = $priority;
		$this->nonce_action = $this->id . '_action';
		$this->nonce_name   = $this->id . '_nonce';
	}

	/**
	 * Register the meta box with WordPress.
	 */
	public function register() {
		foreach ( $this->post_types as $post_type ) {
			add_meta_box(
				$this->id,
				$this->title,
				array( $this, 'render' ),
				$post_type,
				$this->context,
				$this->priority
			);
		}
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Output meta box markup (nonce + fields).
	 *
	 * @param \WP_Post $post Current post.
	 */
	public function render( $post ) {
		wp_nonce_field( $this->nonce_action, $this->nonce_name );
		echo '<div class="ccs-meta-box">';
		foreach ( $this->fields as $field ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
			if ( isset( $field['default'] ) && $value === '' && $value !== 0 ) {
				$value = $field['default'];
			}
			$this->render_field( $field, $value, $post->ID );
		}
		echo '</div>';
	}

	/**
	 * Render a single field by type.
	 *
	 * @param array    $field  Field config (id, type, label, options, sub_fields, etc.).
	 * @param mixed    $value  Current value.
	 * @param int|null $post_id Post ID (for repeater/gallery naming).
	 */
	public function render_field( $field, $value, $post_id = null ) {
		$id     = isset( $field['id'] ) ? $field['id'] : '';
		$type   = isset( $field['type'] ) ? $field['type'] : 'text';
		$label  = isset( $field['label'] ) ? $field['label'] : '';
		$desc   = isset( $field['description'] ) ? $field['description'] : '';
		$name   = $this->get_field_name( $id );
		$esc_id = esc_attr( $this->id . '_' . $id );

		$required = ! empty( $field['required'] );
		echo '<div class="ccs-field ccs-field--' . esc_attr( $type ) . '" data-field-id="' . esc_attr( $id ) . '">';
		if ( $label && ! in_array( $type, array( 'checkbox' ), true ) ) {
			echo '<label for="' . $esc_id . '">' . esc_html( $label );
			if ( $required ) {
				echo ' <span class="ccs-required" aria-hidden="true">*</span>';
			}
			echo '</label>';
		}

		switch ( $type ) {
			case 'text':
				echo '<input type="text" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat"' . ( $required ? ' required' : '' ) . ' />';
				break;
			case 'textarea':
				$rows = isset( $field['rows'] ) ? (int) $field['rows'] : 4;
				$maxlength = ! empty( $field['maxlength'] ) ? (int) $field['maxlength'] : '';
				echo '<textarea id="' . $esc_id . '" name="' . esc_attr( $name ) . '" class="widefat" rows="' . esc_attr( $rows ) . '"';
				if ( $maxlength > 0 ) {
					echo ' maxlength="' . esc_attr( $maxlength ) . '"';
				}
				echo '>' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'wysiwyg':
				wp_editor(
					$value,
					$esc_id,
					array(
						'textarea_name' => $name,
						'textarea_rows' => isset( $field['rows'] ) ? (int) $field['rows'] : 10,
						'media_buttons' => ! empty( $field['media_buttons'] ),
						'teeny'        => ! empty( $field['teeny'] ),
						'quicktags'     => ! empty( $field['quicktags'] ),
						'tinymce'       => array(
							'toolbar1' => isset( $field['toolbar'] ) ? $field['toolbar'] : 'formatselect,bold,italic,link,unlink,bullist,numlist,blockquote',
						),
					)
				);
				break;
			case 'select':
				$options = isset( $field['options'] ) ? $field['options'] : array();
				echo '<select id="' . $esc_id . '" name="' . esc_attr( $name ) . '" class="widefat">';
				if ( ! empty( $field['placeholder'] ) ) {
					echo '<option value="">' . esc_html( $field['placeholder'] ) . '</option>';
				}
				foreach ( $options as $opt_val => $opt_label ) {
					$selected = selected( $value, $opt_val, false );
					echo '<option value="' . esc_attr( $opt_val ) . '" ' . $selected . '>' . esc_html( $opt_label ) . '</option>';
				}
				echo '</select>';
				break;
			case 'checkbox':
				$checked = checked( $value, '1', false );
				echo '<label for="' . $esc_id . '">';
				echo '<input type="checkbox" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="1" ' . $checked . ' /> ';
				echo esc_html( $label );
				echo '</label>';
				break;
			case 'number':
				$min = isset( $field['min'] ) ? (int) $field['min'] : '';
				$max = isset( $field['max'] ) ? (int) $field['max'] : '';
				$step = isset( $field['step'] ) ? $field['step'] : '1';
				echo '<input type="number" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat"';
				if ( $min !== '' ) echo ' min="' . esc_attr( $min ) . '"';
				if ( $max !== '' ) echo ' max="' . esc_attr( $max ) . '"';
				echo ' step="' . esc_attr( $step ) . '" />';
				break;
			case 'url':
				echo '<input type="url" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_url( $value ) . '" class="widefat" />';
				break;
			case 'email':
				echo '<input type="email" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat"' . ( $required ? ' required' : '' ) . ' />';
				break;
			case 'date':
				echo '<input type="date" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
				break;
			case 'repeater':
				$this->render_repeater( $field, $value, $post_id );
				break;
			case 'gallery':
				$this->render_gallery( $field, $value, $name, $esc_id );
				break;
			case 'image':
				$this->render_image( $field, $value, $name, $esc_id );
				break;
			case 'user_select':
				$this->render_user_select( $field, $value, $name, $esc_id );
				break;
			default:
				echo '<input type="text" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
		}

		if ( $desc ) {
			echo '<p class="description">' . esc_html( $desc ) . '</p>';
		}
		echo '</div>';
	}

	/**
	 * Render repeater field (add/remove rows).
	 *
	 * @param array    $field   Field config with 'sub_fields'.
	 * @param array    $value   Array of rows.
	 * @param int|null $post_id Post ID.
	 */
	protected function render_repeater( $field, $value, $post_id = null ) {
		$id          = $field['id'];
		$sub_fields  = isset( $field['sub_fields'] ) ? $field['sub_fields'] : array();
		$rows        = is_array( $value ) ? $value : array();
		$button_text = isset( $field['add_button'] ) ? $field['add_button'] : __( 'Add row', 'ccs-wp-theme' );

		echo '<div class="ccs-repeater" data-field-id="' . esc_attr( $id ) . '">';
		echo '<div class="ccs-repeater__rows">';
		foreach ( $rows as $i => $row ) {
			echo '<div class="ccs-repeater__row">';
			foreach ( $sub_fields as $sub ) {
				$sub_name = $id . '[' . $i . '][' . $sub['id'] . ']';
				$sub_val  = isset( $row[ $sub['id'] ] ) ? $row[ $sub['id'] ] : '';
				$sub['id'] = $id . '_' . $i . '_' . $sub['id'];
				echo '<div class="ccs-repeater__field">';
				if ( ! empty( $sub['label'] ) ) {
					echo '<label>' . esc_html( $sub['label'] ) . '</label>';
				}
				$this->render_repeater_sub_field( $sub, $sub_val, $sub_name );
				echo '</div>';
			}
			echo '<button type="button" class="button ccs-repeater__remove">' . esc_html__( 'Remove', 'ccs-wp-theme' ) . '</button>';
			echo '</div>';
		}
		echo '</div>';
		echo '<button type="button" class="button ccs-repeater__add">' . esc_html( $button_text ) . '</button>';
		echo '<script type="text/template" class="ccs-repeater__tpl" data-sub-fields="' . esc_attr( wp_json_encode( $sub_fields ) ) . '" data-name-prefix="' . esc_attr( $id ) . '">';
		echo '</script>';
		echo '</div>';
	}

	/**
	 * Output a single sub-field input for repeater (no wrapper div/label from render_field).
	 *
	 * @param array  $sub     Sub field config.
	 * @param mixed  $sub_val Value.
	 * @param string $name    Input name.
	 */
	protected function render_repeater_sub_field( $sub, $sub_val, $name ) {
		$type = isset( $sub['type'] ) ? $sub['type'] : 'text';
		$esc_id = esc_attr( $sub['id'] . '_' . wp_rand( 1, 99999 ) );
		switch ( $type ) {
			case 'text':
				echo '<input type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $sub_val ) . '" class="widefat" />';
				break;
			case 'textarea':
				echo '<textarea name="' . esc_attr( $name ) . '" class="widefat" rows="2">' . esc_textarea( $sub_val ) . '</textarea>';
				break;
			case 'number':
				echo '<input type="number" name="' . esc_attr( $name ) . '" value="' . esc_attr( $sub_val ) . '" class="widefat" />';
				break;
			case 'url':
				echo '<input type="url" name="' . esc_attr( $name ) . '" value="' . esc_url( $sub_val ) . '" class="widefat" />';
				break;
			case 'email':
				echo '<input type="email" name="' . esc_attr( $name ) . '" value="' . esc_attr( $sub_val ) . '" class="widefat" />';
				break;
			case 'select':
				$options = isset( $sub['options'] ) ? $sub['options'] : array();
				echo '<select name="' . esc_attr( $name ) . '" class="widefat">';
				foreach ( $options as $k => $v ) {
					echo '<option value="' . esc_attr( $k ) . '" ' . selected( $sub_val, $k, false ) . '>' . esc_html( $v ) . '</option>';
				}
				echo '</select>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . checked( $sub_val, '1', false ) . ' />';
				break;
			case 'date':
				echo '<input type="date" name="' . esc_attr( $name ) . '" value="' . esc_attr( $sub_val ) . '" class="widefat" />';
				break;
			default:
				echo '<input type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $sub_val ) . '" class="widefat" />';
		}
	}

	/**
	 * Render gallery field (multiple image IDs).
	 *
	 * @param array  $field  Field config.
	 * @param mixed  $value  Comma-separated IDs or array.
	 * @param string $name   Input name.
	 * @param string $esc_id Escaped ID for the hidden input.
	 */
	protected function render_gallery( $field, $value, $name, $esc_id ) {
		$ids = is_array( $value ) ? $value : array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
		$ids_str = implode( ',', $ids );
		echo '<input type="hidden" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $ids_str ) . '" class="ccs-gallery-ids" />';
		echo '<div class="ccs-gallery-preview">';
		foreach ( $ids as $aid ) {
			$src = wp_get_attachment_image_url( $aid, 'thumbnail' );
			if ( $src ) {
				echo '<span class="ccs-gallery-preview__item" data-id="' . esc_attr( $aid ) . '"><img src="' . esc_url( $src ) . '" alt="" /><button type="button" class="ccs-gallery-preview__remove" aria-label="' . esc_attr__( 'Remove image', 'ccs-wp-theme' ) . '">&times;</button></span>';
			}
		}
		echo '</div>';
		echo '<p><button type="button" class="button ccs-gallery-add">' . esc_html__( 'Add images', 'ccs-wp-theme' ) . '</button></p>';
	}

	/**
	 * Render single image upload field (stores attachment ID).
	 *
	 * @param array  $field  Field config.
	 * @param mixed  $value  Attachment ID.
	 * @param string $name   Input name.
	 * @param string $esc_id Escaped ID for the hidden input.
	 */
	protected function render_image( $field, $value, $name, $esc_id ) {
		$value = absint( $value );
		echo '<input type="hidden" id="' . $esc_id . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="ccs-image-id" />';
		echo '<div class="ccs-image-preview">';
		if ( $value ) {
			$src = wp_get_attachment_image_url( $value, 'thumbnail' );
			if ( $src ) {
				echo '<span class="ccs-image-preview__wrap"><img src="' . esc_url( $src ) . '" alt="" /><button type="button" class="button ccs-image-remove" aria-label="' . esc_attr__( 'Remove image', 'ccs-wp-theme' ) . '">' . esc_html__( 'Remove', 'ccs-wp-theme' ) . '</button></span>';
			}
		}
		echo '</div>';
		echo '<p><button type="button" class="button ccs-image-select">' . esc_html__( 'Select image', 'ccs-wp-theme' ) . '</button></p>';
	}

	/**
	 * Render user select dropdown (stores user ID).
	 *
	 * @param array  $field  Field config; optional 'roles' => array of role slugs.
	 * @param mixed  $value  Selected user ID.
	 * @param string $name   Input name.
	 * @param string $esc_id Escaped ID for the select.
	 */
	protected function render_user_select( $field, $value, $name, $esc_id ) {
		$roles = isset( $field['roles'] ) && is_array( $field['roles'] ) ? $field['roles'] : array( 'administrator', 'editor' );
		$users = get_users( array(
			'orderby' => 'display_name',
			'role__in' => $roles,
			'number'  => 500,
		) );
		$value = absint( $value );
		echo '<select id="' . $esc_id . '" name="' . esc_attr( $name ) . '" class="widefat">';
		echo '<option value="">' . esc_html__( '— Unassigned —', 'ccs-wp-theme' ) . '</option>';
		foreach ( $users as $user ) {
			$selected = selected( $value, (int) $user->ID, false );
			$label = $user->display_name . ( $user->user_email ? ' (' . $user->user_email . ')' : '' );
			echo '<option value="' . esc_attr( $user->ID ) . '" ' . $selected . '>' . esc_html( $label ) . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get form field name for post meta (single value).
	 *
	 * @param string $key Meta key.
	 * @return string
	 */
	protected function get_field_name( $key ) {
		return $key;
	}

	/**
	 * Save meta box data.
	 *
	 * @param int     $post_id Post ID.
	 * @param \WP_Post $post    Post object.
	 */
	public function save( $post_id, $post ) {
		if ( ! isset( $_POST[ $this->nonce_name ] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->nonce_name ] ) ), $this->nonce_action ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! in_array( $post->post_type, $this->post_types, true ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		foreach ( $this->fields as $field ) {
			$id = isset( $field['id'] ) ? $field['id'] : '';
			if ( ! $id ) {
				continue;
			}
			$raw = isset( $_POST[ $id ] ) ? $_POST[ $id ] : null;
			if ( $field['type'] === 'repeater' && is_array( $raw ) ) {
				$sanitized = $this->sanitize_repeater( $raw, $field );
			} elseif ( $field['type'] === 'gallery' ) {
				$raw = isset( $_POST[ $id ] ) ? sanitize_text_field( wp_unslash( $_POST[ $id ] ) ) : '';
				$sanitized = $this->sanitize_field( $raw, $field );
			} else {
				$raw = isset( $_POST[ $id ] ) ? $raw : '';
				if ( is_string( $raw ) ) {
					$raw = wp_unslash( $raw );
				}
				$sanitized = $this->sanitize_field( $raw, $field );
			}
			update_post_meta( $post_id, $id, $sanitized );
		}
	}

	/**
	 * Sanitize a single value by field type.
	 *
	 * @param mixed $value Raw value.
	 * @param array $field Field config (type, options for select).
	 * @return mixed Sanitized value.
	 */
	public function sanitize_field( $value, $field ) {
		$type = isset( $field['type'] ) ? $field['type'] : 'text';
		switch ( $type ) {
			case 'text':
				return sanitize_text_field( $value );
			case 'textarea':
				$value = sanitize_textarea_field( $value );
				if ( ! empty( $field['maxlength'] ) ) {
					$value = mb_substr( $value, 0, (int) $field['maxlength'] );
				}
				return $value;
			case 'wysiwyg':
				return wp_kses_post( $value );
			case 'select':
				$options = isset( $field['options'] ) ? array_keys( $field['options'] ) : array();
				return in_array( $value, $options, true ) ? $value : ( isset( $field['default'] ) ? $field['default'] : '' );
			case 'checkbox':
				return $value ? '1' : '0';
			case 'number':
				$num = is_numeric( $value ) ? $value + 0 : 0;
				if ( isset( $field['min'] ) && $num < $field['min'] ) {
					$num = $field['min'];
				}
				if ( isset( $field['max'] ) && $num > $field['max'] ) {
					$num = $field['max'];
				}
				return $num;
			case 'url':
				return esc_url_raw( $value );
			case 'email':
				return sanitize_email( $value );
			case 'date':
				return sanitize_text_field( $value );
			case 'gallery':
				$ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
				return $ids;
			case 'image':
				return absint( $value );
			case 'user_select':
				return absint( $value );
			default:
				return sanitize_text_field( $value );
		}
	}

	/**
	 * Sanitize repeater rows and sub-fields.
	 *
	 * @param array $raw   Raw $_POST repeater value (array of rows).
	 * @param array $field Field config with sub_fields.
	 * @return array Sanitized rows.
	 */
	protected function sanitize_repeater( $raw, $field ) {
		$sub_fields = isset( $field['sub_fields'] ) ? $field['sub_fields'] : array();
		$out = array();
		foreach ( (array) $raw as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$sanitized_row = array();
			foreach ( $sub_fields as $sub ) {
				$sid = isset( $sub['id'] ) ? $sub['id'] : '';
				if ( ! $sid ) {
					continue;
				}
				$sub_val = isset( $row[ $sid ] ) ? $row[ $sid ] : '';
				if ( is_string( $sub_val ) ) {
					$sub_val = wp_unslash( $sub_val );
				}
				$sanitized_row[ $sid ] = $this->sanitize_field( $sub_val, $sub );
			}
			$out[] = $sanitized_row;
		}
		return $out;
	}
}
