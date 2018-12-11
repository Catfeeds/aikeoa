<?php namespace App;

use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class EventDispatcher extends BaseEventDispatcher
{
    protected function doDispatch($listeners, $eventName, Event $event)
    {
        foreach ($listeners as $listener) {
            if ($event->isPropagationStopped()) {
                break;
            }
            $event->arguments = \call_user_func($listener, $event->arguments, $eventName, $this);
        }
    }
}
