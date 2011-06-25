<?php

/**
 * Integer To Word Converter class
 *
 * This class allows conversion from a integer to a string. The highest conversion
 * is based on PHP_INT_MAX.
 *
 * @see 	ConverterTest
 * @author 	CodinNinja <ninja@codingninja.com.au>
 * @link 	http://www.codingninja.com.au
 */
class Converter {

    /**
     * Represents the replacement key for values 0-9
     */
    const ONES     = 0;

    /**
     * Represents the replacement key for values 20-90
     */
    const TENS     = 1;

    /**
     * Represents the replacement key for values 11-12
     */
    const TEENS    = 2;

    /**
     * The string replacemets for a range of intergers
     * @var array
     * @see self::ONES
     * @see self::TENS
     * @see self::TEENS
     */
    protected $replacements = array(
        self::ONES => array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
        ),
        self::TENS => array(
            1 => 'Ten',
            2 => 'Twenty',
            3 => 'Thirty',
            4 => 'Fourty',
            5 => 'Fifty',
            6 => 'Sixty',
            7 => 'Seventy',
            8 => 'Eighty',
            9 => 'Ninety'
        ),
        self::TEENS => array(
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen'
        )
    );

    /**
     * Word multipliers
     * @var array
     */
    protected $groups = array(
        '',
        'Thousand',
        'Million',
        'Billion',
        'Quadrillion',
        'Quintillion',
        'Sextillion',
        'Septillion',
        'Octillion',
        'Nonillion',
    	'Decillion'
    );

    /**
     * Stores if the number is negative or not
     * @var bool
     */
    protected $negative = false;

    /**
     * Static instance of self
     * @var Converter
     */
    protected static $instance = null;

    private function __construct() {}

    private function __clone() {}

    /**
     * Helper conversion function
     *
     * @see Converter::doConvert()
     * @param integer The number to convert
     */
    public static function convert($number) {
        return self::getInstance()->doConvert($number);
    }

    /**
     * Get the converters instance
     * @return Converter
     */
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Main converter function
     *
     * Converts a number to it's word representation
     *
     * @param integer $number The number to convert
     * @return string The string representation of the integer
     */
    public function doConvert($number) {
        if($number == '0') {
            return 'Zero';
        }
        $this->reset();

        $number = $this->validateNumber($number);

        $range = $this->convertToRange($number);

        $group = -1;
        $output = '';
        foreach($range as $set) {
            $group++;
            if($set === '000') {
                continue;
            }
            $output[] = $this->groups[$group] . ',';
            $output[] = $this->convertRange($set);
        }

        return ($this->negative ? 'Negative ' : '') . rtrim(implode(' ', array_reverse($output)),', ');
    }

    /**
     * Reset anything that is stored per integer back to it's original state
     */
    protected function reset() {
        $this->negative = false;
    }

    /**
     * Validate the nmber
     *
     * There are a few steps in validating the number
     * * First checks if the number is not numeric (doesn't have alpha chars in it)
     * * Then it checks if the number is negative and flags it as such.
     * * Pad the number to at most 33 characters
     *
     * @param unknown_type $number
     * @throws InvalidArgumentException
     * @return string
     */
    protected function validateNumber($number) {
        if(!is_numeric($number)) {
            throw new InvalidArgumentException(sprintf('The specified number "%s" is not valid.', $number));
        }

        if($number > PHP_INT_MAX) {
            throw new InvalidArgumentException(sprintf('The specified number is too long, a maximum of %d is allowed', PHP_INT_MAX));
        }

        if(intval($number) < 0) {
            $this->negative = true;
            $number = -$number;
        }

        $number = str_pad($number, 27, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Convert a single number into a chunked 3 character long version
     *
     * @param string The number to chunk
     * @return array The chunked and reversed array.
     * @todo This should use maths to only pad the word to something that is divisible by 3.
     */
    protected function convertToRange($number) {
        // Don't strip out any zero's after the first number has been seen
        $hasSeen = false;
        return array_reverse(array_filter(str_split($number, 3), function($value) use (&$hasSeen) {
            if($hasSeen) {
                return true;
            }
            $isValid = $value !== '000';
            if($isValid) {
                $hasSeen = true;
            }

            return $isValid;
        }));
    }

    /**
     * Splits the 3 character range to single characters and then parses them
     * @param string The 3 number range
     * @return string The parsed word version of the number
     */
    protected function convertRange($range) {
        // Split the string to 3 characters, the "hundred", "ten" and "one"
        list($hundred, $ten, $one) = str_split($range);
        return $this->parseRange($hundred, $ten, $one);
    }

    /**
     * Parse a 3 letter range
     *
     * This functions converts the 3 letter range from intergers to words
     * and then does any "word joining" if necessary
     *
     * @param integer The "hundreds" integer
     * @param integer The "tens" integer
     * @param integer The "one" integer
     * @return string The parsed word version
     */
    protected function parseRange($hundred, $ten, $one) {
        $return = array();

        // Add the "hundred" word
        if($hundred > 0) {
            $return[] = $this->replacements[self::ONES][$hundred] . ' Hundred';
        }

        // Add the "ten" word
        if($ten > 1 || ($ten == 1 && $one == 0)) { // Is the "ten" number >= 20?
            $return[] = $this->replacements[self::TENS][$ten];
        }elseif($ten == 1) { // Or a 11-19 number
            $return[] = $this->replacements[self::TEENS][$ten.$one];
            $one = 0;
        }

        // Add the "one" word
        if($one > 0) {
            $return[] = $this->replacements[self::ONES][$one];
        }

        // Do we need a "and" appended to the first number?
        $count = count($return);
        if($count > 2 || $count > 1 && $hundred > 0) {
            $return[0] .= ' and';
        }
        return implode(' ', $return);
    }
}