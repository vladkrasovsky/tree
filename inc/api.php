<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'add_cat':
            if (!add_cat($_POST['parent'])) {
                http_response_code(500);
                exit;
            }
            echo get_categories_output();
            break;
        case 'del_cat':
            if (!del_cat($_POST['id'])) {
                http_response_code(500);
                exit;
            }
            echo get_categories_output();
            break;
    }
    exit;
}
