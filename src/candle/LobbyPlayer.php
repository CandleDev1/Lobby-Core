<?php

namespace candle;

use pocketmine\player\Player;

class LobbyPlayer extends Player
{

    private $combatTag = 0;
    private int $combatTimer;

    public function combatTag(bool $value = true): void {
        if($value) {
            $this->combatTag = time();
            return;
        }
        $this->combatTag = 0;
    }

    public function isTagged(): bool {
        return (time() - $this->combatTag) <= 15;
    }

    public function getCombatTime(): int
    {
        if ($this->combatTag === 0) {
            return 0;
        }

        $time = time();
        $rtime = 15 - ($time - $this->combatTag);

        if ($rtime <= 0) {
            $this->combatTag = 0;
            $this->combatTimer = 0;
        }

        return $rtime;
    }

}