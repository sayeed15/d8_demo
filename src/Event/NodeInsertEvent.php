<?php

namespace Drupal\d8_demo\Event;

use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\Event;

class NodeInsertEvent extends Event {
  const NODE_INSERT = 'node.insert';

  protected $node;

  /**
   * NodeInsertEvent constructor.
   * @param \Drupal\node\NodeInterface $node
   */
  public function __construct(NodeInterface $node) {
    $this->node = $node;
  }

  /**
   * @return \Drupal\node\NodeInterface
   */
  public function getNode() {
    return $this->node;
  }

}