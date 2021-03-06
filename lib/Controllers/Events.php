<?php
/**
 * Controller for EM-Events-Plugin to list all Events.
 * 
 * @since 1.0.0
 */

namespace Contexis\Controllers;

use Contexis\Wordpress\Breadcrumbs;

class Events extends \Contexis\Core\Controller {

    
    public function __construct($site, $template = false) {
        parent::__construct($site);
        $this->addToContext([
            'events' => $this->getEvents(),
            'terms' => $this->getTerms(),
            'slug' => get_option("dbem_cp_events_slug"),
            "breadcrumbs" => Breadcrumbs::generate(),
        ]);
        $this->setTemplate('pages/events.twig');
    }

    private function getEvents() {
        // Event Manager has it's own Class, but it doesn't return a thumbnail-ID :-(
        // if (class_exists('EM_Events')) {
        //     return \EM_Events::get();
        // }
        // return [];
        return new \Timber\PostQuery([
            'post_type' => 'event',
            'orderby' => '_event_start_date',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                  'key' => '_event_start_date',
                  'value' => date('Y-m-d'),
                  'compare' => '>=',
                )
              )
        ]);
    }

    private function getTerms() {
        return get_terms( array(
            'taxonomy' => 'event-categories',
            'hide_empty' => true,
        ) );
    }

}