<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the corporate footer block.
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
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct the footer block object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
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
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cache = new CacheableMetadata();
    $cache->addCacheContexts(['languages:language_interface']);

    $config = $this->configFactory->get('oe_corporate_blocks.data.footer');
    $cache->addCacheableDependency($config);

    $build['#theme'] = 'oe_corporate_blocks_footer';

    NestedArray::setValue($build, ['#corporate_footer', 'about_ec', 'title'], $config->get('about_ec_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'about_ec', 'items'], $config->get('about_ec_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'social_media', 'title'], $config->get('social_media_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'social_media', 'items'], $config->get('social_media_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'about_eu', 'title'], $config->get('about_eu_title'));
    NestedArray::setValue($build, ['#corporate_footer', 'about_eu', 'items'], $config->get('about_eu_links'));

    NestedArray::setValue($build, ['#corporate_footer', 'bottom_links'], $config->get('bottom_links'));

    $general_links = $this->getCustomFooterLinks('footer_link_general', $cache);
    $social_links = $this->getCustomFooterLinks('footer_link_social', $cache);
    $site_info_config = $this->configFactory->get('system.site');
    $cache->addCacheableDependency($site_info_config);
    $site_identity = $site_info_config->get('name');

    if (!empty($site_identity) && (!empty($social_links) || !empty($general_links))) {
      NestedArray::setValue($build, ['#custom_footer', 'site_identity'], $site_identity);
      NestedArray::setValue($build, ['#custom_footer', 'social_links'], $social_links);
      NestedArray::setValue($build, ['#custom_footer', 'other_links'], $general_links);
    }

    $cache->applyTo($build);

    return $build;
  }

  /**
   * Retrieve a list of custom footer link configuration entities.
   *
   * @param string $type
   *   Type of configs.
   * @param \Drupal\Core\Cache\CacheableMetadata $cache
   *   The Cacheable metadata abject.
   *
   * @return array
   *   The array of links.
   */
  protected function getCustomFooterLinks(string $type, CacheableMetadata &$cache): array {
    $links = [];
    /** @var \Drupal\Core\Entity\EntityStorageInterface $links_storage */
    $links_storage = $this->entityTypeManager->getStorage($type);
    $cache->addCacheTags($links_storage->getEntityType()->getListCacheTags());
    $link_ids = $links_storage->getQuery()->sort('weight')->execute();
    if (count($link_ids) > 0) {
      /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $link_entity */
      foreach ($links_storage->loadMultiple($link_ids) as $link_entity) {
        $cache->addCacheableDependency($link_entity);

        $link = [
          'href' => $link_entity->getUrl()->toString(),
          'label' => $link_entity->label(),
        ];

        if ($type === 'footer_link_social') {
          $link['social_network'] = $link_entity->getSocialNetwork();
        }

        $links[] = $link;
      }
    }

    return $links;
  }

}
