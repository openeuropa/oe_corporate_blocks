<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Provides an OpenEuropa Footer block.
 *
 * @Block(
 *   id = "oe_footer",
 *   admin_label = @Translation("OpenEuropa Footer block"),
 *   category = @Translation("OE Corporate blocks"),
 * )
 */
class FooterBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['#theme'] = 'corporate_blocks_footer';
    $footer_data = Yaml::parseFile(drupal_get_path('module', 'oe_corporate_blocks') . '/config/blocks/footer.yml');
    $build['#corporate_footer'] = $footer_data['corporate_footer'];
    return $build;
  }

}
