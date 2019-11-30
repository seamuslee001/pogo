<?php

namespace Qp\Runner;

/**
 * Class DataRunner
 * @package Qp\Runner
 *
 * Execute via 'include data://text/plain...cleanup($code)...'
 *
 * Pro:
 *  - Supports pipes/cli arguments intuitively
 *  - Hides shebang
 * Con:
 *  - Does not run the original file; weaker for xdebug and i/o
 *  - Only works with pure-logic - not interpolated text
 *  - Forces allow_url_include=1
 */
class DataRunner {

  /**
   * @param string $autoloader
   * @param string $script
   * @param array $cliArgs
   * @return int
   */
  public function run($autoloader, $script, $cliArgs) {
    $launcher = 'require_once getenv("QP_AUTOLOAD"); include "data://text/plain;base64,".base64_encode(qp_script());';
    $cmd = sprintf('QP_SCRIPT=%s QP_AUTOLOAD=%s php -d allow_url_include=1 -r %s',
      escapeshellarg($script),
      escapeshellarg($autoloader),
      escapeshellarg($launcher)
    );
    $cmd .= ' ' . implode(' ', array_map('escapeshellarg', $cliArgs));
    // printf("[%s] Running command: $cmd\n", __CLASS__, $cmd);
    $process = proc_open($cmd, [STDIN, STDOUT, STDERR], $pipes);
    return proc_close($process);
  }

}