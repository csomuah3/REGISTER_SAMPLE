<?php
require_once __DIR__ . '/../classes/category_class.php';

function add_category_ctr(string $name): array
{
    $c = new Category();
    return $c->add_category($name);
}

function get_categories_ctr(): array
{
    $c = new Category();
    return $c->get_categories();
}

function get_category_ctr(int $cat_id): array
{
    $c = new Category();
    return $c->get_category($cat_id);
}

function get_user_categories_ctr(int $user_id): array
{
    $c = new Category();
    return $c->get_user_categories($user_id);
}

function update_category_ctr(int $cat_id, string $name): array
{
    $c = new Category();
    return $c->update_category($cat_id, $name);
}

function delete_category_ctr(int $cat_id): array
{
    $c = new Category();
    return $c->delete_category($cat_id);
}
