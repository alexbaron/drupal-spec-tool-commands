<?php

namespace AlexBaron77\DrupalSpecToolCommands\Command;

use Composer\Plugin\Capability\CommandProvider  as CommandProviderCapability;

class DrupalSpecCommands implements CommandProviderCapability
{
  public function getCommands()
  {
    return [
      new DrupalSpecGherkinDumper()
    ];
  }

}
