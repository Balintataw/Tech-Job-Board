<template>
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
      <router-link :to="{ name: 'jobs' }">Laravel</router-link>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto"></ul>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <router-link :to="{ name: 'login' }" class="nav-link">Login</router-link>
          </li>
          <li class="nav-item">
            <router-link :to="{ name: 'register' }" class="nav-link">Register</router-link>
          </li>
          <li v-if="$auth.user" class="nav-item dropdown">
            <a
              id="navbarDropdown"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              {{ $auth.user.name }}
              <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <router-link
                class="dropdown-item"
                :to="{name: 'user-profile', params: {id: $auth.user.id}}"
              >My Profile</router-link>
              <router-link
                v-if="$auth.user.user_type === 'employer'"
                class="dropdown-item"
                :to="{name: 'company-profile'}"
              >Company</router-link>
              <router-link
                v-if="$auth.user.user_type === 'employer'"
                class="dropdown-item"
                :to="{name: 'my-jobs'}"
              >My Jobs</router-link>
              <div class="divider"></div>
              <a class="dropdown-item" href="#" @click="logout()">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
import Auth from "@/api/auth";

export default {
  data() {
    return {
      name: "biff"
    };
  },
  mounted() {
    this.$auth.user && console.log(this.$auth.user.name);
  },
  methods: {
    async logout() {
      this.$auth.logout();
    }
  }
};
</script>