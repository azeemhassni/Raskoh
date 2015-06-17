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
        add_action('init', array($this, 'hookIntoWordPress'), rand());

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
     * Enable filters on admin interface
     * @return $this
     */
    public function enableFilters(){
        add_action('restrict_manage_posts', array($this, 'restrictPostsByTerm'));
        add_action('parse_query', array($this, 'convertTermsToQuery'));

        return $this;
    }

    /**
     *  Enable filters on Admin Interface
     */
    public function restrictPostsByTerm()
    {
        if (!$this->pt) {
            throw new \BadMethodCallException("Taxonomy is not associated with a post type");
        }

        global $typenow;
        $post_type = $this->pt->slug;
        $taxonomy  = $this->slug;
        if ($typenow == $post_type) {
            $selected      = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
            $info_taxonomy = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' => __("Show All {$info_taxonomy->label}"),
                'taxonomy'        => $taxonomy,
                'name'            => $taxonomy,
                'orderby'         => 'name',
                'selected'        => $selected,
                'show_count'      => true,
                'hide_empty'      => true,
            ));
        };
    }

    /**
     *  Convert Terms into Query
     * @param $query
     */
    public function convertTermsToQuery( $query )
    {
        if (!$this->pt) {
            throw new \BadMethodCallException("Taxonomy is not associated with a post type");
        }

        global $pagenow;
        $post_type = $this->pt->slug; 
        $taxonomy  = $this->slug; 
        $q_vars    = &$query->query_vars;
        if ($pagenow == 'edit.php' &&
            isset( $q_vars[ 'post_type' ] ) &&
            $q_vars[ 'post_type' ] == $post_type &&
            isset( $q_vars[ $taxonomy ] ) &&
            is_numeric($q_vars[ $taxonomy ]) &&
            $q_vars[ $taxonomy ] != 0
        ) {

            $term                = get_term_by('id', $q_vars[ $taxonomy ], $taxonomy);
            $q_vars[ $taxonomy ] = $term->slug;
        }
    }

} 