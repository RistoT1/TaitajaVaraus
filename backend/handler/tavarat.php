<?php
function hae_tavarat($pdo, $input, $decoded) {
    try {
        $stmt = $pdo->query("SELECT * FROM Tavara ORDER BY Nimi");
        $tavarat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "tavarat" => $tavarat];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function hae_tavara($pdo, $input, $decoded) {
    $TavaraID = $input['TavaraID'] ?? null;

    if (!$TavaraID) {
        return ["success" => false, "message" => "TavaraID vaaditaan"];
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM Tavara WHERE TavaraID = ?");
        $stmt->execute([$TavaraID]);
        $tavara = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tavara) {
            return ["success" => false, "message" => "Tavaraa ei löytynyt"];
        }
        
        return ["success" => true, "tavara" => $tavara];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function lisaa_tavara($pdo, $input, $decoded) {
    $Nimi = $input['Nimi'] ?? null;
    $Kuvaus = $input['Kuvaus'] ?? '';
    $Maara = $input['Maara'] ?? 0;
    $SaatavillaMaara = $input['SaatavillaMaara'] ?? $Maara;

    if (!$Nimi) {
        return ["success" => false, "message" => "Tavaran nimi vaaditaan"];
    }

    try {
        $sql = "INSERT INTO Tavara (Nimi, Kuvaus, Maara, SaatavillaMaara) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $Kuvaus, $Maara, $SaatavillaMaara]);
        
        return ["success" => true, "message" => "Tavara lisätty", "id" => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function paivita_tavara($pdo, $input, $decoded) {
    $TavaraID = $input['TavaraID'] ?? null;
    $Nimi = $input['Nimi'] ?? null;
    $Kuvaus = $input['Kuvaus'] ?? '';
    $Maara = $input['Maara'] ?? null;
    $SaatavillaMaara = $input['SaatavillaMaara'] ?? null;

    if (!$TavaraID || !$Nimi || $Maara === null) {
        return ["success" => false, "message" => "Puuttuvat tiedot"];
    }

    try {
        $sql = "UPDATE Tavara SET Nimi = ?, Kuvaus = ?, Maara = ?, SaatavillaMaara = ? WHERE TavaraID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $Kuvaus, $Maara, $SaatavillaMaara, $TavaraID]);
        
        return ["success" => true, "message" => "Tavara päivitetty"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function poista_tavara($pdo, $input, $decoded) {
    $TavaraID = $input['TavaraID'] ?? null;

    if (!$TavaraID) {
        return ["success" => false, "message" => "TavaraID vaaditaan"];
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM Tavara WHERE TavaraID = ?");
        $stmt->execute([$TavaraID]);
        
        return ["success" => true, "message" => "Tavara poistettu"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}