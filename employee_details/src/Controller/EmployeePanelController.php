<?php

namespace Drupal\employee_details\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class DisplayTableController
 * @package Drupal\employee_details\Controller
 */
class EmployeePanelController extends ControllerBase {

  public function index() {
    //create table header
  $form['key_2'] = [
  '#title' => $this->t('Register Employee'),
  '#type' => 'link',
  '#url' => \Drupal\Core\Url::fromRoute('employee_details.register'),
];
    $header_table = array(
      'id' => t('ID'),
      'name' => t('Employee Name'),
      'employee_id' => t('Employee ID'),
      'designation' => t('Designation'),
      'edit' => t('Edit'),
      'delete' => t('Delete'),
    );
    // get data from database
    $query = \Drupal::database()->select('employees', 'm');
    $query->fields('m', ['id', 'name', 'employee_id', 'designation']);
    $results = $query->execute()->fetchAll();
    $rows = array();
    foreach ($results as $data) {
       $url_delete = Url::fromRoute('employee_details.delete', ['id' => $data->id], []);
     
    
      $url_edit = Url::fromRoute('employee_details.update', ['id' => $data->id], []);
    
      $linkDelete = Link::fromTextAndUrl('Delete', $url_delete);
      $linkEdit = Link::fromTextAndUrl('Edit', $url_edit);
      if(isset($data->designation)){
        $designation = \Drupal\taxonomy\Entity\Term::load($data->designation)->getName();
      }
     
      //get data
      $rows[] = array(
        'id' => $data->id,
        'name' => $data->name,
        'employee_id' => $data->employee_id,
        'designation' => $designation,
        'delete' => $linkDelete,
        'edit' =>  $linkEdit,
      );

    }
    // render table
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => t('No data found'),
    ];
  
    $form['#cache']['max-age'] = 0;
  
    return $form;

  }


}