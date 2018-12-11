<?php

use Symfony\Component\EventDispatcher\Event;

class Hook
{
    public static function listen($tag, $class)
    {
        $object = with(new $class);
        foreach ($object->listens as $action) {
            app('dispatcher')->addListener($tag.'.'.$action[0], [$class, $action[0]]);
        }
        unset($object);
    }

    public static function fire($tag, $data = array())
    {
        $event = new Event();
        $event->arguments = $data;
        $event = app('dispatcher')->dispatch($tag, $event);
        return $event->arguments;
    }
}
