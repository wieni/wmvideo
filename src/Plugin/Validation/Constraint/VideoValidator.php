<?php

namespace Drupal\wmvideo\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\wmvideo\Service\VideoInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Traversable;

class VideoValidator extends ConstraintValidator implements ContainerInjectionInterface
{
    /** @var VideoInfo */
    protected $videoInfo;

    public function __construct(
        VideoInfo $videoInfo
    ) {
        $this->videoInfo = $videoInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $values = $value instanceof Traversable ? $value : [$value];

        foreach ($values as $value) {
            if ($value instanceof FieldItemInterface) {
                $value = $value->value;
            }

            if (!$this->videoInfo->validate($value)) {
                $this->context->addViolation($constraint->message, ['%value' => $value]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('wmvideo.video_info')
        );
    }
}
