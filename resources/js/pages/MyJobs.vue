<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h1>My Jobs</h1>
        <job-table :jobs="jobs"></job-table>
      </div>
    </div>
  </div>
</template>
<script>
import Api from "@/api";
import JobTable from "@/components/JobTable";

export default {
  components: {
    "job-table": JobTable
  },
  data() {
    return {
      jobs: []
    };
  },
  async mounted() {
    try {
      const { data } = await Api.get("jobs/my-jobs");
      console.log("MY JOBS", data);
      this.jobs = data.jobs;
    } catch (error) {
      this.$toasted.error(error.messages[0]);
    }
  }
};
</script>