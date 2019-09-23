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
function oe_corporate_blocks_demo_post_update_00001() {
  $block = Block::load('siteswitcherblock');

  if (!$block) {
    return t('The siteswitcherblock block was not found.');
  }

  $block->delete();
}
