<?php

namespace App\Validator;

use App\Repository\VeterinaireRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EventAtTheSameTimeValidator extends ConstraintValidator
{   
    
    public function __construct(Private VeterinaireRepository $vr){}
    public function validate($value, Constraint $constraint)
    {
    /* @var App\Validator\EventAtTheSameTime $constraint */

    if (null === $value || '' === $value) {
        return;
    }

    // TODO: implement the validation here
    if (null != $this->vr->findEventByStartingTime($value) or ($value->format('i') != '00' or '30')) {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value->format('Y-m-d H:i:s'))
            ->addViolation();
    }
}
}
