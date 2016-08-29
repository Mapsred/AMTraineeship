<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/form", name="formPage")
     * @param Request $request
     * @return Response
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(ProjectType::class);
        $form->handleRequest($request);
        $formView = $form->createView();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Project $project */
            $project = $form->getData();
            for ($i = 0; $i < 4; $i++) {
                /** @var Image $image */
                $image = $form->get("image_$i")->getData();
                if ($image && $image->getPath()) {
                    $project->addImage($image);
                }
            }

            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('formPage'));
        }

        return $this->render('AppBundle:Default:form.html.twig', ["form" => $formView]);
    }
}
