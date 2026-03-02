<?php
// Načtení JSON souboru
$data = json_decode(file_get_contents("profile.json"), true);

// Pokud tam ještě není pole interests, vytvoříme ho
if (!isset($data["interests"]) || !is_array($data["interests"])) {
    $data["interests"] = [];
}

$message = "";
$messageType = "";

// Zpracování formuláře
if (isset($_POST["new_interest"])) {
    $newInterest = trim($_POST["new_interest"]);

    if ($newInterest === "") {
        $message = "Pole nesmí být prázdné.";
        $messageType = "error";
    } else {
        // Připravíme pole zájmů převedené na lowercase
        $lowerInterests = array_map("strtolower", $data["interests"]);

        if (in_array(strtolower($newInterest), $lowerInterests)) {
            $message = "Tento zájem už existuje.";
            $messageType = "error";
        } else {
            // Přidání nového zájmu
            $data["interests"][] = $newInterest;

            // Uložení do JSON
            file_put_contents(
                "profile.json",
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $message = "Zájem byl úspěšně přidán.";
            $messageType = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 4.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Můj IT profil</h1>

<h2>Zájmy</h2>
<ul>
    <?php foreach ($data["interests"] as $interest): ?>
        <li><?php echo htmlspecialchars($interest); ?></li>
    <?php endforeach; ?>
</ul>

<?php if (!empty($message)): ?>
    <p class="<?php echo $messageType; ?>">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat zájem</button>
</form>

</body>
</html>