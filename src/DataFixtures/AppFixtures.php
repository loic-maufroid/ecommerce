<?php

namespace App\DataFixtures;

use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $tastes = ['citron','orange','coca','fraise','framboise','crabe','mayo','mystére','caramel','choco','raisin','acid','synthétique','choco-moelleux'];
        $colors = [];
        for ($i=0; $i < 24 ; $i++) { 
            $colors[] = $faker->safeColorName;
        }        

        for ($i=1; $i <= 100; $i++) { 
            $product = new Product();
            $product->setName('Pâte de fruits goût '.$tastes[random_int(0,count($tastes)-1)]);
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setDescription($faker->sentence(6,true));
            $product->setPrice(random_int(99,4999));
            $product->setCreationDate(new DateTime());
            $product->setHeart(random_int(0,1) == 0 ? true : false);
            $product->setColors(random_int(0,1) == 0 ? [$colors[random_int(0,count($colors)-1)]] : [$colors[random_int(0,count($colors)-1)],$colors[random_int(0,count($colors)-1)]]);
            $product->setImage(null);
            $product->setDiscount(random_int(1,5) > 1 ? random_int(5,75) : null);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
