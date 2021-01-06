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
		// parent::register_controls($widget);
		$this->parent = $widget;
		parent::register_post_count_control();
		parent::register_row_gap_control();
		parent::register_thumbnail_controls();
		parent::register_title_controls();
		parent::register_meta_data_controls();
		parent::register_link_controls();
	}

}
