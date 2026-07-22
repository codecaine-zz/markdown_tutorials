# SimpleGUI API Documentation

SimpleGUI is a beginner-friendly framework for building native macOS Cocoa applications in V. It combines a lightweight V-side API with a native bridge for real macOS windows and controls, so you can create polished desktop apps with very little boilerplate.

This guide is organized for fast scanning on GitHub and for quick reference while coding. The most common patterns are grouped first, followed by detailed API entries for individual controls and window actions.

## Table of contents

- [1. Window operations](#1-window-operations)
- [2. Control layout and grid rows](#2-control-layout--grid-rows)
- [3. Adding controls](#3-adding-controls)
- [4. Control sizing and styling](#4-control-sizing--styling)
- [5. Dialogs, popups, and file pickers](#5-dialogs-popups--file-pickers)
- [6. Utilities and system actions](#6-utilities--system-actions)
- [7. RAD Visual UI Designer & Code Generator API](#7-rad-visual-ui-designer--code-generator-api)

## Quick start

```v
module main

import simplegui

fn main() {
    simplegui.new_simple_window('Starter', 640, 420)
        .add_input('name', 'Ada')
        .add_button('save', 'Save')
        .on_click('save', fn (mut win &simplegui.SimpleWindow) {
            println('saved: ${win.get_text('name')}')
        })
        .run()
}
```

## Common patterns

- Create a window first with `new_simple_window(...)`.
- Give controls clear names such as `name`, `email`, `save`, or `status`.
- Prefer fluent chaining for compact, readable code like `.width(150).tooltip('Helpful text')`.
- Use `on_click(...)`, `on_change(...)`, and `on_enter(...)` to wire behavior without extra boilerplate.

---

## 1. Window Operations

### Quick Start

```v
module main

import simplegui

fn main() {
    simplegui.new_simple_window('Starter', 640, 420)
        .add_input('name', 'Ada')
        .add_button('save', 'Save')
        .on_click('save', fn (mut win &simplegui.SimpleWindow) {
            println("saved: ${win.get_text('name')}")
        })
        .run()
}
```

### Developer Helpers

- `win.has_control(name string) bool` checks whether a named control exists.
- `win.list_controls() []string` returns the registered control names.
- `win.get_control_kind(name string) string` reports the control type.
- `win.require_control(name string) string` returns the control name if it exists, otherwise raises an explicit panic.
- `win.get_title() string` returns the current window title.
- `win.get_resizable() bool` returns whether window scale resizability is enabled.
- `win.get_minimizable() bool` returns whether window minimizability is enabled.
- `win.get_maximizable() bool` returns whether window maximizability is enabled.
- Missing control access now raises a clear panic so mistakes surface early.

### `new_simple_window(title string, width int, height int) &SimpleWindow`

Initializes a new macOS window delegate.

- **Parameters**:
  - `title`: The window title string.
  - `width`: Default initial width.
  - `height`: Default initial height.
- **Notes**: By default, the window automatically resizes its height/width to wrap snugly around the registered controls at startup.

### `win.set_debug_mode(enabled bool) &SimpleWindow`

Enables or disables visual debug logging in stdout and prints events to the window status footer.

### `win.get_debug_mode() bool`

Returns whether debug mode is currently enabled.

### `win.set_title(title string) &SimpleWindow`

Updates the window title bar text.

### `win.set_always_on_top(enabled bool) &SimpleWindow`

Keeps the window above other application windows while the app is running.

### `win.get_always_on_top() bool`

Returns whether the window is currently configured to stay on top.

### `win.set_background_color(hex_color string) &SimpleWindow`

Applies a background theme color to the window content view.

- **Format**: `#RRGGBB` or `#RGB`.

### `win.set_font_color(color string) &SimpleWindow`

Sets the default font text color for labels and form controls.

- **Values**: `'white'`, `'black'`, or hex format.

### `simplegui.list_themes() []string`

Returns all 17 built-in production theme preset names:

- **Apple Light**: Clean macOS Aqua system light canvas (`#ffffff` bg, `#1c1c1e` fg, `#007aff` accent).
- **Apple Dark**: Vibrant macOS Dark Mode surface (`#1c1c1e` bg, `#f2f2f7` fg, `#0a84ff` accent).
- **Midnight Space Gray**: Pro dark titanium space gray theme (`#161618` bg, `#ebebf5` fg, `#0a84ff` accent).
- **Apple Sunset**: Warm macOS Mojave twilight sunset hues (`#281a24` bg, `#fdf7f4` fg, `#ff6b00` accent).
- **Sonoma Emerald**: macOS Sonoma dark forest glass palette (`#0d1f18` bg, `#f0fdf4` fg, `#30d158` accent).
- **Ventura Amber**: macOS Ventura golden sunset dark hues (`#211815` bg, `#fff8f0` fg, `#ff9500` accent).
- **Soft Pastel**: Apple Studio warm soft light theme (`#faf6f0` bg, `#2d2b2a` fg, `#e07a5f` accent).
- **Catppuccin Mocha**: Soothing lavender catppuccin dark mode (`#1e1e2e` bg, `#cdd6f4` fg, `#cba6f7` accent).
- **Nord**: Arctic frost nord developer palette (`#2e3440` bg, `#eceff4` fg, `#88c0d0` accent).
- **Dracula**: High-contrast vampire purple palette (`#282a36` bg, `#f8f8f2` fg, `#bd93f9` accent).
- **Cyberpunk**: Neon glow dark contrast palette (`#0d0d15` bg, `#00f5d4` fg, `#ff007f` accent).
- **Solarized Light**: Precision engineered light palette (`#fdf6e3` bg, `#657b83` fg, `#268bd2` accent).
- **Solarized Dark**: Precision engineered dark palette (`#002b36` bg, `#839496` fg, `#2aa198` accent).
- **GitHub Dark**: Official GitHub dark interface palette (`#0d1117` bg, `#c9d1d9` fg, `#58a6ff` accent).
- **GitHub Light**: Clean GitHub light canvas palette (`#ffffff` bg, `#24292f` fg, `#0969da` accent).
- **Navy Blue**: Deep slate navy dark theme (`#0f172a` bg, `#f8fafc` fg, `#38bdf8` accent).
- **Forest Green**: Rich emerald green dark theme (`#14532d` bg, `#f0fdf4` fg, `#4ade80` accent).

### `simplegui.get_theme(theme_name string) Theme`

Retrieves a `Theme` struct configuration matching `theme_name`. Normalization allows flexible lookup (case-insensitive, space/hyphen/underscore tolerant). Unknown names fall back to `Apple Light`.

- **`Theme` fields**: `name string`, `background_color string`, `font_color string`, `accent_color string`, `description string`, `is_dark bool`.
- **Aliases**: short forms work too, e.g. `'light'` → Apple Light, `'dark'` → Apple Dark, `'nord'`, `'dracula'`, `'catppuccin'`.

### `win.apply_theme(t Theme) &SimpleWindow`

Applies a `Theme` struct configuration directly to the window and controls. Accepts custom `Theme` values, so you can define your own palettes.

### `win.set_theme(theme_name string) &SimpleWindow`

Looks up a built-in production theme by name (or alias) and applies its background and font styling to the window and controls.

- **Values**: Accepts any built-in theme name (e.g. `'Apple Light'`, `'Apple Dark'`, `'Midnight Space Gray'`, `'Apple Sunset'`, `'Sonoma Emerald'`, `'Ventura Amber'`, `'Soft Pastel'`, `'Catppuccin'`, `'Nord'`, `'Dracula'`, `'Cyberpunk'`, `'Solarized Light'`, `'Solarized Dark'`, `'GitHub Dark'`, `'GitHub Light'`, `'Navy Blue'`, `'Forest Green'`).
- **Control styling**: applying a theme restyles every control — buttons, dropdowns, text inputs, textareas, and date pickers derive their light/dark surface colors from the theme's background luminance, not from the macOS system appearance. A light theme therefore renders light controls even on a Mac running system Dark Mode (and vice versa).
- **Window appearance**: the window's `NSAppearance` (Aqua / Dark Aqua) is switched automatically to match the theme background, so native bezels, menus, and scrollers stay consistent.
- **Explicit overrides**: per-control colors set with `win.set_control_background_color()` / `win.set_control_font_color()` complement the theme — setting one property never resets the other. Applying a new theme restyles all controls, so re-apply per-control overrides after `set_theme()` when switching palettes at runtime (see [demos/form_color_theme_demo.v](demos/form_color_theme_demo.v)).

### `win.set_padding(padding int) &SimpleWindow`

Sets the window content margin padding.

### `win.get_padding() int`

Gets the current window content margin padding.

### `win.set_spacing(spacing int) &SimpleWindow`

Sets the vertical spacing between stacked controls.

### `win.get_spacing() int`

Gets the current vertical spacing between stacked controls.

### `win.set_responsive_layout(enabled bool) &SimpleWindow`

Enables or disables responsive auto-layout so controls grow and shrink with the window.

### `win.get_responsive_layout() bool`

Returns whether responsive auto-layout is currently enabled.

### `win.set_min_size(width int, height int) &SimpleWindow`

Sets the minimum allowed width and height limits for the window resize action.

### `win.set_max_size(width int, height int) &SimpleWindow`

Sets the maximum allowed width and height limits for the window resize action.

### `win.set_resizable(enabled bool) &SimpleWindow`

Enables or disables window resizability using the dragging border/corners.

### `win.set_minimizable(enabled bool) &SimpleWindow`

Enables or disables the native minimize window titlebar button.

### `win.set_maximizable(enabled bool) &SimpleWindow`

Enables or disables the native zoom/maximize window titlebar button.

### `win.close()` / `win.close_window()` &SimpleWindow

Programmatically closes the native window delegate.

### `win.hide()` / `win.hide_window()` &SimpleWindow

Temporarily hides the window from view.

### `win.center()` / `win.center_window()` &SimpleWindow

Centers the window on the active display.

### `win.align(position string)` / `win.align_window(position string)` &SimpleWindow

Repositions the window relative to the active display/screen visible frame.
Supports flexible, case-insensitive placement names (e.g., `'top-left'`, `'top-center'`, `'top-right'`, `'middle-left'`, `'center'` or `'middle-center'`, `'middle-right'`, `'bottom-left'`, `'bottom-center'`, `'bottom-right'`).

### `win.set_size(width int, height int)` / `win.resize(width int, height int)` &SimpleWindow

Programmatically resizes the active window content area.

### `win.get_width() int` / `win.get_height() int`

Gets the current width/height of the window.

### `win.set_position(x int, y int)` &SimpleWindow

Repositions the top-left corner of the window on the desktop.

### `win.get_x() int` / `win.get_y() int`

Gets the current screen coordinates of the window position.

### `win.set_opacity(opacity f64)` &SimpleWindow

Applies window transparency / alpha opacity channel (range `0.0` to `1.0`).

### `win.get_opacity() f64`

Gets the current window translucency.

### `win.set_titlebar_visible(visible bool)` &SimpleWindow

Toggles titlebar visibility for custom clean-bordered or borderless overlay look.

### `win.set_cursor(cursor_name string)` &SimpleWindow / `win.get_cursor() string`

Changes the window-wide mouse cursor icon for the app window. Common names include `'arrow'`, `'ibeam'`, `'crosshair'`, `'pointing_hand'`, `'open_hand'`, `'closed_hand'`, and the resize variants such as `'resize_left'`, `'resize_right'`, `'resize_left_right'`, `'resize_up'`, `'resize_down'`, and `'resize_up_down'`.

### `win.set_cursor_size(scale f64)` &SimpleWindow / `win.get_cursor_size() f64`

Scales the active cursor image. Use `1.0` for the system size, `2.0` for double-size, and so on. Values are clamped to the safe range `0.25`–`8.0`.

### `win.reset_cursor()` &SimpleWindow

Restores the default arrow cursor and clears any custom cursor size or window-wide cursor override.

### `win.push_cursor(cursor_name string)` &SimpleWindow / `win.pop_cursor()` &SimpleWindow

Temporarily pushes a cursor onto the cursor stack and later restores the previous cursor with `pop_cursor()`.

### `win.set_control_cursor(control_name string, cursor_name string)` &SimpleWindow

Assigns a cursor that is used while the mouse hovers over a specific named control. Pass `'default'` or an empty string to remove the override.

### `win.get_mouse_location() (int, int)`

Returns the current global mouse location in screen coordinates.

### `win.move_cursor_to(x int, y int)` &SimpleWindow

Warps the mouse cursor to a new global screen position.

### `win.toggle_fullscreen()` &SimpleWindow

Toggles native macOS full screen mode programmatically.

### `win.minimize()` &SimpleWindow

Minimizes the window to the dock.

### `win.deminimize()` &SimpleWindow

Restores the window from the dock.

### `win.maximize()` / `win.zoom()` &SimpleWindow

Toggles native maximized/zoomed window scale.

### `win.is_minimized() bool` / `win.is_maximized() bool` / `win.is_fullscreen() bool`

Queries the active window states to check if it's minimized, maximized, or in full-screen mode.

### `win.is_active() bool`

Returns whether simplegui's window is currently the key focused window on the desktop.

### `win.request_attention(critical bool)` / `win.bounce_dock(critical bool)` &SimpleWindow

Bounces the application icon in the macOS Dock to catch the user's attention. If `critical` is true, the icon bounces repeatedly until the application is activated; otherwise, it bounces once.

### `win.set_closable(enabled bool)` &SimpleWindow / `win.get_closable() bool`

Toggles and queries whether the window has a close button and can be closed by the user.

### `win.set_has_shadow(enabled bool)` &SimpleWindow / `win.get_has_shadow() bool`

Toggles and queries whether the window casts a desktop shadow.

### `win.set_movable_by_window_background(enabled bool)` &SimpleWindow / `win.get_movable_by_window_background() bool`

Toggles and queries whether the user can click and drag anywhere in the window background area to move the window (useful for customized borderless layouts).

### `win.is_visible() bool`

Returns whether the window is currently visible on screen.

### `win.set_title_visible(visible bool)` &SimpleWindow / `win.get_title_visible() bool` / `win.is_title_visible() bool`

Toggles and queries the visibility of the window title text in the titlebar, without hiding the titlebar itself or traffic light controls.

### `win.is_titlebar_visible() bool`

Returns whether the window's titlebar is currently visible (i.e. not hidden via titlebar visibility settings).

### `win.set_subtitle(subtitle string)` &SimpleWindow / `win.get_subtitle() string`

Sets or retrieves the window subtitle text displayed in the macOS titlebar (macOS 11.0+).

### `win.set_titlebar_appears_transparent(transparent bool)` &SimpleWindow / `win.get_titlebar_appears_transparent() bool`

Toggles or queries translucent/transparent titlebar background styling.

### `win.set_full_size_content_view(enabled bool)` &SimpleWindow / `win.get_full_size_content_view() bool`

Toggles or queries whether the window content view extends under the titlebar area.

### `win.set_movable(enabled bool)` &SimpleWindow / `win.get_movable() bool`

Enables, disables, or queries whether the window can be moved by dragging.

### `win.set_window_level(level string)` &SimpleWindow

Sets the window layer stacking level (`'normal'`, `'floating'`, `'modal'`, `'mainMenu'`, `'statusBar'`, `'screenSaver'`).

### `win.set_aspect_ratio(width_ratio f64, height_ratio f64)` &SimpleWindow / `win.reset_aspect_ratio()` &SimpleWindow

Locks or resets window resizing constraints to a fixed aspect ratio.

### `win.bounce_dock_icon(critical bool)` &SimpleWindow

Triggers an attention bounce request on the application Dock icon (`critical` bounces continuously until activated).

### `win.set_fullscreen(enabled bool)` &SimpleWindow

Programmatically enables or disables full screen mode.

### `win.center_on_active_screen()` &SimpleWindow

Centers the window on the active display currently containing the mouse cursor.

### `win.snap_to_edge(edge string)` &SimpleWindow

Snaps the window frame to screen boundary positions (`'top_left'`, `'top_right'`, `'bottom_left'`, `'bottom_right'`, `'top'`, `'bottom'`, `'left'`, `'right'`, `'center'`).

### `win.set_bounds(x int, y int, width int, height int)` &SimpleWindow / `win.get_bounds() (int, int, int, int)`

Sets or retrieves the window x, y position and width, height bounds as a 4-tuple `(x, y, w, h)`.

### `win.has_aspect_ratio() bool`

Queries whether a fixed aspect ratio constraint is currently enforced on window resizing.

### `win.set_vibrancy(material string)` &SimpleWindow

Applies macOS translucent background vibrancy material (`'sidebar'`, `'header'`, `'titlebar'`, `'menu'`, `'hud'`, `'window'`).

### `win.set_corner_radius(radius f64)` &SimpleWindow / `win.get_corner_radius() f64`

Sets or retrieves the window corner rounding radius.

### `win.set_background_blur(enabled bool)` &SimpleWindow

Enables or disables desktop background blur effect behind the window.

### `win.get_window_level() string` / `win.set_level_type(level_type string)` &SimpleWindow

Queries or sets the window z-level layer tier (`'normal'`, `'floating'`, `'modal'`, `'mainMenu'`, `'statusBar'`, `'screenSaver'`).

### `win.set_ignores_mouse_events(enabled bool)` &SimpleWindow / `win.get_ignores_mouse_events() bool`

Toggles or queries click-through mode where mouse clicks pass through the window to underlying desktop applications.

### `win.set_hides_on_deactivate(enabled bool)` &SimpleWindow / `win.get_hides_on_deactivate() bool`

Toggles or queries whether the window automatically hides when the application loses focus.

### `win.set_prevents_app_termination(enabled bool)` &SimpleWindow / `win.get_prevents_app_termination() bool`

Controls whether closing this window prevents application process termination.

### `win.set_represented_filename(filepath string)` &SimpleWindow / `win.get_represented_filename() string`

Associates a file path with the window, displaying the native document proxy icon in the titlebar.

### `win.set_document_edited(edited bool)` &SimpleWindow / `win.is_document_edited() bool`

Displays or queries the unsaved changes dirty dot indicator inside the window close button.

### `win.flash_frame(critical bool)` &SimpleWindow

Flashes the window frame/titlebar to catch user attention.

### `win.fade_in(duration_ms int)` &SimpleWindow / `win.fade_out(duration_ms int)` &SimpleWindow

Animates window opacity smoothly in or out over the specified duration in milliseconds.

### `win.order_front()` / `win.bring_to_front()` &SimpleWindow

Brings the window to the top of the desktop window stack and activates the app.

### `win.order_back()` / `win.send_to_back()` &SimpleWindow

Sends the window behind all other open application windows.

### `win.toggle_minimize()` / `win.toggle_maximize()` / `win.toggle_visibility()` &SimpleWindow

Convenience toggles for window minimized, maximized, and visibility states.

### `win.move_by(dx int, dy int)` &SimpleWindow / `win.resize_by(dw int, dh int)` &SimpleWindow

Shifts the window position or adjusts window size by relative deltas.

### `win.get_center() (int, int)` / `win.set_center(center_x int, center_y int)` &SimpleWindow

Gets or sets the window center point in global screen coordinates.

### `win.center_horizontally()` &SimpleWindow / `win.center_vertically()` &SimpleWindow

Centers the window on the active screen along one axis while preserving the other axis.

### `win.fit_to_screen()` &SimpleWindow / `win.constrain_to_screen()` &SimpleWindow

Fits the window to the visible screen frame, or clamps existing bounds to keep it fully on-screen.

### `win.set_alpha(alpha f64)` &SimpleWindow / `win.get_alpha() f64`

Sets or retrieves the window transparency level (range `0.0` transparent to `1.0` opaque).

### `win.set_min_size(width int, height int)` &SimpleWindow / `win.get_min_size() (int, int)`

Enforces or queries minimum allowed window width and height resize constraints.

### `win.set_max_size(width int, height int)` &SimpleWindow / `win.get_max_size() (int, int)`

Enforces or queries maximum allowed window width and height resize constraints.

### `win.set_collection_behavior(behavior string)` &SimpleWindow

Configures macOS virtual desktop / Spaces behavior (`'can_join_all_spaces'`, `'move_to_active_space'`, `'transient'`, `'full_screen_primary'`, `'full_screen_auxiliary'`).

### `win.set_close_button_enabled(enabled bool)` &SimpleWindow / `win.set_minimize_button_enabled(enabled bool)` &SimpleWindow / `win.set_zoom_button_enabled(enabled bool)` &SimpleWindow

Enables or disables standard macOS titlebar traffic light control buttons (Close, Minimize, Zoom).

### `win.shake_window()` &SimpleWindow

Triggers an animated horizontal window shake feedback effect (ideal for error or validation failure indication).

### Ergonomic Window Shortcuts

- **`win.make_frameless()`**: Creates a clean borderless window with shadow (`set_titlebar_visible(false)` + `set_has_shadow(true)`).
- **`win.make_vibrant(material)`**: Configures window background vibrancy material and background blur filter.
- **`win.make_click_through(enabled)`**: Enables click-through overlay window behavior.
- **`win.make_always_on_top(enabled)`**: Configures stay-on-top window layering.
- **`win.make_modal()`**: Configures window z-level as modal window tier.
- **`win.make_panel()`**: Configures floating tool panel that hides on app deactivation.
- **`win.make_translucent(alpha)`**: Sets window opacity level (`set_alpha(alpha)`).
- **`win.make_sticky_space()`**: Configures window to stick across all virtual desktop Spaces (`set_collection_behavior('can_join_all_spaces')`).
- **`win.shake_on_error()`**: Triggers window error shake animation and flashes window frame.
- **`win.center_and_focus()`**: Centers window on active display and brings to front.

### `win.run()`

Launches the native NSApplication event loop and displays the centered window.

#### Keyboard Shortcuts (Global Window Actions)

- **`CMD + F`**: Toggles native full screen mode.
- **`CMD + Q`**: Quits the application immediately.

---

## 2. Control Layout & Grid Rows

By default, all controls stack vertically. You can group multiple controls side-by-side on the same horizontal row, or group them in layout containers:

### `win.begin_row(name string) &SimpleWindow`

Starts a horizontal layout container. Any subsequent widgets added will align horizontally.

### `win.end_row() &SimpleWindow`

Closes the active horizontal container. Subsequent controls return to vertical stacking.

### `win.begin_grid(name string, columns int, spacing int) &SimpleWindow`

Starts a CSS-like multi-column grid layout container with specified column count and item spacing. Automatically wraps controls across columns without manual row nesting.

### `win.end_grid() &SimpleWindow`

Closes the active multi-column grid layout container.

### `win.grid(name string, columns int, spacing int, callback VoidEventCallback) &SimpleWindow`

Closure-based grid layout container helper. Executes the callback closure and automatically ends the grid layout.

### `win.begin_flex_box(name string, direction string, justify string, align string) &SimpleWindow`

Starts a Flexbox layout container with specified `direction` (`'row'` | `'column'`), main-axis `justify` (`'start'` | `'center'` | `'end'` | `'space_between'` | `'space_around'` | `'fill'`), and cross-axis `align` (`'start'` | `'center'` | `'end'` | `'stretch'`).

### `win.end_flex_box() &SimpleWindow`

Closes the active flexbox container.

### `win.flex_box(name string, direction string, justify string, align string, callback VoidEventCallback) &SimpleWindow`

Closure-based flexbox container helper. Executes the callback closure and automatically ends the flexbox layout.

### `win.align_left() &SimpleWindow` / `win.align_center()` / `win.align_right()` / `win.align_top()` / `win.align_bottom()`

Fluent builder alignment modifiers for explicit control placement within containers. Attaches to the last created control.

### `win.expand_fill() &SimpleWindow`

Fluent builder modifier that configures the last created control to expand and fill available container space.

### `win.set_control_alignment(name string, alignment string) &SimpleWindow` / `win.get_control_alignment(name string) string`

Sets or retrieves explicit alignment (`'left'`, `'center'`, `'right'`, `'top'`, `'bottom'`) for a named control.

### `win.set_control_expand_fill(name string, expand bool) &SimpleWindow` / `win.get_control_expand_fill(name string) bool`

Enables or checks fill expansion configuration for a named control in its container.

### `win.add_action_row(actions map[string]VoidEventCallback) &SimpleWindow`

Lays out a set of buttons horizontally in a single call, binding each to its respective click event callback.

### `win.add_fields_row(fields map[string]string) &SimpleWindow`

Lays out a set of labeled input fields side-by-side. The map maps label text to input control name (e.g. `{"First Name": "fn"}`).

### `win.add_group_box(name string, title string) &SimpleWindow`

Adds a visual framed box container with an optional title label. Subsequent controls added will be nested inside this visual group.

### `win.add_tabs(name string, titles []string) &SimpleWindow`

Adds a tabbed container choice selector displaying the tab panes matching the provided `titles`.

### `win.add_scroll_view(name string, height int) &SimpleWindow`

Adds a scrollable layout viewport container with a fixed vertical height constraint.

### `win.row(name string, callback VoidEventCallback) &SimpleWindow`

Starts a horizontal row stack container, executes the callback closure passing the window reference, and automatically closes the horizontal container. Any widgets added inside the closure align horizontally.

### `win.group(name string, title string, callback VoidEventCallback) &SimpleWindow`

Starts a visual group box container, executes the callback closure passing the window reference, allowing nested layout code.

---

## 3. Adding Controls

Each control requires a `name` handle to get/set its value or listen to events. If you pass an empty string `""` as the `name`, a unique control name (e.g. `'auto_input_1'`) will be auto-generated under the hood.

### High-level form helpers

For common forms, these helpers reduce boilerplate and keep the API friendly for beginners:

- `win.add_form_field(label string, name string, value string) &SimpleWindow` creates a label plus input in a row.
- `win.add_form_textarea(label string, name string, value string) &SimpleWindow` creates a label plus textarea in a row.
- `win.add_form_password(label string, name string, value string) &SimpleWindow` creates a label plus password field in a row.
- `win.add_form_slider(label string, name string, value int) &SimpleWindow` creates a label plus slider in a row.
- `win.add_form_number(label string, name string, value int) &SimpleWindow` creates a label plus stepper number field in a row. While the field is focused, pressing Up/Down arrows increments/decrements the integer value by 1 by default.
- `win.add_form_dropdown(label string, name string, items []string, selected string) &SimpleWindow` creates a label plus dropdown selection in a row.
- `win.add_form_date_picker(label string, name string, date string) &SimpleWindow` creates a label plus date picker in a row.
- `win.add_form_progress(label string, name string, value int) &SimpleWindow` creates a label plus progress indicator in a row.
- `win.add_form_switch(label string, name string, switch_label string, checked bool) &SimpleWindow` creates a label plus switch toggle in a row.
- `win.add_form_link(label string, name string, link_text string, url string) &SimpleWindow` creates a label plus hyperlink text button in a row.
- `win.add_toggle(name string, label string, checked bool) &SimpleWindow` creates a checkbox.
- `win.add_number_field(name string, value int) &SimpleWindow` creates a numeric input.
- `win.add_action(name string, title string, callback VoidEventCallback) &SimpleWindow` creates a button and wires its click handler.
- `win.add_heading(title string) &SimpleWindow` inserts a large, prominent title label followed by a separator line.
- `win.add_breadcrumbs(name string, segments []string) &SimpleWindow` creates a breadcrumb/path navigator control.
- `win.set_breadcrumbs(name string, segments []string) &SimpleWindow` updates the visible breadcrumb segments.
- `win.add_shortcut_recorder(name string) &SimpleWindow` creates a shortcut capture field for keyboard combinations.
- `win.add_chart(name string, chart_type string, height int) &SimpleWindow` creates a line or area chart control.
- `win.set_chart_data(name string, values []f64) &SimpleWindow` updates the points shown in a chart.
- `win.add_circular_progress(name string, value int, min_val int, max_val int) &SimpleWindow` creates a circular progress gauge.
- `win.set_circular_progress(name string, value int) &SimpleWindow` updates the circular progress value.
- `win.add_property_grid(name string, props map[string]string) &SimpleWindow` creates a property inspector grid with key/value rows.
- `win.set_property_grid_value(name string, key string, value string) &SimpleWindow` updates a property grid entry.
- `win.add_color_grid(name string, colors []string) &SimpleWindow` creates a grid of selectable color swatches.
- `win.set_color_grid_selected(name string, color string) &SimpleWindow` selects a color swatch by hex value.
- `win.add_grid(name string, headers []string, initial_rows [][]string) &SimpleWindow` creates an editable spreadsheet-style grid.
- `win.grid_add_row(name string, row_values []string) &SimpleWindow`, `win.grid_delete_row(name string, row_idx int) &SimpleWindow`, `win.grid_add_column(name string, header string) &SimpleWindow`, and `win.grid_delete_column(name string, col_idx int) &SimpleWindow` manage grid rows and columns.
- `win.add_console(name string, height int) &SimpleWindow`, `win.append_console(name string, text string, level int) &SimpleWindow`, and `win.clear_console(name string) &SimpleWindow` create and manage a developer-style log console.
- `win.add_gauge(name string, title string, value int, min_val int, max_val int, unit string) &SimpleWindow`, `win.set_gauge_value(name string, value int) &SimpleWindow`, and `win.get_gauge_value(name string) int` create and manage a visual progress/level gauge.
- `win.add_pagination(name string, total_pages int, current_page int) &SimpleWindow`, `win.set_pagination_page(name string, page int, total_pages int) &SimpleWindow`, and `win.get_pagination_page(name string) int` create and manage a page navigation bar widget.
- `win.add_activity_feed(name string, height int) &SimpleWindow`, `win.add_activity_feed_item(name string, timestamp string, message string, level string) &SimpleWindow`, and `win.clear_activity_feed(name string) &SimpleWindow` create and manage an activity log feed view.
- `win.add_markdown_view(name string, markdown_text string, height int) &SimpleWindow`, `win.set_markdown_view_text(name string, markdown_text string) &SimpleWindow`, and `win.get_markdown_view_text(name string) string` render formatted Markdown text.
- `win.add_sparkline(name string, values []f64, height int) &SimpleWindow` and `win.set_sparkline_data(name string, values []f64) &SimpleWindow` create and update inline trend line charts.
- `win.add_pin_code(name string, digits int) &SimpleWindow`, `win.set_pin_code_value(name string, code string) &SimpleWindow`, and `win.get_pin_code_value(name string) string` create digit verification PIN/OTP input fields.
- `win.add_color_palette(name string, hex_colors []string, selected string) &SimpleWindow`, `win.set_color_palette_selected(name string, hex_color string) &SimpleWindow`, and `win.get_color_palette_selected(name string) string` create swatch color palette selectors.
- `win.add_timeline(name string, height int) &SimpleWindow` and `win.add_timeline_item(name string, title string, subtitle string, time_str string, status string) &SimpleWindow` create and manage vertical milestone event timelines.
- `win.add_metric_card(name string, title string, value string, change_badge string, subtitle string) &SimpleWindow` and `win.set_metric_card_value(name string, value string, change_badge string) &SimpleWindow` display KPI stats cards with trend badges.
- `win.add_tab_pills(name string, items []string, selected string) &SimpleWindow`, `win.set_tab_pills_active(name string, selected string) &SimpleWindow`, and `win.get_tab_pills_active(name string) string` create pill-styled segmented tab bars.
- `win.add_transfer_list(name string, available []string, selected []string) &SimpleWindow` and `win.add_transfer_list_opts(name string, available []string, selected []string, multi_select bool) &SimpleWindow` create a dual-column item transfer list picker with single-select or multi-select capabilities.

- `win.add_audio_waveform(name string, amplitudes []f64, height int) &SimpleWindow` and `win.set_audio_waveform_data(name string, amplitudes []f64) &SimpleWindow` render sound level amplitude waveforms.
- `win.add_rating_breakdown(name string, avg_score f64, total_reviews int, star_percentages []f64) &SimpleWindow` and `win.set_rating_breakdown_data(name string, avg_score f64, total_reviews int, star_percentages []f64) &SimpleWindow` display review rating scores and star percentage bars.
- `win.add_code_view(name string, lang string, code_text string, height int) &SimpleWindow`, `win.set_code_view_text(name string, code_text string) &SimpleWindow`, and `win.get_code_view_text(name string) string` render dark monospaced code snippet blocks.
- `win.add_alert_banner(name string, title string, message string, style string) &SimpleWindow` and `win.set_alert_banner_value(...)` create dismissible notification banners with status icons.
- `win.add_step_tracker(name string, steps []string, current_step int) &SimpleWindow`, `win.set_step_tracker_step(...)`, and `win.get_step_tracker_step(...)` create horizontal workflow process trackers.
- `win.add_filter_chips(name string, chips []string, selected []string, multi_select bool) &SimpleWindow`, `win.set_filter_chips_selected(...)`, and `win.get_filter_chips_selected(...)` create interactive filter chip tag groups.
- `win.add_file_picker_field(name string, initial_path string, button_title string, folder_only bool) &SimpleWindow`, `win.set_file_picker_path(...)`, and `win.get_file_picker_path(...)` create path input fields with native Cocoa `NSOpenPanel` file chooser dialog buttons.
- `win.add_radial_gauge(name string, title string, value f64, min_val f64, max_val f64, unit string) &SimpleWindow`, `win.set_radial_gauge_value(...)`, and `win.get_radial_gauge_value(...)` render semi-circular dial speedometer meters.
- `win.add_key_value_card(name string, title string, keys []string, values []string) &SimpleWindow` and `win.set_key_value_card_data(...)` render key-value summary cards.
- `win.add_form_from_struct[T](default_data T) &SimpleWindow` automatically generates input/checkbox/numeric fields side-by-side and vertically from a V struct using compile-time reflection.

- `win.configure(callback fn (mut cfg WindowConfig)) &SimpleWindow` applies a small fluent configuration block for window title, dimensions, spacing, colors, and resize behavior.
- `win.form(title string, callback VoidEventCallback) &SimpleWindow` and `win.section(title string, callback VoidEventCallback) &SimpleWindow` create grouped form containers with a lightweight builder feel.
- `win.validate_controls(validators map[string]ControlValidator) map[string]string` validates named controls and stores inline errors, while `simplegui.validate_not_empty(value string) string` provides a ready-made required-field validator.
- `win.validate_struct[T]() bool` automatically validates form controls against struct field attributes (e.g. `@[required]`, `@[min_len: X]`, `@[max_len: Y]`, `@[email]`, `@[url]`, `@[alphanumeric]`, `@[min: A]`, `@[max: B]`) and displays visual inline errors on the window. Returns `true` if valid.

### Nameless default control helpers

If your application only needs a single control of a specific type (or you do not want to manage control names), you can use nameless helpers which default to pre-configured keys (`'default_input'`, `'default_textarea'`, `'default_checkbox'`, `'default_number'`, `'default_button'`):

- `win.input(value string) &SimpleWindow`
- `win.set_input(value string) &SimpleWindow` / `win.get_input() string`
- `win.textarea(text string) &SimpleWindow`
- `win.set_textarea(text string) &SimpleWindow` / `win.get_textarea() string`
- `win.checkbox(title string, checked bool) &SimpleWindow`
- `win.set_checkbox(checked bool) &SimpleWindow` / `win.get_checkbox() bool`
- `win.number(value int) &SimpleWindow`
- `win.set_number(value int) &SimpleWindow` / `win.get_number() int`
- `win.button(title string) &SimpleWindow`
- `win.set_button(title string) &SimpleWindow`
- `win.dropdown(items []string, selected string) &SimpleWindow`
- `win.segmented(items []string, selected string) &SimpleWindow`
- `win.radio_group(items []string, selected string) &SimpleWindow`
- `win.toggle_switch(label string, checked bool) &SimpleWindow`
- `win.search_field(placeholder string) &SimpleWindow`
- `win.combo_box(items []string, selected string) &SimpleWindow`
- `win.rating(value int) &SimpleWindow`
- `win.spinner(active bool) &SimpleWindow`
- `win.path_control(path string) &SimpleWindow`
- `win.token_field(value string) &SimpleWindow`

### `win.add_label(name string, text string) &SimpleWindow`

Adds a read-only text description label.

### `win.add_input(name string, value string) &SimpleWindow`

Adds a single-line text input field.

### `win.add_password(name string, value string) &SimpleWindow`

Adds a secure password entry field.

### `win.add_textarea(name string, value string) &SimpleWindow`

Adds a scrollable, multi-line rich text area.

### `win.add_html_view(name string, html string) &SimpleWindow`

Adds a lightweight HTML preview panel using WebKit.

### `win.add_drop_zone(name string, label string) &SimpleWindow`

Adds a drag-and-drop target for file paths and other dropped content.

### `win.add_checkbox(name string, label string, checked bool) &SimpleWindow`

Adds a toggle checkbox.

### `win.add_button(name string, title string) &SimpleWindow`

Adds a clickable push button.

### `win.add_number(name string, value int) &SimpleWindow`

Adds a numeric input field bound to an increment/decrement stepper.

- **Keyboard behavior**: when focused, `Up` increases and `Down` decreases the value by the step size (default `1`).

### `win.add_slider(name string, value int) &SimpleWindow`

Adds a horizontal slider control (range `0` to `100`).

### `win.add_theme_menu(name string, selected string) &SimpleWindow`

Adds a standard popup dropdown menu selection for active theme (options: `Light`, `Dark`, `System`).

### `win.add_color_well(name string, color_hex string) &SimpleWindow`

Adds a native macOS color well block. Clicking it launches the macOS Color Picker.

### `win.add_date_picker(name string, date string) &SimpleWindow`

Adds a calendar date picker input (input format: `yyyy-mm-dd`).

### `win.add_mode_control(name string, selected string) &SimpleWindow`

Adds a segmented control choice selector (choices: `Simple`, `Advanced`, `Expert`).

### `win.add_progress_indicator(name string, value int) &SimpleWindow`

Adds a horizontal progress bar loader (range `0` to `100`).

### `win.add_list_box(name string, items []string) &SimpleWindow`

Adds a scrollable table list box control displaying the array items. Selection changes trigger change events.

### `win.add_image(name string, file_path string) &SimpleWindow`

Adds an image box displaying a local PNG or JPEG file. Custom widths/heights can resize it.

### `win.add_dropdown(name string, items []string, selected string) &SimpleWindow`

Adds a generic popup dropdown choice selector with custom `items`. The selected item can be got/set using `win.get_text()` or `win.set_text()`.

### `win.add_segmented_control(name string, items []string, selected string) &SimpleWindow`

Adds a generic segmented control choice selector containing custom `items`. Choice updates can be set/got via label strings (`win.get_text()`) or 0-indexed segment positions (`win.get_value_int()`).

### `win.add_radio_group(name string, items []string, selected string) &SimpleWindow`

Adds a vertical radio button group layout. Choice updates can be retrieved/set via label strings (`win.get_text()`) or 0-indexed positions (`win.get_value_int()`).

### `win.add_switch(name string, label string, checked bool) &SimpleWindow`

Adds a native horizontal toggle switch. Its active state can be got/set using `win.get_bool()` or `win.set_bool()`.

### `win.add_search_field(name string, placeholder string) &SimpleWindow`

Adds a native magnifying glass search bar textfield.

### `win.add_combo_box(name string, items []string, selected string) &SimpleWindow`

Adds an editable combobox dropdown choice selector containing custom `items`. Users can both type freeform choices and choose from suggestions list. The input text can be get/set using `win.get_text()` or `win.set_text()`.

### `win.add_level_indicator(name string, style int, min_val int, max_val int, value int) &SimpleWindow`

Adds a versatile native macOS level and capacity gauge indicator.

- **Styles**:
  - `0`: Relevancy indicator
  - `1`: Continuous capacity meter
  - `2`: Discrete capacity meter (ticks block)
  - `3`: Star Rating selector

### `win.add_rating(name string, value int) &SimpleWindow`

Convenient shorthand wrapper for `add_level_indicator` that creates an interactive 5-star rating control (min = 0, max = 5, style = 3). Users can click stars directly to change values, which triggers and registers change event callbacks.

### `win.add_spinner(name string, active bool) &SimpleWindow`

Adds an indeterminate activity loading spinner.

- **Parameters**:
  - `active`: If true, the spinner immediately visible and plays its spinning animation loop. If false, the animation stops and the control hides.
- **Toggling**: You can turn the animation on or off programmatically using `win.set_bool(name, true/false)`.

### `win.add_path_control(name string, path string) &SimpleWindow`

Adds a modern breadcrumb path control.

- **Features**: Displays folder tracks beautifully using standard macOS system icons. If `editable` is set to true (default), users can click on links or double-click to invoke standard file dialogues, or drag files direct into the field to populate it.
- **Accessing**: Retrieve or update path text directly using the standard `win.get_text(name)` vs `win.set_text(name, path)`.

### `win.add_token_field(name string, value string) &SimpleWindow`

Adds a token bubble tags editor input field.

- **Features**: Converts typical text phrases into tag chips or tag buttons when users press comma.
- **Reading**: Standard `win.get_text()` returns a clean comma-separated sequence.

### `win.add_stepper(name string, min_val int, max_val int, step int, value int) &SimpleWindow`

Adds a standalone native up/down arrow stepper (`NSStepper`) with a live value label beside it.

- **Parameters**: `min_val`/`max_val` bound the value, `step` is the increment per click (values <= 0 default to 1).
- **Accessing**: Use `win.get_value_int(name)` / `win.set_value_int(name, value)`; user clicks fire `change` events with the numeric value.
- **Range**: Adjust bounds later with `win.set_slider_range(name, min, max)` or the chainable `.range(min, max)`.

### `win.add_help_button(name string) &SimpleWindow`

Adds the round native macOS "?" help button (`NSBezelStyleHelpButton`). Attach behavior with `.onclick()` — typically opening documentation or a popover-style dialog.

### `win.add_knob(name string, value int) &SimpleWindow`

Adds a circular rotary slider knob (`NSSliderTypeCircular`) with a live value label.

- **Range**: Defaults to 0–100; chain `.range(min, max)` to customize.
- **Accessing**: Same numeric accessors and `change` events as a linear slider.

### `win.add_pull_down(name string, title string, items []string) &SimpleWindow`

Adds a native pull-down menu button (`NSPopUpButton` in pulls-down mode). Unlike `add_dropdown`, the button always displays `title` and acts as a compact action menu.

- **Events**: Choosing an item fires a `change` event whose value is the chosen item's text.

### `win.add_image_button(name string, symbol string, title string) &SimpleWindow`

Adds a push button decorated with a native SF Symbol image (macOS 11+), e.g. `'trash'`, `'gearshape'`, `'square.and.arrow.up'`.

- **Icon-only**: Pass an empty `title` for a compact icon-only button.
- **Events**: Fires standard `click` events (`.onclick()`).

### `win.add_stat_card(name string, title string, value string, trend string, trend_style string)` / `win.stat_card(title string, value string, trend string, trend_style string) &SimpleWindow`

Adds a dashboard metric stat card displaying an uppercase title, large metric value, and trend indicator (e.g. `+18.4%`).

- **Parameters**: `trend_style` accepts `"success"`, `"error"`, `"warning"`, or `"info"` for custom status coloring.
- **Updating**: Use `win.set_stat_card(name, value, trend, trend_style)` or `win.set_value(name, value)` to update live metrics programmatically.

### `win.add_banner(name string, text string, style string)` / `win.banner(text string, style string) &SimpleWindow`

Adds an alert message banner strip across the layout. Acceptable `style` values are `"info"`, `"success"`, `"warning"`, or `"error"`.

### `win.add_star_rating(name string, value int, max_stars int)` / `win.star_rating(value int, max_stars int) &SimpleWindow`

Adds an interactive star rating selector control (★ ★ ★ ★ ☆) with custom `max_stars`.

- **Parameters**: `value` sets initial rating; `max_stars` sets total star count (defaults to 5).
- **Accessing**: Use `win.get_star_rating_value(name)` / `win.set_star_rating_value(name, val)`.
- **Events**: Star clicks fire `change` events with the numeric rating string.

### `win.add_range_slider(name string, min_val int, max_val int, low_val int, high_val int)` / `win.range_slider(min_val int, max_val int, low_val int, high_val int) &SimpleWindow`

Adds a dual-thumb range selector slider widget for minimum and maximum boundaries.

- **Parameters**: `min_val` and `max_val` bound the slider scale; `low_val` and `high_val` specify current active range handles.
- **Accessing**: Use `win.get_range_slider_low(name)` / `win.get_range_slider_high(name)` / `win.set_range_slider_values(name, low, high)`.
- **Events**: Handle adjustments fire `change` events formatted as `"low:high"`.

### `win.add_split_button(name string, title string, menu_items []string)` / `win.split_button(title string, menu_items []string) &SimpleWindow`

Adds a primary action button paired with a secondary dropdown popup menu.

- **Parameters**: `title` sets main button text; `menu_items` specifies popup menu options.
- **Events**: Main button click fires `click` events (`.on_click()`). Menu item selection fires `"select_item"` (`.on_select_item()`) and `"change"` (`.on_change()`).

### `win.add_tag_cloud(name string, tags []string)` / `win.tag_cloud(tags []string) &SimpleWindow`

Adds an interactive tag chips list widget.

- **Parameters**: `tags` array contains active tag string labels.
- **Updating**: `win.set_tag_cloud_tags(name, tags)` updates the chip list.
- **Events**: Tag chip clicks fire `"click_tag"` (`.on_click_tag()`) events with the tag text.

### `win.add_wizard_stepper(name string, steps []string, current_step int)` / `win.wizard_stepper(steps []string, current_step int) &SimpleWindow`

Adds a multi-step process indicator bar showing active, completed, and pending step states.

- **Parameters**: `steps` list of step titles; `current_step` 0-based active step index.
- **Updating**: `win.set_wizard_stepper_step(name, step)` updates active step index.
- **Events**: Step clicks fire `"change_step"` (`.on_change_step()`) events with the new step index string.

Adds a styled notice banner callout box for contextual alert messages.

- **Parameters**: `style` accepts `"info"`, `"success"`, `"warning"`, or `"error"`.
- **Updating**: Update banner message text using `win.set_banner(name, text)` or `win.set_text(name, text)`.

### `win.add_section_header(name string, title string, subtitle string)` / `win.section_header(title string, subtitle string) &SimpleWindow`

Adds a section header layout widget featuring a bold section title, optional subtitle, and a full-width divider line.

### `win.add_vertical_slider(name string, value int, min_val int, max_val int, height int)` / `win.vertical_slider(value int, min_val int, max_val int, height int) &SimpleWindow`

Adds a standalone native vertical `NSSlider` control with a live numeric value indicator label.

- **Accessing**: Use `win.get_vertical_slider(name)` / `win.set_vertical_slider(name, value)` to read or update numeric slider values.
- **Events**: User interactions fire `change` events with the new integer value.

### `win.add_chip_group(name string, chips []string, selected string)` / `win.chip_group(chips []string, selected string) &SimpleWindow`

Adds a modern segmented tag/chip pill selection bar (`NSSegmentedControl`) for easy item or category selection.

- **Accessing**: Retrieve or update the active chip using `win.get_chip_selected(name)` / `win.set_chip_selected(name, chip)`.
- **Events**: Segment selection fires `change` events with the selected chip's text string.

### `win.add_badge(name string, text string, style string)` / `win.badge_pill(text string, style string) &SimpleWindow`

Adds a pill-shaped status badge label with styled background tint and text color.

- **Parameters**: `style` accepts `"success"`, `"error"`, `"warning"`, `"info"`, or `"neutral"`.
- **Accessing**: Use `win.get_badge(name)` and `win.set_badge(name, text, style)` to retrieve or update badge content dynamically.

### `win.add_icon_segments(name string, symbols []string, selected string)` / `win.icon_segments(symbols []string, selected string) &SimpleWindow`

Adds an SF Symbol-powered segmented button bar for switching modes or views with native icons.

### `win.add_status_indicator(name string, label string, status string)` / `win.status_indicator(label string, status string) &SimpleWindow`

Adds an LED status indicator light dot alongside a text title.

- **Parameters**: `status` accepts `"active"` / `"online"` (emerald green LED), `"warning"` / `"busy"` (orange LED), `"error"` / `"offline"` (red LED), or `"idle"` (gray LED).
- **Accessing**: Use `win.get_status_indicator(name)` and `win.set_status_indicator(name, status)` to read or update state dynamically.

### `win.add_metric_meter(name string, title string, value int, min_val int, max_val int, unit string)` / `win.metric_meter(title string, value int, min_val int, max_val int, unit string) &SimpleWindow`

Adds a resource meter card widget displaying a title, percentage fill bar, and right-aligned numeric reading (e.g. `48%` or `28 MB/s`).

- **Accessing**: Use `win.get_metric_meter(name)` and `win.set_metric_meter(name, value)` to read or update meter progress dynamically.

### `win.add_avatar_card(name string, title string, subtitle string, status string)` / `win.avatar_card(title string, subtitle string, status string) &SimpleWindow`

Adds a user/profile avatar tile widget featuring a round initial badge, title text, subtitle, and live status pill.

- **Accessing**: Use `win.set_avatar_card(name, title, subtitle, status)` to update operator profile cards dynamically.

### `win.add_time_picker(name string, time string)` / `win.time_picker(time string) &SimpleWindow`

Adds a standalone native Cocoa clock/time selector (`NSDatePicker` with hour/minute/second stepper).

- **Accessing**: Use `win.get_time_picker(name)` and `win.set_time_picker(name, time)` to read or update the selected time string (e.g. `"14:30:00"`).

### `win.add_tray_icon(name string, symbol string, title string)` / `win.tray_icon(symbol string, title string) &SimpleWindow`

Adds a macOS system menu bar status item / tray icon (`NSStatusItem`) in the top menu bar.

- **Accessing**: Use `win.set_tray_icon(name, symbol, title)` to update the status bar icon or title dynamically.

### `win.add_collapsible_section(name string, title string, expanded bool)` / `win.collapsible_section(title string, expanded bool) &SimpleWindow`

Adds a collapsible accordion container section header featuring an interactive disclosure triangle toggle.

- **Accessing**: Use `win.set_collapsible_section_expanded(name, expanded)` to programmatically expand or collapse the section header.

### `win.add_code_editor(name string, code string, height int)` / `win.code_editor(code string, height int) &SimpleWindow`

Adds an integrated dark-themed monospaced code editor container view.

- **Accessing**: Use `win.get_code_editor(name)` and `win.set_code_editor(name, code)` to read or update text source code dynamically.

### `win.add_timeline_view(name string, height int)` / `win.timeline_view(height int) &SimpleWindow`

Adds an activity feed timeline stream widget for displaying real-time event logs with colored status indicators.

- **Accessing**: Append event entries using `win.add_timeline_entry(name, time, title, detail, style)` or clear with `win.clear_timeline(name)`. `style` accepts `"success"`, `"warning"`, `"error"`, or `"info"`.

### `win.add_toolbar_item(name string, label string, tooltip string, symbol string)` & `win.on_toolbar_click(name, callback)`

Adds a native macOS titlebar `NSToolbar` button with an SF Symbol icon and label, and wires click event handling.

---

## 4. Control Sizing & Styling

Customize individual control dimensions and appearance by their registered name.

### `win.set_control_width(name string, width int) &SimpleWindow`

Overwrites the control's Auto Layout width constraint.

### `win.set_control_height(name string, height int) &SimpleWindow`

Overwrites the control's Auto Layout height constraint.

### `win.set_control_font_size(name string, size int) &SimpleWindow`

Changes the control's font size (handles labels, text fields, textareas, and buttons).

### `win.set_control_font_bold(name string, bold bool) &SimpleWindow`

Configures the control's font to Bold weight (supports labels, text fields, textareas, and buttons).

### `win.set_control_font_name(name string, font_name string) &SimpleWindow`

Sets a custom font family/name (e.g. `"Courier"`, `"Helvetica"`, or `"Arial"`) for the control. Falls back to system font if unavailable.

### `win.set_control_background_color(name string, hex_color string) &SimpleWindow`

Sets a custom background color for the individual control.

- **Notes**: The explicit color is remembered — a later `set_control_font_color()` call will not reset it. Applying a theme afterwards restyles the control with theme colors, so re-apply overrides after `set_theme()` if needed.

### `win.set_control_font_color(name string, hex_color string) &SimpleWindow`

Sets a custom text font color for the individual control.

- **Notes**: Setting only the font color preserves any explicitly set background color (and vice versa).

### `win.set_control_visible(name string, visible bool) &SimpleWindow`

Toggles whether the control is shown on screen.

- **Notes**: Hidden controls will automatically collapse within `NSStackView` layouts, dynamically shifting surrounding elements.

### `win.get_control_visible(name string) bool`

Checks if the control is currently visible.

### `win.set_control_enabled(name string, enabled bool) &SimpleWindow`

Enables/disables user interaction on the control. Disabled controls will render greyed out.

### `win.get_control_enabled(name string) bool`

Checks if the control is currently enabled.

### `win.get_control_background_color(name string) string`

Gets the custom background HEX color string of the specified control, or an empty string if none is set.

### `win.get_control_font_color(name string) string`

Gets the custom font HEX color string of the specified control, or an empty string if none is set.

### `win.get_control_width(name string) int`

Gets the custom layout width constraint of the specified control, or `0` if not explicitly constrained.

### `win.get_control_height(name string) int`

Gets the custom layout height constraint of the specified control, or `0` if not explicitly constrained.

### `win.get_control_font_size(name string) int`

Gets the custom font size of the specified control, or `0` if not explicitly configured.

### `win.set_placeholder(name string, text string) &SimpleWindow`

Sets placeholder text for text-based controls such as inputs and password fields.

### `win.set_error(name string, text string) &SimpleWindow`

Applies validation/error feedback to a control and highlights it visually.

### `win.clear_errors() &SimpleWindow`

Clears all active visual validation error states and error messages across all controls at once.

### `win.clear_error(name string) &SimpleWindow`

Clears the visual validation error state and error message for a specific named control.

### `win.get_error(name string) string`

Gets the validation error text currently associated with the specified control, or an empty string if there is no error.

### `win.set_tooltip(name string, text string) &SimpleWindow`

Sets a hover tooltip for any control.

### `win.set_default_button(name string) &SimpleWindow`

Marks a button as the default Enter-key action for the window.

### `win.set_html(name string, html string) &SimpleWindow`

Updates the content shown inside an HTML preview panel.

### Fluent Chaining Modifiers (Last-Control Helpers)

You can chain these modifiers directly onto control creation methods to style or customize the last created control without referencing its name/ID string:

- **`.width(w int) &SimpleWindow`**: Sets the last control's Auto Layout width constraint.
- **`.height(h int) &SimpleWindow`**: Sets the last control's Auto Layout height constraint.
- **`.font_size(size int) &SimpleWindow`**: Changes the last control's font text size.
- **`.bold(b bool) &SimpleWindow`**: Sets the last control's font weight to Bold.
- **`.font_name(font_name string) &SimpleWindow`**: Applies a custom font family (e.g. `"Courier"`) directly to the last control.
- **`.color(hex_color string) &SimpleWindow`**: Sets a custom background color for the last control.
- **`.font_color(hex_color string) &SimpleWindow`**: Sets a custom font color for the last control.
- **`.placeholder(text string) &SimpleWindow`**: Sets placeholder text for the last text-based control.
- **`.error(text string) &SimpleWindow`**: Highlights the last control with validation/error text.
- **`.tooltip(text string) &SimpleWindow`**: Attaches a hover tooltip to the last control.
- **`.visible(visible bool) &SimpleWindow`**: Toggles visibility of the last control.
- **`.enabled(enabled bool) &SimpleWindow`**: Enables or disables user interaction on the last control.
- **`.onclick(callback VoidEventCallback) &SimpleWindow`**: Attaches a click handler to the last created control.
- **`.onchange(callback StringEventCallback) &SimpleWindow`**: Attaches a change handler to the last created control.
- **`.onenter(callback VoidEventCallback) &SimpleWindow`**: Attaches an enter-key handler to the last created control.
- **`.onfocus(callback VoidEventCallback) &SimpleWindow`**: Attaches a focus handler to the last created control.
- **`.onblur(callback VoidEventCallback) &SimpleWindow`**: Attaches a blur handler to the last created control.
- **`.onhover(callback VoidEventCallback) &SimpleWindow`**: Attaches a hover-enter handler to the last created control.
- **`.onhover_exit(callback VoidEventCallback) &SimpleWindow`**: Attaches a hover-exit handler to the last created control.

---

## 5. Dialogs, Popups, & File Pickers

### `win.alert(title string, message string) &SimpleWindow`

Shows a native modal information alert dialog with an OK button.

### `win.alert_with_style(title string, message string, style string) &SimpleWindow`

Shows a native modal alert dialog with a specific visual severity style preset (options: `'info'`, `'warning'`, `'critical'`).

### `win.confirm(title string, message string) bool`

Shows a warning confirmation popup with Yes/No actions, returning a boolean.

### `win.prompt(title string, message string, default_val string) string`

Shows a popup prompt requesting input from the user, returning the entered string (or empty if cancelled).

### `win.choice_dialog(title string, message string, choices []string) int`

Displays a native macOS alert with multiple custom button choices. Returns the 0-indexed choice clicked by the user (or `-1` if cancelled/dismissed).

### `win.select_file() string`

Launches the native macOS file picker panel, returning the chosen file path (or empty if cancelled).

### `win.select_file_with_extensions(extensions string) string`

Launches the native macOS file picker panel filtered by specific file extension constraints (e.g. `'png,txt,pdf'`), returning the chosen file path (or empty if cancelled).

### `win.select_folder() string`

Launches the native macOS folder selection panel, returning the chosen folder path (or empty if cancelled).

### `win.save_file_picker() string`

Launches the native macOS file save panel, returning the target path (or empty if cancelled).

---

## 6. Utilities & System Actions

### `win.toast(message string) &SimpleWindow`

Shows a self-dismissing native overlay toast notification containing the message text.

### `win.open_url(url string) &SimpleWindow`

Opens a web URL in the user's default web browser.

### `win.copy_to_clipboard(text string) &SimpleWindow`

Copies the specified text to the macOS system clipboard.

---

### 6b. Neutralino-Inspired System Calls & Platform API

To simplify system integrations and mirror key features from NeutralinoJS, `simplegui` includes fluent-style wrappers around the V standard library's `os` and core system actions. These methods extend `SimpleWindow` and are readily available inside event handlers.

### Shell Execution (`NL_OS`)

- `win.exec(command string) (string, int)`: Runs a command synchronously in the system terminal and returns a tuple of `(output, exit_code)`.
- `win.exec_or(command string, fallback string) string`: Runs a command, returning its stdout if successful (code 0) or the `fallback` value if it failed.
- `win.exec_bg(command string) &SimpleWindow`: Spawns a shell command in the background (asynchronous concurrent thread) so the application GUI doesn't block or freeze.
- `win.exec_timeout(command string, timeout_ms int) (string, int, bool)`: Runs a command synchronously with a maximum timeout in milliseconds, returning `(output, exit_code, timed_out)`.
- `win.exec_result(command string) CommandResult`: Runs a command and returns structured metadata (`output`, `exit_code`, `duration_ms`, `attempts`, timeout flag) for production diagnostics.
- `win.exec_timeout_result(command string, timeout_ms int) CommandResult`: Timeout-aware variant of `exec_result`.
- `win.exec_retry(command string, max_attempts int, initial_delay_ms int, backoff_factor f64) CommandResult`: Retries failed commands using exponential backoff.
  - **Returned Type**: `CommandResult` contains `command`, `output`, `exit_code`, `timed_out`, `duration_ms`, and `attempts`.

### Environment Variables

- `win.get_env(key string) string`: Retrieves the value of a system environment variable.
- `win.get_env_opt(key string) ?string`: Retrieves the optional value of an environment variable, returning `none` if not defined.
- `win.get_env_or(key string, default_val string) string`: Retrieves an environment variable, returning `default_val` if missing or empty.
- `win.get_envs() map[string]string`: Retrieves all system environment variables as a key-value map.
- `win.set_env(key string, val string) &SimpleWindow`: Sets or overrides an environment variable for the running app.
- `win.unset_env(key string) &SimpleWindow`: Removes an environment variable.

### System Diagnostics

- `win.get_hostname() string`: Retrieves the network hostname of the current machine.
- `win.get_username() string`: Retrieves the active username running the application.
- `win.get_user_os() string`: Returns the host operating system name (e.g. `macos`, `linux`, `windows`).
- `win.get_pid() int`: Returns the current Process ID (PID).
- `win.get_ppid() int`: Returns the Parent Process ID (PPID).
- `win.get_parent_pid() int`: Returns the Parent Process ID (PPID).
- `win.get_parent_process_name() string`: Returns the executable name of the parent process.
- `win.get_uid() int`: Returns the real User ID (UID).
- `win.get_gid() int`: Returns the real Group ID (GID).
- `win.get_euid() int`: Returns the effective User ID (EUID).
- `win.get_egid() int`: Returns the effective Group ID (EGID).
- `win.exists_in_path(cmd string) bool`: Checks if a given command binary is present in the system's PATH.
- `win.command_exists(cmd string) bool`: Alias-style command presence check used in reliability-oriented flows.
- `win.find_executable(cmd string) string`: Returns the absolute path of the specified command binary if it exists in the system's PATH.
- `win.require_command(cmd string) !string`: Returns the absolute command path or a clear error when the dependency is missing.
- `win.get_executable_path() string`: Returns the absolute path of the current running executable.
- `win.get_uname() Uname`: Retrieves system operating system and kernel architecture details.
  - **Returned Type**: `Uname` contains `sysname`, `nodename`, `release`, `version`, and `machine` string fields.

### System Notifications (`os.showNotification`)

- `win.show_system_notification(title string, message string) &SimpleWindow`: Dispatches a native, standard, system-wide macOS notification banner using lightweight Applescript.

### macOS Appearance & Power Controls

- `win.is_dark_mode() bool`: Returns `true` when macOS global Dark Mode is active.
- `win.get_system_theme() string`: Returns `'dark'` or `'light'` based on current system appearance.
- `win.set_system_dark_mode(enabled bool) &SimpleWindow`: Enables/disables global Dark Mode (`true` for dark, `false` for light).
- `win.set_system_theme(theme string) !&SimpleWindow`: Sets the global system theme. Accepted values: `'dark'`, `'light'`.
- `win.sleep_display() &SimpleWindow`: Immediately puts attached displays to sleep (`pmset displaysleepnow`).
- `win.sleep_computer() &SimpleWindow`: Puts the Mac to sleep.
- `win.lock_screen() &SimpleWindow`: Locks the current user session.
- `win.start_screen_saver() &SimpleWindow`: Starts the macOS screen saver engine.
- `win.log_out_user() &SimpleWindow`: Logs out the current user.
- `win.restart_computer() &SimpleWindow`: Restarts the Mac.
- `win.shut_down_computer() &SimpleWindow`: Shuts down the Mac.
- `win.get_power_source() string`: Returns active power source as `'ac'`, `'battery'`, `'ups'`, or `'unknown'`.
- `win.get_battery_charge_percent() int`: Returns battery percentage (0-100) or `-1` if unavailable.
- `win.get_battery_charging_status() string`: Returns battery charge state as `'charging'`, `'discharging'`, `'charged'`, `'not_charging'`, or `'unknown'`.
- `win.start_prevent_sleep() &SimpleWindow`: Starts an indefinite tracked `caffeinate` guard to keep macOS awake.
- `win.stop_prevent_sleep() &SimpleWindow`: Stops the tracked `caffeinate` guard started by `start_prevent_sleep()`.
- `win.is_preventing_sleep() bool`: Reports whether the tracked sleep-prevention guard is currently active.
- `win.prevent_sleep_while_process_running(target_pid int) &SimpleWindow`: Prevents sleep while the specified PID is alive (`caffeinate -w`).

Notes:

- Appearance and power calls are best-effort wrappers around macOS tools like `osascript`, `pmset`, and `CGSession`.
- Depending on macOS privacy/security settings, your app may need Automation permissions (for `System Events`) to perform some actions.

### Cross-Window Registry & External App Automation (`simplegui.sys_*`)

These are package-level helpers (not `win.*` instance methods) for Spy++-style inspection and automation across windows.

#### Registered SimpleGUI window registry

- `simplegui.sys_register_window(win &SimpleWindow)`: Registers a window for cross-window access.
- `simplegui.sys_unregister_window(title string)`: Unregisters a window by title.
- `simplegui.sys_list_app_windows() []string`: Returns titles of all registered windows.
- `simplegui.sys_get_window(title string) ?&SimpleWindow`: Returns a registered window by title.

#### Internal window ordering, visibility, and control operations

- `simplegui.sys_order_app_window_front(title string) bool`: Brings a registered window to front.
- `simplegui.sys_order_app_window_back(title string) bool`: Sends a registered window to back.
- `simplegui.sys_set_app_window_visible(title string, visible bool) bool`: Shows/hides a registered window.
- `simplegui.sys_spy_window(title string) ?[]simplegui.ControlInfo`: Returns control metadata snapshot for a registered window.
- `simplegui.sys_set_control_enabled(win_title string, control_name string, enabled bool) bool`: Enables/disables a named control.
- `simplegui.sys_set_control_visible(win_title string, control_name string, visible bool) bool`: Shows/hides a named control.
- `simplegui.sys_set_control_text(win_title string, control_name string, text string) bool`: Sets control text/value.
- `simplegui.sys_get_control_text(win_title string, control_name string) string`: Reads control text/value.
- `simplegui.sys_flash_control(win_title string, control_name string) bool`: Flashes/highlights a control.

#### External macOS app enumeration and AXUIElement control automation

- `simplegui.sys_list_external_apps() []simplegui.ExternalAppInfo`: Lists running GUI apps (`pid`, `name`, `bundle_id`).
- `simplegui.sys_spy_external_app(pid int) []simplegui.ExternalControlInfo`: Returns external app control metadata (`role`, `title`, `value`, `enabled`).
- `simplegui.sys_set_external_control_value(pid int, control_title string, value string) bool`: Sets value/text on matching external control.
- `simplegui.sys_press_external_control(pid int, control_title string) bool`: Presses/clicks matching external control.
- `simplegui.sys_set_external_control_enabled(pid int, control_title string, enabled bool) bool`: Enables/disables matching external control.
- `simplegui.sys_set_external_control_visible(pid int, control_title string, visible bool) bool`: Shows/hides matching external control.
- `simplegui.sys_flash_external_control(pid int, control_title string) bool`: Draws a temporary visual highlight overlay over matching control.
- `simplegui.sys_set_external_app_frontmost(pid int) bool`: Requests the external app process to become frontmost.
- `simplegui.sys_set_external_app_visible(pid int, visible bool) bool`: Shows/hides external app process windows.

Notes:

- External app helpers rely on macOS Accessibility (`System Events`) permissions.
- Match selectors should typically use a control title first, then role as fallback.
- See `demos/spy_plus_plus_demo.v` for a production-ready target-table + control-tree workflow.
- The Spy++ demo now includes a strict selector mode (exact title/role), control value watch mode, one-click health check diagnostics, selector resolve helper, and action-history audit table for production debugging workflows.
- `demos/ext_spy_calc_check.v` is a lightweight proof-of-concept that auto-opens Calculator when needed, drives it through `sys_spy_external_app()` and `sys_press_external_control()`, and verifies the readback with a single `v run demos/ext_spy_calc_check.v` command.

#### Quick start example

```v
module main

import simplegui

fn main() {
  mut win := simplegui.new_simple_window('Sys Automation API', 620, 360)
  win.add_console('log', 220)
  win.add_button('run_demo', 'Run Automation Checks')

  win.on_click('run_demo', fn (mut w simplegui.SimpleWindow) {
    // 1) Internal registered windows
    titles := simplegui.sys_list_app_windows()
    w.append_console('log', 'Registered windows: ${titles.len}', 0)
    if titles.len > 0 {
      simplegui.sys_order_app_window_front(titles[0])
      w.append_console('log', 'Brought to front: ${titles[0]}', 0)
    }

    // 2) External applications by PID
    apps := simplegui.sys_list_external_apps()
    w.append_console('log', 'External apps: ${apps.len}', 0)
    if apps.len > 0 {
      pid := apps[0].pid
      simplegui.sys_set_external_app_frontmost(pid)
      controls := simplegui.sys_spy_external_app(pid)
      w.append_console('log', 'PID ${pid} controls: ${controls.len}', 0)
    }
  })

  win.run()
}
```

### Hardware & Computer Diagnostics (`NL_COMPUTER`)

- `win.get_cpu_info() string`: Returns the local processor model string (e.g., `Apple M2 Max` or `Intel Core i7`).
- `win.get_cpu_cores() int`: Returns the physical + virtual core count of the processor.
- `win.get_physical_cpu_cores() int`: Returns the physical CPU core count (`sysctl hw.physicalcpu`).
- `win.get_logical_cpu_cores() int`: Returns the logical CPU core count (`sysctl hw.logicalcpu`).
- `win.get_cpu_frequency_hz() u64`: Returns the CPU clock speed frequency in Hz (if available).
- `win.get_cpu_architecture() string`: Returns the machine CPU architecture string (e.g. `arm64`, `x86_64`).
- `win.get_memory_info() string`: Returns the total capacity of system physical memory (e.g., `16.0 GB RAM`).
- `win.get_total_memory_bytes() u64`: Returns total physical RAM capacity in bytes (`sysctl hw.memsize`).
- `win.get_disk_usage(path string) !DiskStats`: Retrieves disk space usage statistics for the given folder path.
  - **Returned Type**: `DiskStats` has `total`, `available`, and `used` as `u64` fields representing size in bytes.

### System Paths Lookup

- `win.get_system_path(name string) string`: Resolves canonical folders:
  - `'home'`: User's home folder.
  - `'temp'`, `'tmp'`: System temporary location.
  - `'desktop'`: Desktop folder.
  - `'documents'`: Documents folder.
  - `'downloads'`: Downloads folder.
  - `'cache'`: User caches folder.
  - `'config'`: User config folder.
  - `'data'`: User data folder.
  - `'app'`: App executable folder.
- `win.get_app_data_dir(app_name string) string`: Resolves the application support directory path (`~/Library/Application Support/<app_name>`).
- `win.get_user_downloads_dir() string`: Returns absolute path to the user's Downloads folder.
- `win.get_user_documents_dir() string`: Returns absolute path to the user's Documents folder.
- `win.get_user_desktop_dir() string`: Returns absolute path to the user's Desktop folder.

### Filesystem IO Utilities (`NL_FILESYSTEM`)

- `win.file_exists(path string) bool`: Reports true if the file or folder exists.
- `win.is_dir(path string) bool`: Reports true if the target path is a directory.
- `win.is_file(path string) bool`: Reports true if the target path is a regular file.
- `win.is_dir_empty(path string) bool`: Reports true if the directory has no files or subfolders.
- `win.get_free_disk_space(path string) u64`: Returns free bytes available on the filesystem volume hosting `path`.
- `win.copy_directory(src string, dest string) !&SimpleWindow`: Recursively copies a directory tree and its contents to destination.
- `win.read_file(path string) string`: Reads file contents, returning an empty string if reading fails.
- `win.read_file_opt(path string) !string`: Reads file contents with V's `!` error-handling/propagation.
- `win.read_lines(path string) ![]string`: Reads a file line-by-line and returns an array of strings.
- `win.read_bytes(path string) ![]u8`: Reads a file's content as a byte array.
- `win.write_file(path string, content string) &SimpleWindow`: Writes content to a file.
- `win.write_file_opt(path string, content string) !&SimpleWindow`: Writes content to a file with V's `!` error-handling/propagation.
- `win.write_file_atomic(path string, content string) !&SimpleWindow`: Writes using temp-file + rename semantics (best effort atomic on same volume) for safer production persistence.
- `win.write_lines(path string, lines []string) !&SimpleWindow`: Writes an array of strings to a file, separating them with newlines.
- `win.write_bytes(path string, bytes []u8) !&SimpleWindow`: Writes a byte array to a file.
- `win.create_directory(path string) &SimpleWindow`: Recursively creates/ensures all directories in the given path.
- `win.create_single_directory(path string) !&SimpleWindow`: Creates a single new directory (non-recursive).
- `win.delete_file(path string) &SimpleWindow`: Deletes a target file or folder path.
- `win.delete_single_directory(path string) !&SimpleWindow`: Deletes a single empty directory.
- `win.delete_directory(path string) !&SimpleWindow`: Recursively removes a directory and all of its contents.
- `win.copy_file(src string, dest string) !&SimpleWindow`: Copies a file from a source path to a destination path.
- `win.move_file(src string, dest string) !&SimpleWindow`: Moves or renames a file.
- `win.create_symlink(target string, linkpath string) !&SimpleWindow`: Creates a symbolic link pointing to a target path.
- `win.is_symlink(path string) bool`: Checks if a path points to a symbolic link.
- `win.get_working_directory() string`: Returns the current active working directory.
- `win.set_working_directory(path string) !&SimpleWindow`: Changes the current active working directory.
- `win.get_file_size(path string) !i64`: Returns the size of a file in bytes.
- `win.get_last_modified(path string) i64`: Returns the Unix timestamp when the file was last modified.
- `win.glob(pattern string) ![]string`: Finds all files matching a wildcard pattern (e.g. `*.txt`).
- `win.walk(path string, callback fn (string))`: Recursively traverses a directory, executing a callback function for each file found.
- `win.walk_ext(path string, ext string) []string`: Traverses a directory, returning a list of files that match a file extension filter (e.g. `.txt`).
- `win.set_permissions(path string, mode int) !&SimpleWindow`: Changes the permission bits on a file.
- `win.set_ownership(path string, uid int, gid int) !&SimpleWindow`: Changes the owner user ID (UID) and group ID (GID) of a file.
- `win.is_readable(path string) bool`: Checks if a path is readable.
- `win.is_writable(path string) bool`: Checks if a path is writable.
- `win.is_executable(path string) bool`: Checks if a path is executable.
- `win.read_dir(path string) []string`: Returns a string array of items within the directory.
- `win.tail_file(path string, max_lines int) ![]string`: Returns up to the last `max_lines` lines of a file (log-style usage).
- `win.wait_for_file(path string, timeout_ms int, poll_ms int) bool`: Waits until a file exists or timeout elapses.

### Path String Parsing

- `win.path_dir(path string) string`: Returns the parent directory of the path.
- `win.path_base(path string) string`: Returns the last element of the path.
- `win.path_ext(path string) string`: Returns the file extension of the path (including the dot).
- `win.path_name(path string) string`: Returns the filename with its extension.
- `win.path_is_abs(path string) bool`: Checks if the path is an absolute path.
- `win.path_real(path string) string`: Resolves all symbolic links and relative references to return an absolute canonical path.
- `win.path_norm(path string) string`: Normalizes path separators.
- `win.path_split(path string) (string, string, string)`: Splits a path into `(directory, filename, extension)`.

### File Metadata

- `win.get_file_metadata(path string) !FileMetadata`: Retrieves detailed metadata for the file at the given path.
  - **Returned Type**: `FileMetadata` contains size, inode, nlink, dev, uid, gid, atime, mtime, ctime, file_type, mode_bitmask, and individual boolean permission fields for owner, group, and others (e.g. `owner_r`, `owner_w`, `owner_x`).

### Asynchronous Subprocesses

- `win.spawn_process(path string, args []string, env map[string]string) !&SimpleProcess`: Starts a background subprocess with redirected stdin/stdout.
  - **Returned Type**: `SimpleProcess` pointer supports:
    - `proc.is_alive() bool`: Checks if the child process is still running.
    - `proc.write(data string)`: Sends input data to the process's standard input.
    - `proc.read() string`: Reads any output currently available in the process's stdout/stderr pipe.
    - `proc.stop()`: Suspends the process using a POSIX SIGSTOP signal.
    - `proc.resume()`: Resumes a suspended process using a POSIX SIGCONT signal.
    - `proc.terminate()`: Terminates the process using a POSIX SIGTERM signal.
    - `proc.wait()`: Waits for the subprocess to exit and blocks until completion.
    - `proc.close()`: Cleans up and releases process resources.

### System Clock & Time

- `win.get_time() SystemTime`: Returns current local date and time in a structured `SystemTime` object.
  - **Returned Type**: `SystemTime` contains `year`, `month`, `day`, `hour`, `minute`, `second`, `unix_epoch`, `unix_milli`, `rfc3339`, and `weekday`.
- `win.get_unix_epoch() i64`: Returns current time as a Unix epoch timestamp in seconds.
- `win.get_unix_milli() i64`: Returns current time as a Unix epoch timestamp in milliseconds.
- `win.get_time_formatted(format string) string`: Returns current local time formatted (e.g. `YYYY-MM-DD HH:mm:ss`).
- `win.get_uptime_seconds() i64`: Returns macOS system uptime in seconds via direct C `sysctl`.
- `win.get_uptime_formatted() string`: Returns human-readable system uptime string (e.g. `"3 days, 4 hours, 12 mins"`).
- `win.get_boot_timestamp() i64`: Returns system boot Unix epoch timestamp.
- `win.sleep_ms(ms u64) &SimpleWindow`: Pauses execution for specified milliseconds.

### Network Utilities

- `win.get_local_ip() string`: Resolves machine's primary local IP address (skipping loopback).
- `win.get_external_ip() string`: Fetches public IP address via HTTP with 2-second timeout protection.
- `win.ping(host string, count int) bool`: Probes TCP port 80/443 reachability with non-blocking 1-second timeout.
- `win.dns_lookup(hostname string) string`: Performs forward DNS lookup returning resolved IP address via libc `getaddrinfo`.
- `win.get_wifi_ssid() string`: Returns connected Wi-Fi network SSID on macOS.
- `win.get_network_interfaces() []string`: Lists active network interface names (`en0`, `lo0`, etc.).
- `win.wait_for_port(host string, port int, timeout_ms int, poll_ms int) bool`: Waits until a TCP endpoint is reachable or timeout elapses.
- `win.get_mac_address() string`: Returns hardware MAC address of primary Wi-Fi/Ethernet interface (`en0`).
- `win.get_dns_servers() []string`: Returns array of configured DNS server IP addresses.
- `win.get_default_gateway() string`: Returns IP address of default network gateway.
- `win.is_internet_connected() bool`: Tests active internet connectivity via ping host checks.
- `win.get_listening_ports() []int`: Returns array of active listening TCP ports on system.

### System Resource Monitoring

- `win.get_cpu_usage_percent() f64`: Returns CPU utilization percentage estimate across all processes.
- `win.get_process_memory_mb(pid int) f64`: Returns resident set size (RSS) memory usage of process `pid` (or current PID if `0`) in Megabytes.
- `win.get_process_cpu_percent(pid int) f64`: Returns CPU usage percentage for process `pid` (or current PID if `0`).
- `win.get_load_average() (f64, f64, f64)`: Returns 1m, 5m, and 15m system load averages via C `getloadavg()`.
- `win.get_memory_pressure() string`: Returns macOS kernel memory pressure state (`"normal"`, `"warn"`, or `"critical"`) via fast `sysctl`.
- `win.get_running_process_count() int`: Returns total count of running processes.
- `win.get_open_file_count() int`: Returns total count of open file descriptors in system.
- `win.get_swap_usage() string`: Returns virtual memory swap utilization summary.

### Terminal & Shell Utilities

- `win.beep() &SimpleWindow`: Triggers standard macOS system alert sound (NSBeep).
- `win.beep_n(n int) &SimpleWindow`: Plays macOS system alert sound `n` times.
- `win.play_system_sound(sound_name string) &SimpleWindow`: Plays macOS system alert sound by name (`Glass`, `Ping`, `Hero`, `Pop`, `Tink`, `Submarine`).
- `win.say(text string) &SimpleWindow`: Uses macOS text-to-speech engine to speak message out loud.
- `win.speak_with_voice(text string, voice string) &SimpleWindow`: Text-to-speech with specific macOS voice name (`Samantha`, `Alex`, `Fred`).
- `win.osascript_dialog(prompt string, default_value string) string`: Displays native macOS text input dialog returning user entry.
- `win.osascript_alert(title string, message string) bool`: Displays native macOS alert dialog box.
- `win.osascript_choose_file() string`: Displays native macOS file picker dialog returning selected POSIX path.
- `win.osascript_choose_folder() string`: Displays native macOS folder picker dialog returning selected POSIX path.

### macOS Details & App Integration

- `win.get_macos_version() string`: Returns macOS version string (e.g. `"14.5"`).
- `win.get_macos_build() string`: Returns macOS build identifier (e.g. `"23F79"`).
- `win.get_macos_product_name() string`: Returns product name (e.g. `"macOS"`).
- `win.get_device_model() string`: Returns hardware model identifier (e.g. `"MacBookPro18,3"`).
- `win.get_serial_number() string`: Returns device serial number via fast targeted `ioreg`.
- `win.get_machine_id() string`: Returns the unique hardware UUID of the macOS machine (`IOPlatformUUID`).
- `win.is_apple_silicon() bool`: Returns `true` if machine CPU architecture is Apple Silicon (ARM64).
- `win.is_rosetta_emulation() bool`: Returns `true` if process is executing under Rosetta 2 translation.
- `win.is_sip_enabled() bool`: Returns `true` if macOS System Integrity Protection (SIP) is active.
- `win.get_screen_resolution() string`: Returns primary display resolution (e.g. `"2560 x 1600"`).
- `win.get_screen_count() int`: Returns the total number of connected monitors.
- `win.is_retina_display() bool`: Returns `true` if primary display is a high-DPI (Retina) screen.
- `win.get_main_display_bounds() (int, int, int, int)`: Returns `(x, y, width, height)` bounds of primary display screen.
- `win.get_display_scale_factor() f64`: Returns display scale factor multiplier (`2.0` Retina, `1.0` Standard).
- `win.get_gpu_info() string`: Returns GPU model string of primary graphics adapter.
- `win.get_battery_percent() int`: Returns battery charge percentage (or `-1` for desktop/no battery).
- `win.get_battery_time_remaining() string`: Returns estimated remaining battery operating time (e.g. `"2:30"`).
- `win.is_on_ac_power() bool`: Returns true if machine is plugged into AC power.
- `win.is_low_power_mode() bool`: Returns true if macOS Low Power Mode is currently enabled.
- `win.is_dark_mode() bool`: Returns true if macOS global appearance is set to Dark Mode.
- `win.get_system_theme() string`: Returns `"dark"` if macOS is in Dark Mode, otherwise `"light"`.
- `win.toggle_dark_mode() &SimpleWindow`: Toggles macOS system appearance mode between Light and Dark.
- `win.get_system_accent_color() string`: Returns current macOS system accent color name (`Red`, `Blue`, `Purple`, `Multicolor`, etc.).
- `win.is_do_not_disturb_enabled() bool`: Returns true if macOS Focus / Do Not Disturb is currently active.
- `win.get_volume() int`: Returns system output volume level percentage (0–100).
- `win.set_volume(level int) &SimpleWindow`: Sets system output volume level (0–100).
- `win.is_muted() bool`: Returns true if system output volume is muted.
- `win.set_muted(mute bool) &SimpleWindow`: Mutes or unmutes system output audio.
- `win.get_active_app_name() string`: Returns the name of the active frontmost macOS application.
- `win.get_active_window_title() string`: Returns the title of the frontmost focused window.
- `win.get_running_app_names() []string`: Returns a string array of names of all active GUI applications running on macOS.
- `win.is_process_running(proc_name string) bool`: Checks if a process matching the given name pattern is active (`pgrep`).
- `win.kill_process(proc_name string) bool`: Terminates matching processes by name pattern (`pkill -f`).
- `win.kill_process_by_name(proc_name string) bool`: Alias for `kill_process(proc_name)`.
- `win.kill_process_exact(proc_name string) bool`: Terminates processes matching the exact process name (`pkill -x`).
- `win.kill_process_by_pid(pid int) bool`: Terminates process with specified PID (`kill -9`).
- `win.exec_in_dir(dir_path string, command string) (string, int)`: Executes shell command synchronously inside a specified directory (`cd <dir> && <cmd>`).
- `win.download_file(url string, dest_path string) !&SimpleWindow`: Downloads a remote HTTP/HTTPS file directly to disk.
- `win.trash_file(path string) !&SimpleWindow`: Safely moves a file or directory to macOS Trash bin via Finder AppleScript.
- `win.empty_trash() &SimpleWindow`: Empties the macOS Trash bin.
- `win.zip_directory(dir_path string, zip_path string) !&SimpleWindow`: Compresses a directory tree into a `.zip` archive.
- `win.unzip_archive(zip_path string, dest_dir string) !&SimpleWindow`: Extracts a `.zip` archive file into a target destination directory.
- `win.create_temp_file(prefix string, suffix string) !string`: Generates and creates a unique temporary file path and returns it.
- `win.create_temp_dir(prefix string) !string`: Generates and creates a unique temporary directory path and returns it.
- `win.append_file(path string, content string) !&SimpleWindow`: Appends text content to a file (creates if missing).
- `win.touch_file(path string) !&SimpleWindow`: Creates an empty file if missing or updates its modification timestamp.
- `win.get_directory_size(path string) u64`: Recursively calculates total cumulative size of a directory tree in bytes.
- `win.sha256_file(path string) !string`: Calculates the SHA256 hexadecimal hash digest of a file.
- `win.md5_file(path string) !string`: Calculates the MD5 hexadecimal hash digest of a file.
- `win.is_port_open(host string, port int) bool`: Checks if a TCP port on a given host is accepting connections.
- `win.find_available_port(start_port int) int`: Scans ports starting from `start_port` to find the first open/unbound TCP port.
- `win.prevent_sleep_bg(duration_sec int) &SimpleWindow`: Spawns macOS `caffeinate -t <seconds>` in background to prevent system sleep.
- `win.start_prevent_sleep() &SimpleWindow`: Starts indefinite tracked `caffeinate -dimsu` sleep-prevention guard.
- `win.stop_prevent_sleep() &SimpleWindow`: Stops the tracked sleep-prevention guard if active.
- `win.is_preventing_sleep() bool`: Returns true when a tracked sleep-prevention guard is active.
- `win.prevent_sleep_while_process_running(target_pid int) &SimpleWindow`: Prevents sleep while a target process PID remains alive.
- `win.get_power_source() string`: Returns active power source (`"ac"`, `"battery"`, `"ups"`, `"unknown"`).
- `win.get_battery_charge_percent() int`: Returns battery charge percentage (0-100), or `-1` if unavailable.
- `win.get_battery_charging_status() string`: Returns charging state (`"charging"`, `"discharging"`, `"charged"`, `"not_charging"`, `"unknown"`).
- `win.take_screenshot(target_path string) !&SimpleWindow`: Captures a screenshot of the primary display to a file.
- `win.take_screenshot_window(target_path string) !&SimpleWindow`: Captures a screenshot of the active window to a file.
- `win.defaults_read(domain string, key string) string`: Reads a preference value from a macOS defaults domain.
- `win.defaults_write(domain string, key string, val string) &SimpleWindow`: Writes a preference key/value pair into a macOS defaults domain.
- `win.defaults_delete(domain string, key string) &SimpleWindow`: Deletes a preference key from a macOS defaults domain.
- `win.get_app_bundle_id() string`: Reads bundle identifier from running app's `Info.plist`.
- `win.get_system_locale() string`: Returns primary system locale code (e.g. `"en_US"`).
- `win.get_timezone() string`: Returns active timezone identifier (e.g. `"America/Chicago"`).
- `win.set_dock_badge(count int) &SimpleWindow`: Sets or clears current application's macOS Dock badge count.
- `win.open_in_finder(folder_path string) &SimpleWindow`: Opens a folder directly in macOS Finder.
- `win.reveal_in_finder(path string) &SimpleWindow`: Highlights target file/folder in macOS Finder.
- `win.open_in_default_app(path string) &SimpleWindow`: Opens file using associated default application.
- `win.open_with_app(path string, app_id string) &SimpleWindow`: Opens file with specified app by bundle ID or name.
- `win.open_terminal() &SimpleWindow`: Opens new macOS Terminal window.
- `win.launch_at_login_add(app_path string) &SimpleWindow`: Registers application in macOS Login Items.
- `win.launch_at_login_remove(app_name string) &SimpleWindow`: Removes application from macOS Login Items.

---

## 6c. V Standard Library High-Level Wrappers

`simplegui` provides beginner-friendly, safe high-level wrappers around complex V standard library structures, which are exposed both as static helpers under `simplegui` namespace and as methods on `SimpleWindow`.

### HTTP Client (`net.http`)

- `win.http_get(url string) string`: Sends a synchronous GET request and returns the response body (empty on failure).
- `win.http_post(url string, data string) string`: Sends a synchronous POST request with the specified body, returning the response (empty on failure).
- `simplegui.SimpleHttpResponse`: Structured response metadata used by strict HTTP helpers.
  - Fields: `status_code int`, `body string`, `raw_headers string`, `url string`.
- `simplegui.SimpleHttpRequestOptions`: Request options used by strict HTTP helpers.
  - Fields: `headers map[string]string`, `user_agent string`, `retries int`, `retry_delay_ms int`, `expect_success bool`.
- `simplegui.http_request(method http.Method, url string, data string, options SimpleHttpRequestOptions) !SimpleHttpResponse`: Executes an HTTP request with configurable headers, retries, retry delay, user-agent, and strict success-status enforcement.
- `win.http_get_strict(url string) !string`: Sends a strict GET request and returns response body or explicit error.
- `win.http_post_strict(url string, data string) !string`: Sends a strict POST request and returns response body or explicit error.

### Regular Expressions (`regex`)

- `win.regex_match(text string, pattern string) bool`: Checks if a target string contains matches for a regular expression pattern.
- `win.regex_find(text string, pattern string) []string`: Extracts all substrings matching a regular expression pattern.
- `win.regex_replace(text string, pattern string, replacement string) string`: Replaces any pattern matches inside a string with a replacement text.
- `win.regex_match_strict(text string, pattern string) !bool`: Same as `regex_match`, but returns an explicit error if the pattern is invalid.
- `win.regex_find_strict(text string, pattern string) ![]string`: Same as `regex_find`, but returns an explicit error if the pattern is invalid.
- `win.regex_replace_strict(text string, pattern string, replacement string) !string`: Same as `regex_replace`, but returns an explicit error if the pattern is invalid.

### Cryptography & Hash Functions (`crypto`, `crypto.hmac`, `hash`)

- `win.crypto_sha256(text string) string`: Computes the hex-encoded SHA-256 hash of a string.
- `win.crypto_sha512(text string) string`: Computes the hex-encoded SHA-512 hash of a string.
- `win.crypto_sha1(text string) string`: Computes the hex-encoded SHA-1 hash of a string.
- `win.crypto_md5(text string) string`: Computes the hex-encoded MD5 hash of a string.
- `win.crypto_bcrypt_hash(password string) !string`: Generates a secure bcrypt password hash of a string.
- `win.crypto_bcrypt_verify(password string, hash string) bool`: Verifies a password against a bcrypt hash.
- `win.crypto_hmac_sha256(text string, key string) string`: Computes the hex-encoded HMAC-SHA256 signature of a string with a key.
- `win.crypto_hmac_sha512(text string, key string) string`: Computes the hex-encoded HMAC-SHA512 signature of a string with a key.
- `win.crypto_hmac_sha1(text string, key string) string`: Computes the hex-encoded HMAC-SHA1 signature of a string with a key.
- `win.crypto_wyhash(text string, seed u64) u64`: Computes a 64-bit Wyhash value of a string with a seed.
- `win.crypto_encrypt_aes(plain_text string, key_hex string) string`: Encrypts text using 128-bit AES block cipher under CBC mode, returning hex-encoded ciphertext.
- `win.crypto_decrypt_aes(cipher_hex string, key_hex string) string`: Decrypts a hex-encoded AES CBC block string, returning the unpadded plaintext string.
- `win.crypto_encrypt_aes_secure(plain_text string, key_hex string) !string`: Production-safe AES-CBC encryption with random IV and PKCS7 padding validation, returning hex of `iv + ciphertext`.
- `win.crypto_decrypt_aes_secure(payload_hex string, key_hex string) !string`: Decrypts payloads from `crypto_encrypt_aes_secure`, validating key length, payload framing, and PKCS7 padding.

### Encoding (`encoding.hex`, `encoding.base64`)

- `win.hex_encode(text string) string`: Converts a raw text string into its hex-encoded representation.
- `win.hex_decode(hex_str string) string`: Decodes a hex-encoded string back into raw text.
- `win.base64_encode(text string) string`: Converts a raw text string into its Base64-encoded representation.
- `win.base64_decode(b64_str string) string`: Decodes a Base64-encoded string back into raw text.

### Compression (`compress.gzip`, `compress.zlib`, `compress.deflate`, & `compress.zstd`)

- `win.compress_gzip(text string) []u8`: Compresses a string using Gzip format.
- `win.decompress_gzip(data []u8) string`: Decompresses Gzip-compressed binary bytes back to a string.
- `win.compress_zlib(text string) []u8`: Compresses a string using Zlib format.
- `win.decompress_zlib(data []u8) string`: Decompresses Zlib-compressed binary bytes back to a string.
- `win.compress_deflate(text string) []u8`: Compresses a string using Deflate format.
- `win.decompress_deflate(data []u8) string`: Decompresses Deflate-compressed binary bytes back to a string.
- `win.compress_zstd(text string) []u8`: Compresses a string using Zstd format.
- `win.decompress_zstd(data []u8) string`: Decompresses Zstd-compressed binary bytes back to a string.

### Random Numbers (`rand`, `crypto.rand`)

- `win.rand_int(min int, max int) int`: Generates a secure random integer between min (inclusive) and max (exclusive).
- `win.rand_string(length int) string`: Produces a random alphanumeric string token of target length.
- `win.rand_shuffle_strings(mut arr []string) &SimpleWindow`: Shuffles the items within a string array in-place.
- `win.rand_choice_strings(items []string) string`: Selects a random string from an array with uniform probability.
- `win.rand_choice_ints(items []int) int`: Selects a random integer from an array with uniform probability.
- `win.rand_weighted_choice_strings(items []string, weights []f64) string`: Selects a string element from an array proportional to relative non-negative probabilities in `weights`.
- `win.rand_weighted_choice_ints(items []int, weights []f64) int`: Selects an integer element from an array proportional to relative weights.
- `win.crypto_rand_bytes(length int) []u8`: Returns a slice of cryptographically secure random bytes of specified length.
- `win.crypto_rand_hex(length int) string`: Produces a cryptographically secure hex string token.
- `win.crypto_rand_uuid() string`: Generates a cryptographically secure UUID v4 string (`8-4-4-4-12` hex format).

### Time & Calendar Calculations (`time`)

- `win.time_now() string`: Returns the formatted current timestamp (`YYYY-MM-DD HH:MM:SS`).
- `win.time_elapsed(ms int) string`: Formats a millisecond counter into a friendly custom duration (e.g. `1200ms` or `1.20s`).
- `win.time_unix_timestamp() i64`: Returns current Unix epoch timestamp in seconds.
- `win.time_from_unix(timestamp i64) string`: Formats a Unix epoch timestamp (seconds) into standard `YYYY-MM-DD HH:MM:SS` string.
- `win.time_is_leap_year(year int) bool`: Returns true if specified year is a leap year.
- `win.time_days_in_month(year int, month int) int`: Returns total number of days in a specific year and month (1-12).

### URL Escaping & Object Model (`net.urllib`)

- `win.url_encode(text string) string`: Outputs a secure, percent-encoded string for URL query parameters.
- `win.url_decode(text string) string`: Translates a percent-encoded URL query string back to plain text.
- `win.url_parse(raw_url string) SimpleURL`: Parses a raw URL string into a `SimpleURL` structure.
- `win.url_build(scheme string, host string, path string, query_params map[string]string) string`: Constructs a canonical URL string from scheme, host, path, and query parameter map.
- **Returned Type**: `SimpleURL` supports:
  - `u.build_url() string`: Assembles the full canonical URL string from fields (`scheme`, `host`, `port`, `path`, `query`, `fragment`).

### Config Parsers (TOML & JSON)

- `win.toml_parse(content string) &TOMLWrapperDoc`: Parses TOML text and wraps query details inside an easy helper.
  - **Returned Type**: `TOMLWrapperDoc` supports `doc.get_string(key)`, `doc.get_string_default(key, def)`, `doc.get_int(key)`, and `doc.get_bool(key)`.
- `win.json_decode_map(json_str string) map[string]string`: Deserializes a JSON string into a flat key-value map.
- `win.json_decode_map_strict(json_str string) !map[string]string`: Deserializes a JSON string into a flat key-value map and returns explicit errors for malformed JSON.
- `win.json_encode_map_list(m []map[string]string) string`: Serializes an array of maps into a JSON string.
- `win.json_decode_map_list(json_str string) []map[string]string`: Deserializes a JSON string into an array of flat key-value maps.
- `win.json_validate(json_str string) bool`: Checks if a string contains valid JSON syntax.
- `win.json_pretty_print(json_str string) string`: Formats a flat key-value map JSON string with clean line indents.

### WebSocket Client (`net.websocket`)

- `win.websocket_client(url string, on_msg SimpleWSMessageCallback) ?&SimpleWSClient`: Spawns a WebSocket client on a background thread.
  - **Returned Type**: `SimpleWSClient` supports:
    - `ws.write_string(msg string) !`: Sends a text payload to the active WebSocket server.
    - `ws.close()`: Cleanly disconnects from the remote WebSocket server.

### Stopwatch Utility (`time`)

- `win.start_stopwatch() &SimpleStopwatch`: Constructs and starts a new high-precision stopwatch.
  - **Returned Type**: `SimpleStopwatch` supports:
    - `sw.elapsed_ms() int`: Returns elapsed duration in milliseconds.
    - `sw.elapsed_sec() f64`: Returns elapsed duration in seconds.
    - `sw.restart()`: Resets and restarts the stopwatch in-place.

### System Clipboard (`clipboard`)

- `win.clipboard_copy(text string) bool`: Copies the specified text to the system clipboard.
- `win.clipboard_read() string`: Pastes and returns the text content from the system clipboard.

### Benchmark & Execution Timing (`benchmark`)

- `win.start_benchmark() SimpleBenchmark`: Starts timing code execution blocks.
- `win.new_benchmark() SimpleBenchmark`: Prepares a new benchmark tracker.
- **Returned Type**: `SimpleBenchmark` supports:
  - `sb.measure(label string)`: Adds a timing marker point.
  - `sb.step()`: Progresses to the next step.
  - `sb.ok()`: Flags the current step as successful.
  - `sb.fail()`: Flags the current step as failed.
  - `sb.step_message(label string) string`: Retrieves step duration detail message.
  - `sb.total_message(label string) string`: Retrieves full benchmark overview.
  - `sb.stop()`: Halts benchmark timing.

### Network Sockets (TCP, UDP, Unix Domain Clients)

- `win.tcp_connect(address string) !SimpleTCPClient`: Connects a TCP client to the host (e.g. `'127.0.0.1:8080'`).
  - **Returned Type**: `SimpleTCPClient` supports:
    - `s.write(data string) !`: Sends string data to the remote host.
    - `s.read() !string`: Reads available incoming string data.
    - `s.close()`: Closes the active client connection.

- `win.udp_connect(address string) !SimpleUDPClient`: Connects a UDP socket to a remote endpoint.
  - **Returned Type**: `SimpleUDPClient` supports:
    - `s.write(data string) !`: Dispatches datagram packet data.
    - `s.read() !string`: Reads incoming datagram packet data.
    - `s.close()`: Closes the active socket.

- `win.unix_connect(path string) !SimpleUnixClient`: Connects a client to a local Unix domain socket file.
  - **Returned Type**: `SimpleUnixClient` supports:
    - `s.write(data string) !`: Sends data over the Unix socket.
    - `s.read() !string`: Reads data from the Unix socket.
    - `s.close()`: Closes the active stream connection.

### HTML Parser (`net.html`)

- `win.html_parse(content string) SimpleHTMLDocument`: Parses HTML string content into a queryable Document Object Model (DOM).
  - **Returned Type**: `SimpleHTMLDocument` supports:
    - `d.get_tag_text(name string) string`: Extracts trimmed inner text of the first matching tag name.
    - `d.get_tags_by_class(class_name string) []string`: Extracts trimmed inner text of all tags matching a class name.
    - `d.get_attr(tag_name string, attr_name string) string`: Retrieves attribute value of the first matching HTML tag name.
    - `d.get_all_links() []string`: Extracts all `href` links from `<a>` tags in the document.
    - `d.get_all_images() []string`: Extracts all image `src` URLs from `<img>` tags in the document.
    - `d.strip_tags() string`: Removes HTML tags from the document, returning plain text content.

### Placeholder Text Generator (`strings.lorem`)

- `win.lorem_generate(corpus_name string, paragraphs int, sentences int, words int) string`: Generates pseudo-random placeholder paragraphs based on Markov chains from corpora.
  - **Parameters**:
    - `corpus_name`: Choice of `'lorem'` (Latin), `'poe'` (Edgar Allan Poe), `'darwin'` (Charles Darwin), or `'bard'` (William Shakespeare).
    - `paragraphs`: Number of paragraphs.
    - `sentences`: Number of sentences per paragraph.
    - `words`: Number of words per sentence.

### Console Text Styling (`term`)

- `win.term_color(text string, style string) string`: Styles console text outputs (supports `'red'`, `'green'`, `'blue'`, `'yellow'`, `'bold'`, `'underline'`).

### Standard Collections & Datatypes (`datatypes`)

`simplegui` provides high-level generic LIFO (Stack), FIFO (Queue), Set, Ring Buffer, and MinHeap collections:

- `simplegui.new_stack[T]() SimpleStack[T]`: Instantiates a new LIFO stack.
  - `stack.push(item T)`: Pushes an element onto the stack.
  - `stack.pop() !T`: Pops and returns the top element from the stack.
  - `stack.peek() !T`: Returns the top element without removing it.
  - `stack.len() int`: Returns the number of items in the stack.
  - `stack.is_empty() bool`: Reports whether the stack has no items.

- `simplegui.new_queue[T]() SimpleQueue[T]`: Instantiates a new FIFO queue.
  - `queue.push(item T)`: Enqueues an element.
  - `queue.pop() !T`: Dequeues and returns the front element.
  - `queue.peek() !T`: Returns the front element without removing it.
  - `queue.len() int`: Returns the number of items in the queue.
  - `queue.is_empty() bool`: Reports whether the queue has no items.

- `simplegui.new_set[T]() SimpleSet[T]`: Instantiates a new unique set collection.
  - `set.add(item T)`: Adds an item to the set if not already present.
  - `set.remove(item T)`: Removes an item from the set.
  - `set.exists(item T) bool`: Reports whether an item is in the set.
  - `set.len() int`: Returns the number of unique items.
  - `set.is_empty() bool`: Reports whether the set has no items.
  - `set.to_array() []T`: Exports set items as a standard V array.

- `simplegui.new_ringbuffer[T](capacity int) SimpleRingBuffer[T]`: Instantiates a new ring buffer with a fixed capacity.
  - `rb.push(item T) !`: Pushes an item to the buffer, returning an error if full.
  - `rb.pop() !T`: Pops and returns the oldest item, returning an error if empty.
  - `rb.len() int`: Returns the number of occupied slots in the buffer.
  - `rb.capacity() int`: Returns the total capacity.
  - `rb.is_empty() bool`: Reports whether the buffer has no items.
  - `rb.is_full() bool`: Reports whether the buffer is fully occupied.

- `simplegui.new_min_heap[T]() SimpleMinHeap[T]`: Instantiates a new generic min-heap priority queue.
  - `smh.push(item T)`: Inserts an item into the min-heap.
  - `smh.pop() !T`: Removes and returns the smallest item from the min-heap.
  - `smh.peek() !T`: Returns the smallest item without removing it.
  - `smh.len() int`: Returns total number of items stored in the min-heap.

### Complex Number Arithmetic (`math.complex`)

- `win.complex_new(re f64, im f64) SimpleComplex` / `simplegui.complex_new(re f64, im f64) SimpleComplex`: Constructs a 2D complex number (real + imaginary).
- **Returned Type**: `SimpleComplex` supports:
  - `c.add(other SimpleComplex) SimpleComplex`: Adds two complex numbers.
  - `c.sub(other SimpleComplex) SimpleComplex`: Subtracts two complex numbers.
  - `c.mul(other SimpleComplex) SimpleComplex`: Multiplies two complex numbers.
  - `c.div(other SimpleComplex) SimpleComplex`: Divides two complex numbers.
  - `c.abs() f64`: Returns magnitude/modulus of complex number.
  - `c.arg() f64`: Returns phase angle in radians.
  - `c.conj() SimpleComplex`: Returns complex conjugate.
  - `c.exp() SimpleComplex`: Computes $e^z$.
  - `c.str() string`: Formats complex number as string (`re + im i`).

### Math & Trigonometry (`math`)

- `win.math_sin(x f64) f64`: Returns the sine of a radian value.
- `win.math_cos(x f64) f64`: Returns the cosine of a radian value.
- `win.math_tan(x f64) f64`: Returns the tangent of a radian value.
- `win.math_sqrt(x f64) f64`: Returns the square root of a non-negative floating-point number.
- `win.math_pow(base f64, exp f64) f64`: Returns `base` raised to the power `exp`.
- `win.math_abs(x f64) f64`: Returns the absolute value of a floating-point number.
- `win.math_clamp(val f64, min f64, max f64) f64`: Constrains a value within a specified `min` and `max` range.
- `win.math_round(x f64) f64`: Rounds a floating-point number to the nearest integer.
- `win.math_floor(x f64) f64`: Returns the greatest integer less than or equal to `x`.
- `win.math_ceil(x f64) f64`: Returns the least integer greater than or equal to `x`.
- `win.math_degrees(radians f64) f64`: Converts radians to degrees.
- `win.math_radians(degrees f64) f64`: Converts degrees to radians.
- `win.math_hypot(x f64, y f64) f64`: Computes Euclidean distance $\sqrt{x^2 + y^2}$.
- `win.math_gcd(a i64, b i64) i64`: Returns greatest common divisor of two integers.
- `win.math_lcm(a i64, b i64) i64`: Returns least common multiple of two integers.
- `win.math_remap(x f64, in_min f64, in_max f64, out_min f64, out_max f64) f64`: Maps a value from range `[in_min, in_max]` to target range `[out_min, out_max]`.
- `win.math_smoothstep(edge0 f64, edge1 f64, x f64) f64`: Computes smooth Hermite interpolation between 0 and 1.
- `win.math_atan2(y f64, x f64) f64`: Computes multi-valued arctangent $\text{atan2}(y, x)$.
- `win.math_log10(x f64) f64`: Returns base-10 logarithm of a positive floating point number.
- `win.math_log2(x f64) f64`: Returns base-2 logarithm of a positive floating point number.
- `win.math_round_sig(x f64, sig_digits int) f64`: Rounds a floating point number to a given number of significant digits.

### Statistical Analysis (`math.stats`)

- `win.stats_mean(data []f64) f64`: Computes the arithmetic mean of a floating-point dataset.
- `win.stats_median(data []f64) f64`: Computes the median value of a floating-point dataset.
- `win.stats_geometric_mean(data []f64) f64`: Computes the geometric mean of a dataset.
- `win.stats_harmonic_mean(data []f64) f64`: Computes the harmonic mean of a dataset.
- `win.stats_rms(data []f64) f64`: Computes the Root Mean Square (quadratic mean) of a dataset.
- `win.stats_mode(data []f64) f64`: Returns the most frequent value in a dataset.
- `win.stats_range(data []f64) f64`: Returns the difference between maximum and minimum values in a dataset.
- `win.stats_kurtosis(data []f64) f64`: Computes the sample kurtosis of a dataset.
- `win.stats_skew(data []f64) f64`: Computes the sample skewness of a dataset.
- `win.stats_covariance(data1 []f64, data2 []f64) f64`: Computes sample covariance between two equal-sized datasets.
- `win.stats_sample_variance(data []f64) f64`: Computes the sample variance of a dataset.
- `win.stats_sample_std_dev(data []f64) f64`: Computes the sample standard deviation of a dataset.
- `win.stats_population_variance(data []f64) f64`: Computes the population variance of a dataset.
- `win.stats_population_std_dev(data []f64) f64`: Computes the population standard deviation of a dataset.

### Arbitrary-Precision BigInteger Math (`math.big`)

- `win.big_int_from_int(v int) SimpleBigInt`: Constructs a `SimpleBigInt` from an integer value.
- `win.big_int_from_str(s string) SimpleBigInt`: Constructs a `SimpleBigInt` from a decimal string.
- **Returned Type**: `SimpleBigInt` supports:
  - `b.add(other SimpleBigInt) SimpleBigInt`: Returns the sum of two BigInts.
  - `b.sub(other SimpleBigInt) SimpleBigInt`: Returns the difference of two BigInts.
  - `b.mul(other SimpleBigInt) SimpleBigInt`: Returns the product of two BigInts.
  - `b.div(other SimpleBigInt) SimpleBigInt`: Returns the quotient of two BigInts.
  - `b.mod(other SimpleBigInt) SimpleBigInt`: Returns the remainder of two BigInts.
  - `b.str() string`: Formats the BigInt as a decimal string representation.

### Array Processing Utilities (`arrays`)

- `win.array_min(arr []int) int`: Returns the minimum value in an integer array (or `0` if empty).
- `win.array_max(arr []int) int`: Returns the maximum value in an integer array (or `0` if empty).
- `win.array_min_f64(arr []f64) f64`: Returns the minimum value in a float array (or `0.0` if empty).
- `win.array_max_f64(arr []f64) f64`: Returns the maximum value in a float array (or `0.0` if empty).
- `win.array_sum(arr []int) int`: Computes the sum of all elements in an integer array.
- `win.array_sum_f64(arr []f64) f64`: Computes the sum of all elements in a float array.
- `win.array_unique_strings(arr []string) []string`: Deduplicates string array values while preserving original insertion order.

### UTF-8 String Utilities (`encoding.utf8`)

- `win.utf8_len(text string) int`: Returns the total number of UTF-8 code points/characters in a string.
- `win.utf8_is_valid(text string) bool`: Validates whether a string contains valid UTF-8 character encoding.

### String Distance, Metrics & Utilities (`strings`)

- `win.string_levenshtein(s1 string, s2 string) int`: Computes the Levenshtein edit distance between two strings.
- `win.string_jaro_similarity(s1 string, s2 string) f64`: Computes Jaro distance similarity between two strings (0.0 to 1.0).
- `win.string_jaro_winkler_similarity(s1 string, s2 string) f64`: Computes Jaro-Winkler distance similarity (0.0 to 1.0).
- `win.string_hamming_distance(s1 string, s2 string) int`: Computes Hamming distance between two equal-length strings.
- `win.string_between(input string, start string, end string) string`: Extracts text located between start and end delimiter strings.
- `win.string_pad_left(input string, length int, pad_char string) string`: Left-pads string with repeating pad_char characters up to length.
- `win.string_pad_right(input string, length int, pad_char string) string`: Right-pads string with repeating pad_char characters up to length.
- `win.string_repeat(input string, count int) string`: Repeats string `count` times.
- `win.string_count_words(input string) int`: Counts non-empty space-separated words in a text string.
- `win.new_string_builder() SimpleStringBuilder`: Constructs an efficient, growable string buffer builder.
- **Returned Type**: `SimpleStringBuilder` supports:
  - `sb.write(text string)`: Appends text to the string builder.
  - `sb.write_line(text string)`: Appends text followed by a newline.
  - `sb.str() string`: Returns the complete accumulated string content.
  - `sb.len() int`: Returns the byte length of accumulated content.

### CSV Matrix Operations (`encoding.csv`)

- `win.csv_parse(content string) [][]string`: Parses a CSV formatted string into a 2D matrix of row/column strings.
- `win.csv_encode(rows [][]string) string`: Serializes a 2D matrix of strings into RFC-4180 compliant CSV text with automatic quote escaping.
- `win.csv_extract_column(rows [][]string, col_idx int) []string`: Extracts all row values belonging to a specific zero-based column index.
- `win.csv_filter_by_column(rows [][]string, col_idx int, search_term string) [][]string`: Returns only CSV rows where a column matches a given search string.

### Ed25519 Digital Signatures (`crypto.ed25519`)

- `win.crypto_ed25519_generate_key() !SimpleEd25519KeyPair`: Generates a new Ed25519 public/private key pair.
- `win.crypto_ed25519_sign(priv_key []u8, msg string) ![]u8`: Signs a string payload using an Ed25519 private key.
- `win.crypto_ed25519_verify(pub_key []u8, msg string, sig []u8) bool`: Verifies an Ed25519 signature against a public key and message.

### Password-Based Key Derivation (`crypto.pbkdf2`)

- `win.crypto_pbkdf2(password string, salt string, iterations int, key_len int) []u8`: Derives cryptographic keys from a password and salt using PBKDF2 with HMAC-SHA256.

### Thread Synchronization Primitives (`sync`)

- `win.new_mutex() SimpleMutex`: Constructs a thread-safe mutex lock wrapper.
  - `m.lock()`: Acquires the lock (blocking).
  - `m.unlock()`: Releases the lock.
- `win.new_wait_group() SimpleWaitGroup`: Constructs a thread synchronization counter.
  - `wg.add(delta int)`: Increments counter by `delta`.
  - `wg.done()`: Decrements counter by 1.
  - `wg.wait()`: Blocks until counter reaches zero.

---

## 7. List Box & Image View Operations

### `win.update_list_items(name string, items []string) &SimpleWindow`

Updates the entire set of rows displayed inside the list box. This is useful for search filters or dynamic updates.

### `win.set_list_selected(name string, index int) &SimpleWindow`

Sets the selected row index in the list box.

### `win.get_list_selected(name string) int`

Returns the 0-indexed selected row in the list box (or `-1` if no row is selected).

### `win.set_image_path(name string, file_path string) &SimpleWindow`

Updates the active image shown in the specified image view control.

---

## 8. Scheduled Timers & Delays

### `win.set_interval(timer_name string, ms int, callback VoidEventCallback) &SimpleWindow`

Starts a recurring main-loop timer that triggers the callback function every `N` milliseconds.

- **Timer Callbacks**: Attaches to `timer_name` trigger. Callback is executed on main V thread.

### `win.stop_interval(timer_name string) &SimpleWindow`

Cancels and invalidates the active interval timer.

### `win.run_after(ms int, callback VoidEventCallback) &SimpleWindow`

Schedules a one-shot delay, executing the callback once after `ms` milliseconds have elapsed.

---

## 9. Reading & Writing Values

### `win.get_text(name string) string`

Reads the string value of any text input, textarea, label, color well, popup, or date picker (including list boxes, returning the text of the selected row).

### `win.get(name string) string`

Beginner-friendly shorthand alias for `win.get_text(name)`.

### `win.get_as[T](name string) T`

Helper method to fetch any control value in its native type. Supports fetching strings (`string`), booleans (`bool`), integers (`int`), and floats (`f64`).

### `win.set_text(name string, text string) &SimpleWindow`

Sets/updates the text content of any input, textarea, or label.

### `win.set[T](name string, value T) &SimpleWindow`

Beginner-friendly, generic shorthand method to set or update any control value (replaces the older non-generic `win.set`). Automatically routes to `set_text`, `set_bool`, `set_number_value`, or `set_float` based on the compile-time type of `T` (via type inference).

### `win.get_checked(name string) bool`

Gets the toggle state of a checkbox.

### `win.set_checked(name string, checked bool) &SimpleWindow`

Sets the toggle state of a checkbox.

### `win.get_value_int(name string) int`

Gets the integer value of a slider, progress bar, list box selected index, or number/stepper input.

### `win.set_value_int(name string, value int) &SimpleWindow`

Sets the integer value of a slider, progress bar, list box selected index, or number/stepper input.

### `win.get_status() string`

Reads the current text value of the window status footer.

### `win.set_status(text string) &SimpleWindow`

Updates the text display of the window status footer.

### `win.status(text string) &SimpleWindow`

Alias for `set_status(text)`, updating the window status footer.

### `win.clear(name string) &SimpleWindow`

Clears the value of a specific named control. Text inputs and textareas are set to `""`, checkboxes to `false`, and numeric controls to `0`.

### `win.clear_all() &SimpleWindow`

Clears all input controls in the window.

### `win.reset_form() &SimpleWindow`

Resets all form input controls back to their initial/default values at registration time.

### Name-based generic control accessors

For advanced operations, these methods bypass type assumptions and directly set or read the primitive state value of controls by their named handles:

- **`win.get_value(name string) string`** / **`win.set_value(name string, value string) &SimpleWindow`**: Directly sets or retrieves the raw string value representing any text/HTML-based control content.
- **`win.get_bool(name string) bool`** / **`win.set_bool(name string, checked bool) &SimpleWindow`**: Gets or sets the toggle boolean state of checkbox and switch controls.
- **`win.get_number_value(name string) int`** / **`win.set_number_value(name string, value int) &SimpleWindow`**: Gets or sets the primitive integer value of sliders, progress bars, list boxes selection/indexing, or numeric box steppers.

---

## 10. Event Handling

Callbacks can be attached to any interactive control.

### `win.on_click(name string, callback VoidEventCallback) &SimpleWindow`

Attaches an event handler for button click events.

- **Callback Signature**: `fn (mut win &SimpleWindow)`

### `win.on_change(name string, callback StringEventCallback) &SimpleWindow`

Attaches an event handler for input changes (inputs, checkboxes, sliders, dropdowns, segmented controls, list boxes).

- **Callback Signature**: `fn (mut win &simplegui.SimpleWindow, value string)`

### `win.on_hover(name string, callback VoidEventCallback) &SimpleWindow`

Attaches an event handler when the mouse pointer enters the bounding area of the control.

### `win.on_hover_exit(name string, callback VoidEventCallback) &SimpleWindow`

Attaches an event handler when the mouse pointer exits the bounding area of the control.

### `win.on_focus(name string, callback VoidEventCallback) &SimpleWindow`

Attaches an event handler when a text field input control gains active focus.

### `win.on_blur(name string, callback VoidEventCallback) &SimpleWindow`

Attaches an event handler when a text field input control loses focus.

### `win.on_enter(name string, callback VoidEventCallback) &SimpleWindow`

Attaches an event handler triggered when the Enter/Return key is pressed inside a text input field.

### `win.on_key(key string, callback StringEventCallback) &SimpleWindow`

Attaches a global window-wide keyboard shortcut event listener. The callback value receives the key string.

### `win.on_close(callback VoidEventCallback) &SimpleWindow`

Attaches an event handler executed right before the window is closed and terminated.

### `win.on_resize(callback StringEventCallback) &SimpleWindow`

Attaches an event handler when the application window is resized by the user.

- **Callback Signature**: `fn (mut win &simplegui.SimpleWindow, new_size string)` (where `new_size` has format `"widthxheight"`, e.g. `"640x480"`)

### `win.on_window_focus(callback VoidEventCallback) &SimpleWindow`

Attaches an event handler triggered when the application window gains focus (becomes key).

### `win.on_window_blur(callback VoidEventCallback) &SimpleWindow`

Attaches an event handler triggered when the application window loses focus (resigns key).

### `win.on_window_minimize(callback VoidEventCallback) &SimpleWindow`

Attaches an event handler triggered when the window is minimized / miniaturized to the macOS Dock.

### `win.on_window_restore(callback VoidEventCallback) &SimpleWindow`

Attaches an event handler triggered when the window is restored / deminiaturized from the macOS Dock.

### `win.on_file_drop(callback FileDropCallback) &SimpleWindow`

Attaches an event handler when files are dragged and dropped onto the window or onto a drop zone control.

- **Callback Signature**: `fn (mut win &simplegui.SimpleWindow, files []string)`

### `win.on_change_step(name string, callback StringEventCallback) &SimpleWindow`

Attaches an event handler for wizard stepper step changes (`"change_step"`).

- **Callback Signature**: `fn (mut win &simplegui.SimpleWindow, step string)`

### `win.on_click_tag(name string, callback StringEventCallback) &SimpleWindow`

Attaches an event handler for tag cloud chip clicks (`"click_tag"`).

- **Callback Signature**: `fn (mut win &simplegui.SimpleWindow, tag string)`

### `win.on_select_item(name string, callback StringEventCallback) &SimpleWindow`

Attaches an event handler for split button menu item selection (`"select_item"`).

- **Callback Signature**: `fn (mut win &simplegui.SimpleWindow, item string)`

---

## 11. Custom Application Menus & Context Menus

Custom top-level menus appear in the macOS menu bar between the application menu and the standard `Edit`/`Window` menus. Menus can be registered at any time — including before `win.run()` — and become visible as soon as the app launches.

### `win.add_menu_item(menu_name string, item_title string, shortcut string, callback VoidEventCallback) &SimpleWindow`

Adds a custom drop-down menu item under the main macOS application menu bar (e.g. under a custom menu tab like "Actions"). Binds the menu click action directly to the callback.

- **Shortcut format**: modifier tokens joined with `+`, e.g. `'cmd+s'`, `'cmd+shift+s'`, `'ctrl+alt+d'`. Supported modifiers: `cmd`/`command`, `ctrl`/`control`, `opt`/`option`/`alt`, `shift`. Special keys: `return`/`enter`, `escape`/`esc`, `space`. Pass `''` for no shortcut.
- Passing `'-'` as `item_title` inserts a native separator line.

### `win.add_context_menu_item(control_name string, item_title string, callback VoidEventCallback) &SimpleWindow`

Binds a native right-click Context Menu item directly to any control by its `name` handle (or `"window"` to bind it to the general window background). Clicking the triggered choice executes the callback function.

### `win.add_menu(menu_name string, items []MenuItem) &SimpleWindow`

Creates a structured drop-down menu bar hierarchy. Supports native separators when `MenuItem.title` is `"-"`.

```v ignore
win.add_menu('Demo', [
    simplegui.MenuItem{
        title:    'Show Snapshot'
        shortcut: 'cmd+shift+s'
        callback: fn (mut w simplegui.SimpleWindow) {
            w.set_status('Snapshot triggered from the Demo menu')
        }
    },
    simplegui.MenuItem{
        title: '-' // separator
    },
    simplegui.MenuItem{
        title:    'Reset Status'
        callback: fn (mut w simplegui.SimpleWindow) {
            w.set_status('Ready.')
        }
    },
])
```

**Note**: When the app runs in status-bar accessory mode (after `enable_status_bar`), menu items are added to the status bar dropdown menu instead of the main menu bar.

### `win.add_context_menu(control_name string, items []MenuItem) &SimpleWindow`

Creates a structured right-click Context Menu on any control or the general `"window"`.

---

## 12. Multi-Column Table / Data Grid

### `win.add_table(name string, columns []string) &SimpleWindow`

Adds a scrollable multi-column table view widget with column headers.

### `win.set_table_rows(name string, rows [][]string) &SimpleWindow`

Updates the entire set of row cells displayed inside the table grid.

### `win.load_table_from_structs[T](name string, items []T) &SimpleWindow`

Populates and renders a scrollable multi-column table widget automatically using field names and values from an array of V structs of generic type `T`. Supports compile-time reflection of `string`, `int`, and `bool` fields.

Table rows are tracked automatically on the V side, enabling the incremental row-management, selection, and event helpers described in [Section 17](#17-ergonomic-helpers) (`add_table_row`, `remove_selected_table_rows`, `on_table_select`, `on_table_double_click`, and more).

### `win.add_grid(name string, headers []string, initial_rows [][]string) &SimpleWindow`

Adds a native editable data grid for spreadsheet-style layouts. It supports inline editing, persistent selection, checkbox and button cell types, column resizing, filtering, and programmatic sorting.

### Grid row and column management

- `win.grid_add_row(name, row_values)` appends a new row.
- `win.grid_delete_row(name, row_idx)` removes a row by index.
- `win.grid_add_column(name, header)` appends a new column.
- `win.grid_delete_column(name, col_idx)` removes a column by index.
- `win.grid_get_rows(name)` and `win.grid_set_rows(name, rows)` read or replace the entire dataset.
- `win.grid_get_row(name, row_idx)` / `win.grid_set_row(name, row_idx, values)` and `win.grid_get_column(name, col_idx)` / `win.grid_set_column(name, col_idx, values)` support common spreadsheet-style row and column updates.

### Grid cell and selection helpers

- `win.grid_set_cell(name, row, col, value)` and `win.grid_get_cell(name, row, col)` read and write individual cells.
- `win.grid_get_selected_row(name)` and `win.grid_get_selected_column(name)` return the current selection coordinates, and `win.grid_get_selected_cell(name)` returns them as a `(row, column)` pair.
- `win.grid_set_selected_row(name, row_idx)`, `win.grid_set_selected_column(name, col_idx)`, and `win.grid_set_selected_cell(name, row, col)` select rows, columns, or cells programmatically.
- `win.grid_get_row_values(name, row_idx)` and `win.grid_get_column_values(name, col_idx)` return the current contents of a row or column.
- `win.grid_set_row_values(name, row_idx, values)` and `win.grid_set_column_values(name, col_idx, values)` update entire rows or columns.

### Grid filtering, sorting, and display options

- `win.grid_set_filter(name, query)` and `win.grid_clear_filter(name)` filter visible rows by cell contents.
- `win.grid_sort_by_column(name, col_idx, ascending)` sorts the current grid data by a specific column. Column header clicks select the column and keep sorting explicit via the API.
- `win.grid_set_column_type(name, col_idx, type)` controls rendering for text, checkbox, or button cells.
- `win.grid_set_column_width(name, col_idx, width)` and `win.grid_set_row_height(name, height)` adjust sizing.
- `win.grid_autosize_columns(name)` resizes columns to fit their current content.

### Grid editability and enabled-state helpers

- `win.grid_set_column_editable(name, col_idx, editable)` / `win.grid_get_column_editable(name, col_idx)`
- `win.grid_set_row_editable(name, row_idx, editable)` / `win.grid_get_row_editable(name, row_idx)`
- `win.grid_set_cell_editable(name, row, col, editable)` / `win.grid_get_cell_editable(name, row, col)`
- `win.grid_set_column_enabled(name, col_idx, enabled)` / `win.grid_get_column_enabled(name, col_idx)`
- `win.grid_set_row_enabled(name, row_idx, enabled)` / `win.grid_get_row_enabled(name, row_idx)`
- `win.grid_set_cell_enabled(name, row, col, enabled)` / `win.grid_get_cell_enabled(name, row, col)`

### Grid event hooks

- `on_change('grid_name', handler)` fires when the grid contents or selection changes.
- `on_column_click('grid_name', handler)` fires when a column is selected.
- `on_cell_button_click('grid_name', handler)` fires when a button-style cell is clicked.

---

## 12b. Hierarchical Tree View

### `TreeNode` struct

Describes a single node in the tree hierarchy:

- `id`: Unique identifier string.
- `parent_id`: ID of parent node (leave empty `""` for root nodes).
- `text`: Label/text displayed for the node.

```v
pub struct TreeNode {
pub mut:
    id        string
    parent_id string
    text      string
}
```

### Tree node constructors

- `simplegui.tree_node(id, parent_id, text)` creates a node with explicit fields.
- `simplegui.tree_root(id, text)` creates a root node (`parent_id == ""`).
- `simplegui.tree_child(id, parent_id, text)` creates a child node.
- `simplegui.tree_nodes_from_paths(paths, separator)` converts path strings into nodes.

Example:

```v
nodes := simplegui.tree_nodes_from_paths([
  'Company/Engineering/Frontend',
  'Company/Engineering/Backend',
  'Company/Design/UX',
], '/')
```

### `win.add_tree_view(name string, height int) &SimpleWindow`

Adds a scrollable, native hierarchal tree view control with a defined vertical height.

### `win.set_tree_nodes(name string, nodes []TreeNode) &SimpleWindow`

Builds and populates the tree hierarchy from a flat array of nodes. It automatically resolves parent-child relations and expands the nodes by default.

### `win.get_tree_selected(name string) string`

Returns the `id` of the currently selected tree view node, or `""` if no cell is selected.

### `win.set_tree_selected(name string, node_id string) &SimpleWindow`

Programmatically expands parent items as needed, selects the specified node by its `node_id`, and scrolls it into view.

### `win.expand_tree(name string) &SimpleWindow` / `win.open_tree(name string) &SimpleWindow`

Expands all nodes in the target tree.

### `win.collapse_tree(name string) &SimpleWindow` / `win.close_tree(name string) &SimpleWindow`

Collapses all nodes in the target tree.

### `win.expand_tree_node(name string, node_id string, expand_children bool) &SimpleWindow`

Expands a specific node by id. If `expand_children` is `true`, expands the full subtree under that node.

### `win.collapse_tree_node(name string, node_id string, collapse_children bool) &SimpleWindow`

Collapses a specific node by id. If `collapse_children` is `true`, collapses all descendants too.

### `win.set_tree(name string, nodes []TreeNode) &SimpleWindow`

Alias for `set_tree_nodes(...)`.

### `win.clear_tree(name string) &SimpleWindow`

Clears all nodes and current selection in a single call.

### `win.clear_tree_selection(name string) &SimpleWindow`

Clears only the current selected node.

### `win.get_tree_nodes(name string) []TreeNode`

Returns a copy of nodes currently registered for that tree in V-side state.

### `win.has_tree_node(name string, node_id string) bool`

Returns true if `node_id` exists in the tree.

### `win.get_tree_node(name string, node_id string) ?TreeNode`

Returns the matching node when found, otherwise `none`.

### `win.add_tree_node(name string, node TreeNode) &SimpleWindow`

Adds a new node or updates an existing one with the same `id`.

### `win.remove_tree_node(name string, node_id string, remove_children bool) &SimpleWindow`

Removes a node.

- If `remove_children` is `true`, removes the full subtree.
- If `false`, reparents direct children to the removed node's parent.

### `win.set_tree_node_text(name string, node_id string, text string) &SimpleWindow`

Updates the visible label text of one node.

### `win.set_tree_paths(name string, paths []string) &SimpleWindow`

Builds/replaces a tree from slash-separated path values.

### `win.set_tree_paths_with_separator(name string, paths []string, separator string) &SimpleWindow`

Same as `set_tree_paths`, but with a custom path separator.

---

---

## 13. Bulk Data Binding

### `win.get_values() map[string]string`

Serializes and returns a map containing all input control names matched to their current text values.

### `win.set_values(values map[string]string) &SimpleWindow`

Sets/updates multiple control text values from a name-value map.

### `win.inspect_controls() string`

Returns a comma-separated string containing the names of all currently registered controls.

### `win.dump_values() map[string]string`

Alias for `get_values()`, serializing all form inputs to a name-value string map.

### `win.bind_to_struct[T](mut data T) &SimpleWindow`

Queries all input control values and populates the matching field names on a mutable struct using compile-time reflection. Supports `string`, `int`, and `bool` fields.

### `win.load_from_struct[T](data T) &SimpleWindow`

Populates GUI controls using matching field name values from the passed struct.

---

## 14. Layout Spacers & Visual Separators

### `win.add_vertical_spacer(height int) &SimpleWindow`

Inserts an empty spacing box of the specified height in the layout stack.

### `win.add_horizontal_spacer(width int) &SimpleWindow`

Inserts an empty spacing box of the specified width in horizontal layout rows.

### `win.add_separator() &SimpleWindow`

Draws a native horizontal visual line divider.

---

## 15. System Status Tray Mode & Thread Safety

### `win.enable_status_bar(icon_path string) &SimpleWindow`

Hides the main window and runs the application as a background macOS menu bar accessory with a dropdown status menu.

### `win.show_window() &SimpleWindow`

Restores window visibility and brings the window to the front.

### `win.run_on_main_thread(callback VoidEventCallback) &SimpleWindow`

Safely queues a UI update callback to execute on the main event thread, bridging background execution threads.

### `win.run_async(bg_task fn (), on_complete VoidEventCallback) &SimpleWindow`

Runs a time-consuming background or I/O task on a separate concurrent worker thread to keep the application window fully responsive. Upon completion, automatically dispatches the `on_complete` callback on the main thread for thread-safe UI updates.

---

## 16. Form Change & Dirty Tracking

You can track if users have modified form fields compared to their baseline state:

### `win.is_dirty() bool`

Returns `true` if any input control (text input, checkbox, toggle, slider, number) has a value different from its last committed baseline state.

### `win.is_control_dirty(name string) bool`

Returns `true` if the specific named control has changed compared to its baseline state.

### `win.commit_changes() &SimpleWindow`

Sets the current control values as the new baseline state, causing `is_dirty()` to reset to `false` without needing to reload the window. Typical use case is after a successful save action.

---

## 17. Ergonomic Helpers

A set of high-level shortcuts designed to make everyday tasks one-liners. See `demos/easy_api_demo.v`, `demos/todo_list_demo.v`, `demos/table_manager_demo.v`, `demos/save_restore_demo.v`, and `demos/ergonomics_helpers_demo.v` for working examples.

### Dialog & File Panel Shortcuts

- `win.info(title string, message string) &SimpleWindow` shows an informational alert.
- `win.warn(title string, message string) &SimpleWindow` shows a warning-styled alert.
- `win.error_dialog(title string, message string) &SimpleWindow` shows a critical error-styled alert.
- `win.ask(title string, question string) bool` shows a Yes/No confirmation and returns `true` when confirmed.
- `win.choose(title string, message string, choices []string) int` shows a Choice/Dropdown dialog box and returns the selected 0-based option index.
- `win.ask_text(title string, message string, default_val string) string` prompts the user for text input with a dialog box, returning the response.
- `win.choose_file() string` opens a native file dialog selection panel.
- `win.choose_file_ext(extensions string) string` opens a native file dialog selection panel filtered by file extensions.
- `win.choose_folder() string` opens a native directory selection panel.
- `win.choose_save_file() string` opens a native save file dialog panel.
- `win.quit()` terminates the application event loop immediately.

### Batch Control Operations

- `win.show_controls(names []string)` / `win.hide_controls(names []string)` toggle visibility for many controls at once.
- `win.enable_controls(names []string)` / `win.disable_controls(names []string)` toggle interactivity for many controls at once.
- `win.enable_all_controls()` / `win.disable_all_controls()` affect every registered control (e.g. lock the UI while processing).
- `win.toggle_visible(name string) bool` flips visibility and returns the new state.
- `win.toggle_enabled(name string) bool` flips the enabled state and returns the new state.

### Value Convenience Accessors

- `win.get_int(name) int` / `win.set_int(name, value)` — aliases for numeric and text controls (auto-parsed if text).
- `win.get_float(name) f64` / `win.set_float(name, value)` — parse/write floating point values in text controls.
- `win.get_int_or(name string, fallback int) int` — gets integer value or returns a fallback if invalid or empty.
- `win.get_float_or(name string, fallback f64) f64` — gets float value or returns a fallback if invalid or empty.
- `win.get_text_or(name string, fallback string) string` — gets text value or returns a fallback if empty.
- `win.increment(name string, delta int) int` adds `delta` (may be negative) to a numeric control and returns the new value.
- `win.toggle_checked(name string) bool` flips a checkbox/switch and returns the new state.
- `win.set_progress(name, value)` / `win.get_progress(name) int` — friendly progress bar accessors.
- `win.append_text(name, text)` appends text to a text-based control.
- `win.append_line(name, line)` appends a new line to a textarea (perfect for activity logs).
- `win.set_many_texts(values map[string]string)` updates many text-based controls in one call.
- `win.get_many_texts(names []string) map[string]string` reads many text-based controls into a map.
- `win.set_many_checked(values map[string]bool)` updates many checkbox/switch controls in one call.
- `win.get_many_checked(names []string) map[string]bool` reads many checkbox/switch controls into a map.
- `win.set_many_numbers(values map[string]int)` updates many numeric controls in one call.
- `win.get_many_numbers(names []string) map[string]int` reads many numeric controls into a map.
- `win.set_many_visibility(values map[string]bool)` updates many controls' visibility in one call.
- `win.get_many_visibility(names []string) map[string]bool` reads many controls' visibility into a map.
- `win.set_many_enabled(values map[string]bool)` updates many controls' enabled state in one call.
- `win.get_many_enabled(names []string) map[string]bool` reads many controls' enabled state into a map.
- `win.set_many_errors(values map[string]string)` updates many controls' inline errors in one call.
- `win.set_many_placeholders(values map[string]string)` updates many controls' placeholder text in one call.
- `win.set_many_tooltips(values map[string]string)` updates many controls' tooltip text in one call.
- `win.with_busy_state(names []string, status_text string, callback)` temporarily disables a set of controls while running a callback.
- `win.clear_many(names []string)` clears a subset of controls to their empty/default state.
- `win.clear_errors_for(names []string)` clears the inline error state for the specified list of controls.
- `win.reset_many(names []string)` restores a subset of controls to their original values.
- `win.focus(name)` moves keyboard focus to a control (alias of `set_focus`).

### List Box Item Management

Items are tracked automatically for every list box, so you can manage them incrementally:

- `win.get_list_items(name) []string` returns the current items.
- `win.set_list_items(name, items)` replaces all items (alias of `update_list_items`).
- `win.add_list_item(name, item)` appends a single item.
- `win.add_list_items(name, items)` appends multiple items.
- `win.has_list_item(name, item) bool` returns whether the item exists in the list box.
- `win.find_list_item(name, item) int` returns the 0-based index of the first matching item, or `-1`.
- `win.remove_list_item(name, index)` removes an item by 0-based index.
- `win.remove_selected_list_item(name)` removes the currently selected row.
- `win.clear_list_items(name)` removes everything.
- `win.get_list_count(name) int` returns the item count.
- `win.get_list_selected_text(name) string` returns the selected row's text, or `''`.

### List Box Multi-Selection & Double-Click

- `win.set_list_multi_select(name, enabled bool)` enables Cmd/Shift-click multiple row selection.
- `win.get_list_selected_indexes(name) []int` returns every selected row index (ascending).
- `win.set_list_selected_indexes(name, indexes []int)` selects the given rows programmatically (empty array clears the selection).
- `win.get_list_selected_texts(name) []string` returns the text of every selected row.
- `win.select_all_list_items(name)` selects every row (multi-select must be enabled).
- `win.clear_list_selection(name)` deselects everything.
- `win.remove_selected_list_items(name) []string` removes all selected rows and returns the removed items (works in both single and multi mode).
- `win.on_list_double_click(name, callback StringEventCallback)` fires when a row is double-clicked; the callback receives the 0-based row index as a string.

### Settings Persistence

- `win.save_values_to_file(path string) !` writes every control value to a JSON file.
- `win.load_values_from_file(path string) !` restores control values from a JSON file (unknown control names are skipped safely).

### Labeled Control Rows

One-call label + control rows (no `begin_row`/`end_row` needed):

- `win.add_labeled_slider(label, name, value)`
- `win.add_labeled_dropdown(label, name, items, selected)`
- `win.add_labeled_number(label, name, value)`
- `win.add_labeled_date_picker(label, name, date)`
- `win.add_labeled_progress(label, name, value)`

### Timer & Event Sugar

- `win.every(ms int, callback)` runs the callback repeatedly with an auto-generated timer name.
- `win.after(ms int, callback)` runs the callback once after the delay (alias of `run_after`).
- `win.on_change_many(names []string, callback)` binds a change callback to multiple controls.
- `win.on_click_many(names []string, callback)` binds a click callback to multiple controls.

### Table Row Management

Rows are tracked automatically for every table, so you can manage them incrementally (see `demos/table_manager_demo.v`):

- `win.get_table_rows(name) [][]string` returns every row.
- `win.get_table_row(name, index) []string` returns one row (empty array when out of range).
- `win.get_table_row_count(name) int` returns the row count.
- `win.get_table_column_count(name) int` returns the configured column count (or inferred widest row when no explicit columns were registered).
- `win.get_table_cell(name, row, col) string` / `win.set_table_cell(name, row, col, value)` read/write a single cell.
- `win.add_table_row(name, row []string)` appends a row.
- `win.add_table_rows(name, rows [][]string)` appends multiple rows.
- `win.get_table_column_values(name, column int) []string` returns all values of a specific 0-based column.
- `win.insert_table_row(name, index, row)` inserts a row at a 0-based index.
- `win.update_table_row(name, index, row)` replaces a row.
- `win.remove_table_row(name, index)` removes a row.
- `win.remove_table_column(name, column int) []string` removes a 0-based column and returns removed cell values.
- `win.clear_table(name)` removes every row.
- `win.find_table_row(name, column, value) int` returns the first row index whose cell matches, or `-1`.
- `win.get_table_row_where(name, column, value) []string` returns the first row matching the filter value in the specified column.
- `win.get_table_rows_where(name, column, value) [][]string` returns all rows matching the filter value in the specified column.
- `win.get_table_column_sum(name, column) f64` computes the numeric sum of a column's values.
- `win.get_table_column_average(name, column) f64` computes the numeric average of a column's values.
- `win.get_table_column_average_numeric(name, column) f64` computes the average using only parseable numeric cells (skips blanks/non-numeric cells).

Strict production variants (`!` return type) are also available:

- `win.set_table_rows_strict(name, rows) !`
- `win.add_table_row_strict(name, row) !`
- `win.insert_table_row_strict(name, index, row) !`
- `win.update_table_row_strict(name, index, row) !`
- `win.remove_table_row_strict(name, index) !`
- `win.remove_table_column_strict(name, column) ![]string`
- `win.remove_selected_table_column_strict(name) !(int, []string)`
- `win.set_table_cell_strict(name, row, col, value) !`
- `win.find_table_row_strict(name, column, value) !int`

`set_table_rows` and `set_table_rows_strict` normalize row width to the table's column count (truncate extra cells, pad missing cells with empty strings) so table data remains schema-safe.

### Table Selection & Events

- `win.get_table_selected(name) int` returns the selected row index (`-1` when none).
- `win.set_table_selected(name, index)` selects a row programmatically (`-1` clears).
- `win.get_table_selected_row(name) []string` returns the selected row's cells.
- `win.set_table_column_selection(name, enabled bool)` enables whole-column selection.
- `win.get_table_column_selection(name) bool` returns whether whole-column selection is enabled.
- `win.set_table_selected_column(name, column int)` selects a full table column (`-1` clears).
- `win.get_table_selected_column(name) int` returns the selected column index (`-1` when none).
- `win.get_table_selected_column_values(name) []string` returns all values from the selected column.
- `win.remove_selected_table_column(name) []string` removes the selected column and returns removed values.
- `win.set_table_multi_select(name, enabled bool)` enables Cmd/Shift-click multiple row selection.
- `win.get_table_selected_indexes(name) []int` returns every selected row index (ascending).
- `win.get_table_selected_rows(name) [][]string` returns the cells of every selected row.
- `win.clear_table_selection(name)` deselects everything.
- `win.remove_selected_table_rows(name) [][]string` removes all selected rows and returns them (works in both single and multi mode).
- `win.on_table_select(name, callback StringEventCallback)` fires on selection change; the callback receives the selected row index as a string (`-1` when cleared).
- `win.on_table_double_click(name, callback StringEventCallback)` fires when a row is double-clicked; the callback receives the 0-based row index as a string.
- `win.on_table_column_select(name, callback StringEventCallback)` fires when a column is selected (with column selection mode enabled); callback receives the 0-based column index as a string.

### Table Querying, Mapping & Filtering

- `win.has_table_row(name string, column int, value string) bool` returns whether any row has `row[column] == value`.
- `win.map_table_column(name string, column int, f fn (val string) string)` transforms all cells in a specific column in-place using `f`.
- `win.filter_table_rows(name string, predicate fn (row []string) bool) [][]string` returns a filtered list of rows matching `predicate`.
- `win.find_table_row_where(name string, predicate fn (row []string) bool) int` returns the index of the first row matching `predicate`, or `-1`.

### Quick Validation

- `win.require_fields(names []string) bool` marks blank controls with an inline "required" error, clears errors on filled ones, and returns `true` only when all fields are filled.
- `simplegui.validate_email(value) string` is a ready-made `ControlValidator` accepting basic `user@domain.tld` addresses.
- `simplegui.validate_number(value) string` is a ready-made `ControlValidator` accepting integers and floats.
- `simplegui.validate_url(value) string` is a ready-made `ControlValidator` accepting basic `http://` or `https://` URLs.
- `simplegui.validate_alphanumeric(value) string` is a ready-made `ControlValidator` accepting only letters and digits.
- `simplegui.validate_ip(value) string` is a ready-made `ControlValidator` accepting valid IPv4 addresses (`a.b.c.d`).
- `simplegui.validate_phone(value) string` is a ready-made `ControlValidator` accepting valid phone numbers (digits, spaces, `-`, `()`, `+`).
- `simplegui.min_len_validator(min int) ControlValidator` builds a validator requiring at least `min` characters (whitespace trimmed).
- `simplegui.max_len_validator(max int) ControlValidator` builds a validator requiring at most `max` characters.
- `simplegui.range_validator(min f64, max f64) ControlValidator` builds a validator requiring the numeric value of a control to be within `[min, max]`.
- `simplegui.required_validator() ControlValidator` builds a validator rejecting empty or whitespace-only values.
- `simplegui.one_of_validator(options []string) ControlValidator` builds a validator requiring the value to be one of the given options (case-insensitive, whitespace trimmed).
- `simplegui.chain_validators(validators ...ControlValidator) ControlValidator` combines several validators into one; the first non-empty error message wins.

### Batch Value Access & Reset Helpers

- `win.clear_fields(names []string)` empties every named text-based control and clears its error state in one call.
- `win.clear_all_errors() &SimpleWindow` clears the inline error state for all registered controls.
- `win.clear_all_fields() &SimpleWindow` empties the text or boolean states of all controls to empty/unchecked.
- `win.reset_all_fields() &SimpleWindow` restores every control in the window to its initial default value.

### Token Field Ergonomics

- `win.get_tokens(name string) []string` parses the comma-separated text of a token field into a cleaned slice of strings.
- `win.set_tokens(name string, tokens []string) &SimpleWindow` formats and sets a slice of strings into a token field.
- `win.add_token(name string, token string) &SimpleWindow` appends a token if not already present.
- `win.remove_token(name string, token string) &SimpleWindow` removes a token if present.

### List Sorting, Reordering & Live Search

- `win.sort_list_items(name, ascending bool)` sorts list box items alphabetically (case-insensitive).
- `win.move_list_item(name, from, to)` moves an item to a new index (great for Move Up/Down buttons).
- `win.insert_list_item(name, index, item)` inserts a single item at a given index.
- `win.update_list_item(name, index, item)` replaces a single item at a given index.
- `win.get_list_selected_text_or(name, fallback)` returns the text of the selected row, or a fallback string if none is selected.
- `win.move_selected_list_item_up(name)` / `win.move_selected_list_item_down(name)` shifts the currently selected list item up/down by 1 slot.
- `win.bind_search_to_list(search_name, list_name)` wires a search field to a list box so typing filters the visible rows live (case-insensitive substring match; clearing the search restores the full set). The full item set is snapshotted at bind time, so re-bind after replacing the list's master items.
- `win.save_list_to_file(name, path)` / `win.load_list_from_file(name, path)` saves/loads list box items to/from a line-separated text file.
- `win.save_list_to_json(name, path) !` / `win.load_list_from_json(name, path) !` saves/loads list box items to/from a JSON file.

### Table Sorting & CSV/JSON Import/Export

- `win.sort_table_by_column(name, column, ascending bool)` sorts rows by a 0-based column — numerically when every cell in the column parses as a number, otherwise as case-insensitive text.
- `win.move_table_row(name, from, to)` moves a row to a new index.
- `win.move_selected_table_row_up(name)` / `win.move_selected_table_row_down(name)` shifts the currently selected table row up/down by 1 slot.
- `win.save_table_to_csv(name, path) !` exports every table row to a CSV file.
- `win.load_table_from_csv(name, path) !` replaces a table's rows with the contents of a CSV file.
- `win.save_table_to_json(name, path) !` / `win.load_table_from_json(name, path) !` exports/imports table rows to/from a JSON file.

### Clipboard & State Helpers

- `win.copy_control_to_clipboard(name)` copies the text value of a named control to the system clipboard.
- `win.paste_from_clipboard_to_control(name)` replaces the named control's text with the system clipboard content.
- `win.copy_list_to_clipboard(name)` copies all list box items to the clipboard, one item per line.
- `win.copy_table_to_clipboard(name)` copies all table rows to the clipboard as tab-separated lines (paste-ready for spreadsheets).
- `win.confirm_discard_changes(title, message) bool` prompts the user with a confirmation dialog if the window has unsaved dirty changes, returning `true` if it's safe to proceed.

### Reactive Bindings & Data QoL

- `win.confirm_then(title, question, callback VoidEventCallback) bool` shows a Yes/No dialog and runs the callback only when confirmed; returns `true` when the callback was executed.
- `win.bind_value_to_label(source, label, prefix, suffix)` mirrors a control's value into a label whenever it changes, rendered as `prefix + value + suffix` (the current value is applied immediately).
- `win.bind_checkbox_enables(checkbox, names []string)` keeps a group of controls enabled while the checkbox/switch is checked and disabled while unchecked (the current state is applied immediately).
- `win.bind_char_counter(input, counter_label, max int)` keeps a label updated with `used/max` as the user types and flags the input with an inline error while over the limit.
- `win.countdown(label, seconds, callback VoidEventCallback)` counts a numeric label down to zero once per second, then stops its timer and invokes the callback.
- `win.dedupe_list_items(name)` removes duplicate list items, keeping the first occurrence.
- `win.reverse_list_items(name)` reverses the order of a list box's items.
- `win.keep_list_items(name, predicate fn (item string) bool)` keeps only the list items matching the predicate (destructive filter).
- `win.map_list_items(name, f fn (item string) string)` rewrites every list item through the transform function.
- `win.dedupe_table_rows(name)` removes duplicate table rows (all cells equal), keeping the first occurrence.
- `win.count_table_rows_where(name, predicate fn (row []string) bool) int` returns how many table rows match the predicate.

### Workflow, Text & Data Extras

- `win.on_change_debounced(name, ms, callback StringEventCallback)` fires the callback only after the user stops changing the control for `ms` milliseconds — ideal for search-as-you-type. The callback receives the most recent value.
- `win.submit_on_enter(names []string, callback VoidEventCallback)` registers the same Enter-key callback on several text controls at once ("press Enter anywhere in the form to submit").
- `win.ask_int(title, message, default_val int) int` prompts the user for a number, falling back to `default_val` when the response is empty, cancelled, or not numeric.
- `win.append_timestamped_line(name, line)` appends a line prefixed with the current `[HH:MM:SS]` time — ideal for activity logs in a textarea.
- `win.get_word_count(name) int` returns the number of whitespace-separated words in a text control's value.
- `win.get_line_count(name) int` returns the number of lines in a text control's value (0 for an empty control).
- `win.swap_list_items(name, i, j)` swaps the items at two indexes in a list box (out-of-range indexes are a no-op).
- `win.swap_table_rows(name, i, j)` swaps the rows at two indexes in a table (out-of-range indexes are a no-op).
- `win.add_table_row_unique(name, row []string) bool` appends a row only when an identical row is not already present; returns `true` when added.
- `win.select_list_item_by_text(name, text) bool` selects the first list row whose text matches exactly; returns `true` when found.
- `win.enable_autosave(path, interval_ms)` saves every control value to a JSON file at a fixed interval (failed writes are silently skipped). Pair with `load_values_if_exists` at startup for crash-safe forms.
- `win.load_values_if_exists(path) bool` restores control values from a JSON file when it exists, returning `true` when values were loaded.

### RAD / DX Ergonomics

- `win.set_status_temp(message string, ms int) &SimpleWindow` / `win.status_temp(message string, ms int) &SimpleWindow` displays a temporary message on the window's status bar that automatically reverts to the previous status message after `ms` milliseconds.
- `win.style_controls(names []string, style_fn fn (name string, mut w SimpleWindow)) &SimpleWindow` applies a custom styling closure to a list of named controls in bulk.
- `win.get_dirty_controls() []string` returns a list of modified control names since the last baseline commit.
- `win.get_dirty_values() map[string]string` returns a map of modified control names and their new string values.
- `win.notify(title string, message string) &SimpleWindow` triggers a non-blocking slide-in macOS native user notification.
- `win.badge(text string) &SimpleWindow` sets a badge text label on the application Dock icon (pass empty string `""` to clear).
- `win.set_slider_range(name string, min_val f64, max_val f64) &SimpleWindow` / `win.range(min_val f64, max_val f64) &SimpleWindow` sets a custom min/max bounds range for a slider or level indicator.
- `win.add_link(name string, text string, url string) &SimpleWindow` inserts a styled native hyperlink text button.
- `win.toggle_spinner(name string) bool` flips a spinner's active/spinning state and returns the new active state.
- `win.start_spinner(name string) &SimpleWindow` / `win.stop_spinner(name string) &SimpleWindow` starts or stops a spinner's animation.
- `win.increment_progress(name string, delta int) int` increments/decrements a progress bar value, bounding it in `0..100`.
- `simplegui.beep()` plays the native macOS system alert beep sound.
- `win.add_disclosure(name string, title string, open bool) &SimpleWindow` inserts a collapsible native disclosure triangle toggle button.
- `win.enable_search_history(name string, autosave_name string) &SimpleWindow` configures recent search item caches and history dropdowns automatically on a named search field.
- `win.set_status_bar_icon(icon_path string) &SimpleWindow` updates the status bar accessory icon dynamically.
- `win.set_status_bar_title(title string) &SimpleWindow` updates the status bar accessory title text dynamically.
- `win.set_dock_icon(image_path string) &SimpleWindow` overrides the application dock icon dynamically using a custom file image (or clears it with `win.clear_dock_icon()`).
- `simplegui.play_sound(sound_name string)` / `simplegui.play_system_sound(sound_name string)` plays a native macOS system sound by name (e.g. `"Glass"`, `"Ping"`, `"Purr"`, `"Basso"`, `"Tink"`, `"Blow"`).
- `simplegui.speak_with_voice(text string, voice string)` speaks text out loud using a specific macOS voice name (e.g. `"Samantha"`, `"Alex"`, `"Fred"`).
- `simplegui.toggle_dark_mode()` toggles macOS system appearance mode between Light and Dark.

### Developer Inspection & Interactive UI Controls

- `win.add_diff_view(name, old_code, new_code, height)` / `win.set_diff_view(name, old_code, new_code)`: Renders a unified side-by-side code diff comparison view with syntax highlighting and added (`+` green) / removed (`-` red) line indicators.
- `win.add_json_tree(name, json_str, height)` / `win.set_json_tree(name, json_str)`: Monospaced structured JSON data viewer with syntax highlighting and text selection event support.
- `win.add_http_request_card(name, method, url, status_code, response_ms)` / `win.set_http_request_card(name, method, url, status_code, response_ms)`: Visual API request card component with HTTP method badge, status code label, and latency telemetry.
- `win.add_terminal_view(name, initial_command, height)` / `win.append_terminal_line(name, text, style)` / `win.clear_terminal(name)`: Monospaced terminal / shell output log emulator widget with color-coded line styles (normal, info, error, success).
- `win.add_resource_monitor(name, cpu, ram, disk, net_kbps)` / `win.set_resource_monitor(name, cpu, ram, disk, net_kbps)`: Real-time telemetry monitoring component showing CPU, Memory, Disk usage percentages and network throughput.
- `win.add_env_vars(name, title, keys, values)` / `win.set_env_vars(name, keys, values)`: Monospaced key-value environment and configuration variables editor card.
- `win.add_badge_button(name, title, count, badge_color)` / `win.set_badge_button_count(name, count)`: Action button widget featuring an attached notification counter badge pill.
- `win.add_command_palette(name, placeholder, shortcut_hint)` / `win.set_command_palette_text(name, text)`: Search / command palette bar with magnifying glass icon and keyboard shortcut hint badge.
- `win.add_status_banner(name, title, message, style_type)` / `win.set_status_banner(name, title, message, style_type)`: Notification alert banner strip with accent border and status icon (`info`, `success`, `warning`, `error`).
- `win.add_pill_toggle(name, options, selected_index)` / `win.set_pill_toggle_selected(name, index)`: Rounded pill segment option toggle bar for mode switching.
- `win.add_color_swatch_panel(name, hex_colors, selected_color)` / `win.set_color_swatch_selected(name, hex_color)`: Design system palette panel with circular color swatch selection.
- `win.add_hotkey_badge(name, shortcut_str, description)` / `win.set_hotkey_badge_shortcut(name, shortcut_str, description)`: macOS metallic keycap hotkey display badge paired with description text.
- `win.on_shortcut(shortcut_str, callback)`: Registers a global keyboard shortcut handler using flexible formats (e.g. `'cmd+shift+p'`, `'Cmd+Shift+P'`, `'⌘+⇧+P'`, `'⌘K'`). Automatically normalizes modifier tokens and suppresses standard unhandled alert beeps when triggered.
- `simplegui.normalize_key_shortcut(input)`: Utility function converting shortcut notation variants into canonical format (`cmd+shift+p`).

---

## 7. RAD Visual UI Designer & Code Generator API

SimpleGUI includes a Delphi/VB/Lazarus-inspired **Visual UI Designer Engine** ([simplegui/designer.v](file:///Users/codecaine/vlang_simplegui/simplegui/designer.v)) and executable RAD Studio workspace ([ui_designer.v](file:///Users/codecaine/vlang_simplegui/ui_designer.v) and [demos/ui_designer.v](file:///Users/codecaine/vlang_simplegui/demos/ui_designer.v)).

### Structs

#### `ControlSpec`
Represents a single GUI control component on the visual design canvas.

```v
pub struct ControlSpec {
pub mut:
	id               string
	control_type     string // 'button', 'label', 'input', 'password', 'textarea', 'checkbox', 'switch', 'slider', 'mode', 'number', 'date', 'color', 'progress', 'image', 'table', 'panel', 'radio', 'divider', 'badge', 'search'
	x                int
	y                int
	width            int    = 140
	height           int    = 36
	text             string = 'Control'
	font_size        int    = 13
	font_color       string = '#ffffff'
	background_color string = '#1e293b'
	hover_color      string
	hover_text_color string
	cursor           string
	placeholder      string
	tooltip          string
	min_val          int
	max_val          int  = 100
	value            int  = 50
	enabled          bool = true
	visible          bool = true
	checked          bool
	locked           bool
	tab_order        int
	event_handlers   map[string]string
}
```

#### `FormSpec`
Represents a complete window form layout specification with global dimensions, themes, and controls.

```v
pub struct FormSpec {
pub mut:
	title            string = 'Delphi/VB RAD Form Studio'
	width            int    = 840
	height           int    = 560
	background_color string = '#0f172a'
	font_color       string = '#f8fafc'
	padding          int    = 20
	spacing          int    = 12
	controls         []ControlSpec
}
```

### Core API Functions

#### `(f FormSpec) to_json() string`
Encodes a `FormSpec` object into a JSON string layout representation.

#### `simplegui.form_spec_from_json(js string) FormSpec`
Parses a JSON layout string into a `FormSpec` object.

#### `simplegui.generate_v_code(spec FormSpec) string`
Generates clean, idiomatic V source code targeting `simplegui` from a `FormSpec` design, including RAD event handler callback stubs.

#### `simplegui.generate_html_code(spec FormSpec) string`
Generates clean, standalone semantic HTML5 & modern CSS webpage markup from a `FormSpec` design layout.

#### `simplegui.compile_designer_html(spec FormSpec) string`
Compiles an interactive HTML5/CSS3/JavaScript visual design studio canvas containing:
- **🔢 Visual Tab Order Editor (`TabOrder` / `TabIndex`)**: Interactive Tab Order Mode with numbered canvas badges (`[0]`, `[1]`, `[2]`, ...), 1-click focus index assignment, and spatial `Auto-Sequence` computation (Top-to-Bottom, Left-to-Right).
- **🔒 Lock Control Position (Delphi/VB `Lock Controls`)**: Individual control position locking (`locked: true`) and global lock toggle with visual 🔒 lock badges to prevent accidental dragging or resizing.
- **⚡ Component Selector Dropdown**: Top Object Inspector component dropdown listing all controls on form (`id: ControlType ("Caption")`) for instant selection and canvas highlighting.
- **Object Inspector Property Search & Filter**: Live keyword search/filter bar (`filterControlProps`) to filter property fields (`color`, `width`, `text`, `hover`, etc.).
- **Auto-Generated Event Callbacks & Code Stubs**: 1-click RAD event generator (`autoGenerateEvents`) populating `on_<id>_click`, `on_<id>_change`, `on_<id>_hover`, `on_<id>_hover_exit` and V function stubs.
- **Undo (`Cmd+Z`) & Redo (`Cmd+Shift+Z`) Engine**: 40-step snapshot history stack for all canvas modifications.
- **Clipboard Engine (`Cmd+C` / `Cmd+V` / `Cmd+D`)**: Full internal clipboard support for copying, pasting with offset, and duplicating single or multiple selected controls.
- **Custom Right-Click Context Menu**: Right-click canvas controls for instant Cut, Copy, Paste, Duplicate, Delete, Lock/Unlock, Bring to Front, Send to Back, and Alignment actions.
- **Canvas Rulers & Pan/Zoom Workspace**: Top and left pixel rulers, `Space + Mouse Drag` canvas panning, and smooth `Ctrl/Cmd + Wheel` canvas zooming (50% to 200%).
- **Distance & Gap Measuring Guides**: Smart snap lines paired with real-time numeric distance gap badges (`16px` gap overlays between adjacent controls).
- **Categorized Component Palette & Search Filter**: 25+ controls organized into 6 collapsible categories with live search filtering (`filterPalette`):
  - 🚀 **Standard Controls**: Button, Label, Input, Password, Text Area
  - 🎛️ **Toggles & Options**: Checkbox, Switch, Radio Button, Slider, Mode Toggle
  - 📊 **Pickers & Displays**: Number, Date Picker, Color Well, Progress Bar, Image, Badge, Search
  - 📝 **Labeled Form Controls**: Form Field, Form Textarea, Form Password, Form Number, Form Slider, Form Dropdown, Form Date, Form Progress, Form Switch, Form Link
  - ⚡ **Gauges, Cards & Widgets**: Star Rating, Stepper, Tag Field, Path Bar, Drop Zone, Circular Progress, Metric Meter, Status Light, Metric Card, Alert Banner, Code View
  - 📦 **Containers & Layout**: Data Grid, Panel Box, Separator
- **Component Tree Inspector Tab**: Hierarchical control tree for layer z-index depth re-ordering (`Bring to Front`, `Send to Back`, `Move Up`, `Move Down`) and locking (`Lock`/`Unlock`).
- **Multi-Selection & Simultaneous Move/Resize**: Marquee drag selection box, `Shift`/`Cmd`-click selection, and `Cmd+A` / `Ctrl+A` Select All with simultaneous multi-control drag moving and multi-control handle resizing, plus batch property updates (width, height, text/caption, font size, font/background colors, color swatch presets, hover styles, cursor styles, position, and RAD event callbacks).
- **Interactive Hotkeys Modal (`⌨️ Hotkeys`)**: Quick reference cheat-sheet detailing all keyboard shortcuts.
- **New Form Creation**: Non-blocking `📄 New` form reset button (`clearForm()`) restoring title, canvas geometry (`840x560`), and controls.
- **Alignment & Distribute Tools**: `Align Left`, `Center`, `Right`, `Top`, `Middle`, `Bottom`, `Center H Form`, `Center V Form`, `Distribute Horizontally/Vertically`, `Equal Width/Height`, `Fit Text Size`.
- **Layout JSON Import / Export**: Import custom JSON layout specs or copy generated V code.
- **Live V Engine Sync**: Real-time two-way synchronization (`syncSpecToV()`) with V runtime state and live preview execution (`launch_preview_window`).

#### Form Template Preset Helpers
- `simplegui.get_default_form_spec() FormSpec`: Customer Account Registration 2-Column Form layout preset.
- `simplegui.get_login_form_spec() FormSpec`: User Authentication Sign-In Dialog layout preset.
- `simplegui.get_dashboard_form_spec() FormSpec`: Executive KPI Performance Dashboard 3-Column layout preset.
- `simplegui.get_settings_form_spec() FormSpec`: Application Settings & Preferences Studio 2-Column layout preset.
- `simplegui.get_checkout_form_spec() FormSpec`: E-Commerce Multi-Column Order Checkout & Payment layout preset.
- `simplegui.get_crud_form_spec() FormSpec`: Enterprise Data Grid & Database Record Manager CRUD layout preset.
- `simplegui.get_ticket_form_spec() FormSpec`: Help Desk & Support Ticket Reporter layout preset.
- `simplegui.get_api_form_spec() FormSpec`: REST API Client & Endpoint Tester layout preset.
- `simplegui.get_media_form_spec() FormSpec`: Hi-Fi Audio Media Player & Controls layout preset.
- `simplegui.get_profile_form_spec() FormSpec`: User Profile & Account Settings layout preset.


