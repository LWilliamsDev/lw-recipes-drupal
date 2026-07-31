<?php

declare(strict_types=1);

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lw_recipes_core\Entity\Recipe;

class RecipeViewBuilder {

  /**
   * Constructs a new RecipeViewBuilder object.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * Core execution engine to safely render ANY view display with arguments.
   *
   * This is your "one function to rule them all," but kept internal/protected.
   *
   * @param string $view_id
   *   The machine name of the view.
   * @param string $display_id
   *   The display machine name (e.g., 'block_1', 'embed_1').
   * @param array $args
   *   Contextual filter arguments to pass to the view.
   *
   * @return array
   *   A Drupal render array.
   */
  protected function executeView(string $view_id, string $display_id, array $args = []): array {
    $view_storage = $this->entityTypeManager->getStorage('view')->load($view_id);
    if (!$view_storage) {
      return [];
    }

    $view = $view_storage->getExecutable();
    $view->setDisplay($display_id);
    
    if (!empty($args)) {
      $view->setArguments($args);
    }
    
    $view->execute();

    // Check if the view actually yielded results to avoid empty wrappers
    return empty($view->result) ? [] : $view->buildRenderable();
  }

  /**
   * Build the related recipes view.
   * 
   * Encapsulates the field-mining logic beautifully.
   */
  public function buildRelated(Recipe $recipe): array {
    $diet_id = !$recipe->get('diet')->isEmpty() ? $recipe->get('diet')->target_id : NULL;

    return $this->executeView('recipe', 'related_recipes', [$diet_id, $recipe->id()]);
  }

  /**
   * Build the latest recipes view.
   */
  public function buildLatest(): array {
    return $this->executeView('recipe', 'latest_recipes');
  }

  /**
   * Build a recipe view filtered by a taxonomy vocabulary.
   */
  public function buildVocabulary(string $vocabulary_id, string $display_id): array {
    return $this->executeView('recipe_taxonomy', $display_id, [$vocabulary_id]);
  }

}