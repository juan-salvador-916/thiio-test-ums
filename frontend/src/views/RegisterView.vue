<script setup lang="ts">
import HeaderRegister from "@/components/HeaderRegister.vue";
import { ref } from 'vue'
import { useField, useForm } from 'vee-validate'

import { useAuthStore } from '@/stores/auth.store';

const { handleSubmit, handleReset } = useForm({
    validationSchema: {
        name(value) {

            if (!value) {
                return 'Name required';
            }

            if (value.length < 2) return 'Name must be at least 2 characters'

            return true;
        },
        lastName(value) {

            if (!value) {
                return 'Last name required';
            }

            if (value.length < 2) return 'Last name must be at least 2 characters'

            return true;
        },
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

const name = useField('name');
const lastName = useField('lastName');
const email = useField('email')
const password = useField('password')

const items = ref([
    'Item 1',
    'Item 2',
    'Item 3',
    'Item 4',
    
])

const submit = handleSubmit(values => {
    const authStore = useAuthStore();
    const { email, password, name, lastName } = values;
    return authStore.register(email, password, name, lastName)
        .catch(error => {
            console.log(error)
        });
})

</script>

<template>
    <HeaderRegister />
    <v-card elevation="8" width="500" rounded="lg" class="ma-auto pa-12 pb-8 ">
        <v-card-title class="text-center">Register</v-card-title>
        <v-card-item>
            <v-sheet>
                <v-form @submit.prevent="submit">
                    <v-text-field 
                        v-model="name.value.value" 
                        :error-messages="name.errorMessage.value" 
                        label="Name"
                        variant="solo" 
                        append-inner-icon="mdi-account"
                    ></v-text-field>
                    <v-text-field 
                        v-model="lastName.value.value" 
                        :error-messages="lastName.errorMessage.value" 
                        label="Last Name"
                        variant="solo" 
                        append-inner-icon="mdi-account"
                    ></v-text-field>
                    <v-text-field 
                        v-model="email.value.value" 
                        :error-messages="email.errorMessage.value" 
                        label="Email"
                        variant="solo" 
                        append-inner-icon="mdi-email"
                    ></v-text-field>
                    <v-text-field 
                        v-model="password.value.value" 
                        :error-messages="password.errorMessage.value"
                        type="password" 
                        label="Password" 
                        variant="solo" 
                        append-inner-icon="mdi-lock"
                    ></v-text-field>
                    <v-btn type="submit" block class="mt-2 w-50 rounded" color="indigo-darken-4">Sign Up</v-btn>
                </v-form>
            </v-sheet>
        </v-card-item>

        <v-card-action>
            <RouterLink class="text-center" to="/login">
                Sign In
            </RouterLink>
        </v-card-action>
    </v-card>
</template>
