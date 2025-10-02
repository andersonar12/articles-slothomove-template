<?php
function add_page_data_script() {
    if (!is_singular()) return; // Solo para páginas y posts
    
    // Asegurar que jQuery esté cargado
    wp_enqueue_script('jquery');
    
    $author_id = null;
    $author_name = '';
    
    if (isset($_GET['elementor-preview']) && is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;
        $author_name = $current_user->display_name;
    } else {
        // Para páginas, obtener el autor de manera diferente
        $post = get_post();
        if ($post) {
            $author_id = $post->post_author;
            $author_name = get_the_author_meta('display_name', $author_id);
            
            // Si no hay nombre de display, usar el login
            if (empty($author_name)) {
                $author_name = get_the_author_meta('user_login', $author_id);
            }
        }
    }
    
    // Si aún no hay autor, usar el admin por defecto
    if (empty($author_id)) {
        $admin_users = get_users(array('role' => 'administrator', 'number' => 1));
        if (!empty($admin_users)) {
            $author_id = $admin_users[0]->ID;
            $author_name = $admin_users[0]->display_name;
        }
    }
    
    $avatar_url = get_avatar_url($author_id, array('size' => 32, 'default' => 'mystery'));
    $reading_time = get_post_meta(get_the_ID(), 'reading_time', true);
    $badge_text = get_post_meta(get_the_ID(), 'article_badge_text', true);
    
    // Debug: añadir datos para verificar
    $debug_info = array(
        'author_id' => $author_id,
        'post_author' => get_post()->post_author ?? 'none',
        'current_user_id' => get_current_user_id(),
        'is_preview' => isset($_GET['elementor-preview'])
    );
    
    $script = '
        window.pageData = {
            id: ' . json_encode(get_the_ID()) . ',
            author: ' . json_encode($author_name) . ',
            avatar: ' . json_encode($avatar_url) . ',
            date: ' . json_encode(get_the_date('j M Y')) . ',
            modified: ' . json_encode(get_the_modified_date('j M Y')) . ',
            readingTime: ' . json_encode($reading_time) . ',
            badgeText: ' . json_encode($badge_text) . ',
            debug: ' . json_encode($debug_info) . '
        };
    ';
    
    wp_add_inline_script('jquery', $script);
}
add_action('wp_enqueue_scripts', 'add_page_data_script');
?>