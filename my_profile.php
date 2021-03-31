<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 *
 * @package    local
 * @subpackage rsocial
 * @copyright  Nicolás Soto León    
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_rss_client\task\refreshfeeds;

require_once (dirname(dirname(dirname(__FILE__)))."/config.php"); //search for config.php
require_once ($CFG->dirroot."/local/rsocial/forms.php"); //search the file where we have our forms 


 global $DB, $PAGE, $OUTPUT, $USER;

$context = context_system::instance();
$url = new moodle_url("/local/rsocial/my_profile.php");
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("title", "local_rsocial"));
$PAGE->set_heading(get_string("heading", "local_rsocial"));
// $sql = "select * from {user_info_data} where " . $DB->sql_compare_text('data') . " = ? ";

// ^ Standar for moodle plug-ins




require_login();
if (isguestuser()){
	die(redirect("http://localhost/moodle/login/index.php"));
}


if (isset($_POST['Delete'])){
    $DB->delete_records("local_rsocial",array("id"=>$_POST['Delete']));
    $file_erased = \core\notification::add(get_string("photo_deleted", "local_rsocial"),\core\output\notification::NOTIFY_SUCCESS);
    echo "<p>$file_erased</p>";
}

echo $OUTPUT->header();




echo "<b>", get_string("profile","local_rsocial"),"</b>","<br><br>";

echo "
<div class ='a'>
    <a href = 'upload.php' style = 'float:right; margin-right: 5px'> <button class = 'btn btn-primary'>",get_string("upload","local_rsocial") ," </button> </a>
    <a href = 'home.php' style = 'float:right; margin-right: 5px'> <button class = 'btn btn-primary'>",get_string("home","local_rsocial") ,"</button> </a> 
    

</div><br><br>  
";

$myid = $USER->id;
$myfiles = $DB->get_records("local_rsocial",array("uploaderid" => $myid));


foreach (array_reverse($myfiles) as $file) {
    $url = moodle_url::make_pluginfile_url(context_system::instance()->id, "local_rsocial", "photos", 0, "/", "$file->filename");
    $by =  get_string("by","local_rsocial");
    $likecount = $DB->count_records('local_likes',array('post_id'=>$file->id));

    
    echo "<p style= 'text-align:center'> <a href='$url'> $file->photoname </a> $by $file->uploadername $file->date</p>";

    echo "  
    <head>
        <link rel='stylesheet' href='styles.css'/>
    </head>
    
    <div class='flex-container'>

        <div>
            <img src=$url width = '500'>
        </div>
        

        <div>
             $file->caption         
        </div>


        <div>
            
            <img  
            src=https://i.imgur.com/rMi0n2j.png
            width='40'> 
             = $likecount

            <form action=my_profile.php method = post>
                <button class= 'btn btn-outline-primary' ; type = submit ; value = '$file->id'; name = Delete > 
                <img src=https://i.imgur.com/7RnNmCv.png width = 40px> </button>
            </form>

        </div>

    </div>  
        
      <br><br>  ";
}   




echo $OUTPUT->footer();

