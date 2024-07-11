<?php 

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/css/theme.css', array(), filemtime(get_stylesheet_directory() . '/css/theme.css'));
}

function register_my_menus() {
    register_nav_menus(
        array(
            'primary' => __('Primary Menu'),
            'footer' => __('Footer Menu')
        )
    );
}
add_action('init', 'register_my_menus');

// Ajout des classes personnalisées aux éléments du menu
function add_additional_class_on_li($classes, $item, $args) {
    if ($args->theme_location == 'primary') {
        if ($item->title == 'Nous rencontrer') {
            $classes[] = 'menu-nous-rencontrer';
        } elseif ($item->title == 'Admin') {
            $classes[] = 'menu-admin';
        } elseif ($item->title == 'Commander') {
            $classes[] = 'menu-commander';
        }
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

// Affiche le lien "Admin" uniquement pour les utilisateurs connectés.

function show_admin_link_for_logged_in_users($items, $args) {
    if ($args->theme_location == 'primary') {
        foreach ($items as $key => $item) {
            if ($item->title == 'Admin') {
                if (!is_user_logged_in()) {
                    unset($items[$key]);
                } else {
                    // Changer l'URL du lien "Admin" pour pointer vers le tableau de bord d'administration
                    $item->url = admin_url();
                }
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'show_admin_link_for_logged_in_users', 10, 2);

?>