<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Type;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function formAction(Request $request)
    {
        $projects = $this->getDoctrine()->getRepository("AppBundle:Project")->findAll();
        $pagerfanta = $this->paginateProjects($projects, $request);
        if ($pagerfanta instanceof RedirectResponse) {
            return $pagerfanta;
        }
        $projects = $pagerfanta->getCurrentPageResults();

        return $this->render("AppBundle:Default:index.html.twig", ["projects" => $projects, "pager" => $pagerfanta]);
    }

    /**
     * @param $projects
     * @param Request $request
     * @return Pagerfanta|RedirectResponse
     */
    private function paginateProjects($projects, Request $request)
    {
        $pagerfanta = new Pagerfanta(new ArrayAdapter($projects));
        $currentPage = $request->query->get("page", 1);
        $pagerfanta->setMaxPerPage(12);
        if ($currentPage > $pagerfanta->getNbPages()) {
            return $this->redirectToRoute("homepage");
        }
        $pagerfanta->setCurrentPage($currentPage);

        return $pagerfanta;
    }

    /**
     * @Route("/search", name="search_page")
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function searchAction(Request $request)
    {
        if (!$request->query->has("search")) {
            return $this->redirectToRoute("homepage");
        }
        $projects = $this->getDoctrine()->getRepository("AppBundle:Project")
            ->findByString($request->query->get("search"));

        $pagerfanta = $this->paginateProjects($projects, $request);
        if ($pagerfanta instanceof RedirectResponse) {
            return $pagerfanta;
        }
        $projects = $pagerfanta->getCurrentPageResults();

        return $this->render("AppBundle:Default:index.html.twig", ["projects" => $projects, "pager" => $pagerfanta]);
    }

    /**
     * @Route("/type/{slug}", name="type_page")
     * @ParamConverter("type", class="AppBundle:Type", options={"mapping"={"slug": "slug"}})
     * @param Request $request
     * @param Type $type
     * @return Response
     */
    public function typeAction(Request $request, Type $type)
    {
        $projects = $this->getDoctrine()->getRepository("AppBundle:Project")->findBy(['type' => $type]);
        $pagerfanta = $this->paginateProjects($projects, $request);
        if ($pagerfanta instanceof RedirectResponse) {
            return $pagerfanta;
        }
        $projects = $pagerfanta->getCurrentPageResults();

        return $this->render("AppBundle:Default:index.html.twig", ["projects" => $projects, "pager" => $pagerfanta]);
    }

    /**
     * @Route("/detail/{id}", name="detail")
     * @param Project $project
     * @return Response
     */
    public function detailAction(Project $project)
    {
        $similars = $this->getDoctrine()->getRepository("AppBundle:Project")->findBySimilarType($project);

        return $this->render("AppBundle:Default:detail.html.twig", ["project" => $project, "similars" => $similars]);
    }

    /**
     * @return Response
     */
    public function headerAction()
    {
        $types = $this->getDoctrine()->getRepository("AppBundle:Type")->findAll();

        return $this->render("AppBundle:Parts:header.html.twig", ["types" => $types]);
    }

    public function footerAction()
    {
        return $this->render("AppBundle:Parts:footer.html.twig");
    }
}
