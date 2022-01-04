<?php
chdir('./generate_content');
require_once('./vendor/autoload.php');

$fetchImages = true;
$downloadImages = false;

function downloadAndCDNImage($remoteImage, $folderName, $fileName) {
    //fetch image from remote url (parameter)
    if (true) {
        $retryCount = 0;
        $downloadSuccess = false;
        $remoteImageRaw = '';
        while (!$remoteImageRaw) {
            if ($retryCount < 10) {
                usleep(250);
                $remoteImageRaw = @file_get_contents($remoteImage);
                if ($remoteImageRaw) {
                    $downloadSuccess = true;
                }
                $retryCount++;
            }
        }

        if (!$downloadSuccess) {
            return false;
        }

        if (!is_dir('./../static/icon')) mkdir('./../icon');
        if (!is_dir('./../static' . $folderName)) mkdir('./../static' . $folderName);
        file_put_contents('./../static' . $folderName . '/' . $fileName, $remoteImageRaw);

    }
    return 'https://ik.imagekit.io/flyffdb' . $folderName . '/' . $fileName;
}

function downloadFromApi($endpointUrl) {
    if (!is_dir('./../apicache'))
        mkdir('./../apicache');
    if (file_exists('./../apicache/' .  md5($endpointUrl))) {
        $content = file_get_contents('./../apicache/' . md5($endpointUrl));
    } else {
        $baseApiUrl = 'https://flyff-api.sniegu.fr';
        $retryCount = 0;
        $downloadSuccess = false;
        $content = '';
        while (!$content) {
            if ($retryCount < 15) {
                usleep(250);
                $content = @file_get_contents($baseApiUrl . $endpointUrl);
                if ($content) {
                    $downloadSuccess = true;
                }
                $retryCount++;
            }
        }
    
        if (!$downloadSuccess) {
            return false;
        }
        
        file_put_contents('./../apicache/' .  md5($endpointUrl), $content);
    }
    return json_decode($content, true);
}

function batchDownloadFromApi ($dataTypeApiPrefix, $dataCallbackFunction) {
    $itemIds = downloadFromApi($dataTypeApiPrefix);
    $batchItemIds = [];
    
    for($i = 0; $i < count($itemIds); $i++) {
        array_push($batchItemIds, $itemIds[$i]);
    
        if ((count($batchItemIds) == 25) || ($i == (count($itemIds) - 1))) {
            $returnedData = downloadFromApi($dataTypeApiPrefix . '/' . implode(',', $batchItemIds));
            if (count($returnedData) > 0) {
                if (is_callable($dataCallbackFunction)) {
                    foreach($returnedData as $currentReturnedElement) {
                        $dataCallbackFunction($currentReturnedElement);
                    }
                }
            }
            echo ($i+1) . " of " . count($itemIds) . " objects processed.".PHP_EOL;
            $batchItemIds = [];
        }
    }
}

$monsterByDroppingItem = [];

$endpoints = [[
    'url' => '/monster',
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem, $fetchImages) {
        return;
        if ($fetchImages) {
            //file_put_contents('./images/item/' . $currentItem['icon'], file_get_contents('https://flyff-api.sniegu.fr/image/item/' . $currentItem['icon']));
            $currentItem['icon'] = downloadAndCDNImage('https://flyff-api.sniegu.fr/image/monster/' . $currentItem['icon'], '/icon/monster', $currentItem['icon']);
        }    

        foreach($currentItem['drops'] as $currentItemDrop) {
            if (!isset($monsterByDroppingItem[$currentItemDrop['item']])) {
                $monsterByDroppingItem[$currentItemDrop['item']] = [];
            }

            array_push($monsterByDroppingItem[$currentItemDrop['item']], $currentItem['id']);
        }
    },
],[
    'url' => '/item',
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem, $fetchImages) {
        return;
        $currentItem['flyffdb_dropped_by'] = [];

        if ($fetchImages) {
            //file_put_contents('./images/item/' . $currentItem['icon'], file_get_contents('https://flyff-api.sniegu.fr/image/item/' . $currentItem['icon']));
            $currentItem['icon'] = downloadAndCDNImage('https://flyff-api.sniegu.fr/image/item/' . $currentItem['icon'], '/icon/item', $currentItem['icon']);
        }

        if (isset($monsterByDroppingItem[$currentItem['id']])) {
            $currentItem['flyffdb_dropped_by'] = $monsterByDroppingItem[$currentItem['id']];
        }
    },
],[
    'url' => '/world',
    'postProcessing' => function (&$currentWorld) use ($fetchImages) {
        for($x = 0; $x < $currentWorld['width']; $x = $x +  $currentWorld['tileSize']) {
            for($y = 0; $y < $currentWorld['height']; $y = $y + $currentWorld['tileSize']) {
                $tileFileName = $currentWorld['tileName'] . ($x / $currentWorld['tileSize']) . '-' . ($y / $currentWorld['tileSize']) . '-0.png';
                downloadAndCDNImage('https://flyff-api.sniegu.fr/image/world/' . $tileFileName, '/icon/world', $tileFileName);
            }
        }
    },
],[
    'url' => '/class',
    'postProcessing' => null,
],[
    'url' => '/equipset',
    'postProcessing' => null,
],[
    'url' => '/skill',
    'postProcessing' => null,
],[
    'url' => '/partyskill',
    'postProcessing' => null,
],[
    'url' => '/npc',
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem, $fetchImages) {
        return;
        if ($fetchImages) {
            $currentItem['image'] = downloadAndCDNImage('https://flyff-api.sniegu.fr/image/npc/' . $currentItem['image'], '/icon/npc', $currentItem['image']);
        }
    },
],[
    'url' => '/quest',
    'postProcessing' => null,
],[
    'url' => '/karma',
    'postProcessing' => null,
],[
    'url' => '/achievement',
    'postProcessing' => null,
]];
$timeStart = microtime(true);
foreach($endpoints as $currentEndpoint) {
    echo "Downloading ".$currentEndpoint['url'].PHP_EOL;
    batchDownloadFromApi($currentEndpoint['url'], function ($currentItem) use ($currentEndpoint) {
        if (!is_dir('./../content')) mkdir('./../content');
        if (!is_dir('./../content'. $currentEndpoint['url'] .'s')) mkdir('./../content'. $currentEndpoint['url'] .'s');
        $currentItem['flyffdb_meta_id'] = substr($currentEndpoint['url'] . '_' . $currentItem['id'], 1);

        //make sure we dont use these fields for input data because they cant be indexed as in api
        $fulltextSearchFields = ['title', 'description', 'slug', 'text'];
        foreach($fulltextSearchFields as $currentFieldToAvoid) {
            if (isset($currentItem[$currentFieldToAvoid])) {
                $currentItem['raw_' . $currentFieldToAvoid] = $currentItem[$currentFieldToAvoid];
                unset($currentItem[$currentFieldToAvoid]);
            }
        }

        if (is_callable($currentEndpoint['postProcessing'])) {
            $currentEndpoint['postProcessing']($currentItem);
        }

        //file_put_contents('./../content'. $currentEndpoint['url'] .'s' . $currentEndpoint['url'] . '_' . $currentItem['id'] . '.json', json_encode($currentItem, JSON_PRETTY_PRINT));
    });
}
echo "Took: " . (microtime(true) - $timeStart)/60 . 'minutes';