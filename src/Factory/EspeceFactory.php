<?php

namespace App\Factory;

use App\Entity\Espece;
use App\Repository\EspeceRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Espece>
 *
 * @method        Espece|Proxy create(array|callable $attributes = [])
 * @method static Espece|Proxy createOne(array $attributes = [])
 * @method static Espece|Proxy find(object|array|mixed $criteria)
 * @method static Espece|Proxy findOrCreate(array $attributes)
 * @method static Espece|Proxy first(string $sortedField = 'id')
 * @method static Espece|Proxy last(string $sortedField = 'id')
 * @method static Espece|Proxy random(array $attributes = [])
 * @method static Espece|Proxy randomOrCreate(array $attributes = [])
 * @method static EspeceRepository|RepositoryProxy repository()
 * @method static Espece[]|Proxy[] all()
 * @method static Espece[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Espece[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Espece[]|Proxy[] findBy(array $attributes)
 * @method static Espece[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Espece[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class EspeceFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'name' => mb_convert_case(self::faker()->word(), MB_CASE_TITLE, "UTF-8")
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Espece $espece): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Espece::class;
    }
}
