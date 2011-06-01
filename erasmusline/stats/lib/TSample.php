<?php
/**
 * @class       TSample
 * @author      Ignatius Teo    <ignatius@act28.com>
 * @version     0.1
 * @copyright   Ignatius Teo    <http://act28.com> All Rights Reserved
 *
 * $Id: tsample.php,v 1.2 2004/12/04 00:00:54 Owner Exp $
 *
 * Helper class for generating sample test data.
 * To use this, simply descend from this class using:
 *
 * <code>
 * <?php
 *     include_once "tsample.php";
 *     class MySample extends TSample
 *     {
 *         // ... your own implementation here
 *     }
 * ?>
 * </code>
 *
 */

class TSample
{
	/**
	 * Returns a pseudo-random numerical value between the range specified by min & max.
	 * If min or max is null, the value returned will be between 0 and the maximum
	 * random value defined by RAND_MAX.
	 * @method  range(int $min, int $max)
	 * @public
	 * @param	(int)	    $min
	 * @param	(int)	    $max
	 * @return	(int)	    $rand
	 */
	function range($min = null, $max = null)
    {
        if (PHP_VERSION < 4.2)
        {
            // seed the range generator
            list($usec, $sec) = explode(' ', microtime());
            $seed = (float) $sec + ((float) $usec * 100000);
            mt_srand($seed);
        }
        if ($min !== null && $max !== null)
            $rand = mt_rand($min, $max);
        else
            $rand = mt_rand();
        return $rand;
    }

	/**
	 * This is an alias for the range method.
	 * @method  random(int $min, int $max)
	 * @public
	 * @param	(int)	    $min
	 * @param	(int)	    $max
	 * @return	(int)	    $rand
	 * @see     #range($min, $max)
	 */
	function random($min = null, $max = null)
    {
        return $this->range($min, $max);
    }

	/**
	 * Return $max_choices from array of choices as a separated string.
	 * If $max_choices is null, only the minimum number of choices defined by
	 * $min_choices will be returned.
	 * If both $min and $max is specified, this method will return a random number of
	 * selections between $min and $max.
	 * @method  choice(array $choices, int $min_choices, int $max_choices, char $sep)
	 * @public
	 * @param	(array)	    $choices
	 * @param	(int)	    $min_choices    minimum number of choices
	 * @param	(int)	    $max_choices    maximum number of choices
	 * @param	(char)	    $sep            choice separator character (default ",")
	 * @return	(string)	$rand           separated string (if applicable) of random choices
	 */
	function choice($choices, $min_choices = 1, $max_choices = null, $sep = ",")
	{
	    // make choices even more random...
	    for ($i = 0; $i < $this->random(1,10); $i++)
    	    shuffle($choices);

	    $my_choices = array();

        if ($max_choices === null)
	        $max_choices = $min_choices;

        $range = $max_choices;
	    if ($max_choices > $min_choices)
            $range = $this->range($min_choices, $max_choices);

	    while (count($my_choices) < $range)
	    {
            $rand = $this->range(0, count($choices) - 1);
            $selected = $choices[$rand];
            // this ensures that choices are unique!
            $my_choices[$selected] = $selected;
        }
        asort($my_choices);
        return @implode($sep, array_keys($my_choices));
	}

	/**
	 * Returns a DB (ISO) format datetime string between $d1 & $d2.
	 * if $d1 and $d2 is not specified, the current datetime is returned.
	 * @method  datetime(int $d1, int $d2)
	 * @public
	 * @param	(int)       $d1             timestamp
	 * @param	(int)       $d2             timestamp
	 * @return	(string)	$db_datetime    a db (ISO) format datetime string
	 */
	function datetime($d1 = null, $d2 = null)
	{
        if ($d1 === null && $d2 === null)
	        $dt = time();
        else if ($d1 > 0 && $d2 === null)
            $dt = $d1;
        else if ($d1 > 0 && $d2 > 0)
	        $dt = $this->range($d1, $d2);
	    else
	        $dt = time();   // safety valve!

	    $db_datetime = date('Y-m-d H:i:s', $dt);
        return $db_datetime;
	}

	/**
	 * Returns a date of birth within the specified age range
	 * @method  dob(int $min, $int $max)
	 * @public
	 * @param	(int)           $min    minimum age in years
	 * @param	(int)           $max    maximum age in years
	 * @return	(string)	    $dob    a db (ISO) format datetime string
	 */
	function dob($min = 18, $max = 100)
	{
        $dob_year = date('Y') - ($this->range($min, $max));

        $dob_month = $this->range(1, 12);

        if ($dob_month == 2)
        {
            // leap year?
            if ($age_years % 4 || $age_years % 400)
                $max_days = 29;
            else
                $max_days = 28;
        }
        else if (in_array($dob_month, array(4, 6, 9, 11)))
            $max_days = 30;
        else
            $max_days = 31;

        $dob_day = $this->range(1, $max_days);

        $dob = sprintf("%4d-%02d-%02d", $dob_year, $dob_month, $dob_day);
        return $dob;
	}

	/**
	 * Returns a string of random numbers to specified length.
	 * @method  number(int $length)
	 * @public
	 * @param	(int)	    $length
	 * @return	(str)	    $number
	 */
	function number($length = 8)
	{
        $number = "";
        for ($i=0; $i < $length; $i++)
        {
            if ($i==0)
                $number .= $this->range(1, 9);    // so we don't start with 0!
            else
                $number .= $this->range(0, 9);
        }
		return $number;
	}

	/**
	 * Returns a string of alphabetical characters to the specified length.
	 * @method  alpha(int $length)
	 * @public
	 * @param	(int)	    $length
	 * @return	(string)	$str
	 */
	function alpha($length = 10)
	{
        $str = "";
        for ($i=0; $i <= $length; $i++)
        {
            $chr = $this->range(1, 26); // from a-z of course!
            $str .= chr($chr + 96);
    	}
    	return $str;
	}

	/**
	 * Returns an string of printable ASCII alphanumeric characters to the specified length.
	 * @method  alphanum(int $length)
	 * @public
	 * @param	(int)	    $length
	 * @return	(string)	$str
	 */
	function alphanum($length = 10)
	{
        $str = "";
        for ($i=0; $i <= $length; $i++)
        {
            $chr = $this->range(33, 255); // printable ASCII range
            $str .= chr($chr);
    	}
    	return $str;
	}

	/**
	 * This method is used to return a value as-is.
	 * Basically, this method does nothing - it's used to
	 * return the passed value to the generate method.
	 * @method  as_is(mixed $value)
	 * @public
	 * @param	(mixed)     $value
	 * @return	(mixed)	    $value
	 * @see     #generate($ruleset, $rows)
	 */
	function as_is($value = null)
	{
		return $value;
	}

	/**
	 * Generates the specified number of records according to the supplied ruleset
	 * and returns an array of random sample data.
	 * @method  generate(array $ruleset, int $rows)
	 * @public
	 * @param	(array)	    $ruleset    field data rules
	 * @param	(int)	    $rows       number of records
	 * @return	(array)     $records
	 */
	function generate($ruleset, $rows = 100)
	{
        $records = array();
        for ($i=0; $i < $rows; $i++)
        {
            foreach ($ruleset as $field => $params)
            {
                if (!empty($params))
                {
                    $rule = array_shift($params);
                    if (is_array($rule))
                    {
                        $tmp = array();
                        array_unshift($params, $rule);
                        foreach ($params as $subrules)
                        {
                            $subrule = array_shift($subrules);
                            $obj = new TSample;
                            $tmp[$subrule] = call_user_func_array(array(&$obj, $subrule), $subrules);
                        }
                        $records[$i][$field] = @implode(" ", $tmp);
                    }
                    else
                    {
                        $obj = new TSample;
                        $records[$i][$field] = call_user_func_array(array(&$obj, $rule), $params);
                    }
                }
                else
                    $records[$i][$field] = "";
            }
        }
		return $records;
	}
	
	function words($numlines, $unique=true,$filename='/usr/share/dict/words') {

		if (!file_exists($filename) || !is_readable($filename))
		    return null;
		$filesize = filesize($filename);
		$lines = array();
		$n = 0;
	    
		$handle = @fopen($filename, 'r');
	    
		if ($handle) {
		    while ($n < $numlines) {
			fseek($handle, rand(0, $filesize));
	    
			$started = false;
			$gotline = false;
			$line = "";
	    
			while (!$gotline) {
			    if (false === ($char = fgetc($handle))) {
				$gotline = true;
			    } elseif ($char == "\n" || $char == "\r") {
				if ($started)
				    $gotline = true;
				else
				    $started = true;
			    } elseif ($started) {
				$line .= $char;
			    }
			}
	    
			if ($unique && array_search($line, $lines))
			    continue;
	    
			$n++;
			array_push($lines, $line);
		    }
	    
		    fclose($handle);
		}
	    
		return $lines;
	    }
	
	
	
}
?>