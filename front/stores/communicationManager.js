const LARAVEL_URL = 'http://localhost:8000/api/';
//const LARAVEL_URL = 'http://a20edurenlopcinelaravel.daw.inspedralbes.cat/api/';


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

async function login(data) {
  try {
    const response = await fetch(`${LARAVEL_URL}auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: data.email,
        password: data.password,
        recaptcha_token: data.recaptchaToken
      })
    });

    const textResponse = await response.text();
    const result = textResponse ? JSON.parse(textResponse) : {};

    if (!response.ok) {
      return {
        error: result.message || 'Error en el login',
        status: response.status
      };
    }

    return result;

  } catch (error) {
    console.error('Login error:', error);
    return { error: 'Error de conexión: ' + error.message };
  }
}

async function register(data) {
  try {
    const response = await fetch(`${LARAVEL_URL}auth/register`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        name: data.name,
        lastname: data.lastname,
        email: data.email,
        password: data.password,
        password_confirmation: data.password_confirmation,
        phone: data.phone,
        recaptcha_token: data.recaptchaToken
      })
    });

    const textResponse = await response.text();
    const result = textResponse ? JSON.parse(textResponse) : {};

    if (!response.ok) {
      return {
        error: result.message || 'Error en el registro',
        status: response.status
      };
    }

    return result;

  } catch (error) {
    console.error('Register error:', error);
    return { error: 'Error de conexión: ' + error.message };
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

async function getSessionDetails(sessionId) {
  try {
    const res = await fetch(`${LARAVEL_URL}sessions/${sessionId}`);
    if (!res.ok) {
      throw new Error("Error al carregar la sessió");
    }
    const data = await res.json();
    const session = data.data && data.data.session ? data.data.session : data.session;
    return { session };
  } catch (error) {
    console.error("Error al carregar la sessió:", error);
    return { error: error.message || "Error al carregar la sessió" };
  }
}

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

const goToSession = (router, sessionId) => {
  if (sessionId) {
    router.push(`/sessions/${sessionId}`);
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