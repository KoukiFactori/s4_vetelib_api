<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AuthenticatedUserEventValidator extends ConstraintValidator
{   
    public function __construct(private Security $security){}
    public function validate($value, Constraint $constraint)
    {
    /* @var App\Validator\AuthenticatedUserEvent $constraint */
 
    if ($this->security->isGranted('ROLE_CLIENT')) {
        if ($this->security->getUser()->getId() !== $value->getClient()->getId()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getId())
                ->addViolation();
        }
    }
    
}
}
