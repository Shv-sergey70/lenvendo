<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixtures extends Fixture
{
    /** @var Generator */
    protected $faker;

    /** @var ObjectManager */
    protected $manager;

    /** @var array */
    private $referencesIndex = [];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        $this->loadData($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    abstract public function loadData(ObjectManager $manager);

    /**
     * @param string   $className
     * @param int      $count
     * @param callable $factory
     */
    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = $this->create($className, $factory);

            $this->addReference("{$className}|{$i}", $entity);
        }

        $this->manager->flush();
    }

    /**
     * @param string   $className
     * @param callable $factory
     *
     * @return mixed
     */
    protected function create(string $className, callable $factory)
    {
        $entity = new $className();

        $factory($entity);

        $this->manager->persist($entity);

        return $entity;
    }

    /**
     * @param string $className
     *
     * @return object
     * @throws \Exception
     */
    protected function getRandomReference(string $className)
    {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $reference) {
                if (strpos($key, $className . '|') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new \Exception('Не найдены ссылки на класс: ' . $className);
        }

        return $this->getReference($this->faker->randomElement($this->referencesIndex[$className]));
    }
}
