<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use App\Repository\CategoryRepository;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;

class AppFixtures extends Fixture
{
	private $seedDirectory = '\data\seeds';

    public function load(ObjectManager $manager)
    {
    	$finder = new Finder();
        $finderSeed = $finder->in( __DIR__ . '\..\..' . $this->seedDirectory);

        $categoryRepository = $manager->getRepository(Category::class);

        foreach ($finderSeed as $file) {

		    $content = file_get_contents($file->getRealPath());
			$json = json_decode($content, true);

			foreach ($json as $seedName => $seedList) {
				foreach ($seedList as $crtSeed) {
					if($seedName == 'products') {
						$product = new Product();
						$product->setName($crtSeed['name']);

						$category = $categoryRepository->findOneBy(['name' => $crtSeed['category'] ]);
						if (!$category) {
					        $category = new Category();
					        $category->setName($crtSeed['category']);
					        $manager->persist($category);
					    }
						$product->setCategory($category);

						$product->setSku($crtSeed['sku']);
						$product->setPrice($crtSeed['price']);
						$product->setQuantity($crtSeed['quantity']);
						$manager->persist($product);
					} elseif ($seedName == 'user') {
						$user = new User();
						$user->setUsername($crtSeed['name']);
						$user->setEmail($crtSeed['email']);
						$manager->persist($user);
					} 
				}
			}

		}
        $manager->flush();
    }
}
