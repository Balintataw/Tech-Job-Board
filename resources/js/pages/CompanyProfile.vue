<template>
  <div class="container" v-if="loaded">
    <div class="row">
      <div class="col-md-3">
        <img v-if="company.logo" :src="company.logo" width="100%" />
        <img v-else :src="'/images/avatar_default.svg'" width="100%" />
        <div class="form-group text-center mt-2">
          <image-cropper :aspectRatio="1" buttonText="Update Logo" @crop-complete="updateLogo"></image-cropper>
        </div>
      </div>
      <div class="col-md-6">
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
              <label for="website">Website</label>
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
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">Your Information</div>
          <div class="card-body">
            <p>Name: {{company.name}}</p>
            <p>Email: {{company.email}}</p>
            <p>Address: {{company.address}}</p>
            <p>Member since: {{company.created_at | formatDate }}</p>
            <p>
              <router-link :to="`/company/${company.id}/${company.slug}`">View company page</router-link>
            </p>
          </div>
        </div>
        <br />
        <div class="card">
          <div class="card-header">Cover Photo</div>
          <div class="card-body">
            <img v-if="company.cover_photo" :src="company.cover_photo" width="100%" />
            <div class="mt-3">
              <image-cropper :aspectRatio="16/7" @crop-complete="updateCoverPhoto"></image-cropper>
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
import ImageCropper from "@/components/ImageCropper";

export default {
  components: {
    "image-cropper": ImageCropper
  },
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
    async updateLogo(file) {
      const data = new FormData();
      data.append("image", file);
      try {
        const resp = await Api.postWithProgress("company/profile/logo", data);
        console.log("UPDATE logo", resp);
        this.$toasted.success("Success");
      } catch (error) {
        console.log("logo update error", error);
        this.$toasted.error(error.messages[0]);
      }
    },
    async updateCoverPhoto(file) {
      console.log("onComplete", file);
      const data = new FormData();
      data.append("image", file);
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
    async download(filepath) {
      const { data } = await axios.post(`/api/v1/download`, { path: filepath });
      window.location.href = data;
    }
  }
};
</script>