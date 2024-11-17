<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saurabh's Dictionary</title>
    <script src="https://kit.fontawesome.com/e33bb46629.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f0f8ff; /* Light blue for a soft look */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #sabdkosh {
            text-align: center;
            padding: 20px;
            background: linear-gradient(90deg, #0078ff, #19a7ce);
            color: white;
            border-radius: 0 0 20px 20px;
        }

        #sabdkosh h1 {
            font-size: 2.5rem;
            margin: 0;
        }

        .container {
            margin: 50px auto;
            text-align: center;
        }

        input[type="text"] {
            width: 50%;
            padding: 10px;
            font-size: 1.2rem;
            border: 2px solid #19a7ce;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            background-color: #19a7ce;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0078ff;
        }

        h2,
        h3,
        h4 {
            color: #333;
            margin: 10px 0;
            text-align: center;
        }

        img {
            display: block;
            margin: 20px auto;
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .result {
            margin: 20px auto;
            padding: 20px;
            width: 70%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .result h2,
        .result h3,
        .result h4 {
            color: #0078ff;
        }

        .result-card {
            margin: 20px auto;
            padding: 20px;
            width: 80%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .result-card h2 {
            color: #0078ff;
            font-size: 1.8rem;
            margin-bottom: 10px;
            text-align: center;
        }

        .result-card p {
            color: #333;
            font-size: 1rem;
            line-height: 1.6;
            margin: 5px 0;
        }

        .result-card .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .result-card .meanings {
            margin-bottom: 20px;
        }

        .result-card .meaning-section {
            margin-bottom: 20px;
            border-left: 4px solid #19a7ce;
            padding-left: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            padding: 10px;
        }

        .result-card .info {
            margin-bottom: 20px;
        }

        .result-card .info p {
            margin: 5px 0;
        }

        .result-card .language-section {
            text-align: center;
        }

        .result-card .language-section span {
            display: inline-block;
            margin: 5px 10px;
            font-size: 1rem;
            background: #f0f8ff;
            padding: 10px;
            border-radius: 5px;
            color: #333;
            font-weight: bold;
            border: 1px solid #19a7ce;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div id="sabdkosh" class="dic">
        <h1><b><i><u>Saurabh's Dictionary</u></i></b></h1>
    </div>

    <!-- Search Form Section -->
    <div class="container">
        <form align="center" action="<?php $_PHP_SELF ?>" method="POST">
            <input type="text" name="name" placeholder="Search for a word..." />
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "saurabh16";
    error_reporting(0);

    try {
        $conn = new PDO("mysql:host=$servername;dbname=dictionary", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_POST['name'] != NULL) {
            $search = trim($_POST['name'], " ");

            $sql = "SELECT * FROM `synonym` NATURAL JOIN
                        (SELECT * FROM `antonym` NATURAL JOIN
                            (SELECT * FROM `image` NATURAL JOIN
                                (SELECT * FROM `example` NATURAL JOIN
                                    (SELECT * FROM `language` NATURAL JOIN
                                        (SELECT * FROM `word` NATURAL JOIN `meaning` AS t1) AS t2) AS t3) AS t4) AS t5) AS whole
                    WHERE Word = '$search';";

            $result = $conn->query($sql);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $row = $result->fetchAll();

            foreach ($row as $word) {
                echo '<div class="result">';
                if ($word['Image'] != NULL) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($word['Image']) . '">';
                }

                echo '<div class="result-card">';

                // Header Section
                echo '<div class="header">';
                echo '<h2>Word: ' . htmlspecialchars($word['Word']) . '</h2>';
                echo '<p><strong>Syllable:</strong> ' . htmlspecialchars($word['Syllable']) . ' | <strong>Pronunciation:</strong> ' . htmlspecialchars($word['Pronounciation']) . '</p>';
                echo '<p><strong>Scientific Name:</strong> ' . htmlspecialchars($word['Scientific_Name']) . '</p>';
                echo '<p><strong>Part of Speech:</strong> ' . htmlspecialchars($word['PartOf_Speech']) . '</p>';
                echo '</div>';

                // Meanings Section
                echo '<div class="meanings">';
                // Meaning 1
                if (!empty($word['Meaning_1'])) {
                    echo '<div class="meaning-section">';
                    echo '<h3>Meaning 1:</h3>';
                    echo '<p>' . htmlspecialchars($word['Meaning_1']) . '</p>';
                    if (!empty($word['Example_1'])) {
                        echo '<h4>Example 1:</h4>';
                        echo '<p>' . htmlspecialchars($word['Example_1']) . '</p>';
                    }
                    echo '</div>';
                }

                // Meaning 2
                if (!empty($word['Meaning_2'])) {
                    echo '<div class="meaning-section">';
                    echo '<h3>Meaning 2:</h3>';
                    echo '<p>' . htmlspecialchars($word['Meaning_2']) . '</p>';
                    if (!empty($word['Example_2'])) {
                        echo '<h4>Example 2:</h4>';
                        echo '<p>' . htmlspecialchars($word['Example_2']) . '</p>';
                    }
                    echo '</div>';
                }

                // Meaning 3
                if (!empty($word['Meaning_3'])) {
                    echo '<div class="meaning-section">';
                    echo '<h3>Meaning 3:</h3>';
                    echo '<p>' . htmlspecialchars($word['Meaning_3']) . '</p>';
                    if (!empty($word['Example_3'])) {
                        echo '<h4>Example 3:</h4>';
                        echo '<p>' . htmlspecialchars($word['Example_3']) . '</p>';
                    }
                    echo '</div>';
                }

                
                echo '</div>';

                // Synonyms and Antonyms Section
                echo '<div class="info">';
                echo '<p><strong>Synonyms:</strong> ' . (!empty($word['Synonym']) ? htmlspecialchars($word['Synonym']) : 'None') . '</p>';
                echo '<p><strong>Antonyms:</strong> ' . (!empty($word['Antonym']) ? htmlspecialchars($word['Antonym']) : 'None') . '</p>';
                echo '</div>';

                // Translations Section
                echo '<div class="language-section">';
                echo '<h3>Translations:</h3>';
                echo '<span>Hindi: ' . htmlspecialchars($word['Hindi']) . '</span>';
                echo '<span>Marathi: ' . htmlspecialchars($word['Marathi']) . '</span>';
                echo '<span>Bengali: ' . htmlspecialchars($word['Bengali']) . '</span>';
                echo '</div>';

                echo '</div>';
            }
        }
    } catch (PDOException $e) {
        echo "<div style='text-align: center; color: red;'>Connection failed: " . $e->getMessage() . "</div>";
    }
    ?>
</body>

</html>
