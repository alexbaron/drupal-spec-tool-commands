<?php

namespace AlexBaron77\DrupalSpecToolCommands;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

/**
 *
 */
class ComposerPlugin implements PluginInterface, Capable {

  protected $composer;
  protected $io;

  /**
   * Apply plugin modifications to Composer.
   *
   * @param \Composer\Composer $composer
   * @param \Composer\IO\IOInterface $io
   */
  public function activate(Composer $composer, IOInterface $io) {
    $this->composer = $composer;
    $this->io = $io;
  }

  /**
   *
   */
  public function deactivate(Composer $composer, IOInterface $io) {
  }

  /**
   *
   */
  public function uninstall(Composer $composer, IOInterface $io) {
  }

  /**
   *
   */
  public function getCapabilities() {
    return [
      'Composer\Plugin\Capability\CommandProvider' => 'AlexBaron77\DrupalSpecToolCommands\Command\DrupalSpecCommands',
    ];
  }

}
