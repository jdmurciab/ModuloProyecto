<?php

/**
 * @file
 * contains \Drupal\playlist_block\Form;
 */

namespace Drupal\playlist_block\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class PlaylistForm extends FormBase {

  /**
	 * {@inheritdoc}
	 */

	public function getFormId() {
    return 'playlist_form';
  }

  /**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['nodes_series'] = [
			'#type' => 'fieldset',
			'#title' => $this->t('Favourties'),
		];

    $form['nodes_series']['field_nodes'] = [
       '#type' => 'entity_autocomplete',
       '#target_type' => 'node',
       '#selection_settings' => [
        'target_bundles' => array('Series', 'Movie'),
       ],
       '#placeholder' => ('Escribe la serie a añadir'),
     ];

		 $form['nodes_series']['actions'] = [
 			'#type' => 'submit',
 			'#value' => $this->t('Save'),
 		];

    /**
     * @RenderElement("link");
     */

    $form['link_favourites'] = [
      '#type' => 'link',
      '#title' => $this->t('Vamos a tu lista de favoritos'),
      '#url' => \Drupal\Core\Url::fromRoute('playlist_block.favourites_page'),
    ];
 
		return $form;

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {


    $connection = \Drupal::database();

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

    $uid = $user->get('uid')->value;
    $nid = $form_state->getValue('field_nodes');


    $query = $connection->query('SELECT * FROM list_favourites WHERE nid = :nid AND uid = :uid' ,
    ['nid' => $nid , 'uid' => $uid]);

    $verifyQ = $query->fetchAssoc();

    if ($verifyQ['nid'] == $nid && $verifyQ['uid'] == $uid) {

      drupal_set_message(t('Ya esta en la lista!'));

    } else {

      $result = $connection->insert('list_favourites')->fields([
      'id' => NULL,
      'uid' => $uid,
      'nid' => $nid,

      ])->execute();

      if ($result) {
        drupal_set_message(t('Añadida a la lista!'));
      } else {
        drupal_set_message(t('Intentalo mas tarde'));
      }
      
    }

	}
}
