<?php
/**
 * Plugin Name: Matomo Tracker (Lean)
 * Description: Lightweight Matomo tracking for the frontend (cookieless, no admin tracking).
 * Version: 1.0.0
 * License: GPL-3.0-or-later
 */

defined('ABSPATH') || exit;

/**
 * Settings
 * - Set these via wp-config.php (recommended) or fallback constants below.
 *
 * In wp-config.php:
 *   define('MATOMO_URL', 'https://deine.matomo.url.de');
 *   define('MATOMO_SITE_ID', 1);
 */

// Fallbacks (only used if constants not defined)
if (!defined('MATOMO_URL'))     define('MATOMO_URL', '');
if (!defined('MATOMO_SITE_ID')) define('MATOMO_SITE_ID', 0);

/**
 * Decide whether to track.
 */
function matomo_lean_should_track(): bool {
  if (is_admin() || wp_doing_ajax() || wp_is_json_request()) return false;
  if (is_preview()) return false;
  if (is_user_logged_in()) return false; // no admin/editor/etc tracking
  $base = trim((string) MATOMO_URL);
  $id   = (int) MATOMO_SITE_ID;
  if ($base === '' || $id <= 0) return false;

  /**
   * Filter to allow consent integration later, if needed.
   * Return false to disable tracking.
   */
  return (bool) apply_filters('matomo_lean_should_track', true);
}

/**
 * Enqueue Matomo (footer, async) + inline bootstrap.
 */
add_action('wp_enqueue_scripts', function () {
  if (!matomo_lean_should_track()) return;

  $base = rtrim(esc_url_raw(trim((string)MATOMO_URL)), '/');
  $site_id = (int) MATOMO_SITE_ID;

  $js_url  = $base . '/matomo.js';
  $php_url = $base . '/matomo.php';

  // Enqueue Matomo JS in footer
  wp_register_script('matomo-lean', $js_url, [], null, true);
  wp_enqueue_script('matomo-lean');

  // Inline bootstrap BEFORE matomo.js executes
  $inline =
    "window._paq = window._paq || [];\n" .
    "_paq.push(['disableCookies']);\n" .
    "_paq.push(['trackPageView']);\n" .
    "_paq.push(['enableLinkTracking']);\n" .
    "_paq.push(['setTrackerUrl', " . wp_json_encode($php_url) . "]);\n" .
    "_paq.push(['setSiteId', " . wp_json_encode((string)$site_id) . "]);\n";

  wp_add_inline_script('matomo-lean', $inline, 'before');
}, 20);
