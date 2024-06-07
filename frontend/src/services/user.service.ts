import { fetchWrapper } from '@/utils/fetchWrapper';
const API_URL = import.meta.env.VITE_API_URL;

class UserService {
  async getUsers() {
    return await fetchWrapper.get(`${API_URL}/users`);
  }

  async getUser(userId) {
    return await fetchWrapper.get(`${API_URL}/users/${userId}`);
  }

  async createUser(user) {
    return await fetchWrapper.post(`${API_URL}/users`, user);
  }

  async updateUser(user, userId) {
    return await fetchWrapper.put(`${API_URL}/users/${userId}`, user);
  }

  async deleteUser(userId) {
    return await fetchWrapper.delete(`${API_URL}/users/${userId}`);
  }

}

export default new UserService();