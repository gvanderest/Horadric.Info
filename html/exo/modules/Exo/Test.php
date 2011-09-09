<?php
/**
 * ExoSkeleton Test
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */
class Exo_Test
{
    const PASS = TRUE;
    const FAIL = FALSE;
    const SKIP = 'skip';

    protected $verbose = FALSE; // display tests as they occur and auto-display summary
    protected $summary = TRUE;
    protected $fatal = FALSE; // all test failings are fatal
    protected $tests = array();

    protected $pass_count = 0;
    protected $fail_count = 0;
    protected $skip_count = 0;
    protected $count = 0;

    protected $timings = array();

    /**
     * Instantiate the test
     * @param array $options (optional)
     * @return void
     */
    public function __construct($options = array())
    {
        $this->timings['START'] = microtime(TRUE); 
        if (isset($options['verbose'])) { $this->verbose = (bool)$options['verbose']; }
    }

    /**
     * Test a case
     * @param mixed $actual
     * @param mixed $expected
     * @return bool TRUE on correct assertion, FALSE if not
     */
    public function assert($title, $result, $options = array())
    {
        $omit = isset($options['omit']) ? (bool)$options['omit'] : FALSE;
        $time = microtime(TRUE);
        $elapsed = ($time - $this->timings['START']);
        $last = count($this->tests) == 0 ? $this->timings['START'] : $this->tests[count($this->tests) - 1]['time'];
        $duration = $time - $last;
        $test = array(
            'time' => $time,
            'elapsed' => $elapsed,
            'duration' => $duration,
            'title' => $title,
            'result' => $result,
            'omit' => $omit
        );

        if ($result == self::PASS)
        {
            $this->pass_count++;
        } elseif ($result == self::SKIP) {
            $this->skip_count++;
        } else {
            $this->fail_count++;
        }
        $this->count++;

        if ($this->verbose)
        {
            printf("[%0.5fs] %s... <strong>%s</strong><br />\n", $duration, $title, $result == self::PASS ? 'PASS' : $result == self::SKIP ? 'SKIP' : 'FAIL');
        }

        $this->tests[] = $test;

        if (isset($options['fatal']) && $options['fatal'] && !$result)
        {
            exit("This test was marked as fatal\n");
        }

        $this->timings['END'] = microtime(TRUE);
    }

    /**
     * Display the results in a table
     * @param void 
     * @return string
     */
    public function displayTable()
    {
        $duration = $this->timings['END'] - $this->timings['START'];

        // subtract omissions from time
        foreach ($this->tests as $test)
        {
            if ($test['omit'])
            {
                $duration -= $test['duration'];
            }
        }

        if (count($this->tests) == 0)
        {
            return "No tests were performed.";
        }
    
        $output = '';
        $output .= "
            <table style=\"border: 1px solid black;\">
            <thead>
            <tr>
            <th style=\"border: 1px solid black;\">#</th>
            <th style=\"border: 1px solid black;\">Time</th>
            <th style=\"border: 1px solid black;\">Duration</th>
            <th style=\"border: 1px solid black;\">Percent</th>
            <th style=\"border: 1px solid black;\">Test</th>
            <th style=\"border: 1px solid black;\">Result</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
            <td style=\"border: 1px solid black;\" colspan=\"6\">Tests Executed: " . $this->count . ", Pass: " . $this->pass_count . ", Skip: " . $this->skip_count . ", Fail: " . $this->fail_count . ", Time Elapsed: " . sprintf('%0.5f', $duration) . " seconds</td>
            </tfoot>
            <tbody>
        ";
        foreach ($this->tests as $test)
        {
            if (!isset($count))
            {
                $count = 0;
            }
            $count++;

            $percent = ($duration == 0 ? 100 : ($test['duration'] / $duration));
            $bg_red = floor($percent * 255);
            $bg_green = 0;
            $bg_blue = 0;
            $output .= "
                <tr>
                <td style=\"border: 1px solid black; text-align: right;\">" . sprintf('%d', $count) . "</td>
                <td style=\"border: 1px solid black;\">" . sprintf('%0.5fs', ($test['elapsed'])) . "</td>
                <td style=\"border: 1px solid black;\">" . sprintf('%0.5fs', ($test['duration'])) . "</td>
            ";
            if ($test['omit'])
            {
                $output .= "
                    <td style=\"border: 1px solid black; color: #555; background-color: #000;\">OMIT</td>
                ";
            } else {
                $output .= "
                    <td style=\"border: 1px solid black; color: #aaa; background-color: rgb(".$bg_red.",".$bg_green.",".$bg_blue.");\">" . sprintf('%0.1f%%', $percent * 100) . "</td>
                ";
            }
            $output .= "
                <td style=\"border: 1px solid black;\">" . $test['title'] . "</td>
                <td style=\"border: 1px solid black; background-color: " . ($test['result'] ? 'green' : 'red') . "; color: white;\">" . ($test['result'] ? 'PASS' : 'FAIL') . "</td>
                </tr>
            ";
            
        }
        $output .= "
            </tbody>
            </table>
        ";

        return $output;
    }

    /**
     * Destructor when script ends
     * @param void
     * @return void
     */
    public function __destruct()
    {
        if ($this->verbose || $this->summary)
        {
            print($this->displayTable());
        }
    }
}
