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
          <img :src="'/images/avatar_default.svg'" alt="company logo" width="40" />
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
          <router-link
            :to="{ name: 'job-details', params: { id: job.id, job: job.slug }}"
            class="btn btn-success btn-sm"
          >Apply</router-link>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
export default {
  props: {
    jobs: {
      type: Array,
      required: true
    }
  }
};
</script>