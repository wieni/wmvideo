<?php

namespace Drupal\wmvideo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;

/**
 * Plugin implementation of the 'video_embed_widget' widget.
 *
 * @FieldWidget(
 *   id = "video_embed_widget",
 *   label = @Translation("Video Embed"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class VideoEmbedWidget extends LinkWidget
{
    
    
    /**
     * {@inheritdoc}
     */
    public static function defaultSettings()
    {
        return [
            'placeholder_url' => 'https://www.youtube.com/watch?v=akud5GL6rRQ'
        ];
    }
    
    /**
     * Form element validation handler for the 'uri' element.
     *
     * Disallows saving inaccessible or untrusted URLs.
     */
    public static function validateUriElement(
        $element,
        FormStateInterface $form_state,
        $form
    ) {
        $uri = static::getUserEnteredStringAsUri($element['#value']);
        
        if (!empty($uri)) {
            $regexp = [
                '/youtube\.com\/watch\?v=([a-z0-9\-_]+)/i',
                '/youtu.be\/([a-z0-9\-_]+)/i',
                '/youtube\.com\/v\/([a-z0-9\-_]+)/i',
                '/vimeo\.(.*?)\/([0-9]+)/i',
            ];
            
            $match = FALSE;
            foreach ($regexp as $regex) {
                if (preg_match($regex, $uri)) {
                    $match = TRUE;
                    break;
                }
            }
            
            if (!$match) {
                $form_state->setError(
                    $element,
                    t('Entered URL is not a valid YouTube or Vimeo URL.')
                );
                
                return;
            }
        }
        
        parent::validateUriElement($element, $form_state, $form);
    }
    
    /**
     * {@inheritdoc}
     */
    public function formElement(
        FieldItemListInterface $items,
        $delta,
        array $element,
        array &$form,
        FormStateInterface $form_state
    ) {
        $element = parent::formElement($items, $delta, $element, $form,
            $form_state);
        
        $element['uri']['#description'] = $this->t('This must be a YouTube or Vimeo URL such as %url.',
            array('%url' => 'https://www.youtube.com/watch?v=akud5GL6rRQ'));
        
        return $element;
    }
    
    // https://www.youtube.com/watch?v=akud5GL6rRQ
    
    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state)
    {
        $elements = parent::settingsForm($form, $form_state);
        unset($elements['placeholder_title']);
        
        return $elements;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function supportsInternalLinks()
    {
        return FALSE;
    }
    
    
    /**
     * {@inheritdoc}
     */
    protected function supportsExternalLinks()
    {
        return TRUE;
    }
}
