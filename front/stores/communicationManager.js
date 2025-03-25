const LARAVEL_URL = 'http://localhost:8000/api/';

async function fetchMovies() {
  try {
    const response = await fetch(`${LARAVEL_URL}movies`);
    if (!response.ok) {
      throw new Error('Error al cargar las películas');
    }
    const data = await response.json();
    return data.movies || [];
  } catch (error) {
    console.error('Error cargando películas:', error);
    return [];
  }
}

async function fetchSessions() {
  try {
    const response = await fetch(`${LARAVEL_URL}sessions`);
    if (!response.ok) {
      throw new Error('Error al cargar las sesiones');
    }
    const data = await response.json();
    return data.sessions || [];
  } catch (error) {
    console.error('Error cargando sesiones:', error);
    return [];
  }
}

async function login(loginForm, router) {
  try {
    const res = await fetch(`${LARAVEL_URL}auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(loginForm)
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: data.message || 'Error en iniciar sessió' };
    }
    localStorage.setItem('access_token', data.data.access_token);
    localStorage.setItem('user', JSON.stringify(data.data.user));
    router.push('/');
    return {
      token: data.data.access_token,
      user: data.data.user
    };
  } catch (err) {
    console.error(err);
    return { error: "S'ha produït un error" };
  }
}

async function register(registerForm, router) {
  try {
    const res = await fetch(`${LARAVEL_URL}auth/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(registerForm)
    });
    const data = await res.json();
    if (!res.ok) {
      return { error: data.message || 'Error en registrar-se' };
    }
    localStorage.setItem('access_token', data.data.access_token);
    localStorage.setItem('user', JSON.stringify(data.data.user));
    router.push('/');
    return {
      token: data.data.access_token,
      user: data.data.user
    };
  } catch (err) {
    console.error(err);
    return { error: "S'ha produït un error" };
  }
}

async function getTickets(email) {
  try {
    const response = await fetch(`${LARAVEL_URL}tickets?email=${encodeURIComponent(email)}`);
    if (!response.ok) {
      throw new Error("No s'han trobat entrades per aquest correu.");
    }
    const data = await response.json();
    return { tickets: data.data || [] };
  } catch (error) {
    return { error: error.message || "S'ha produït un error en obtenir les entrades." };
  }
}

async function getMovieDetails(movieId) {
  try {
    const response = await fetch(`${LARAVEL_URL}movies/${movieId}`);
    if (!response.ok) {
      throw new Error("Error al cargar detalles de la película");
    }
    const data = await response.json();
    return { movie: data.movie };
  } catch (error) {
    console.error("Error al cargar detalles de la película:", error);
    return { error: error.message || "Error al cargar detalles de la película" };
  }
}

// Mètode per obtenir la sessió (que conté informació com is_discount_day)
async function getSessionDetails(sessionId) {
  try {
    const res = await fetch(`${LARAVEL_URL}sessions/${sessionId}`);
    if (!res.ok) {
      throw new Error("Error al carregar la sessió");
    }
    const data = await res.json();
    // Suposem que la resposta és de la forma { data: { session: {...} } }
    const session = data.data && data.data.session ? data.data.session : data.session;
    return { session };
  } catch (error) {
    console.error("Error al carregar la sessió:", error);
    return { error: error.message || "Error al carregar la sessió" };
  }
}

// Mètode per obtenir els seients d'una sessió
async function getSeats(sessionId) {
  try {
    const res = await fetch(`${LARAVEL_URL}sessions/${sessionId}/seats`);
    if (!res.ok) {
      throw new Error("Error al carregar els seients des del backend");
    }
    const data = await res.json();
    return { seats: data.data || [] };
  } catch (error) {
    console.error("Error carregant seients:", error);
    return { error: error.message || "Error carregant seients" };
  }
}

// Mètode per realitzar la compra d'entrades
async function purchaseTickets(purchaseData) {
  try {
    const res = await fetch(`${LARAVEL_URL}tickets/purchase`, {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json', 
        'Accept': 'application/json'
      },
      body: JSON.stringify(purchaseData)
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.message || 'Error al reservar seients');
    }
    return { success: true, data };
  } catch (error) {
    console.error("Error al comprar entrades:", error);
    return { error: error.message || "S'ha produït un error al comprar entrades" };
  }
}

const goToSession = () => {
  if (sessionId.value) {
    // Usar vue-router para cambiar la ruta sin recargar la página
    router.push(`/sessions/${sessionId.value}`);
  }
}



export default {
  fetchMovies,
  fetchSessions,
  login,
  register,
  getTickets,
  getMovieDetails,
  getSessionDetails,
  getSeats,
  purchaseTickets,
  goToSession
};
