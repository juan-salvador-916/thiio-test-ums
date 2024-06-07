import { defineStore } from 'pinia';

import { fetchWrapper } from '@/utils/fetchWrapper';

import authService from '@/services/auth.service';

import router from '@/router';

const baseUrl = `${import.meta.env.VITE_API_URL}`;


export const useAuthStore = defineStore({
    id: 'auth',
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')),
        returnUrl: null
    }),
    actions: {
        async login(email, password) {
            const user = await authService.login({ email, password });
            console.log(user.data);
            this.user = user.data;

            localStorage.setItem('user', JSON.stringify(user.data));

            router.push(this.returnUrl || '/');
        },
        async register(email, password, name, last_name) {
            const user = await authService.register({ email, password, name, last_name, role: 'NORMAL' });
            console.log(user);
            router.push('/login');
        },
        logout() {
            this.user = null;
            localStorage.removeItem('user');
            router.push('/login');
        }
    }
});