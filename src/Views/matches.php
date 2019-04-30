<?php
require(__DIR__ . '/../../app/config.php');

$page_number = isset($_GET['page']) ? $_GET['page'] : 0;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $page_title; ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/4.1.2/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/search-with-icon.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="container" style="margin-top:20px;">
        <?php require(__DIR__ . '/Partials/head.php'); ?>

        <form method="post">
            <div class="search-container center" style="width: 70%;">
                <input type="text" name="search-bar" placeholder="Search Match ID, Player Name or SteamID64" class="search-input">
                <button class="btn btn-light search-btn" type="submit" name="Submit"> <i class="fa fa-search"></i></button>
            </div>
        </form>

<?php
    if (isset($_POST['Submit']) && !empty($_POST['search-bar'])) {
        $search = $conn->real_escape_string($_POST['search-bar']);
        $sql = "SELECT DISTINCT sql_matches_scoretotal.match_id, sql_matches_scoretotal.map, sql_matches_scoretotal.team_2, sql_matches_scoretotal.team_3
                FROM sql_matches_scoretotal INNER JOIN sql_matches
                ON sql_matches_scoretotal.match_id = sql_matches.match_id
                WHERE sql_matches.name LIKE '%".$search."%' OR sql_matches.steamid64 = '".$search."' OR sql_matches_scoretotal.match_id = '".$search."' ORDER BY sql_matches_scoretotal.match_id DESC";
    } else if (isset($_GET['page'])) {
        $page_number = $conn->real_escape_string($_GET['page']);
        $offset = ($page_number - 1) * $limit;
        $sql = "SELECT * FROM sql_matches_scoretotal ORDER BY match_id DESC LIMIT {$offset}, {$limit}";
    } else {
        $page_number = 1;
        $sql = "SELECT * FROM sql_matches_scoretotal ORDER BY match_id DESC LIMIT {$limit}";
    }

    $result = $conn->query($sql);

    if (!!$result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $half = ($row['team_2'] + $row['team_3']) / 2;

            if ($row['team_2'] > $half) {
                $image = 'ct_icon.png';
            } elseif ($row['team_2'] == $half && $row['team_3'] == $half) {
                $image = 'tie_icon.png';
            } else {
                $image = 't_icon.png';
            }

            $map_img = array_search($row['map'], $maps);

            echo '
            <div class="match d-none" style="margin-bottom: 35px">        
                <a href="scoreboard.php?id='.$row['match_id'].'">
                    <div class="card match-card center" data-bs-hover-animate="pulse" style="margin-top:35px;"><img class="card-img w-100 d-block matches-img rounded-borders" style="background-image:url(&quot;'.$map_img.'&quot;);height:150px;">
                        <div class="row card-img-overlay">
                            <h4 class="text-white col-4" style="font-size:70px;margin-top:15px;">'.$row['team_2'].':'.$row['team_3'].'</h4>
                            <h4 class="text-white col-4 timestamp" style="text-align: center; font-size:30px;margin-top:15px;">'.$row['timestamp'].'</h4>
                            <div class="col-4">
                                <img class="float-right" src="assets/img/icons/'.$image.'" style="width:110px;">
                            </div>
                        </div>
                    </div>
                </a>
            </div>';
        }
    } else {
        echo '<h1 style="margin-top:20px;text-align:center;">No Results!</h1>';
    }

    if (!isset($_POST['Submit'])) {
        $sql_pages = 'SELECT COUNT(*) FROM sql_matches_scoretotal';
        $result_pages = $conn->query($sql_pages);
        $row_pages = $result_pages->fetch_assoc();

        $total_pages = ceil($row_pages['COUNT(*)'] / $limit);

        if ($total_pages > 1) {
            echo '
            <nav style="margin-top:30px;width:80%;" class="center">
                <ul class="pagination">';

                if ($page_number == 1) {
                    echo '
                    <li class="page-item disabled">
                        <span class="page-link">
                            Previous
                        </span>
                    </li>';
                } else {
                    $past_page = $page_number - 1;
                    echo '
                    <li class="page-item">
                        <a class="page-link" href="?page=' . $past_page . '">
                            Previous
                        </a>
                    </li>';
                }

                for ($i = max(1, $page_number - 2); $i <= min($page_number + 4, $total_pages); $i++) {
                    if ($i == $page_number) {
                        echo '
                        <li class="page-item active">
                            <span class="page-link">
                                ' . $i . '
                                <span class="sr-only">
                                    (current)
                                </span>
                            </span>
                        </li>';
                    } else {
                        echo '
                        <li class="page-item">
                            <a class="page-link" href="?page=' . $i . '">
                                ' . $i . '
                            </a>
                        </li>';
                    }
                }
                if ($page_number == $total_pages) {
                    echo '
                    <li class="page-item disabled">
                        <span class="page-link">
                            Next
                        </span>
                    </li>';
                } else {
                    $next_page = $page_number + 1;
                    echo '
                    <li class="page-item">
                        <a class="page-link" href="?page=' . $next_page . '">
                            Next
                        </a>
                    </li>';
                }
                echo '
                </ul>
            </nav>';
        }
    }
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>