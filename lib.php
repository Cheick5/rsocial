<?php

#this function handles file urls
function local_rsocial_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    $itemid = array_shift($args); // The first item in the $args array.
    $filename = array_pop($args); // The last item in the $args array.

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_rsocial', $filearea, $itemid, "/", $filename);

    if (!$file) {
        return false; // The file does not exist.
    }

    send_stored_file($file);
}

