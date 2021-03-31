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



require_once (dirname(dirname(dirname(__FILE__)))."/config.php"); //search for config.php
require_once ($CFG->dirroot."/local/rsocial/forms.php"); //search the file where we have our forms 


 global $DB, $PAGE, $OUTPUT, $USER;

$context = context_system::instance();
$url = new moodle_url("/local/rsocial/home.php");
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("title", "local_rsocial"));
$PAGE->set_heading(get_string("heading", "local_rsocial"));

// ^ Standar for moodle plug-ins

require_login();
if (isguestuser()){
	die(redirect("http://localhost/moodle/login/index.php"));
}

if (isset($_POST['Liked'])){
    $liker_id = $USER->id;
    $post_id =  $_POST['Liked'];
    if($DB->get_records("local_likes",array("liker_id" =>$liker_id, "post_id" => $post_id )) == array()){
        $object = new stdClass();
        $object->liker_id = $liker_id;
        $object->post_id = $post_id;
        $DB->insert_record("local_likes", $object);
    }else{
        $DB->delete_records("local_likes",array("liker_id" =>$liker_id, "post_id" => $post_id));
    }
}


echo $OUTPUT->header();






echo "<b>", get_string("welcome","local_rsocial"),"</b>","<br><br>";

echo "
<div class ='a'>
    <a href = 'upload.php' style = 'float:right; margin-right: 5px'> <button class = 'btn btn-primary'>",get_string("upload","local_rsocial") ," </button> </a>
    <a href = 'my_profile.php' style = 'float:right; margin-right: 5px'> <button class = 'btn btn-primary'>",get_string("profile","local_rsocial") ,"</button> </a> 
    

</div><br><br>  
";



#get all uploaded files
$files = $DB->get_records("local_rsocial");


foreach (array_reverse($files) as $file) {
    $url = moodle_url::make_pluginfile_url(context_system::instance()->id, "local_rsocial", "photos", 0, "/", "$file->filename");
    $by =  get_string("by","local_rsocial");
    $likecount = $DB->count_records('local_likes',array('post_id'=>$file->id));
    if($DB->get_records("local_likes",array("liker_id" =>$USER->id, "post_id" => $file->id)) == array()){
        $liked = 'https://i.imgur.com/rMi0n2j.png';
    }else{
        $liked = 'https://i.imgur.com/nK6kEtf.png';
    }

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


             <form action=home.php method = post>
             <button class= 'btn btn-outline-primary' ; type = submit ; value = '$file->id'; name = Liked > 
             <img src=$liked width = 40px> </button> = $likecount
             </form>
             


        </div>

    </div>  
        
      <br><br>  ";
}   


echo $OUTPUT->footer();

