<?php

namespace Drupal\employee_details\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class DeleteForm
 * @package Drupal\employee_details\Form
 */
class EmployeeDeleteForm extends ConfirmFormBase {

  public $id;
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'employee_delete_form';
  }
  public function getQuestion() {
    return t('Delete data');
  }
  public function getCancelUrl() {
    return new Url('employee_details.employeepanel');
  }

  public function getDescription() {
    return t('Do you want to delete data number %id ?', array('%id' => $this->id));
  }
  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete it!');
  }
  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {

    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('employees')->condition('id', $this->id)
      ->execute();
    \Drupal::messenger()->addStatus('Succesfully deleted.');
    $form_state->setRedirect('employee_details.employeepanel');
    
  }
}