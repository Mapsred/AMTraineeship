<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return JsonResponse
     */
    public function removeObjectAction(Request $request)
    {
        $content = $this->get('admin.manager')->remove($request);

        return new JsonResponse($content[0], $content[1]);
    }

}
