<?php

/**
 * Register all actions and filters for the plugin
 *
 * @since      1.0.0
 *
 * @package    WC_Braspress
 * @subpackage includes
 */

/**
 * Load and register every filters and actions needed
 * for the Woocommerce Braspress.
 *
 * Manages the list of all filters and hooks used throughout the
 * Woocommerce Braspress plugin.
 * The run () method executes this list, keeping the process
 * centralized in this class.
 *
 * @package    WC_Braspress
 * @subpackage includes
 * @author     Thiago Alencar <thiagofalencar@gmail.com>
 */
class WC_Braspress_Loader {

	/**
	 * It is an array with all the actions that need to be registered in WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions list that needed to be initialized on Woocommerce Braspress load.
	 */
	protected $actions;

	/**
	 * It is an array with all the filters that need to be registered in WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters list that needed to be initialized on Woocommerce Braspress load.
	 */
	protected $filters;

	/**
	 * Initialize the list of actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * This method add a new action to the action list, that will be registered on WordPress.
	 *
	 * @since    1.0.0
	 * @param    String               $hook             The hook name action that is being registered.
	 * @param    object               $component        The instance of object on which the action is defined.
	 * @param    String               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         (Optional). The priority at which the function will be loaded, by Default is set 10.
	 * @param    int                  $args_numbers     (Optional). The number of arguments that will be passed to the $callback, by Default is set 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $args_numbers = 1 ) {
		$this->actions = $this->add(
			$this->actions,
			$hook,
			$component,
			$callback,
			$priority,
			$args_numbers
		);
	}

	/**
	 * This method add a new filter to the action list, that will be registered on WordPress.
	 *
	 * @since    1.0.0
	 * @param    String               $hook             The hook name filter that is being registered.
	 * @param    object               $component        The instance of object on which the filter is defined.
	 * @param    String               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         (Optional) The priority at which the function will be loaded, by Default is set 10.
	 * @param    int                  $args_numbers     (Optional) The number of arguments that will be passed to the $callback, by Default is set 1.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $args_numbers = 1 ) {
		$this->filters = $this->add( $this->filters,
			$hook,
			$component,
			$callback,
			$priority,
			$args_numbers
		);
	}

	/**
	 * This function is used to load the actions and hooks into a single list.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The list of hooks(actions or filters) that is being registered.
	 * @param    String               $hook             The name filter that is being registered.
	 * @param    object               $component        The instance of object on which the filter is defined.
	 * @param    String               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function will be loaded, by Default is set 10.
	 * @param    int                  $args_numbers     The number of arguments that will be passed to the $callback, by Default is set 1.
	 * @return   array                                  The list of actions and filters registered in WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $args_numbers ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $args_numbers
		);

		return $hooks;

	}

	/**
	 * Register all the actions and filters in WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {

			if ( !is_null($hook['component'] ) ) {
				$callback = array(
					$hook['component'],
					$hook['callback']
				);
			} else {
				$callback = $hook['callback'];
			}

			add_filter(
				$hook['hook'],
				$callback,
				$hook['priority'],
				$hook['accepted_args']
			);
		}

		foreach ( $this->actions as $hook ) {

			if ( !is_null($hook['component'] ) ) {
				$callback = array(
					$hook['component'],
					$hook['callback']
				);
			} else {
				$callback = $hook['callback'];
			}

			add_action(
				$hook['hook'],
				$callback,
				$hook['priority'],
				$hook['accepted_args']
			);

		}

	}

}
