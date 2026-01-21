<?php
function hae_varaukset($pdo, $input, $decoded) {
    $KayttajaID = $decoded['sub'] ?? null;
    $Rooli = $decoded['role'] ?? 'kayttaja';
    $aktiivinen = $input['aktiivinen'] ?? null;

    try {
        $sql = "SELECT v.*, vr.Maara as VarausMaara, vr.Varauspaiva, vr.Paattymispaiva,
                k.Nimi as KayttajaNimi, k.Sukunimi as KayttajaSukunimi,
                t.Nimi as TavaraNimi, ka.Nimi as KaappiNimi
                FROM Varaus v
                JOIN VarausRivit vr ON v.VarausriviID = vr.VarausriviID
                JOIN Kayttaja k ON v.KayttajaID = k.KayttajaID
                JOIN Varasto_Rivit vari ON vr.VarastoRiviID = vari.VarastoRiviID
                JOIN Tavara t ON vari.TavaraID = t.TavaraID
                JOIN Kaappi ka ON vari.KaappiID = ka.KaappiID";
        
        $conditions = [];
        $params = [];
        
        if ($Rooli !== 'admin' && $KayttajaID) {
            $conditions[] = "v.KayttajaID = ?";
            $params[] = $KayttajaID;
        }
        
        if ($aktiivinen !== null) {
            $conditions[] = "v.aktiivinen = ?";
            $params[] = $aktiivinen;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY v.Aloitus DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $varaukset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "varaukset" => $varaukset];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function hae_varaushistoria($pdo, $input, $decoded) {
    $KayttajaID = $decoded['sub'] ?? null;
    $Rooli = $decoded['role'] ?? 'kayttaja';

    try {
        $sql = "SELECT v.*, vr.Maara as VarausMaara, vr.Varauspaiva, vr.Paattymispaiva,
                k.Nimi as KayttajaNimi, k.Sukunimi as KayttajaSukunimi,
                t.Nimi as TavaraNimi, ka.Nimi as KaappiNimi
                FROM Varaus v
                JOIN VarausRivit vr ON v.VarausriviID = vr.VarausriviID
                JOIN Kayttaja k ON v.KayttajaID = k.KayttajaID
                JOIN Varasto_Rivit vari ON vr.VarastoRiviID = vari.VarastoRiviID
                JOIN Tavara t ON vari.TavaraID = t.TavaraID
                JOIN Kaappi ka ON vari.KaappiID = ka.KaappiID";
        
        if ($Rooli !== 'admin' && $KayttajaID) {
            $sql .= " WHERE v.KayttajaID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$KayttajaID]);
        } else {
            $stmt = $pdo->query($sql);
        }
        
        $sql .= " ORDER BY v.Aloitus DESC";
        
        $varaukset = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "varaukset" => $varaukset];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function luo_varaus($pdo, $input, $decoded) {
    $KayttajaID = $decoded['sub'];
    $TavaraID = $input['TavaraID'] ?? null;
    $KaappiID = $input['KaappiID'] ?? null;
    $Maara = $input['Maara'] ?? null;
    $Varauspaiva = $input['Varauspaiva'] ?? date('Y-m-d');
    $Paattymispaiva = $input['Paattymispaiva'] ?? null;

    if (!$TavaraID || !$KaappiID || !$Maara || !$Paattymispaiva) {
        return ["success" => false, "message" => "Puuttuvat tiedot (TavaraID, KaappiID, Maara, Paattymispaiva vaaditaan)"];
    }

    try {
        $pdo->beginTransaction();

        // Tarkista että tavara on saatavilla
        $stmt = $pdo->prepare("SELECT SaatavillaMaara FROM Tavara WHERE TavaraID = ?");
        $stmt->execute([$TavaraID]);
        $tavara = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tavara || $tavara['SaatavillaMaara'] < $Maara) {
            $pdo->rollBack();
            return ["success" => false, "message" => "Tavara ei ole saatavilla riittävässä määrin"];
        }

        // Hae tai luo varasto_rivi
        $stmt = $pdo->prepare("SELECT VarastoRiviID FROM Varasto_Rivit WHERE TavaraID = ? AND KaappiID = ?");
        $stmt->execute([$TavaraID, $KaappiID]);
        $varastoRivi = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$varastoRivi) {
            // Luo uusi varasto_rivi
            $stmt = $pdo->prepare("INSERT INTO Varasto_Rivit (TavaraID, KaappiID, Maara, Hylly) VALUES (?, ?, 0, '')");
            $stmt->execute([$TavaraID, $KaappiID]);
            $VarastoRiviID = $pdo->lastInsertId();
        } else {
            $VarastoRiviID = $varastoRivi['VarastoRiviID'];
        }

        // Luo varausrivi
        $sql = "INSERT INTO VarausRivit (VarastoRiviID, Maara, Varauspaiva, Paattymispaiva) 
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$VarastoRiviID, $Maara, $Varauspaiva, $Paattymispaiva]);
        $VarausriviID = $pdo->lastInsertId();

        // Luo varaus
        $sql = "INSERT INTO Varaus (VarausriviID, KayttajaID, Lopetus, aktiivinen) VALUES (?, ?, ?, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$VarausriviID, $KayttajaID, $Paattymispaiva . ' 23:59:59']);
        $VarausID = $pdo->lastInsertId();
        
        // Päivitä saatavilla oleva määrä
        $sql = "UPDATE Tavara SET SaatavillaMaara = SaatavillaMaara - ? WHERE TavaraID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Maara, $TavaraID]);

        $pdo->commit();
        
        return ["success" => true, "message" => "Varaus luotu", "id" => $VarausID];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}

function peruuta_varaus($pdo, $input, $decoded) {
    $KayttajaID = $decoded['sub'];
    $Rooli = $decoded['role'] ?? 'kayttaja';
    $VarausID = $input['VarausID'] ?? null;

    if (!$VarausID) {
        return ["success" => false, "message" => "VarausID vaaditaan"];
    }

    try {
        $pdo->beginTransaction();

        // Hae varauksen tiedot
        $sql = "SELECT v.VarausID, v.KayttajaID, vr.Maara, vari.TavaraID
                FROM Varaus v
                JOIN VarausRivit vr ON v.VarausriviID = vr.VarausriviID
                JOIN Varasto_Rivit vari ON vr.VarastoRiviID = vari.VarastoRiviID
                WHERE v.VarausID = ? AND v.aktiivinen = 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$VarausID]);
        $varaus = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$varaus) {
            $pdo->rollBack();
            return ["success" => false, "message" => "Varausta ei löytynyt tai se on jo peruutettu"];
        }

        // Tarkista oikeudet (vain oma varaus tai admin)
        if ($Rooli !== 'admin' && $varaus['KayttajaID'] != $KayttajaID) {
            $pdo->rollBack();
            return ["success" => false, "message" => "Ei oikeuksia peruuttaa tätä varausta"];
        }

        // Merkitse varaus peruutetuksi
        $sql = "UPDATE Varaus SET aktiivinen = 0, Palautettu = NOW() WHERE VarausID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$VarausID]);

        // Palauta tavarat varastoon
        $sql = "UPDATE Tavara SET SaatavillaMaara = SaatavillaMaara + ? WHERE TavaraID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$varaus['Maara'], $varaus['TavaraID']]);

        $pdo->commit();
        
        return ["success" => true, "message" => "Varaus peruutettu ja tavarat palautettu"];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ["success" => false, "message" => "Virhe: " . $e->getMessage()];
    }
}