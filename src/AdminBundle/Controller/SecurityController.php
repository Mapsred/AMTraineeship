<?php
/**
 * Created by PhpStorm.
 * User: francois
 * Date: 30/08/16
 * Time: 11:44
 */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     * @return Response|RedirectResponse
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin_homepage');
        }
        $exception = $this->get('security.authentication_utils')->getLastAuthenticationError();
        $twigArray['last_username'] = $this->get('security.authentication_utils')->getLastUsername();
        $twigArray['error'] = $exception ? $exception : null;

        return $this->render('AdminBundle:Security:login.html.twig', $twigArray);
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
        // will never be executed
    }
}