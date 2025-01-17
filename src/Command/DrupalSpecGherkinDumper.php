<?php

namespace AlexBaron77\DrupalSpecToolCommands\Command;

use Composer\Command\BaseCommand;
use AlexBaron77\DrupalSpecToolCommands\GoogleSpreadsheetAccessTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 *
 */
class DrupalSpecGherkinDumper extends BaseCommand {
  use GoogleSpreadsheetAccessTrait;

  /**
   *
   */
  public function configure() {
    $this->setName('drupal-spec-dump-gherkin');
    $this->setAliases(['drupal-dg']);
  }

  /**
   *
   */
  public function execute(InputInterface $input, OutputInterface $output) {
    include_once 'vendor/autoload.php';
    $package = $this->getComposer()->getPackage();
    $name = $package->getName();
    $extra = $package->getExtra();
    $io = $this->getIO();

    if (!isset($extra['drupal-spec-tool'])) {
      throw new \InvalidArgumentException("drupal-spec-dump-gherkin command requires the presence of the 'drupal-spec-tool' key as a child of the 'extra' key in the package config.");
    }
    $config = $extra['drupal-spec-tool'];
    if (!isset($config['spreadsheet'])) {
      throw new \InvalidArgumentException("drupal-spec-dump-gherkin command requires extra.drupal-spec-tool.spreadsheet key to be set.");
    }
    if (!isset($config['feature-path'])) {
      $config['feature-path'] = 'tests/features';
    }
    if (!is_dir($config['feature-path'])) {
      mkdir($config['feature-path'], 0777, TRUE);
    }
    if (!isset($config['credentials-path'])) {
      throw new \InvalidArgumentException("drupal-spec-dump-gherkin command requires extra.drupal-spec-tool.credentials-path key to be set.");
    }
    if (!file_exists($config['credentials-path'] . "/credentials.json")) {
      throw new FileNotFoundException("drupal-spec-dump-gherkin credentials file not found.");
    }

    $client = $this->getClient($name, $config['credentials-path'], $io);
    $range = 'Behat!A4:B17';
    $data = $this->getData($client, $config['spreadsheet'], $range);

    foreach ($data as $row) {
      if ($pos = stripos($row[0], 'feature')) {
        $name = str_replace(' ', '-', strtolower(trim(substr($row[0], 0, $pos))));
        file_put_contents("{$config['feature-path']}/$name.feature", $row[1]);
      }
    }
    $io->write("Updated all Drupal Spec Features");

  }

}
