<?php

declare(strict_types=1);

namespace Drupal\oe_corporate_blocks\Form;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Form base class for the Footer Links configuration entity types.
 */
abstract class FooterLinkFormBase extends EntityForm {

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
        'exists' => '\Drupal\oe_corporate_blocks\Entity\FooterLinkGeneral::load',
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

    $form['weight'] = [
      '#type' => 'hidden',
      '#value' => $this->t('weight') ?? 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $uri = trim($form['url']['#value']);

    if (parse_url($uri, PHP_URL_SCHEME) === NULL) {
      if (strpos($uri, '<front>') !== FALSE && $uri !== '<front>') {
        // Only support the <front> token if it's on its own.
        $form_state->setError($form['url'], t('The path %uri is invalid.', ['%uri' => $uri]));
        return;
      }

      if (strpos($uri, '<front>') === 0) {
        $uri = '/' . substr($uri, strlen('<front>'));
      }
      $uri = 'internal:' . $uri;
    }

    // @see \Drupal\link\Plugin\Field\FieldWidget\LinkWidget::validateUriElement()
    if (parse_url($uri, PHP_URL_SCHEME) === 'internal' &&
      !in_array($form['url']['#value'][0], ['/', '?', '#'], TRUE) &&
      substr($form['url']['#value'], 0, strlen('<front>')) !== '<front>') {
      $form_state->setError($form['url'], t('The specified target is invalid. Manually entered paths should start with one of the following characters: / ? #'));
      return;
    }

    try {
      $url = Url::fromUri($uri);
      $url->toString(TRUE);
    }
    catch (\Exception $exception) {
      // Mark the url as invalid if any kind of exception is being thrown by
      // the Url class.
      $url = FALSE;
    }
    if ($url === FALSE || ($url->isExternal() && !in_array(parse_url($url->getUri(), PHP_URL_SCHEME), UrlHelper::getAllowedProtocols()))) {
      $form_state->setError($form['url'], t('The path %uri is invalid.', ['%uri' => $uri]));
    }
  }

}
