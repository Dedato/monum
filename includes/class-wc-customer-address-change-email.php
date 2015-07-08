<?php
  
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Customer_Address_Change_Email' ) ) :

/**
 * A custom address change WooCommerce Email class
 *
 * @class       WC_Customer_Address_Change_Email
 * @version     2.3.0
 * @package     WooCommerce/Classes/Emails
 * @author      WooThemes
 * @extends     WC_Email
 */
class WC_Customer_Address_Change_Email extends WC_Email {

  public $woocommerce;
  public $current_user;
  
	/**
	 * Set email defaults
	 *
	 * @since 0.1
	 */
	function __construct() {

		// set ID, this simply needs to be a unique name
		$this->id = 'wc_customer_address_changed';

		// this is the title in WooCommerce Email settings
		$this->title = __('Customer Address Change', 'monum');

		// this is the description in WooCommerce email settings
		$this->description = __('Address Change Notification emails are sent when a customer changes his billing or shipping address', 'monum');

		// these are the default heading and subject lines that can be overridden using the settings
		$this->heading = __('Customer Address Change', 'monum');
		$this->subject = __('Customer Address Change', 'monum');

		// these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
		$this->template_html  = 'emails/admin-address-change.php';
		$this->template_plain = 'emails/plain/admin-address-change.php';
    
		// Trigger on new paid orders
		add_action( 'woocommerce_customer_save_address', array( $this, 'trigger' ) );

		// Call parent constructor to load any other defaults not explicity defined here
		parent::__construct();

		// this sets the recipient to the settings defined below in init_form_fields()
		$this->recipient = $this->get_option( 'recipient' );

		// if none was entered, just use the WP admin email as a fallback
		if ( ! $this->recipient )
			$this->recipient = get_option( 'admin_email' );
	}


	/**
   * trigger function.
   *
   * @access public
   * @return void
   */
	function trigger( $user_id ) {
  
		if ( $user_id ) {
			$this->object 		= new WP_User( $user_id );
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() )
			return;
    
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}
  
  
	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		wc_get_template( $this->template_html, array(
			'email_heading' => $this->get_heading(),
			'current_user'  => $this->current_user,
			'blogname'      => $this->get_blogname(),
			'sent_to_admin' => true,
			'plain_text'    => false
		) );
		return ob_get_clean();
	}


	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		wc_get_template( $this->template_plain, array(
			'email_heading' => $this->get_heading(),
			'current_user'  => $this->current_user,
			'blogname'      => $this->get_blogname(),
			'sent_to_admin' => true,
			'plain_text'    => true
		) );
		return ob_get_clean();
	}


	/**
	 * Initialise Settings Form Fields
   *
   * @access public
   * @return void
	 */
	function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
        'title'         => __( 'Enable/Disable', 'woocommerce' ),
        'type'          => 'checkbox',
        'label'         => __( 'Enable this email notification', 'woocommerce' ),
        'default'       => 'yes'
      ),
			'recipient'  => array(
				'title'       => __( 'Recipient(s)', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', 'woocommerce' ), esc_attr( get_option('admin_email') ) ),
				'placeholder' => '',
				'default'     => ''
			),
			'subject'    => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', 'woocommerce' ), $this->subject ),
				'placeholder' => '',
				'default'     => ''
			),
			'heading'    => array(
				'title'       => __( 'Email Heading', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', 'woocommerce' ), $this->heading ),
				'placeholder' => '',
				'default'     => ''
			),
			'email_type' => array(
				'title'       => __( 'Email type', 'woocommerce' ),
				'type'        => 'select',
				'description' => __( 'Choose which format of email to send.', 'woocommerce' ),
				'default'     => 'html',
				'class'       => 'email_type wc-enhanced-select',
				'options'     => $this->get_email_type_options()
			)
		);
	}


}

endif;

return new WC_Customer_Address_Change_Email();
