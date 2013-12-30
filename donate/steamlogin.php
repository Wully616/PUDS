<?php
/**
 * Steam Login
 * ----------------------------------
 * Provided with no warranties by Ryan Stewart (www.calculator.tf)
 * This has been tested on MyBB 1.6
 */
class steam {

    // You can get an API key by going to http://steamcommunity.com/dev/apikey
    public $API_KEY = "F14739CB25F0A9C0651D1111EF2D6DAE";

    function __construct() {
        global $db;

        $get_key = $db->fetch_array($db->simple_select("settings", "name, value", "name = 'steamlogin_api_key'"));
        $this->API_KEY = $get_key['value'];
    }
    
    function curl($url)
    {
        if(function_exists('curl_version'))
        {
            $ch = curl_init();
            curl_setopt_array($ch, array(CURLOPT_URL => $url, CURLOPT_HEADER => false, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10));
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;

        } else { // if(function_exists('curl_version'))

            if (function_exists('fopen') && ini_get('allow_url_fopen'))
            {
                $context = stream_context_create( array(
                    'http'=>array(
                      'timeout' => 10.0
                    )
                  ));
                $handle = @fopen($url, 'r', false, $context);
                $file = @stream_get_contents($handle);
                @fclose($handle);
                return $file;

            } else {
                    if(!function_exists('fopen') && ini_get('allow_url_fopen')){
                        die("cURL and Fopen are both disabled. Please enable one or the other. cURL is prefered.");
                    } elseif(function_exists('fopen') && !ini_get('allow_url_fopen')){
                            die("cURL is disabled and Fopen is enabled but 'allow_url_fopen' is disabled(means you can not open external urls). Please enabled one or the other.");
                    } else {
                            die("cURL and Fopen are both disabled. Please enable one or the other. cURL is prefered.");
                    }
            }

        } // close else
    } // close function curl
        
    /**
     * get_user_info
     *-------------------------------------
     * This will return information about the Steam user
     * including their avatar, persona and online status.
     */
        function get_user_info($id = '') {

        // Resolve our ID.
                $id = $this->_resolve_vanity($id);

        if($id['status'] == 'success')
        {

                    $info_array = $this->curl('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$this->API_KEY.'&steamids='.$id['steamid']);
                    $info_array = json_decode($info_array, true);

            if(isset($info_array['response']['players'][0])) 
            {

                $player_info = $info_array['response']['players'][0];

                $personaname = $player_info['personaname'];
                $profileurl = $player_info['profileurl'];
                $avatar = $player_info['avatarfull'];
                $personastate = $player_info['personastate'];

                $return_array = array(
                    'status' => 'success',
                    'steamid' => $id['steamid'],
                    'personaname' => $personaname,
                    'profileurl' => $profileurl,
                    'avatar' => $avatar,
                    'personastate' => $personastate
                );

            } else {

                $return_array = array(
                    'status' => 'error',
                    'message' => 'An error occured retrieving user information from the Steam service.'
                );

            } // close else

        } elseif($id['status'] == 'error')
        {

            $return_array = array(
                'status' => 'error',
                'message' => $id['message']
            );

        } // close elseif($id['status'] == 'error')

        return $return_array;

        } // close get_user_info

?>