<template>
  <div id="header" class="w-100 layout-header">
    <div class="top-bar">
      <span class="back-btn" v-if="$route.path != '/'" @click="$router.push('/')">&#x21E6;</span>
      <span class="service-name">시간표 자동생성 서비스</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Header',
  methods: {
  }
}
</script>

<style lang="scss" scoped>
.layout-header {
  .top-bar {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 100;
    min-width: 320px;
    background: #fff;
    -webkit-box-shadow: 0 1px 3px hsla(0,0%,54%,.14);
    box-shadow: 0 1px 3px hsla(0,0%,54%,.14);
    padding: 5px 10px;
    height: 34px;

    .service-name {
      font-size: 14px;
      font-weight: bold;
      color: #5db0c5;
    }
    .back-btn {
      font-size: 20px;
      font-weight: bold;
      color: #5db0c5;
      margin-left:10px;
      cursor:pointer;
    }
  }

}
</style>
