<?php
namespace ILC\Widgets;
// use Elementor\Widget_Base;
// use Elementor\Post;
// use Elementor\Controls_Manager;



// use ElementorPro\Modules\QueryControl\Module as Module_Query;
// use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
// use Elementor\Widget_Base;
// use Elementor\Controls_Manager;
use ElementorPro\Modules\Posts\Skins;
use ElementorPro\Modules\Posts\Widgets\Posts;
// require_once(__DIR__ .'../skins/skin-ilc-cards.php');
use ILC\Skins\Skin_ILC_Cards;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Posts
 */
class ILC_Posts extends Posts {

	public function get_name() {
		return 'ilc_posts';
	}

	public function get_title() {
		return __( 'ILC Posts', 'elementor-pro' );
	}

	// public function get_keywords() {
	// 	return [ 'posts', 'cpt', 'item', 'loop', 'query', 'cards', 'custom post type' ];
	// }

	// public function on_import( $element ) {
	// 	if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
	// 		$element['settings']['posts_post_type'] = 'post';
	// 	}

	// 	return $element; 
	// }

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
		$this->add_skin( new Skin_ILC_Cards( $this ) );
		$this->add_skin( new Skins\Skin_Full_Content( $this ) );
	}

	protected function _register_controls() {
		parent::_register_controls();

		$this->register_query_section_controls();
		$this->register_pagination_section_controls();

	}

	// public function query_posts() {

	// 	$query_args = [
	// 		'posts_per_page' => $this->get_current_skin()->get_instance_value( 'posts_per_page' ),
	// 		'paged' => $this->get_current_page(),
	// 	];

	// 	/** @var Module_Query $elementor_query */
	// 	$elementor_query = Module_Query::instance();
	// 	$this->query = $elementor_query->get_query( $this, 'posts', $query_args, [] );
	// }

	// protected function register_query_section_controls() {
	// 	$this->start_controls_section(
	// 		'section_query',
	// 		[
	// 			'label' => __( 'Query', 'elementor-pro' ),
	// 			'tab' => Controls_Manager::TAB_CONTENT,
	// 		]
	// 	);

	// 	$this->add_group_control(
	// 		Group_Control_Related::get_type(),
	// 		[
	// 			'name' => $this->get_name(),
	// 			'presets' => [ 'full' ],
	// 			'exclude' => [
	// 				'posts_per_page', //use the one from Layout section
	// 			],
	// 		]
	// 	);

	// 	$this->end_controls_section();
	// }

}
