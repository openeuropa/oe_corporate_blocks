<?php

/**
 * @file
 * OpenEuropa Corporate blocks Demo post updates.
 */

declare(strict_types = 1);

use Drupal\block\Entity\Block;
use Drupal\Core\Config\FileStorage;

/**
 * Delete the block site switcher block.
 */
function oe_corporate_blocks_demo_post_update_20001() {
  $block = Block::load('siteswitcherblock');

  if (!$block) {
    return t('The siteswitcherblock block was not found.');
  }

  $block->delete();
}

/**
 * Remove current demo corporate footer block.
 */
function oe_corporate_blocks_demo_post_update_20002() {
  \Drupal::configFactory()->getEditable('block.block.footer_block')->delete();
}

/**
 * Add EC and EU demo corporate blocks to active configuration storage.
 */
function oe_corporate_blocks_demo_post_update_20003() {
  $config_path = drupal_get_path('module', 'oe_corporate_blocks_demo') . '/config/install';
  $source = new FileStorage($config_path);
  $config_storage = \Drupal::service('config.storage');
  $config_factory = \Drupal::configFactory();

  $blocks = [
    'block.block.ec_footer_block',
    'block.block.eu_footer_block',
  ];

  foreach ($blocks as $block) {
    $config_storage->write($block, $source->read($block));
    $config_factory->getEditable($block)->save();
  }
}
