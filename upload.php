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


 global $DB, $PAGE, $OUTPUT, $USER, $FS;

$context = context_system::instance();
$url = new moodle_url("/local/rsocial/upload.php");
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_pagelayout("standard");
$PAGE->set_title(get_string("title", "local_rsocial"));
$PAGE->set_heading(get_string("upload", "local_rsocial")); 




require_login();
if (isguestuser()){
	die(redirect("http://localhost/moodle/login/index.php"));
}



echo $OUTPUT->header();






$phform = new photo();

if ($data = $phform->get_data()) {

  $extension = pathinfo($phform->get_new_filename('photo'), PATHINFO_EXTENSION);
  $date = date("d-m-Y H:s:i");
  $dateunix = strtotime($date);
  $file_name = "$USER->id-$dateunix.$extension";
  $result = $phform->save_stored_file("photo", $context->id, "local_rsocial", "photos", 0, "/", $file_name);
  $photoname = $data->name;
  $caption = $data->caption;
  if ($result){
    $file_uploaded = \core\notification::add(get_string("good_upload", "local_rsocial"),\core\output\notification::NOTIFY_SUCCESS);
        echo "<p>$file_uploaded</p>";

        #we create a new entry in the db, storing the user who uploaded the thing and the file name
        $object = new stdClass();
        $object->uploaderid = $USER->id;
        $object->filename = $file_name;
        $object->date = $date;
        $object->uploadername = $USER->username;
        $object->photoname = $photoname;
        $object->caption = $caption;
        $DB->insert_record("local_rsocial", $object);
    }
  }
else if ($data = $phform->is_cancelled()){
  redirect("home.php");
}

echo "
<div class ='a'>
    <a href = 'my_profile.php' style = 'float:right; margin-right: 5px'> <button class = 'btn btn-primary'>",get_string("profile","local_rsocial") ,"</button> </a>
    <a href = 'home.php' style = 'float:right; margin-right: 5px'> <button class = 'btn btn-primary'>",get_string("home","local_rsocial") ," </button> </a>
     
    

</div>
";

$phform->display();




echo $OUTPUT->footer();