<script setup>
import SideBar from "@/components/SideBar.vue"
import TableData from "@/components/TableData.vue";
</script>

<template>
    <v-layout class="flex-row">
        <SideBar />
        <v-main>
            <TableData />
        </v-main>
    </v-layout>
</template>