<?php

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class UserDenormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
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
