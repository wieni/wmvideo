<?php

namespace Drupal\wmvideo\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldType\LinkItem;
use Drupal\wmvideo\VideoEmbedder;

/**
 * Plugin implementation of the 'video_embed_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "video_embed_formatter",
 *   label = @Translation("Video Embed"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class VideoEmbedFormatter extends FormatterBase {
    
    /**
     * {@inheritdoc}
     */
    public static function defaultSettings()
    {
        return array(// Implement default settings.
        ) + parent::defaultSettings();
    }
    
    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state)
    {
        return array(// Implement settings form.
        ) + parent::settingsForm($form, $form_state);
    }
    
    /**
     * {@inheritdoc}
     */
    public function settingsSummary()
    {
        $summary = [];
        
        // Implement settings summary.
        
        return $summary;
    }
    
    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $elements = [];
        
        foreach ($items as $delta => $item) {
            $elements[$delta] = VideoEmbedder::create($item->getUrl()
                ->getUri());
        }
        
        return $elements;
    }
    
    /**
     * Generate the output appropriate for one field item.
     *
     * @param \Drupal\Core\Field\FieldItemInterface $item
     *   One field item.
     *
     * @return string
     *   The textual output generated.
     */
    protected function viewValue(LinkItem $item)
    {
        // The text value has no text format assigned to it, so the user input
        // should equal the output, including newlines.
        $url = $item->getUrl();
        dump($url->getUri());
        
        return nl2br($url->getUri());
    }
}
