<template>
  <div>
    <input
      ref="input"
      id="image_select_button"
      hidden
      type="file"
      name="image"
      accept="image/*"
      @change="setImage"
    />
    <div class="form-group text-center">
      <button class="btn btn-dark btn-sm" @click="selectImage">{{ buttonText }}</button>
    </div>

    <div
      v-if="show"
      class="modal fade show"
      style="display: block;"
      id="exampleModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" @click="close" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <vue-cropper
              ref="cropper"
              :aspect-ratio="aspectRatio"
              :src="imgSrc"
              @cropend="cropEnd"
            />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="close">Close</button>
            <button type="button" class="btn btn-dark" @click.prevent="cropImage">Crop</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";

export default {
  components: {
    "vue-cropper": VueCropper
  },
  props: {
    aspectRatio: {
      type: Number,
      default: 1
    },
    buttonText: {
      type: String,
      default: "Select Image"
    }
    // imgSrc: {
    //   type: String,
    //   required: false,
    //   default: null
    // }
  },
  data() {
    return {
      imgSrc: "",
      cropImg: "",
      show: false
    };
  },
  methods: {
    dataURLtoFile(dataurl, filename) {
      var arr = dataurl.split(","),
        mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]),
        n = bstr.length,
        u8arr = new Uint8Array(n);

      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }

      return new File([u8arr], filename, { type: mime });
    },
    cropImage() {
      // get image data for post processing, e.g. upload or setting image src
      this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.show = false;
      const file = this.dataURLtoFile(this.cropImg, "temp");
      this.$emit("crop-complete", file);
    },
    cropEnd(e) {
      this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
    },
    selectImage() {
      document.getElementById("image_select_button").click();
    },
    setImage(e) {
      this.show = true;
      const file = e.target.files[0];
      if (file.type.indexOf("image/") === -1) {
        alert("Please select an image file");
        return;
      }
      if (typeof FileReader === "function") {
        const reader = new FileReader();
        reader.onload = event => {
          this.imgSrc = event.target.result;
          // rebuild cropperjs with the updated source
          this.$refs.cropper.replace(event.target.result);
        };
        reader.readAsDataURL(file);
      } else {
        alert("Sorry, FileReader API not supported");
      }
    },
    close() {
      this.show = false;
      this.imgSrc = "";
      this.cropImg = "";
    }
  }
};
</script>