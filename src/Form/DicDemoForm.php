<?php

namespace Drupal\d8_demo\Form;

use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\d8_demo\DbWrapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DicDemoForm extends FormBase {
  protected $wrapper;

  /**
   * DicDemoForm constructor.
   * @param \Drupal\d8_demo\DbWrapper $wrapper
   */
  public function __construct(DbWrapper $wrapper) {
    $this->wrapper = $wrapper;
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'dic_demo_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => 'Enter Name',
      '#default_value' => $this->wrapper->fetch('first_name')
    ];

    $form['surname'] = [
      '#type' => 'textfield',
      '#title' => 'Enter Last Name',
      '#default_value' => $this->wrapper->fetch('last_name')
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'submit'
    ];
    
    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $first_name = $form_state->getValue('name');
    $last_name = $form_state->getValue('surname');
    $this->wrapper->insert($first_name, $last_name);
    drupal_set_message('Data Captured successfully!', 'status');
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @return array
   */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('d8_demo.db_wrapper')
    );
  }


}