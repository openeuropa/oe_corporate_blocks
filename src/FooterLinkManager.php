<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Manage footer links, link sections, etc.
 */
class FooterLinkManager implements FooterLinkManagerInterface {

  use StringTranslationTrait;

  /**
   * Footer link entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $linkStorage;

  /**
   * Footer link section entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $sectionStorage;

  /**
   * Constructs a FooterLinkManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->linkStorage = $entity_type_manager->getStorage('footer_link_general');
    $this->sectionStorage = $entity_type_manager->getStorage('footer_link_section');
  }

  /**
   * {@inheritdoc}
   */
  public function getSections(): array {
    $entity_ids = $this->sectionStorage->getQuery()->sort('weight')->execute();
    return $this->sectionStorage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function getLinksBySection(string $section): array {
    $entity_ids = $this->linkStorage->getQuery()
      ->condition('section', $section)
      ->sort('weight')
      ->execute();
    return $this->linkStorage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function getLinksWithoutSection(): array {
    $entity_ids = $this->linkStorage->getQuery()
      ->condition('section', NULL)
      ->sort('weight')
      ->execute();
    return $this->linkStorage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function getSectionsAsOptions(): array {
    $sections = ['' => $this->t('- Disabled -')];
    foreach ($this->getSections() as $entity) {
      $sections[$entity->id()] = $entity->label();
    }

    return $sections;
  }

  /**
   * {@inheritdoc}
   */
  public function getListCacheTags(): array {
    return array_merge(
      $this->linkStorage->getEntityType()->getListCacheTags(),
      $this->sectionStorage->getEntityType()->getListCacheTags()
    );
  }

}
