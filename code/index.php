<?php
    declare(strict_types=1);

    interface Shelter {
        public function putDownAnimals(): void;
    }

    class AnimalShelter implements Shelter
    {
        private array $animals = [];

        public function putDownAnimals(): void
        {
            $this->animals = []; // all dead
        }

        public function getAnimals(): array
        {
            return $this->animals;
        }

        public function setAnimals(array $animals): void
        {
            $this->animals = $animals;
        }
    }

    interface Animal {
        public function getId(): ?int;
        public function setIsSick(bool $isSick): void;
        public function isSick(): bool;
    }

    class Cat implements Animal {
        private ?int $id;

        private bool $isSick = false;

        public function __construct(?int $id)
        {
            $this->id = $id;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function setIsSick(bool $isSick): void
        {
            $this->isSick = $isSick;
        }

        public function isSick(): bool
        {
            return $this->isSick;
        }
    }

    class Dog implements Animal {
        private ?int $id;

        private bool $isSick = false;

        public function __construct(?int $id)
        {
            $this->id = $id;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function isSick(): bool
        {
            return $this->isSick;
        }

        public function setIsSick(bool $isSick): void
        {
            $this->isSick = $isSick;
        }
    }

    class Rabbit implements Animal {
        private ?int $id;

        private bool $isSick = false;

        public function __construct(?int $id)
        {
            $this->id = $id;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function isSick(): bool
        {
            return $this->isSick;
        }

        public function setIsSick(bool $isSick): void
        {
            $this->isSick = $isSick;
        }
    }

    class PutDownDecorator
    {
        protected AnimalShelter $shelter;

        private array $animals = [];

        private array $animalsToPutDown = [];

        public function __construct(AnimalShelter $shelter)
        {
            $this->shelter = $shelter;

            $this->animals = $this->shelter->getAnimals();
        }

        public function putDownAnimals(): void
        {
            $animalsToKeep = [];

            foreach ($this->animals as $animal) {
                foreach ($this->animalsToPutDown as $animalToPutDown) {
                    if ($animal === $animalToPutDown) {
                        continue 2;
                    }
                }

                $animalsToKeep[] = $animal;
            }

            $this->shelter->setAnimals($animalsToKeep);
        }

        protected function getAnimals(): array
        {
            return $this->animals;
        }

        protected function setAnimalsToPutDown(array $animalsToPutDown): void
        {
            $this->animalsToPutDown = $animalsToPutDown;
        }
    }

    class PutDownCatsDecorator extends PutDownDecorator
    {
        private PutDownDecorator $putDownDecorator;

        private array $animals = [];

        private array $animalsToPutDown = [];

        public function __construct(PutDownDecorator $putDownDecorator)
        {
            $this->putDownDecorator = $putDownDecorator;
            $this->animals = $this->putDownDecorator->getAnimals();
        }

        public function putDownAnimals(): void
        {
            $animals = $this->getAnimals();

            foreach ($animals as $animal) {
                if (get_class($animal) === Cat::class) {
                    $this->animalsToPutDown[] = $animal;
                }
            }

            $this->putDownDecorator->setAnimalsToPutDown($this->animalsToPutDown);

            $this->putDownDecorator->putDownAnimals();
        }

        protected function getAnimals(): array
        {
            return $this->animals;
        }

        protected function setAnimalsToPutDown(array $animalsToPutDown): void
        {
            $this->animalsToPutDown = $animalsToPutDown;
        }
    }

    class PutDownDogsDecorator extends PutDownDecorator
    {
        private PutDownDecorator $putDownDecorator;

        private array $animals = [];

        private array $animalsToPutDown = [];

        public function __construct(PutDownDecorator $putDownDecorator)
        {
            $this->putDownDecorator = $putDownDecorator;
            $this->animals = $this->putDownDecorator->getAnimals();
        }

        public function putDownAnimals(): void
        {
            $animals = $this->animals;

            foreach ($animals as $animal) {
                if (get_class($animal) === Dog::class) {
                    $this->animalsToPutDown[] = $animal;
                }
            }

            $this->putDownDecorator->setAnimalsToPutDown($this->animalsToPutDown);

            $this->putDownDecorator->putDownAnimals();
        }

        protected function getAnimals(): array
        {
            return $this->animals;
        }

        protected function setAnimalsToPutDown(array $animalsToPutDown): void
        {
            $this->animalsToPutDown = $animalsToPutDown;
        }
    }

    class PutDownSickDecorator extends PutDownDecorator
    {
        private PutDownDecorator $putDownDecorator;

        public array $animals = [];

        public array $animalsToPutDown = [];

        public function __construct(PutDownDecorator $putDownDecorator)
        {
            $this->putDownDecorator = $putDownDecorator;
            $this->animals = $this->putDownDecorator->getAnimals();
        }

        public function putDownAnimals(): void
        {
            $animals = $this->animals;

            foreach ($animals as $animal) {
                if ($animal->IsSick() === true) {
                    $this->animalsToPutDown[] = $animal;
                }
            }

            $this->putDownDecorator->setAnimalsToPutDown($this->animalsToPutDown);

            $this->putDownDecorator->putDownAnimals();
        }

        protected function getAnimals(): array
        {
            return $this->animals;
        }

        protected function setAnimalsToPutDown(array $animalsToPutDown): void
        {
            $this->animalsToPutDown = $animalsToPutDown;
        }
    }

    $shelter = new AnimalShelter();

    $cat1 = new Cat(1);
    $cat1->setIsSick(true);
    $cat2 = new Cat(2);
    $cat3 = new Cat(3);

    $dog1 = new Dog(1);
    $dog2 = new Dog(2);
    $dog2->setIsSick(true);
    $dog3 = new Dog(3);

    $rabbit1 = new Rabbit(1);
    $rabbit2 = new Rabbit(2);
    $rabbit3 = new Rabbit(3);
    $rabbit3->setIsSick(true);

    $shelter->setAnimals([$cat1, $cat2, $cat3, $dog1, $dog2, $dog3, $rabbit1, $rabbit2, $rabbit3]);

    $putDownDecorator = new PutDownDecorator($shelter);

    $putDownCatsDecorator = new PutDownCatsDecorator($putDownDecorator); // kill all cats
    $putDownDogsDecorator = new PutDownDogsDecorator($putDownCatsDecorator); //kill all dogs
    $putDownSickDecorator = new PutDownSickDecorator($putDownDogsDecorator); //kill all sickos

    $putDownSickDecorator->putDownAnimals(); // kills all sickos, then what is left of dogs and then cats

    echo "<pre>" . print_r($shelter->getAnimals(), true) . "</pre>"; // in the end only 2 healthy rabbits gets to see another day
