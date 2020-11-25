<?php 

require 'vendor/autoload.php';

/******  CONNECTION *******/
$client = new GuzzleHttp\Client();
$auth = ['auth' => ['a.radanovic1984@gmail.com', '$ifr4Fantasy']];
$url = 'https://fantasy.premierleague.com/api/';

/****** ENDPOINTS ********/
// Generic Information
$endpoint1 = 'bootstrap-static/'; 
// Fixtures
$endpoint2 = 'fixtures/';
// PL Players
$endpoint3 = 'element-summary/1';
$endpoint4 = 'event/1/live/';
// FPL Managers
$endpoint5 = 'entry/31616';
$endpoint6 = 'entry/657945/transfers/';
$endpoint7 = 'entry/{manager-id}/event/{GW}/picks/';
$endpoint8 = 'entry/{team-id}/history/';
// FPL Leagues
$endpoint9 = 'leagues-classic/514874/standings/';

/******* REQUESTS **********/
// Generic Information
$responseGeneralStatic = $client->request('GET', $url . $endpoint1);
$jsonGeneralStatic = json_decode($responseGeneralStatic->getBody()->getContents(), JSON_PRETTY_PRINT);
// mini league 
$responseMiniLeague = $client->request('GET', $url . $endpoint9);
$jsonMiniLeague = json_decode($responseMiniLeague->getBody()->getContents(), JSON_PRETTY_PRINT);

// manager stats
$responseManagerStats = $client->request('POST', $url . $endpoint5);
$jsonManagerStats = json_decode($responseManagerStats->getBody()->getContents(), JSON_PRETTY_PRINT);

// general info
$responseGeneralInfo = $client->request('GET', $url . $endpoint1);
$generalInfoStats = json_decode($responseGeneralInfo->getBody()->getContents(), JSON_PRETTY_PRINT);

$totalPlayers = ($jsonGeneralStatic['total_players']/1000000000000);
// echo "<pre>";
// print_r($jsonGeneralStatic['total_players']);
// echo "</pre>"; 

// last Game week
foreach ($generalInfoStats['events'] as $k => $v) {
    if ($v['finished'] == 1) {
        $lastGw = $v['id'];
    }
    if ($v['is_current'] == 'true') {
        $currentGw = $v['id'];
    }
}

// all players
$players = [];
foreach ($generalInfoStats['elements'] as $k => $v) {
    $players[$v['id']] = $v['first_name'] . ' ' . $v['web_name'];
}

function currentRankColor($rank, $lastRank){
    if ($rank > $lastRank) {
        echo 'style="background-color: #ffcdc9"';
    } else if ($rank < $lastRank) {
        echo 'style="background-color: #c9ffd8"';
    } else {
        echo 'style="background-color: #f5f5f5"';
    }
}

// $array = [];
// foreach ($jsonMiniLeague['standings']['results'] as $key => $rows) { 
//     $response = $client->request('POST', $url . 'entry/' . $rows['entry']);
//     $json = json_decode($response->getBody()->getContents(), JSON_PRETTY_PRINT);
//     $array['rank'] = $rows['rank'];
//     $array['teamName'] = $rows['entry_name'];
//     $array['managerName'] = $rows['player_name'];
//     $array['last_rank'] = $rows['last_rank'];
//     $array['total'] = $rows['total'];
//     $array['entry'] = $rows['entry'];
//     if ($json['id'] == $rows['entry']) {
//         $array['currentRank'] = $json['summary_overall_rank'];
//         $array['gwPoints'] = $json['summary_event_points'];
//         $array['gwRank'] = $json['summary_event_rank'];
//         $array['bank'] = $json['last_deadline_bank']/10;
//         $array['teamValue'] = $json['last_deadline_value']/10;
//         $array['totalTransfers'] = $json['last_deadline_total_transfers'];
//     }
// }

// echo "<pre>";
// print_r($array);
// echo "</pre>"; 
// die;


#541376
#9339fa