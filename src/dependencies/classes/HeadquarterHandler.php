<?php

class HeadquarterHandler implements APIInterface {

    /*
     * {
     *  "lvl": "10",
     *  "lat": "12.1234567890123456",
     *  "lon": "12.1234567890123456",
     *  "progress1": "0",
     *  "progress2": "0",
     *  "progress3": "0",
     *  "progress4": "0",
     *  "target": "60",
     *  "itemID1": "60",
     *  "itemID2": "70",
     *  "itemID3": "77",
     *  "itemID4": null,
     *  "itemname1": "Glass",
     *  "itemname2": "Scrap metal",
     *  "itemname3": "Plastic scrap",
     *  "itemname4": null
     * }
     */

    public function transform(array $data): array {
        $data = (array) $data[0];

        foreach(['itemname1', 'itemname2', 'itemname3', 'itemname4'] as $itemName) {
            unset($data[$itemName]);
        }

        return $data;
    }
}