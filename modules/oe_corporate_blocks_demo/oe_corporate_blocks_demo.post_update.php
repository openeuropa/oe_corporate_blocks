<?php

/**
 * @file
 * OpenEuropa Corporate blocks Demo post updates.
 */

declare(strict_types = 1);

use Drupal\block\Entity\Block;

/**
 * Delete the block siteswitcherblock.
 */
function oe_theme_helper_post_update_8201_delete_the_siteswitcherblock() {
  $block = Block::load('siteswitcherblock');

  if (!$block) {
    return t('The siteswitcherblock block was not found.');
  }

  $block->delete();
}
