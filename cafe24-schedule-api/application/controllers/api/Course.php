<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Restserver\Libraries\REST_Controller;
require(APPPATH . 'libraries/REST_Controller.php');

class Course extends REST_Controller
{
  public function list_get ()   {
    $this->load->model('model_course');
    $aCourseList = array();
    $aRows = $this->model_course->getCourseList();

    foreach ($aRows as $ikey => $oRow) {
      $aCourseTimeDataList = array();
      $aCourseTimeNoList = explode(',', $oRow['str_course_time_no']);
      $aStartTimeList = explode(',', $oRow['str_star_time_list']);
      $aChildTimeList = $oRow['point'] == 3 ? explode(',', $oRow['str_child_star_time_list']) : array();
      $oCourseTimeDataList = array();

      foreach ($aCourseTimeNoList as $iCourseListKey => $sValue) {
        $oCourseTimeDataList[$aCourseTimeNoList[$iCourseListKey]] = array();

        $oCourseTimeDataList[$aCourseTimeNoList[$iCourseListKey]][] = intval($aStartTimeList[$iCourseListKey]);
        $oCourseTimeDataList[$aCourseTimeNoList[$iCourseListKey]][] = intval($aStartTimeList[$iCourseListKey]) + 1;

        if ( !empty($aChildTimeList) ) {
          $oCourseTimeDataList[$aCourseTimeNoList[$iCourseListKey]][] = intval($aChildTimeList[$iCourseListKey]);
          $oCourseTimeDataList[$aCourseTimeNoList[$iCourseListKey]][] = $aChildTimeList[$iCourseListKey] + 1;
        }
      }

      $aCourseList[$oRow['course_no']] = array(
        'sName' => $oRow['name'],
        'sColor' => $oRow['color'],
        'iPoint' => $oRow['point'],
        'oCourseTimeDataList' => $oCourseTimeDataList
      );
    }

    $this->response(array(
      'aCourseList' =>$aCourseList
    ), 200);
  }
}
