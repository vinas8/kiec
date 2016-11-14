<?php
namespace AppBundle\EventListener;


use AppBundle\Model\MenuItemModel;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;

class MenuItemListener
{
    public function onSetupMenu(SidebarMenuEvent $event)
    {
        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }
    }

    protected function getMenu(Request $request)
    {
        $items = [
            $home = new MenuItemModel('homepage', 'Pradžia', 'fa fa-home'),
            $lesson = new MenuItemModel('current', 'Dabartinė pamoka', 'fa fa-clock-o')
        ];

        return $this->activateByRoute($request->get('_route'), $items);
    }

    /**
     * @param  mixed $route
     * @param  array $items
     * @return array
     */
    protected function activateByRoute($route, $items)
    {
        foreach ($items as $item) {

            if ($item->hasChildren())
                $this->activateByRoute($route, $item->getChildren());
            else {
                if ($item->getRoute() == $route)
                    $item->setIsActive(true);
            }
        }

        return $items;
    }
}
