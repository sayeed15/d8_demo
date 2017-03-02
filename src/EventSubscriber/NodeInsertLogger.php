<?php

namespace Drupal\d8_demo\EventSubscriber;

use \Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Drupal\d8_demo\Event\NodeInsertEvent;
use \Drupal\node\NodeInterface;
use Drupal\Core\Logger\LoggerChannelFactory;

class NodeInsertLogger implements EventSubscriberInterface {
  protected $logger;

  public function __construct(LoggerChannelFactory $logger) {
    $this->logger = $logger;
  }

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
    $events[NodeInsertEvent::NODE_INSERT][] = array('logNodeInsert');

    return $events;
  }

  public function logNodeInsert(NodeInsertEvent $event) {
    $this->logger->get('d8_demo')->info('Node inserted with node id: ' . $event->getNode()->id());
  }
}