<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EventBeforeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\EventBefore $constraint */


        if(intval($value->format('H')) < 8 || intval($value->format('H')) > 18){
            $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value->format('H:i'))
            ->addViolation();
        }

    }
}
