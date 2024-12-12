<?php
include '../includes/db.php';

session_start(); // Start the session to store follow information and random images

function getRandomAuthorImages($author)
{
    // Ensure $author is a string
    if (!is_string($author)) {
        throw new InvalidArgumentException('The parameter $author must be a string.');
    }

    // Define the path to the author images directory
    $authorImagesDir = '../asset/img-authors';

    // Fetch all available images for authors
    $availableImages = glob("$authorImagesDir/*.jpg");

    if (empty($availableImages)) {
        throw new RuntimeException('No images found in the author images directory.');
    }

    // Check if the random images for the author are already set in the session
    if (!isset($_SESSION['random_author_images'][$author])) {
        // Shuffle the array to randomize the images and select up to 3
        shuffle($availableImages);
        $_SESSION['random_author_images'][$author] = array_slice($availableImages, 0, 3);
    }

    return $_SESSION['random_author_images'][$author];
}

// Follow or Unfollow Action
if (isset($_GET['follow_author'])) {
    $authorName = $_GET['follow_author'];

    // Initialize the followed authors session if it's not already set
    if (!isset($_SESSION['followed_authors'])) {
        $_SESSION['followed_authors'] = [];
    }

    // Toggle the follow/unfollow status
    if (in_array($authorName, $_SESSION['followed_authors'])) {
        // Unfollow the author
        $_SESSION['followed_authors'] = array_diff($_SESSION['followed_authors'], [$authorName]);
    } else {
        // Follow the author
        $_SESSION['followed_authors'][] = $authorName;
    }
}
?>
