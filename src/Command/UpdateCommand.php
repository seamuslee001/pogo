<?php
namespace Pogo\Command;

use Pogo\PogoInput;
use Pogo\Pwd;

class UpdateCommand {

  public function run(PogoInput $input) {
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

    $subInput = PogoInput::create([
      $input->interpreter,
      'dl',
      $target,
      '--force',
      '--out=' . Pwd::getPwd(),
    ]);
    return pogo_main($subInput);
  }

}
