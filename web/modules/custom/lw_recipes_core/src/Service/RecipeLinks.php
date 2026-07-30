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

    // Course taxonomy (Single or Multi-value handled implicitly via array_merge).
    $courseLinks = $this->getRecipeTermLinks($recipe, 'course');
    $breadcrumbs = array_merge($breadcrumbs, $courseLinks);

    // Diet taxonomy.
    $dietLinks = $this->getRecipeTermLinks($recipe, 'diet');
    $breadcrumbs = array_merge($breadcrumbs, $dietLinks);

    return $breadcrumbs;
  }

  /**
   * Extracts taxonomy term fields from a recipe and builds their link arrays.
   *
   * Handles both single-value and multi-value fields seamlessly.
   *
   * @param \Drupal\lw_recipes_core\Entity\Recipe $recipe
   *   The recipe entity.
   * @param string $fieldName
   *   The machine name of the taxonomy term reference field.
   *
   * @return array
   *   An array of link arrays, or an empty array if none are found.
   */
  public function getRecipeTermLinks(Recipe $recipe, string $fieldName): array {
    if (!$recipe->hasField($fieldName) || $recipe->get($fieldName)->isEmpty()) {
      return [];
    }

    $links = [];
    // Loop through referenced entities to natively support multi-value fields.
    foreach ($recipe->get($fieldName)->referencedEntities() as $term) {
      if ($term instanceof TermInterface) {
        $links[] = $this->buildTermLink($term, $fieldName);
      }
    }

    return $links;
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