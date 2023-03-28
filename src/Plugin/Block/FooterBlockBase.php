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
use Drupal\oe_corporate_blocks\FooterLinkManagerInterface;

/**
 * Provides Base the corporate footer block.
 */
abstract class FooterBlockBase extends BlockBase implements ContainerFactoryPluginInterface {

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
   * Footer link manager service.
   *
   * @var \Drupal\oe_corporate_blocks\FooterLinkManagerInterface
   */
  protected $linkManager;

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
   * @param \Drupal\oe_corporate_blocks\FooterLinkManagerInterface $link_manager
   *   The footer link manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, FooterLinkManagerInterface $link_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->entityTypeManager = $entity_type_manager;
    $this->linkManager = $link_manager;
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
      $container->get('entity_type.manager'),
      $container->get('oe_corporate_blocks.footer_link_manager')
    );
  }

  /**
   * Set site specific footer for footer block build.
   *
   * @param array $build
   *   The build render array of footer.
   * @param \Drupal\Core\Cache\CacheableMetadata $cache
   *   The Cacheable metadata abject.
   */
  protected function setSiteSpecificFooter(array &$build, CacheableMetadata &$cache): void {
    $general_links = $this->getGeneralFooterLinks($cache);
    $social_links = $this->getSocialFooterLinks($cache);
    $site_info_config = $this->configFactory->get('system.site');
    $cache->addCacheableDependency($site_info_config);
    $site_identity = $site_info_config->get('name');
    NestedArray::setValue($build, ['#site_specific_footer', 'site_identity'], $site_identity);

    if (!empty($social_links) || !empty($general_links)) {
      NestedArray::setValue($build, ['#site_specific_footer', 'social_links'], $social_links);
      NestedArray::setValue($build, ['#site_specific_footer', 'other_links'], $general_links);
    }
  }

  /**
   * Retrieve a list of custom footer link configuration entities.
   *
   * @param \Drupal\Core\Cache\CacheableMetadata $cache
   *   The Cacheable metadata abject.
   *
   * @return array
   *   The array of links.
   */
  protected function getGeneralFooterLinks(CacheableMetadata &$cache): array {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->entityTypeManager->getStorage('footer_link_general');
    $cache->addCacheTags($storage->getEntityType()->getListCacheTags());
    $storage = $this->entityTypeManager->getStorage('footer_link_section');
    $cache->addCacheTags($storage->getEntityType()->getListCacheTags());
    $links = [];
    foreach ($this->linkManager->getSections() as $section) {
      $section_links = $this->linkManager->getLinksBySection($section->id());

      if (empty($section_links)) {
        continue;
      }

      $cache->addCacheableDependency($section);
      $links[$section->id()] = [
        'label' => $section->label(),
        'links' => [],
      ];

      foreach ($section_links as $entity) {
        $cache->addCacheableDependency($entity);
        $link = [
          'href' => $entity->getUrl(),
          'label' => $entity->label(),
        ];

        $links[$section->id()]['links'][] = $link;
      }
    }

    return $links;
  }

  /**
   * Retrieve a list of custom footer link configuration entities.
   *
   * @param \Drupal\Core\Cache\CacheableMetadata $cache
   *   The Cacheable metadata abject.
   *
   * @return array
   *   The array of links.
   */
  protected function getSocialFooterLinks(CacheableMetadata &$cache): array {
    $links = [];
    /** @var \Drupal\Core\Entity\EntityStorageInterface $links_storage */
    $links_storage = $this->entityTypeManager->getStorage('footer_link_social');
    $cache->addCacheTags($links_storage->getEntityType()->getListCacheTags());
    $link_ids = $links_storage->getQuery()
      ->sort('weight')
      ->accessCheck()
      ->execute();
    if (count($link_ids) > 0) {
      /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $link_entity */
      foreach ($links_storage->loadMultiple($link_ids) as $link_entity) {
        $cache->addCacheableDependency($link_entity);

        $link = [
          'href' => $link_entity->getUrl(),
          'label' => $link_entity->label(),
          'social_network' => $link_entity->getSocialNetwork(),
        ];

        $links[] = $link;
      }
    }

    return $links;
  }

}
