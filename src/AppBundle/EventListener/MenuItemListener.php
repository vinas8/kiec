<?php
namespace AppBundle\EventListener;


use AppBundle\Collection\Collection;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Avanzu\AdminThemeBundle\Model\MenuItemModel;
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
        $home = new MenuItemModel('menu-item-home', 'Pradžia', '/');
        $home->setIcon('fa fa-home');

        $lesson = new MenuItemModel('menu-item-lesson', 'Dabartinė pamoka', '/current');
        $lesson->setIcon('fa fa-clock-o');

        $schedule = new MenuItemModel('menu-item-schedule', 'Tvarkaraštis', '/schedule');
        $schedule->setIcon('fa fa-calendar');

        $classes = new MenuItemModel('menu-item-classes', 'Klasės', '/classes');
        $classes->setIcon('fa fa-list');

        $items = new Collection();
        $items->put($home);
        $items->put($lesson);
        $items->put($schedule);
        $items->put($classes);

        // TODO: add logout to the menu
        //
        // if User is logged in {
        //      $logout = new MenuItemModel('menu-item-logout', 'Atsijungti', '/logout');
        //      $logout->setIcon('fa fa-sign-out');
        //
        //      $item->put($logout);
        // }

        return $this->activateByRoute($request->get('_route'), $items);
    }

    /**
     * @param  mixed $route
     * @param  Collection $items
     * @return Collection
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
