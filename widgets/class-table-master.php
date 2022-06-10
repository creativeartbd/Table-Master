<?php
/**
 * TableMaster class.
 *
 * @category   Class
 * @package    TableMaster
 * @subpackage WordPress
 * @author     Shibbir <https://wwww.shibbir.dev>
 * @copyright  2022 Shibbir
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @since      1.0.0
 * php version 7.3.9
 */

namespace TableMaster\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Shuchkin\SimpleXLSX;

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

		wp_register_style( 'tablemaster', plugins_url( '/assets/css/tablemaster.css', ELEMENTOR_TABLEMASTER ), array(), '1.0.0' );
		wp_register_script( 'tablemaster', plugins_url( '/assets/js/tablemaster.js', ELEMENTOR_TABLEMASTER ), array('jquery'), '1.0.0', true );
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

		/* ===========================
		Control Section Start Here
		=========================== */

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'table-master' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sh_tabe_or_file',
			[
				'label' => esc_html__( 'Build manual table', 'table-master' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'your-plugin' ),
				'label_off' => esc_html__( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'sh_details_heading_title', [
				'label' => esc_html__( 'Details Heading', 'table-master' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Details Heading Name' , 'table-master' ),
				'label_block' => true,
				'condition' => [
					'sh_tabe_or_file' => 'yes',
				],
			]
		);

		$this->add_control(
			'sh_data_heading_title', [
				'label' => esc_html__( 'Data Heading', 'table-master' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Data Heading Name' , 'table-master' ),
				'label_block' => true,
				'condition' => [
					'sh_tabe_or_file' => 'yes',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'sh_details_title', [
				'label' => esc_html__( 'Details Title', 'table-master' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title name' , 'table-master' ),
				'label_block' => true,
				'condition' => [
					'sh_tabe_or_file' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'sh_data_description', [
				'label' => esc_html__( 'Data Description', 'table-master' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Data description...' , 'table-master' ),
				'label_block' => true,
				'condition' => [
					'sh_tabe_or_file' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'sh_table_info',
			[
				'label' => esc_html__( 'Add table informaton', 'table-master' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'sh_details_title' => esc_html__( 'Details Title #1', 'table-master' ),
						'sh_data_description' => esc_html__( 'Data Description...', 'table-master' ),
					],
				],
				'title_field' => 'Add details and data description',
				'condition' => [
					'sh_tabe_or_file' => 'yes',
				],
			]
		);

		$this->add_control(
			'or_options',
			[
				'label' => esc_html__( 'Or upload files', 'table-master' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sh_tabe_or_file' => '',
				],
			]
		);


		$this->add_control(
			'sh_file_upload_excel',
			[
				'label' => esc_html__( 'Upload Excel File', 'table-master' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'sh_tabe_or_file' => '',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		/* ===========================
		Control Section END Here
		=========================== */

		/* ===========================
		Style Section Start Here
		=========================== */

		$this->end_controls_section();

		$this->start_controls_section(
			'sh_table_heading',
			[
				'label' => esc_html__( 'Heading', 'table-master' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sh_heading_padding',
			[
				'label' => esc_html__( 'Heading Padding', 'table-master' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .sh_table_master table thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sh_heading_alignment',
			[
				'label' => esc_html__( 'Heading Alignment', 'table-master' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'table-master' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'table-master' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'table-master' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .sh_table_master table thead tr th' => 'text-align : {{VALUE}};',
				],
				// 'selector' => '{{WRAPPER}} .sh_table_master table thead tr th',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'sh_heading_typography',
				'selector' => '{{WRAPPER}} .sh_table_master table thead tr th',
			]
		);

		$this->start_controls_tabs(
			'sh_heading_color_tabs'
		);

			$this->start_controls_tab(
				'sh_heading_title_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_control(
				'sh_heading_title_normal_color',
				[
					'label' => esc_html__( 'Heading Title Color', 'table-master' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table thead tr th' => 'color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_heading_title_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_control(
				'sh_heading_title_hover_color',
				[
					'label' => esc_html__( 'Heading Title Hover Color', 'table-master' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table thead tr th:hover' => 'color: {{VALUE}}',
					],
				]
			);
	
			$this->end_controls_tab();

		$this->end_controls_tabs();
	
		$this->start_controls_tabs(
			'sh_heading_background_tabs'
		);

			$this->start_controls_tab(
				'sh_heading_background_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sh_normal_background',
					'label' => esc_html__( 'Heading Background Color', 'table-master' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sh_table_master table thead tr',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_heading_background_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sh_hover_background',
					'label' => esc_html__( 'Heading Background Color', 'table-master' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sh_table_master table thead tr:hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'sh_heading_shadow',
				'label' => esc_html__( 'Heading Text Shadow', 'table-master' ),
				'selector' => '{{WRAPPER}} .sh_table_master table thead tr th',
			]
		);

		$this->start_controls_tabs(
			'sh_heading_border_tabs'
		);

			$this->start_controls_tab(
				'sh_heading_border_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'sh_heading_normal_border',
					'label' => esc_html__( 'Heading Border', 'table-master' ),
					'selector' => '{{WRAPPER}} .sh_table_master table thead',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_heading_border_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'sh_heading_hover_border',
					'label' => esc_html__( 'Heading Border', 'table-master' ),
					'selector' => '{{WRAPPER}} .sh_table_master table thead:hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'sh_heading_shadow',
				'label' => esc_html__( 'Heading Text Shadow', 'table-master' ),
				'selector' => '{{WRAPPER}} .sh_table_master table thead tr th',
			]
		);

		$this->start_controls_tabs(
			'sh_heading_opacity_tabs'
		);

			$this->start_controls_tab(
				'sh_heading_opacity_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_responsive_control(
				'sh_heading_normal_opacity',
				[
					'label' => esc_html__( 'Heading Opacity', 'table-master' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table thead' => 'opacity: {{SIZE}};',
					],
				]
			);
	
			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_heading_opacity_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_responsive_control(
				'sh_heading_hover_opacity',
				[
					'label' => esc_html__( 'Heading Opacity', 'table-master' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table thead:hover' => 'opacity: {{SIZE}};',
					],
				]
			);
	

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'sh_table_body',
			[
				'label' => esc_html__( 'Body', 'table-master' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sh_body_padding',
			[
				'label' => esc_html__( 'Body Text Padding', 'table-master' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .sh_table_master table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'sh_body_alignment',
			[
				'label' => esc_html__( 'Body Alignment', 'table-master' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'table-master' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'table-master' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'table-master' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .sh_table_master table tbody tr td' => 'text-align : {{VALUE}};',
				],
				// 'selector' => '{{WRAPPER}} .sh_table_master table thead tr th',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'sh_body_typography',
				'selector' => '{{WRAPPER}} .sh_table_master table tbody tr td',
			]
		);

		$this->add_control(
			'more_options',
			[
				'label' => esc_html__( 'Body text color', 'table-master' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'sh_body_color_tabs'
		);

			$this->start_controls_tab(
				'sh_body_title_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_control(
				'sh_body_title_normal_color',
				[
					'label' => esc_html__( 'Body text color', 'table-master' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tbody tr td' => 'color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_body_title_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_control(
				'sh_body_title_hover_color',
				[
					'label' => esc_html__( 'Body text color', 'table-master' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tbody tr td:hover' => 'color: {{VALUE}}',
					],
				]
			);
	
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'sh_body_shadow',
				'label' => esc_html__( 'Border text shadow', 'table-master' ),
				'selector' => '{{WRAPPER}} .sh_table_master table tbody tr td',
			]
		);

		$this->start_controls_tabs(
			'sh_body_background_tabs'
		);

			$this->start_controls_tab(
				'sh_body_background_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sh_normal_body_background',
					'label' => esc_html__( 'Body Background Color', 'table-master' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sh_table_master table tbody tr td',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_body_background_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sh_body_hover_background',
					'label' => esc_html__( 'Body Background Hover Color', 'table-master' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sh_table_master table tbody tr td:hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs(
			'sh_body_opacity_tabs'
		);

			$this->start_controls_tab(
				'sh_body_opacity_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_responsive_control(
				'sh_body_normal_opacity',
				[
					'label' => esc_html__( 'Body Text Opacity', 'table-master' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tbody tr td' => 'opacity: {{SIZE}};',
					],
				]
			);
	
			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_body_opacity_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_responsive_control(
				'sh_body_hover_opacity',
				[
					'label' => esc_html__( 'Body Text Opacity', 'table-master' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tbody tr td:hover' => 'opacity: {{SIZE}};',
					],
				]
			);
	

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs(
			'sh_body_border_tabs'
		);

			$this->start_controls_tab(
				'sh_body_border_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'sh_body_normal_border',
					'label' => esc_html__( 'Body Text Border', 'table-master' ),
					'selector' => '{{WRAPPER}} .sh_table_master table tbody tr td',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_body_border_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'sh_body_hover_border',
					'label' => esc_html__( 'Body Text Hover Border', 'table-master' ),
					'selector' => '{{WRAPPER}} .sh_table_master table tbody tr td:hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();
	
		$this->end_controls_section();


		/* ===============
		First Column styles
		*=================*/

		$this->start_controls_section(
			'sh_table_first_column',
			[
				'label' => esc_html__( 'First Column', 'table-master' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sh_first_column_padding',
			[
				'label' => esc_html__( 'First Column Text Padding', 'table-master' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .sh_table_master table tbody tr td:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sh_first_column_alignment',
			[
				'label' => esc_html__( 'First Column Alignment', 'table-master' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'table-master' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'table-master' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'table-master' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .sh_table_master table tbody tr td:first-child' => 'text-align : {{VALUE}} !important;',
				],
				// 'selector' => '{{WRAPPER}} .sh_table_master table thead tr th',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'sh_first_column_typography',
				'selector' => '{{WRAPPER}} .sh_table_master table tr td:first-child',
			]
		);

		$this->add_control(
			'first_column_more_options',
			[
				'label' => esc_html__( 'First column text color', 'table-master' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'sh_first_column_color_tabs'
		);

			$this->start_controls_tab(
				'sh_first_column_title_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_control(
				'sh_first_column_title_normal_color',
				[
					'label' => esc_html__( 'Body text color', 'table-master' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tr td:first-child' => 'color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_first_column_title_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_control(
				'sh_first_column_title_hover_color',
				[
					'label' => esc_html__( 'Body text color', 'table-master' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tr td:first-child:hover' => 'color: {{VALUE}}',
					],
				]
			);
	
			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'sh_first_column_shadow',
				'label' => esc_html__( 'First column text shadow', 'table-master' ),
				'selector' => '{{WRAPPER}} .sh_table_master table tr td:first-child',
			]
		);

		$this->start_controls_tabs(
			'sh_first_column_background_tabs'
		);

			$this->start_controls_tab(
				'sh_first_column_background_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sh_normal_first_column_background',
					'label' => esc_html__( 'First Column Background Color', 'table-master' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sh_table_master table tr td:first-child',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_first_column_background_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'sh_first_column_hover_background',
					'label' => esc_html__( 'First Column Background Hover Color', 'table-master' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .sh_table_master table tr td:first-child:hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs(
			'sh_first_column_opacity_tabs'
		);

			$this->start_controls_tab(
				'sh_first_column_opacity_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_responsive_control(
				'sh_first_column_normal_opacity',
				[
					'label' => esc_html__( 'First Column Text Opacity', 'table-master' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tr td:first-child' => 'opacity: {{SIZE}};',
					],
				]
			);
	
			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_first_column_opacity_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_responsive_control(
				'sh_first_column_hover_opacity',
				[
					'label' => esc_html__( 'First Column Text Opacity', 'table-master' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => .1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .sh_table_master table tr td:first-child:hover' => 'opacity: {{SIZE}};',
					],
				]
			);
	

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs(
			'sh_first_column_border_tabs'
		);

			$this->start_controls_tab(
				'sh_first_column_border_normal_tab',
				[
					'label' => esc_html__( 'Normal', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'sh_first_column_normal_border',
					'label' => esc_html__( 'First Column Text Border', 'table-master' ),
					'selector' => '{{WRAPPER}} .sh_table_master table tr td:first-child',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'sh_first_column_border_hover_tab',
				[
					'label' => esc_html__( 'Hover', 'table-master' ),
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'sh_first_column_hover_border',
					'label' => esc_html__( 'First Column Text Hover Border', 'table-master' ),
					'selector' => '{{WRAPPER}} .sh_table_master table tr td:first-child :hover',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		/* ===========================
		Style Section END Here
		=========================== */
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

		$excel_file = isset( $settings['sh_file_upload_excel']['url'] ) ? $settings['sh_file_upload_excel']['url'] : '';
		
		$sh_tabe_or_file = $settings['sh_tabe_or_file'];
		$sh_details_heading_title = $settings['sh_details_heading_title'];
		$sh_data_heading_title = $settings['sh_data_heading_title'];
		$sh_table_info = $settings['sh_table_info'];
		$sh_heading_alignment = $settings['sh_heading_alignment'];
		$extension = '';

		if( ! empty( $excel_file ) ) {
			$file_id = $settings['sh_file_upload_excel']['id'];
			$plugin_dir = WP_PLUGIN_DIR . '/table-master/';
			require_once $plugin_dir.'lib/SimpleXLSX.php';
			$attached_file = get_attached_file( $file_id );
			$explode = explode( '.', $excel_file );
			$extension = end( $explode );
		}
		
		if( 'yes' == $sh_tabe_or_file ) {
			if( !empty( $sh_details_heading_title ) && !empty( $sh_data_heading_title) ) {
				echo "<div class='sh_table_master'>";
					echo "<table cellpadding='0' cellspacing='0'>";
						echo "<thead>";
							echo "<tr>";
								echo "<th>$sh_details_heading_title</th>";
								echo "<th>$sh_data_heading_title</th>";
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						if( !empty( $sh_table_info ) ) {

							foreach( $sh_table_info as $sh_info ) {

								$sh_details_title = $sh_info['sh_details_title'];
								$sh_data_description = $sh_info['sh_data_description'];

								echo "<tr>";
									echo "<td>$sh_details_title</td>";
									echo "<td>$sh_data_description</td>";
								echo "</tr>";
							}
						}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
			}
		} else {
			if( ! in_array( $extension, ['xlsx', 'xls' ] ) ) {
				echo "<div>Please upload either xlsx or xls file.</div>";
			} else {
				if( !empty( $excel_file ) ) {
					if ($xlsx = SimpleXLSX::parse( $attached_file ) ) {
						$results = $xlsx->rows();
						echo "<div class='sh_table_master'>";
						echo "<table cellpadding='0' cellspacing='0'>";
							echo "<thead>";
								echo "<tr>";
									foreach( $results[0] as $key => $result ) {
										echo "<th>$result</th>";
									}
								echo "</tr>";
							echo "</thead>";
		
							unset( $results[0] );
							
							echo "<tbody>";
							foreach( $results as $key => $value ) {
								echo "<tr>";
									foreach( $value as $val ) {
										echo "<td>$val</td>";
									}	
								echo "</tr>";
							}
							echo "</tbody>";
						echo "</table>";
						echo "</div>";
					} else {
						echo SimpleXLSX::parseError();
					}
				}
			}
		}
	}
}
