<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form class for the Footer Link General configuration entity.
 */
class FooterLinkGeneralForm extends FooterLinkFormBase {

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
      '#options' => [
        'contact_us' => $this->t('Contact us'),
        'about_us' => $this->t('About us'),
        'related_sites' => $this->t('Related sites'),
      ],
      '#default_value' => $footer_link_general->get('section'),
      '#description' => $this->t('Footer general link Section. We have to use only predefined sections of general links.'),
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

}
