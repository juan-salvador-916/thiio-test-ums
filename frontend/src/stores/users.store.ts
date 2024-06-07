import { defineStore } from "pinia";

import userService from "@/services/user.service";

export const useUsersStore = defineStore({
  id: "users",
  state: () => ({
    users: {
      loading: false,
      data: [],
      error: "",
    },
    userShown: {
      loading: false,
      data: {},
      error: "",
    },
  }),
  actions: {
    getUsers() {
      this.users.loading = true;
      userService
        .getUsers()
        .then((users) => {
          console.log("USUARIOS: ", users.data.users)
          this.users.data = users.data.users.map((user) => user);
          this.users.error = "";
        })
        .catch((error) => (this.users.error = error))
        .finally(() => (this.users.loading = false));
    },
    getUser(id) {
      this.userShown.loading = true;
      userService
        .getUser(id)
        .then((user) => {
          this.userShown.data = user.data.user;
          this.userShown.error = "";
        })
        .catch((error) => (this.userShown.error = error))
        .finally(() => (this.userShown.loading = false));
    },
    createUser(email, password, name, last_name, role) {
      userService
        .createUser({ email, password, name, last_name, role })
        .then((user) => {
          this.getUsers();
        })
        .catch((error) => (this.users.error = error));
    },

    updateUser(user, id) {
      userService
        .updateUser(user, id)
        .then((user) => {
          this.getUsers();
        })
        .catch((error) => (this.users.error = error));
    },

    deleteUser(id) {
        userService
          .deleteUser(id)
          .then((user) => {
            this.getUsers();
          })
          .catch((error) => (this.users.error = error));
      },
  },
});
