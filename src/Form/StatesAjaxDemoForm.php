<?php

namespace Drupal\d8_demo\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StatesAjaxDemoForm extends FormBase {

  private $states = [
    'india' => ['New Delhi', 'Mumbai', 'Bangalore'],
    'uk' => ['Wales', 'Ireland', 'Scotland']
  ];

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'states_ajax_form';
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
    $form['qualifications'] = [
      '#type' => 'select',
      '#title' => 'Select your Qualification',
      '#options' => ['ug' => 'U.G', 'pg' => 'P.G', 'other' => 'other']
    ];

    $form['other_qualification'] = [
      '#type' => 'textfield',
      '#title' => 'If others, please specify',
      '#states' => [
        'visible' => [
          ":input[name='qualifications']" => ['value' => 'other']
        ]
      ]
    ];

    $form['country'] = [
      '#type' => 'select',
      '#title' => 'Select your country',
      '#options' => ['' => '-- select --', 'india' => 'India', 'uk' => 'UK'],
      '#ajax' => [
        'callback' => '::statesCallback',
        'wrapper' => 'state-wrapper'
      ]
    ];

    $form['states_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'state-wrapper'],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'submit'
    ];

    return $form;
  }

  public function statesCallback (array &$form, FormStateInterface $form_state) {
    $country = $form_state->getValue('country');

    if (!empty($country)) {
      $form['states_wrapper']['states'] = [
        '#type' => 'select',
        '#options' => $this->states[$country]
      ];
    }

    return $form['states_wrapper'];
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
    // TODO: Implement submitForm() method.
  }
}