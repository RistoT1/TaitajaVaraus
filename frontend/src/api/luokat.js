import api from './client';

export async function haeLuokat() {
  try {
    const response = await api.get('/server.php?hae_luokat=1');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe luokkia haettaessa');
  }
}

export async function lisaaLuokka(luokka) {
  try {
    const response = await api.post('/server.php', {
      lisaa_luokka: 1,
      Nimi: luokka.nimi,
      Tiedot: luokka.tiedot,
      Kattavuus: luokka.kattavuus
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe lisättäessä');
  }
}

export async function paivitaLuokka(luokkaId, luokka) {
  try {
    const response = await api.post('/server.php', {
      paivita_luokka: 1,
      LuokkaID: luokkaId,
      Nimi: luokka.nimi,
      Tiedot: luokka.tiedot,
      Kattavuus: luokka.kattavuus
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe päivittäessä');
  }
}

export async function poistaLuokka(luokkaId) {
  try {
    const response = await api.post('/server.php', {
      poista_luokka: 1,
      LuokkaID: luokkaId
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe poistettaessa');
  }
}