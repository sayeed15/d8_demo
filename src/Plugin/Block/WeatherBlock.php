<?php

namespace Drupal\d8_demo\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Weather data' block.
 *
 * @Block(
 *   id = "weather_d8",
 *   admin_label = @Translation("Weather"),
 * )
 */

class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $http_client;
  protected $config;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Client $http_client, ConfigFactory $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->http_client = $http_client;
    $this->config = $config;
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
    $city = $this->configuration['city'];
    $appid = $this->config->get('d8_demo.weather_config')->get('appid');
    $res = $this->http_client->get('http://api.openweathermap.org/data/2.5/weather', ['query' => ['q' => $city, 'appid' => $appid]]);
    $weatherDataJson = $res->getBody()->getContents();
    $weatherDataArray = Json::decode($weatherDataJson);

    $build['weather_block_city_name'] = array(
      '#theme' => 'weather_widget',
      '#weather_data' => $weatherDataArray,
      '#attached' => [
        'library' => ['d8_demo/d8_demo.weather_widget']
      ]
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
      $container->get('http_client'),
      $container->get('config.factory')
    );
  }

  public function blockForm($form, FormStateInterface $form_state) {

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => 'City Name',
      '#default_value' => $this->configuration['city']
    ];

    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['city'] = $form_state->getValue('city');
  }
}