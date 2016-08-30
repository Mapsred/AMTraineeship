<?php
/**
 * Created by PhpStorm.
 * User: francois
 * Date: 26/08/16
 * Time: 15:12
 */

namespace AdminBundle\Utils;


use CommentBundle\Event\CommentEvent;
use CommentBundle\Event\CommentEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class ManagerService
{
    /** @var EntityManager $em */
    private $em;
    /** @var AuthorizationChecker $checker */
    private $checker;

    /**
     * DeleteService constructor.
     * @param EntityManager $entityManager
     * @param AuthorizationChecker $checker
     */
    public function __construct(EntityManager $entityManager, AuthorizationChecker $checker)
    {
        $this->em = $entityManager;
        $this->checker = $checker;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function remove(Request $request)
    {
        if (!$this->checker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return [['message' => 'You must be authentificated to remove a collection.'], 401];
        }

        if (!$request->request->has('id')) {
            return [['message' => 'Parameter id missing.'], 401];
        }

        if (!$request->request->has('type')) {
            return [['message' => 'Parameter type missing.'], 401];
        }

        $id = $request->request->get('id');
        switch ($request->request->get('type')) {
            case "project":
                $project = $this->em->getRepository("AppBundle:Project")->findOneBy(['id' => $id]);
                if (!$project) {
                    return [['message' => 'Project not found'], 404];
                }

                $project->delete();
                $message = sprintf('Projet %s supprimé', $project->getTitle());
                $this->addFlash("success", $message);
                break;

            default:
                return [['message' => 'Parameter type invalid.'], 401];
                break;
        }
        $this->em->flush();

        return [['message' => $message], 200];
    }

    /**
     * @param $type
     * @param $message
     */
    private function addFlash($type, $message)
    {
        $session = new Session();
        $session->getFlashBag()->add($type, $message);
    }

}