<?php

namespace candle;

use candle\Forms\ServerForm;
use candle\Session\SessionFactory;
use candle\Util\Scoreboard\Task\ScoreboardTask;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class EventListener implements Listener
{
    private LobbyCore $core;

    public function __construct(LobbyCore $core)
    {
        $this->core = $core;
    }

    public function PlayerCreationEvent(PlayerCreationEvent $event): void {
        $event->setPlayerClass(LobbyPlayer::class);
    }

    public function PlayerJoinEvent(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $event->setJoinMessage(str_replace(["{player}"],  [$player->getName()],LobbyCore::getInstance()->getConfig()->getNested("message.join")));
        LobbyCore::getInstance()->getUtils()->playerSendKit($player, "lobby");
        LobbyCore::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this->core, $player), 20);
        $player->teleport(new Position($this->core->getConfig()->getNested("worldSystem.LobbyWorld.x"),$this->core->getConfig()->getNested("worldSystem.LobbyWorld.y"), $this->core->getConfig()->getNested("worldSystem.LobbyWorld.z"), Server::getInstance()->getWorldManager()->getWorldByName($this->core->getConfig()->getNested("worldSystem.LobbyWorld.worldName"))));
    }

    public function PlayerQuitEvent(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $event->setQuitMessage(str_replace(["{player}"], [$player->getName()], LobbyCore::getInstance()->getConfig()->getNested("message.quit")));
    }

    public function PlayerItemUseEvent(PlayerItemUseEvent $event): void {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $config = LobbyCore::getInstance()->getConfig();
        $session = SessionFactory::getSession($player);
        if(!$player instanceof LobbyPlayer) return;
        switch ($item->getName()) {
            case $config->getNested("items.compass"):
                $player->sendForm(new ServerForm());
                break;
            case $config->getNested("items.ender_pearl"):
                $event->cancel();
                $player->knockBack($player->getDirectionVector()->getX(), $player->getDirectionVector()->getY(), 1);
                break;
            case $config->getNested("items.diamond_sword"):
                $player->getInventory()->clearAll();
                $player->sendMessage($config->getNested("message.prefix") . $config->getNested("message.combatmode_enabled"));
                LobbyCore::getInstance()->getUtils()->playerSendKit($player, "PvP");
                $session->setInPvP(true);
                $player->teleport(new Position($this->core->getConfig()->getNested("worldSystem.PvPWorld.x"),$this->core->getConfig()->getNested("worldSystem.PvPWorld.y"), $this->core->getConfig()->getNested("worldSystem.PvPWorld.z"), Server::getInstance()->getWorldManager()->getWorldByName($this->core->getConfig()->getNested("worldSystem.PvPWorld.worldName"))));
                break;
            case $config->getNested("items.enchanted_book"):
                $player->sendMessage($config->getNested("message.server_information"));
                break;
            case $config->getNested("items.dye"):
                if($player->isTagged()) {
                    $player->sendMessage($config->getNested("message.prefix") . $config->getNested("message.combat"));
                } else {
                    $player->teleport(new Position($this->core->getConfig()->getNested("worldSystem.LobbyWorld.x"),$this->core->getConfig()->getNested("worldSystem.LobbyWorld.y"), $this->core->getConfig()->getNested("worldSystem.LobbyWorld.z"), Server::getInstance()->getWorldManager()->getWorldByName($this->core->getConfig()->getNested("worldSystem.LobbyWorld.worldName"))));
                    $session->setInPvP(false);
                    LobbyCore::getInstance()->getUtils()->playerSendKit($player, "lobby");
                    $player->sendMessage($config->getNested("message.prefix") . ($config->getNested("message.combatmode_disabled")));
                }
                break;
        }
    }



    public function EntityDamageEvent(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        $config = LobbyCore::getInstance()->getConfig();
        if ($entity instanceof LobbyPlayer) {
            if($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();
                if ($entity->isTagged()) {
                    $entity->combatTag();
                } else {
                    $entity->combatTag();
                    $entity->sendActionBarMessage($config->getNested("message.prefix") . $config->getNested("message.combat_tagged"));
                }
                if ($damager->isTagged()) {
                    $damager->combatTag();
                } else {
                    $damager->combatTag();
                    $damager->sendActionBarMessage($config->getNested("message.prefix") . $config->getNested("message.combat_tagged"));
                }
            }
        }
    }
}