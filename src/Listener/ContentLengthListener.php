<?php


namespace App\Listener;


use App\Event\ResponseEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthListener implements EventSubscriberInterface
{

	/**
	 * @return string[]
	 */
	#[ArrayShape(["response" => "string"])] public static function getSubscribedEvents(): array
	{
		return [
			"response" => ["onResponse", -255]
		];
	}

	public function onResponse(ResponseEvent $event)
	{
		$response = $event->getResponse();
		$headers = $response->headers;

		if (!$headers->has("Content-Length") && !$headers->has("Transfer-Encoding")) {
			$headers->set("Content-Length", strlen($response->getContent()));
		}
	}
}
