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
  
	public function get_id() {
		return 'ilc_cards';
	}

	public function get_title() {
		return __( 'ILC Cards', 'elementor-pro' );
	}

	// public function register_controls( Widget_Base $widget ) {
	// 	parent::register_controls($widget);
		
	// }
	public function register_skin_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		$this->register_post_count_control();
		$this->register_row_gap_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_meta_data_controls();
		$this->register_link_controls();
	}

}
