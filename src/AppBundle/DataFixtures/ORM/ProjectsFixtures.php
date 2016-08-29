<?php
/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 29/08/2016
 * Time: 22:44
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    private $types = [
        ['id' => '1', 'name' => 'After Effects', 'img' => 'after-effects.png', 'slug' => 'after-effects'],
        ['id' => '2', 'name' => 'Illustrator', 'img' => 'illustrator.png', 'slug' => 'illustrator'],
        ['id' => '3', 'name' => 'Photoshop', 'img' => 'photoshop.png', 'slug' => 'photoshop'],
        ['id' => '4', 'name' => 'InDesign', 'img' => 'indesign.png', 'slug' => 'indesign'],
        ['id' => '5', 'name' => 'Image', 'img' => 'image.png', 'slug' => 'image'],
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $datas = include(__DIR__."/projects.php");
        $detailsData = include(__DIR__."/details.php");
        $details = [];
        foreach ($detailsData as $item) {
            $details[$item['project']] = $item;
        }

        foreach ($datas as $data) {
            $project = new Project();
            $type = $data['type'] - 1;
            $type = $manager->getRepository("AppBundle:Type")->findOneBy(['name' => $this->types[$type]['name']]);
            $image = new Image();
            $image->setPath($this->getImage($data['image']));
            $project->setTitle($data['title'])->setDescription($data['description'])->setType($type)->addImage($image);
            if (isset($details[$data['id']])) {
                $detail = $details[$data['id']];
                $project->setFullDescription($detail['description']);
                if (isset($detail['youtube'])) {
                    $project->setVideo($detail['youtube']);
                }
                for ($i = 0; $i < 4; $i++) {
                    if (isset($detail["image$i"])) {
                        $image = new Image();
                        $image->setPath($this->getImage($detail['image1']));
                        $project->addImage($image);
                    }
                }
            }
            $manager->persist($project);
        }

        $manager->flush();
    }

    /**
     * @param $name
     * @return UploadedFile
     */
    private function getImage($name)
    {
        $path = sys_get_temp_dir()."/".uniqid();
        copy("C:\\wamp\\www\\projets\\AMZoominTV\\medias\\projects\\".$name, $path);

        return new UploadedFile($path, uniqid(), null, null, null, true);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}