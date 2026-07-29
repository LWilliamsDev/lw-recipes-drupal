<?php

namespace Drupal\lw_recipes_core\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

class RecipeSettings {

  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  protected function getConfig() {
    return $this->configFactory->get('lw_recipes.settings');
  }

  public function getListingPageId(): ?int {
    return $this->getConfig()->get('recipe_listing_page');
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

  return $url . '?' . $filter . '=' . $term_id;
}

}