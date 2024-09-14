
<aside class="sidebar left">
    <h2>Countries</h2>
    <ul>
        <?php
            global $nav, $competition, $country;
            $live_score_api = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $countries = $live_score_api->get_countries();
            echo "<li><a href=\"index.php?nav={$nav}&competition={$competition}&country=0\">ALL</a></li>";
            foreach ($countries as $_country) {
                $url = "index.php?nav={$nav}&competition={$competition}&country={$_country['id']}";
                echo "<li" . ($country == $_country['id'] ?  ' class="active"' :  '') . "><a href=\"{$url}\">{$_country['name']}</a></li>";
            }

        ?>
    </ul>
</aside>
