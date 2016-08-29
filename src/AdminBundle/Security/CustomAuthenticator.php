<?php
/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 30/08/2016
 * Time: 01:18
 */

namespace AdminBundle\Security;

use AdminBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class CustomAuthenticator extends AbstractGuardAuthenticator
{
    /** @var ContainerInterface $container */
    private $container;
    /** @var EntityManager $em */
    private $em;

    /**
     * CustomAuthenticator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get("doctrine")->getManager();
    }

    /**
     * step 1
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login_check') {
            return null;
        }

        $username = ucfirst(trim($request->request->get('_username')));
        $password = $request->request->get('_password');

        return ['username' => $username, 'password' => $password];
    }

    /**
     * step 2
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->em->getRepository('AdminBundle:User')->findByUsernameOrEmail($credentials['username']);
    }

    /**
     * step 3
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $encoder = $this->container->get('security.password_encoder');

        /** @var User $user */
        if (!$user instanceof User) {
            throw new CustomUserMessageAuthenticationException("Invalid credentials");
        }

        return $encoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($this->container->get('router')->generate('security_login'));
    }

    /**
     * Called when authentication is needed, but it's not sent
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['message' => 'Connexion requise'], 401);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }


}