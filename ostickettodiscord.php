<?php
require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.plugin.php');
require_once('config.php');
require_once('DiscordClient.php');
include('lib/Html2Text.php');

use \DiscordWebhooks\Client as DiscordWebhook;

class OsticketToDiscordPlugin extends Plugin
{
	
	var $config_class = "OsticketToDiscordPluginConfig";

	function bootstrap()
	{
		Signal::connect('model.created', array(
			$this,
			'onTicketCreated'
		), 'Ticket');
		Signal::connect('model.created', array(
			$this,
			'onThreadEntryCreated'
		), 'ThreadEntry');
	}
	function onThreadEntryCreated($entry)
	{
		if ($entry->ht[ 'thread_type' ] == 'R') {
			// Responses by staff
			$this->onResponseCreated($entry);
		} elseif ($entry->ht[ 'thread_type' ] == 'N') {
			// Notes by staff or system
			$this->onNoteCreated($entry);
		} else {
			// New tickets or responses by users
			$this->onMessageCreated($entry);
		}
	}
	function onResponseCreated($response)
	{
		$this->sendThreadEntryToostickettodiscord($response, 'Response', $this->getConfig()->get('ostickettodiscord-color-warning'));
	}
	function onNoteCreated($note)
	{
		$this->sendThreadEntryToostickettodiscord($note, 'Note', $this->getConfig()->get('ostickettodiscord-color-good'));
	}
	function onMessageCreated($message)
	{
		$this->sendThreadEntryToostickettodiscord($message, 'Message', $this->getConfig()->get('ostickettodiscord-color-danger'));
	}

	function sendThreadEntryToostickettodiscord($entry, $label, $color)
	{
		global $ost;
		$ticketLink = $ost->getConfig()->getUrl() . 'scp/tickets.php?id=' . $entry->getTicket()->getId();
		$title      = $entry->getTitle() ?: $label;
		$body       = $entry->getBody() ?: $entry->ht[ 'body' ] ?: 'No content';
		$this->sendToostickettodiscord(array(
			'username' => $this->getConfig()->get('ostickettodiscord-username'),
			'text' => $label . ' by ' . $entry->getPoster(),
			'attachments' => array(
				
					'title' => 'Ticket ' . $entry->getTicket()->getNumber() . ': ' . $title,
					'title_link' => $ticketLink,
					'text' => $this->escapeText($body),
					'color' => $color
				
			)
		));
	}

	/**
	 * Creation d'un nouveau ticket
	 */
	function onTicketCreated($ticket)
	{
		global $ost;
		$ticketLink = $ost->getConfig()->getUrl() . 'scp/tickets.php?id=' . $ticket->getId();
		$title      = $ticket->getSubject() ?: 'No subject';
		$body       = $ticket->getLastMessage()->getMessage() ?: 'No content';
		$this->sendToostickettodiscord(array(
			'username' => $this->getConfig()->get('ostickettodiscord-username'),
			'text' => 'New Ticket <' . $ticketLink . '> by ' . $ticket->getName() . ' (' . $ticket->getEmail() . ')',
			'attachments' => 
				array(
					'title' => 'Ticket ' . $ticket->getNumber() . ': ' . $title,
					'title_link' => $ticketLink,
					'text' => $this->escapeText($body),
					'color' => $this->getConfig()->get('ostickettodiscord-color-danger')
				)
			
		));
	}
	function sendToostickettodiscord($payload)
	{

		$discord = new DiscordWebhook($this->getConfig()->get('ostickettodiscord-webhook-url'));		

		try {
			global $ost;

			$discord->name($payload['username']);

			//$message = json_encode($payload);
			$message = "**Bonjour le chan**  :heart_eyes:  ! "."\n";
			$message .= "J'ai un ticket pour vous : ".$payload['attachments']['title']."\n";
			$message .= "a cette url : ".$payload['attachments']['title_link']."\n";
			$message .= "### Message ###\n";
			$message .= "> ".$payload['attachments']['text']."\n";

			$discord->message($message);
			$discord->tts($this->getConfig()->get('ostickettodiscord-tts'));

			$discord->send();
		}
		catch (Exception $e) {
			error_log('Error posting. ' . $e->getMessage());
		}
	}

	function escapeText($text)
	{
		$text = convert_html_to_text($text);
		if ($this->getConfig()->get('ostickettodiscord-text-escape') == true) {
			$text = str_replace('<br />', '\n', $text);
			$text = str_replace('<br/>', '\n', $text);
			$text = str_replace('&', '&amp;', $text);
			$text = str_replace('<', '&lt;', $text);
			$text = str_replace('>', '&gt;', $text);
		}
		if ($this->getConfig()->get('ostickettodiscord-text-doublenl') == true) {
			$text = preg_replace("/[\r\n]+/", "\n", $text);
			$text = preg_replace("/[\n\n]+/", "\n", $text);
		}
		$text = nl2br($text);
		$text = preg_replace('/[\n]+/', '', $text);
		if (strlen($text) >= $this->getConfig()->get('ostickettodiscord-text-length')) {
			$text = substr($text, 0, $this->getConfig()->get('ostickettodiscord-text-length')) . '...';
		}
		return $text;
	}

}

