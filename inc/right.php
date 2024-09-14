<h2>Competitions</h2>
<ul>
    <?php
    global $nav, $competition, $country;
    $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $competitions = $live_score_api->get_competitions();
    echo "<li><a href=\"index.php?nav={$nav}&competition=0&country={$country}\">ALL</a></li>";
    foreach ($competitions as $_competition) {
        if(isset($_competition['countries'][0]) && $_competition['countries'][0]['id'] == $country){
            $country_or_fed_name = ($_competition['countries'] != null ? $_competition['countries'][0]['fifa_code'] : $_competition['federations'][0]['name']);
        $combined = $country_or_fed_name . " - " . $_competition['name'];
        echo "<li " . ($competition == $_competition['id'] ?  ' class="active"' :  '') . "><a href=\"index.php?nav={$nav}&competition={$_competition['id']}&country={$country}\">{$combined}</a></li>";
        }        
    }

    ?>
</ul>