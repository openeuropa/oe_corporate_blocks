<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Manage footer links, link sections, etc.
 */
class FooterLinkManager implements FooterLinkManagerInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a FooterLinkManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getSections(): array {
    $storage = $this->entityTypeManager->getStorage('footer_link_section');
    $entity_ids = $storage->getQuery()
      ->sort('weight')
      ->accessCheck()
      ->execute();
    return $storage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function getLinksBySection(string $section): array {
    $storage = $this->entityTypeManager->getStorage('footer_link_general');
    $entity_ids = $storage->getQuery()
      ->condition('section', $section)
      ->sort('weight')
      ->accessCheck()
      ->execute();
    return $storage->loadMultiple($entity_ids);
  }

  /**
   * {@inheritdoc}
   */
  public function getLinksWithoutSection(): array {
    $storage = $this->entityTypeManager->getStorage('footer_link_general');
    $entity_ids = $storage->getQuery()
      ->condition('section', '')
      ->sort('weight')
      ->accessCheck()
      ->execute();
    return $storage->loadMultiple($entity_ids);
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

}
