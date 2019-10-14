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
  // We have to update configuration also for translated configs manually.
  $language_manager = \Drupal::languageManager();
  $default_language = $language_manager->getDefaultLanguage()->getId();
  foreach ($language_manager->getLanguages() as $language) {
    if ($default_language === $language->getId()) {
      continue;
    }
    $config_translation = $language_manager->getLanguageConfigOverride($language->getId(), 'oe_corporate_blocks.data.footer');
    $config_translation->set(
      'about_ec_links', [
        [
          'href' => 'https://ec.europa.eu/info/index_en',
          'label' => 'European Commission website',
        ],
      ]
    );
    $config_translation->save();
  }

}
