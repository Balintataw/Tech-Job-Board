<template>
  <div class="container" v-if="loaded">
    <div class="row">
      <div class="col-md-3">
        <img v-if="company.logo" :src="company.logo" width="100%" />
        <img v-else :src="'/images/avatar_default.svg'" width="100%" />
        <input
          @change="updateLogo"
          id="upload_logo_button"
          hidden
          type="file"
          class="form-control"
          name="logo"
        />
        <br />
        <div class="form-group text-center mt-2">
          <button class="btn btn-dark btn-sm" @click="selectLogo">Update</button>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card">
          <div class="card-header">Update your company information</div>
          <div class="card-body">
            <div class="form-group">
              <label for="address">Address</label>
              <input v-model="company.address" type="text" class="form-control" name="address" />
            </div>
            <div class="form-group">
              <label for="address">Phone Number</label>
              <input v-model="company.phone" type="tel" class="form-control" name="phone" />
            </div>
            <div class="form-group">
              <label for>Website</label>
              <input v-model="company.website" type="text" class="form-control" name="website" />
            </div>
            <div class="form-group">
              <label for="slogan">Slogan</label>
              <textarea v-model="company.slogan" type="text" class="form-control" name="slogan" />
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea
                v-model="company.description"
                type="text"
                class="form-control"
                name="description"
              />
            </div>
            <div class="form-group">
              <button class="btn btn-dark" @click="updateInfo">Update</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Your Information</div>
          <div class="card-body">
            <p>Name: {{company.name}}</p>
            <p>Email: {{company.email}}</p>
            <p>Address: {{company.address}}</p>
            <p>Member since: {{company.created_at | formatDate }}</p>
            <p>
              <a :href="`company/${company.slug}`">View company page</a>
            </p>
          </div>
        </div>
        <br />
        <div class="card">
          <div class="card-header">Cover Photo</div>
          <div class="card-body">
            <input @change="onFileChange" type="file" class="form-control" name="cover_photo" />
            <br />
            <div class="form-group">
              <button class="btn btn-dark float-right" @click="updateCoverPhoto">Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Api from "@/api";

export default {
  data() {
    return {
      coverLetter: null,
      company: {},
      file: null,
      loaded: false
    };
  },
  async mounted() {
    try {
      const { data } = await Api.get(`company/profile`);
      console.log("Company", data);
      this.company = data.company;
      this.loaded = true;
    } catch (error) {}
  },
  methods: {
    async updateInfo() {
      try {
        const { data } = await Api.put("company/profile", { ...this.company });
        this.$toasted.success("Success");
        console.log("UPDATE", data);
      } catch (error) {
        console.log(error);
        this.$toasted.error(error.messages[0]);
      }
    },
    selectLogo() {
      document.getElementById("upload_logo_button").click();
    },
    async updateLogo(e) {
      const files = e.target.files;
      if (!files.length) return;
      const data = new FormData();
      data.append("image", files[0]);
      try {
        const resp = await Api.postWithProgress("company/profile/logo", data);
        console.log("UPDATE logo", resp);
        this.$toasted.success("Success");
      } catch (error) {
        console.log("logo update error", error);
        this.$toasted.error(error.messages[0]);
      }
    },
    async updateCoverPhoto() {
      const data = new FormData();
      data.append("image", this.file);
      try {
        const resp = await Api.postWithProgress(
          "company/profile/coverphoto",
          data
        );
        console.log("UPDATE coverphoto", resp);
        this.$toasted.success("Success");
      } catch (error) {
        console.log("coverphoto update error", error);
        this.$toasted.error(error.messages[0]);
      }
    },
    onFileChange(e) {
      const files = e.target.files;
      if (!files.length) return;
      console.log("files", files[0]);
      this.file = files[0];
    },
    async download(filepath) {
      const { data } = await axios.post(`/api/v1/download`, { path: filepath });
      window.location.href = data;
    }
  }
};
</script>