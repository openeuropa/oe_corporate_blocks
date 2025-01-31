<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Plugin\Block;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides the corporate footer block.
 *
 * @Block(
 *   id = "oe_corporate_blocks_ec_footer",
 *   admin_label = @Translation("EC Footer block"),
 *   category = @Translation("Corporate blocks"),
 * )
 */
class EcFooterBlock extends FooterBlockBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $cache = new CacheableMetadata();
    $cache->addCacheContexts(['languages:language_interface']);

    $config = $this->configFactory->get('oe_corporate_blocks.ec_data.footer');
    $cache->addCacheableDependency($config);

    $build['#theme'] = 'oe_corporate_blocks_ec_footer';

    NestedArray::setValue($build, ['#corporate_footer', 'corporate_site_link'], $config->get('corporate_site_link'));

    NestedArray::setValue($build, ['#corporate_footer', 'service_navigation'], $config->get('service_navigation'));

    NestedArray::setValue($build, ['#corporate_footer', 'legal_navigation'], $config->get('legal_navigation'));

    $ec_core_sections = $this->getEcCoreSectionsLinks($cache);
    NestedArray::setValue($build, ['#corporate_footer', 'ec_core_section'], $ec_core_sections['ec_core_column'] ?? []);

    $this->setSiteSpecificFooter($build, $cache, ['ec_core_column']);

    $cache->applyTo($build);

    return $build;
  }

  /**
   * Get links only from EC core sections.
   *
   * @param \Drupal\Core\Cache\CacheableMetadata $cache
   *   CacheableMetadata object.
   *
   * @return array
   *   Array of links.
   */
  protected function getEcCoreSectionsLinks(&$cache): array {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->entityTypeManager->getStorage('footer_link_general');
    $cache->addCacheTags($storage->getEntityType()->getListCacheTags());
    $section_storage = $this->entityTypeManager->getStorage('footer_link_section');
    $cache->addCacheTags($section_storage->getEntityType()->getListCacheTags());
    $links = [];
    foreach ($section_storage->loadMultiple(['ec_core_column']) as $section) {
      $section_links = $this->linkManager->getLinksBySection($section->id());

      if (empty($section_links)) {
        continue;
      }

      $cache->addCacheableDependency($section);
      foreach ($section_links as $entity) {
        $cache->addCacheableDependency($entity);
        $link = [
          'href' => $entity->getUrl(),
          'label' => $entity->label(),
          // Pass link config object for possible use in other components.
          '#link' => $entity,
        ];

        $links[$section->id()][] = $link;
      }
    }
    return $links;
  }

}
