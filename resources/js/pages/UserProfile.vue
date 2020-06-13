<template>
  <div class="container" v-if="loaded">
    <div class="row">
      <div class="col-md-2">
        <img v-if="user.profile_data.avatar" :src="user.profile_data.avatar" width="100%" />
        <img v-else :src="'/images/avatar_default.svg'" width="100%" />
        <input
          @change="updateAvatar"
          id="upload_avatar_button"
          hidden
          type="file"
          class="form-control"
          name="cover_letter"
        />
        <br />
        <div class="form-group text-center mt-2">
          <button class="btn btn-success btn-sm" @click="selectAvatar">Update</button>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">Update your profile</div>
          <div class="card-body">
            <div class="form-group">
              <label for="address">Address</label>
              <input
                v-model="user.profile_data.address"
                type="text"
                class="form-control"
                name="address"
              />
            </div>
            <div class="form-group">
              <label for="address">Phone Number</label>
              <input
                v-model="user.profile_data.phone_number"
                type="tel"
                class="form-control"
                name="phone_number"
              />
            </div>
            <div class="form-group">
              <label for>Bio</label>
              <input v-model="user.profile_data.bio" type="text" class="form-control" name="bio" />
            </div>
            <div class="form-group">
              <label for="experience">Work Experience</label>
              <textarea
                v-model="user.profile_data.experience"
                type="text"
                class="form-control"
                name="experience"
              />
            </div>
            <div class="form-group">
              <button class="btn btn-success" @click="updateInfo">Update</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Your Information</div>
          <div class="card-body">
            <p>Name: {{user.name}}</p>
            <p>Email: {{user.email}}</p>
            <p>Address: {{user.profile_data.address}}</p>
            <p>Member since: {{user.created_at | formatDate }}</p>
            <p>
              <a href="#" @click="download(user.profile_data.resume)">Download Resume</a>
              <br />
              <a href="#" @click="download(user.profile_data.cover_letter)">Download Cover Letter</a>
            </p>
          </div>
        </div>
        <br />
        <div class="card">
          <div class="card-header">Resume</div>
          <div class="card-body">
            <input @change="onFileChange" type="file" class="form-control" name="resume" />
            <br />
            <div class="form-group">
              <button class="btn btn-success float-right" @click="updateResume">Update</button>
            </div>
          </div>
        </div>
        <br />
        <div class="card">
          <div class="card-header">Cover Letter</div>
          <div class="card-body">
            <input @change="onFileChange" type="file" class="form-control" name="cover_letter" />
            <br />
            <div class="form-group">
              <button class="btn btn-success float-right" @click="updateCoverLetter">Update</button>
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
      resume: null,
      coverLetter: null,
      user: {},
      file: null,
      loaded: false
    };
  },
  async mounted() {
    try {
      const { data } = await Api.get("user/profile");
      console.log("USER", data);
      this.user = data.profile;
      this.loaded = true;
    } catch (error) {}
  },
  methods: {
    async updateInfo() {
      try {
        const { data } = await Api.put("user/profile", {
          address: this.user.profile_data.address,
          phone_number: this.user.profile_data.phone_number,
          bio: this.user.profile_data.bio,
          experience: this.user.profile_data.experience
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
    selectAvatar() {
      document.getElementById("upload_avatar_button").click();
    },
    async updateAvatar(e) {
      const files = e.target.files;
      if (!files.length) return;
      const data = new FormData();
      data.append("image", files[0]);
      try {
        const resp = await Api.postWithProgress("user/profile/avatar", data);
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
    async download(filepath) {
      const { data } = await axios.post(`/api/v1/download`, { path: filepath });
      window.location.href = data;
    }
  }
};
</script>