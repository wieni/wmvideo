<?php

namespace Drupal\wmvideo\Plugin\Validation\Constraint;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Validation\Attribute\Constraint;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

#[Constraint(
    id: 'video',
    label: new TranslatableMarkup('Valid video URL', [], ['context' => 'Validation']),
)]
class Video extends SymfonyConstraint
{
    /** @var string */
    public $message = '%value is not a valid video.';
}
