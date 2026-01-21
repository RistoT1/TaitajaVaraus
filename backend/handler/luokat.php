<?php
function hae_luokat($pdo, $input, $decoded) {
    try {
        $stmt = $pdo->query("SELECT * FROM Luokat ORDER BY Nimi");
        $luokat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "luokat" => $luokat];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function lisaa_luokka($pdo, $input, $decoded) {
    $Nimi = $input['Nimi'] ?? null;
    $Tiedot = $input['Tiedot'] ?? '';
    $Kattavuus = $input['Kattavuus'] ?? null;

    if (!$Nimi) {
        return ["success" => false, "message" => "Luokan nimi vaaditaan"];
    }

    try {
        $sql = "INSERT INTO Luokat (Nimi, Tiedot, Kattavuus) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $Tiedot, $Kattavuus]);
        
        return ["success" => true, "message" => "Luokka lisätty", "id" => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function paivita_luokka($pdo, $input, $decoded) {
    $LuokkaID = $input['LuokkaID'] ?? null;
    $Nimi = $input['Nimi'] ?? null;
    $Tiedot = $input['Tiedot'] ?? '';
    $Kattavuus = $input['Kattavuus'] ?? null;

    if (!$LuokkaID || !$Nimi) {
        return ["success" => false, "message" => "Puuttuvat tiedot"];
    }

    try {
        $sql = "UPDATE Luokat SET Nimi = ?, Tiedot = ?, Kattavuus = ? WHERE LuokkaID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $Tiedot, $Kattavuus, $LuokkaID]);
        
        return ["success" => true, "message" => "Luokka päivitetty"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function poista_luokka($pdo, $input, $decoded) {
    $LuokkaID = $input['LuokkaID'] ?? null;

    if (!$LuokkaID) {
        return ["success" => false, "message" => "LuokkaID vaaditaan"];
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM Luokat WHERE LuokkaID = ?");
        $stmt->execute([$LuokkaID]);
        
        return ["success" => true, "message" => "Luokka poistettu"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}