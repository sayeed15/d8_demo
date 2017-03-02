<?php
namespace Drupal\d8_demo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsSubscriber implements EventSubscriberInterface {

  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * The array keys are event names and the value can be:
   *
   *  * The method name to call (priority defaults to 0)
   *  * An array composed of the method name to call and the priority
   *  * An array of arrays composed of the method names to call and respective
   *    priorities, or 0 if unset
   *
   * For instance:
   *
   *  * array('eventName' => 'methodName')
   *  * array('eventName' => array('methodName', $priority))
   *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
   *
   * @return array The event names to listen to
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = array('addCorsHeaders');
    return $events;
  }

  /**
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   */
  public function addCorsHeaders(FilterResponseEvent $event) {
    $response = $event->getResponse();
    $response->headers->add(['Access-Control-Allow-Origin' => '*']);
  }
}