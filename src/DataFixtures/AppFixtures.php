<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;
    private $passwordEncoder;

    public function __construct(SluggerInterface $slugger,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $userPasswordEncoder;
    }

    public static function couleurs(){
        $r = random_int(1,7);
        switch ($r) {
            case 1:
                return ['bleu'];
            case 2:
                return ['rouge'];
            case 3:
                return ['vert'];
            case 4:
                return ['bleu','rouge'];
            case 5:
                return ['bleu','vert'];
            case 6:
                return ['rouge','vert'];
            default:
                return ['bleu','rouge','vert'];
        }
    }


    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $tastes = ['citron','orange','coca','fraise','framboise','crabe','mayo','mystére','caramel','choco','raisin','acid','synthétique','choco-moelleux'];

        for ($i=1; $i <= 100; $i++) { 
            $product = new Product();
            $product->setName('Pâte de fruits goût '.$tastes[random_int(0,count($tastes)-1)]." ".$i);
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $product->setDescription($faker->sentence(6,true));
            $product->setPrice(random_int(99,4999));
            $product->setCreationDate(new DateTime());
            $product->setHeart(random_int(0,1) == 0 ? true : false);
            $product->setColors(self::couleurs());
            $product->setImage(null);
            $product->setDiscount(random_int(1,5) > 1 ? random_int(5,75) : null);
            $manager->persist($product);
        }

        $admin = new User();
        $admin->setEmail("loic.maufroid@admin.com");
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin,'admin'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail("fear.hate@user.fr");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user,'user'));
        $manager->persist($user);

        $manager->flush();
    }
}
