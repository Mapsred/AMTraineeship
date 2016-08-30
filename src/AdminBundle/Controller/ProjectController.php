<?php
/**
 * Created by PhpStorm.
 * User: francois
 * Date: 30/08/16
 * Time: 14:27
 */

namespace AdminBundle\Controller;


use AppBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return Response|RedirectResponse
     */
    public function listAction()
    {
        $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAllNotDeleted();

        return $this->render('AdminBundle:Project:list.html.twig', ['projects' => $projects]);
    }

    /**
     * @Route("/edit/{id}", name="admin_project_edit")
     * @param Request $request
     * @param Project $project
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Project $project)
    {

    }

    /**
     * @Route("/new", name="admin_project_new")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function newAction(Request $request)
    {

    }

}