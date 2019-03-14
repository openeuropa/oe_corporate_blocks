<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an OpenEuropa Footer block.
 *
 * @Block(
 *   id = "oe_footer",
 *   admin_label = @Translation("Footer block"),
 *   category = @Translation("Corporate blocks"),
 * )
 */
class FooterBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs an Search block object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->renderer = $renderer;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('renderer')
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

    $config = $this->configFactory->get('oe_corporate_blocks.data.footer');

    NestedArray::setValue($build, ['#corporate_footer', 'about_ec', 'title'], $config->get('about_ec_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'about_ec', 'items'], $config->get('about_ec_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'social_media', 'title'], $config->get('social_media_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'social_media', 'items'], $config->get('social_media_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'about_eu', 'title'], $config->get('about_eu_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'about_eu', 'items'], $config->get('about_eu_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'bottom_links'], $config->get('bottom_links'));

    // By some reasons drush config import do not do invalidating of cache tags.
    // So for deployment we should take care about this case and
    // do cache invalidating by tag "config:oe_corporate_blocks.data.footer".
    $this->renderer->addCacheableDependency($build, $config);

    return $build;
  }

}
