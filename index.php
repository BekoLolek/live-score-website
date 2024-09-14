<?php

require_once 'config.php';
require_once 'classes/LiveScoreApi.class.php';

$isActiveLive = false;
$isActiveScheduled = false;

$nav = isset($_GET['nav']) ? $_GET['nav'] : 'live';
$competition = isset($_GET['competition']) ? $_GET['competition'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$params = [];
$params['page'] = '';
if ($competition) {
    $params['competition_id'] = $competition;
}
if ($country) {
    $params['country_id'] = $country;
}

if ($nav == 'live') {
    $isActiveLive = true;
    $isActiveScheduled = false;
} elseif ($nav == 'scheduled') {
    $isActiveLive = false;
    $isActiveScheduled = true;
    $params['page'] = $page;
}

include 'inc/header.php';
include 'php/games.php';




?>

<!-- <h1><?php echo $isActiveLive ? 'Live Scores' : 'Fixtures'; ?></h1> -->

<?php


if ($isActiveLive) {
    get_live_data($params);
} else {
    get_scheduled_data($params);
}

if($params['page']){
    $prev_page = ($page > 1 ? $page-1 : $page);
    $next_page = $page + 1;
    echo "<div class=\"pagination\">";
    echo "<a href=\"?nav={$nav}&competition={$competition}&country={$country}&page={$prev_page}\">Previous</a>";
    echo "<a href=\"?nav={$nav}&competition={$competition}&country={$country}&page={$next_page}\">Next</a>";
    echo "</div>";
}




include 'inc/footer.php';