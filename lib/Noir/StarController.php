<?php

/**
 * Created by PhpStorm.
 * User: Liam
 * Date: 4/20/20
 * Time: 7:58 PM
 */


namespace Noir;


class StarController extends Controller {
    /**
     * StarController constructor.
     * @param Site $site Site object
     * @param $user User object
     * @param array $post $_POST
     */
    public function __construct(Site $site, $user, $post) {
        parent::__construct($site);


        $movie = strip_tags($post['id']);
        $rating = strip_tags($post['rating']);

        $movies = new Movies($site);

        $res = $movies->updateRating($user, $movie, $rating);

        $view = new HomeView($site, $user);
        $html = $view->presentTable();

        if($res){
            $this->result = json_encode(['ok' => true, 'html' => $html]);
        }
        else{
            $this->result = json_encode(['ok' => false, 'message' => 'Failed to update database!']);
        }
    }

}