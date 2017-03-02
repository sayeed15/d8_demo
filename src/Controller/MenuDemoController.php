<?php

namespace Drupal\d8_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

class MenuDemoController extends ControllerBase {
  public function staticContent() {
    return [
      '#markup' => 'Hello world!'
    ];
  }
  
  public function argContent($arg) {
    return [
      '#markup' => 'The argument form url is ' . $arg
    ];
  }

  public function entityArgContent(NodeInterface $node) {
    return [
      '#theme' => 'item_list',
      '#items' => [
        $node->getTitle(),
        $node->get('body')->getValue()
      ]
    ];
  }

  public function entityMultipleArgContent(NodeInterface $node1, NodeInterface $node2) {
    return [
      '#theme' => 'item_list',
      '#items' => [
        $node1->getTitle(),
        $node2->getTitle()
      ]
    ];
  }
}