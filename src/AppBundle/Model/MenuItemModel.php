<?php
namespace AppBundle\Model;

use Avanzu\AdminThemeBundle\Model\MenuItemInterface;


class MenuItemModel implements MenuItemInterface
{
    protected $identifier;
    protected $label;
    protected $route;
    protected $routeArgs = [];
    protected $isActive = false;
    protected $icon;
    protected $badge;
    protected $badgeColor;
    protected $children = [];
    protected $parent = null;

    /**
     * MenuItemModel constructor.
     *
     * @param  string $route Route name
     * @param  string $label Route label
     * @param  string $icon Route icon
     */
    public function __construct($route, $label, $icon = '')
    {
        $this->setRoute($route);
        $this->setLabel($label);
        $this->setIdentifier($this->createId($route));
        $this->setIcon($icon);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param  string $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param  string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param  string $badge
     * @return $this
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param  array $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param  string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        if ($this->hasParent()) {
            $this->getParent()->setIsActive($isActive);
        }

        $this->isActive = $isActive;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParent()
    {
        return ($this->parent instanceof MenuItemInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(MenuItemInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getRouteArgs()
    {
        return $this->routeArgs;
    }

    public function setRouteArgs($routeArgs)
    {
        $this->routeArgs = $routeArgs;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        return (count($this->children) > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(MenuItemInterface $child)
    {
        $this->setParent($this);

        $this->children[] = $child;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(MenuItemInterface $child)
    {
        if (false !== ($key = array_search($child, $this->children))) {
            unset($this->children[$key]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBadgeColor()
    {
        return $this->badgeColor;
    }

    /**
     * @param  string $badgeColor
     * @return $this
     */
    public function setBadgeColor($badgeColor)
    {
        $this->badgeColor = $badgeColor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getActiveChild()
    {
        foreach ($this->children as $child) {
            if ($child->isActive()) {
                return $child;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->getIsActive();
    }

    /**
     * @param  string $id
     * @return string
     */
    private function createId($id)
    {
        return 'menu-' . preg_replace('/[^a-z]/', '', mb_strtolower($id));
    }

}
