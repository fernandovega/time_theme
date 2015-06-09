<?php
/**
 * Elgg page header
 * In the default theme, the header lives between the topbar and main content area.
 */

// link back to main site.
//echo 'hola';
echo elgg_view('page/elements/header_logo', $vars);
//echo elgg_view('page/elements/riverwire');
// drop-down login
//echo elgg_view('core/account/login_dropdown');

// insert site-wide navigation
//echo elgg_view_menu('site');