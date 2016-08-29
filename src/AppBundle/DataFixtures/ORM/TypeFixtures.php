<?php
/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 29/08/2016
 * Time: 22:01
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Type;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TypeFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    private $datas = [
        ['id' => '1', 'name' => 'After Effects', 'img' => 'after-effects.png', 'slug' => 'after-effects'],
        ['id' => '2', 'name' => 'Illustrator', 'img' => 'illustrator.png', 'slug' => 'illustrator'],
        ['id' => '3', 'name' => 'Photoshop', 'img' => 'photoshop.png', 'slug' => 'photoshop'],
        ['id' => '4', 'name' => 'InDesign', 'img' => 'indesign.png', 'slug' => 'indesign'],
        ['id' => '5', 'name' => 'Image', 'img' => 'image.png', 'slug' => 'image'],
    ];


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->datas as $data) {
            $type = new Type();
            $path = sys_get_temp_dir()."/".uniqid();
            copy("C:\\wamp\\www\\projets\\AMZoominTV\\img\\icons\\".$data['img'], $path);
            $image = new UploadedFile($path, uniqid(), null, null, null, true);
            $type->setName($data['name'])->setFile($image);
            $manager->persist($type);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}