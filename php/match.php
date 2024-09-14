<?php

function get_match_commentary_data($params){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $data = $live_score_api->get_game_commentary($params); 
    display_data_match_commentary($data);
}

function get_match_event_data($params){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $data = $live_score_api->get_game_events($params);
    display_data_match_event($data);
}

function get_match_event_stats_data($params){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $data = $live_score_api->get_game_stats($params);
    display_data_match_stats($data);   
}

function get_match_event_lineups_data($params){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $data = $live_score_api->get_game_lineups($params);
    display_data_match_lineups($data);
}



function display_data_match_commentary($data){
    if($data != null){
        echo "<div class=\"commentary-container\">";
        echo "<table>";
        foreach ($data as $_comment) {
            echo "<tr><td>{$_comment['minute']} {$_comment['text']}</td></tr>";
        }
        echo "</table>";
        echo "</div>";
    }
    
}

function display_data_match_event($data){
    echo "<div class=\"event-container\">";
    echo "<table>";
    if($data != null){
        foreach ($data as $_event) {
            echo "<tr><td>{$_event['time']} - {$_event['player']} - {$_event['event']}</td></tr>";
        }
    }
    
    echo "</table>";
    echo "</div>";
}

function display_data_match_stats($data){
    echo "<div class=\"stats-container\">";
    foreach ($data as $key => $value) {
        $values = explode(':', $value);
        if (count($values) == 2) {
            $home = $values[0];
            $away = $values[1];
        } else {
            $home = 'N/A';
            $away = 'N/A';
        }
        $formatted_key = ucwords(str_replace('_', ' ', $key));
        echo "<div class=\"stat-card\">
                <div class=\"stat-title\">{$formatted_key}</div>
                <div class=\"stat-values\">
                    <span class=\"home-value\">{$home}</span>
                    <span class=\"away-value\">{$away}</span>
                </div>
              </div>";
    }
    echo "</div>";
}

function display_data_match_lineups($data){
    echo "<div class=\"lineup\">";
    echo "<table id=\"lineup-table\">";
    echo "<thead><tr><th colspan=\"2\">{$data['home']['team']['name']}</th><th colspan=\"2\">{$data['away']['team']['name']}</th></tr></thead>";
    echo "<tbody>";

    $count = max(count($data['home']['players']), count($data['away']['players']));

    for ($i = 0; $i < $count; $i++) {
        $home_player_shirt = '';
        $away_player_shirt = '';
        $home_player_name = '';
        $away_player_name = '';

        if (isset($data['home']['players'][$i])) {
            $home_player_shirt = $data['home']['players'][$i]['shirt_number'];
            $home_player_name = $data['home']['players'][$i]['name'];
        }
        if (isset($data['away']['players'][$i])) {
            $away_player_shirt = $data['away']['players'][$i]['shirt_number'];
            $away_player_name = $data['away']['players'][$i]['name'];
        }

        echo "<tr><td>{$home_player_shirt}</td><td>{$home_player_name}</td><td>{$away_player_shirt}</td><td>{$away_player_name}</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";



}