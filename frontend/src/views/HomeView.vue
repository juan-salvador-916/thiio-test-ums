<script setup>
import SideBar from "@/components/SideBar.vue"
import TableData from "@/components/TableData.vue";
</script>

<template>
  <v-layout class="flex-row">
    <SideBar />
    <v-main>
      <div class="w-100">
        <div class="w-50 mx-auto" :style="{ 'display': 'flex', 'gap': '2rem', 'margin-top':'25rem' }">
          <v-btn to="/profile" width="300" class="rounded" color="white" append-icon="mdi-account">Profile</v-btn>
          <v-btn to="/users" width="300" class="rounded" color="white" append-icon="mdi-list-box">Users</v-btn>
        </div>
      </div>

    </v-main>
  </v-layout>


</template>