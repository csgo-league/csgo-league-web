<?php
require(__DIR__ . '/../app/config.php');

$match_id = isset($_GET['id']) ? $_GET['id'] : 0;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $page_title; ?></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/4.1.2/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/search-with-icon.css?h=407fbd3e4331a9634a54008fed5b49b9">
    <link rel="stylesheet" href="assets/css/styles.css?h=a95bd1c65d4dfacc3eae1239db3fae0b">
</head>

<body>
    <div class="container" style="margin-top: 20px;">
<?php
        require ('head.php');

        $match_id = $conn->real_escape_string($match_id);

        $sql = "SELECT sql_matches_scoretotal.*, sql_matches.*
                FROM sql_matches_scoretotal INNER JOIN sql_matches
                ON sql_matches_scoretotal.match_id = sql_matches.match_id
                WHERE sql_matches_scoretotal.match_id = '".$match_id."' ORDER BY sql_matches.score DESC";
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $ct = $t = null;
            while ($row = $result->fetch_assoc()) {
                if ($row['kills'] > 0 && $row['deaths'] > 0) {
                    $kdr = round(($row['kills'] / $row['deaths']), 2);
                } else {
                    $kdr = 0;
                }

                if ($row['team'] == 2) {
                    $t_score = $row['team_3'];
                    $t_name = $row['teamname_1'];

                    if ($t_name == null) {
                        $t_name = 'Terrorists';
                    }

                    $t .= '
                    <tr>
                        <td><a href="https://steamcommunity.com/profiles/'.$row['steamid64'].'" class="text-white" target="_blank">'.htmlspecialchars(substr($row['name'],0,12)).'</a></td>
                        <td>'.$row['kills'].'</td>
                        <td>'.$row['assists'].'</td>
                        <td>'.$row['deaths'].'</td>
                        <td>'.$kdr.'</td>
                        <td>'.$row['mvps'].'</td>
                        <td>'.$row['score'].'</td>
                        <td>'.$row['ping'].'</td>
                    </tr>';
                } elseif ($row['team'] == 3) {
                    $ct_score = $row['team_2'];
                    $ct_name = $row['teamname_2'];

                    if ($ct_name == null) {
                        $ct_name = 'Counter-Terrorists';
                    }

                    $ct .= '
                    <tr>
                        <td><a href="https://steamcommunity.com/profiles/'.$row['steamid64'].'" class="text-white" target="_blank">'.htmlspecialchars(substr($row['name'],0,32)).'</a></td>
                        <td>'.$row['kills'].'</td>
                        <td>'.$row['assists'].'</td>
                        <td>'.$row['deaths'].'</td>
                        <td>'.$kdr.'</td>
                        <td>'.$row['mvps'].'</td>
                        <td>'.$row['score'].'</td>
                        <td>'.$row['ping'].'</td>
                    </tr>';
                }
            }

            if (!isset($ct)) {
                $ct = '<h3 style="margin-top:20px; text-align:center;">No Players Recorded!</h3>';
            }

            if (!isset($t)) {
                $t = '<h3 style="margin-top:20px; text-align:center;">No Players Recorded!</h3>';
            }

            echo '
            <div class="row" style="margin-top:20px;">
                <div class="col-md-12">
                    <div class="card rounded-borders" style="border: none !important;">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive" style="border: none !important;">
                                <table class="table">
                                    <thead class="table-borderless" style="border: none !important;">
                                        <tr>
                                            <th colspan="8" style="
                                                font-size: 30px;
                                                text-align: center;
                                                background-color: #5b768d;
                                                height:25px;">
                                                ' .$ct_name.'
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width:200px;">Player</th>
                                            <th>Kills</th>
                                            <th>Assists</th>
                                            <th>Deaths</th>
                                            <th>KDR</th>
                                            <th>MVPs</th>
                                            <th>Score</th>
                                            <th>Ping</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    '.$ct.'
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center" style="margin-top:10px;margin-bottom:10px;"><span style="color:#5b768d;">'.$ct_score.'</span> : <span style="color:#ac9b66;">'.$t_score. '</span></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card rounded-borders" style="border: none !important;">
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive" style="border-top:none !important;">
                                <table class="table">
                                    <thead class="table-borderless" style="border: none !important;">
                                        <tr>
                                            <th colspan="8" style="
                                                font-size: 30px;
                                                text-align: center;
                                                background-color: #ac9b66;
                                                height:25px;">
                                                ' .$t_name.'
                                            </th>
                                        </tr>
                                        <tr>
                                            <th style="width:200px;">Player</th>
                                            <th>Kills</th>
                                            <th>Assists</th>
                                            <th>Deaths</th>
                                            <th>KDR</th>
                                            <th>MVPs</th>
                                            <th>Score</th>
                                            <th>Ping</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    '.$t.'
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    } else {
        echo '<h4 style="margin-top:40px;text-align:center;">No Match with that ID!</h4>';
    }
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bs-animation.js?h=98fdbbd86223499341d76166d015c405"></script>
</body>

</html>
