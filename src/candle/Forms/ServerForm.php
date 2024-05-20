<?php

namespace candle\Forms;

use candle\LobbyCore;
use EasyUI\element\Button;
use EasyUI\Form;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;

class ServerForm extends SimpleForm
{

    public function __construct(){
        parent::__construct("Server Selector");
    }

    public function onCreation(): void
    {
        $buttons = LobbyCore::getInstance()->getConfig()->getNested("ServerForm.buttons");
        foreach ($buttons as $index => $value) {
            $button = new Button($value);
            $button->setSubmitListener(function (Player $player) use ($index): void {
                switch ($index) {
                    case 0:
                        $player->transfer(LobbyCore::getInstance()->getConfig()->getNested("ServerForm.button1.ip"), LobbyCore::getInstance()->getConfig()->getNested("ServerForm.button1.port"));
                        break;
                    case 1:
                        $player->transfer(LobbyCore::getInstance()->getConfig()->getNested("ServerForm.button2.ip"), LobbyCore::getInstance()->getConfig()->getNested("ServerForm.button1.port"));
                        break;
                    case 2:
                        $player->transfer(LobbyCore::getInstance()->getConfig()->getNested("ServerForm.button3.ip"), LobbyCore::getInstance()->getConfig()->getNested("ServerForm.button1.port"));
                        break;
                }
            });
            $this->addButton($button);
        }
    }
}