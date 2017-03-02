<?php

namespace Drupal\d8_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id= "cache_demo",
 *   admin_label= @Translation("Cache Demo Block")
 * )
 */

class CacheDemoBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $connection;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $connection) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->connection = $connection;
  }
  /**
   * Builds and returns the renderable array for this block plugin.
   *
   * If a block should not be rendered because it has no content, then this
   * method must also ensure to return no content: it must then only return an
   * empty array, or an empty array with #cache set (with cacheability metadata
   * indicating the circumstances for it being empty).
   *
   * @return array
   *   A renderable array representing the content of the block.
   *
   * @see \Drupal\block\BlockViewBuilder
   */
  public function build() {
    $query = $this->connection->select('node_field_data', 'n')
      ->fields('n', ['title', 'nid', 'created'])
      ->range(0,3)
      ->orderBy('n.created', 'DESC');

    $result = $query->execute();

    $block_message = '';
    $cache_tags = [];

    while ($row = $result->fetchAssoc()) {
      $block_message .= $row['title'] . '|';
      $cache_tags[] = 'node:' . $row['nid'];
    }

    $email = \Drupal::currentUser()->getAccount()->getEmail();
    $block_message .= '<==========>' . $email;

    $build = [];
    $build['cache_demo_block'] = array(
      '#markup' => $block_message,
      '#cache' => array(
        'tags' => $cache_tags
      )
    );

    return $build;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

}