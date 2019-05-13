<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_course extends CI_Model
{
  /**
   * 과목 리스트 조회
   * @return result_array
   */
  public function getCourseList() {
    $sQuery = "
      SELECT
        c.no AS course_no, c.name, c.color, c.point,
        GROUP_CONCAT(ct.no ORDER BY ct.no ASC) str_course_time_no,
        GROUP_CONCAT(ct.start_time ORDER BY ct.no ASC) AS str_star_time_list,
        GROUP_CONCAT(child_ct.no ORDER BY ct.no ASC) str_child_course_time_no,
        GROUP_CONCAT(child_ct.start_time ORDER BY ct.no ASC) AS str_child_star_time_list
      FROM
        t_course AS c
        LEFT JOIN t_course_time AS ct ON (c.no = ct.course_no)
        LEFT JOIN t_course_time AS child_ct ON (child_ct.parent_course_time_no = ct.no)
      WHERE
        ct.parent_course_time_no IS NULL
      GROUP BY
        c.no
    ";

    return $this->db->query($sQuery)->result_array();
  }
}
