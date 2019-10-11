<?php

/**
 * @file
 * OpenEuropa Corporate Blocks post updates.
 */

declare(strict_types = 1);

/**
 * Update corporate footer.
 */
function oe_corporate_blocks_post_update_00001(&$sandbox): void {
  $config = \Drupal::configFactory()->getEditable('oe_corporate_blocks.data.footer');
  $config->set('about_ec_links', [
    [
      'href' => 'https://ec.europa.eu/info/index_en',
      'label' => 'European Commission website',
    ],
  ]);
  $config->save();
}
