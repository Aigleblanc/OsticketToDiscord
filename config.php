<?php
require_once INCLUDE_DIR . 'class.plugin.php';
class OsticketToDiscordPluginConfig extends PluginConfig
{

	function getOptions()
	{
		return array(
			'ostickettodiscord' => new SectionBreakField(array(
				'label' => 'Informations'
			)),
			'ostickettodiscord-username' => new TextboxField(array(
				'label' => 'Username',
				'configuration' => array(
					'size' => 20,
					'length' => 20
				)
			)),
			'ostickettodiscord-avatar' => new TextboxField(array(
				'label' => 'Avatar',
				'configuration' => array(
					'size' => 20,
					'length' => 200
				)
			)),	
			'ostickettodiscord-text-length' => new TextboxField(array(
				'label' => 'Longueur du text a afficher',
				'configuration' => array(
					'size' => 20,
					'length' => 20
				)
			)),
			'ostickettodiscord-sub' => new SectionBreakField(array(
				'label' => 'Discord'
			)),
			'ostickettodiscord-webhook-url' => new TextboxField(array(
				'label' => 'Webhook URL',
				'configuration' => array(
					'size' => 80,
					'length' => 200
				)
			)),
			'ostickettodiscord-tts' => new BooleanField(array(
				'id' => 'ostickettodiscord-tts',
				'label' => 'Lit les notification',
				'configuration' => array(
					'desc' => 'Lit la notification avec une voie de synthese. ( win only )'
				)
			))

		);
	}
}

