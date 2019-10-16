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
  \Drupal::configFactory()
    ->getEditable('oe_corporate_blocks.data.site_switcher')
    ->delete();
}

/**
 * Only display "European Commission website" link in footer.
 */
function oe_corporate_blocks_post_update_20002(&$sandbox) {
  $config = \Drupal::configFactory()->getEditable('oe_corporate_blocks.data.footer');
  $config->set('about_ec_links', [
    [
      'href' => 'https://ec.europa.eu/info/index_en',
      'label' => 'European Commission website',
    ],
  ]);
  $config->save();

  // Allow for config translation re-import when running
  // "drush oe-multilingual:import-local-translations".
  // @see https://webgate.ec.europa.eu/CITnet/jira/browse/OPENEUROPA-2407
  /** @var \Drupal\locale\StringDatabaseStorage $storage */
  $storage = \Drupal::service('locale.storage');
  $string = $storage->findString(['source' => 'https://ec.europa.eu/info/index_en']);
  $storage->delete($string);
  $string = $storage->findString(['source' => 'European Commission website']);
  $storage->delete($string);
}
