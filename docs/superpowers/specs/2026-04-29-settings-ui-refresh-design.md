# Settings UI Refresh Design

Date: 2026-04-29
Plugin: `event-tickets-manager-for-woocommerce`
Scope: Shared admin settings and tabs UI, plus plugin-wide admin button color normalization

## Goal

Move the plugin admin settings experience closer to the provided reference by improving heading scale, spacing, card softness, tab presentation, and sidebar hierarchy across shared settings screens.

Normalize plugin admin buttons to a black action system throughout the plugin so primary actions no longer appear blue on some screens.

## Approved Direction

Use the balanced screenshot-match direction:

- Shared settings and tab screens should feel closer to the reference without becoming a one-off redesign.
- The change must apply through shared layout and shared admin CSS where possible.
- Buttons throughout the plugin admin should use a black visual system instead of blue.

## Architecture

This work should stay in the existing admin UI system rather than adding per-tab patches.

Primary shared layers:

- `admin/css/event-tickets-manager-for-woocommerce-admin-ui.css`
  - Owns shared settings shell, hero, intro cards, tabs, sidebar cards, and shared button styles used by the newer admin layout.
- `admin/ui/layouts/class-event-tickets-manager-for-woocommerce-admin-layout.php`
  - Renders the shared hero, intro cards, tabs, page grid, and sidebar wrappers.
- Existing plugin admin CSS/markup outside the shared layout
  - Needs targeted button normalization so older admin screens also adopt the black button system.

The design goal is reuse first:

- Shared typography and layout adjustments belong in the shared admin UI stylesheet.
- Black button styling should be applied through shared selectors and targeted plugin-admin selectors, not by editing individual buttons one by one unless a screen is structurally different.

## Components Affected

### 1. Shared Header and Intro Cards

Update the shared typography and spacing for:

- top hero heading
- intro card eyebrow
- intro card title
- intro card description

Expected result:

- headings visibly larger and closer to the screenshot hierarchy
- more breathing room in hero and intro cards
- copy remains readable without becoming oversized

### 2. Shared Tabs Row

Adjust spacing and rhythm for the tab track:

- cleaner horizontal spacing
- lighter visual weight
- active underline still present, but aligned with the updated scale

Expected result:

- tabs feel less cramped and more deliberate
- active state remains easy to scan

### 3. Shared Cards and Sidebar

Update:

- shared card padding
- card radius/shadow balance
- sidebar heading sizes
- sidebar resource row spacing

Expected result:

- main content and sidebar feel part of the same visual system
- sidebar headings no longer feel undersized relative to main content

### 4. Plugin-Wide Button System

Normalize plugin admin buttons to black:

- black background
- white text
- readable hover/focus/active states
- preserve accessibility contrast

This includes:

- shared UI buttons in the newer settings layout
- plugin-specific admin buttons still styled blue on older settings or integration screens

When a button is structurally secondary but still rendered as a prominent action in plugin admin, it should follow the black system unless there is a strong existing reason to keep it neutral.

## Data Flow and Behavior

No business logic, setting storage, or request flow should change.

This is a presentation-layer change only:

- PHP renderers continue producing the same settings fields and actions
- existing form submissions continue unchanged
- existing button actions continue unchanged
- only visual styling and shared layout presentation are modified

## Error Handling and Risk Control

Main risks:

- globally broad button selectors could unintentionally style WordPress core buttons outside plugin screens
- typography changes could make a few dense screens wrap awkwardly
- older plugin admin screens may use different button classes than the shared layout

Controls:

- scope CSS to plugin admin pages/selectors only
- prefer existing plugin wrapper classes before generic `.button` overrides
- verify dense settings screens, especially Integrations and PDF/Ticket screens
- keep line-height and spacing resilient for long labels and descriptions

## Testing Plan

Visual verification on plugin admin screens:

- General
- Ticket Settings
- PDF Ticket Layout
- Other Settings
- Dashboard Settings
- Integrations
- System Status
- any older plugin admin screen still using blue action buttons

Check:

- heading hierarchy matches the approved balanced direction
- sidebar headings and resources feel closer to the reference
- tabs have improved spacing and active-state alignment
- all plugin admin buttons appear black with readable text
- hover/focus states remain visible
- no obvious spillover into non-plugin WordPress admin UI

## Out of Scope

- changing plugin business logic
- changing settings structure or field behavior
- redesigning WordPress core admin outside this plugin
- adding new tabs, actions, or content

## Implementation Notes

- Prefer editing shared CSS first, then add narrowly scoped legacy admin button selectors where needed.
- Avoid per-page CSS overrides unless a screen cannot be aligned through shared styles.
- Preserve current markup unless a small shared-layout class addition meaningfully improves selector quality.
