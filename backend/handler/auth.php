<?php
function kirjaudu($pdo, $input) {
    $Sahkoposti = $input['Sahkoposti'] ?? '';
    $Salasana = $input['Salasana'] ?? '';

    $stmt = $pdo->prepare("SELECT KayttajaID, Nimi, Sukunimi, Salasana, Rooli FROM Kayttaja WHERE Sahkoposti = ?");
    $stmt->execute([$Sahkoposti]);
    $Kayttaja = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$Kayttaja || !password_verify($Salasana, $Kayttaja['Salasana'])) {
        return ["success" => false, "message" => "Virheellinen sähköposti tai salasana"];
    }

    // JWT tokenin generointi oikeilla kentillä
    $tokenData = [
        'id' => $Kayttaja['KayttajaID'],
        'role' => $Kayttaja['Rooli']
    ];
    $token = generateToken($tokenData);

    return [
        "success" => true, 
        "token" => $token, 
        "kayttaja" => [
            "id" => $Kayttaja['KayttajaID'],
            "nimi" => $Kayttaja['Nimi'],
            "sukunimi" => $Kayttaja['Sukunimi'],
            "rooli" => $Kayttaja['Rooli']
        ]
    ];
}

function rekisteroidy($pdo, $input) {
    $Nimi = $input['Nimi'] ?? null;
    $Sukunimi = $input['Sukunimi'] ?? null;
    $Sahkoposti = $input['Sahkoposti'] ?? null;
    $Salasana = $input['Salasana'] ?? null;
    $Osoite = $input['Osoite'] ?? '';
    $Postinumero = $input['Postinumero'] ?? '';
    $Kunta = $input['Kunta'] ?? '';

    if (!$Nimi || !$Sukunimi || !$Sahkoposti || !$Salasana) {
        return ["success" => false, "message" => "Täytä kaikki pakolliset kentät"];
    }

    $hashedSalasana = password_hash($Salasana, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO Kayttaja (
                    Nimi, Sukunimi, Sahkoposti, Osoite, 
                    Postinumero, Kunta, Rooli, Salasana
                ) VALUES (?, ?, ?, ?, ?, ?, 'kayttaja', ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $Nimi, $Sukunimi, $Sahkoposti, $Osoite, 
            $Postinumero, $Kunta, $hashedSalasana
        ]);
        
        return ["success" => true, "message" => "Käyttäjä rekisteröity onnistuneesti"];

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return ["success" => false, "message" => "Sähköpostiosoite on jo käytössä"];
        }
        return ["success" => false, "message" => "Tietokantavirhe: " . $e->getMessage()];
    }
}

function hae_kayttajat($pdo, $input, $decoded) {
    try {
        $stmt = $pdo->query("SELECT KayttajaID, Nimi, Sukunimi, Sahkoposti, Rooli, Luotu FROM Kayttaja ORDER BY Luotu DESC");
        $kayttajat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ["success" => true, "kayttajat" => $kayttajat];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe haettaessa käyttäjiä: " . $e->getMessage()];
    }
}

function paivita_kayttaja($pdo, $input, $decoded) {
    // Käyttäjä voi päivittää vain omia tietojaan (paitsi admin)
    $tokenKayttajaID = $decoded['sub'];
    $Rooli = $decoded['role'] ?? 'kayttaja';
    
    $KayttajaID = $input['KayttajaID'] ?? $tokenKayttajaID;
    $Nimi = $input['Nimi'] ?? null;
    $Sukunimi = $input['Sukunimi'] ?? null;
    $Osoite = $input['Osoite'] ?? '';
    $Postinumero = $input['Postinumero'] ?? '';
    $Kunta = $input['Kunta'] ?? '';

    // Tarkista oikeudet (ei-admin voi muokata vain omia tietojaan)
    if ($Rooli !== 'admin' && $KayttajaID != $tokenKayttajaID) {
        return ["success" => false, "message" => "Ei oikeuksia muokata toisen käyttäjän tietoja"];
    }

    if (!$KayttajaID || !$Nimi || !$Sukunimi) {
        return ["success" => false, "message" => "Puuttuvat pakolliset tiedot"];
    }

    try {
        $sql = "UPDATE Kayttaja SET Nimi = ?, Sukunimi = ?, Osoite = ?, 
                Postinumero = ?, Kunta = ? WHERE KayttajaID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$Nimi, $Sukunimi, $Osoite, $Postinumero, $Kunta, $KayttajaID]);
        
        return ["success" => true, "message" => "Käyttäjätiedot päivitetty"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Virhe päivityksessä: " . $e->getMessage()];
    }
}