<?php

/**
 * @file
 * OpenEuropa Corporate blocks post updates.
 */

declare(strict_types = 1);

/**
 * Delete the config for Site Switcher.
 */
function oe_corporate_blocks_post_update_20001() {
  // Delete the config.
  \Drupal::configFactory()->getEditable('oe_corporate_blocks.data.site_switcher')
    ->delete();
}
