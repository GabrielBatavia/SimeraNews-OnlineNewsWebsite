<?php
include '../includes/db.php';

function getImg($article)
{
    // Ensure $article is an object, not an array
    if (!is_object($article)) {
        throw new InvalidArgumentException('The parameter $article must be an object.');
    }

    // Access the ObjectId and convert it to a string
    $id = (string) $article->_id;

    // Cast the ObjectId string to an integer (using a hash function for consistent results)
    $idNumeric = hexdec(substr($id, -6)); // Use the last 6 characters of the ObjectId for numeric operations

    // Access the 'category' field of the object
    if (!property_exists($article, 'category')) {
        throw new InvalidArgumentException('The $article object must have a "category" property.');
    }
    $category = (string) $article->category;

    // Check if the numeric ID is odd or even
    if ($idNumeric % 2 === 0) {
        return "../asset/img-news/$category/genap.jpg";
    } else {
        return "../asset/img-news/$category/ganjil.jpg";
    }
}
?>
