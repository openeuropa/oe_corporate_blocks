<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form class for the breadcrumb root item configuration entity.
 */
class FooterSocialLinkForm extends FooterGeneralLinkForm {

  /**
   * The list of allowed social networks.
   *
   * @var array
   */
  protected $allowedSocialNetworks = [
    'twitter' => 'Twitter',
    'facebook' => 'Facebook',
    'instagram' => 'Instagram',
    'linkedin' => 'Linkedin',
  ];

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $footer_social_link */
    $footer_social_link = $this->entity;
    $form['social_network'] = [
      '#type' => 'select',
      '#title' => $this->t('Social network'),
      '#default_value' => $footer_social_link->get('social_network'),
      '#description' => $this->t('Footer general link label. Accepts tokens.'),
      '#options' => $this->allowedSocialNetworks,
      '#required' => FALSE,
      '#empty_value' => '',
    ];

    $form = parent::form($form, $form_state);

    $form['id']['#machine_name']['exists'] = '\Drupal\oe_corporate_blocks\Entity\FooterSocialLink::load';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $footer_general_link = $this->entity;
    $status = $footer_general_link->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger->addStatus($this->t('The "%label" social footer link has been created.', [
          '%label' => $footer_general_link->label(),
        ]));
        break;

      default:
        $this->messenger->addStatus($this->t('The "%label" social footer link has been updated.', [
          '%label' => $footer_general_link->label(),
        ]));
    }
    $form_state->setRedirectUrl($footer_general_link->toUrl('collection'));
  }

}
