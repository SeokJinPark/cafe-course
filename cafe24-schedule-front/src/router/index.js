import Vue from 'vue'
import Router from 'vue-router'
import index from '@/components/index'
import scheduleList from '@/components/schedule/list'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'main',
      component: index
    },
    {
      path: '/schedule/list',
      name: 'scheduleList',
      component: scheduleList
    }
  ]
})
