<?php
namespace ILC\Skins;

use ElementorPro\Modules\Posts\Skins\Skin_Cards;

// use Elementor\Controls_Manager;
// use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
// use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
// use Elementor\Group_Control_Image_Size;
// use Elementor\Group_Control_Typography;
// use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Skin_ILC_Cards extends Skin_Cards {
  
	protected function _register_controls_actions() {
		add_action( 'elementor/element/archive-posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/archive-posts/section_layout/after_section_end', [ $this, 'register_style_sections' ] );
		add_action( 'elementor/element/archive-posts/archive_cards_section_design_image/before_section_end', [ $this, 'register_additional_design_image_controls' ] );
	}

	public function get_id() {
		return 'ilc_cards';
	}

	public function get_title() {
		return __( 'ILC Cards', 'elementor-pro' );
	}

	public function get_container_class() {
		// Use parent class and parent css.
		return 'elementor-posts--skin-cards';
	}

	/* Remove `posts_per_page` control */
	protected function register_post_count_control(){}
}
