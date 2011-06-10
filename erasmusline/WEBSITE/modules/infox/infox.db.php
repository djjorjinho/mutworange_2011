<?php

class InfoxDB {
    
    public static function getUniversity() {
        $db = PlonkWebsite::getDB();
        
        $rs = $db->retrieve("SELECT instId, instName FROM institutions");
        if (!empty($rs)) {
          return $rs;
        } else
          return 0;
    }
    
    public static function getURL($id) {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT url FROM institutions WHERE instEmail = '".$id."'");
      if (!empty($rs)) {
        return $rs['url'];
      } else
        return 0;
    }

    public function getAuthUsername() {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT email FROM users WHERE userLevel = '8'");
      if (!empty($rs)) {
        return $rs['email'];
      } else
        return 0;
    }

    public function getAuthPwd() {
    	$db = PlonkWebsite::getDB();
    	$rs = $db->retrieveOne("SELECT password FROM users WHERE userLevel = '8'");
      if (!empty($rs)) {
        return $rs['password'];
      } else
        return 0;
    }
    public function getAllTables() {
    	$db = PlonkWebsite::getDB();
      $rs = $db->retrieve("SHOW TABLES");
      if (!empty($rs))
        return $rs;
      else
        return 0;
    }
    public function getAllDataFromTable($tbl) {
      $db = PlonkWebsite::getDB();
      $rs = $db->retrieve("SELECT * FROM ".$tbl);
	  $back = array();
      if (!empty($rs)) {
	    for ($i = 0; $i < count($rs); $i++) {
		  $c = count($rs[$i]);
		  $a = array_keys($rs[$i]);
		  for ($z = 0; $z < count($a); $z++) {
		    $back[$i][$z] = $rs[$i][$a[$z]];
		  }
		}
		
		return $back;
      } else
        return 0;
    }
	public function getRowFromDB($tbl, $id) {
      $db = PlonkWebsite::getDB();
      $rs = $db->retrieve("SELECT * FROM " . $tbl);
	  $a = array_keys($rs[0]);

      $rs = $db->retrieve("SELECT * FROM " . $tbl . " WHERE " . $a[0] . " = '" . $id . "'");
      if (!empty($rs)) {
        return $rs[0];
      } else
        return 0;
	}

    function UTF8_CharEncode ($UnicodePosition) {
      $UsedUtf8Chars = 1;
      $Utf8Chars = '';
      if ($UnicodePosition < 128)
      {
        $Utf8Chars[0] = chr ($UnicodePosition);
      }
      else
      {
        $FirstCharFreeBits = 0x3F;
        $CurrentChar = 0x80;
        $Utf8Chars[0] = chr (0x80);
        do
        {
          ++$UsedUtf8Chars;
          $FirstCharFreeBits >>= 1;
          $CurrentChar >>= 1;
          array_unshift ($Utf8Chars, chr (ord ($Utf8Chars[0]) | $CurrentChar));
          $Utf8Chars[1] = chr (0x80);
          $Utf8Chars[1] = chr (ord ($Utf8Chars[1]) | ($UnicodePosition & 0x3F));
          $UnicodePosition >>= 6;
        }
        while ($UnicodePosition > $FirstCharFreeBits);
        $Utf8Chars[0] = chr (ord ($Utf8Chars[0]) | $UnicodePosition);
      }
      return implode ('', $Utf8Chars);
    }
    function UTF8_CharDecode ($Chars) {
      $Number = 0;
      $ContBytes = InfoxDB::UTF8_CountBytes ($Chars);
      if ($ContBytes > 1)
      {
        for ($i = 0, $z = $ContBytes - 1; $i < $z; ++$i)
        {
          $ByteVal = ord ($Chars[$ContBytes - $i - 1]);
          $ByteVal &= 0x3F;
          $Number |= $ByteVal << ($i * 6);
        }
        $ByteVal = ord ($Chars[0]);
        $ByteVal &= (0x7F >> $ContBytes);
        $Number |= $ByteVal << (($ContBytes - 1) * 6);
      }
      else
        $Number = ord ($Chars);
      return $Number;
    }
    
    function UTF8_CountBytes ($FirstChar) {
      $FirstChar = ord ($FirstChar);
      $ContBytes = 1;
      if ($FirstChar > 0x7F)
      {
        $BitMask = 0x80;
        while ($BitMask > 0x01)
        {
          $BitMask >>= 1;
          if (($FirstChar & $BitMask) != 0)
            ++$ContBytes;
          else
            break;
        }
      }
      return $ContBytes;
    }
    function powmod($base, $exp, $modulus) {
      $basepow2 = $base;
      $exppow2 = $exp;
      $retvalue = 0;
    
      if (1 == bcmod($exppow2, 2)) {
    	  $retvalue = bcmod(bcadd($retvalue, $basepow2), $modulus);
      }
      do {
    	  $basepow2 = bcmod(bcmul($basepow2, $basepow2), $modulus);
    	  $exppow2 = bcdiv($exppow2, 2);
    	  if (1 == bcmod($exppow2, 2)) {
    		  $retvalue = bcmod(bcmul($retvalue, $basepow2), $modulus);
    	  }
      } while (1 == bccomp($exppow2, 0));
      $retvalue = bcmod($retvalue, $modulus);
      return $retvalue;
    }
}

?>
