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

require_once("../../config.php");
require_once("$CFG->libdir/formslib.php");

class photo extends moodleform
{
    public function definition()
    {
        $mform = $this->_form;

        #the second parameter is the name of the file uploaded
        $mform->addElement('filepicker', 'photo', 'File to upload',null,array("accepted_types" => array(".png",".jpg",".jpeg")));
        $mform->setType('photo', PARAM_FILE);
        
        $mform->addElement('text', 'name', 'Name');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('text', 'caption', 'Caption');
        $mform->setType('caption', PARAM_TEXT);

        $this->add_action_buttons();
    }
}
