<?php declare(strict_types=1);

class User {

    /** @var string */
    private $key;
    private $playerIndexUID = 0;

    /** @var PDO $pdo */
    private $pdo;

    private const QUERIES = [
        'exists'            => 'SELECT `uid` FROM `user` WHERE `apiKey` = :apiKey',
        'add'               => 'INSERT INTO `user` (`apiKey`, `playerIndexUID`, `firstSeen`, `lastSeen`) VALUES(:apiKey, :playerIndexUID, :firstSeen, :lastSeen)',
        'get'               => 'SELECT `playerIndexUID` FROM `user` WHERE `apiKey` = :apiKey',
        'setPlayerIndexUID' => 'UPDATE `user` SET `playerIndexUID` = :playerIndexUID WHERE `apiKey` = :apiKey',
    ];

    public function __construct(PDO $pdo, string $apiKey) {
        $this->key = $apiKey;
        $this->pdo = $pdo;
    }

    public function exists(): bool {
        $stmt = $this->pdo->prepare(self::QUERIES['exists']);
        $stmt->execute([
            'apiKey' => $this->key,
        ]);

        return $stmt->rowCount() === 1;
    }

    public function add(): int {
        $playerIndex = new PlayerIndex($this->pdo);
        $playerName  = $playerIndex->escapeUserName((new APICore($this->key, 7))->getPlayerNameFromSource());

        if(empty($playerName)) {
            return 0;
        }

        if($playerIndex->getPlayerIDByName($playerName) === 0) {
            $this->playerIndexUID = $playerIndex->addPlayer($playerName);
        }

        $stmt = $this->pdo->prepare(self::QUERIES['add']);
        $stmt->execute([
            'apiKey'         => $this->key,
            'playerIndexUID' => $this->playerIndexUID,
            'firstSeen'      => time(),
            'lastSeen'       => time(),
        ]);

        return $this->playerIndexUID;
    }

    public function get(): int {
        $stmt = $this->pdo->prepare(self::QUERIES['get']);
        $stmt->execute([
            'apiKey' => $this->key,
        ]);

        return $stmt->fetch()['playerIndexUID'];
    }

    public function setPlayerIndexUID(int $playerIndexUID): void {
        $stmt = $this->pdo->prepare(self::QUERIES['setPlayerIndexUID']);
        $stmt->execute([
            'playerIndexUID' => $playerIndexUID,
            'apiKey'         => $this->key,
        ]);
    }
}
