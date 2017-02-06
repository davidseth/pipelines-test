<?php

/**
 * @file
 * Contains \Drupal\dfs_obio_menu\Plugin\Derivative\DFSOBIOMenuLinkDerivative.
 */

namespace Drupal\dfs_obio_menu\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\node\Entity\Node;

class DFSOBIOMenuLinkDerivative extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = array();

    // Get all nodes of type product
    $nodeQuery = \Drupal::entityQuery('node');
    $group = $nodeQuery->orConditionGroup()
      ->condition('type', 'product');
    $nodeQuery->condition($group)
      ->condition('status', TRUE);
    $ids = $nodeQuery->execute();
    $ids = array_values($ids);

    // Map node bundles to related menu links.
    $parent_map = [
      'product' => 'dfs_obio.shop_office',
    ];

   	$title_blacklist = [
      ''
    ];

    $nodes = Node::loadMultiple($ids);

    /** @var \Drupal\node\Entity\Node $node */
    foreach($nodes as $node) {
      $title = $node->get('title');
      if (!in_array($title->getString(), $title_blacklist)) {
        $links['dfs_obio_menu_menulink_' . $node->id()] = [
            'title' => $title->getString(),
            'menu_name' => 'main',
            'parent' => $parent_map[$node->bundle()],
            'route_name' => 'entity.node.canonical',
            'route_parameters' => [
              'node' => $node->id(),
            ],
          ] + $base_plugin_definition;
      }
    }

    return $links;
  }
}
