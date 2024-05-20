<?php

namespace candle\Util;

use candle\LobbyCore;
use pocketmine\block\utils\DyeColor;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Utils
{

    public function playerSendKit(Player $player, string $kitMode) {
        match ($kitMode) {
            "lobby" => $this->lobbyModeKit($player),
            "PvP" => $this->pvpModeKit($player)
        };
    }

    private function lobbyModeKit(Player $player): void
    {
        $player->getInventory()->setContents([
            0 => VanillaItems::COMPASS()->setCustomName(LobbyCore::getInstance()->getConfig()->getNested("items.compass")),
            1 => VanillaItems::ENDER_PEARL()->setCustomName(LobbyCore::getInstance()->getConfig()->getNested("items.ender_pearl")),
            5 => VanillaItems::DIAMOND_SWORD()->setCustomName(LobbyCore::getInstance()->getConfig()->getNested("items.diamond_sword")),
            8 => VanillaItems::ENCHANTED_BOOK()->setCustomName(LobbyCore::getInstance()->getConfig()->getNested("items.enchanted_book")),
        ]);
    }

    private function pvpModeKit(Player $player): void
    {
        $player->getInventory()->setContents([
            0 => VanillaItems::DIAMOND_SWORD(),
            1 => VanillaItems::GOLDEN_APPLE()->setCount(12),
            2 => VanillaItems::BOW(),
            8 => VanillaItems::DYE()->setColor(DyeColor::GREEN)->setCustomName(LobbyCore::getInstance()->getConfig()->getNested("items.dye")),
            9 => VanillaItems::ARROW()->setCount(64)
        ]);

        $player->getArmorInventory()->setContents([
            VanillaItems::IRON_HELMET(),
            VanillaItems::DIAMOND_CHESTPLATE(),
            VanillaItems::DIAMOND_LEGGINGS(),
            VanillaItems::IRON_BOOTS()
        ]);
    }

}