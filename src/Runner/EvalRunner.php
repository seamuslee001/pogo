<?php

namespace Qp\Runner;

/**
 * Class DataRunner
 * @package Qp\Runner
 *
 * Execute via 'eval(...cleanup($code)...)'
 *
 * Pro:
 *  - Supports pipes/cli arguments intuitively
 *  - Hides shebang
 *  - Works with pure-logic and with templates/interploated-text
 * Con:
 *  - Does not run the original file; weaker for xdebug and i/o
 */
class EvalRunner {

  /**
   * @param string $autoloader
   * @param string $script
   * @param array $cliArgs
   * @return int
   */
  public function run($autoloader, $script, $cliArgs) {
    $launcher = 'require_once getenv("QP_AUTOLOAD");eval("?" . ">" . qp_script());';

    $cmd = sprintf('QP_SCRIPT=%s QP_AUTOLOAD=%s php -r %s',
      escapeshellarg($script),
      escapeshellarg($autoloader),
      escapeshellarg($launcher)
    );
    $cmd .= ' ' . implode(' ', array_map('escapeshellarg', $cliArgs));
    printf("[%s] Running command: $cmd\n", __CLASS__, $cmd);
    $process = proc_open($cmd, [STDIN, STDOUT, STDERR], $pipes);
    return proc_close($process);
  }

}