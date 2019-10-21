<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form class for the footer general link configuration entity.
 */
class FooterGeneralLinkForm extends EntityForm {

  /**
   * The constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $footer_general_link */
    $footer_general_link = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $footer_general_link->label(),
      '#description' => $this->t('Footer general link label. Accepts tokens.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $footer_general_link->id(),
      '#machine_name' => [
        'exists' => '\Drupal\oe_corporate_blocks\Entity\FooterGeneralLink::load',
      ],
      '#disabled' => !$footer_general_link->isNew(),
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#maxlength' => 255,
      '#default_value' => $footer_general_link->get('url'),
      '#description' => $this->t('Footer general link URL.'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $url = $form_state->getValue('url');
    if (parse_url($url, PHP_URL_SCHEME) !== NULL) {
      // We have nothing to validate on external URLs.
      return;
    }

    try {
      Url::fromUserInput($url);
    }
    catch (\InvalidArgumentException $exception) {
      $form_state->setErrorByName('url', $exception->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $footer_general_link = $this->entity;
    $status = $footer_general_link->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger->addStatus($this->t('The "%label" general footer link has been created.', [
          '%label' => $footer_general_link->label(),
        ]));
        break;

      default:
        $this->messenger->addStatus($this->t('The "%label" general footer link has been updated.', [
          '%label' => $footer_general_link->label(),
        ]));
    }
    $form_state->setRedirectUrl($footer_general_link->toUrl('collection'));
  }

}
