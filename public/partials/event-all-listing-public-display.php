<?php

?>

    <div class="wps-search-input">
    <input type="text" name="s" id="wps-search-event" placeholder="Search event by alphabet" />
    <select name="wps_select_event_listing_type" id="wps_select_type_main">
    <option value="wps_list">List</option>
    <option value="wps_card">Card</option>
    </select>

    </div>

    <div id="wps-search-results"></div>
    <div id="wps-product-loader">
    <img id="wps-loader" src="<?php echo esc_url(EVENT_TICKETS_MANAGER_FOR_WOOCOMMERCE_DIR_URL); ?>public/src/image/loading.gif" alt="Loading..." class="loader">
    </div>

