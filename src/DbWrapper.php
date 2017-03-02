<?php

namespace Drupal\d8_demo;

use Drupal\Core\Database\Driver\mysql\Connection;

class DbWrapper {

  /**
   * DbWrapper constructor.
   * @param \Drupal\Core\Database\Driver\mysql\Connection $connection
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function insert($first_name, $last_name) {
    $this->connection->insert('d8_demo')
      ->fields([
        'first_name' => $first_name,
        'last_name' => $last_name
      ])
      ->execute();
  }

  public function fetch($field_name) {
    return $this->connection->select('d8_demo', 'ddc')
      ->fields('ddc', [$field_name])
      ->range(0, 1)
      ->execute()->fetchField();
  }
}