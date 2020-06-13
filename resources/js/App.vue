<template>
  <div>
    <navigation />

    <div class="container">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
import Navigation from "@/components/Navigation";

export default {
  components: {
    navigation: Navigation
  },
  mounted() {
    console.log("app.js loaded");
  }
};
</script>
