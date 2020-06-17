<template>
  <table class="table">
    <thead>
      <th></th>
      <th>Name</th>
      <th>email</th>
      <th>Resume</th>
      <th>Cover Letter</th>
      <th></th>
    </thead>
    <tbody v-if="applicants.length > 0">
      <tr v-for="applicant in applicants" :key="'applicant' + applicant.id">
        <td>
          <img
            v-if="applicant.profile && applicant.profile.avatar"
            :src="applicant.profile.avatar"
            alt="user avatar"
            width="40"
          />
          <img v-else :src="'/images/avatar_default.svg'" alt="user avatar" width="40" />
        </td>
        <td>{{ applicant.name }}</td>
        <td>{{ applicant.email }}</td>
        <td>{{ applicant.profile.resume }}</td>
        <td>{{ applicant.profile.cover_letter }}</td>
        <td>
          <div class="d-flex">
            <router-link
              :to="{ name: 'user-profile', params: { id: applicant.id} }"
              class="btn btn-info btn-sm mr-1"
            >View</router-link>
            <template v-if="$auth.user && $auth.user.id === applicant.user_id">
              <router-link
                class="btn btn-success btn-sm mr-1"
                :to="{name: 'applicant-edit', params: { id: applicant.id, applicant: applicant.slug }}"
              >Edit</router-link>
              <button class="btn btn-danger btn-sm" @click="deleteJob(applicant.id)">Delete</button>
            </template>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
import Api from "@/api";

export default {
  props: {
    applicants: {
      type: Array,
      required: true
    }
  },
  methods: {
    async deleteJob(id) {
      try {
        const { data } = await Api.post("applicants/delete", { id });
        this.$toasted.success("Delete Successfull");
        this.$emit("delete", id);
      } catch (error) {
        this.$toasted.error();
      }
    }
  }
};
</script>