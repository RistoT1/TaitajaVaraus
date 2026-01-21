<?php
function hae_saatavilla_olevat($pdo, $input, $decoded) {
    try {
        $sql = "SELECT t.*, 
                GROUP_CONCAT(CONCAT(k.Nimi, ' (', vr.Maara, ')') SEPARATOR ', ') as Sijainnit
                FROM Tavara t
                LEFT JOIN Varasto_Rivit vr ON t.TavaraID = vr.TavaraID
                LEFT JOIN Kaappi k ON vr.KaappiID = k.KaappiID
                WHERE t.SaatavillaMaara > 0
                GROUP BY t.TavaraID
                ORDER BY t.Nimi";
        
        $stmt = $pdo->query($sql);
        $tavarat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "tavarat" => $tavarat];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function hae_kaappi_sisalto($pdo, $input, $decoded) {
    $KaappiID = $input['KaappiID'] ?? null;

    if (!$KaappiID) {
        return ["success" => false, "message" => "KaappiID vaaditaan"];
    }

    try {
        $sql = "SELECT vr.*, t.Nimi, t.Kuvaus, t.SaatavillaMaara
                FROM Varasto_Rivit vr
                JOIN Tavara t ON vr.TavaraID = t.TavaraID
                WHERE vr.KaappiID = ?
                ORDER BY vr.Hylly, t.Nimi";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$KaappiID]);
        $sisalto = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "sisalto" => $sisalto];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function etsi_tavara($pdo, $input, $decoded) {
    $hakusana = $input['hakusana'] ?? '';

    if (strlen($hakusana) < 2) {
        return ["success" => false, "message" => "Hakusanan tulee olla vähintään 2 merkkiä"];
    }

    try {
        $sql = "SELECT t.*, 
                GROUP_CONCAT(
                    CONCAT(k.Nimi, ' - ', l.Nimi, ' (', vr.Maara, ' kpl, ', vr.Hylly, ')') 
                    SEPARATOR ' | '
                ) as Sijainnit
                FROM Tavara t
                LEFT JOIN Varasto_Rivit vr ON t.TavaraID = vr.TavaraID
                LEFT JOIN Kaappi k ON vr.KaappiID = k.KaappiID
                LEFT JOIN Luokat l ON k.LuokkaID = l.LuokkaID
                WHERE t.Nimi LIKE ? OR t.Kuvaus LIKE ?
                GROUP BY t.TavaraID
                ORDER BY t.Nimi";
        
        $stmt = $pdo->prepare($sql);
        $searchTerm = "%$hakusana%";
        $stmt->execute([$searchTerm, $searchTerm]);
        $tulokset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "tulokset" => $tulokset, "maara" => count($tulokset)];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}