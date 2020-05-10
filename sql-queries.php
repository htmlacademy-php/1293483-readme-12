<?php
function get_sql_content_types($connection): array {
    $sql_content_types = 'SELECT id, title, class_name FROM content_types';
    $query_result = fetch($connection, $sql_content_types);

    return mysqli_fetch_all($query_result, MYSQLI_ASSOC);
} //запрос на получение типов только существующих постов SELECT c.id, c.title, c.class_name FROM content_types c INNER JOIN posts p ON c.id = p.content_type_id GROUP BY c.id

function get_sql_posts_filters($connection, array $params, string $sort_field = 'show_count', string $sorting_order = 'desc', int $limit = 6): array {
    $sorting_order = mb_strtolower($sorting_order) === 'asc' ? 'ASC' : 'DESC';
    $sort_field = in_array($sort_field, ['show_count', 'dt_add', 'likes_count']) ? $sort_field : 'show_count';

    $sql_posts = "SELECT p.id, p.dt_add, p.title, p.content, p.quote_author, p.photo, p.video, p.link, u.login, u.avatar, ct.class_name,
                (SELECT COUNT(*) FROM likes WHERE p.id = likes.post_id) AS likes_count,
                (SELECT COUNT(*) FROM comments WHERE p.id = comments.post_id) AS comments_count
                FROM posts p
                INNER JOIN users u ON p.user_id = u.id
                INNER JOIN content_types ct ON p.content_type_id = ct.id";

    if (count($params) > 0) {
        $sql_posts .= " WHERE " . implode(' AND ', array_keys($params));
    }

    $sql_posts .= " ORDER BY $sort_field $sorting_order LIMIT $limit";

    return fetch_all($connection, $sql_posts, $params);
}

function get_sql_post($connection, int $post_id): ?array {
    $sql_post = "SELECT p.id, p.dt_add, p.title, p.content, p.quote_author, p.photo, p.video, p.link, p.show_count, ct.class_name, p.user_id,
                (SELECT COUNT(*) FROM likes WHERE p.id = likes.post_id) AS likes_count,
                (SELECT COUNT(*) FROM comments WHERE p.id = comments.post_id) AS comments_count
                FROM posts p
                INNER JOIN content_types ct ON p.content_type_id = ct.id 
                WHERE p.id = ?";

    return fetch_assoc($connection, $sql_post, [$post_id]);
}

function get_sql_comments($connection, int $post_id, int $limit = 100): array {
    $sql_comments = "SELECT c.id, c.dt_add, c.content, u.login, u.avatar FROM comments c
                INNER JOIN users u ON c.user_id = u.id
                WHERE c.post_id = ? 
                ORDER BY c.dt_add DESC LIMIT $limit";

    return fetch_all($connection, $sql_comments, [$post_id]);
}

function get_sql_comments_count($connection, int $post_id): int {
    $sql_comments = "SELECT COUNT(c.id) AS count FROM comments c
                WHERE c.post_id = ?";

    return fetch_assoc($connection, $sql_comments, [$post_id])['count'];
}

function get_sql_user($connection, int $user_id): array {
    $sql_user = "SELECT u.id, u.dt_add, u.login, u.avatar,
                (SELECT COUNT(*) FROM subscriptions WHERE u.id = subscriptions.author_id) AS follower_count,
                (SELECT COUNT(*) FROM posts WHERE u.id = posts.user_id) AS posts_count
                FROM users u
                WHERE u.id = ?";

    return fetch_assoc($connection, $sql_user, [$user_id]);
}

function create_sql_post($connection, $content_type, $post) {
    $sql_post = 'INSERT INTO posts (dt_add, title, user_id, content_type_id';

    switch ($content_type) {
        case 'photo':
            $sql_post .= ', photo) VALUES (NOW(), ?, 2, ?, ?)';
            break;
        case 'video':
            $sql_post .= ', video) VALUES (NOW(), ?, 1, ?, ?)';
            break;
        case 'text':
            $sql_post .= ', content) VALUES (NOW(), ?, 3, ?, ?)';
            break;
        case 'quote':
            $sql_post .= ', content, quote_author) VALUES (NOW(), ?, 2, ?, ?, ?)';
            break;
        case 'link':
            $sql_post .= ', link) VALUES (NOW(), ?, 3, ?, ?)';
            break;
    }

    $post_without_hashtags = $post;
    unset($post_without_hashtags['hashtags']);

    get_prepare_stmt($connection, $sql_post, $post_without_hashtags);
    $post_id = mysqli_insert_id($connection);
    create_sql_hashtags($connection, $post_id, $post['hashtags']);
    return $post_id;
}

function get_sql_hashtags($connection) {
    $sql_hashtags = 'SELECT h.id, h.hashtag_name FROM hashtags h';

    $query_result = fetch($connection, $sql_hashtags);

    return mysqli_fetch_all($query_result, MYSQLI_ASSOC);
}

function create_sql_hashtags($connection, $post_id, $hashtags) {
    $hashtags_database = get_sql_hashtags($connection);

    foreach ($hashtags as $hashtag) {
        if (in_array($hashtag, $hashtags_database)) {
            $hashtag_id = find_index($hashtags_database, 'hashtag_name', $hashtag);
        } else {
            $sql_hashtag = 'INSERT INTO hashtags (hashtag_name) VALUES (?)';
            get_prepare_stmt($connection, $sql_hashtag, [$hashtag]);
            $hashtag_id = mysqli_insert_id($connection);
        }

        $sql_post_hashtag = 'INSERT INTO post_hashtag (post_id, hashtag_id) VALUES (?, ?)';
        get_prepare_stmt($connection, $sql_post_hashtag, [$post_id, $hashtag_id]);
    }
}
