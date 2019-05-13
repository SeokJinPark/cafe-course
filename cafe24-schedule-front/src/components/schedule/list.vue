<template>
  <div>
    <b-container>
      <b-row>
        <b-col sm="12" md="8" class="title">
          선택하신 과목을 조합해 총 {{aScheduleView.length}}개의 시간표를 생성했습니다.
        </b-col>
        <b-col sm="12" md="4" class="pagination">

            <b-button variant="outline-dark" @click="changePage('down')" class="pagination-button" :disabled="iPage <= 1">‹</b-button>
            <b-form-input class="page" v-model="iPage" type="number" min="1" :max="aScheduleView.length"></b-form-input> <span>/ {{aScheduleView.length}}</span>
            <b-button variant="outline-dark" @click="changePage('up')" class="pagination-button" :disabled="iPage >= aScheduleView.length">›</b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col class="schedule-body" md="12" sm="12" lg="12">
          <!-- 시간(x교시) 리스트 -->
          <div class="class-list">
            <div>
            </div>
            <div v-for="iClass in 8" :key="iClass">
              {{iClass}}교시
            </div>
          </div>
          <!-- 요일 리스트 -->
          <div class="week">
            <div v-for="sName in aWeek" :key="sName">
              {{sName}}
            </div>
          </div>
          <!-- 과목 리스트(요일별 주 5일 수업) -->
          <div class="course" v-for="(iWeek, iWeekIndex) in 5" :key="iWeek">
            <!-- 과목 리스트(시간별 하루에 8교시)  -->
            <div
              v-for="(iClass, iClassIndex) in 8"
              :key="iClass"
              :style="aScheduleView[iPage-1][ (iClassIndex) + (iWeekIndex)*8 ] ? changeRgbColorChange(aScheduleView[iPage-1][ (iClassIndex) + (iWeekIndex)*8 ].sColor) : ''"
            >
              {{aScheduleView[iPage-1][ (iClassIndex) + (iWeekIndex)*8 ] ? aScheduleView[iPage-1][ (iClassIndex) + (iWeekIndex)*8 ].sName : ''}}
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      aWeek: [
        "월요일",
        "화요일",
        "수요일",
        "목요일",
        "금요일",
      ],
      aScheduleList: [],
      aCourseList: [],
      aSelectCourseIndexList: [],
      aScheduleView: [],
      iPage: 1
    }
  },
  watch: {
    iPage(iPage) {
      if ( iPage > this.aScheduleView.length ) {
        this.$nextTick(() => {
          this.iPage = this.aScheduleView.length
        })
      } else if ( iPage < 1 ) {
        this.$nextTick(() => {
          this.iPage = 1
        })
      }
    }
  },
  methods: {
    changePage(sType) {
      if ( sType === 'up' ) ++this.iPage
      else --this.iPage
    },
    changeRgbColorChange(sColor) {
      const iRed = parseInt(sColor.substr(0, 2), 16)
      const iGreen = parseInt(sColor.substr(2, 2), 16)
      const iBlue = parseInt(sColor.substr(4, 2), 16)
      const sTextColor = (iRed + iGreen + iBlue > 382) ? 'black' : 'white'

      return "background-color: rgba(" + iRed + ", " + iGreen + ", " + iBlue + ", 0.5); color: " + sTextColor + ";"
    }
  },
  created() {
    if (
      this.$store.state.aScheduleList.length === 0
      || this.$store.state.aCourseList.length === 0
      || this.$store.state.aSelectCourseIndexList.length === 0
    ) {
      alert('잘못된 접근입니다.')
      this.$router.push('/')
    }

    this.aScheduleList = this.$store.state.aScheduleList
    this.aCourseList =this.$store.state.aCourseList
    this.aSelectCourseIndexList = this.$store.state.aSelectCourseIndexList
    let aTemp = new Array()
    for ( let i = 0; i < 40; i++ ) {
      aTemp.push(undefined)
    }

    for ( let iKey = 0; iKey < this.aScheduleList.length; iKey++ ) {
      let aSchedule = this.aScheduleList[iKey]
      this.aScheduleView.push(aTemp.concat([]))

      for ( let iIndexKey = 0; iIndexKey < this.aSelectCourseIndexList.length; iIndexKey++ ) {
        let oCourseData = this.aCourseList[this.aSelectCourseIndexList[iIndexKey]]
        let aCourseTimeList = oCourseData.oCourseTimeDataList[aSchedule[iIndexKey]]

        for ( let iTimeListKey = 0; iTimeListKey < aCourseTimeList.length; iTimeListKey++ ) {
          this.aScheduleView[iKey][(aCourseTimeList[iTimeListKey]-1)] = {
            sName: oCourseData.sName,
            sColor: oCourseData.sColor
          }
        } // End for (aCourseTimeList)
      } // End for (aSelectCourseIndexList)
    } // End for (aScheduleList)
  } // End Created
}
</script>

<style lang="scss" scoped>
.title {
  margin-top: 15px;
  font-size: 20px;
  font-weight: bold;
}
.pagination {
  -webkit-display:flex;
  display:flex;
  -webkit-justify-content: center;
  justify-content: center;
  -webkit-align-items: center;
  align-items: center;
  margin-top: 15px;

  .page {
    height: 32px;
    margin: 3px;
    width: 60px;
  }

  span {
    font-size: 20px;
  }

  .pagination-button {
    font-size: 12px;
    margin: 3px;
  }
}
@mixin column-style() {
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
  height: 50px;
  padding: 5px;
  border-collapse: collapse;
  border: 0.5px solid #c8cceb;
  padding:0px 5px;
  margin-top:-1px;
}

.schedule-body {
  font-size: 12px;
  padding: 20px;

  .class-list {
    width: 10%;
    float:left;
    >div {
      display: flex;
      align-items: center;
      justify-content: center;
      @include column-style()
      border-right: 0px;
    }
  }
  .week {
    float: left;
    width: 90%;
    >div {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 20%;
      float: left;
      margin-left: -1px;
      @include column-style()
    }
  }
  .course {
    width: 18%;
    float: left;
    margin-left: -1px;
    >div {
      @include column-style()
    }
  }
}

</style>
