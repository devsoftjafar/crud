<?php

namespace Drupal\employee_details\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\taxonomy\Entity\Term;

class EmployeeRegisterForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'employee_details';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => true,
      '#size' => 60,
      '#default_value' => ' ',
      '#maxlength' => 128,
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];
    $form['employee_id'] = [
      '#type' => 'number',
      '#title' => $this->t('Employee Id'),
      '#required' => true,
      '#size' => 6,
      '#default_value' => ' ',
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];
    $form['designation'] = [
      '#type' => 'select',
      '#title' => $this->t('Designation'),
      '#required' => true,
      '#options' => $this->getDesignationList(),
      '#default_value' => ' select',
      '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12']
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('save'),
      '#buttom_type' => 'primary'
    ];
    return $form;
  }
  /**
   * Implements to get country list and save in cache.
   */
  public function getDesignationList() {
    $output = [];
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree("Designation");
    foreach ($terms as $data) {
        $output[$data->tid] = $data->name;  
      }   
    return $output;
  }
  /**
   * @param array $form
   * @param FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (is_numeric($form_state->getValue('name'))) {
      $form_state->setErrorByName('name', $this->t('Error, The Name Must Be A String'));
    }
    if (strlen($form_state->getValue('employee_id')) < 6 || strlen($form_state->getValue('employee_id')) > 6) {
      $form_state->setErrorByName('employee_id', $this->t('Employee Id Should be only 6 digit.'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {    
    $data = array(
      'name' => $form_state->getValue('name'),
      'employee_id' => $form_state->getValue('employee_id'),
      'designation' => $form_state->getValue('designation'),
    );
    // insert data to database.
     \Drupal::database()->insert('employees')->fields($data)->execute();
     // show message and redirect to employee details page.
    \Drupal::messenger()->addStatus('Records Succesfully saved');
    $url = new Url('employee_details.employeepanel');
    $response = new RedirectResponse($url->toString());
    $response->send();
  }

}