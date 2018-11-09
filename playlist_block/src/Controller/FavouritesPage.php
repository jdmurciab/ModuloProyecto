<?php

namespace Drupal\playlist_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\Core\Url;
use Drupal\Core\Link;


/**
 * Provides route response from playlist_block module.
 */

class FavouritesPage extends ControllerBase {

	/**
	 * Returns a page with a query of all series related to favourites series of current user.
	 *
	 * @return array
	 *	a simple renderable array.
	 */

	public function PageController() {

		$connection = \Drupal::database();

		$user = User::load(\Drupal::currentUser()->id());

    $uid = $user->get('uid')->value;

    	/**
    	* Get the ID's of nodes.
    	*/

		$nids = [];

		$result = $connection->query("SELECT * FROM list_favourites WHERE uid = :uid" , array('uid' => $uid));

		while ($nodes = $result->fetchAssoc()) {
			$nids[] = $nodes['nid'];
			//$delete = Url::fromUserInput('/playlist_block/Form/delete/' . $nodes['nid']);
		}

		$node_type = 'node';
		$entities = \Drupal::entityTypeManager()->getStorage($node_type)->loadMultiple($nids);


		$output = array();

		foreach ($entities as $entity) {

			$delete = Url::fromUserInput('/user/favourites/' . $entity->id() . '/delete');

			$output[]=array(
				'title'=> Link::fromTextAndUrl($entity->getTitle(), $entity->toUrl()),
				'link' => Link::fromTextAndUrl('Remove', $delete),

			);

		}
		$header=array(
			'title' => t('<h3>' . 'Titulo' . '</h3>'),
			'link' => t('<h3>' . 'Remove?' . '</h3>'),
		);

		return array(
			'#type' => 'table',
			'#header' => $header,
			'#rows' => $output,
			//'#cache'=> ['max-age' = 0],
		);

	}

}
