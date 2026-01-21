# Laiterekisteri ja Varausjärjestelmä
https://tvt-linux.tvtedu.fi/~213603/TaitajaVaraus/frontend/
## Projektin kuvaus

Selainpohjainen laiterekisteri ja varausjärjestelmä, jolla opettajat voivat etsiä, varata, palauttaa ja hallita TVT-osaston laitteita. Järjestelmä tukee kahta käyttäjäroolia: tavallinen opettaja ja admin-opettaja.

### Teknologiat
- **Frontend**: React (Vite)
- **Backend**: PHP (REST API)
- **Tietokanta**: MySQL
- **Tyylit**: Mukautettu CSS (ei valmiita kirjastoja)

## Projektin rakenne
```
/
├── api/                          # PHP Backend
│   ├── index.php                 # API reititys
│   ├── config/
│   │   └── config.php           # Tietokantayhteys
│   ├── handler/                 # API-käsittelijät
│   │   ├── auth.php             # Kirjautuminen & rekisteröinti
│   │   ├── haku.php             # Hakutoiminnot
│   │   ├── kaapit.php           # Kaappien hallinta
│   │   ├── luokat.php           # Laiteryhmien hallinta
│   │   ├── tavarat.php          # Laitteiden hallinta
│   │   ├── varasto_rivit.php    # Varastotilojen hallinta
│   │   └── varaukset.php        # Varausten hallinta
│   └── middleware/
│       └── auth.php             # Autentikointi middleware
│
├── src/                         # React Frontend
│   ├── assets/                  # Staattiset tiedostot
│   ├── components/              # React komponentit
│   │   ├── includes/
│   │   │   ├── Footer.jsx       # Sivun alatunniste
│   │   │   └── Navbar.jsx       # Navigaatio
│   │   └── ui/                  # UI-komponentit
│   ├── context/                 # React Context
│   ├── pages/                   # Sivut
│   │   ├── HallintaPaneeli.jsx  # Hallintapaneeli
│   │   ├── Home.jsx             # Etusivu
│   │   ├── Kirjaudu.jsx         # Kirjautumisnäkymä
│   │   └── Rekisteroidy.jsx     # Rekisteröitymisnäkymä
│   ├── styles/                  # CSS-tyylit
│   ├── App.jsx                  # Pääkomponentti
│   └── main.jsx                 # Entry point
│
├── database.sql                 # Tietokannan rakenne
├── package.json                 # NPM riippuvuudet
└── README.md                    # Tämä tiedosto
```

## Käyttöohjeet

### Kirjautuminen

Järjestelmään on määritelty kaksi oletuskäyttäjää:

**Tavallinen opettaja:**
- Käyttäjätunnus: `Opettaja@sakky.fi`
- Salasana: `Taitaja2026!`

**Admin-opettaja:**
- Käyttäjätunnus: `AdminOpettaja@sakky.fi`
- Salasana: `Semifinaali2026!`

### Opettajan toiminnallisuudet

1. **Laitteiden haku**
   - Hae hakusanalla (esim. "HP kannettava")
   - Hae varastohuoneen mukaan (esim. "A2TS16")
   - Hae laiteryhmän mukaan (esim. "kuvauslaitteet")

2. **Varaaminen**
   - Valitse vapaa laite listasta
   - Määritä varauksen päättymispäivä
   - Vahvista varaus

3. **Omat varaukset**
   - Tarkastele aktiivisia varauksiasi
   - Muokkaa varauksen päättymispäivää
   - Merkitse laite palautetuksi

4. **Profiili**
   - Muokkaa omia tietoja
   - Vaihda salasana

### Taulut

- **kayttajat** - Käyttäjätilit (salasanat häshätty bcrypt:llä)
- **luokat** - Laiteryhmät
- **tavarat** - Laiterekisteri
- **varastohuoneet** - Varastotilat
- **kaapit** - Kaapit varastohuoneissa
- **hyllyt** - Hyllyt kaapeissa
- **varasto_rivit** - Laitteiden sijainnit
- **varaukset** - Varaukset ja niiden historia

### Suhteet

- Laite ↔ Laiteryhmä (1:N)
- Laite ↔ Sijainti (1:1)
- Varaus ↔ Käyttäjä (N:1)
- Varaus ↔ Laite (N:1)


### Testattu selaimilla

- Chrome (viimeisin versio)
- Chrome Mobile (Android)
