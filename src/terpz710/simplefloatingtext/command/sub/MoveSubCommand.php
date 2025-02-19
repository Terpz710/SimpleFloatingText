<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext\command\sub;

use pocketmine\command\CommandSender;

use pocketmine\player\Player;

use terpz710\simplefloatingtext\api\FloatingText;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\TextArgument;

class MoveSubCommand extends BaseSubCommand {

    protected function prepare() : void{
        $this->registerArgument(0, new TextArgument("tag"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be used in-game!");
            return;
        }

        $tag = $args["tag"];

        if (!isset(FloatingText::$floatingText[$tag])) {
            $sender->sendMessage("§l§f(§c!§f)§r§f No floating text found with the tag §e{$tag}§f!");
            return;
        }

        $pos = $sender->getPosition();
        FloatingText::$floatingText[$tag][0]->x = $pos->x;
        FloatingText::$floatingText[$tag][0]->y = $pos->y;
        FloatingText::$floatingText[$tag][0]->z = $pos->z;
        FloatingText::update($tag, FloatingText::$floatingText[$tag][1]->getText());

        $sender->sendMessage("§l§f(§b!§f)§r§f Floating text §e{$tag}§f moved to your location!");
    }
}