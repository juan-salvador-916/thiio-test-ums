<script setup lang="ts">
import HeaderLogin from "@/components/HeaderLogin.vue";
import { ref } from 'vue'
import { useField, useForm } from 'vee-validate'

import { useAuthStore } from '@/stores/auth.store';

const { handleSubmit, handleReset } = useForm({
    validationSchema: {
        email(value) {

            if (!value) {
                return 'Email required';
            }

            const regexEmail = /^[a-z.-]+@[a-z.-]+\.[a-z]+$/i;

            if (!regexEmail.test(value)) return 'Must be a valid email'

            return true;
        },
        password(value) {

            if (!value) {
                return 'Password required';
            }

            if (value.length < 8) return 'Password must be at least 8 characters'

            return true;
        },
    },
})

const email = useField('email')
const password = useField('password')

const items = ref([
    'Item 1',
    'Item 2'
])

const submit = handleSubmit(values => {
    const authStore = useAuthStore();
    const { email, password } = values;
    return authStore.login(email, password)
        .catch(error => {
            console.log(error)
        });
})

</script>

<template>
    <HeaderLogin />
    <v-card elevation="8" width="500" rounded="lg" class="ma-auto pa-12 pb-8 ">
        <v-card-title class="text-center">Login</v-card-title>
        <v-card-item>
            <v-sheet>
                <v-form @submit.prevent="submit">
                    <v-text-field v-model="email.value.value" :error-messages="email.errorMessage.value" label="Email"
                        variant="solo" append-inner-icon="mdi-email"></v-text-field>
                    <v-text-field v-model="password.value.value" :error-messages="password.errorMessage.value"
                        type="password" label="Password" variant="solo" append-inner-icon="mdi-lock"></v-text-field>
                    <v-btn type="submit" block class="mt-2 w-50 rounded" color="indigo-darken-4">Sign In</v-btn>
                </v-form>
            </v-sheet>
        </v-card-item>

        <v-card-action>
            <RouterLink class="text-center" to="/register">
                Sign Up
            </RouterLink>
        </v-card-action>
    </v-card>
</template>
