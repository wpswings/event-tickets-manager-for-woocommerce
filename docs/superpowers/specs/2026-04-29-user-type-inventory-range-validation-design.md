# User Type Inventory Range Validation Design

Date: 2026-04-29
Plugin: `event-tickets-manager-for-woocommerce`
Scope: Product edit screen validation for user-type pricing inventory min/max values

## Goal

Prevent invalid user-type pricing rows from being saved when `Inventory Min` is greater than `Inventory Max`.

The validation must work in two layers:

- admin-side JavaScript for immediate feedback on the product edit screen
- PHP save validation so invalid data cannot be persisted even if JavaScript is bypassed

## Approved Behavior

For each submitted user-type pricing row:

- if both inventory values are present and `Inventory Min > Inventory Max`, the row is invalid
- invalid rows must block product save
- the entered values should remain visible so the admin can correct them
- blank `Inventory Max` should not trigger the comparison
- equal values such as `min = 1`, `max = 1` are valid

## Architecture

This bug lives in two connected surfaces:

- backend save flow in `admin/class-event-tickets-manager-for-woocommerce-admin.php`
- product edit UI behavior in `admin/src/js/event-tickets-manager-for-woocommerce-edit-product.js`

The save flow currently maps `etmfwppp_fields` directly into the product meta payload without validating the min/max relationship. The product edit JavaScript currently handles dynamic row creation but does not validate the min/max pair before product submission.

The fix should keep the validation rule narrow and local to the user-type pricing table instead of adding broad product-level restrictions.

## Components Affected

### 1. PHP Save Validation

Add a reusable validation step before the plugin writes `wps_etmfw_product_array`.

Expected behavior:

- inspect each submitted `etmfwppp_fields` row
- compare `_inventory_min` and `_inventory_max` only when both are present
- reject the save payload if any row has `min > max`
- add an admin error notice explaining that inventory minimum cannot be greater than inventory maximum

The validation should avoid corrupting unrelated product fields. If the inventory rule fails, user-type pricing should not be saved with invalid values.

### 2. Product Edit Screen Validation

Add product edit JavaScript validation for both existing rows and dynamically added rows.

Expected behavior:

- validate all `.wps_etmfwpp_user_field_wrap` rows before form submission
- block submit when any row has `min > max`
- show a visible error near the pricing table
- visually mark the invalid row or fields
- clear the error once the row becomes valid

### 3. Regression Coverage

If the lightweight `tests/` regression pattern can cover the new PHP validation helper cleanly, add a narrow regression check for the validation contract.

If a full behavioral test is not practical in the current test setup, keep the PHP validation logic isolated enough to support future targeted regression coverage.

## Data Flow

Input path:

- admin product form posts `etmfwppp_fields`
- PHP sanitizes those values
- plugin builds `wps_etmfw_product_array`
- plugin writes the array with `update_post_meta`

New validation point:

- validate the sanitized `etmfwppp_fields` rows before the array is finalized and saved

UI path:

- product edit page renders existing rows from stored data
- JS validates rows on submit and on relevant field changes

## Error Handling

Admin-side:

- show a clear message such as: `Inventory Min cannot be greater than Inventory Max.`
- keep the user on the edit screen
- keep entered values available for correction

Server-side:

- do not silently normalize invalid values
- do not swap min and max automatically
- reject invalid data explicitly

## Testing Plan

Manual verification:

- create or edit an event product
- enter `Inventory Min = 10` and `Inventory Max = 1`
- confirm the screen blocks submission and shows an error
- if JavaScript is bypassed, confirm PHP still rejects the invalid save
- confirm valid pairs still save:
  - `1 / 10`
  - `1 / 1`
  - `1 / blank`

Regression verification:

- run `php -l` on touched PHP files
- run targeted regression script if one is added
- inspect the product edit flow manually because this is an admin interaction bug

## Out of Scope

- changing the user-type pricing feature structure
- auto-correcting invalid inventory values
- changing frontend quantity behavior beyond protecting the saved admin data
