<?php

namespace Drupal\playlist_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Favourites Block' Block.
 *
 * @Block(
 * 	 id = "playlist_block",
 * 	 admin_label = @Translation("Favourites Block"),
 * 	 category = @Translation("Custom Block"),
 *  )
 */

class PlaylistBlock extends BlockBase {

	/*
	 *  {@inheritdoc}
	 */
	public function build(){

		$form = \Drupal::formBuilder()->getForm('Drupal\playlist_block\Form\PlaylistForm');
		return $form;

	}
}
