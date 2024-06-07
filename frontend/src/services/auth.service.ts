
import { fetchWrapper } from '@/utils/fetchWrapper';
const API_URL = import.meta.env.VITE_API_URL;

class AuthService {
  async login(user) {
    return await fetchWrapper.post(`${API_URL}/login`, user);
  }

  async register(user) {
    return await fetchWrapper.post(`${API_URL}/register-user`, user);
  }
}

export default new AuthService();
