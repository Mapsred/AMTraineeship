<?php
/**
 * Created by PhpStorm.
 * User: Maps_red
 * Date: 30/08/2016
 * Time: 01:41
 */

namespace AdminBundle\EventListener;

use Avanzu\AdminThemeBundle\Model\MenuItemModel;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;

class ListListener
{
    /**
     * @param SidebarMenuEvent $event
     */
    public function onSetupMenu(SidebarMenuEvent $event)
    {
        foreach ($this->getMenu($event->getRequest()) as $item) {
            $event->addItem($item);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getMenu(Request $request)
    {
        $menuItems = [
            new MenuItemModel('dashboard', 'Dashboard', 'admin_homepage', [], 'iconclasses fa fa-tachometer'),
            new MenuItemModel('projects', 'Projets', 'admin_project_list', [], 'iconclasses fa fa-cube'),
        ];

        return $this->activateByRoute($request->attributes->get('_route'), $menuItems);
    }

    /**
     * @param string $route
     * @param array $items
     * @return array
     */
    protected function activateByRoute($route, $items)
    {
        /** @var MenuItemModel $item */
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() == $route) {
                $item->setIsActive(true);
            }
        }

        return $items;
    }
}