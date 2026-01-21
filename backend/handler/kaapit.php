<?php
function hae_kaapit($pdo, $input, $decoded) {
    try {
        $sql = "SELECT k.*, l.Nimi as LuokkaNimi 
                FROM Kaappi k 
                LEFT JOIN Luokat l ON k.LuokkaID = l.LuokkaID 
                ORDER BY k.Nimi";
        $stmt = $pdo->query($sql);
        $kaapit = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "kaapit" => $kaapit];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function hae_kaappi($pdo, $input, $decoded) {
    $KaappiID = $input['KaappiID'] ?? null;

    if (!$KaappiID) {
        return ["success" => false, "message" => "KaappiID vaaditaan"];
    }

    try {
        $sql = "SELECT k.*, l.Nimi as LuokkaNimi 
                FROM Kaappi k 
                LEFT JOIN Luokat l ON k.LuokkaID = l.LuokkaID 
                WHERE k.KaappiID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$KaappiID]);
        $kaappi = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$kaappi) {
            return ["success" => false, "message" => "Kaappia ei löytynyt"];
        }
        
        return ["success" => true, "kaappi" => $kaappi];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function lisaa_kaappi($pdo, $input, $decoded) {
    $Nimi = $input['Nimi'] ?? null;
    $LuokkaID = $input['LuokkaID'] ?? null;
    $Sijainti = $input['Sijainti'] ?? '';
    $Tyyppi = $input['Tyyppi'] ?? 'kaappi';

    if (!$Nimi || !$LuokkaID) {
        return ["success" => false, "message" => "Nimi ja LuokkaID vaaditaan"];
    }

    try {
        $sql = "INSERT INTO Kaappi (Nimi, LuokkaID, Sijainti, Tyyppi) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $LuokkaID, $Sijainti, $Tyyppi]);
        
        return ["success" => true, "message" => "Kaappi lisätty", "id" => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function paivita_kaappi($pdo, $input, $decoded) {
    $KaappiID = $input['KaappiID'] ?? null;
    $Nimi = $input['Nimi'] ?? null;
    $LuokkaID = $input['LuokkaID'] ?? null;
    $Sijainti = $input['Sijainti'] ?? '';
    $Tyyppi = $input['Tyyppi'] ?? 'kaappi';

    if (!$KaappiID || !$Nimi || !$LuokkaID) {
        return ["success" => false, "message" => "Puuttuvat tiedot"];
    }

    try {
        $sql = "UPDATE Kaappi SET Nimi = ?, LuokkaID = ?, Sijainti = ?, Tyyppi = ? WHERE KaappiID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $LuokkaID, $Sijainti, $Tyyppi, $KaappiID]);
        
        return ["success" => true, "message" => "Kaappi päivitetty"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function poista_kaappi($pdo, $input, $decoded) {
    $KaappiID = $input['KaappiID'] ?? null;

    if (!$KaappiID) {
        return ["success" => false, "message" => "KaappiID vaaditaan"];
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM Kaappi WHERE KaappiID = ?");
        $stmt->execute([$KaappiID]);
        
        return ["success" => true, "message" => "Kaappi poistettu"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}