import { defineStore } from 'pinia';

export const useAppStore = defineStore('app', {
  state: () => ({
    loginInfo: typeof window !== 'undefined' && localStorage.getItem('loginInfo')
      ? JSON.parse(localStorage.getItem('loginInfo'))
      : {
          loggedIn: false,
          id: null,
          token: '',
          name: '',
          lastname: '',
          email: '',
          phone: ''
        },
  }),
  actions: {
    setLoginInfo({ id, loggedIn, token, name, lastname, email, phone }) {
      this.loginInfo = { loggedIn, id, token, name, lastname, email, phone };
      if (typeof window !== 'undefined') {
        localStorage.setItem('loginInfo', JSON.stringify(this.loginInfo));
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify({ id, name, lastname, email, phone }));
      }
    },
    logout() {
      this.loginInfo = {
        loggedIn: false,
        id: null,
        token: '',
        name: '',
        lastname: '',
        email: '',
        phone: ''
      };
      if (typeof window !== 'undefined') {
        localStorage.removeItem('loginInfo');
        localStorage.removeItem('token');
        localStorage.removeItem('user');
      }
    }
  },
  getters: {
    isLoggedIn: (state) => state.loginInfo.loggedIn,
    getLoginInfo: (state) => state.loginInfo,
  },
});
