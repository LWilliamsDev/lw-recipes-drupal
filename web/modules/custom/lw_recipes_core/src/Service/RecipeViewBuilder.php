<?php

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lw_recipes_core\Entity\Recipe;
use Drupal\taxonomy\TermInterface;

class RecipeViewBuilder {

  /**
   * Constructs a new RecipeViewBuilder object.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

 /**
   * Helper to load view storage natively.
   * 
   * @param string $view_id
   *   The machine name of the view (defaults to 'recipe').
   */
  protected function getView(string $view_id = 'recipe') {
    // Dynamic loading allows this builder to handle multiple views.
    $view = $this->entityTypeManager->getStorage('view')->load($view_id);
    if (!$view) {
      return NULL;
    }
    return $view->getExecutable();
  }

  /**
   * Build the related recipes view (with contextual filters).
   */
  public function buildRelated(Recipe $recipe): array {
    $view = $this->getView();
    if (!$view) {
      return [];
    }

    $view->setDisplay('related_recipes');

    $diet_id = NULL;
    if (!$recipe->get('diet')->isEmpty()) {
      $diet_id = $recipe->get('diet')->target_id;
    }

    $view->setArguments([$diet_id, $recipe->id()]);
    $view->execute();

    return empty($view->result) ? [] : $view->buildRenderable();
  }

  /**
   * Build the latest recipes view (no contextual filters).
   */
  public function buildLatest(): array {
    $view = $this->getView();
    if (!$view) {
      return [];
    }

    $view->setDisplay('latest_recipes');
    
    $view->execute();

    return empty($view->result) ? [] : $view->buildRenderable();
  }

  /**
   * Build a recipe view filtered by a specific taxonomy vocabulary machine name.
   *
   * @param string $vocabulary_id
   *   The vocabulary machine name (e.g., 'diet', 'course').
   * @param string $display_id
   *   The Views display ID (e.g., 'recipes_by_vocabulary_listing').
   *
   * @return array
   *   A Drupal render array for the view execution.
   */
  public function buildVocabulary(string $vocabulary_id, string $display_id): array {
    $view = $this->getView('recipe_taxonomy');
    if (!$view) {
      return [];
    }

    $view->setDisplay($display_id);

    // Pass the vocabulary machine name string as the contextual filter argument.
    $view->setArguments([$vocabulary_id]);
    $view->execute();

    // If the view returns no results, return an empty array so nothing renders.
    return empty($view->result) ? [] : $view->buildRenderable();
  }

}