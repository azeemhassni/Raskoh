<?php namespace Raskoh;


/**
 * Class Taxonomy
 *
 * @package Raskoh
 */
class Taxonomy
{

    /**
     * @var
     */
    private $name;
    /**
     * @var bool
     */
    public $public = true;
    /**
     * @var bool
     */
    public $hierarchical = true;

    /**
     * @var bool
     */
    public $show_ui = true;
    /**
     * @var bool
     */
    public $show_admin_column = true;
    /**
     * @var bool
     */
    public $show_in_nav_menus = true;
    /**
     * @var bool
     */
    public $show_tagcloud = true;

    /**
     * @var array|null
     */
    private $args;

    /**
     * @var PostType $pt
     */
    private $pt;

    /**
     * @var $slug
     */
    public $slug;

    /**
     * @var
     */
    private $priority;


    /**
     * @param null $name
     */
    public function __construct( $name = null )
    {

        if ($name) {
            $this->setName($name);
        }

    }

    public function register( PostType $postType )
    {
        $this->pt = $postType;
        $this->toSlug();
        $args = $this->args();

        $args[ 'labels' ] = $this->labels();
        $this->args       = $args;;
        add_action('init', array($this, 'hookIntoWordPress'), $this->getPriority());

        return $this;
    }

    public function hookIntoWordPress()
    {
        register_taxonomy($this->slug, array($this->pt->slug), $this->args);

    }

    /**
     * @return array
     */
    public function labels()
    {
        return array(
            'name'                       => _x($this->toPlural(), 'Taxonomy General Name', 'text_domain'),
            'singular_name'              => _x($this->getName(), 'Taxonomy Singular Name', 'text_domain'),
            'menu_name'                  => __($this->toPlural(), 'text_domain'),
            'all_items'                  => __('All ' . $this->toPlural(), 'text_domain'),
            'parent_item'                => __('Parent ' . $this->getName(), 'text_domain'),
            'parent_item_colon'          => __('Parent ' . $this->getName() . ':', 'text_domain'),
            'new_item_name'              => __('New ' . $this->getName() . ' Name', 'text_domain'),
            'add_new_item'               => __('Add New ' . $this->getName(), 'text_domain'),
            'edit_item'                  => __('Edit ' . $this->getName(), 'text_domain'),
            'update_item'                => __('Update ' . $this->getName(), 'text_domain'),
            'view_item'                  => __('View ' . $this->getName(), 'text_domain'),
            'separate_items_with_commas' => __('Separate ' . $this->toPlural() . ' with commas', 'text_domain'),
            'add_or_remove_items'        => __('Add or remove ' . $this->getName(), 'text_domain'),
            'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
            'popular_items'              => __('Popular ' . $this->toPlural(), 'text_domain'),
            'search_items'               => __('Search ' . $this->toPlural(), 'text_domain'),
            'not_found'                  => __('Not Found', 'text_domain'),
        );
    }

    /**
     * @return array
     */
    public function args()
    {
        return array(
            'labels'            => $this->labels(),
            'hierarchical'      => $this->hierarchical,
            'public'            => $this->public,
            'show_ui'           => $this->show_ui,
            'show_admin_column' => $this->show_admin_column,
            'show_in_nav_menus' => $this->show_in_nav_menus,
            'show_tagcloud'     => $this->show_tagcloud,
        );
    }

    /**
     * Convert Taxonomy name to slug
     */
    public function toSlug()
    {
        $this->slug = str_replace(' ', '-', strtolower($this->name));
    }


    /**
     * @param $name
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = ucwords($name);

        return $this;
    }

    /**
     * @return mixed
     */
    public function toPlural()
    {
        return Inflect::pluralize($this->name);
    }

    /**
     * @param $hierarchical
     * @return $this
     */
    public function setHierarchical( $hierarchical )
    {
        $this->hierarchical = $hierarchical;

        return $this;
    }

    /**
     * @param $public
     * @return $this
     */
    public function setPublic( $public )
    {
        $this->public = $public;

        return $this;
    }

    /**
     * @param $show_admin_column
     * @return $this
     */
    public function setShowAdminColumn( $show_admin_column )
    {
        $this->show_admin_column = $show_admin_column;

        return $this;
    }

    /**
     * @param $show_in_nav_menus
     * @return $this
     */
    public function setShowInNavMenus( $show_in_nav_menus )
    {
        $this->show_in_nav_menus = $show_in_nav_menus;

        return $this;
    }

    /**
     * @param $show_tagcloud
     * @return $this
     */
    public function setShowTagcloud( $show_tagcloud )
    {
        $this->show_tagcloud = $show_tagcloud;

        return $this;
    }

    /**
     * @param $show_ui
     * @return $this
     */
    public function setShowUi( $show_ui )
    {
        $this->show_ui = $show_ui;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return ucwords($this->name);
    }

    /**
     * @return int
     */
    private function getPriority()
    {
        return $this->priority ?: 10;
    }

    /**
     * @param $priority
     * @return $this
     */
    public function setPriority( $priority )
    {
        $this->priority = $priority;
        return $this;
    }


} 