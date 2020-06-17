<template>
  <div class="container" v-if="loaded">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card">
          <router-link
            :to="{ name: 'job-details', params: { id: job.id, job: job.slug }}"
            class="card-header"
          >{{ job.title }}</router-link>
        </div>
        <applicants-table :applicants="job.users"></applicants-table>
      </div>
    </div>
  </div>
</template>
<script>
import Api from "@/api";
import ApplicantsTable from "@/components/ApplicantsTable";

export default {
  components: {
    "applicants-table": ApplicantsTable
  },
  data() {
    return {
      job: {},
      loaded: false
    };
  },
  async mounted() {
    const { job_id } = this.$route.params;
    try {
      const {
        data: { job }
      } = await Api.get(`applications/job/applicants/${job_id}`);
      console.log("JOB Applicants", job);
      this.job = job;
      this.loaded = true;
    } catch (error) {
      this.$toasted.error(error.messages[0]);
    }
  }
};
</script>