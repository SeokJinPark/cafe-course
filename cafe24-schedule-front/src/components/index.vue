<template>
  <div>
    <div class="left-bar">
      <div class="title">
        <span class="text">과목을 선택해주세요</span>
        <span class="total-point">총: {{iTotalPoint}}학점</span>
      </div>
      <div class="body">
        <div class="item" :class="{select: oCourseData.sIsSelect === 'T'}" v-for="(oCourseData, iIndex) in aCourseList" :key="iIndex" @click="toggleCourse(iIndex, oCourseData.sIsSelect)">
          <div class="item-name">{{oCourseData.sName}}</div>
          <label class="item-color" :style="'background-color: #' + oCourseData.sColor + ';'"></label>
          <div class="item-point">학점: {{oCourseData.iPoint}}</div>
        </div>
      </div>
    </div>
    <div class="main">
      시간표 자동생성 서비스에 오신 것을 환영합니다.<br />과목을 18~21학점 사이로 선택해주세요.
      <div class="select-course">
        <div class="course-result">
          <b-button @click.prevent="createSchedule()">생성</b-button>
          <b-form-select v-model="aSelectExclude" :options="aExcludeList" class="w-25"></b-form-select>
        </div>
        <div class="course-title">선택된 과목 상세 리스트</div>
        <b-container>
          <b-row>
            <b-col md="6" sm="6" lg="3" class="course-list" :key="iSelectCourseIndex" v-for="iSelectCourseIndex in aSelectCourseIndexList">
              <div class="course-body">
                <div class="course-name">
                  과목명: {{aCourseList[iSelectCourseIndex].sName}}
                </div>
                <div class="course-point">
                  학점: {{aCourseList[iSelectCourseIndex].iPoint}}
                </div>
                <div class="course-time-list">
                  수업시간
                  <div v-for="(aTimeList, iIndex) in aCourseList[iSelectCourseIndex].oCourseTimeDataList" :key="iIndex">
                    {{'* '}}{{getCourseTimeMessage(aTimeList[0], aTimeList[1])}}
                    {{aCourseList[iSelectCourseIndex].iPoint == 3 ? ', ' + getCourseTimeMessage(aTimeList[2], aTimeList[3]) : ''}}
                  </div>
                </div>
              </div>
            </b-col>
          </b-row>
        </b-container>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      aCourseList: [],
      aSelectCourseIndexList: [],
      iTotalPoint: 0,
      iMaxPoint: 21,
      iMinPoint: 18,
      aSelectExclude: [],
      aExcludeList: [
        {
          value: [], text: "전체"
        },
        {
          value: [1, 2, 9, 10, 17, 18, 25, 26, 33, 34], text: "오전수업 제외(1~2교시)"
        },
        {
          value: [1, 2, 3, 4, 5, 6, 7, 8], text: "월요일 전체 제외"
        },
        {
          value: [9, 10, 11, 12, 13, 14, 15, 16], text: "화요일 전체 제외"
        },
        {
          value: [17, 18, 19, 20, 21, 22, 23, 24], text: "수요일 전체 제외"
        },
        {
          value: [25, 26, 27, 28, 29, 30, 31, 32], text: "목요일 전체 제외"
        },
        {
          value: [33, 34, 35, 36, 37, 38, 39, 40], text: "금요일 전체 제외"
        },
      ]
    }
  },
  methods: {
    getCourseTimeMessage(iStartTime, iEndTime) {
      return this.getWeek(iStartTime) + ': ' + this.getcourseTime(iStartTime) + ' ~ ' + this.getcourseTime(iEndTime) + '교시'
    },
    getcourseTime(iTime) {
      let iResultTime = iTime%8

      return iResultTime === 0 ? 8 : iResultTime
    },
    getWeek(iTime) {
      let iTimeResult = Math.ceil(iTime/8)
      let sWeek = ''

      switch (iTimeResult) {
        case 1:
          sWeek = '월요일'
          break
        case 2:
          sWeek = '화요일'
          break
        case 3:
          sWeek = '수요일'
          break
        case 4:
          sWeek = '목요일'
          break
        case 5:
          sWeek = '금요일'
          break
      }

      return sWeek
    },
    toggleCourse(iIndex, sIsSelect) {
      if ( sIsSelect === 'T' ) {
        const iSelectIndex = this.aSelectCourseIndexList.indexOf(iIndex)
        if (iSelectIndex > -1) this.aSelectCourseIndexList.splice(iSelectIndex, 1)

        this.aCourseList[iIndex].sIsSelect = 'F'
        this.iTotalPoint -= this.aCourseList[iIndex].iPoint
      } else {
        if ( this.iMaxPoint < (this.iTotalPoint + this.aCourseList[iIndex].iPoint) ) {
          alert('총 21학점 이상은 불가능합니다')
          return
        }
        this.aCourseList[iIndex].sIsSelect = 'T'
        this.aSelectCourseIndexList.push(iIndex)
        this.iTotalPoint += this.aCourseList[iIndex].iPoint
      }
    },
    async createSchedule() {
      if ( this.iTotalPoint > this.iMaxPoint || this.iTotalPoint < this.iMinPoint ) {
        alert('총 학점은 ' + this.iMinPoint + ' ~ ' + this.iMaxPoint + '점 사이로 선택해주세요')
        return
      }
      if ( this.bCreatingSchedule ) return
      let self = this

      // // 결과 리스트
      let aResult = []

      combination(0, [])

      if ( aResult.length === 0 ) {
        alert("선택된 조건으로 시간표를 생성할 수 없습니다.")
        return;
      }

      this.$store.commit('setScheduleList', aResult)
      this.$store.commit('setCourseList', this.aCourseList)
      this.$store.commit('setSelectCourseIndesList', this.aSelectCourseIndexList)

      this.$router.push('/schedule/list')
      // 선택한 과목들에 대한 시간표 자동생성 함수(시간 중복 제거 포함)
      function combination(iSelectIndex, aPreResult) {
        // 선택된 과목 수 만큼 재귀 호출 후 결과값 검증
        if ( iSelectIndex == self.aSelectCourseIndexList.length ) {
          // 중복된 시간이 제거된 시간 리스트
          let aTimeList = []
          // 전체 시간 수(1시간당 1씩증가)
          let iTimeTotalCount = 0

          for ( const iKey in aPreResult ) {
            // 과목별 선택된 시간 조회
            let aTime = self.aCourseList[self.aSelectCourseIndexList[iKey]].oCourseTimeDataList[aPreResult[iKey]]

            for ( const iTimeKey in aTime ) {
              // 시간 중복 체크 및 제외 시간 체크
              if (aTimeList.indexOf(aTime[iTimeKey]) == -1 && self.aSelectExclude.indexOf(aTime[iTimeKey]) < 0) aTimeList.push(aTime[iTimeKey])
            }

            // 과목별 학점에 따라 전체 시간 값 추가 (3학점: 4시간, 1,2학점: 2시간)
            iTimeTotalCount += self.aCourseList[self.aSelectCourseIndexList[iKey]].iPoint === 3 ? 4 : 2
          }

          // 전체 시간 수와 중복이 제거된 시간의 수가 같을 경우만 결과값에 시간표 결과값 추가
          if ( iTimeTotalCount == aTimeList.length ) aResult.push(aPreResult)
        } else {  // 선택한 과목이 다 돌때까지 combination 함수 재귀호출
          // 선택된 과목에대한 시간 리스트
          const oCourseTimeDataList = self.aCourseList[self.aSelectCourseIndexList[iSelectIndex]].oCourseTimeDataList

          for ( const iKey in oCourseTimeDataList ) {
            combination(
              (iSelectIndex + 1),
              [].concat(aPreResult, [iKey])
            )
          } // End for
        } // End if
      } // End combination function
    },  // End createSchedule
    async getCourseList() {
      try {
        let oResponse = await this.$http({
          url: '/api/course/list',
          method: 'GET',
          data: {}
        })

        const oResponseData = oResponse.data

        for ( const iKey in oResponseData.aCourseList ) {
          const oCourseData = oResponseData.aCourseList[iKey]
          this.aCourseList.push({
            oCourseTimeDataList: oCourseData.oCourseTimeDataList,
            iCourseNo: oCourseData.iCourseNo,
            iPoint: parseInt(oCourseData.iPoint),
            sColor: oCourseData.sColor,
            sName: oCourseData.sName,
            sIsSelect: 'F'
          })
        }
      } catch (e) {
        alert('과목을 조회하는 중에 에러가 발생했습니다.\n지속적으로 발생 시 고객센터에 문의 바랍니다.')
      } // End Try Catch
    } // End getCourseList function
  },
  mounted() {
    if ( this.$store.state.aCourseList.length > 0) {
      for ( const iKey in this.$store.state.aCourseList ) {
        const oCourseData = this.$store.state.aCourseList[iKey]
        this.aCourseList.push({
          oCourseTimeDataList: oCourseData.oCourseTimeDataList,
          iCourseNo: oCourseData.iCourseNo,
          iPoint: parseInt(oCourseData.iPoint),
          sColor: oCourseData.sColor,
          sName: oCourseData.sName,
          sIsSelect: 'F'
        })
      }
      this.$store.commit('setScheduleList', [])
      this.$store.commit('setSelectCourseIndesList', [])

    } else {
      this.getCourseList()
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.container {
  // padding:0px;
  margin: 0px;
}
.main {
  margin-left: 290px;
  width: auto;

  .select-course {
    margin-top: 20px;

    .course-result {
      margin-bottom: 12px;
    }

    .course-title {
      font-weight: bold;
    }

    .course-list {
      padding: 5px;

      .course-body {
        font-size: 14px;
        box-shadow: 0 1px 3px 0 rgba(110,111,173,.33);
        padding:5px 10px;
        min-height: 100%;
      }
    }
  }
}

.left-bar {
  position: fixed;
  overflow: hidden;
  margin-top: 34px;
  top: 0;
  right: 0;
  left: 0;
  width: 270px;
  height: 100%;
  background: #fbfbfe;
  -webkit-box-shadow: 0 1px 3px hsla(0,0%,54%,.14);
  box-shadow: 0 1px 3px hsla(0,0%,54%,.14);
  z-index: 101;

  >div {
    padding-top: 10px;
    &.title {
      padding-left: 10px;
      .text {
        font-weight: bold;
        font-size: 12px;
      }

      .total-point {
        font-weight: bold;
        font-size: 10px;
        position: relative;
        right: -70px;
      }
    }
    &.body {
      .item {
        min-width:253px;
        max-width:270px;
        padding: 10px 0px 10px 20px;
        font-size: 14px;
        border-bottom: 1px solid #e8ecfb;
        cursor: pointer;
        &.select {
          background: #f0f0f0;
        }
        .item-name {
          float:left;
          margin-right: 5px;
        }

        .item-color {
          border-radius: 50%;
          width: 10px;
          height: 10px;
        }

        .item-point {
          float: right;
          font-size: 8px;
          margin-top: 9px;
          font-weight: bold;
          vertical-align: bottom;
        }
      }
      height: calc(100vh - 78px);
      overflow-y: scroll;
      &::-webkit-scrollbar-track {
        display: none;
      }
      &::-webkit-scrollbar {
        width: 4px;
      }
      &::-webkit-scrollbar-thumb {
        border: 4px solid #c1c1c1;
        border-radius: 2px;
      }
      &::-moz-scrollbar {
        width: 4px;
      }
      &::-moz-scrollbar-track {
        display: none;
      }
      &::-moz-scrollbar-thumb {
        border: 4px solid #c1c1c1;
        border-radius: 2px;
      }
    }
  }
}
</style>
