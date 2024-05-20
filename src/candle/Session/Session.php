<?php

namespace candle\Session;

use pocketmine\player\Player;

class Session
{

    private Player $player;
    private $var = false;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getPlayer(): Player {
        return $this->player;
    }


    public function setInPvP(bool $var): bool {
        return $this->var = $var;
    }

    public function isInPvP(): bool {
        return $this->var;
    }
}