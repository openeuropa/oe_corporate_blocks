<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of Footer general link item entities.
 */
class FooterLinkGeneralListBuilder extends DraggableListBuilder {

  /**
   * Footer link section entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $sectionStorage;

  /**
   * FooterLinkGeneralListBuilder constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *   The entity storage.
   * @param \Drupal\Core\Entity\EntityStorageInterface $section_storage
   *   The footer link section entity storage.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, EntityStorageInterface $section_storage) {
    parent::__construct($entity_type, $storage);
    $this->sectionStorage = $section_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('entity_type.manager')->getStorage('footer_link_section')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'footer_link_generals_overview_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['url'] = $this->t('URL');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['url'] = [
      '#markup' => $entity->getUrl()->toString(),
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    foreach ($this->getSections() as $section_key => $section) {
      $form[$section_key] = [
        '#type' => 'table',
        '#header' => $this->buildHeader(),
        '#empty' => t('There are no @label yet.', ['@label' => $this->entityType->getPluralLabel()]),
        '#tabledrag' => [
          [
            'action' => 'order',
            'relationship' => 'sibling',
            'group' => 'weight',
          ],
        ],
      ];
      $this->entities = $this->loadSectionLinks($section_key);
      $delta = 10;
      // Change the delta of the weight field if have more than 20 entities.
      if (!empty($this->weightKey)) {
        $count = count($this->entities);
        if ($count > 20) {
          $delta = ceil($count / 2);
        }
      }
      foreach ($this->entities as $entity) {
        $row = $this->buildRow($entity);
        if (isset($row['label'])) {
          $row['label'] = ['#markup' => $row['label']];
        }
        if (isset($row['weight'])) {
          $row['weight']['#delta'] = $delta;
        }
        $form[$section_key][$entity->id()] = $row;
      }
    }
    return $form;
  }

  /**
   * Load the links within specific section.
   *
   * @param string $section
   *   The section's name.
   *
   * @return array
   *   List of footer links.
   */
  public function loadSectionLinks(string $section): array {
    $query = $this->getStorage()->getQuery()->condition('section', $section)->sort($this->entityType->getKey('weight'));
    $entity_ids = $query->execute();
    $entities = $this->storage->loadMultipleOverrideFree($entity_ids);

    // Sort the entities using the entity class's sort() method.
    // See \Drupal\Core\Config\Entity\ConfigEntityBase::sort().
    uasort($entities, [$this->entityType->getClass(), 'sort']);
    return $entities;
  }

  /**
   * Get the sections of footer links.
   *
   * @return array
   *   Return declared regions of table.
   */
  public function getSections(): array {
    $sections = [];
    $entities = $this->sectionStorage->loadMultiple();
    foreach ($entities as $entity) {
      $sections[$entity->id()] = $entity->label();
    }

    return $sections;
  }

}
