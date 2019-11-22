<?php

declare(strict_types = 1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form class for the Footer Link General configuration entity.
 */
class FooterLinkGeneralForm extends EntityForm {

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

    /** @var \Drupal\oe_corporate_blocks\Entity\FooterLinkInterface $footer_link_general */
    $footer_link_general = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $footer_link_general->label(),
      '#description' => $this->t('Footer Link General label.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $footer_link_general->id(),
      '#machine_name' => [
        'exists' => '\Drupal\oe_corporate_blocks\Entity\FooterGeneralLink::load',
      ],
      '#disabled' => !$footer_link_general->isNew(),
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#maxlength' => 255,
      '#default_value' => $footer_link_general->get('url'),
      '#description' => $this->t('Footer general link URL. We only support internal links (like /node/1) and external.'),
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
    $footer_link_general = $this->entity;
    $status = $footer_link_general->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger->addStatus($this->t('The "%label" general footer link has been created.', [
          '%label' => $footer_link_general->label(),
        ]));
        break;

      default:
        $this->messenger->addStatus($this->t('The "%label" general footer link has been updated.', [
          '%label' => $footer_link_general->label(),
        ]));
    }
    $form_state->setRedirectUrl($footer_link_general->toUrl('collection'));
  }

}
