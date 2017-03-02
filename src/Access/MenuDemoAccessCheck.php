<?php

namespace Drupal\d8_demo\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\node\NodeInterface;

class MenuDemoAccessCheck implements AccessInterface {

  protected $current_user;

  public function __construct(AccountProxy $current_user) {
    $this->current_user = $current_user;
  }

  public function access(NodeInterface $node) {
    if ($this->current_user->getAccount()->id() == $node->getOwner()->id()) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }
  }
}
