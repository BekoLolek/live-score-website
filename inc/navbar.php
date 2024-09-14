<?php
    global $nav, $country, $competition;
?>


<nav>
    <ul>
        <li <?php echo $isActiveLive ? 'class="active"' : ''; ?>><a  href="index.php?nav=live&competition=<?php echo $competition ?>&country=<?php echo $country ?>">Live</a></li>
        <li <?php echo $isActiveScheduled ? 'class="active"' : ''; ?>><a  href="index.php?nav=scheduled&competition=<?php echo $competition ?>&country=<?php echo $country ?>">Scheduled</a></li>
    </ul>
</nav>
