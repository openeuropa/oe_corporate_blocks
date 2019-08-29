<?php

/**
 * @file
 * OpenEuropa Corporate blocks Demo post updates.
 */

declare(strict_types = 1);

/**
 * Delete the config and menu for Site Switcher.
 */
function oe_corporate_blocks_post_update_00001_delete_all_of_site_switcher() {
  // Delete the config.
  \Drupal::configFactory()->getEditable('oe_corporate_blocks.data.site_switcher')
    ->delete();
}
