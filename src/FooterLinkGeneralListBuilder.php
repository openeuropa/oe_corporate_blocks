<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks;

use Drupal\Core\Config\Entity\DraggableListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\StringTranslation\TranslatableMarkup;
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
    return 'footer_link_general_overview_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['url'] = $this->t('URL');
    $header['section'] = $this->t('Section');

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
    $row['section'] = [
      '#type' => 'select',
      '#options' => $this->getSectionsAsOptions(),
      '#default_value' => $entity->get('section'),
      '#attributes' => [
        'class' => ['section'],
      ],
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Organise site specific footer links per section. Sections without links will not be displayed. To hide a link move it in the "Disabled" section.'),
    ];
    $form = parent::buildForm($form, $form_state);
    $table = &$form[$this->entitiesKey];

    // Extract table rows, so we can print them in the proper order.
    $entity_rows = [];
    foreach (Element::children($table) as $id) {
      $entity_rows[$id] = $table[$id];
      unset($table[$id]);
    }

    // Re-compose the table by stacking rows in the correct order.
    foreach ($this->getSections() as $section) {
      $label = $this->t('@name section', ['@name' => $section->label()]);
      $table['section.' . $section->id()] = $this->buildSectionRow($label, $section->id());
      foreach ($this->getLinksBySection($section->id()) as $entity) {
        $table[$entity->id()] = $entity_rows[$entity->id()];
      }
    }

    // Stack links without section in the "Disabled" area.
    $table['section.hidden'] = $this->buildSectionRow($this->t('Disabled'), '');
    foreach ($this->getLinksWithoutSection() as $entity) {
      $table[$entity->id()] = $entity_rows[$entity->id()];
    }

    // Include library that sets regions when rearranging links.
    $table['#attached']['library'][] = 'oe_corporate_blocks/footer_link_general_list_builder';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    foreach ($form_state->getValue($this->entitiesKey) as $id => $value) {
      if (isset($this->entities[$id])) {
        $this->entities[$id]->set('section', $value['section']);
        $this->entities[$id]->save();
      }
    }
  }

  /**
   * Build a table section row.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup $label
   *   Section label.
   * @param string $id
   *   Section ID.
   *
   * @return array
   *   Table row array.
   */
  protected function buildSectionRow(TranslatableMarkup $label, string $id): array {
    $row['label'] = [
      '#markup' => "<b>{$label}</b>",
    ];
    $row['url'] = [];
    $row['section'] = [];
    $row['weight'] = [
      '#type' => 'weight',
      '#default_value' => 0,
      '#attributes' => ['class' => ['weight']],
    ];
    $row['operations'] = [];
    $row['#attributes']['class'][] = 'tabledrag-root';
    $row['#attributes']['data-link-section-id'] = $id;

    return $row;
  }

  /**
   * Load section entities, sorted by their weight.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Section entities, sorted by weight.
   */
  public function getSections(): array {
    $entity_ids = $this->sectionStorage->getQuery()->sort('weight')->execute();
    return $this->sectionStorage->loadMultiple($entity_ids);
  }

  /**
   * Get links by section, sorted by their weight.
   *
   * @param string $section
   *   Section ID.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Link entities, sorted by weight.
   */
  public function getLinksBySection(string $section): array {
    $entity_ids = $this->storage->getQuery()
      ->condition('section', $section)
      ->sort('weight')
      ->execute();
    return $this->storage->loadMultiple($entity_ids);
  }

  /**
   * Get links without a section, sorted by their weight.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Link entities, sorted by weight.
   */
  public function getLinksWithoutSection(): array {
    $entity_ids = $this->storage->getQuery()
      ->condition('section', NULL)
      ->sort('weight')
      ->execute();
    return $this->storage->loadMultiple($entity_ids);
  }

  /**
   * Get the sections suitable for a select "#options" property.
   *
   * @return array
   *   List of section names, keyed by their ID.
   */
  public function getSectionsAsOptions(): array {
    $sections = ['' => $this->t('- Disabled -')];
    foreach ($this->getSections() as $entity) {
      $sections[$entity->id()] = $entity->label();
    }

    return $sections;
  }

}
