<?php

namespace Drupal\lw_recipes_core\Entity;
use Drupal\Core\Entity\Attribute\Bundle;


use Drupal\paragraphs\Entity\Paragraph;

/**
 * Bundle class for Recipe nodes
 */

#[Bundle(
  entityType: 'paragraph',
  bundle: 'social_media_link'
)]
class SocialMediaLinkParagraph extends Paragraph {

  public function getSocialMediaIcon(): string|null {

    $icon_map = [
      'facebook' => 'fb.svg',
      'instagram' => 'instagram.svg',
      'linkedin' => 'linked-in.svg',
      'youtube' => 'youtube.svg'
    ];

    if ($this->hasField('social_media_site') && !$this->get('social_media_site')->isEmpty()) {
        $icon = $this->get('social_media_site')->value;

        $theme_path = \Drupal::service('extension.list.theme')->getPath('lw_recipes');
        $asset_path = $theme_path . '/assets/img/' . $icon_map[$icon];
        
        return \Drupal::service('file_url_generator')->generateString($asset_path);
    }

    return null;
  }

  public function getUrl(): string|null {
    if ($this->hasField('social_media_url') && !$this->get('social_media_url')->isEmpty()) {
        return $this->get('social_media_url')->getValue()[0]['uri'];
    }

    return null;
  }
}