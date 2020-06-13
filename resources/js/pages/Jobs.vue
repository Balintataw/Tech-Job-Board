<template>
  <div class="container">
    <div class="row">
      <h1>Recent Jobs</h1>
      <job-table :jobs="jobs"></job-table>
    </div>
  </div>
</template>
<script>
import JobTable from "@/components/JobTable";
import Api from "@/api";

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
      const { data } = await Api.get("jobs");
      this.jobs = data.jobs;
    } catch (error) {
      console.log("error", error);
    }
  }
};
</script>