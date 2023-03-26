<?php

namespace App\Validator;

use App\Repository\VeterinaireRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class EventAtTheSameTimeValidator extends ConstraintValidator
{
    private VeterinaireRepository $veterinaireRepository;

    public function __construct(VeterinaireRepository $veterinaireRepository)
    {
        $this->veterinaireRepository = $veterinaireRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint EventAtTheSameTime */

        if (!$value->getDate() || !$value->getVeterinaire()) {
            return;
        }

        $event = $this->veterinaireRepository->findByStartingTimeAndVeterinaire($value->getDate(), $value->getVeterinaire());
        if ($event && $event !== $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getDate()->format('Y-m-d H:i:s'))
                ->setParameter('{{ value2 }}', $value->getVeterinaire()->getNom())
                ->addViolation();
        }
    }
}
