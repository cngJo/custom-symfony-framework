<?php

namespace App\Listener;

use App\Event\ResponseEvent;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{

	/**
	 * @return string[]
	 */
	#[ArrayShape(["response" => "string"])] public static function getSubscribedEvents(): array
	{
		return [
			"response" => "onResponse"
		];
	}

	public function onResponse(ResponseEvent $event)
	{
		$response = $event->getResponse();

		if ($response->isRedirection()
			|| ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
			|| 'html' !== $event->getRequest()->getRequestFormat()
		) {
			return;
		}

		$response->setContent($response->getContent().'GA CODE');
	}

}
