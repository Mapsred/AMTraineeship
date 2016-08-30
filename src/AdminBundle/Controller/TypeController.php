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
use AppBundle\Entity\Type;
use AppBundle\Form\TypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TypeController
 * @package AdminBundle\Controller
 * @Route("/types")
 */
class TypeController extends Controller
{
    /**
     * @Route("/", name="admin_type_list")
     * @return Response|RedirectResponse
     */
    public function listAction()
    {
        $twigArray['types'] = $this->getDoctrine()->getRepository('AppBundle:Type')->findAll();
        foreach ($twigArray['types'] as $key => $st) {
            $twigArray['count'][$key] = $this->getDoctrine()->getRepository("AppBundle:Project")->countByType($st);
        }

        return $this->render('AdminBundle:Type:list.html.twig', $twigArray);
    }

    /**
     * @Route("/new", name="admin_type_new")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(TypeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Type $type */
            $type = $form->getData();
            $this->getDoctrine()->getManager()->persist($type);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", sprintf('Type %s créé', $type->getName()));

            return $this->redirectToRoute("admin_type_list");
        }

        return $this->render('AdminBundle:Type:create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="admin_type_edit")
     * @param Request $request
     * @param Type $type
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Type $type)
    {
        $form = $this->createForm(TypeType::class, $type, ['img_req' => false]);
        $form->handleRequest($request);
        $params = ['form' => $form->createView(), 'type' => $type];
        if ($type->getFile() instanceof File) {
            $this->get('session')->set('file', $type->getFile()->getFilename());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Type $type */
            $type = $form->getData();
            if (empty($type->getFile())) {
                $type->setFile($this->get('session')->get('file'));
            }

            $this->getDoctrine()->getManager()->persist($type);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", sprintf('Type %s créé', $type->getName()));

            return $this->redirectToRoute("admin_type_list");
        }

        return $this->render('AdminBundle:Type:create.html.twig', $params);
    }
}