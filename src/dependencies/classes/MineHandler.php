<?php

class MineHandler implements APIInterface {

    /**
     * {
     *  "minecount": "359",
     *  "mineID": "9",
     *  "name": "Coal mine",
     *  "SUMfullrate": "901399.6",
     *  "SUMrawrate": "174082.0",
     *  "OAtechfactor": "5.0470",
     *  "OAHQboost": "1.0260",
     *  "OAcondition": "0.9508",
     *  "resourceName": "Coal",
     *  "resourceID": "8",
     *  "SUMdef1": "125451",
     *  "SUMdef2": "2463",
     *  "SUMdef3": "390",
     *  "OAattackpenalty": "1.0000",
     *  "SUMattackcount": "21",
     *  "SUMattacklost": "21",
     *  "OAquality": "0.9508",
     *  "OAqualityInclTU": "4.7986"
     * }
     */

    private static $unwantedKeys = ['name', 'resourceName', 'mineID', 'OAcondition'];

    public function transform(array $data): array {

        $response = [];

        foreach($data as $dataset) {
            foreach(self::$unwantedKeys as $key) {
                unset($dataset[$key]);
            }

            $response[$dataset['resourceID']] = [
                'amount'         => $dataset['minecount'],
                'sumTechRate'    => $dataset['SUMfullrate'],
                'sumRawRate'     => $dataset['SUMrawrate'],
                'sumDef1'        => $dataset['SUMdef1'],
                'sumDef2'        => $dataset['SUMdef2'],
                'sumDef3'        => $dataset['SUMdef3'],
                'sumAttacks'     => $dataset['SUMattackcount'],
                'sumAttacksLost' => $dataset['SUMattacklost'],

                'avgTechFactor'    => $dataset['OAtechfactor'],
                'avgHQBoost'       => $dataset['OAHQboost'],
                'avgQuality'       => $dataset['OAquality'],
                'avgTechedQuality' => $dataset['OAqualityInclTU'],
                'avgPenalty'       => $dataset['OAattackpenalty'],

            ];
        }

        return $response;
    }
}