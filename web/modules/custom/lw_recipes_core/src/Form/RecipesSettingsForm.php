<?php

declare(strict_types=1);

namespace Drupal\lw_recipes_core\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class RecipesSettingsForm extends ConfigFormBase {

  public function getFormId(): string {
    return 'lw_recipes_settings';
  }

  protected function getEditableConfigNames(): array {
    return ['lw_recipes.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('lw_recipes.settings');

    $form['recipe_listing_page'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Recipe listing page'),
      '#description' => $this->t('Select the page containing the recipe listing.'),
      '#target_type' => 'node',
      '#selection_settings' => [
        'target_bundles' => ['listing_page'],
      ],
      '#default_value' => $config->get('recipe_listing_page')
        ? Node::load($config->get('recipe_listing_page'))
        : NULL,
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->configFactory()
      ->getEditable('lw_recipes.settings')
      ->set('recipe_listing_page', $form_state->getValue('recipe_listing_page'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}