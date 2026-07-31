<?php

declare(strict_types=1);

namespace Drupal\lw_recipes_core\Entity;

use Drupal\Core\Entity\Attribute\Bundle;
use Drupal\taxonomy\Entity\Term;


#[Bundle(
  entityType: 'taxonomy_term',
  bundle: 'protein',
)]
class ProteinTerm extends Term {

  public function getLink() {
    $vocabulary_name = $this->bundle();

     return \Drupal::service('lw_recipes_core.recipe_links')->buildTermLink($this, $vocabulary_name);
  }
  
}