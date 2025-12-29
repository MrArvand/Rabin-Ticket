<?php

$code_p = $_SESSION['code_p'];
$selected_avatar = str_p('selected_avatar');

// List of allowed predefined avatars
$allowed_avatars = ['karbar', 'maryam', 'mobina', 'morteza', 'sara', 'user1', 'user2', 'user3', 'user4', 'user5'];

// Validate that selected avatar is in the allowed list
if (empty($selected_avatar) || $selected_avatar == "0" || !in_array($selected_avatar, $allowed_avatars)) {
    header("location: ?page=profile&p=n");
    exit;
}

// Verify the avatar file exists
$avatar_path = "assets/images/" . $selected_avatar . ".png";
if (!file_exists($avatar_path)) {
    header("location: ?page=profile&p=n");
    exit;
}

// Update avatar in database
$Qery = "UPDATE `karbar` SET `avatar` = '$selected_avatar' WHERE `code_p` = '$code_p';";

if (mysqli_query($Link, $Qery)) {
    // Update session
    $_SESSION['avatar'] = $selected_avatar;
    header("location: ?page=profile&p=y");
} else {
    header("location: ?page=profile&p=n");
}

?>
