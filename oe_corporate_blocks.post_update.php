<?php

/**
 * @file
 * OpenEuropa Corporate blocks post updates.
 */

declare(strict_types = 1);

use Drupal\Core\Config\FileStorage;
use Drupal\locale\Locale;
use Drupal\Component\Utility\Crypt;

/**
 * Helper function: import corporate links.
 *
 * @param string $config_path
 *   The config path.
 */
function _oe_corporate_blocks_import_corporate_links(string $config_path): void {
  $source = new FileStorage($config_path);
  /** @var Drupal\Core\Config\StorageInterface $config_storage */
  $config_storage = \Drupal::service('config.storage');

  $configs = [
    'oe_corporate_blocks.eu_data.footer',
    'oe_corporate_blocks.ec_data.footer',
  ];

  foreach ($configs as $config) {
    if ($data = $source->read($config)) {
      $data['_core']['default_config_hash'] = Crypt::hashBase64(serialize($data));
      $config_storage->write($config, $data);
    }
  }

  if (!\Drupal::hasService('locale.storage')) {
    return;
  }

  // Import translations.
  $langcodes = array_keys(\Drupal::languageManager()->getLanguages());
  Locale::config()->updateConfigTranslations($configs, $langcodes);
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

  if (!\Drupal::hasService('locale.storage')) {
    return;
  }

  // Allow for config translation re-import when running
  // "drush oe-multilingual:import-local-translations".
  // @see https://webgate.ec.europa.eu/CITnet/jira/browse/OPENEUROPA-2407
  /** @var \Drupal\locale\StringDatabaseStorage $storage */
  $storage = \Drupal::service('locale.storage');
  $string = $storage->findString(['source' => 'https://ec.europa.eu/info/index_en']);
  if ($string !== NULL) {
    $storage->delete($string);
  }
  $string = $storage->findString(['source' => 'European Commission website']);
  if ($string !== NULL) {
    $storage->delete($string);
  }
}

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
 * Remove current footer data.
 */
function oe_corporate_blocks_post_update_20002(&$sandbox): void {
  \Drupal::configFactory()
    ->getEditable('oe_corporate_blocks.data.footer')
    ->delete();
}

/**
 * Import EC and EU footer data, along with their translations.
 */
function oe_corporate_blocks_post_update_20003(&$sandbox): void {
  // Clear cached block definition as we have renamed EC footer base class.
  \Drupal::service('plugin.manager.block')->clearCachedDefinitions();
  $config_path = drupal_get_path('module', 'oe_corporate_blocks') . '/config/post_update/20003_update_footer_data';
  _oe_corporate_blocks_import_corporate_links($config_path);
}

/**
 * Remove 'Presidency of the Council of the EU' from EU footer.
 */
function oe_corporate_blocks_post_update_20004(&$sandbox): void {
  $config = \Drupal::configFactory()->getEditable('oe_corporate_blocks.eu_data.footer');
  $institution_links = $config->get('institution_links');
  foreach ($institution_links as $key => $link) {
    if (
      $link['label'] === 'Presidency of the Council of the EU' &&
      $link['href'] === 'https://www.romania2019.eu/home/'
    ) {
      unset($institution_links[$key]);
    }
  }
  $config->set('institution_links', $institution_links);
  $config->save();
}

/**
 * Install "OpenEuropa Corporate Site Information" module.
 */
function oe_corporate_blocks_post_update_30001(): void {
  \Drupal::service('module_installer')->install(['oe_corporate_site_info']);
  // Invalidate kernel container to make site info service available.
  \Drupal::service('kernel')->invalidateContainer();
}

/**
 * Import updated EC and EU footer data, along with their translations.
 */
function oe_corporate_blocks_post_update_30002(): void {
  $config_path = drupal_get_path('module', 'oe_corporate_blocks') . '/config/post_update/30002_update_footer_data';
  _oe_corporate_blocks_import_corporate_links($config_path);
}

/**
 * Create default footer link sections.
 */
function oe_corporate_blocks_post_update_30003(): void {
  $storage = \Drupal::entityTypeManager()->getStorage('footer_link_section');
  $sections = [
    [
      'id' => 'contact_us',
      'label' => 'Contact us',
      'weight' => -10,
    ],
    [
      'id' => 'about_us',
      'label' => 'About us',
      'weight' => -9,
    ],
    [
      'id' => 'related_sites',
      'label' => 'Related sites',
      'weight' => -8,
    ],
  ];

  foreach ($sections as $config) {
    // We are creating the config which means that we are also shipping
    // it in the config/install folder so we want to make sure it gets the hash
    // so Drupal treats it as a shipped config. This means that it gets exposed
    // to be translated via the locale system as well.
    $config['_core']['default_config_hash'] = Crypt::hashBase64(serialize($config));
    $storage->create($config)->save();
  }
}

/**
 * Set "Related sites" as default section for existing general links.
 */
function oe_corporate_blocks_post_update_30004(): void {
  /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkGeneral[] $general_links */
  $general_links = \Drupal::entityTypeManager()->getStorage('footer_link_general')->loadMultiple();
  foreach ($general_links as $general_link) {
    $general_link->setSection('related_sites');
    $general_link->save();
  }
}

/**
 * Updates EU footer data.
 */
function oe_corporate_blocks_post_update_40001(): void {
  $config_path = drupal_get_path('module', 'oe_corporate_blocks') . '/config/post_update/40001_update_eu_footer_data';
  _oe_corporate_blocks_import_corporate_links($config_path);
}

/**
 * Updates EU and EC footer data.
 */
function oe_corporate_blocks_post_update_40002(&$sandbox): void {
  $config_path = drupal_get_path('module', 'oe_corporate_blocks') . '/config/post_update/40002_update_footer_data';
  _oe_corporate_blocks_import_corporate_links($config_path);
}

/**
 * Updates European Personnel Selection Office link of EU footer data.
 */
function oe_corporate_blocks_post_update_40003(): void {
  $config = \Drupal::configFactory()->getEditable('oe_corporate_blocks.eu_data.footer');
  $institution_links = $config->get('institution_links');
  foreach ($institution_links as $key => $link) {
    if (
      $link['label'] === 'European Personnel Selection Office' &&
      $link['href'] === 'https://epso.europa.eu/home_en'
    ) {
      $institution_links[$key]['href'] = 'https://epso.europa.eu/en';
    }
  }
  $config->set('institution_links', $institution_links);
  $config->save();
}
