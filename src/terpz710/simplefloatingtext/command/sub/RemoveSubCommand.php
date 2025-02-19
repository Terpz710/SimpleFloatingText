<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext\command\sub;

use pocketmine\command\CommandSender;

use terpz710\simplefloatingtext\api\FloatingText;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\RawStringArgument;

class RemoveSubCommand extends BaseSubCommand {

    protected function prepare() : void{
        $this->registerArgument(0, new RawStringArgument("tag"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $tag = $args["tag"];

        if (!isset(FloatingText::$floatingText[$tag])) {
            $sender->sendMessage("§l§f(§c!§f)§r§f No floating text found with the tag §e{$tag}§f!");
            return;
        }

        FloatingText::remove($tag);
        $sender->sendMessage("§l§f(§e!§f)§r§f Floating text §e{$tag}§f removed!");
    }
}
