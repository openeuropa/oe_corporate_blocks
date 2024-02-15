<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form class for the "Footer link section" configuration entity.
 */
class FooterLinkSectionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkSectionInterface $section */
    $section = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $section->label(),
      '#description' => $this->t('Link section label.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $section->id(),
      '#machine_name' => [
        'exists' => '\Drupal\oe_corporate_blocks\Entity\FooterLinkSection::load',
      ],
      '#disabled' => !$section->isNew(),
    ];

    $form['weight'] = [
      '#type' => 'hidden',
      '#value' => $section->getWeight() ?? 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $section = $this->entity;

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('The "%label" footer link section has been created.', [
          '%label' => $section->label(),
        ]));
        break;

      default:
        $this->messenger()->addStatus($this->t('The "%label" footer link section has been updated.', [
          '%label' => $section->label(),
        ]));
    }
    $form_state->setRedirectUrl($section->toUrl('collection'));
  }

}
