<?php
/**
 * Created by PhpStorm.
 * User: francois
 * Date: 30/08/16
 * Time: 14:27
 */

namespace AdminBundle\Controller;


use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProjectController
 * @package AdminBundle\Controller
 * @Route("/projects")
 */
class ProjectController extends Controller
{
    /**
     * @Route("/", name="admin_project_list")
     * @Security("has_role('ROLE_ADMIN')")
     * @return Response|RedirectResponse
     */
    public function listAction()
    {
        $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAllNotDeleted();

        return $this->render('AdminBundle:Project:list.html.twig', ['projects' => $projects]);
    }

    /**
     * @Route("/new", name="admin_project_new")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ProjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->manageImages($form->getData(), $form);
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", sprintf('Projet %s créé', $project->getTitle()));

            return $this->redirectToRoute("admin_project_list");
        }

        return $this->render('AdminBundle:Project:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="admin_project_edit")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param Project $project
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Project $project)
    {
        $form = $this->createForm(ProjectType::class, $project, ['img_req' => false]);
        $form->handleRequest($request);
        $params = ['form' => $form->createView(), 'project' => $project];

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $this->manageImages($form->getData(), $form);
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", sprintf('Projet %s modifié', $project->getTitle()));

            return $this->redirectToRoute("admin_project_list");
        }

        return $this->render('AdminBundle:Project:create.html.twig', $params);
    }

    /**
     * @param Project $project
     * @param Form $form
     * @return Project
     */
    private function manageImages(Project $project, Form $form)
    {
        for ($i = 0; $i < 4; $i++) {
            /** @var Image $image */
            $image = $form->get("image_$i")->getData();
            if ($image && $image->getPath()) {
                $images = $project->getImages();
                if (!$images instanceof ArrayCollection) {
                    $images = new ArrayCollection($images);
                }
                if (!empty($images->get($i))) {
                    $this->getDoctrine()->getManager()->remove($images->get($i));
                }
                $images->set($i, $image);
                $project->setImages($images);
            }
        }

        return $project;
    }

}