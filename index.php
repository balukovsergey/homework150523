<?php

include_once('APIRequester.php');

$baseurl = "https://test.labsales.ru/tasks/articles/rest";
$login = "labsales_test";
$password = "********";

$requester = new APIRequester($login, $password);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="wrapper mb-5 mt-5">
    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <?php
                try {
                    $categories = $requester->requestGET($baseurl . '/categories');

                    foreach ($categories['data'] as $category) {
                        echo '<a class="nav-item nav-link" id="nav-' . $category['category_id'] . '-tab" data-toggle="tab" href="#nav-' . $category['category_id'] . '" role="tab" aria-controls="nav-' . $category['category_id'] . '" aria-selected="false">' . $category['name'] . '</a>';
                    }
                } catch (Exception $e) {
                    echo 'An error occurred while fetching categories: ' . $e->getMessage();
                }
                ?>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <?php
            if (is_array($categories['data'])) {
                foreach ($categories['data'] as $category) {
                    echo '<div class="tab-pane fade" id="nav-' . $category['category_id'] . '" role="tabpanel">';
                    echo '<div class="row mb-4">';
                    try {
                        $articles = $requester->requestGET($baseurl . '/category/' . $category['category_id']);
                        foreach ($articles['data'] as $article) {
                            $articleData = $requester->requestGET($baseurl . '/article/' . $article['article_id']);
                            echo '<div class="col-12 mt-3 mb-3">';
                            echo '<h2>' . $articleData['data']['name'] . '</h2>';
                            echo '<p><i>' . $articleData['data']['date'] . '</i></p>';
                            echo '<p>' . $articleData['data']['text'] . '</p>';
                            echo '</div>';
                            echo '<div class="col-12">';
                            echo '<hr>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                    } catch (Exception $e) {
                        echo 'An error occurred while fetching articles: ' . $e->getMessage();
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
