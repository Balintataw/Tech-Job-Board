<template>
  <div v-if="loaded" class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{job.title}}</div>

          <div class="card-body">
            <h3>Description</h3>
            <p>{{job.description}}</p>
            <h3>Duties</h3>
            <p>{{job.roles}}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">Company Info</div>
          <div class="card-body">
            <p>
              Company:
              <router-link
                :to="{ 
                   name: 'company', 
                   params: {
                    id: job.company.id, company: job.company.slug
                  }
                }"
              >{{job.company.name}}</router-link>
            </p>
            <p>Location: {{job.address}}</p>
            <p>Employment Type: {{job.type}}</p>
            <p>Position: {{job.position}}</p>
            <p>Posted: {{job.created_at | formatDateTimeAgo }}</p>
          </div>
        </div>
        <br />
        <!-- TODO do not allow application with incomplete profile data -->
        <button
          class="btn btn-success"
          :disabled="disableApply"
          @click="apply"
          style="width: 100%;"
        >{{disableApply ? 'Already applied' : 'Apply' }}</button>
      </div>
    </div>
  </div>
</template>
<script>
import Api from "@/api";
export default {
  data() {
    return {
      job: {},
      loaded: false,
      apply_sucess: false
    };
  },
  async mounted() {
    const params = this.$route.params;
    try {
      const {
        data: { job }
      } = await Api.get(`jobs/${params.id}/${params.job}`);
      this.job = job;
      console.log("Job", job);
      this.loaded = true;
    } catch (error) {
      console.log("Error fetching job", error);
    }
  },
  methods: {
    async apply() {
      try {
        const {
          data: { message }
        } = await Api.post("applications", {
          job_id: this.job.id
        });
        console.log("Apply", message);
        this.apply_sucess = true;
        this.$toasted.success(message);
      } catch (error) {
        console.log("Apply error", error);
        this.$toasted.error(error.messages[0]);
      }
    }
  },
  computed: {
    disableApply() {
      return this.job.has_applied || this.apply_sucess;
    }
  }
};
</script>