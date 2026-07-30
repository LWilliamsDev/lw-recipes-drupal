<?php

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\lw_recipes_core\Entity\Recipe;
use Drupal\taxonomy\TermInterface;

/**
 * Service to handle recipe breadcrumbs and general recipe-related navigation links.
 */
class RecipeLinks {

  use StringTranslationTrait;

  public function __construct(
    protected RecipeSettings $recipeSettings,
  ) {}

  /**
   * Build breadcrumbs for a recipe.
   */
  public function buildBreadcrumbs(Recipe $recipe): array {
    $breadcrumbs = [];

    // Recipe listing page.
    $breadcrumbs[] = [
      'url' => $this->recipeSettings->getListingUrl(),
      'text' => $this->t('Recipes'),
    ];

    // Course taxonomy.
    if ($courseLink = $this->getRecipeTermLink($recipe, 'course')) {
      $breadcrumbs[] = $courseLink;
    }

    // Diet taxonomy.
    if ($dietLink = $this->getRecipeTermLink($recipe, 'diet')) {
      $breadcrumbs[] = $dietLink;
    }

    return $breadcrumbs;
  }

  /**
   * Extracts a specific taxonomy term field from a recipe and builds its link array.
   * 
   * Useful for external contexts when you have the Recipe object and want a specific field.
   */
  public function getRecipeTermLink(Recipe $recipe, string $fieldName): ?array {
    if (!$recipe->hasField($fieldName) || $recipe->get($fieldName)->isEmpty()) {
      return null;
    }

    $term = $recipe->get($fieldName)->entity;
    if ($term instanceof TermInterface) {
      return $this->buildTermLink($term, $fieldName);
    }

    return null;
  }

  /**
   * Generates a generic link array directly from a taxonomy term.
   * 
   * Highly generic. Use this when you already have the TermInterface object 
   * in another context (e.g., inside a controller, preprocess function, or block).
   */
  public function buildTermLink(TermInterface $term, string $type): array {
    return [
      'url' => $this->recipeSettings->buildListingUrl($type, $term->id()),
      'text' => $term->label(),
    ];
  }

}