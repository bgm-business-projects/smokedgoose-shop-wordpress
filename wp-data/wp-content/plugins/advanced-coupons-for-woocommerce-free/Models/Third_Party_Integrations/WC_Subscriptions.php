<?php
namespace ACFWF\Models\Third_Party_Integrations;

use ACFWF\Abstracts\Abstract_Main_Plugin_Class;
use ACFWF\Abstracts\Base_Model;
use ACFWF\Helpers\Helper_Functions;
use ACFWF\Helpers\Plugin_Constants;
use ACFWF\Interfaces\Model_Interface;
use ACFWF\Models\Store_Credits\Checkout as Store_Credits_Checkout;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Model that houses the logic of the WooCommerce Subscriptions module.
 *
 * @since 4.6.4
 */
class WC_Subscriptions extends Base_Model implements Model_Interface {
    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
     */

    /**
     * Property that holds the single main instance of WC_Subscriptions.
     *
     * @since 4.6.4
     * @access private
     * @var Store_Credits_Checkout
     */
    private $_sc_checkout;

    /**
     * Property that houses the excluded meta keys to be removed from subscriptions and renewal orders.
     *
     * @since 4.6.4
     * @access private
     * @var array
     */
    private $_excluded_meta_keys = array(
        'acfw_store_credits_order_paid',
        'acfw_store_credits_version',
    );

    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
     */

    /**
     * Class constructor.
     *
     * @since 4.6.4
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin, Plugin_Constants $constants, Helper_Functions $helper_functions ) {
        parent::__construct( $main_plugin, $constants, $helper_functions );
        $main_plugin->add_to_all_plugin_models( $this );

        $this->_sc_checkout = Store_Credits_Checkout::get_instance( $main_plugin, $constants, $helper_functions );
    }

    /**
     * Prevent the after tax store credits from being applied to recurring carts.
     *
     * @since 4.6.4
     * @access public
     *
     * @param bool     $is_valid  The validation result.
     * @param float    $cart_total The cart total.
     * @param \WC_Cart $cart The cart object.
     * @return mixed The store credit data or null.
     */
    public function prevent_store_credits_apply_on_recurring_carts( $is_valid, $cart_total, $cart ) {

        // Return false if the cart is a recurring one.
        if ( isset( $cart->recurring_cart_key ) ) {
            $is_valid = false;
        }

        return $is_valid;
    }

    /**
     * Disable the application of store credits on subscription orders.
     *
     * @since 4.6.4
     * @access public
     *
     * @param bool                       $is_enabled Whether the apply store credits button is enabled.
     * @param \WC_Order|\WC_Subscription $order The order object.
     */
    public function disable_apply_store_credits_order_in_subscription( $is_enabled, $order ) {
        if ( $order instanceof \WC_Subscription ) {
            $is_enabled = false;
        }

        return $is_enabled;
    }

    /**
     * Unset excluded meta keys from the renewal order data.
     *
     * @since 4.6.4
     * @access public
     *
     * @param array $order_data The renewal order data.
     * @return array The modified renewal order data.
     */
    public function unset_excluded_meta_keys_for_renewal_order( $order_data ) {
        // Remove excluded meta keys from the order data if they exist.
        foreach ( $this->_excluded_meta_keys as $meta_key ) {
            unset( $order_data[ $meta_key ] );
        }

        return $order_data;
    }

    /**
     * Update the renewal WooCommerce Subscriptions order total after it's created.
     *
     * @since 4.6.4
     * @access public
     *
     * @param \WC_Order        $renewal_order The renewal order object.
     * @param \WC_Subscription $subscription The subscription object associated with the renewal order.
     */
    public function update_renewal_order_total( $renewal_order, $subscription ) {
        // Re-Calculate the totals based on the current state of the renewal order.
        $renewal_order->calculate_totals();
        $renewal_order->save();

        return $renewal_order;
    }

    /**
     * Remove excluded meta keys from the subscription order upon creation.
     *
     * @since 4.6.4
     * @access public
     *
     * @param \WC_Subscription $subscription The subscription object being created.
     * @param array            $posted_data   Data posted during checkout.
     * @param \WC_Order        $order         The order associated with the subscription.
     * @param \WC_Cart         $cart          The current cart object.
     */
    public function remove_excluded_meta_keys_from_subscription( $subscription, $posted_data, $order, $cart ) {
        // Remove specified meta keys.
        foreach ( $this->_excluded_meta_keys as $meta_key ) {
            if ( $subscription->meta_exists( $meta_key ) ) {
                $subscription->delete_meta_data( $meta_key );
            }
        }
    }

    /**
     * Remove excluded meta keys from the subscription order upon recalculation.
     *
     * @since 4.6.4
     * @access public
     *
     * @param bool                       $and_taxes Whether to recalculate taxes.
     * @param \WC_Order|\WC_Subscription $order The order object.
     */
    public function remove_excluded_meta_keys_from_subscription_on_recalculate( $and_taxes, $order ) {
        if ( ! $order instanceof \WC_Subscription ) {
            return;
        }

        // Remove specified meta keys.
        foreach ( $this->_excluded_meta_keys as $meta_key ) {
            if ( $order->meta_exists( $meta_key ) ) {
                $order->delete_meta_data( $meta_key );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
     */

    /**
     * Execute WC_Subscriptions class.
     *
     * @since 4.6.4
     * @access public
     * @inherit ACFWF\Interfaces\Model_Interface
     */
    public function run() {
        if ( $this->_helper_functions->is_plugin_active( Plugin_Constants::WC_SUBSCRIPTIONS ) ) {

            // Checkout related hooks.

            // Remove hook for applying after tax store credits.
            remove_filter( 'woocommerce_calculated_total', array( $this->_sc_checkout, 'apply_store_credit_discount' ), 10, 2 );

            // Re-add hook for applying after tax store credits but set to 1001 priority.
            add_filter( 'woocommerce_calculated_total', array( $this->_sc_checkout, 'apply_store_credit_discount' ), 1001, 2 );

            // Prevent the after tax store credits from being applied to recurring carts.
            add_filter( 'acfw_validate_cart_before_apply_store_credits', array( $this, 'prevent_store_credits_apply_on_recurring_carts' ), 10, 3 );

            // Remove excluded meta keys from the subscription order upon creation.
            add_action( 'woocommerce_checkout_create_subscription', array( $this, 'remove_excluded_meta_keys_from_subscription' ), 999, 4 );

            // Update the renewal WooCommerce Subscriptions order total after it's created.
            add_filter( 'wcs_renewal_order_created', array( $this, 'update_renewal_order_total' ), 10, 2 );

            // Admin related hooks.

            // Disable the application of store credits on subscription orders.
            add_filter( 'acfw_enable_apply_store_credits_order', array( $this, 'disable_apply_store_credits_order_in_subscription' ), 10, 2 );

            // Renewal order related hooks.
            add_filter( 'wc_subscriptions_renewal_order_data', array( $this, 'unset_excluded_meta_keys_for_renewal_order' ), 10, 1 );

            // Add hooks to remove excluded meta keys from subscriptions on recalculate.
            add_action( 'woocommerce_order_before_calculate_totals', array( $this, 'remove_excluded_meta_keys_from_subscription_on_recalculate' ), 10, 2 );
        }
    }
}
