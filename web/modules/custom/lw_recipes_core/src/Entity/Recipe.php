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
  
    $viewBuilder = \Drupal::service('lw_recipes_core.related_recipes');
    return $viewBuilder->build($this);
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

}