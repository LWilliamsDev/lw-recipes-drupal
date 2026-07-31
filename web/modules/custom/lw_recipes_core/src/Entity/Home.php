<?php

declare(strict_types=1);

namespace Drupal\lw_recipes_core\Entity;
use Drupal\Core\Entity\Attribute\Bundle;


use Drupal\node\Entity\Node;

/**
 * Bundle class for Recipe nodes
 */

#[Bundle(
  entityType: 'node',
  bundle: 'home'
)]
class Home extends Node {

public function getLatestRecipes(): array {
  $viewBuilder = \Drupal::service('lw_recipes_core.recipe_view_builder');
  return $viewBuilder->buildLatest();
}

public function getTaxonomyTerms(): array {
   $viewBuilder = \Drupal::service('lw_recipes_core.recipe_view_builder');

   $taxonomy_machine_name = $this->get('taxonomy')->target_id;
   
   return $viewBuilder->buildVocabulary($taxonomy_machine_name, 'recipe_taxonomy');

}

}