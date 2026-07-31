<?php 

declare(strict_types=1);

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Service class to build previous/next navigation on recipe detail pages.
 */

class RecipePostNav {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * Get the adjacent (previous or next) recipe node.
   */
  public function getAdjacentRecipe(NodeInterface $current_recipe, string $direction = 'next'): ?NodeInterface {
    $storage = $this->entityTypeManager->getStorage('node');
    
    // Determine sort direction and query operator based on target navigation
    $operator = ($direction === 'next') ? '>' : '<';
    $sort = ($direction === 'next') ? 'ASC' : 'DESC';

    $query = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'recipe')
      ->condition('status', NodeInterface::PUBLISHED)
      ->condition('created', $current_recipe->getCreatedTime(), $operator)
      ->sort('created', $sort)
      ->range(0, 1); // We only need the single next immediate item

    $nids = $query->execute();

    if (empty($nids)) {
      return NULL;
    }

    return $storage->load(reset($nids));
  }
}