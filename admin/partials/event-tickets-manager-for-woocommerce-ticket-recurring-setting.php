<?php
// echo 'prince';

if ($_POST['submit'] && !empty($_POST['product_id']) && is_numeric($_POST['product_id'])) {

    $product_data = get_post_meta($_POST['product_id'], 'wps_etmfw_product_array', array());

    $product = wc_get_product($_POST['product_id']);

    // if ( $product instanceof WC_Product && $product->is_type( 'event_ticket_manager' ) ) {
    $event_id = 1711; // Replace with the actual event ID
    $recurring_type = 'daily'; // 'daily', 'weekly', or 'monthly'
    $end_date = $product_data[0]['event_end_date_time']; // Replace with the desired end date
    $start_date = $product_data[0]['event_start_date_time'];

    // echo $end_date;

    $timestamp = strtotime($end_date);

    if ($timestamp !== false) {
        $formattedDate = date("Y-m-d", $timestamp);
        echo $formattedDate;
    }

    $timestamp1 = strtotime($start_date);

    if ($timestamp1 !== false) {
        $formattedDate1 = date("Y-m-d", $timestamp1);
        echo $formattedDate1;
    }

    convert_to_recurring_event($event_id, $recurring_type, $formattedDate, $formattedDate1);
    // }
} else {

    $prod_categories = array(22); // Category IDs to filter products
    $product_args = array(
        'numberposts' => $limit,
        'post_status' => array('publish', 'pending', 'private', 'draft'),
        'post_type' => array('product', 'product_variation'),
        'orderby' => 'ID',
        'suppress_filters' => false,
        'order' => 'ASC',
        'offset' => 0
    );

    if (!empty($prod_categories)) {
        $product_args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $prod_categories,
                'operator' => 'IN',
            )
        );
    }

    $products = get_posts($product_args);

    foreach ($products as $product) {
        if (get_post_type($product->ID) == 'product') {
            // Delete the product
            $result = wp_delete_post($product->ID, true);

            foreach ($prod_categories as $category_id) {
                // Check if the category exists
                $category = get_term($category_id, 'product_cat');

                if ($category && !is_wp_error($category)) {
                    // Delete the category
                    $result = wp_delete_term($category_id, 'product_cat');

                    if ($result) {
                        echo "Category with ID $category_id has been deleted successfully.<br>";
                    } else {
                        echo "Failed to delete category with ID $category_id.<br>";
                    }
                } else {
                    echo "Category with ID $category_id does not exist.<br>";
                }
            }
        }
    }
}


function convert_to_recurring_event($event_id, $recurring_type, $end_date, $start_date)
{

    $product = wc_get_product($event_id);
    $thumbnail_id = get_post_thumbnail_id($event_id);
    $event_title = get_the_title($event_id);

    $current_date = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);

    while ($current_date <= $end_date_obj) {
        // Create a new event post for each recurring instance
        $new_event_id = wp_insert_post(array(
            'post_title' => $event_title,
            'post_type' => 'product',
            'post_status' => 'publish',
            // 'product_cat' => 'recurring of'.$event_title,
        ));

        // Calculate end date for the current instance based on recurring type
        $current_end_date = clone $current_date;
        if ($recurring_type === 'weekly') {
            $current_end_date->modify('+6 days');
        } elseif ($recurring_type === 'monthly') {
            $current_end_date->modify('last day of this month');
        }

        // Define the array data for your recurring product (replace with your actual data)
        $recurring_product_data = array(
            'etmfw_event_price' => $product->get_price(),
            'event_start_date_time' => $current_date->format('Y-m-d H:i:s'),
            'wps_etmfw_field_user_type_price_data_baseprice' => 'base_price',
            'event_end_date_time' => $current_end_date->format('Y-m-d H:i:s'),
            'etmfw_event_venue' => 'Delhi',
            'etmfw_event_venue_lat' => 28.7040592,
            'etmfw_event_venue_lng' => 77.10249019999999,
            'etmfw_event_trash_event' => 'no',
            'etmfw_event_disable_shipping' => 'no',
            'wps_etmfw_dyn_name' => '',
            'wps_etmfw_dyn_mail' => '',
            'wps_etmfw_dyn_contact' => '',
            'wps_etmfw_dyn_date' => '',
            'wps_etmfw_dyn_address' => '',
            'wps_etmfw_field_user_type_price_data' => array(),
            'wps_etmfw_field_days_price_data' => array(),
            'wps_etmfw_field_stock_price_data' => array(),
            'wps_etmfw_field_data' => array(),
            'etmfw_display_map' => 'no',
            'etmfwp_share_on_fb' => 'no',
            'etmfwp_recurring_event_enable' => 'no',
        );

        // Assuming $product_id contains the ID of the created recurring product
        if ($new_event_id) {
            // Save the array as post meta for the product
            wp_set_object_terms($new_event_id, 'event_ticket_manager', 'product_type');
            update_post_meta($new_event_id, 'wps_etmfw_product_array', $recurring_product_data);
            update_post_meta($new_event_id, '_price', $product->get_price());
            update_post_meta($new_event_id, '_featured', 'yes');
            update_post_meta($new_event_id, '_stock', $product->get_stock_status());
            update_post_meta($new_event_id, '_stock_status', 'instock');
            update_post_meta($new_event_id, '_sku', $product->get_sku());
            update_post_meta($new_event_id, '_thumbnail_id', $thumbnail_id);
            update_post_meta($new_event_id, 'is_recurring', 'yes');

            $new_category_name = 'Recurring Event Of ' . $event_title; // Replace with your desired category name
            $new_category_slug = sanitize_title($new_category_name);

            // // Check if the category already exists
            // $existing_category = get_term_by('slug', $new_category_slug, 'product_cat');

            // if (!$existing_category) {
            $category_args = array(
                'cat_name' => $new_category_name,
                'category_nicename' => $new_category_slug,
                'category_parent' => '',
            );

            wp_set_post_terms($new_event_id, 20, 'product_cat');
        }
        // Move to the next recurring date
        if ($recurring_type === 'daily') {
            $current_date->modify('+2 day');
        } elseif ($recurring_type === 'weekly') {
            $current_date->modify('+1 week');
        } elseif ($recurring_type === 'monthly') {
            $current_date->modify('+1 month');
        }
    }
}

$args = array(
    'post_type' => 'product', // Change to the appropriate post type (e.g., 'product')
    'posts_per_page' => -1,  // Retrieve all posts
    'meta_query' => array(
        array(
            'key' => 'parent_of_recurring', // Replace with your custom field name
            'value' => '1711',            // Specify the value you're looking for
            'compare' => '=',             // Match the exact value
            'type' => 'NUMERIC',          // Assuming the value is numeric
        ),
    ),
);

$products_query = new WP_Query($args);

if ($products_query->have_posts()) {
    while ($products_query->have_posts()) {
        $products_query->the_post();
        // Output or manipulate the product information as needed
        the_title().'<br>'; // Display the title of the product

        $post_id = get_the_ID();
        // Delete the post
        // wp_delete_post($post_id, true); // Set the second parameter to true to force deletion

        // Output or manipulate the product information as needed
        // echo 'Deleted product: ' . get_the_title() . '<br>';
    }
    wp_reset_postdata();
} else {
    // No products found with the specified custom field value
    echo 'No products found.';
}
?>
<form method="post">
    <label>Product ID :</label><input type="number" value="" name="product_id" />
    <input type="submit" value="Submit" name="submit" />
</form>