import api from './client';

export async function haeVaraukset(aktiivinen = null) {
  try {
    const params = new URLSearchParams({ hae_varaukset: 1 });
    if (aktiivinen !== null) {
      params.append('aktiivinen', aktiivinen);
    }
    const response = await api.get(`/server.php?${params.toString()}`);
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe varauksia haettaessa');
  }
}

export async function haeVaraushistoria() {
  try {
    const response = await api.get('/server.php?hae_varaushistoria=1');
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe historiaa haettaessa');
  }
}

export async function luoVaraus(varaus) {
  try {
    const response = await api.post('/server.php', {
      luo_varaus: 1,
      TavaraID: varaus.tavaraId,
      KaappiID: varaus.kaappiId,
      Maara: varaus.maara,
      Varauspaiva: varaus.varauspaiva,
      Paattymispaiva: varaus.paattymispaiva
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe varauksessa');
  }
}

export async function peruutaVaraus(varausId) {
  try {
    const response = await api.post('/server.php', {
      peruuta_varaus: 1,
      VarausID: varausId
    });
    return response.data;
  } catch (error) {
    throw new Error(error.response?.data?.message || 'Virhe peruttaessa');
  }
}