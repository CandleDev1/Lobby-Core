<?php

namespace candle\Util\Scoreboard\Task;

use candle\LobbyCore;
use candle\LobbyPlayer;
use candle\Util\Scoreboard\ScoreboardManager;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class ScoreboardTask extends Task
{
    private Player $player;
    private LobbyCore $plugin;

    /**
     * Status constructor.
     * @param LobbyCore $plugin
     * @param Player $player
     */
    public function __construct(LobbyCore $plugin, Player $player)
    {
        $this->plugin = $plugin;
        $this->player = $player;
    }


    public function onRun(): void
    {
        if ($this->player instanceof LobbyPlayer) {
            $config = LobbyCore::getInstance()->getConfig();
            if ($this->player->isOnline()) {
                        if ($this->player->getWorld()->getFolderName() === LobbyCore::getInstance()->getConfig()->getNested("worldSystem.LobbyWorld.worldName")) {
                            ScoreboardManager::remove($this->player);
                            ScoreboardManager::new($this->player, "Lobby", $config->getNested("Scoreboard.title"));
                            ScoreboardManager::setLine($this->player, 1, TextFormat::GRAY . '');
                            ScoreboardManager::setLine($this->player, 2, TextFormat::WHITE . str_replace(["{player}"], [$this->player->getName()], $config->getNested("Scoreboard.line_1")));
                            ScoreboardManager::setLine($this->player, 3, TextFormat::WHITE . str_replace(["{count}"], [count(server::getInstance()->getOnlinePlayers())], $config->getNested("Scoreboard.line_2")));
                            ScoreboardManager::setLine($this->player, 4, TextFormat::WHITE . str_replace(["{pvp}"], [count(Server::getInstance()->getWorldManager()->getWorldByName($config->getNested("worldSystem.PvPWorld.worldName"))->getPlayers())], $config->getNested("Scoreboard.line_3")));
                            ScoreboardManager::setLine($this->player, 5, TextFormat::WHITE . str_replace(["{ip}"], [$config->getNested("Scoreboard.Server_IP")], $config->getNested("Scoreboard.line_4")));
                            ScoreboardManager::setLine($this->player, 6, TextFormat::WHITE . str_replace(["{website}"], [$config->getNested("Scoreboard.Website")], $config->getNested("Scoreboard.line_5")));
                            ScoreboardManager::setLine($this->player, 7, TextFormat::GRAY . "§f");
                }
            }
        }
    }
}