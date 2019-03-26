<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the corporate site switcher block.
 *
 * @Block(
 *   id = "oe_site_switcher",
 *   admin_label = @Translation("Site switcher block"),
 *   category = @Translation("Corporate blocks"),
 * )
 */
class SiteSwitcherBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
   * Construct the site switcher block object.
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
    $config = $this->configFactory->get('oe_corporate_blocks.data.site_switcher');

    $build['#theme'] = 'oe_corporate_blocks_site_switcher';

    foreach (['info', 'political'] as $name) {
      $build['#links'][$name] = [
        'label' => $config->get("{$name}_label"),
        'href' => $config->get("{$name}_href"),
        'active' => $config->get('active') === $name,
      ];
    }

    $build['#cache'] = [
      'contexts' => [
        'languages:language_interface',
      ],
    ];

    // By some reasons drush config import does not invalidate cache tags.
    // So for deployment we should take care about this case and
    // perform cache invalidation
    // via the "config:oe_corporate_blocks.data.footer" cache tag.
    $this->renderer->addCacheableDependency($build, $config);

    return $build;
  }

}
