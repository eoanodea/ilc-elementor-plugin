<?php
// namespace Jaxer\Widgets\Posts\Skins;
namespace ILC\Skins;


use ElementorPro\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;

use \WpfpInterface\Wrapper;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class Skinss_Base extends Elementor_Skin_Base
{

    /**
     * @var string Save current permalink to avoid conflict with plugins the filters the permalink during the post render.
     */

    private $current_permalink;

    protected function _register_controls_actions()
    {
        add_action('elementor/element/ilc_posts/section_layout/before_section_end', [$this, 'register_controls']);
        add_action('elementor/element/ilc_posts/section_query/after_section_end', [$this, 'register_style_sections']);
    }

    public function register_style_sections(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->register_design_controls();
    }

    public function register_controls(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->register_columns_controls();
        $this->register_post_count_control();
        $this->register_thumbnail_controls();
        $this->register_title_controls();
        $this->register_excerpt_controls();
        $this->register_meta_data_controls();
        $this->register_read_more_controls();
    }

    public function register_design_controls()
    {
        $this->register_design_layout_controls();
        $this->register_design_image_controls();
        $this->register_design_content_controls();
    }

    protected function register_thumbnail_controls()
    {
        $this->add_control(
            'thumbnail',
            [
                'label' => __('Image Position', 'ilc-elements'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __('Top', 'ilc-elements'),
                    'left' => __('Left', 'ilc-elements'),
                    'right' => __('Right', 'ilc-elements'),
                    'none' => __('None', 'ilc-elements'),
                ],
                'prefix_class' => 'elementor-widget-posts elementor-posts--thumbnail-',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail_size',
                'default' => 'medium',
                'exclude' => ['custom'],
                'condition' => [
                    $this->get_control_id('thumbnail!') => 'none',
                ],
                'prefix_class' => 'elementor-posts--thumbnail-size-',
            ]
        );

        $this->add_responsive_control(
            'pss_item_ratio',
            [
                'label' => __('Image Ratio', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.66,
                ],
                'tablet_default' => [
                    'size' => '',
                ],
                'mobile_default' => [
                    'size' => 0.5,
                ],
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-posts-container .elementor-post__thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% );',
                    '{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
                ],
                'condition' => [
                    $this->get_control_id('thumbnail!') => 'none',
                ],
                // 'render_type' => 'template'
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Image Width', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 10,
                        'max' => 600,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'size' => '',
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__thumbnail__link' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('thumbnail!') => 'none',
                ],
            ]
        );
    }

    protected function register_columns_controls()
    {
        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'ilc-elements'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'prefix_class' => 'elementor-grid%s-',
                'frontend_available' => true,
            ]
        );
    }

    protected function register_post_count_control()
    {
        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'ilc-elements'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );
    }

    protected function register_title_controls()
    {
        $this->add_control(
            'show_title',
            [
                'label' => __('Title', 'ilc-elements'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ilc-elements'),
                'label_off' => __('Hide', 'ilc-elements'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'ilc-elements'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
                'condition' => [
                    $this->get_control_id('show_title') => 'yes',
                ],
            ]
        );
    }

    protected function register_excerpt_controls()
    {
        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Excerpt', 'ilc-elements'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ilc-elements'),
                'label_off' => __('Hide', 'ilc-elements'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'ilc-elements'),
                'type' => Controls_Manager::NUMBER,
                /** This filter is documented in wp-includes/formatting.php */
                'default' => 25,
                'condition' => [
                    $this->get_control_id('show_excerpt') => 'yes',
                ],
            ]
        );
    }

    protected function register_read_more_controls()
    {
        $this->add_control(
            'show_read_more',
            [
                'label' => __('Read More', 'ilc-elements'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ilc-elements'),
                'label_off' => __('Hide', 'ilc-elements'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __('Read More Text', 'ilc-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Read More »', 'ilc-elements'),
                'condition' => [
                    $this->get_control_id('show_read_more') => 'yes',
                ],
            ]
        );
    }

    protected function register_meta_data_controls()
    {
        $this->add_control(
            'meta_data',
            [
                'label' => __('Meta Data', 'ilc-elements'),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'default' => ['date', 'comments'],
                'multiple' => true,
                'options' => [
                    'author' => __('Author', 'ilc-elements'),
                    'date' => __('Date', 'ilc-elements'),
                    'time' => __('Time', 'ilc-elements'),
                    'comments' => __('Comments', 'ilc-elements'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'meta_separator',
            [
                'label' => __('Separator Between', 'ilc-elements'),
                'type' => Controls_Manager::TEXT,
                'default' => '///',
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
                ],
                'condition' => [
                    $this->get_control_id('meta_data!') => [],
                ],
            ]
        );
    }

    /**
     * Style Tab
     */
    protected function register_design_layout_controls()
    {
        $this->start_controls_section(
            'section_design_layout',
            [
                'label' => __('Layout', 'ilc-elements'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
            'column_gap',
            [
                'label' => __('Columns Gap', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-posts-container' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                    '.elementor-msie {{WRAPPER}} .elementor-post' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
                    '.elementor-msie {{WRAPPER}} .elementor-posts-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
                ],
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __('Rows Gap', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 35,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-posts-container' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                    '.elementor-msie {{WRAPPER}} .elementor-post' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __('Alignment', 'ilc-elements'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'ilc-elements'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'ilc-elements'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'ilc-elements'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'prefix_class' => 'elementor-posts--align-',
            ]
        );

        $this->add_control(
			'c_animation',
			[
				'label' => __( 'Animation', 'ilc-elements' ),
                'type' => Controls_Manager::ANIMATION,
                'render_type' => 'template'
			]
        );
        
        {
            $this->add_control(
                'animation_duration',
                [
                    'label' => __('Animation Duration', 'ilc-elements'),
                    'type' => Controls_Manager::NUMBER,
                    'default' => 1500,
                    'render_type' => 'template',
                    'condition'=> [
                        $this->get_control_id('c_animation!') =>''
                    ],
                ]
            );
        }

        $this->end_controls_section();
    }

    protected function register_design_image_controls()
    {
        $this->start_controls_section(
            'section_design_image',
            [
                'label' => __('Image', 'ilc-elements'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    $this->get_control_id('thumbnail!') => 'none',
                ],
            ]
        );

        $this->add_control(
            'img_border_radius',
            [
                'label' => __('Border Radius', 'ilc-elements'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('thumbnail!') => 'none',
                ],
            ]
        );

        $this->add_control(
            'image_spacing',
            [
                'label' => __('Spacing', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-posts--thumbnail-left .elementor-post__thumbnail__link' => 'margin-right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-posts--thumbnail-right .elementor-post__thumbnail__link' => 'margin-left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-posts--thumbnail-top .elementor-post__thumbnail__link' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'default' => [
                    'size' => 20,
                ],
                'condition' => [
                    $this->get_control_id('thumbnail!') => 'none',
                ],
            ]
        );

        $this->start_controls_tabs('thumbnail_effects_tabs');

        $this->start_controls_tab(
            'normal',
            [
                'label' => __('Normal', 'ilc-elements'),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'thumbnail_filters',
                'selector' => '{{WRAPPER}} .elementor-post__thumbnail img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover',
            [
                'label' => __('Hover', 'ilc-elements'),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'thumbnail_hover_filters',
                'selector' => '{{WRAPPER}} .elementor-post:hover .elementor-post__thumbnail img',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function register_design_content_controls()
    {
        $this->start_controls_section(
            'section_design_content',
            [
                'label' => __('Content', 'ilc-elements'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label' => __('Title', 'ilc-elements'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    $this->get_control_id('show_title') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'ilc-elements'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    $this->get_control_id('show_title') => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a',
                'condition' => [
                    $this->get_control_id('show_title') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label' => __('Spacing', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('show_title') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_meta_style',
            [
                'label' => __('Meta', 'ilc-elements'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $this->get_control_id('meta_data!') => [],
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => __('Color', 'ilc-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__meta-data' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    $this->get_control_id('meta_data!') => [],
                ],
            ]
        );

        $this->add_control(
            'meta_separator_color',
            [
                'label' => __('Separator Color', 'ilc-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__meta-data span:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    $this->get_control_id('meta_data!') => [],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .elementor-post__meta-data',
                'condition' => [
                    $this->get_control_id('meta_data!') => [],
                ],
            ]
        );

        $this->add_control(
            'meta_spacing',
            [
                'label' => __('Spacing', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('meta_data!') => [],
                ],
            ]
        );

        $this->add_control(
            'heading_excerpt_style',
            [
                'label' => __('Excerpt', 'ilc-elements'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $this->get_control_id('show_excerpt') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => __('Color', 'ilc-elements'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__excerpt p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    $this->get_control_id('show_excerpt') => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .elementor-post__excerpt p',
                'condition' => [
                    $this->get_control_id('show_excerpt') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'excerpt_spacing',
            [
                'label' => __('Spacing', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('show_excerpt') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading_readmore_style',
            [
                'label' => __('Read More', 'ilc-elements'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    $this->get_control_id('show_read_more') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_color',
            [
                'label' => __('Color', 'ilc-elements'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__read-more' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    $this->get_control_id('show_read_more') => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'read_more_typography',
                'selector' => '{{WRAPPER}} .elementor-post__read-more',
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'condition' => [
                    $this->get_control_id('show_read_more') => 'yes',
                ],
            ]
        );

        $this->add_control(
            'read_more_spacing',
            [
                'label' => __('Spacing', 'ilc-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post__text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    $this->get_control_id('show_read_more') => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {

        $this->parent->query_posts();

        //print_r($this->get_setttings());
        /** @var \WP_Query $query */
        $query = $this->parent->get_query();

        if (!$query->found_posts) {
            return;
        }

        $this->render_loop_header();

        // hhhhhh
       

        $userFavs= new Wrapper();
        $userFavs= $userFavs->all_posts();
        echo "<script>";
        if ($userFavs){
        ?>
            // Add active class to selector
            const ilcPostIds=()=> [<?php
            $c = 0;
            foreach($userFavs as $post_id ) {
                $c++;
                echo $post_id;
                if($c!=count($userFavs)){
                    echo ",";
                }
            }?>]
        <?php
        }else{
            echo "console.log('No posts');";
            echo "const ilcPostIds=()=> []";
        }
        echo "</script>";
        ////////

        // It's the global `wp_query` it self. and the loop was started from the theme.
        if ($query->in_the_loop) {
            $this->current_permalink = get_permalink();
            $this->current_id = get_the_id();

            $this->render_post();
        } else {
            
            while ($query->have_posts()) {
                $query->the_post();
                $this->current_permalink = get_permalink();
                $this->current_id = get_the_id();
                $this->render_post();
            }
        }
        wp_reset_postdata();

        $this->render_loop_footer();
    
    }






    public function filter_excerpt_length()
    {
        return $this->get_instance_value('excerpt_length');
    }

    public function filter_excerpt_more($more)
    {
        return '';
    }

    public function get_container_class()
    {
        return 'elementor-posts--skin-' . $this->get_id();
    }





    
    protected function render_thumbnail()
    {
        $thumbnail = $this->get_instance_value('thumbnail');

        if ('none' === $thumbnail && !Plugin::elementor()->editor->is_edit_mode()) {
            return;
        }

        $settings = $this->parent->get_settings();
        $setting_key = $this->get_control_id('thumbnail_size');
        $settings[$setting_key] = [
            'id' => get_post_thumbnail_id(),
        ];

        $thumbnail_html = Group_Control_Image_Size::get_attachment_image_html($settings, $setting_key);

        if (empty($thumbnail_html)) {
            return;
        } 

        ?>

        <?php

        ?>
            <a class="elementor-post__thumbnail__link" href="<?php echo $this->current_permalink; ?>">
            <div class="elementor-post__thumbnail  <?php echo ($this->get_instance_value('pss_item_ratio')['size'] > 0.66) ?  ' elementor-fit-height ': ''; ?> "> <?php echo $thumbnail_html; ?> </div>
            </a>
        <?php
        
    }

    protected function render_title()
    {
        if (!$this->get_instance_value('show_title')) {
            return;
        }

        $tag = $this->get_instance_value('title_tag'); ?>
		<<?php echo $tag; ?> class="elementor-post__title">
			<a href="<?php echo $this->current_permalink; ?>">
				<?php the_title(); ?>
			</a>
		</<?php echo $tag; ?>>
		<?php
    }

    protected function render_excerpt()
    {
        if (!$this->get_instance_value('show_excerpt')) {
            return;
        } ?>
		<div class="elementor-post__excerpt">
			<p><?php echo wp_trim_words(get_the_excerpt(), $this->get_instance_value('excerpt_length'), '...'); ?></p>
		</div>
		<?php
    }

    protected function render_read_more()
    {
        if (!$this->get_instance_value('show_read_more')) {
            return;
        } ?>
			<a class="elementor-post__read-more" href="<?php echo $this->current_permalink; ?>">
	<?php echo $this->get_instance_value('read_more_text'); ?>
			</a>
		<?php
    }

    protected function render_post_header()
    {
        ?>
		<article  <?php post_class(['elementor-post elementor-grid-item server-side__'. $this->parent->get_data()['id'] . ' animated '. $this->get_instance_value('c_animation')]); echo ' style="animation-duration: '.$this->get_instance_value('animation_duration').'ms;"' ?> >
		<?php
    }

    protected function render_post_footer()
    {
        ?>
		</article>
		<?php
    }

    protected function render_text_header()
    {
        ?>
		<div class="elementor-post__text">
		<?php
    }

    protected function render_text_footer()
    {
        ?>
		</div>
		<?php
    }

    protected function render_loop_header()
    {
        $this->parent->add_render_attribute('container', [
            'class' => [
                'elementor-posts-container',
                'elementor-posts',
                'elementor-grid',
                'elementor-has-item-ratio',
                $this->get_container_class(),
            ],
        ]); 
        ?>
		    <div <?php echo $this->parent->get_render_attribute_string('container'); ?>>
        <?php
    }

    protected function render_loop_footer()
    {
        ?>
		</div>
		<?php

        $parent_settings = $this->parent->get_settings();

        if ('' === $parent_settings['pagination_type']) {
            return;
        }

        $page_limit = $this->parent->get_query()->max_num_pages;
        if ('' !== $parent_settings['pagination_page_limit']) {
            $page_limit = min($parent_settings['pagination_page_limit'], $page_limit);
        }

        if (2 > $page_limit) {
            return;
        }

        $this->parent->add_render_attribute('pagination', 'class', 'elementor-pagination');
        ?>
        
        <nav class="ajax elementor-pagination" role="navigation" aria-label="<?php esc_attr_e('Pagination', 'ilc-elements'); ?>">
    
        <?php
            $id = $this->parent->get_data('id');
            $param = [
                'id' => $id,
                'post_type' => $parent_settings['posts_post_type'],
                'posts_per_page' => $this->get_instance_value('posts_per_page'),
                'thumbnail_size' => $parent_settings[$this->get_id() . '_thumbnail_size_size'],
                'max_page' => $page_limit,
                'pagination_type' => $parent_settings['pagination_type'],
                'orderby' => $parent_settings['orderby'],
                'order' => $parent_settings['order'],
                'author' => $parent_settings['posts_authors'],
                'category' => $parent_settings['posts_category_ids'],
                'tags' => $parent_settings['posts_post_tag_ids'],
                'format' => $parent_settings['posts_post_format_ids'],
                'excerpt_length' => $this->get_instance_value('excerpt_length'),
                'current_page' => 1
            ];
            // echo '<pre>';
            // print_r($parent_settings);
            if ('numbers' === $parent_settings['pagination_type']) {
                $param['paged'] = 1;
                echo "<button ng-click='getPost(" . json_encode($param) . ",\"prev\" )' 
                
                class='page-numbers {{_". $id ."_class.current == 1 ? \" disabled\":null}}  {{!_". $id ."_class.current ? \" disabled\":null}} ' ><i class='fas fa-angle-left'></i></button>";
            
                for ($i = 1; $i <= $page_limit; $i++) {
                    $param['paged'] = $i;

                    if($parent_settings['pagination_numbers_shorten'] !== 'yes'){

                        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                            echo "<button class='page-numbers '> $i </button>";
                        } else{
                            echo "<button ng-cloak ng-click='getPost(" . 
                            json_encode($param) . ",\"paged\")' 
    
                            class='page-numbers {{_". $id ."_class.current == $i ? \"current disabled\":null}}
                            {{!_". $id ."_class.current && $i == 1  ? \"current disabled\":null}}'>" . $i . '</button>';
                        }
                        
                        // on page load it will be 1 how does this mark 
                    } else{

                        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                           if($i == $page_limit) echo "<button class='page-numbers disabled'> 1 of $page_limit</button>";
                        } else{
                            if($i == $page_limit) echo "<button class='page-numbers disabled' ng-cloak> {{_". $id ."_class.current ? _". $id ."_class.current : 1 }} of $page_limit</button>";
                        }
                    }
                } 


                echo "<button ng-click='getPost(" . json_encode($param) . ",\"next\")' 
                class='page-numbers {{_". $id ."_class.current == $page_limit ? \"disabled\":null}}'> <i class='fas fa-angle-right'></i></button>";
            
            }
            
            if ('load_more' === $parent_settings['pagination_type']) {
                $param['paged'] = 2;
                if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                    echo "<button class='page-numbers load-more'> &nbsp;LOAD MORE - " . (($page_limit * $this->get_instance_value('posts_per_page') - $this->get_instance_value('posts_per_page') . ' Posts') ) . "</button>";
                    return;
                }
                
                echo "<button ng-cloak ng-hide='_". $id ."_class.hide' ng-click='getPost(" . json_encode($param) . ", _". $id ."_class.current == $page_limit + 1" . ")' class='page-numbers load-more'> &nbsp;LOAD MORE - {{_" .$id. "_class.postsToLoad || '" . (($page_limit * $this->get_instance_value('posts_per_page') - $this->get_instance_value('posts_per_page') . ' Posts') ) . "' }}</button>";
            }
            
            
        ?>
        </nav>
        
		<?php
    }

    protected function render_meta_data()
    {
        /** @var array $settings e.g. [ 'author', 'date', ... ] */
        $settings = $this->get_instance_value('meta_data');
        if (empty($settings)) {
            return;
        } 

        ?>
		    <div class="elementor-post__meta-data">
        <?php

        if (in_array('author', $settings)) {
            $this->render_author();
        }

        if (in_array('date', $settings)) {
            $this->render_date();
        }

        if (in_array('time', $settings)) {
            $this->render_time();
        }

        if (in_array('comments', $settings)) {
            $this->render_comments();
        } 
        
        ?>
		    </div>
		<?php
    }

    protected function render_author()
    {
        ?>
            <span class="elementor-post-author">
                <?php the_author(); ?>
            </span>
		<?php
    }

    protected function render_date()
    {
        ?>
            <span class="elementor-post-date">
                <?php
            /** This filter is documented in wp-includes/general-template.php */
            echo apply_filters('the_date', get_the_date(), get_option('date_format'), '', ''); ?>
            </span>
		<?php
    }

    protected function render_time()
    {
        ?>
            <span class="elementor-post-time">
                <?php the_time(); ?>
            </span>
		<?php
    }

    protected function render_comments()
    {
        ?>
		<span class="elementor-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
    }

    protected function render_post()
    {
        $this->render_post_header();
        $this->render_thumbnail();
        $this->render_text_header();
        $this->render_title();
        $this->render_meta_data();
        $this->render_excerpt();
        $this->render_read_more();
        $this->render_text_footer();
        $this->render_post_footer();
    }

    protected function render_post_wrapper(){
        ?>
        
        <article  ng-show="<?php echo '_'. $this->parent->get_data('id').'.length';?>" ng-repeat="post in <?php echo '_'. ($this->parent->get_data('id')); ?>||[] track by $index" <?php post_class( [ 'elementor-post  elementor-grid-item client-side__'. $this->parent->get_data()['id'] . 'animated '. $this->get_instance_value('c_animation')] ); echo ' style="animation-duration: '.$this->get_instance_value('animation_duration').'ms;"'?>>
        <?php
    }

    protected function render_post_wrapper_end(){
        ?>
            </article>
        <?php
    }


    protected function render_title_wrapper()
    {
        ?>
		<div class="elementor-post__text">
		<?php
    }

    protected function render_title_wrapper_end()
    {
        ?>
		</div>
		<?php
    }

}
