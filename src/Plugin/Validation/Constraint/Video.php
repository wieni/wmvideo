<?php

namespace Drupal\wmvideo\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "video",
 *   label = @Translation("Valid video URL", context = "Validation"),
 * )
 */
class Video extends Constraint
{
    public $message = '%value is not a valid video.';
}
