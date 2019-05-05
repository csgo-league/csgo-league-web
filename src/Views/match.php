<div class="container" style="margin-top: 20px;">
<?php
    $ct = $t = '';

    if ($row['kills'] > 0 && $row['deaths'] > 0) {
        $kdr = round(($row['kills'] / $row['deaths']), 2);
    } else {
        $kdr = 0;
    }

    if ($row['team'] == 2) {
        $t_score = $row['team_3'];
        $t_name = $row['teamname_1'] ?: 'Terrorists';

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
        $ct_name = $row['teamname_2'] ?: 'Counter-Terrorists';

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
