<?php

/**
 * Prints the value of a variable or an object
 * @param type $variable The variable to print
 * @param boolean $vardump True if you need its type
 * @param string $color The color of the debug
 * @param type $stacktrace If needed to be changed
 */
function debug($variable = '', $vardump = false, $color = null, $stacktrace = null) {
    if (ENVIRONMENT != 'production') {
        if ($stacktrace == null) {
            $stacktrace = debug_backtrace();
        }
        $stacktrace = 'in file : ' . $stacktrace[0]['file'] . ' (line ' . $stacktrace[0]['line'] . ')';
        ?>
        <div class="debug <?= $color !== null ? '-' . $color : '' ?>">
            <p><strong>Debug <?= $stacktrace ?></strong></p>
            <?php if ($variable !== '') { ?>
                <pre><?php
                    if ($vardump) {
                        var_dump($variable);
                    } else {
                        print_r($variable);
                    }
                    ?></pre>
            <?php } else { ?>
                <p>You need an argument to the debug function</p>
            <?php } ?>
        </div>
        <?php
    }
}

/**
 * Prints an array with separated debugs
 * @param array $array The array to print
 * @param boolean $vardump True if you need its type
 * @param string $color The color of the debug
 * @param boolean $darken True if one debug on two is darken
 */
function debug_array($array = array(), $vardump = false, $color = null, $darken = true) {
    if (ENVIRONMENT != 'production') {
        $stacktrace = debug_backtrace();

        $i = 0;
        foreach ($array as $item) {
            $temp_color = $color;
            if ($darken && $i % 2 == 1) {
                $temp_color .= ' -darker';
            }
            debug($item, $vardump, $temp_color, $stacktrace);
            $i++;
        }
    }
}

/**
 * Save the microtime of the moment.<br>
 * Prints a debug to show how much time is spent between two moments
 * @param type $moment A flag in your code to mark a moment. If empty, the whole debug is printed
 * @param string $color The color of the debug
 */
function check_execution_time($moment = null, $color = 'green') {
    if (ENVIRONMENT != 'production') {
        if ($moment === null) {
            $GLOBALS['execution_time']['end'] = microtime(true);
            $execution_time = $GLOBALS['execution_time'];
            $prev_exec_moment = null;
            $prev_flag = null;
            foreach ($execution_time as $flag => $exec_moment) {
                if ($prev_exec_moment !== null) {
                    $elapsed_time = number_format($exec_moment - $prev_exec_moment, 3);
                    debug('Elapsed time from <strong>' . $prev_flag . '</strong> to <strong>' . $flag . '</strong> : ' . $elapsed_time . 's', false, $color);
                }
                $prev_exec_moment = $exec_moment;
                $prev_flag = $flag;
            }
            unset($GLOBALS['execution_time']);
        } else {
            $GLOBALS['execution_time'][$moment] = microtime(true);
        }
    }
}
