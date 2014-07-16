<?php
/**
 * Adds the Ravelry Designs Widget... settings page
 */
class Rdw_Settings
{
	private static $ins = null;
	private $settings;

	private static function instance()
	{
		is_null(self::$ins) && self::$ins = new self();
		return self::$ins;
	}

	public static function init()
	{
		add_action('admin_menu', array(self::instance(), 'registerSettingsPage'));
		add_action('admin_init', array(self::instance(), 'settingsInit'));
	}

	public function registerSettingsPage()
	{
		add_options_page(
			'Ravelry Designs Widget Options',
			'Ravelry Designs Widget',
			'manage_options',
			'rdw',
			array(self::instance(), 'settingsPage')
		);
	}

	public function settingsInit()
	{
		// Register a settings container
		register_setting(
			'rdw_settings_group',
			'rdw_settings',
			array(self::instance(), 'sanitizeInputs')
		);

		// Define settings form sections
		add_settings_section(
            'rdw_settings_rav_id',
            'Ravelry Details',
            function() {},// array(self::instance(), 'print_section_info' ), // Callback
            'rdw'
        );
		add_settings_section(
            'rdw_settings_display',
            'Pattern Display',
            function() {},// array(self::instance(), 'print_section_info' ), // Callback
            'rdw'
        );

        // Add the fields
        add_settings_field(
        	'ravelry_designer_name',
        	'Designer Name:',
        	array(self::instance(), 'ravelryDesignerNameField'),
        	'rdw',
        	'rdw_settings_rav_id'
        );
        add_settings_field(
        	'pattern_display_count',
        	'Number to display:',
        	array(self::instance(), 'ravelryDisplayCountField'),
        	'rdw',
        	'rdw_settings_display'
        );
	}

	public function settingsPage()
	{
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page. Go away, naughty person.'));
		}

		$this->settings = get_option('rdw_settings');

		include 'tpl/settings-page-tpl.php';
	}

	public function ravelryDesignerNameField()
	{
		printf(
			'<input
				type="text"
				id="ravelry_designer_name"
				name="rdw_settings[ravelry_designer_name]"
				value="%s" />',
				isset($this->settings['ravelry_designer_name']) ?
					esc_attr($this->settings['ravelry_designer_name']) : ''
        );
        echo '<p class="description">This is the name under which your patterns
			are listed on Ravelry.<br>Remember that your Ravelry Designer Name is not
			necessarily<br>the same as your Ravelry login name.</p>';
	}

	public function ravelryDisplayCountField()
	{
		printf(
			'<input
				type="text"
				id="pattern_display_count"
				name="rdw_settings[pattern_display_count]"
				value="%s" />',
				isset($this->settings['pattern_display_count']) ?
					esc_attr($this->settings['pattern_display_count']) : ''
        );
	}

	public function sanitizeInputs($input)
	{
		$newInput = array();
        if (isset($input['pattern_display_count'])) {
        	$new_input['pattern_display_count'] = absint($input['pattern_display_count']);
        }

        if (isset($input['ravelry_designer_name'])) {
        	$new_input['ravelry_designer_name'] = sanitize_text_field($input['ravelry_designer_name'] );
        }

        return $new_input;
	}
}
