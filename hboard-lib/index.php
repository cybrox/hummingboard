<?php
  
  /**
   * Hummingbird Library Boards
   * Display your whole Hummingbird library on a single page
   *
   * Written and copyrighted 2014+
   * by Sven Marc 'cybrox' Gehring
   *
   * Licensed under Hugware license!
   * http://dev.cybrox.eu/hugware/
   */

  $link = explode('/', $_SERVER['REQUEST_URI']);
  $user = (!empty($link[1])) ? $link[1] : '';
  $type = (!empty($link[2])) ? strtolower($link[2]) : 'all';

  $status = array('completed', 'onhold', 'dropped', 'plantowatch', 'currentlywatching');
  $library = array();
  $anime = array();

  if(!preg_match("#[A-z0-9]{3,30}#", $user))  $user = 'cybrox';
  if(!in_array($type, $status)) $type = 'all';
  
  $hbd = @file_get_contents('http://hummingbird.me/library_entries?user_id='.$user);
  $lib = @json_decode($hbd, true);

  if(gettype($lib) != 'array') die('Something went wrong, are you sure that '.$user.' is a valid username?');

  foreach($lib['anime'] as $a){
    $anime[$a['id']] = array(
      'title' => $a['canonical_title'],
      'image' => $a['poster_image'],
      'link' => "http://hummingbird.me/anime/".$a['id']
    );
  }

  foreach($lib['library_entries'] as $entry) {
    if($type != 'all'){
      if(str_replace(" ", "", strtolower($entry['status'])) != $type) continue;
    }

    array_push($library, $anime[$entry['anime_id']]);
  }

  shuffle($library);

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  
  <meta name="author" content="cybrox" />
  <meta name="copyright" content="2014+ cybrox" />
  
  <meta name="language" content="en" />

  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" />
  <style type="text/css">
    body {
      background: #111;
      margin: 0;
      padding: 0 20px;
    }

    .row { margin: 20px 0; }

    .panel {
      margin: 0;
      padding: 0;
      border: none;
    }

    .panel-heading {
      background-color: #141414 !important;
      border-bottom: none;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      color: #999 !important;
    }

    .panel-body { background-color: #121212 !important; }
    .panel-body img:hover { opacity: 1; }
    .panel-body img {
      width: 100%;
      opacity: 0.9;
      border-radius: 3px;
      -moz-border-radius: 3px;
      -webkit-border-radius: 3px;
    }

    .panel-body img, .panel-body img:hover {
      transition: opacity 0.2s ease-in-out;
      -moz-transition: opacity 0.2s ease-in-out;
      -webkit-transition: opacity 0.2s ease-in-out;
    }
  </style>
  
  <title><?php echo $user.'\'s'; ?> Hummingbird Library</title>
  
</head>
<body>
  <section id="anime-field" class="clearfix">
    <div class="row">
      <?php

        $row = 0;

        foreach($library as $a){
          $row++;
          if($row >= 7){
            echo '</div><div class="row">';
            $row = 1;
          }
          echo '
            <div class="col-md-2">
              <div class="panel panel-default">
                <div class="panel-heading">'.$a['title'].'</div>
                <div class="panel-body"><a href="'.$a['link'].'" target="_blank"><img src="'.$a['image'].'" /></a></div>
              </div>
            </div>
          ';
        }

      ?>
    </div>
  </section>
</body>
</html>