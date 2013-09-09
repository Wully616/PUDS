<?php
/*
 *      Copyright 2010 Rob McFadzean <rob.mcfadzean@gmail.com>
 *      
 *      Permission is hereby granted, free of charge, to any person obtaining a copy
 *      of this software and associated documentation files (the "Software"), to deal
 *      in the Software without restriction, including without limitation the rights
 *      to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *      copies of the Software, and to permit persons to whom the Software is
 *      furnished to do so, subject to the following conditions:
 *      
 *      The above copyright notice and this permission notice shall be included in
 *      all copies or substantial portions of the Software.
 *      
 *      THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *      IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *      FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *      AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *      LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *      OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *      THE SOFTWARE.
 *      
 */

class SteamAPIException extends Exception { }
class SteamAPI {
	
	private $customURL;
	private $steamID64;
	private $gameList;
	
	function version() {
		return (float) '0.1';
	}
	
	/**
	 *  Sets the $steamID64 or CustomURL then retrieves the profile.
	 * @param int $id
	 * */
	function __construct($id) {
		if(is_numeric($id)) {
			$this->steamID64 = $id;
		} else {
			$this->customURL = strtolower($id);
		}
        
            if( !file_exists('./profiles/steamprofile.'.$id.'.cache.xml') || (time() - filemtime('./profiles/steamprofile.'.$id.'.cache.xml')) > 160 )
        {
            $url = $this->baseUrl() . "/?xml=1";
            if( ini_get('allow_url_fopen') == '1' )
            {
                $feed = file_get_contents($url);
                file_put_contents('./profiles/steamprofile.'.$id.'.cache.xml',$feed);
                
            }
            else
            {
                if( function_exists('curl_init') )
                {
                    $ch = curl_init();
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch,CURLOPT_HEADER,0);
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                    $feed = curl_exec($ch);
                    curl_close($ch);
                    file_put_contents('./profiles/steamprofile.'.$id.'.cache.xml',$feed);  
                }
                else
                {
                    return false;
                }
            }
        }
        
        
        if( function_exists('simplexml_load_file') || $id )
        {
            libxml_use_internal_errors(true);
            $xml = simplexml_load_file('./profiles/steamprofile.'.$id.'.cache.xml');
            if( !$xml )
            {
                foreach( libxml_get_errors() as $error )
                {
                    @file_put_contents('./profiles/steamprofile.log.txt',date('[d/m/Y H:i.s]').' '.$error."\n",FILE_APPEND);
                }
                @file_put_contents('./profiles/steamprofile.log.txt',"\n\n",FILE_APPEND);
                return false;
            }
		
            $this->steamID64= (string) $xml->steamID64;
            $this->friendlyName  = (string) $xml->steamID;

            $this->onlineState = (string) $xml->onlineState;
            $this->stateMessage = (string) $xml->stateMessage;
            
            $this->privacyState = (string) $xml->privacyState;
            $this->visibilityState = (int) $xml->visibilityState;
            
            $this->avatarIcon = (string) $xml->avatarIcon;
            $this->avatarMedium = (string) $xml->avatarMedium;
            $this->avatarFull = (string) $xml->avatarFull;
            
            $this->vacBanned = (bool) $xml->vacBanned;
	

            if($this->privacyState == "public") {
                $this->customUrl = strtolower((string) $xml->customURL);
                
                $this->memberSince = (string) $xml->memberSince;
                $this->steamRating = (float) $xml->steamRating;
                $this->location = (string) $xml->location;
                $this->realName = (string) $xml->realname;
                
                $this->hoursPlayed2Wk = (float) $xml->hoursPlayed2Wk;
                
                $this->favoriteGame = (string) $xml->favoriteGame->name;
                $this->favoriteGameHoursPlayed2Wk = (string) $xml->favoriteGame->hoursPlayed2wk;
                
                $this->headLine = (string) $xml->headline;
                $this->summary = (string) $xml->summary;
            }
            
            if(!empty($xml->weblinks)) {
                foreach($xml->weblinks->weblink as $link) {
                    $this->weblinks[(string) $link->title] = (string) $link->link;
                }
            }
        }
        else
        {
            return false;
        }        
        //$this->loadProfile();        
    }
	/**
	 *  Creates and then returns the url to the profiles.
	 *  @return string
	 * */
	function baseUrl() {
		if(empty($this->customURL)) {
			return "http://steamcommunity.com/profiles/{$this->steamID64}";
		}
		else {
			return "http://steamcommunity.com/id/{$this->customURL}";
		}
	}
    
 
    
        

            
	
	       
        
	/**
	 *  Retrieves all of the cached information found on the profile.
	 * */
    function loadProfile() {

	
    }
	
	/**
	 *  If there are no games in the variable it calls the retrieveGames() function, upon completion returns an array of all of the owned games and related information
	 *  @return array
	 * */
	function getGames() {
		if(empty($this->gameList)) {
			$this->retrieveGames();
		}
		return $this->gameList;
	}
	
	/**
	 *  Returns the friendly name of the user. The one seen by all friends & visitors.
	 *  @return string
	 * */
	function getFriendlyName() {
		return $this->friendlyName;
	}
	
	/**
	 *  Returns the users current state. (online,offline)
	 *  @return string
	 * */
	function onlineState() {
        return $this->onlineState;
    }
    
    /**
	 *  Returns the state message of the user (EG: "Last Online: 2 hrs, 24 mins ago", "In Game <br /> Team Fortress 2")
	 *  @return string
	 * */
    function getStateMessage() {
		return $this->stateMessage;
	}
    
    /**
	 *  Returns the users Vac status. 0 = Clear, 1 = Banned
	 *  @return boolean
	 * */
    function isBanned() {
        return $this->vacBanned;
    }
    
    /**
	 *  Returns a link to the small sized avatar of the user (32x32)
	 *  @return string
	 * */
    function getAvatarSmall() {
		return $this->avatarIcon;
	}
	
	/**
	 *  Returns a link to the medium sized avatar of the user (64x64)
	 *  @return string
	 * */
	function getAvatarMedium() {
		return $this->avatarMedium;
	}
	
	/**
	 *  Returns a link to the full sized avatar of the user
	 *  @return string
	 * */
	function getAvatarFull() {
		return $this->avatarLarge;
	}
	
	/**
	 *  Returns the Steam ID of the user
	 *  @return int
	 * */
	function getSteamID64() {
		return $this->steamID64;
	}
	
	/**
	 *  Returns the total amount of games owned by the user
	 *  @return int
	 * */
	function getTotalGames() {
		return sizeof($this->gameList);
	}
    }


?>
