<?php
namespace Pogo\Command;

use Pogo\PathUtil;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends BaseCommand {

  protected function configure() {
    $this
      ->setName('up')
      ->setDescription('Get dependencies for a PHP script');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    if (!empty($input->script)) {
      throw new \Exception("[up] Unexpected file argument.");
    }

    if (!file_exists('composer.json')) {
      throw new \Exception("[up] The update command should run inside a build folder.");
    }

    $composerJson = json_decode(file_get_contents('composer.json'), 1);
    if (empty($composerJson['extra']['pogo']['script'])) {
      throw new \Exception("[up] This project was not generated by pogo.");
    }

    $target = $composerJson['extra']['pogo']['script'];
    if (!file_exists($target)) {
      throw new \Exception("[up] This project references a non-existent source script ($target).");
    }

    global $argv;
    return \Pogo\Application::main([
      $argv[0],
      '--get',
      '-f',
      '--dl=' . PathUtil::getPwd(),
      $target,
    ]);
  }

}
