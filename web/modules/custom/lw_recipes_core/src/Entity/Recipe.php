<?php

namespace Drupal\lw_recipes_core\Entity;
use Drupal\Core\Entity\Attribute\Bundle;


use Drupal\node\Entity\Node;

/**
 * Bundle class for Recipe nodes
 */

#[Bundle(
  entityType: 'node',
  bundle: 'recipe'
)]
class Recipe extends Node {

  public function getRelatedRecipes(): array {
  
    $viewBuilder = \Drupal::service('lw_recipes_core.recipe_view_builder');
    return $viewBuilder->buildRelated($this);
  }

  protected function getNavigationService() {
    return \Drupal::service('lw_recipes_core.post_nav');
  }

 public function getPreviousRecipe(): array|null {
  $node = $this->getNavigationService()->getAdjacentRecipe($this, 'previous');
  
  
  if (!$node) {
    return NULL;
  }

  // We wrap the node in a tiny render array so we can attach the list cache tag
  return [
    '#type' => 'entity',
    '#url' => $node->toUrl()->toString(),
    '#view_mode' => 'default', // Not strictly used since you call properties, but required for structural rendering
    '#cache' => [
      'tags' => ['node_list:recipe'],
    ],
  ];
}

public function getNextRecipe(): array|null {
  $node = $this->getNavigationService()->getAdjacentRecipe($this, 'next');
  
  if (!$node) {
    return NULL;
  }

  // We wrap the node in a tiny render array so we can attach the list cache tag
  return [
    '#type' => 'entity',
    '#url' => $node->toUrl()->toString(),
    '#view_mode' => 'default', // Not strictly used since you call properties, but required for structural rendering
    '#cache' => [
      'tags' => ['node_list:recipe'],
    ],
  ];
}

public function getRecipeTermLinks($fieldName): array {
  return \Drupal::service('lw_recipes_core.recipe_links')->getRecipeTermLinks($this, $fieldName);
}

/**
 * Gets combined term links from multiple fields, sorted alphabetically.
 *
 * @param array $fieldNames
 *   An array of field machine names to aggregate (e.g., ['diet', 'course']).
 *
 * @return array
 *   A single, combined array of link arrays sorted alphabetically by text.
 */
public function getCombinedAlphabetizedTermLinks(array $fieldNames): array {
  $combinedLinks = [];

  $links_service =  \Drupal::service('lw_recipes_core.recipe_links');

  // Gather all links across the requested fields.
  foreach ($fieldNames as $fieldName) {
    // Reuses your existing bundle method.
    if ($links = $links_service->getRecipeTermLinks($this, $fieldName)) {
      $combinedLinks = array_merge($combinedLinks, $links);
    }
  }

  
  // Alphabetize the combined result.
  usort($combinedLinks, fn($a, $b) => strcasecmp($a['text'], $b['text']));

  return $combinedLinks;
}

}