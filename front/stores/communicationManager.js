const LARAVEL_URL = 'http://localhost:8000/api/';

export async function fetchMovies() {
  try {
    const moviesRes = await fetch(`${LARAVEL_URL}movies`);
    if (!moviesRes.ok) {
      throw new Error('Error al cargar las películas');
    }
    const data = await moviesRes.json();
    return data.movies || [];
  } catch (error) {
    console.error('Error cargando películas:', error);
    return [];
  }
}

export async function fetchSessions() {
  try {
    const sessionsRes = await fetch(`${LARAVEL_URL}sessions`);
    if (!sessionsRes.ok) {
      throw new Error('Error al cargar las sesiones');
    }
    const data = await sessionsRes.json();
    return data.sessions || [];
  } catch (error) {
    console.error('Error cargando sesiones:', error);
    return [];
  }
}

export async function login(loginForm, router) {
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
        console.log('Errors de validació:', data);
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
      return { error: 'S\'ha produït un error' };
    }
  }
  

export async function register(registerForm, router) {
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
    return { error: 'S\'ha produït un error' };
  }
}
