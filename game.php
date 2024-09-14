<?php

require_once 'config.php';
require_once 'classes/LiveScoreApi.class.php';

$isActiveLive = true;
$isActiveScheduled = false;

include 'inc/header.php';

$game_id = isset($_GET['id']) ? $_GET['id'] : '0';
$view = isset($_GET['view']) ? $_GET['view'] : 'lineup';

?>
<nav class="game-nav">
            <ul>
                <li><a href="game.php?id=<?php echo $game_id; ?>&view=lineup" <?php echo $view == 'lineup' ? 'class="active"' : ''; ?>>Lineup</a></li>
                <li><a href="game.php?id=<?php echo $game_id; ?>&view=stats" <?php echo $view == 'stats' ? 'class="active"' : ''; ?>>Stats</a></li>
                <li><a href="game.php?id=<?php echo $game_id; ?>&view=commentary" <?php echo $view == 'commentary' ? 'class="active"' : ''; ?>>Commentary</a></li>
                <li><a href="game.php?id=<?php echo $game_id; ?>&view=events" <?php echo $view == 'events' ? 'class="active"' : ''; ?>>Events</a></li>
            </ul>
</nav>
<div class="lineup-container">
        
<?php

switch ($view) {
    case 'lineup':
        include 'games/lineups.php';
        break;
    case 'stats':
        include 'games/stats.php';
        break;
    case 'commentary':
        include 'games/commentary.php';
        break;
    case 'events':
        include 'games/events.php';
        break;
    default:
        include 'games/lineups.php';
}
?>
</div>

<?php


include 'inc/footer.php';