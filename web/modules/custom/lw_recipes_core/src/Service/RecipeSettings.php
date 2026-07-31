<?php

declare(strict_types=1);

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Service class to set up recipe settings config.
 * Primarily used to set the URL for the Recipe Listing page, so that this can be
 * used by other components to print pre-filtered links to the recipe listing page.
 */

class RecipeSettings {

  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  protected function getConfig() {
    return $this->configFactory->get('lw_recipes.settings');
  }

  public function getListingPageId(): ?int {
    return (int) $this->getConfig()->get('recipe_listing_page');
  }

  public function getListingPage(): ?NodeInterface {
    $nid = $this->getListingPageId();

    if (!$nid) {
      return NULL;
    }

    return $this->entityTypeManager
      ->getStorage('node')
      ->load($nid);
  }

  public function getListingUrl(): ?string {
    $node = $this->getListingPage();

    return $node?->toUrl()->toString();
  }

  public function buildListingUrl(string $filter, int $term_id): ?string {
  $url = $this->getListingUrl();

  if (!$url) {
    return NULL;
  }

  /* As of this writing, all listing taxonomy filters are multi-value. The below code assumes this
     is always the case. It will need to be changed if that changes. */ 

  return $url . '?' . $filter . '[]=' . $term_id;
}

}