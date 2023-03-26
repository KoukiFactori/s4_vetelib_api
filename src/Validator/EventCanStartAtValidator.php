<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EventCanStartAtValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\EventCanStartAt $constraint */

        // TODO: implement the validation here
        if (00 != intval($value->format('i')) && 30 != intval($value->format('i'))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->format('i'))
                ->addViolation();
        }
    }
}