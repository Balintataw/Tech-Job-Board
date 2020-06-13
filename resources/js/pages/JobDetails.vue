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
        <!-- @if(Auth::check() && Auth::user()->user_type='seeker') -->
        <br />
        <button class="btn btn-success" style="width: 100%;">Apply</button>
        <!-- @endif -->
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
      loaded: false
    };
  },
  async mounted() {
    const params = this.$route.params;
    try {
      const { data } = await Api.get(`jobs/${params.id}/${params.job}`);
      this.job = data.job;
      this.loaded = true;
      console.log("FOUND", data);
    } catch (error) {
      console.log("Error fetching job", error);
    }
  }
};
</script>