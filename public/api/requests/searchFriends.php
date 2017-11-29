<br />
<ul class="list-group">
    <?php
    require_once 'base.php';
    $friends = $session->userSearch($data);
    $n = count($friends);
    for ($i = 0; $i < $n; $i++) {
        if(!isset($friends[$i]))
            continue;
        echo "<li class=\"list-group-item text-center\">{$friends[$i]['email']}<a href=\"#\" id=\"add\" friendid=\"{$friends[$i]['userid']}\"> [REQUEST] </a></li>";
    }
    ?>
</ul>