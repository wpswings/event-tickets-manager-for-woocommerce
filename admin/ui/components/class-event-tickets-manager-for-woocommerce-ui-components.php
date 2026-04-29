<?php
/**
 * Reusable admin UI field components.
 *
 * @package Event_Tickets_Manager_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reusable admin UI field components.
 */
class Event_Tickets_Manager_For_Woocommerce_UI_Components {

	/**
	 * Render a list of settings fields.
	 *
	 * @param array $fields Settings field definitions.
	 * @return void
	 */
	public static function render_fields( $fields = array() ) {
		if ( empty( $fields ) || ! is_array( $fields ) ) {
			echo '<div class="wps-etmfw-ui-empty-state">';
			esc_html_e( 'No settings are available for this section yet.', 'event-tickets-manager-for-woocommerce' );
			echo '</div>';
			return;
		}

		foreach ( $fields as $field ) {
			if ( empty( $field['type'] ) ) {
				continue;
			}

			self::render_field( $field );
		}
	}

	/**
	 * Render a single field.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	public static function render_field( $field ) {
		$type = isset( $field['type'] ) ? $field['type'] : '';

		if ( 'hidden' === $type ) {
			printf(
				'<input type="hidden" id="%1$s" name="%2$s" value="%3$s" />',
				esc_attr( self::get_field_id( $field ) ),
				esc_attr( self::get_field_name( $field ) ),
				esc_attr( self::get_field_value( $field ) )
			);
			return;
		}

		$row_classes = array(
			'wps-etmfw-ui-field',
			'wps-etmfw-ui-field--' . sanitize_html_class( $type ),
		);

		if ( in_array( $type, array( 'button', 'submit' ), true ) ) {
			$row_classes[] = 'wps-etmfw-ui-field--actions';
		}

		echo '<div class="' . esc_attr( implode( ' ', $row_classes ) ) . '">';

		if ( ! in_array( $type, array( 'button', 'submit' ), true ) ) {
			echo '<div class="wps-etmfw-ui-field__meta">';
			if ( ! empty( $field['title'] ) ) {
				echo '<label class="wps-etmfw-ui-field__label" for="' . esc_attr( self::get_field_id( $field ) ) . '">' . esc_html( $field['title'] ) . '</label>';
			}

			if ( ! empty( $field['description'] ) && ! in_array( $type, array( 'wps_simple_text', 'wps-new-checkbox' ), true ) ) {
				echo '<div class="wps-etmfw-ui-field__description">' . wp_kses_post( $field['description'] ) . '</div>';
			}
			echo '</div>';
		}

		echo '<div class="wps-etmfw-ui-field__control">';
		self::render_control( $field );
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Render the field control.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_control( $field ) {
		$type  = isset( $field['type'] ) ? $field['type'] : 'text';
		$value = self::get_field_value( $field );

		switch ( $type ) {
			case 'text':
			case 'email':
			case 'number':
			case 'password':
			case 'color':
			case 'date':
			case 'file':
				self::render_input( $field, $type, $value );
				break;
			case 'textarea':
				self::render_textarea( $field, $value );
				break;
			case 'select':
			case 'multiselect':
				self::render_select( $field, $value );
				break;
			case 'checkbox':
				self::render_checkbox( $field, $value );
				break;
			case 'radio':
				self::render_radio( $field, $value );
				break;
			case 'radio-switch':
				self::render_toggle( $field, $value );
				break;
			case 'button':
			case 'submit':
				self::render_button( $field );
				break;
			case 'multi':
				self::render_multi( $field );
				break;
			case 'wps_simple_text':
				self::render_simple_text( $field );
				break;
			case 'wp_editor':
				self::render_editor( $field );
				break;
			case 'textWithButton':
				self::render_text_with_button( $field );
				break;
			case 'wps-new-checkbox':
				self::render_social_checkboxes( $field );
				break;
			default:
				self::render_input( $field, 'text', $value );
				break;
		}
	}

	/**
	 * Render a text-like input.
	 *
	 * @param array  $field Settings field.
	 * @param string $type Input type.
	 * @param mixed  $value Field value.
	 * @return void
	 */
	protected static function render_input( $field, $type, $value ) {
		$attributes = array(
			'type'        => $type,
			'id'          => self::get_field_id( $field ),
			'name'        => self::get_field_name( $field ),
			'value'       => is_scalar( $value ) ? $value : '',
			'class'       => 'wps-etmfw-ui-input ' . self::get_field_class( $field ),
			'placeholder' => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
		);

		if ( ! empty( $field['required'] ) && 'yes' === $field['required'] ) {
			$attributes['required'] = 'required';
		}

		foreach ( array( 'min', 'max', 'step', 'readonly' ) as $attribute_key ) {
			if ( isset( $field[ $attribute_key ] ) && '' !== $field[ $attribute_key ] ) {
				$attributes[ $attribute_key ] = $field[ $attribute_key ];
			}
		}

		if ( ! empty( $field['custom_attribute'] ) && is_array( $field['custom_attribute'] ) ) {
			$attributes = array_merge( $attributes, $field['custom_attribute'] );
		}

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
			$attributes = array_merge( $attributes, $field['custom_attributes'] );
		}

		echo '<div class="wps-etmfw-ui-control-box wps-etmfw-ui-control-box--input">';
		echo '<input ' . self::build_attributes( $attributes ) . ' />';
		echo '</div>';
	}

	/**
	 * Render textarea.
	 *
	 * @param array $field Settings field.
	 * @param mixed $value Value.
	 * @return void
	 */
	protected static function render_textarea( $field, $value ) {
		$attributes = array(
			'id'          => self::get_field_id( $field ),
			'name'        => self::get_field_name( $field ),
			'class'       => 'wps-etmfw-ui-textarea ' . self::get_field_class( $field ),
			'placeholder' => isset( $field['placeholder'] ) ? $field['placeholder'] : '',
			'rows'        => 4,
		);

		if ( ! empty( $field['required'] ) && 'yes' === $field['required'] ) {
			$attributes['required'] = 'required';
		}

		echo '<div class="wps-etmfw-ui-control-box wps-etmfw-ui-control-box--textarea">';
		echo '<textarea ' . self::build_attributes( $attributes ) . '>' . esc_textarea( is_scalar( $value ) ? (string) $value : '' ) . '</textarea>';
		echo '</div>';
	}

	/**
	 * Render select.
	 *
	 * @param array $field Settings field.
	 * @param mixed $value Value.
	 * @return void
	 */
	protected static function render_select( $field, $value ) {
		$is_multiple = 'multiselect' === $field['type'];
		$name        = self::get_field_name( $field );
		$options     = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array();

		$attributes = array(
			'id'    => self::get_field_id( $field ),
			'name'  => $is_multiple ? $name . '[]' : $name,
			'class' => 'wps-etmfw-ui-select ' . self::get_field_class( $field ),
		);

		if ( $is_multiple ) {
			$attributes['multiple'] = 'multiple';
		}

		echo '<div class="wps-etmfw-ui-control-box wps-etmfw-ui-control-box--select">';
		echo '<select ' . self::build_attributes( $attributes ) . '>';

		foreach ( $options as $option_value => $option_label ) {
			$is_selected = false;

			if ( is_array( $value ) ) {
				$is_selected = in_array( (string) $option_value, array_map( 'strval', $value ), true );
			} else {
				$is_selected = (string) $value === (string) $option_value;
			}

			printf(
				'<option value="%1$s" %2$s>%3$s</option>',
				esc_attr( $option_value ),
				selected( $is_selected, true, false ),
				esc_html( $option_label )
			);
		}

		echo '</select>';
		echo '</div>';
	}

	/**
	 * Render checkbox.
	 *
	 * @param array $field Settings field.
	 * @param mixed $value Value.
	 * @return void
	 */
	protected static function render_checkbox( $field, $value ) {
		$input_id = self::get_field_id( $field );
		echo '<label class="wps-etmfw-ui-checkbox" for="' . esc_attr( $input_id ) . '">';
		printf(
			'<input type="checkbox" id="%1$s" name="%2$s" class="%3$s" value="1" %4$s />',
			esc_attr( $input_id ),
			esc_attr( self::get_field_name( $field ) ),
			esc_attr( trim( self::get_field_class( $field ) ) ),
			checked( self::is_checked_value( $value ), true, false )
		);
		echo '<span class="wps-etmfw-ui-checkbox__indicator" aria-hidden="true"></span>';
		if ( ! empty( $field['description'] ) ) {
			echo '<span class="wps-etmfw-ui-checkbox__label">' . wp_kses_post( $field['description'] ) . '</span>';
		}
		echo '</label>';
	}

	/**
	 * Render radio group.
	 *
	 * @param array $field Settings field.
	 * @param mixed $value Value.
	 * @return void
	 */
	protected static function render_radio( $field, $value ) {
		$options = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array();
		echo '<div class="wps-etmfw-ui-radio-group">';

		foreach ( $options as $option_value => $option_label ) {
			$input_id = self::get_field_id( $field ) . '-' . sanitize_html_class( (string) $option_value );
			echo '<label class="wps-etmfw-ui-radio" for="' . esc_attr( $input_id ) . '">';
			printf(
				'<input type="radio" id="%1$s" name="%2$s" value="%3$s" %4$s />',
				esc_attr( $input_id ),
				esc_attr( self::get_field_name( $field ) ),
				esc_attr( $option_value ),
				checked( (string) $value, (string) $option_value, false )
			);
			echo '<span class="wps-etmfw-ui-radio__indicator" aria-hidden="true"></span>';
			echo '<span class="wps-etmfw-ui-radio__label">' . esc_html( $option_label ) . '</span>';
			echo '</label>';
		}

		echo '</div>';
	}

	/**
	 * Render toggle switch.
	 *
	 * @param array $field Settings field.
	 * @param mixed $value Value.
	 * @return void
	 */
	protected static function render_toggle( $field, $value ) {
		$input_id = self::get_field_id( $field );
		$checked  = self::is_checked_value( $value );

		echo '<label class="wps-etmfw-ui-toggle" for="' . esc_attr( $input_id ) . '" data-state="' . esc_attr( $checked ? 'on' : 'off' ) . '">';
		printf(
			'<input type="checkbox" id="%1$s" name="%2$s" class="wps-etmfw-ui-toggle__input %3$s" value="on" role="switch" aria-label="%4$s" aria-checked="%5$s" %6$s />',
			esc_attr( $input_id ),
			esc_attr( self::get_field_name( $field ) ),
			esc_attr( trim( self::get_field_class( $field ) ) ),
			esc_attr( isset( $field['title'] ) ? $field['title'] : __( 'Toggle setting', 'event-tickets-manager-for-woocommerce' ) ),
			$checked ? 'true' : 'false',
			checked( $checked, true, false )
		);
		echo '<span class="wps-etmfw-ui-toggle__track" aria-hidden="true"></span>';
		echo '<span class="wps-etmfw-ui-toggle__thumb" aria-hidden="true"></span>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Toggle setting', 'event-tickets-manager-for-woocommerce' ) . '</span>';
		echo '</label>';
	}

	/**
	 * Render button.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_button( $field ) {
		$button_text = isset( $field['button_text'] ) ? $field['button_text'] : __( 'Save Changes', 'event-tickets-manager-for-woocommerce' );
		printf(
			'<button type="submit" id="%1$s" name="%2$s" class="wps-etmfw-ui-button wps-etmfw-ui-button--primary %3$s">%4$s</button>',
			esc_attr( self::get_field_id( $field ) ),
			esc_attr( self::get_field_name( $field ) ),
			esc_attr( trim( self::get_field_class( $field ) ) ),
			esc_html( $button_text )
		);
	}

	/**
	 * Render grouped fields.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_multi( $field ) {
		$children = isset( $field['value'] ) && is_array( $field['value'] ) ? $field['value'] : array();

		echo '<div class="wps-etmfw-ui-multi">';
		foreach ( $children as $child ) {
			$child_value = isset( $_POST[ $child['id'] ] ) ? wp_unslash( $_POST[ $child['id'] ] ) : '';
			$child['value'] = $child_value;
			self::render_control( $child );
		}
		echo '</div>';
	}

	/**
	 * Render helper text block.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_simple_text( $field ) {
		echo '<div class="wps-etmfw-ui-control-box wps-etmfw-ui-control-box--note ' . esc_attr( self::get_field_class( $field ) ) . '">';
		if ( ! empty( $field['description'] ) ) {
			$description = wp_kses_post( $field['description'] );
			$description = preg_replace( '/(\[[^\]]+\])/', '<code>$1</code>', $description, 1 );
			echo $description;
		}
		echo '</div>';
	}

	/**
	 * Render wp_editor field.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_editor( $field ) {
		$content   = is_scalar( self::get_field_value( $field ) ) ? (string) self::get_field_value( $field ) : '';
		$editor_id = self::get_field_id( $field );
		$settings  = array(
			'media_buttons' => false,
			'teeny'         => true,
			'textarea_name' => self::get_field_name( $field ),
			'textarea_rows' => 10,
			'editor_class'  => 'wps-etmfw-ui-editor',
		);

		echo '<div class="wps-etmfw-ui-editor-wrap">';
		wp_editor( wp_kses_post( $content ), $editor_id, $settings );
		echo '</div>';
	}

	/**
	 * Render a composed text + button control.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_text_with_button( $field ) {
		$items = isset( $field['custom_attribute'] ) && is_array( $field['custom_attribute'] ) ? $field['custom_attribute'] : array();

		echo '<div class="wps-etmfw-ui-composed-control ' . esc_attr( self::get_field_class( $field ) ) . '">';
		foreach ( $items as $item ) {
			$item_type = isset( $item['type'] ) ? $item['type'] : '';
			if ( 'text' === $item_type ) {
				self::render_input( $item, 'text', isset( $item['value'] ) ? $item['value'] : '' );
			} elseif ( 'button' === $item_type ) {
				printf(
					'<button type="button" id="%1$s" class="wps-etmfw-ui-button wps-etmfw-ui-button--secondary %2$s">%3$s</button>',
					esc_attr( self::get_field_id( $item ) ),
					esc_attr( self::get_field_class( $item ) ),
					esc_html( isset( $item['button_text'] ) ? $item['button_text'] : __( 'Choose', 'event-tickets-manager-for-woocommerce' ) )
				);
			} elseif ( 'paragraph' === $item_type ) {
				echo '<div id="' . esc_attr( isset( $item['id'] ) ? $item['id'] : '' ) . '" class="wps-etmfw-ui-preview-box">';
				echo '<img src="" id="' . esc_attr( isset( $item['imgId'] ) ? $item['imgId'] : '' ) . '" alt="" />';
				echo '<button type="button" class="wps-etmfw-ui-preview-remove ' . esc_attr( isset( $item['spanX'] ) ? $item['spanX'] : '' ) . '">' . esc_html( isset( $item['button_text'] ) ? $item['button_text'] : __( 'Remove', 'event-tickets-manager-for-woocommerce' ) ) . '</button>';
				echo '</div>';
			}
		}
		echo '</div>';
	}

	/**
	 * Render the social checkbox bundle used in feedback settings.
	 *
	 * @param array $field Settings field.
	 * @return void
	 */
	protected static function render_social_checkboxes( $field ) {
		$social_options = array(
			'wps_etmfw_social_share_facebook'  => __( 'Facebook', 'event-tickets-manager-for-woocommerce' ),
			'wps_etmfw_social_share_twitter'   => __( 'X', 'event-tickets-manager-for-woocommerce' ),
			'wps_etmfw_social_share_gmail'     => __( 'Gmail', 'event-tickets-manager-for-woocommerce' ),
			'wps_etmfw_social_share_whatsapp'  => __( 'WhatsApp', 'event-tickets-manager-for-woocommerce' ),
			'wps_etmfw_social_share_pinterest' => __( 'Pinterest', 'event-tickets-manager-for-woocommerce' ),
		);

		echo '<div class="wps-etmfw-ui-chip-group">';
		foreach ( $social_options as $option_key => $option_label ) {
			$is_checked = self::is_checked_value( get_option( $option_key ) );
			echo '<label class="wps-etmfw-ui-chip" for="' . esc_attr( $option_key ) . '">';
			printf(
				'<input type="checkbox" id="%1$s" name="%1$s" value="on" %2$s />',
				esc_attr( $option_key ),
				checked( $is_checked, true, false )
			);
			echo '<span>' . esc_html( $option_label ) . '</span>';
			echo '</label>';
		}
		echo '</div>';

		if ( ! empty( $field['description'] ) ) {
			echo '<div class="wps-etmfw-ui-field__description">' . wp_kses_post( $field['description'] ) . '</div>';
		}
	}

	/**
	 * Get field name.
	 *
	 * @param array $field Settings field.
	 * @return string
	 */
	protected static function get_field_name( $field ) {
		return isset( $field['name'] ) && '' !== $field['name'] ? $field['name'] : self::get_field_id( $field );
	}

	/**
	 * Get field id.
	 *
	 * @param array $field Settings field.
	 * @return string
	 */
	protected static function get_field_id( $field ) {
		return isset( $field['id'] ) ? $field['id'] : uniqid( 'wps-etmfw-' );
	}

	/**
	 * Get field value.
	 *
	 * @param array $field Settings field.
	 * @return mixed
	 */
	protected static function get_field_value( $field ) {
		return isset( $field['value'] ) ? $field['value'] : '';
	}

	/**
	 * Get CSS class string.
	 *
	 * @param array $field Settings field.
	 * @return string
	 */
	protected static function get_field_class( $field ) {
		return isset( $field['class'] ) ? $field['class'] : '';
	}

	/**
	 * Build HTML attributes.
	 *
	 * @param array $attributes Attributes.
	 * @return string
	 */
	protected static function build_attributes( $attributes ) {
		$compiled = array();

		foreach ( $attributes as $attribute => $attribute_value ) {
			if ( '' === $attribute_value || null === $attribute_value ) {
				continue;
			}

			if ( is_bool( $attribute_value ) ) {
				if ( $attribute_value ) {
					$compiled[] = esc_attr( $attribute );
				}
				continue;
			}

			$compiled[] = sprintf( '%1$s="%2$s"', esc_attr( $attribute ), esc_attr( $attribute_value ) );
		}

		return implode( ' ', $compiled );
	}

	/**
	 * Determine if a stored value should be considered checked.
	 *
	 * @param mixed $value Stored value.
	 * @return bool
	 */
	protected static function is_checked_value( $value ) {
		return in_array( $value, array( 'on', 'yes', '1', 1, true ), true );
	}
}
