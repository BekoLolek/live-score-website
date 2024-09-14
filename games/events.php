<?php

include 'php/match.php';

global $game_id;

$params = [];
if ($game_id) {
    $params['match_id'] = $game_id;
}

get_match_event_data($params); 