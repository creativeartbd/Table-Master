<?php
/**
 * TableMaster class.
 *
 * @category   Class
 * @package    TableMaster
 * @subpackage WordPress
 * @author     Shibbir <shibbir.me>
 * @copyright  2022 Shibbir
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @since      1.0.0
 * php version 7.3.9
 */

namespace TableMaster\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * TableMaster widget class.
 *
 * @since 1.0.0
 */
class TableMaster extends Widget_Base {
	/**
	 * Class constructor.
	 *
	 * @param array $data Widget data.
	 * @param array $args Widget arguments.
	 */
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'tablemaster', plugins_url( '/assets/css/tablemaster.css', ELEMENTOR_NAVIGATEMASTER ), array(), '1.0.0' );
		wp_register_script( 'tablemaster', plugins_url( '/assets/js/tablemaster.js', ELEMENTOR_NAVIGATEMASTER ), array('jquery'), '1.0.0', true );
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tablemaster';
	}

	public function get_script_depends() {
		return [ 'tablemaster' ];
	}
 

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Table Master', 'table-master' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-table';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}
	
	/**
	 * Enqueue styles.
	 */
	public function get_style_depends() {
		return array( 'tablemaster' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'table-master' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'nm_title', [
				'label' => esc_html__( 'Navigation Title', 'table-master' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Navigation Title' , 'table-master' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'nm_link', [
				'label' => esc_html__( 'Navigation Link', 'table-master' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Navigation Link' , 'table-master' ),
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'nm_list',
			[
				'label' => esc_html__( 'New Navigation', 'table-master' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'nm_title' => esc_html__( 'Navigation Title #1', 'table-master' ),
						'nm_link' => esc_html__( 'nav1', 'table-master' ),
					],
				],
				'title_field' => '{{{ nm_title }}}',
			]
		);

		$this->add_control(
			'nm_nm_title',
			[
				'label' => esc_html__( 'Show Title', 'table-master' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'your-plugin' ),
				'label_off' => esc_html__( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style', 'table-master' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nm_bg_color', [
				'label' => esc_html__( 'Hover Color', 'table-master' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => esc_html__( '#f00' , 'table-master' ),
				'label_block' => true,
				'selectors' => [
					'{{WRAPPER}} .table-master ul li a:hover' => 'color: {{VALUE}} ',
				],
				'condition' => [
					'nm_nm_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'table-master' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .table-master ul li a',
				'default' => esc_html__( '#ffb200' , 'table-master' ),
				'condition' => [
					'nm_nm_title' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_hover',
				'label' => esc_html__( 'Background Hover', 'table-master' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .table-master ul li a:hover',
				'default' => esc_html__( 'yellow' , 'table-master' ),
				'condition' => [
					'nm_nm_title' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background_active',
				'label' => esc_html__( 'Background Active', 'table-master' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .table-master ul li.active a',
				'default' => esc_html__( '#ffb200' , 'table-master' ),
				'condition' => [
					'nm_nm_title' => '',
				],
			]
		);


		$this->add_responsive_control(
			'nm_margin',
			[
				'label' => esc_html__( 'Margin', 'table-master' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .table-master ul li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nm_padding',
			[
				'label' => esc_html__( 'Padding', 'table-master' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .table-master ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'nm_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'table-master' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .table-master ul li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nm_content_typography',
				'selector' => '{{WRAPPER}} .table-master ul li a',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'nm_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'table-master' ),
				'selector' => '{{WRAPPER}} .table-master ul li a',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'nm_border',
				'label' => esc_html__( 'Border', 'table-master' ),
				'selector' => '{{WRAPPER}} .table-master ul li a',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'nm_width',
			[
				'label' => esc_html__( 'Width', 'table-master' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .table-master ul li a' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nm_height',
			[
				'label' => esc_html__( 'Height', 'table-master' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .table-master ul li a' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$this->add_responsive_control(
			'nv_position',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => esc_html__( 'Navigation Position', 'table-master' ),
				'options' => [
					'default' => esc_html__( 'Default', 'table-master' ),
					'absolute' => esc_html__( 'Absolute', 'table-master' ),
					'fixed' => esc_html__( 'Fixed', 'table-master' ),
				],
				'default' => 'fixed',
				'selectors' => [
					'{{WRAPPER}} .table-master' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'nm_position_right',
			[
				'label' => esc_html__( 'Right', 'table-master' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .table-master' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nm_position_top',
			[
				'label' => esc_html__( 'Top', 'table-master' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .table-master' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
	

		$this->end_controls_section();

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'description', 'basic' );
		$this->add_inline_editing_attributes( 'content', 'advanced' );
	

		$lists = $settings['nm_list'];
		$nm_nm_title = $settings['nm_nm_title'];
		?>

		<div class="table-master">
			<ul>
				<?php
				foreach( $lists as $list )  {
					if( 'yes' === $nm_nm_title ) {
						echo "<li><a href='#".$list['nm_link']."'>".$list['nm_title']."</a></li>";
					} else {
						echo "<li><a href='#".$list['nm_link']."'>&nbsp;</a></li>";
					}
				}
				?>
			</ul>
		</div>
		<?php
	}

}
