<script setup lang="ts">
import userIcon from "@/assets/user-icon.webp";
import { useAuthStore } from "@/stores/auth.store";
const auth = useAuthStore();
const authenticatedUser = auth.user.user;

const logout = () => {
    const authStore = useAuthStore();
    return authStore.logout();
}

</script>


<template>
    <v-navigation-drawer mobile-breakpoint="sm" color="indigo-darken-4">
        <v-list>
            <v-list-item :prepend-avatar="userIcon" :subtitle="authenticatedUser.email"
                :title="authenticatedUser.name"></v-list-item>
        </v-list>
        <v-divider></v-divider>
        <v-list density="compact" nav>
            <v-list-item to="/" prepend-icon="mdi-home" title="Home" value="home"></v-list-item>
            <v-list-item to="/profile" prepend-icon="mdi-account" title="Profile" value="profile"></v-list-item>
            <v-list-item to="/users" prepend-icon="mdi-list-box" title="Users" value="users"></v-list-item>
            <v-list-item prepend-icon="mdi-logout" title="Logout" value="logout" @click="logout"></v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>