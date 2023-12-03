<?php


namespace IPS\jsmcloudchat\modules\admin\jsmcloudchatsetting;

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !\defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	header( ( isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0' ) . ' 403 Forbidden' );
	exit;
}

/**
 * jsmcloudchatsettingspage
 */
class _jsmcloudchatsettingspage extends \IPS\Dispatcher\Controller
{
	/**
	 * Execute
	 *
	 * @return	void
	 */
	public function execute()
	{
		\IPS\Dispatcher::i()->checkAcpPermission( 'jsmcloudchatsettingspage_manage' );
		parent::execute();
	}

	/**
	 * ...
	 *
	 * @return	void
	 */
	protected function manage()
	{
		\IPS\Output::i()->title = \IPS\Member::loggedIn()->language()->addToStack('jsmcloudchatsettingspage_manage');

		//create a text field to save the api key
		$form = new \IPS\Helpers\Form;
		$form->addHeader('Manage JSM Cloud Chat Settings');
		$form->add( new \IPS\Helpers\Form\Text( 'jsmcloudchat_api_key', \IPS\Settings::i()->jsmcloudchat_api_key, TRUE,  ) );

		$form->add( new \IPS\Helpers\Form\Text( 'jsmcloudchat_jira_ID', \IPS\Settings::i()->jsmcloudchat_jira_ID, TRUE ) );

		$form->add( new \IPS\Helpers\Form\Number( 
			'jsmcloudchat_service_desk_id', 
			\IPS\Settings::i()->jsmcloudchat_service_desk_id, 
			TRUE, 
			array( 
				'default' => 3,
				
			) 
		));

		//add a number field to save the minutes
		$form->add( new \IPS\Helpers\Form\Number( 
			'jsmcloudchat_exp_minutes', 
			\IPS\Settings::i()->jsmcloudchat_exp_minutes, 
			TRUE, 
			array( 
				'min' => 1,
				'unit' => 'minutes',
				'default' => 1,
				
			) 
		));

		$form->add( new \IPS\Helpers\Form\YesNo( 'jsmcloudchat_enabled', \IPS\Settings::i()->jsmcloudchat_enabled, TRUE, array(), NULL, NULL, NULL, 'jsmcloudchat_enabled' ) );

		//if the form is submitted
		if ( $values = $form->values() )
		{
			//compare the new values with the old values
			$oldValues = array(
				'jsmcloudchat_api_key' => \IPS\Settings::i()->jsmcloudchat_api_key,
				'jsmcloudchat_exp_minutes' => \IPS\Settings::i()->jsmcloudchat_exp_minutes,
				'jsmcloudchat_enabled' => \IPS\Settings::i()->jsmcloudchat_enabled,
				'jsmcloudchat_jira_ID' => \IPS\Settings::i()->jsmcloudchat_jira_ID,
				'jsmcloudchat_service_desk_id' => \IPS\Settings::i()->jsmcloudchat_service_desk_id,	
			);
			foreach ($values as $key => $value) {
				if ($value != $oldValues[$key]) {
					//if the setting is the API key, log only the first few characters
					if ($key == 'jsmcloudchat_api_key') {
						$value = mb_substr($value, 0, 20) . '...';
						$oldValues[$key] = mb_substr($oldValues[$key], 0, 20) . '...';
					}

					//log the change
					\IPS\Session::i()->log(
						'JSM Cloud Chat Setting Changed: ' 
						. $key . ' Changed From ' . $oldValues[$key] . ' To ' . $value,
						array(
							'setting__log_key' => $key,
							'setting__new' => $value,
							'setting__old' => $oldValues[$key]
						)
					);
				}
			}
		
			//save the settings
			$form->saveAsSettings();
		}

		//display the form
		\IPS\Output::i()->output = $form;

	}
	
	// Create new methods with the same name as the 'do' parameter which should execute it
}