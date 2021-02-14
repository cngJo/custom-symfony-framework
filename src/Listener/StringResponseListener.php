<?php


namespace App\Listener;


use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class StringResponseListener implements EventSubscriberInterface
{
	/**
	 * @return string[]
	 */
	#[ArrayShape(["kernel.view" => "string"])] public static function getSubscribedEvents(): array
	{

		return [
			"kernel.view" => "onView"
		];
	}

	public function onView(ViewEvent $event)
	{
		$response = $event->getControllerResult();

		if (is_string($response)) {
			$event->setResponse(new Response($response));
		}
	}
}
