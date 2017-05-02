<?php
require_once INCLUDE_DIR . 'class.plugin.php';
class OsticketToDiscordPluginConfig extends PluginConfig
{

	function getOptions()
	{
		return array(
			'ostickettodiscord' => new SectionBreakField(array(
				'label' => 'Discord Notification'
			)),
			'ostickettodiscord-webhook-url' => new TextboxField(array(
				'label' => 'Webhook URL',
				'configuration' => array(
					'size' => 80,
					'length' => 200
				)
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
					'size' => 80,
					'length' => 200
				)
			)),	
			'ostickettodiscord-tts' => new BooleanField(array(
				'id' => 'ostickettodiscord-tts',
				'label' => 'Read Voice notification ',
				'configuration' => array(
					'desc' => 'Lit la notice'
				)
			)),					
			'ostickettodiscord-text-escape' => new BooleanField(array(
				'id' => 'ostickettodiscord-text-escape',
				'label' => 'Escape text',
				'configuration' => array(
					'desc' => 'Check to escape text (You must have <a href="https://github.com/soundasleep/html2text/blob/master/src/Html2Text.php">Html2Text</a> in plugin /lib directory for full functionality)'
				)
			)),
			'ostickettodiscord-text-doublenl' => new BooleanField(array(
				'id' => 'ostickettodiscord-text-doublenl',
				'label' => 'Remove double newlines',
				'configuration' => array(
					'desc' => 'Check to remove double newlines'
				)
			)),
			'ostickettodiscord-text-length' => new TextboxField(array(
				'label' => 'Text length to show',
				'configuration' => array(
					'size' => 20,
					'length' => 20
				)
			)),

		);
	}
}

