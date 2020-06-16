<template>
  <table class="table">
    <thead>
      <th></th>
      <th>Position</th>
      <th>Location</th>
      <th>Posted</th>
      <th></th>
    </thead>
    <tbody v-if="jobs.length > 0">
      <tr v-for="job in jobs" :key="'job' + job.id">
        <td>
          <img
            v-if="job.company && job.company.logo"
            :src="job.company.logo"
            alt="company logo"
            width="40"
          />
          <img v-else :src="'/images/avatar_default.svg'" alt="company logo" width="40" />
        </td>
        <td>
          {{job.position}}
          <br />
          <i class="far fa-clock" aria-hidden="true"></i>
          {{job.type}}
        </td>
        <td>
          <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
          {{ job.address }}
        </td>
        <td>
          <i class="fas fa-calendar-day" aria-hidden="true"></i>
          {{job.created_at | formatDateTimeAgo }}
        </td>
        <td>
          <div class="d-flex">
            <router-link
              :to="{ name: 'job-details', params: { id: job.id, job: job.slug }}"
              class="btn btn-info btn-sm mr-1"
            >Apply</router-link>
            <template v-if="$auth.user && $auth.user.id === job.user_id">
              <router-link
                class="btn btn-success btn-sm mr-1"
                :to="{name: 'job-edit', params: { id: job.id, job: job.slug }}"
              >Edit</router-link>
              <button class="btn btn-danger btn-sm" @click="deleteJob(job.id)">Delete</button>
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
    jobs: {
      type: Array,
      required: true
    }
  },
  methods: {
    async deleteJob(id) {
      console.log("ID", id);
      try {
        const { data } = await Api.post("jobs/delete", { id });
        this.$toasted.success("Delete Successfull");
        this.$emit("delete", id);
      } catch (error) {
        this.$toasted.error();
      }
    }
  }
};
</script>