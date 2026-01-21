<?php
function hae_varasto_rivit($pdo, $input, $decoded) {
    $KaappiID = $input['KaappiID'] ?? null;

    try {
        if ($KaappiID) {
            $sql = "SELECT vr.*, t.Nimi as TavaraNimi, t.Kuvaus, k.Nimi as KaappiNimi
                    FROM Varasto_Rivit vr
                    JOIN Tavara t ON vr.TavaraID = t.TavaraID
                    JOIN Kaappi k ON vr.KaappiID = k.KaappiID
                    WHERE vr.KaappiID = ?
                    ORDER BY t.Nimi";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$KaappiID]);
        } else {
            $sql = "SELECT vr.*, t.Nimi as TavaraNimi, t.Kuvaus, k.Nimi as KaappiNimi
                    FROM Varasto_Rivit vr
                    JOIN Tavara t ON vr.TavaraID = t.TavaraID
                    JOIN Kaappi k ON vr.KaappiID = k.KaappiID
                    ORDER BY k.Nimi, t.Nimi";
            $stmt = $pdo->query($sql);
        }
        
        $rivit = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ["success" => true, "rivit" => $rivit];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function lisaa_varasto_rivi($pdo, $input, $decoded) {
    $KaappiID = $input['KaappiID'] ?? null;
    $TavaraID = $input['TavaraID'] ?? null;
    $Maara = $input['Maara'] ?? 0;
    $Hylly = $input['Hylly'] ?? '';

    if (!$KaappiID || !$TavaraID) {
        return ["success" => false, "message" => "KaappiID ja TavaraID vaaditaan"];
    }

    try {
        $sql = "INSERT INTO Varasto_Rivit (KaappiID, TavaraID, Maara, Hylly) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$KaappiID, $TavaraID, $Maara, $Hylly]);
        
        return ["success" => true, "message" => "Varastorivi lisätty", "id" => $pdo->lastInsertId()];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function paivita_varasto_rivi($pdo, $input, $decoded) {
    $VarastoRiviID = $input['VarastoRiviID'] ?? null;
    $Maara = $input['Maara'] ?? null;
    $Hylly = $input['Hylly'] ?? '';

    if (!$VarastoRiviID || $Maara === null) {
        return ["success" => false, "message" => "Puuttuvat tiedot"];
    }

    try {
        $sql = "UPDATE Varasto_Rivit SET Maara = ?, Hylly = ? WHERE VarastoRiviID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Maara, $Hylly, $VarastoRiviID]);
        
        return ["success" => true, "message" => "Varastorivi päivitetty"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function poista_varasto_rivi($pdo, $input, $decoded) {
    $VarastoRiviID = $input['VarastoRiviID'] ?? null;

    if (!$VarastoRiviID) {
        return ["success" => false, "message" => "VarastoRiviID vaaditaan"];
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM Varasto_Rivit WHERE VarastoRiviID = ?");
        $stmt->execute([$VarastoRiviID]);
        
        return ["success" => true, "message" => "Varastorivi poistettu"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}