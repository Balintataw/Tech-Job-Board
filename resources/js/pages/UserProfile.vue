<template>
  <div class="container" v-if="loaded">
    <div class="row">
      <div class="col-md-3">
        <img v-if="user.profile.avatar" :src="user.profile.avatar" width="100%" />
        <img v-else :src="'/images/avatar_default.svg'" width="100%" />
        <div v-if="isOwner" class="form-group text-center mt-2">
          <image-cropper :aspectRatio="1" buttonText="Update Avatar" @crop-complete="updateAvatar"></image-cropper>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Update your profile</div>
          <div class="card-body">
            <div class="form-group">
              <label for="address">Address</label>
              <input
                v-if="isOwner"
                v-model="user.profile.address"
                type="text"
                class="form-control"
                name="address"
              />
              <p v-else>{{user.profile.address}}</p>
            </div>
            <div class="form-group">
              <label for="address">Phone Number</label>
              <input
                v-if="isOwner"
                v-model="user.profile.phone_number"
                type="tel"
                class="form-control"
                name="phone_number"
              />
              <p v-else>{{user.profile.phone_number}}</p>
            </div>
            <div class="form-group">
              <label for>Bio</label>
              <input
                v-if="isOwner"
                v-model="user.profile.bio"
                type="text"
                class="form-control"
                name="bio"
              />
              <p v-else>{{user.profile.bio}}</p>
            </div>
            <div class="form-group">
              <label for="experience">Work Experience</label>
              <textarea
                v-if="isOwner"
                v-model="user.profile.experience"
                type="text"
                class="form-control"
                name="experience"
              />
              <p v-else>{{user.profile.experience}}</p>
            </div>
            <div v-if="isOwner" class="form-group">
              <button class="btn btn-success" @click="updateInfo">Update</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">Your Information</div>
          <div class="card-body">
            <p>Name: {{user.name}}</p>
            <p>Email: {{user.email}}</p>
            <p>Address: {{user.profile.address}}</p>
            <p>Member since: {{user.created_at | formatDate }}</p>
            <p>
              <a href="#" @click="download(user.profile.resume)">Download Resume</a>
              <br />
              <a href="#" @click="download(user.profile.cover_letter)">Download Cover Letter</a>
            </p>
          </div>
        </div>
        <br />
        <div v-if="isOwner" class="card">
          <div class="card-header">Resume</div>
          <div class="card-body">
            <input @change="onFileChange" type="file" class="form-control" name="resume" />
            <br />
            <div class="form-group">
              <button class="btn btn-dark float-right" @click="updateResume">Update</button>
            </div>
          </div>
        </div>
        <br />
        <div v-if="isOwner" class="card">
          <div class="card-header">Cover Letter</div>
          <div class="card-body">
            <input @change="onFileChange" type="file" class="form-control" name="cover_letter" />
            <br />
            <div class="form-group">
              <button class="btn btn-dark float-right" @click="updateCoverLetter">Update</button>
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
      resume: null,
      coverLetter: null,
      user: {},
      file: null,
      loaded: false
    };
  },
  async mounted() {
    const { id } = this.$route.params;
    try {
      const {
        data: { user }
      } = await Api.get(`user/profile/${id}`);
      console.log("USER w/profile", user);
      this.user = user;
      this.loaded = true;
    } catch (error) {}
  },
  computed: {
    isOwner() {
      return this.$auth.user.id === this.user.id;
    }
  },
  methods: {
    async updateInfo() {
      try {
        const { data } = await Api.put("user/profile", {
          address: this.user.profile.address,
          phone_number: this.user.profile.phone_number,
          bio: this.user.profile.bio,
          experience: this.user.profile.experience
        });
        this.$toasted.success("Success");
        console.log("UPDATE", data);
      } catch (error) {
        console.log(error);
        const msg = error.messages[0];
        this.$toasted.error(msg);
      }
    },
    async updateResume() {
      const data = new FormData();
      data.append("file", this.file);
      try {
        const resp = await Api.postWithProgress("user/profile/resume", data);
        console.log("UPDATE Resume", resp);
        this.$toasted.success("Success");
      } catch (error) {
        console.log("resume update error", error);
        this.$toasted.error("Error");
      }
    },
    async updateAvatar(file) {
      const data = new FormData();
      data.append("image", file);
      try {
        const resp = await Api.postWithProgress(
          "user/profile/avatar",
          data,
          this.onProgress
        );
        console.log("UPDATE Avatar", resp);
        this.$toasted.success("Success");
      } catch (error) {
        console.log("avatar update error", error);
        this.$toasted.error("Error");
      }
    },
    async updateCoverLetter() {
      const data = new FormData();
      data.append("file", this.file);
      try {
        const resp = await Api.postWithProgress(
          "user/profile/coverletter",
          data
        );
        console.log("UPDATE coverletter", resp);
        this.$toasted.success("Success");
      } catch (error) {
        console.log("resume update error", error);
        this.$toasted.error("Error");
      }
    },
    onFileChange(e) {
      const files = e.target.files;
      if (!files.length) return;
      console.log("files", files[0]);
      this.file = files[0];
    },
    onProgress(progress) {
      console.log("progress", progress);
    },
    async download(filepath) {
      const { data } = await axios.post(`/api/v1/download`, { path: filepath });
      window.location.href = data;
    }
  }
};
</script>