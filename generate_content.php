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

$endpoints = ['/item', '/monster', '/world', '/class', '/equipset', '/skill', '/partyskill', '/npc', '/quest', '/karma', '/achievement'];
foreach($endpoints as $currentEndpoint) {
    echo "Downloading ".$currentEndpoint.PHP_EOL;
    batchDownloadFromApi($currentEndpoint, function ($currentItem) use ($currentEndpoint) {
        if (!is_dir('./content')) mkdir('./content');
        if (!is_dir('./content'. $currentEndpoint .'s')) mkdir('./content'. $currentEndpoint .'s');
        $currentItem['flyffdb_meta_id'] = mb_substr($currentEndpoint . '_' . $currentItem['id'], 1);

        //make sure we dont use these fields for input data because they cant be indexed as in api
        $fulltextSearchFields = ['title', 'description', 'slug', 'text'];
        foreach($fulltextSearchFields as $currentFieldToAvoid) {
            if (isset($currentItem[$currentFieldToAvoid])) {
                $currentItem['raw_' . $currentFieldToAvoid] = $currentItem[$currentFieldToAvoid];
                unset($currentItem[$currentFieldToAvoid]);
            }
        }

        file_put_contents('./content'. $currentEndpoint .'s' . $currentEndpoint . '_' . $currentItem['id'] . '.json', json_encode($currentItem));
    });
}
