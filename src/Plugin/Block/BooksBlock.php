<?php

namespace Drupal\d8_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use AntoineAugusti\Books\Fetcher;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BooksBlock
 * @package Drupal\d8_demo\Plugin\Block
 *
 * @Block(
 *   id = "books",
 *   admin_label = @Translation("Book Block")
 * )
 */

class BooksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $http_client;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Client $http_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->http_client = $http_client;
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
    $client = new Client(['base_uri' => 'https://www.googleapis.com/books/v1/']);
    $fetcher = new Fetcher($client);
    $book = $fetcher->forISBN($this->configuration['isbn']);

    return [
      '#theme' => 'item_list',
      '#items' => [
        $book->title,
        $book->subtitle,
        $book->authors[0],
        $book->pageCount
      ]
    ];
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
      $container->get('http_client')
    );
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['isbn'] = [
      '#type' => 'textfield',
      '#title' => 'Book ISBN',
      '#default_value' => $this->configuration['isbn']
    ];

    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['isbn'] = $form_state->getValue('isbn');
  }
}