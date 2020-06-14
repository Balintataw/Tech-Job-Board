<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Login</div>

          <div class="card-body">
            <ValidationObserver v-slot="{ handleSubmit, invalid }">
              <form @submit.prevent="handleSubmit(onSubmit)" aria-label="Login">
                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                  <div class="col-md-6">
                    <ValidationProvider rules="required|email" v-slot="{ errors }">
                      <input
                        v-model="email"
                        type="email"
                        class="form-control"
                        :class="errors[0] ? 'is-invalid' : ''"
                        name="email"
                        required
                        autocomplete="email"
                        autofocus
                      />
                      <span>{{ errors[0] }}</span>
                    </ValidationProvider>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                  <div class="col-md-6">
                    <ValidationProvider rules="required" v-slot="{ errors }">
                      <input
                        v-model="password"
                        type="password"
                        class="form-control"
                        :class="errors[0] ? 'is-invalid' : ''"
                        name="password"
                        required
                        autocomplete="current-password"
                      />
                      <span>{{ errors[0] }}</span>
                    </ValidationProvider>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        v-model="rememberMe"
                      />
                      <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                  </div>
                </div>

                <div class="form-group row mb-0">
                  <div class="col-md-8 offset-md-4">
                    <button type="submit" :disabled="invalid" class="btn btn-primary">Login</button>
                    <router-link to="/" class="btn btn-link">Forgot Password?</router-link>
                  </div>
                </div>
              </form>
            </ValidationObserver>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, extend, ValidationObserver } from "vee-validate";
import { required, email } from "vee-validate/dist/rules";
import Auth from "@/api/auth.js";

extend("email", email);
extend("required", {
  ...required,
  message: "This field is required"
});

export default {
  components: {
    ValidationObserver,
    ValidationProvider
  },
  data: () => ({
    email: "",
    password: "",
    rememberMe: false
  }),
  methods: {
    async onSubmit() {
      console.log(this.email, this.password, this.rememberMe);
      this.$auth.login(this.email, this.password);
    }
  }
};
</script>