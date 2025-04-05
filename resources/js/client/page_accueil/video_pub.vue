<template>
  <div class="video-container">
    <div class="video-wrapper">
      <video ref="videoPlayer" class="w-100" height="300">
        <source src="/resources/images/video_pub.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="play-button" @click="togglePlay" v-show="!isPlaying">
        <i class="fas fa-play"></i>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VideoComponent',
  data() {
    return {
      isPlaying: false
    }
  },
  methods: {
    togglePlay() {
      if (this.$refs.videoPlayer.paused) {
        this.$refs.videoPlayer.play();
        this.isPlaying = true;
      } else {
        this.$refs.videoPlayer.pause();
        this.isPlaying = false;
      }
    }
  },
  mounted() {
    this.$refs.videoPlayer.addEventListener('pause', () => {
      this.isPlaying = false;
    });
    this.$refs.videoPlayer.addEventListener('play', () => {
      this.isPlaying = true;
    });
  }
};
</script>

<style scoped>
.video-container {
  width: 100%;
  height: 500px;
  overflow: hidden;
}

.video-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
}

.play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 80px;
  height: 80px;
  background-color: rgba(42, 144, 217, 0.9);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.play-button i {
  color: white;
  font-size: 30px;
  margin-left: 5px;
}

.play-button:hover {
  background-color: rgba(30, 108, 168, 0.9);
  transform: translate(-50%, -50%) scale(1.1);
}

video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
