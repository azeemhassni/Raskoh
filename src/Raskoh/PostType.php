<?php namespace Raskoh;

/**
 * Class PostType
 *
 * @package Raskoh
 * @author Azi Baloch <http://www.azibaloch.com>
 */

class PostType {


    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $icon;
    /**
     * @var string
     */
    public $text_domain = 'theme_name';
    /**
     * @var
     */
    public $slug;
    /**
     * @var bool
     */
    public $hierarchical = true;
    /**
     * @var string
     */
    public $description = "Description";
    /**
     * @var array
     */
    public $taxonomy = array();
    /**
     * @var bool
     */
    public $isPublic = true;
    /**
     * @var bool
     */
    public $show_ui = true;
    /**
     * @var bool
     */
    public $show_in_menu = true;
    /**
     * @var int
     */
    public $menu_position = 5;
    /**
     * @var bool
     */
    public $show_in_admin_bar = true;
    /**
     * @var bool
     */
    public $show_in_nav_menus = true;
    /**
     * @var bool
     */
    public $can_export = true;
    /**
     * @var bool
     */
    public $has_archive = true;
    /**
     * @var bool
     */
    public $exclude_from_search = false;
    /**
     * @var bool
     */
    public $queryable = true;
    /**
     * @var string
     */
    public $capability_type = 'page';


    /**
     * @var array
     */
    public $supports = array();

    /**
     * @var array
     */
    public $args = array();

    /**
     * @var Taxonomy
     */
    private $newTaxonomy;

    /**
     * @return PostType
     */
    public static function getInstance(){
        // don't need singleton due to wordpress hooks :( sadly i need to learn wp more.
        return new PostType();
    }


    /**
     *
     */
    public function register(){
        // Hook into the 'init' action
        add_action( 'init', array($this,'hookInWordPress') );
    }

    /**
     * callback of hook in init action
     */
    public function hookInWordPress(){
        $this->toSlug();
        if($this->newTaxonomy){
            $tax = $this->newTaxonomy->register($this);

            $this->addTaxonomy($tax->slug);
        }
        $args = $this->buildArgs();
        $args['labels'] = $this->buildLabels();
        if($this->icon) {
            $args['menu_icon'] = $this->icon;
        }

        register_post_type( $this->slug, $args);
        flush_rewrite_rules();
    }

    /**
     * @return array
     */
    public function buildArgs(){

       return array(
            'label'               => __( $this->slug, $this->text_domain ),
            'description'         => __( $this->description, $this->text_domain ),
            'supports'            => $this->getSupports(),
            'taxonomies'          => $this->taxonomy,
            'hierarchical'        => $this->hierarchical,
            'public'              => $this->isPublic,
            'show_ui'             => $this->show_ui,
            'show_in_menu'        => $this->show_in_menu,
            'menu_position'       => $this->menu_position,
            'show_in_admin_bar'   => $this->show_in_admin_bar,
            'show_in_nav_menus'   => $this->show_in_nav_menus,
            'can_export'          => $this->can_export,
            'has_archive'         => $this->has_archive,
            'exclude_from_search' => $this->exclude_from_search,
            'publicly_queryable'  => $this->queryable,
            'capability_type'     => $this->capability_type,
        );

    }


    /**
     * @return array
     */
    public function buildLabels(){
        return array(
                'name'                => _x( $this->toPlural(), $this->name.' General Name', $this->text_domain ),
                'singular_name'       => _x( $this->name, 'Post Type Singular Name', $this->text_domain ),
                'menu_name'           => __( $this->toPlural(), $this->text_domain ),
                'name_admin_bar'      => __( $this->name, $this->text_domain ),
                'parent_item_colon'   => __( 'Parent '.$this->name.':', $this->text_domain ),
                'all_items'           => __( 'All '.$this->toPlural(), $this->text_domain ),
                'add_new_item'        => __( 'Add New '.$this->name, $this->text_domain ),
                'add_new'             => __( 'Add New', $this->text_domain ),
                'new_item'            => __( 'New '.$this->name, $this->text_domain ),
                'edit_item'           => __( 'Edit '.$this->name, $this->text_domain ),
                'update_item'         => __( 'Update '.$this->name, $this->text_domain ),
                'view_item'           => __( 'View '.$this->name, $this->text_domain ),
                'search_items'        => __( 'Search '.$this->name, $this->text_domain ),
                'not_found'           => __( $this->name.' Not found', $this->text_domain ),
                'not_found_in_trash'  => __( $this->name.' Not found in Trash', $this->text_domain ),
            );
    }


    /**
     * @return array
     */
    public function getSupports(){
        $defaults = array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats' );
        return array_merge($defaults, $this->supports);
    }


    /**
     * @param $supports
     */
    public function supports($supports){
        $this->supports = $supports;
    }

    /**
     * Convert post type name to slug
     */
    public function toSlug(){
        $this->slug = str_replace(' ', '-' ,strtolower($this->name));
    }

    /**
     * @return mixed
     */
    public function toPlural(){
        return Inflect::pluralize($this->name);
    }

    /**
     * @return boolean
     */
    public function isCanExport() {
        return $this->can_export;
    }

    /**
     * @param $can_export
     * @return $this
     */
    public function setCanExport( $can_export ) {
        $this->can_export = $can_export;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCapabilityType() {
        return $this->capability_type;
    }

    /**
     * @param $capability_type
     * @return $this
     */
    public function setCapabilityType( $capability_type ) {
        $this->capability_type = $capability_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription( $description ) {
        $this->description = $description;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isExcludeFromSearch() {
        return $this->exclude_from_search;
    }

    /**
     * @param $exclude_from_search
     * @return $this
     */
    public function setExcludeFromSearch( $exclude_from_search ) {
        $this->exclude_from_search = $exclude_from_search;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHasArchive() {
        return $this->has_archive;
    }

    /**
     * @param $has_archive
     * @return $this
     */
    public function setHasArchive( $has_archive ) {
        $this->has_archive = $has_archive;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isHierarchical() {
        return $this->hierarchical;
    }


    /**
     * @param $hierarchical
     * @return $this
     */
    public function setHierarchical( $hierarchical ) {
        $this->hierarchical = $hierarchical;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param $icon
     * @return $this
     */
    public function setIcon( $icon ) {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPublic() {
        return $this->isPublic;
    }


    /**
     * @param $isPublic
     * @return $this
     */
    public function setIsPublic( $isPublic ) {
        $this->isPublic = $isPublic;
        return $this;
    }

    /**
     * @return int
     */
    public function getMenuPosition() {
        return $this->menu_position;
    }


    /**
     * @param $menu_position
     * @return $this
     */
    public function setMenuPosition( $menu_position ) {
        $this->menu_position = $menu_position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }


    /**
     * @param $name
     * @return $this
     */
    public function setName( $name ) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isQueryable() {
        return $this->queryable;
    }

    /**
     * @param boolean $queryable
     */
    public function setQueryable( $queryable ) {
        $this->queryable = $queryable;
    }

    /**
     * @return boolean
     */
    public function isShowInAdminBar() {
        return $this->show_in_admin_bar;
    }

    /**
     * @param boolean $show_in_admin_bar
     */
    public function setShowInAdminBar( $show_in_admin_bar ) {
        $this->show_in_admin_bar = $show_in_admin_bar;
    }

    /**
     * @return boolean
     */
    public function isShowInMenu() {
        return $this->show_in_menu;
    }

    /**
     * @param boolean $show_in_menu
     */
    public function setShowInMenu( $show_in_menu ) {
        $this->show_in_menu = $show_in_menu;
    }

    /**
     * @return boolean
     */
    public function isShowInNavMenus() {
        return $this->show_in_nav_menus;
    }

    /**
     * @param boolean $show_in_nav_menus
     */
    public function setShowInNavMenus( $show_in_nav_menus ) {
        $this->show_in_nav_menus = $show_in_nav_menus;
    }

    /**
     * @return boolean
     */
    public function isShowUi() {
        return $this->show_ui;
    }

    /**
     * @param boolean $show_ui
     */
    public function setShowUi( $show_ui ) {
        $this->show_ui = $show_ui;
    }

    /**
     * @return mixed
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug( $slug ) {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getTaxonomy() {
        return $this->taxonomy;
    }

    /**
     * @param mixed $taxonomy
     */
    public function addTaxonomy( $taxonomy ) {
        $this->taxonomy[] = $taxonomy;
    }

    /**
     * @return mixed
     */
    public function getTextDomain() {
        return $this->text_domain;
    }

    /**
     * @param mixed $text_domain
     */
    public function setTextDomain( $text_domain ) {
        $this->text_domain = $text_domain;
    }

    public function taxonomy($name = '') {
        $this->newTaxonomy = (new Taxonomy())->setName($name);
        return $this;
    }

}
