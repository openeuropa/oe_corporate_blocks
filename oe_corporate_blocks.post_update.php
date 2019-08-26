<?php

/**
 * @file
 * OpenEuropa Corporate blocks Demo post updates.
 */

declare(strict_types = 1);

/**
 * Delete the config for Site Switcher.
 */
function oe_theme_helper_post_update_00001_delete_config_of_site_switcher() {
  \Drupal::configFactory()->getEditable('oe_corporate_blocks.data.site_switcher')
    ->delete();
}
