<?php

/**
* Распечатка массива
**/
function print_arr($array){
    echo "<pre>" . print_r($array, true) . "</pre>";
}

/**
* Получение массива категорий
**/
function get_cat(){
    global $connection;
    $query = "SELECT * FROM categories ORDER BY id";
    $res = mysqli_query($connection, $query) or die(mysqli_error($connection));

    $arr_cat = array();
    while($row = mysqli_fetch_assoc($res)){
        $arr_cat[$row['id']] = $row;
    }
    return $arr_cat;
}

/**
* Построение дерева
**/
function map_tree($dataset) {
    $tree = array();

    foreach ($dataset as $id=>&$node) {
        if (!$node['parent']){
            $tree[$id] = &$node;
        }else{
            $dataset[$node['parent']]['childs'][$id] = &$node;
        }
    }

    return $tree;
}

/**
* Дерево в строку HTML
**/
function categories_to_string($data){
    foreach($data as $item){
        $string .= categories_to_template($item);
    }
    return $string;
}

/**
* Шаблон вывода категорий
**/
function categories_to_template($category){
    ob_start();
    include 'inc/category_template.php';
    return ob_get_clean();
}

/**
 * Получение категорий для вывода
 */
function get_categories_output(){
    $categories = get_cat();
    $categories_tree = map_tree($categories);
    return categories_to_string($categories_tree);
}

/**
 * Добавление категории
 */
function add_cat($parent) {
    global $connection;

    // ID родителя
    $parent = intval($parent);

    // Проверка на кол-во потомков у родителя
    $query = "SELECT COUNT(*) as count FROM categories WHERE parent = $parent";
    $res = mysqli_query($connection,$query) or die(mysqli_error($connection));
    if (mysqli_fetch_assoc($res)['count'] >= 2) {
        return false;
    }

    // Добавление категории
    $query = "INSERT INTO categories (parent) VALUES ($parent)";
    $res = mysqli_query($connection,$query) or die(mysqli_error($connection));

    if (mysqli_affected_rows($connection)) {
        return mysqli_insert_id($connection);
    }

    return false;
}

/**
 * Удаление категории
 */
function del_cat($category_id){
    global $connection;

    // ID категории для удаления
    $category_id = intval($category_id);
    if(!$category_id) {
        return false;
    }

    // Получаем дочерние категории
    $query = "SELECT id FROM categories WHERE parent = $category_id";
    $res = mysqli_query($connection,$query) or die(mysqli_error($connection));

    // Удаляем дочерние категории
    while($row = mysqli_fetch_assoc($res)){
        del_cat($row['id']);
    }

    // Удаляем категорию $id
    $query = "DELETE FROM categories WHERE id=$category_id";
    mysqli_query($connection, $query) or die(mysqli_error($connection));

    return true;
}
