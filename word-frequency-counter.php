<?php

function tokenizeText($text) {
    // Use regular expression to split the text into an array of words
    $words = preg_split('/\W+/', $text, -1, PREG_SPLIT_NO_EMPTY);

    return $words;
}

function calculateWordFrequency($text) {
    $wordFrequency = array();

    // Convert the text to lowercase to ensure case-insensitivity
    $text = strtolower($text);

    // Tokenize the text into words
    $words = tokenizeText($text);

    // Common stop words to ignore
    $stopWords = array("the", "and", "in", "of", "to", "a", "is", "it", "that", "with", "for", "on", "was", "as", "at", "by", "an", "be", "this");

    // Count the frequency of each word (ignoring stop words)
    foreach ($words as $word) {
        // Skip stop words
        if (!in_array($word, $stopWords)) {
            if (isset($wordFrequency[$word])) {
                $wordFrequency[$word]++;
            } else {
                $wordFrequency[$word] = 1;
            }
        }
    }

    return $wordFrequency;
}

function sortWordFrequency($wordFrequency, $order = 'asc') {
    // Sort the word frequency array based on frequency
    if ($order == 'asc') {
        asort($wordFrequency);
    } else {
        arsort($wordFrequency);
    }

    return $wordFrequency;
}

function displayWordFrequency($wordFrequency, $displayLimit = null) {
    // Display the list of unique words along with their frequencies, limited by display limit
    echo "<h2>Word Frequency Results</h2>";

    if (empty($wordFrequency)) {
        echo "<p>No valid words to display. Please provide valid input.</p>";
        return;
    }

    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Word</th><th>Frequency</th></tr>";

    // If display limit is specified, take only the top N words
    if ($displayLimit !== null) {
        $displayLimit = max(1, $displayLimit); // Ensure a positive integer
        $wordFrequency = array_slice($wordFrequency, 0, $displayLimit, true);
    }

    foreach ($wordFrequency as $word => $frequency) {
        echo "<tr><td>$word</td><td>$frequency</td></tr>";
    }

    echo "</table>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input from the text area
    $userInput = isset($_POST['inputText']) ? $_POST['inputText'] : '';

    // Check for empty input
    if (empty($userInput)) {
        echo "<p>Please provide text input.</p>";
    } else {
        // Calculate word frequency
        $wordFrequency = calculateWordFrequency($userInput);

        // Sort the word frequency based on user's choice
        $sortOrder = isset($_POST['sortOrder']) ? $_POST['sortOrder'] : 'asc';
        $wordFrequency = sortWordFrequency($wordFrequency, $sortOrder);

        // Get the display limit specified by the user
        $displayLimit = isset($_POST['displayLimit']) ? intval($_POST['displayLimit']) : null;

        // Display the results with the specified display limit
        displayWordFrequency($wordFrequency, $displayLimit);
    }
}

?>
