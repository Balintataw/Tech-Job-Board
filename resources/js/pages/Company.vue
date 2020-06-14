<template>
  <div v-if="loaded" class="col-md-12">
    <div class="company-profile">
      <img v-if="company.cover_photo" :src="company.cover_photo" class="img-fluid" height="400" />
      <img v-else :src="'/images/cover_default.jpg'" class="img-fluid" />
      <div class="company-description">
        <div class="d-flex align-items-center my-2">
          <img v-if="company.logo" :src="company.logo" class="img-fluid" width="80" />
          <img v-else :src="'/images/avatar_default.svg'" width="80" />
          <h3 class="mb-0 ml-2">{{company.name}}</h3>
        </div>
        <p>{{company.slogan}}</p>
        <p>{{company.address}}</p>
        <p>{{company.phone}}</p>
        <p>{{company.website}}</p>
      </div>
      <div>
        <h3>Available Jobs</h3>
        <job-table :jobs="company.jobs"></job-table>
      </div>
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
      company: {},
      loaded: false
    };
  },
  async mounted() {
    const params = this.$route.params;
    try {
      const { data } = await Api.get(`company/${params.id}/${params.company}`);
      this.company = data.company;
      this.loaded = true;
      console.log("FOUND", data);
    } catch (error) {
      console.log("Error fetching company", error);
    }
  }
};
</script>
<style scoped>
</style>