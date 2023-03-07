<?php

namespace App\Factory;

use App\Entity\Veterinaire;
use App\Repository\VeterinaireRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Veterinaire>
 *
 * @method        Veterinaire|Proxy                     create(array|callable $attributes = [])
 * @method static Veterinaire|Proxy                     createOne(array $attributes = [])
 * @method static Veterinaire|Proxy                     find(object|array|mixed $criteria)
 * @method static Veterinaire|Proxy                     findOrCreate(array $attributes)
 * @method static Veterinaire|Proxy                     first(string $sortedField = 'id')
 * @method static Veterinaire|Proxy                     last(string $sortedField = 'id')
 * @method static Veterinaire|Proxy                     random(array $attributes = [])
 * @method static Veterinaire|Proxy                     randomOrCreate(array $attributes = [])
 * @method static VeterinaireRepository|RepositoryProxy repository()
 * @method static Veterinaire[]|Proxy[]                 all()
 * @method static Veterinaire[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Veterinaire[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Veterinaire[]|Proxy[]                 findBy(array $attributes)
 * @method static Veterinaire[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Veterinaire[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class VeterinaireFactory extends UserFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($passwordHasher);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return array_merge(parent::getDefaults(), [
            'roles' => ['ROLE_VETERINAIRE'],
        ]);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        /** @var VeterinaireFactory $self */
        $self = parent::initialize();

        return $self;
    }

    protected static function getClass(): string
    {
        return Veterinaire::class;
    }
}
