<?php

$name_k  = str_p('name_k');
$code_k  = str_p('code_k');
$semat_k = str_p('semat_k');
$tel_k   = str_p('tel_k');
$email_k = str_p('email_k');
$passs   = str_p('passs');
$targetSherkatCode = str_p('target_sherkat');

// Get company name
$q = mysqli_query(
    $Link,
    "SELECT name FROM sherkatha WHERE code='$targetSherkatCode' LIMIT 1"
);
$rowSherkat = mysqli_fetch_assoc($q);
$targetSherkatName = $rowSherkat ? $rowSherkat['name'] : '';

if ($name_k !== "" && $code_k !== "") {

    // -----------------------------------------
    // Duplicate check with details
    $checkQuery = "
        SELECT code_p, email, tel
        FROM karbar
        WHERE code_p = '$code_k'
           OR email  = '$email_k'
           OR tel    = '$tel_k'
        LIMIT 1
    ";

    $checkResult = mysqli_query($Link, $checkQuery);

    if ($dup = mysqli_fetch_assoc($checkResult)) {

        if ($dup['code_p'] === $code_k) {
            header("location: ?page=setting&p=dup_code");
            exit;
        }

        if ($email_k !== "" && $dup['email'] === $email_k) {
            header("location: ?page=setting&p=dup_email");
            exit;
        }

        if ($tel_k !== "" && $dup['tel'] === $tel_k) {
            header("location: ?page=setting&p=dup_tel");
            exit;
        }

        // fallback (should never happen)
        header("location: ?page=setting&p=duplicate");
        exit;
    }

    // -----------------------------------------
    // Insert
    $cooode_k = "T-" . time() . "-" . rand(11, 99);

    $insertQuery = "
        INSERT INTO karbar
        (name, code_p, kind, code_karbar, semat, tel, email, vaziat, pass,
         name_sherkat, code_sherkat, kind_daste, avatar, i_karbar, let, gozaresh)
        VALUES
        ('$name_k', '$code_k', 'poshtiban', '$cooode_k', '$semat_k',
         '$tel_k', '$email_k', 'y', '$passs',
         '$targetSherkatName', '$targetSherkatCode',
         'مدیران', 'morteza', NULL, '', '')
    ";

    if (mysqli_query($Link, $insertQuery)) {
        header("location: ?page=setting&p=y");
    } else {
        header("location: ?page=setting&p=n");
    }
} else {
    header("location: ?page=setting&p=n");
}
