<?php

namespace candle;

use candle\Session\SessionListener;
use candle\Util\Scoreboard\ScoreboardManager;
use candle\Util\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class LobbyCore extends PluginBase
{

    use SingletonTrait;

    private Utils $utils;

    public function onLoad(): void {
        self::$instance = $this;
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->utils = new Utils();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SessionListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ScoreboardManager(), $this);

        $this->getServer()->getWorldManager()->loadWorld($this->getConfig()->getNested("worldSystem.LobbyWorld.worldName"));
        $this->getServer()->getWorldManager()->loadWorld($this->getConfig()->getNested("worldSystem.PvPWorld.worldName"));
    }
    
    public function getUtils(): Utils {
        return $this->utils;
    }

}