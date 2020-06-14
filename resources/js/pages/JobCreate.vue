<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Post a new job</div>
          <div class="card-body">
            <div class="form-group">
              <label for="title">Title</label>
              <input v-model="job.title" type="text" class="form-control" name="title" />
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <textarea v-model="job.description" class="form-control" name="description" />
            </div>
            <div class="form-group">
              <label for="role">Role</label>
              <textarea v-model="job.roles" class="form-control" name="role" />
            </div>
            <div class="form-group">
              <label for="position">Position</label>
              <input v-model="job.position" class="form-control" name="position" />
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <input v-model="job.address" class="form-control" name="address" />
            </div>
            <div class="form-group">
              <label for="category">Category</label>
              <select v-model="job.category_id" class="form-control" name="category">
                <option v-for="cat of categories" :key="cat.id" :value="cat.id">{{cat.name}}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="category">Type</label>
              <select v-model="job.type" class="form-control" name="type">
                <option v-for="type of types" :key="type.value" :value="type.value">{{type.text}}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="lastdate">Expirey Date</label>
              <input
                type="date"
                v-model="job.last_date"
                class="form-control"
                name="lastdate"
                :min="today"
              />
            </div>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remote" v-model="job.remote" />
                <label class="form-check-label" for="remote">Can be remote</label>
              </div>
            </div>
            <button class="btn btn-dark" @click="submit">Submit</button>
          </div>
        </div>
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
      categories: [],
      types: [
        { text: "Full time", value: "fulltime" },
        { text: "Part time", value: "parttime" },
        { text: "Temporary", value: "temporary" },
        { text: "Contract", value: "contract" },
        { text: "Contract to hire", value: "contractforhire" },
        { text: "Internship", value: "internship" }
      ]
    };
  },
  async mounted() {
    try {
      const { data } = await Api.get("categories");
      console.log("Cats", data);
      this.categories = data.categories;
    } catch (error) {
      this.$toasted.error("Could not fetch categories");
    }
  },
  methods: {
    async submit() {
      try {
        const { data } = await Api.post("jobs/create", this.job);
        this.$toasted.success(data.message);
      } catch (error) {
        console.log("ERROR", error);
        this.$toasted.error(error.messages[0]);
      }
    }
  },
  computed: {
    today() {
      const d = new Date();
      const year = d.getFullYear();
      const month = (d.getMonth() + 1).toString().padStart(2, "0");
      const day = d
        .getDate()
        .toString()
        .padStart(2, "0");
      return `${year}-${month}-${day}`;
    }
  }
};
</script>