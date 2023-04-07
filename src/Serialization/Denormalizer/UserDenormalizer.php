<?php

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private Security $security
    ) {
    }

    public function supportsDenormalization($data, string $type, ?string $format = null, array $context = []): bool
    {
        return !isset($context[self::ALREADY_CALLED]) && (User::class == $type);
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = []): mixed
    {
        $context[self::ALREADY_CALLED] = true;

        if (isset($data['password'])) {
            $data['password'] = $this->passwordHasher->hashPassword(
                $this->security->getUser(),
                $data['password']
            );
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}
