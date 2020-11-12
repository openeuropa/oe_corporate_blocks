<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form class for the Footer Link General configuration entity.
 */
class FooterLinkGeneralForm extends FooterLinkFormBase {

  /**
   * Footer link section entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $sectionStorage;

  /**
   * FooterLinkGeneralForm constructor.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $section_storage
   *   The footer link section entity storage.
   */
  public function __construct(EntityStorageInterface $section_storage) {
    $this->sectionStorage = $section_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('footer_link_section')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $footer_link_general */
    $footer_link_general = $this->entity;

    $form['section'] = [
      '#type' => 'select',
      '#title' => $this->t('Section'),
      '#options' => $this->getSections(),
      '#default_value' => $footer_link_general->get('section'),
      '#description' => $this->t('The section the link will appear under.'),
      '#required' => TRUE,
      '#weight' => -1,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $footer_link_general = $this->entity;

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('The "%label" general footer link has been created.', [
          '%label' => $footer_link_general->label(),
        ]));
        break;

      default:
        $this->messenger()->addStatus($this->t('The "%label" general footer link has been updated.', [
          '%label' => $footer_link_general->label(),
        ]));
    }
    $form_state->setRedirectUrl($footer_link_general->toUrl('collection'));
  }

  /**
   * Get the sections of footer links.
   *
   * @return array
   *   Return declared regions of table.
   */
  protected function getSections(): array {
    $sections = [];
    $entities = $this->sectionStorage->loadMultiple();
    foreach ($entities as $entity) {
      $sections[$entity->id()] = $entity->label();
    }

    return $sections;
  }

}
