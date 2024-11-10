<?php
// Load WordPress environment
require_once('/home2/animever/public_html/gogoanim.me/wp-load.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categories = $_POST['categories']; // Array of category IDs

    if (empty($categories)) {
        echo '<p style="color:red;">No categories selected. Please select at least one category.</p>';
        exit;
    }

    // Loop through each category and delete its posts
    foreach ($categories as $category_id) {
        $args = array(
            'category' => $category_id,
            'post_type' => 'post',
            'posts_per_page' => -1, // Get all posts
            'fields' => 'ids', // Only get post IDs
        );

        $posts = get_posts($args);

        if (!empty($posts)) {
            foreach ($posts as $post_id) {
                wp_delete_post($post_id, true); // True = Force delete (bypass trash)
                echo '<p>Deleted post with ID: ' . $post_id . '</p>';
            }
        } else {
            echo '<p>No posts found for category ID: ' . $category_id . '</p>';
        }
    }

    echo '<p>All selected category posts have been deleted!</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Posts by Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 40px;
            padding: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #ff0000;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <h1>Delete Posts by Category</h1>
    <form method="POST">
        <label for="categories">Select Categories to Delete Posts From:</label>
        <select id="categories" name="categories[]" multiple required>
            <?php
            // Load all categories from WordPress
            $categories = get_categories();
            foreach ($categories as $category) {
                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
            }
            ?>
        </select>

        <button type="submit">Delete Posts</button>
    </form>
</body>
</html>
