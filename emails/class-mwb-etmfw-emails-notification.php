<?php
/**
 * This file is used to include email template.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Event_Tickets_Manager_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mwb_Etmfw_Emails_Notification' ) ) {

	/**
	 * Woocommerce Custom Email template to send event mails.
	 *
	 * @package    Event_Tickets_Manager_For_Woocommerce
	 * @subpackage Event_Tickets_Manager_For_Woocommerce/emails
	 * @author     WPSwings <webmaster@WPSwings.com>
	 */
	class Mwb_Etmfw_Emails_Notification extends WC_Email {

		/**
		 * Email Content to send in mail.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $email_content    Email content in mail.
		 */
		public $email_content;

		/**
		 * Email subject for mail.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $mwb_etmfw_email_subject    Email subject for mail.
		 */
		public $mwb_etmfw_email_subject;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->id             = 'mwb_etmfw_email_notification';
			$this->title          = __( 'Event order email', 'event-tickets-manager-for-woocommerce' );
			$this->customer_email = true;
			$this->description    = __( 'This email send to the customer at every event.', 'event-tickets-manager-for-woocommerce' );
			$this->template_html  = 'mwb-etmfw-email-notification-template.php';
			$this->template_plain = 'plain/mwb-etmfw-email-notification-template.php';
			$this->template_base  = EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_PATH . 'emails/templates/';
			$this->placeholders   = array(
				'{site_title}'       => $this->get_blogname(),
			);

			// Call parent constructor.
			parent::__construct();
		}

		/**
		 * Get email subject.
		 *
		 * @since      1.0.0
		 * @return string
		 */
		public function get_default_subject() {
			return $this->mwb_etmfw_email_subject;
		}

		/**
		 * Get email heading.
		 *
		 * @since      1.0.0
		 * @return string
		 */
		public function get_default_heading() {
			return __( 'Event order received.', 'event-tickets-manager-for-woocommerce' );
		}

		/**
		 * Trigger the sending of this email.
		 *
		 * @since      1.0.0
		 * @param string $user_email User Email.
		 * @param string $email_content Email content.
		 * @param string $mwb_etmfw_email_subject Email Subject.
		 * @param object $order Order Object.
		 */
		public function trigger( $user_email, $email_content, $mwb_etmfw_email_subject, $order ) {
			$this->setup_locale();

			if ( is_a( $order, 'WC_Order' ) ) {
				$this->object                         = $order;
				$this->email_content = $email_content;
				$this->mwb_etmfw_email_subject = $mwb_etmfw_email_subject;
				$this->recipient = $user_email;
				$email_already_sent = $order->get_meta( '_new_order_email_sent' );
			}

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
			}
			$this->restore_locale();
		}

		/**
		 * Get content html.
		 *
		 * @since      1.0.0
		 * @access public
		 * @return string
		 */
		public function get_content_html() {
			return wc_get_template_html(
				$this->template_html,
				array(
					'email_content'   => $this->email_content,
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => false,
					'plain_text'    => false,
					'email'         => $this,
				),
				'event-tickets-manager-for-woocommerce',
				$this->template_base
			);
		}

		/**
		 * Get content plain.
		 *
		 * @since      1.0.0
		 * @access public
		 * @return string
		 */
		public function get_content_plain() {
			return wc_get_template_html(
				$this->template_plain,
				array(
					'email_content'   => $this->email_content,
					'email_heading' => $this->get_heading(),
					'sent_to_admin' => false,
					'plain_text'    => true,
					'email' => $this,
				),
				'event-tickets-manager-for-woocommerce',
				$this->template_base
			);
		}

		/**
		 * Initialise settings form fields.
		 *
		 * @since      1.0.0
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'event-tickets-manager-for-woocommerce' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable this email notification', 'event-tickets-manager-for-woocommerce' ),
					'default' => 'yes',
				),
				'heading' => array(
					'title'       => __( 'Email heading', 'event-tickets-manager-for-woocommerce' ),
					'type'        => 'text',
					'desc_tip'    => true,
					/* translators: %s: list of placeholders */
					'description' => sprintf( __( 'Available placeholders: %s', 'event-tickets-manager-for-woocommerce' ), '<code>{site_title}</code>' ),
					'placeholder' => $this->get_default_heading(),
					'default'     => '',
				),
				'email_type' => array(
					'title'       => __( 'Email type', 'event-tickets-manager-for-woocommerce' ),
					'type'        => 'select',
					'description' => __( 'Choose which format of email to send.', 'event-tickets-manager-for-woocommerce' ),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
					'desc_tip'    => true,
				),
			);
		}
	}
}
return new Mwb_Etmfw_Emails_Notification();
