<?php
//namespace Marius\ClickerHeroesApi;

/**
 *	Clicker Heroes Complete API
 *
 *	@package 	Clicker Heroes Api
 *	@author 	Marius Posthumus <mjtheone@gmail.com>
 **/

class ClickerHeroesApi {
    /**
     *	Encrypted save
     *
     *	@var mixed|string
     **/
    private $encrypted = null;

    /**
     *	Decrypted save
     *
     *	@var mixed|stdClass
     **/
    private $decrypted = null;

    /**
     *	Known salts
     *
     *	@var array
     **/
    private $knownSalts = null;

    /**
     *	Working salt variable
     *
     *	@var string
     **/
    private $salt = null;

    /**
     *	Known delimiters
     *
     *	@var array
     **/
    private $knownDelimiters = null;

    /**
     *	Anticheat delimiter and hash check
     *
     *	@var string
     **/
    private $delimiter = null;

    public function __construct() {
        $this->knownSalts 		= $this->getStaticData('salt');
        $this->knownDelimiters 	= $this->getStaticData('delimiter');
    }

    /**
     *	Get static data
     *
     *	@param 	string $type
     *	@return $this
     **/
    public function getStaticData($type) {
        $tmpArr = array();
        $type   = strtolower($type);

        switch($type) {
            case 'salt':
                $type = 'salts';
                break;

            case 'delimiter':
                $type = 'delimiters';
                break;
        }

        $static = json_decode(file_get_contents(__DIR__ . '/statics/' . $type . '.json'));

        foreach($static as $data) {
            $tmpArr[] = $data;
        }

        return $tmpArr;
    }

    /**
     *	Return decrypted save file
     *
     *	@param 	string $value
     *	@return $this
     **/
    public function decrypt($val) {
        $this->encrypted = $val;
        $this->findDelimiter()->hackIt();

        return $this->decrypted;
    }

    public function hackIt() {
        $result = explode($this->delimiter, $this->encrypted);

        foreach($this->knownSalts as $salt) {
            $check = '';
            // Needed because of the 'Sprinkle' function (adding random characters to the hash)
            for($i = 0; $i < strlen($result[0]); $i +=2) {
                $check .= $result[0][$i];
            }

            $hash = md5($check . $salt->val);
            if($hash == $result[1]) {
                $this->salt = $salt;
                $this->decrypted = json_decode(base64_decode($check));

                return $this;
            }
        }
    }

    /**
     *	Find current delimiter
     *	If it's new save it for future use
     **/
    public function findDelimiter() {
        // The delimiter is at a set position at the end of the file
        $this->delimiter = substr($this->encrypted, strlen($this->encrypted) - 48, 16);

        // If the delimiter is unknown add it to the delimiter JSON file for later use
        foreach($this->knownDelimiters as $delim) {
            if(!in_array($delim->val, $this->knownDelimiters)) {
                $data 		= array(
                    "version" 	=> "", // get version?
                    "val"		=> $this->delimiter
                );

                $inp 		= file_get_contents(__DIR__ . '/statics/delimiters.json');
                $tempArray 	= json_decode($inp);

                array_push($tempArray, $data);
                $jsonData 	= json_encode($tempArray);
                file_put_contents(__DIR__ . '/statics/delimiters.json', $jsonData);

                break;
            }
        }

        return $this;
    }
}