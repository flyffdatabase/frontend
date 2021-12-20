<?php

function downloadFromApi($endpointUrl) {
    if (!is_dir('./apicache'))
        mkdir('./apicache');
    if (file_exists('./apicache/' .  md5($endpointUrl))) {
        $content = file_get_contents('./apicache/' . md5($endpointUrl));
    } else {
        $baseApiUrl = 'https://flyff-api.sniegu.fr';
        $content = file_get_contents($baseApiUrl . $endpointUrl);
        file_put_contents('./apicache/' .  md5($endpointUrl), $content);
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
            $batchItemIds = [];
        }
    }
}

$monsterByDroppingItem = [];

$endpoints = [[
    'url' => '/monster',
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem) {
        foreach($currentItem['drops'] as $currentItemDrop) {
            if (!isset($monsterByDroppingItem[$currentItemDrop['item']])) {
                $monsterByDroppingItem[$currentItemDrop['item']] = [];
            }

            array_push($monsterByDroppingItem[$currentItemDrop['item']], $currentItem['id']);
        }
    },
],[
    'url' => '/item',
    'postProcessing' => function (&$currentItem) use (&$monsterByDroppingItem) {
        $currentItem['flyffdb_dropped_by'] = [];

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
        if (!is_dir('./content')) mkdir('./content');
        if (!is_dir('./content'. $currentEndpoint['url'] .'s')) mkdir('./content'. $currentEndpoint['url'] .'s');
        $currentItem['flyffdb_meta_id'] = mb_substr($currentEndpoint['url'] . '_' . $currentItem['id'], 1);

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

        file_put_contents('./content'. $currentEndpoint['url'] .'s' . $currentEndpoint['url'] . '_' . $currentItem['id'] . '.json', json_encode($currentItem));
    });
}
