<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FamFantasy</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<?php require 'general_functions.php' ?>
<body>
    <div class="container-fluid">
        <div class="row">
            <table class="table table-bordered" style="width: 80%; margin: auto;">
                <thead class="thead-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Team & Manager</th>
                        <th>Last Rank</th>
                        <th>Total Points</th>
                        <th>Average Points</th>
                        <th>Form (4GW)</th>
                        <th>Manager ID</th>
                        <th>Overall Rank</th>
                        <th>Overall Rank % position</th>
                        <th>GW Points</th>
                        <th>GW Rank</th>
                        <th>Team Value</th>
                        <th>Bank</th>
                        <th>Total Transfers</th>
                        <th>Chips used</th>
                        <th>Captain</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($jsonMiniLeague['standings']['results'] as $key => $rows) { 
                            // manager stats
                            $responseManagerStats = $client->request('POST', $url . "entry/{$rows['entry']}");
                            $jsonManagerStats = json_decode($responseManagerStats->getBody()->getContents(), JSON_PRETTY_PRINT);
                            // managers history
                            $responseManagerHistory = $client->request('GET', $url . "entry/{$rows['entry']}/history/");
                            $jsonManagerHistory = json_decode($responseManagerHistory->getBody()->getContents(), JSON_PRETTY_PRINT);
                            $chips = '';
                            // live gw
                            // managers history
                            $responseLiveGw = $client->request('GET', $url . "entry/{$rows['entry']}/event/{$currentGw}/picks/");
                            $jsonLiveGw = json_decode($responseLiveGw->getBody()->getContents(), JSON_PRETTY_PRINT);

                            foreach ($jsonLiveGw['picks'] as $k => $v) {
                                if ($v['is_captain'] == true) {
                                    $captain = $players[$v['element']];
                                }
                            }
                            if (empty($jsonManagerHistory['chips'])) {
                                $chips = 'none';
                            } else {
                                foreach ($jsonManagerHistory['chips'] as $k => $v) {
                                    $chips .= $v['name'] . PHP_EOL;
                                } 
                            }
                            
                            $gwPoints = 0;
                            foreach ($jsonManagerHistory['current'] as $k => $v) {
                                if ($v['event'] == $currentGw) {
                                    $gwPoints += $v['points'];
                                }
                                if ($v['event'] == $currentGw - 1) {
                                    $gwPoints += $v['points'];
                                }
                                if ($v['event'] == $currentGw -2 ) {
                                    $gwPoints += $v['points'];
                                }
                                if ($v['event'] == $currentGw - 3) {
                                    $gwPoints += $v['points'];
                                }
                                $managerForm = $gwPoints / 4;
                            }

                            if ($jsonManagerStats['id'] == $rows['entry']) {
                                $overallRank = $jsonManagerStats['summary_overall_rank'];
                                $gwPoints = $jsonManagerStats['summary_event_points'];
                                $gwRank = $jsonManagerStats['summary_event_rank'];
                                $bank = $jsonManagerStats['last_deadline_bank']/10;
                                $teamValue = $jsonManagerStats['last_deadline_value']/10;
                                $totalTransfers = $jsonManagerStats['last_deadline_total_transfers'];
                            }
                        ?>
                    <tr>
                        <td <?php currentRankColor($rows['rank'], $rows['last_rank']) ?>><?php echo $rows['rank']; ?></td>
                        <td><b  style="color: #ff2882;"><?php echo $rows['entry_name'] . '</b><br>' . $rows['player_name']; ?></td>
                        <td><?php echo $rows['last_rank']; ?></td>
                        <td><?php echo number_format($rows['total']); ?></td>
                        <td><?php echo number_format($rows['total']/$lastGw, 2, ',', ' '); ?></td>
                        <td><?php echo number_format($managerForm, 2); ?></td>
                        <td><?php echo $rows['entry']; ?></td>
                        <td><?php echo number_format($overallRank); ?></td>
                        <td><?php echo number_format($totalPlayers*$overallRank, 2); ?></td>
                        <td><?php echo $gwPoints; ?></td>
                        <td><?php echo number_format($gwRank); ?></td>
                        <td><?php echo $teamValue; ?></td>
                        <td><?php echo $bank; ?></td>
                        <td><?php echo $totalTransfers; ?></td>
                        <td><?php echo $chips; ?></td>
                        <td><?php echo $captain; ?></td>
                        <br>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>