<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     * @return Response|RedirectResponse
     */
    public function defaultAction()
    {
        if (!$this->getUser() || !$this->getUser()->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute("security_login");
        }
        return $this->render("@Admin/base.html.twig");
    }

    /**
     * @Route("/ajax/delete", name="admin_delete", options={"expose": "true"})
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function deleteObjectAction(Request $request)
    {

    }
}
