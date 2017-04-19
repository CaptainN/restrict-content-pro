<?php
/**
 * RCP Discounts class
 *
 * This class handles querying, inserting, updating, and removing discounts
 * Also includes other discount helper functions
 *
 * @package     Restrict Content Pro
 * @subpackage  Classes/Discounts
 * @copyright   Copyright (c) 2017, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5
 */


class RCP_Discounts {

	/**
	 * Holds the name of our discounts database table
	 *
	 * @access  public
	 * @since   1.5
	 */
	public $db_name;


	/**
	 * Holds the version number of our discounts database table
	 *
	 * @access  public
	 * @since   1.5
	 */
	public $db_version;


	/**
	 * Get things started
	 *
	 * @since   1.5
	 * @return  void
	 */
	function __construct() {

		$this->db_name    = rcp_get_discounts_db_name();
		$this->db_version = '1.2';

	}


	/**
	 * Retrieve discounts from the database
	 *
	 * @param array $args Query arguments.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  array|false Array of discounts or false if none.
	 */
	public function get_discounts( $args = array() ) {
		global $wpdb;

		$where = '';

		// TODO: Add optional args for limit, order, etc
		if( ! empty( $args['status'] ) )
			$where = " WHERE status='{$args['status']}'";

		$discounts = $wpdb->get_results( "SELECT * FROM {$this->db_name}{$where};" );

		if( $discounts )
			return $discounts;
		return false;

	}


	/**
	 * Retrieve a specific discount from the database
	 *
	 * @param  int $discount_id ID of the discount to retrieve.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  object|null Database row or null on failure.
	 */
	public function get_discount( $discount_id = 0 ) {
		global $wpdb;

		$discount = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->db_name} WHERE id='%d';", $discount_id ) );

		return $discount;

	}


	/**
	 * Retrieve a specific discount from the database by field
	 *
	 * @param string $field Name of the field to check.
	 * @param string $value Value of the field.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  object|null Database row or null on failure.
	 */
	public function get_by( $field = 'code', $value = '' ) {
		global $wpdb;

		$discount = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$this->db_name} WHERE {$field}='%s';", $value ) );

		return $discount;

	}


	/**
	 * Get the status of a discount
	 *
	 * @deprecated 2.9 Use RCP_Discount::$status instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  string|false Discount status or false on failure.
	 */
	public function get_status( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return $discount->status;

	}


	/**
	 * Get the amount of a discount
	 *
	 * @deprecated 2.9 Use RCP_Discount::$amount instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  int|float
	 */
	public function get_amount( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return $discount->amount;

	}


	/**
	 * Get the number of times a discount has been used
	 *
	 * @deprecated 2.9 Use RCP_Discount::$use_count instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  int
	 */
	public function get_uses( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return absint( $discount->use_count );

	}


	/**
	 * Get the maximum number of times a discount can be used
	 *
	 * @deprecated 2.9 Use RCP_Discount::$max_uses instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  int
	 */
	public function get_max_uses( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return absint( $discount->max_uses );

	}


	/**
	 * Get the associated subscription level for a discount
	 *
	 * @deprecated 2.9 Use RCP_Discount::$subscription_id instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.6
	 * @return  int
	 */
	public function get_subscription_id( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return absint( $discount->subscription_id );

	}


	/**
	 * Checks whether a discount code has a subscription associated
	 *
	 * @deprecated 2.9 Use RCP_Discount::has_subscription_id() instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.6
	 * @return  bool
	 */
	public function has_subscription_id( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return $discount->has_subscription_id();

	}


	/**
	 * Increase the use count of a discount by 1
	 *
	 * @deprecated 2.9 Use RCP_Discount::increase_uses() instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  void
	*/
	public function increase_uses( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );
		$discount->increase_uses();

	}

	/**
	 * Decrease the use count of a discount by 1
	 *
	 * @deprecated 2.9 Use RCP_Discount::decrease_uses() instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   2.8
	 * @return  void
	 */
	public function decrease_uses( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );
		$discount->decrease_uses();

	}


	/**
	 * Get the expiration date of a discount
	 *
	 * @deprecated 2.9 Use RCP_Discount::$expiration instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  string|false Expiration date, or false if it never expires.
	 */
	public function get_expiration( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		if( ! empty( $discount->expiration ) ) {
			return $discount->expiration;
		}

		return false;

	}


	/**
	 * Get the discount type
	 *
	 * @deprecated 2.9 Use RCP_Discount::$unit instead.
	 *
	 * @param  int $discount_id ID of the discount.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  string|false
	 */
	public function get_type( $discount_id = 0 ) {

		$discount = $this->get_discount( $discount_id );

		if( $discount ) {
			return $discount->unit;
		}

		return false;

	}


	/**
	 * Store a discount in the database
	 *
	 * @param  array $args Arguments for the discount code.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  int|false ID of the newly created discount code or false on failure.
	 */
	public function insert( $args = array() ) {

		global $wpdb;

		$defaults = array(
			'name'           => '',
			'description'    => '',
			'amount'         => '0.00',
			'status'         => 'inactive',
			'unit'           => '%',
			'code'           => '',
			'expiration'     => '',
			'max_uses' 	     => 0,
			'use_count'      => '0',
			'subscription_id'=> 0
		);

		$args = wp_parse_args( $args, $defaults );

		$amount = $this->format_amount( $args['amount'], $args['unit'] );

		if ( is_wp_error( $amount ) ) {
			return $amount;
		} else {
			$args['amount'] = $amount;
		}

		$args['code'] = strtolower( $args['code'] );

		if( $this->get_by( 'code', $args['code'] ) ) {
			return false; // this code already exists
		}

		do_action( 'rcp_pre_add_discount', $args );

		$add = $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO {$this->db_name} SET
					`name`           = '%s',
					`description`    = '%s',
					`amount`         = '%s',
					`status`         = 'active',
					`unit`           = '%s',
					`code`           = '%s',
					`expiration`     = '%s',
					`max_uses`       = '%d',
					`use_count`      = '0',
					`subscription_id`= '%d'
				;",
				sanitize_text_field( $args['name'] ),
				strip_tags( $args['description'] ),
				sanitize_text_field( $args['amount'] ),
				$args['unit'],
				sanitize_text_field( $args['code'] ),
				sanitize_text_field( $args['expiration'] ),
				absint( $args['max_uses'] ),
				absint( $args['subscription_id'] )
			)
		);

		if( $add ) {

			$discount_id = $wpdb->insert_id;

			do_action( 'rcp_add_discount', $args, $discount_id );

			return $discount_id;

		}

		return false;
	}


	/**
	 * Update an existing discount
	 *
	 * @param  int   $discount_id ID of the discount to update.
	 * @param  array $args        Array of fields/values to update.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  bool Whether or not the update was successful.
	 */
	public function update( $discount_id = 0, $args = array() ) {

		global $wpdb;

		$discount = $this->get_discount( $discount_id );
		$discount = get_object_vars( $discount );

		$args     = array_merge( $discount, $args );

		$amount = $this->format_amount( $args['amount'], $args['unit'] );

		if ( is_wp_error( $amount ) ) {
			return $amount;
		} else {
			$args['amount'] = $amount;
		}

		do_action( 'rcp_pre_edit_discount', absint( $discount_id ), $args );

		$update = $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$this->db_name} SET
					`name`            = '%s',
					`description`     = '%s',
					`amount`          = '%s',
					`unit`            = '%s',
					`code`            = '%s',
					`status`          = '%s',
					`expiration`      = '%s',
					`max_uses`        = '%d',
					`use_count`       = '%d',
					`subscription_id` = '%d'
					WHERE `id`        = '%d'
				;",
				sanitize_text_field( $args['name'] ),
				strip_tags( $args['description'] ),
				sanitize_text_field( $args['amount'] ),
				$args['unit'],
				sanitize_text_field( $args['code'] ),
				sanitize_text_field( $args['status'] ),
				sanitize_text_field( $args['expiration'] ),
				absint( $args['max_uses'] ),
				absint( $args['use_count'] ),
				absint( $args['subscription_id'] ),
				absint( $discount_id )
			)
		);

		do_action( 'rcp_edit_discount', absint( $discount_id ), $args );

		if( $update )
			return true;
		return false;

	}


	/**
	 * Delete a discount code
	 *
	 * @param  int $discount_id ID of the discount to delete.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  void
	 */
	public function delete( $discount_id = 0 ) {
		global $wpdb;
		do_action( 'rcp_delete_discount', $discount_id );
		$remove = $wpdb->query( $wpdb->prepare( "DELETE FROM {$this->db_name} WHERE `id` = '%d';", absint( $discount_id ) ) );
	}


	/**
	 * Check if a discount is maxed out
	 *
	 * @param  int $discount_id ID of the discount to check.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  bool
	 */
	public function is_maxed_out( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return $discount->is_maxed_out();

	}


	/**
	 * Check if a discount is expired
	 *
	 * @param  int $discount_id ID of the discount to check.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  bool
	 */
	public function is_expired( $discount_id = 0 ) {

		$discount = new RCP_Discount( $discount_id );

		return $discount->is_expired();

	}


	/**
	 * Add a discount to a user's history
	 *
	 * @param  int    $user_id ID of the user to add the discount to.
	 * @param  string $discount_code Discount code to add.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  void
	 */
	public function add_to_user( $user_id = 0, $discount_code = '' ) {

		$discount = new RCP_Discount( $discount_code );
		$discount->add_to_user( $user_id );

	}

	/**
	 * Remove a discount from a user's history
	 *
	 * @param  int    $user_id ID of the user to remove the discount from.
	 * @param  string $discount_code Discount code to remove.
	 *
	 * @access  public
	 * @since   2.8
	 * @return  bool Whether or not the discount was removed.
	 */
	public function remove_from_user( $user_id, $discount_code = '' ) {

		$discount = new RCP_Discount( $discount_code );

		return $discount->remove_from_user( $user_id );

	}


	/**
	 * Check if a user has used a discount
	 *
	 * @param  int    $user_id ID of the user to check.
	 * @param  string $discount_code Discount code to check.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  bool
	 */
	public function user_has_used( $user_id = 0, $discount_code = '' ) {

		$discount = new RCP_Discount( $discount_code );

		return $discount->user_has_used( $user_id );

	}


	/**
	 * Format the discount code
	 *
	 * @param int|float $amount Discount amount.
	 * @param string    $type   Type of discount - either '%' or 'flat'.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  string
	 */
	public function format_discount( $amount = '', $type = '' ) {

		if( $type == '%' ) {
			$discount = $amount . '%';
		} elseif( $type == 'flat' ) {
			$discount = rcp_currency_filter( $amount );
		}

		return $discount;

	}


	/**
	 * Calculate the discounted price
	 *
	 * @param int|float $base_price      Full price without the discount.
	 * @param int|float $discount_amount Amount of the discount code.
	 * @param string    $type            Type of discount - either '%' or 'flat'.
	 *
	 * @access  public
	 * @since   1.5
	 * @return  int|float
	 */
	public function calc_discounted_price( $base_price = '', $discount_amount = '', $type = '%' ) {

		$discounted_price = $base_price;
		if( $type == '%' ) {
			$discounted_price = $base_price - ( $base_price * ( $discount_amount / 100 ) );
		} elseif($type == 'flat') {
			$discounted_price = $base_price - $discount_amount;
		}

		return $discounted_price;

	}


	/**
	 * Sanitizes the discount amount
	 *
	 * @param int|float $amount The discount amount.
	 * @param string    $type   The discount type - either '%' or 'flat'.
	 *
	 * @access public
	 * @since  2.4.9
	 * @return mixed|array|WP_Error
	 */
	public function format_amount( $amount, $type ) {

		if ( empty( $amount ) || ! is_numeric( $amount ) ) {
			return new WP_Error( 'amount_missing', __( 'Please enter a discount amount containing numbers only.', 'rcp' ) );
		}

		if ( ! isset( $type ) || ! in_array( $type, array( '%', 'flat' ) ) ) {
			$type = 'flat';
		}

		if ( '%' === $type && ! filter_var( $amount, FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1, 'max_range' => 100 ) ) ) ) {
			return new WP_Error( 'invalid_percent', __( 'Percentage discounts must be whole numbers between 1 and 100.', 'rcp' ) );
		}

		return filter_var( $amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION  );
	}


}
