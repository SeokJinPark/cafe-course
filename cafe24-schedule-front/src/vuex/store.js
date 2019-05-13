import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    aScheduleList: [],
    aCourseList: [],
    aSelectCourseIndexList: []
  },
  mutations: {
    setScheduleList(state, aScheduleList) {
      state.aScheduleList = aScheduleList
    },
    setCourseList(state, aCourseList) {
      state.aCourseList = aCourseList
    },
    setSelectCourseIndesList(state, aSelectCourseIndexList) {
      state.aSelectCourseIndexList = aSelectCourseIndexList
    }
  },
  action: {
  }
})

export default store
