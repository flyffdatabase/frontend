<?php

require_once('./vendor/autoload.php');

use ImageKit\ImageKit;

$fetchImages = true;

if ($fetchImages) {
    $imageKit = new ImageKit(
        "public_O7oYGTSitVW5KUCxnVos/3Wa1uc=",
        "private_WGX2iOlcR+KyzaWGcEGA4tClU/0=",
        "https://ik.imagekit.io/flyffdb/"
    );
}

function downloadAndCDNImage($remoteImage, $folderName, $fileName, $imageKit) {
    //fetch image from remote url (parameter)
    $retryCount = 0;
    $downloadSuccess = false;
    $remoteImageRaw = '';
    while (!$remoteImageRaw) {
        if ($retryCount < 5) {
            usleep(200);
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
    //upload image to cdn and generate imageurl
    $uploadResult = $imageKit->upload(array(
        'file' => base64_encode($remoteImageRaw),
        'fileName' => $fileName,
        'folder' => $folderName
    ));

    if ($uploadResult->success) {
        return $uploadResult->success->url;
    }
    return false;
}

function downloadFromApi($endpointUrl) {
    if (!is_dir('./../apicache'))
        mkdir('./../apicache');
    if (file_exists('./../apicache/' .  md5($endpointUrl))) {
        $content = file_get_contents('./../apicache/' . md5($endpointUrl));
    } else {
        $baseApiUrl = 'https://flyff-api.sniegu.fr';
        $content = file_get_contents($baseApiUrl . $endpointUrl);
        file_put_contents('./../apicache/' .  md5($endpointUrl), $content);
    }
    return json_decode($content, true);
}

function batchDownloadFromApi ($dataTypeApiPrefix, $dataCallbackFunction) {
    $itemIds = downloadFromApi($dataTypeApiPrefix);
    $batchItemIds = [];
    
    for($i = 0; $i < count($itemIds); $i++) {
        array_push($batchItemIds, $itemIds[$i]);
    
        if ((count($batchItemIds) == 100) || ($i == (count($itemIds) - 1))) {
            $returnedData = downloadFromApi($dataTypeApiPrefix . '/' . implode(',', $batchItemIds));
            if (count($returnedData) > 0) {
                if (is_callable($dataCallbackFunction)) {
                    foreach($returnedData as $currentReturnedElement) {
                        $dataCallbackFunction($currentReturnedElement);
                    }
                }
            }
            echo count($batchItemIds) . " objects processed.".PHP_EOL;
            $batchItemIds = [];
        }
    }
}

$monsterByDroppingItem = [];

$endpoints = [[
    'url' => '/monster',
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem, $fetchImages, $imageKit) {
        if ($fetchImages) {
            //file_put_contents('./images/item/' . $currentItem['icon'], file_get_contents('https://flyff-api.sniegu.fr/image/item/' . $currentItem['icon']));
            $currentItem['icon'] = downloadAndCDNImage('https://flyff-api.sniegu.fr/image/monster/' . $currentItem['icon'], '/icon/monster', $currentItem['icon'], $imageKit);
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
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem, $fetchImages, $imageKit) {
        $currentItem['flyffdb_dropped_by'] = [];
        die;

        if ($fetchImages) {
            //file_put_contents('./images/item/' . $currentItem['icon'], file_get_contents('https://flyff-api.sniegu.fr/image/item/' . $currentItem['icon']));
            $currentItem['icon'] = downloadAndCDNImage('https://flyff-api.sniegu.fr/image/item/' . $currentItem['icon'], '/icon/item', $currentItem['icon'], $imageKit);
        }

        if (isset($monsterByDroppingItem[$currentItem['id']])) {
            $currentItem['flyffdb_dropped_by'] = $monsterByDroppingItem[$currentItem['id']];
        }
    },
],[
    'url' => '/world',
    'postProcessing' => null,
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
    'postProcessing' => null,
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

        file_put_contents('./../content'. $currentEndpoint['url'] .'s' . $currentEndpoint['url'] . '_' . $currentItem['id'] . '.json', json_encode($currentItem));
    });
}