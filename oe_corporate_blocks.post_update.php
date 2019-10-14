<?php

/**
 * @file
 * OpenEuropa Corporate Blocks post updates.
 */

declare(strict_types = 1);

/**
 * Update corporate footer.
 */
function oe_corporate_blocks_post_update_10001(&$sandbox): void {
  $config = \Drupal::configFactory()->getEditable('oe_corporate_blocks.data.footer');
  $config->set('about_ec_links', [
    [
      'href' => 'https://ec.europa.eu/info/index_en',
      'label' => 'European Commission website',
    ],
  ]);
  $config->save();

  // Workaround for forcing config translation.
  /** @var \Drupal\locale\StringDatabaseStorage $storage */
  $storage = \Drupal::service('locale.storage');
  $string = $storage->findString(['source' => 'https://ec.europa.eu/info/index_en']);
  $storage->delete($string);
  $string = $storage->findString(['source' => 'European Commission website']);
  $storage->delete($string);
}
