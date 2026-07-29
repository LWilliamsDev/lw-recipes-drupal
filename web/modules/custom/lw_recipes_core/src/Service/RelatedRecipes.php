<?php

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\lw_recipes_core\Entity\Recipe;
use Drupal\views\Views;

class RelatedRecipes {

  /**
   * Build the related recipes view.
   */
  public function build(Recipe $recipe): array {
    $view = Views::getView('recipe');

    if (!$view) {
      return [];
    }

    $view->setDisplay('related_recipes');

    $diet_id = NULL;

    if (!$recipe->get('diet')->isEmpty()) {
      $diet_id = $recipe->get('diet')->target_id;
    }

    $view->setArguments([
      $diet_id,
      $recipe->id(),
    ]);

    $view->execute();

    if (empty($view->result)) {
      return [];
    }

    return $view->buildRenderable();
  }

}