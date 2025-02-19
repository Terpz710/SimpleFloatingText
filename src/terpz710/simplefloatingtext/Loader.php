<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext;

use pocketmine\plugin\PluginBase;

use terpz710\simplefloatingtext\api\FloatingText;

use terpz710\simplefloatingtext\command\FloatingTextCommand;

use CortexPE\Commando\PacketHooker;

final class Loader extends PluginBase {

    protected static self $instance;

    protected function onLoad() : void{
        self::$instance = $this;
    }

    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }

        $this->getServer()->getCommandMap()->register("SimpleFloatingText", new FloatingTextCommand($this, "ft", "Manage Floating Texts", ["floatingtext"]));
    }

    public function onDisable() : void{
        FloatingText::saveFile();
    }

    public static function getInstance() : self{
        return self::$instance;
    }
}
