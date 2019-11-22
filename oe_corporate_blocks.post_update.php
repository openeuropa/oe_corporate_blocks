<?php

/**
 * @file
 * OpenEuropa Corporate blocks post updates.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\locale\Locale;

/**
 * Delete the config for Site Switcher.
 */
function oe_corporate_blocks_post_update_20001(&$sandbox): void {
  // Delete the config.
  \Drupal::configFactory()
    ->getEditable('oe_corporate_blocks.data.site_switcher')
    ->delete();
}

/**
 * Only display "European Commission website" link in footer.
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

/**
 * Update existing blocks for EC corporate footer and import new configs.
 */
function oe_corporate_blocks_post_update_20002(&$sandbox): void {
  // Clear cached block definition as we have renamed EC footer base class.
  \Drupal::service('plugin.manager.block')->clearCachedDefinitions();

  // Replacing plugin for existing footer.
  $block_storage = \Drupal::entityTypeManager()->getStorage('block');
  $block_ids = $block_storage->getQuery()->condition('plugin', 'oe_footer')->execute();
  foreach ($block_ids as $block_id) {
    /** @var \Drupal\block\Entity\Block $block */
    $block = $block_storage->load($block_id);
    $block->set('plugin', 'oe_corporate_blocks_ec_footer');
    $settings = $block->get('settings');
    $settings['id'] = 'oe_corporate_blocks_ec_footer';
    $block->set('settings', $settings);
    $block->save();
  }

  $config_path = drupal_get_path('module', 'oe_corporate_blocks') . '/config/install';
  $source = new FileStorage($config_path);
  $config_storage = \Drupal::service('config.storage');
  $config_storage->write('oe_corporate_blocks.eu_data.footer', $source->read('oe_corporate_blocks.eu_data.footer'));

  $langcodes = array_keys(\Drupal::languageManager()->getLanguages());
  Locale::config()->updateConfigTranslations(['oe_corporate_blocks'], $langcodes);
}
