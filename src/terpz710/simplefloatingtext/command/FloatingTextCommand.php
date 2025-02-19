<?php

declare(strict_types=1);

namespace terpz710\simplefloatingtext\command;

use terpz710\simplefloatingtext\Loader;

use terpz710\simplefloatingtext\command\sub\CreateSubCommand;
use terpz710\simplefloatingtext\command\sub\RemoveSubCommand;
use terpz710\simplefloatingtext\command\sub\EditSubCommand;
use terpz710\simplefloatingtext\command\sub\MoveSubCommand;

use CortexPE\Commando\BaseCommand;

class FloatingTextCommand extends BaseCommand {

    protected function prepare() : void{
        $this->setPermission("simplefloatingtext.cmd");
        
        $this->registerSubCommand(new CreateSubCommand("create", "Create a floating text"));
        $this->registerSubCommand(new RemoveSubCommand("remove", "Remove a floating text"));
        $this->registerSubCommand(new EditSubCommand("edit", "Edit an existing floating text"));
        $this->registerSubCommand(new MoveSubCommand("move", "Move a floating text to your location"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
        $sender->sendMessage("§l§eAvailable SubCommands:");
        $sender->sendMessage("§e/ft create <tag> <text>§f - Create a new floating text");
        $sender->sendMessage("§e/ft remove <tag>§f - Remove an existing floating text");
        $sender->sendMessage("§e/ft edit <tag> <new-text>§f - Edit a floating text's text");
        $sender->sendMessage("§e/ft move <tag>§f - Move a floating text to your location");
    }
}
