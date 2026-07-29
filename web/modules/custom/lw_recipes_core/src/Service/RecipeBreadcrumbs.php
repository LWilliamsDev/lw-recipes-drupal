<?php

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\lw_recipes_core\Entity\Recipe;
use Drupal\taxonomy\TermInterface;

class RecipeBreadcrumbs {

  use StringTranslationTrait;

  public function __construct(
    protected RecipeSettings $recipeSettings,
  ) {}

  /**
   * Build breadcrumbs for a recipe.
   */
  public function build(Recipe $recipe): array {
    $breadcrumbs = [];

    // Recipe listing page.
    $breadcrumbs[] = [
      'url' => $this->recipeSettings->getListingUrl(),
      'text' => $this->t('Recipes'),
    ];


    // Course taxonomy.
    if (!$recipe->get('course')->isEmpty()) {
      $term = $recipe->get('course')->entity;

      if ($term instanceof TermInterface) {
        $breadcrumbs[] = [
          'url' => $this->recipeSettings->buildListingUrl(
            'course',
            $term->id()
          ),
          'text' => $term->label(),
        ];
      }
    }


    // Diet taxonomy.
    if (!$recipe->get('diet')->isEmpty()) {
      $term = $recipe->get('diet')->entity;

      if ($term instanceof TermInterface) {
        $breadcrumbs[] = [
          'url' => $this->recipeSettings->buildListingUrl(
            'diet',
            $term->id()
          ),
          'text' => $term->label(),
        ];
      }
    }


    return $breadcrumbs;
  }

}