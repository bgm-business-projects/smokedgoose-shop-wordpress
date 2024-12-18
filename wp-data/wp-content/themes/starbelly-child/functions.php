<?php
/**
 * starbelly-child functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package starbelly-child
 */

/**
 * Load the parent style.css and child styles.css file
 */
function starbelly_child_stylesheets() {
  // Dynamically get version number of the parent stylesheet
  $parent_style = 'starbelly-style';
  $parent_dep = array( 'bootstrap', 'fontawesome', 'datepicker', 'swiper', 'starbelly-mapbox', 'magnific-popup' );
  $child_style = 'starbelly-child-style';
  $rtl_style = 'starbelly-rtl';
  $version = wp_get_theme()->get('Version');

  // Load the main stylesheet
  wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', $parent_dep );

  if ( is_rtl() ) {
	// Load the rtl stylesheet
	wp_enqueue_style( $rtl_style, get_template_directory_uri() . '/rtl.css', array( $parent_style ), $version );

	// Load the child stylesheet
	wp_enqueue_style( $child_style, get_stylesheet_directory_uri() . '/style.css', array( $parent_style, $rtl_style ), $version );
  } else {
	// Load the child stylesheet
	wp_enqueue_style( $child_style, get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), $version );
  }
}
add_action( 'wp_enqueue_scripts', 'starbelly_child_stylesheets' );

// 建立一個自訂的短代碼來顯示登入使用者名稱並修改字體顏色
function display_logged_in_username() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        return '<span style="color: #734B24;">' . esc_html($current_user->display_name) . '</span>';
    } else {
        return '<span style="color: #734B24;">尚未登入</span>';
    }
}
add_shortcode('display_username', 'display_logged_in_username');


// 新增註冊欄位：名字，姓氏和電話號碼等
function wooc_extra_register_fields() {?>
       <p class="form-row form-row-wide">
       <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
       <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
       </p>
       <p class="form-row form-row-first">
       <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
       </p>
       <p class="form-row form-row-last">
       <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
       </p>
       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );


// 驗證註冊欄位：名字，姓氏和電話號碼等
/**
* register fields Validating.
*/
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
      if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
             $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
      }
      if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
             $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
      }
         return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );



// 保存到數據庫中 註冊欄位：名字，姓氏和電話號碼等
/**
* Below code save extra fields.
*/
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
                 // Phone input filed which is used in WooCommerce
                 update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
          }
      if ( isset( $_POST['billing_first_name'] ) ) {
             //First name field which is by default
             update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
             // First name field which is used in WooCommerce
             update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
      }
      if ( isset( $_POST['billing_last_name'] ) ) {
             // Last name field which is by default
             update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
             // Last name field which is used in WooCommerce
             update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
      }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


 // 自動更新購物車
function cart_update_qty_script() {
    // 確認是購物車頁面，並且 WooCommerce 已啟用
    if (function_exists('is_cart') && is_cart()) : 
    ?>
        <script>
            jQuery(document).ready(function($) {
                let debounceTimer;
                $('div.woocommerce').on('change', '.qty', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        const updateButton = $("[name='update_cart']");
                        if (updateButton.length > 0) {
                            updateButton.removeAttr('disabled');
                            updateButton.trigger("click");
                        } else {
                            console.error("更新按鈕未找到！");
                        }
                    }, 500);
                });
            });
        </script>
    <?php
    endif;
}
if (class_exists('WooCommerce')) {
    add_action('wp_footer', 'cart_update_qty_script');
}


//文字載入
function add_google_fonts() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Serif+TC:wght@200..900&display=swap', false);
}
add_action('wp_enqueue_scripts', 'add_google_fonts');


