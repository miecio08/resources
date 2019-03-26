<?php declare(strict_types=1);

class APICreditsHandler implements APIInterface {

    /*
     * {
     *  "creditsleft": "8106"
     * }
     */

    private $pdo;
    private $playerIndexUID;

    private const QUERIES = [
        'save' => 'UPDATE `user` SET `remainingAPICredits` = :remainingAPICredits WHERE `playerIndexUID` = :playerIndexUID',
    ];

    public function __construct(PDO $pdo, int $playerIndexUID) {
        $this->pdo            = $pdo;
        $this->playerIndexUID = $playerIndexUID;
    }

    public function transform(array $data): array {
        return [
            'remainingAPICredits' => $data[0]['creditsleft'],
            'playerIndexUID'      => $this->playerIndexUID,
        ];
    }

    public function save(array $data): bool {
        $stmt = $this->pdo->prepare(self::QUERIES['save']);
        return $stmt->execute($data);
    }
}