<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an OpenEuropa Footer block.
 *
 * @Block(
 *   id = "oe_footer",
 *   admin_label = @Translation("OpenEuropa Footer block"),
 *   category = @Translation("OE Corporate blocks"),
 * )
 */
class FooterBlock extends BlockBase implements ContainerFactoryPluginInterface {


  /**
   * Data for footer block from service container.
   *
   * @var array
   */
  protected $footerData;

  /**
   * Constructs an Search block object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $block_footer
   *   The footer block data from parameters of service container.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $block_footer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->footerData = $block_footer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('block.footer')
    );
  }

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
    $build['#corporate_footer'] = $this->footerData['corporate_footer'];
    return $build;
  }

}
