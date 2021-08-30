<?php

namespace Drupal\wmvideo\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\wmvideo\Service\VideoInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VideoValidator extends ConstraintValidator implements ContainerInjectionInterface
{
    /** @var VideoInfo */
    protected $videoInfo;

    public static function create(ContainerInterface $container)
    {
        $instance = new static();
        $instance->videoInfo = $container->get('wmvideo.video_info');

        return $instance;
    }

    public function validate($value, Constraint $constraint): void
    {
        $values = $value instanceof \Traversable ? $value : [$value];

        foreach ($values as $value) {
            if ($value instanceof FieldItemInterface) {
                $value = $value->value;
            }

            if (!$this->videoInfo->validate($value)) {
                $this->context->addViolation($constraint->message, ['%value' => $value]);
            }
        }
    }
}
