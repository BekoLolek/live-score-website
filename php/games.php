<?php



function get_live_data($params){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $scores = $live_score_api->get_live_scores($params); #live_scores is data['match']
    display_data_live($scores);
}

function get_scheduled_data($params){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $scores = $live_score_api->get_scheduled_games($params);
    display_data_scheduled($scores);
}

function get_game_by_id($id){
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $params = ["competition_id"=> $id];
    $scores = $live_score_api->get_scheduled_games($params);
    display_data_scheduled($scores);
}


function display_data_live($scores){
    if ($scores == null) {
        echo "No live matches right now.";
    } else {
        // Sort matches by competition ID
        usort($scores, function ($a, $b) {
            return $a['competition_id'] - $b['competition_id'];
        });
    
        // Display matches
        $country_or_fed = ($scores[0]['country'] != null ? $scores[0]['country']['name'] : $scores[0]['federation']['name']);
        $current_competition = $scores[0]['competition_id'];        
        echo "<div class=\"competitions\">";#every competition
        echo "<div class=\"competition\">"; #current competition
        echo "<h2>{$country_or_fed}</h2>";
        echo "<div class=\"games\">"; #games in the competition
    
    
        foreach ($scores as $_match) {
    
            if ($_match['country'] == null) {
                $country_or_fed = $_match['federation']['name'];
            } else {
                $country_or_fed = $_match['country']['name'];
            }
    
            if ($current_competition == $_match['competition_id']) {
                echo "<a class=\"game-link\" href=\"game.php?id={$_match['id']}\">
                    <div class=\"game\">
                    <div class=\"time\">{$_match['time']}</div>
                    <div class=\"teams\">
                        <div class=\"home-team\">{$_match['home_name']}</div>
                        <div class=\"away-team\">{$_match['away_name']}</div>
                    </div>
                    <div class=\"score\">{$_match['score']}</div>
                    </div>
                    </a>";
    
            } else {
                $current_competition = $_match['competition_id'];
                echo "</div>";
                echo "</div>";
                echo "<div class=\"competition\">";
                echo "<h2>{$country_or_fed}</h2>";
                echo "<div class=\"games\">";
                echo "<a class=\"game-link\" href=\"game.php?id={$_match['id']}\">
                    <div class=\"game\">
                    <div class=\"time\">{$_match['time']}</div>
                    <div class=\"teams\">
                        <div class=\"home-team\">{$_match['home_name']}</div>
                        <div class=\"away-team\">{$_match['away_name']}</div>
                    </div>
                    <div class=\"score\">{$_match['score']}</div>
                    </div>
                    </a>";
    
    
            }
    
        }
        echo "</div>"; # end games
        echo "</div>"; # end current competition
        echo "</div>"; # end competitions
    
    }
}

function display_data_scheduled($scores){
    if ($scores == null) {
        echo "No fixtures";
    } else {
        // Sort matches by competition ID
        usort($scores, function ($a, $b) {
            return $a['competition_id'] - $b['competition_id'];
        });
    
        // Display matches
        $country_or_fed = ($scores[0]['league']['name'] != null ? $scores[0]['league']['name'] : $scores[0]['competition']['name']);
        $current_competition = $scores[0]['competition_id'];        
        echo "<div class=\"competitions\">";#every competition
        echo "<div class=\"competition\">"; #current competition
        echo "<h2>{$country_or_fed}</h2>";
        echo "<div class=\"games\">"; #games in the competition
    
    
        foreach ($scores as $_match) {
    
            if ($_match['league']['name'] == null) {
                $country_or_fed = $_match['competition']['name'];
            } else {
                $country_or_fed = $_match['league']['name'];
            }
    
            if ($current_competition == $_match['competition_id']) {
                echo "<a class=\"game-link\">
                    <div class=\"game\">
                    <div class=\"time\">{$_match['time']}</div>
                    <div class=\"teams\">
                        <div class=\"home-team\">{$_match['home_name']}</div>
                        <div class=\"away-team\">{$_match['away_name']}</div>
                    </div>
                    
                    </div>
                    </a>";
    
            } else {
                $current_competition = $_match['competition_id'];
                echo "</div>";
                echo "</div>";
                echo "<div class=\"competition\">";
                echo "<h2>{$country_or_fed}</h2>";
                echo "<div class=\"games\">";
                echo "<a class=\"game-link\">
                    <div class=\"game\">
                    <div class=\"time\">{$_match['time']}</div>
                    <div class=\"teams\">
                        <div class=\"home-team\">{$_match['home_name']}</div>
                        <div class=\"away-team\">{$_match['away_name']}</div>
                    </div>
                    
                    </div>
                    </a>";
    
    
            }
    
        }
        echo "</div>"; # end games
        echo "</div>"; # end current competition
        echo "</div>"; # end competitions
    
    }
}

