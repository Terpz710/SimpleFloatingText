<?php

declare(strict_types=1);

namespace Terpz710\SimpleFloatingText\Task;

use pocketmine\scheduler\Task;

use Terpz710\SimpleFloatingText\API\FloatingTextAPI;

class UpdateTask extends Task {

    public function onRun(): void {
        foreach (FloatingTextAPI::$floatingText as $tag => [$position, $floatingText]) {
            $world = $position->getWorld();
            if ($world !== null) {
                $world->addParticle($position, $floatingText, $world->getPlayers());
            }
        }
    }
}