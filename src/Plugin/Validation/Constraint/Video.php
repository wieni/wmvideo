<?php

namespace Drupal\wmvideo\Plugin\Validation\Constraint;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Validation\Attribute\Constraint;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

#[Constraint(
    id: 'video',
    label: new TranslatableMarkup('Valid video URL', [], ['context' => 'Validation']),
)]
class Video extends SymfonyConstraint
{
    /** @var string */
    public $message = '%value is not a valid video.';

    /**
     * Symfony 8 no longer evaluates an options array in the base Constraint,
     * and Drupal 12 passes the constraint options as named arguments.
     */
    #[HasNamedArguments]
    public function __construct(?string $message = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct(null, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
