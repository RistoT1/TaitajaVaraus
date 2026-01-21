import api from './client';

export async function haeKaapit() {
  try {
    const response = await api.get('/server.php?hae_kaapit=1');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe kaappeja haettaessa');
  }
}

export async function haeKaappi(kaappiId) {
  try {
    const response = await api.get(`/server.php?hae_kaappi=1&KaappiID=${kaappiId}`);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe kaappia haettaessa');
  }
}

export async function haeKaappiSisalto(kaappiId) {
  try {
    const response = await api.get(`/server.php?hae_kaappi_sisalto=1&KaappiID=${kaappiId}`);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe sisältöä haettaessa');
  }
}

export async function lisaaKaappi(kaappi) {
  try {
    const response = await api.post('/server.php', {
      lisaa_kaappi: 1,
      Nimi: kaappi.nimi,
      LuokkaID: kaappi.luokkaId,
      Sijainti: kaappi.sijainti,
      Tyyppi: kaappi.tyyppi
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe lisättäessä');
  }
}

export async function paivitaKaappi(kaappiId, kaappi) {
  try {
    const response = await api.post('/server.php', {
      paivita_kaappi: 1,
      KaappiID: kaappiId,
      Nimi: kaappi.nimi,
      LuokkaID: kaappi.luokkaId,
      Sijainti: kaappi.sijainti,
      Tyyppi: kaappi.tyyppi
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe päivittäessä');
  }
}

export async function poistaKaappi(kaappiId) {
  try {
    const response = await api.post('/server.php', {
      poista_kaappi: 1,
      KaappiID: kaappiId
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe poistettaessa');
  }
}