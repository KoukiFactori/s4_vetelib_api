<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AuthenticatedUserAnimalValidator extends ConstraintValidator
{
    public function __construct(private Security $security)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\AuthenticatedUserAnimal $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->security->isGranted('ROLE_CLIENT')) {
            if ($value !== $this->security->getUser()) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
