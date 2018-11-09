<?php

namespace Drupal\playlist_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Drupal\user\Entity\User;
/**
 * Class DeleteForm.
 *
 * @package Drupal\mydata\Form
 */
class DeleteForm extends ConfirmFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'delete_form';
  }

  //public $nid;

  public function getQuestion() { 
    return t('Do you want to remove?');
  }

  public function getCancelUrl() {
    return new Url('playlist_block.favourites_page');
}
public function getDescription() {

    $entity = \Drupal::entityTypeManager()->getStorage('node')->load($this->nid);

    return t('<h3> Do you want to remove %nid? </h3>', array('%nid' => $entity->getTitle()));
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Remove it!');
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
  public function buildForm(array $form, FormStateInterface $form_state, $nid = NULL) {

     $this->nid = $nid;
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

    $user = User::load(\Drupal::currentUser()->id());

    $uid = $user->get('uid')->value;


   $query = \Drupal::database();
     
    $query->delete('List_favourite')
       
          ->condition('nid',$this->nid)
          ->condition('uid', $uid)
        ->execute();
        
             drupal_set_message("succesfully deleted");

    $form_state->setRedirect('playlist_block.favourites_page');
  }

}
