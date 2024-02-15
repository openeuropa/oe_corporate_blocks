<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\oe_corporate_blocks\Entity\FooterLinkSocial;

/**
 * Form class for the Footer Link Social configuration entity.
 */
class FooterLinkSocialForm extends FooterLinkFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $footer_link_social */
    $footer_link_social = $this->entity;
    $form['social_network'] = [
      '#type' => 'select',
      '#title' => $this->t('Social network'),
      '#default_value' => $footer_link_social->get('social_network'),
      '#options' => FooterLinkSocial::$allowedSocialNetworks,
      '#required' => FALSE,
      '#empty_value' => '',
    ];

    $form = parent::form($form, $form_state);

    $form['id']['#machine_name']['exists'] = '\Drupal\oe_corporate_blocks\Entity\FooterLinkSocial::load';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $footer_link_general = $this->entity;
    $status = $footer_link_general->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('The "%label" social footer link has been created.', [
          '%label' => $footer_link_general->label(),
        ]));
        break;

      default:
        $this->messenger()->addStatus($this->t('The "%label" social footer link has been updated.', [
          '%label' => $footer_link_general->label(),
        ]));
    }
    $form_state->setRedirectUrl($footer_link_general->toUrl('collection'));
  }

}
