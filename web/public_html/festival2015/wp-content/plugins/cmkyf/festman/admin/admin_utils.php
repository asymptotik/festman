<?php

function fmForward($forward)
{
    header("Location: " . $forward);
}

function fmClearMessages()
{
    unset($_SESSION['error_message']);
    unset($_SESSION['action_message']);
    unset($_SESSION['progress_message']);
}

function fmHandleMultipleUploadedFiles($dest_dir, $overwrite)
{
    echo "fmHandleMultipleUploadedFiles: filecount: " . count($_FILES["file"]["name"]);
    for ($i = 0; $i < count($_FILES["file"]["name"]); $i++)
    {
        if ($_FILES["file"]["error"][$i] > 0)
        {
            $uploadErrors = array(
                UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
                UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. It is likely that you are attempting to upload a file larger than your server would allow.',
                UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
            );

            $errorCode = $_FILES["file"]["error"][$i];

            $ret['name'][$i] = $_FILES["file"]["name"][$i];
            $ret['error'][$i] = true;

            if (isset($uploadErrors[$errorCode]))
                $ret['message'][$i] = "Error Uploading: " . $uploadErrors[$errorCode];
            else
                $ret['message'][$i] = "Error Code: " . $errorCode;
        }
        else if (($_FILES["file"]["type"][$i] != "image/gif") &&
                ($_FILES["file"]["type"][$i] != "image/jpeg") &&
                ($_FILES["file"]["type"][$i] != "audio/mpeg") &&
                ($_FILES["file"]["type"][$i] != "video/mpeg") &&
                ($_FILES["file"]["type"][$i] != "application/pdf") &&
                ($_FILES["file"]["type"][$i] != "image/png"))
        {
            $ret['name'][$i] = $_FILES["file"]["name"][$i];
            $ret['error'][$i] = true;
            $ret['message'][$i] = "Error: file '" . $dest_dir . $_FILES["file"]["name"][$i] . "' is not a gif, jpeg, png, mp3 or mpeg.";
        }
        else if ($_FILES["file"]["size"][$i] > 24000000)
        {
            $ret['name'][$i] = $_FILES["file"]["name"][$i];
            $ret['error'][$i] = true;
            $ret['message'][$i] = "Error: file '" . $dest_dir . $_FILES["file"]["name"][$i] . "' is too large to upload. Max size is 2000000";
        }
        else
        {
            $action_text = "Upload: " . $_FILES["file"]["name"][$i] . "<br />" .
                    "Type: " . $_FILES["file"]["type"][$i] . "<br />" .
                    "Size: " . ($_FILES["file"]["size"][$i] / 1024) . " Kb<br />" .
                    "Temp File: " . $_FILES["file"]["tmp_name"][$i];

            if ($overwrite == false && file_exists($dest_dir . $_FILES["file"]["name"][$i]))
            {
                $ret['name'][$i] = $_FILES["file"]["name"][$i];
                $ret['error'][$i] = true;
                $ret['message'][$i] = "Error: file '" . $_FILES["file"]["name"][$i] . "' already exists. ";
            }
            else if (file_exists($dest_dir) == false)
            {
                $ret['name'][$i] = $_FILES["file"]["name"][$i];
                $ret['error'][$i] = true;
                $ret['message'][$i] = "File: '" . $_FILES["file"]["name"][$i] .
                        "' could not be uploaded successfully. The destination directory '" . $dest_dir . "' does not exist. " .
                        "To solve the problem create the directory on the server and try again.";
            }
            else
            {
                if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], $dest_dir . $_FILES["file"]["name"][$i]) == true)
                {
                    $ret['name'][$i] = $_FILES["file"]["name"][$i];
                    $ret['error'][$i] = false;
                    $ret['message'][$i] = "File: '" . $_FILES["file"]["name"][$i] . "' uploaded successfully.";
                }
                else
                {
                    $ret['name'][$i] = $_FILES["file"]["name"][$i];
                    $ret['error'][$i] = true;
                    $ret['message'][$i] = "Error: Failed to store file '" . $dest_dir . $_FILES["file"]["name"][$i] . " successfully.";
                }
            }
        }
    }

    return $ret;
}
?>