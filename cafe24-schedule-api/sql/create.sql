CREATE SCHEMA IF NOT EXISTS `cafe_schedule`;
USE `schedule` ;

-- -----------------------------------------------------
-- Table `cafe_schedule`.`t_course`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cafe_schedule`.`t_course` (
  `no` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'idx',
  `name` VARCHAR(30) NOT NULL COMMENT '과목 이름',
  `color` char(6) NOT NULL COMMENT '표시될 컬러(16진수 6자리)',
  `point` SMALLINT NOT NULL DEFAULT '1' COMMENT '학점',
  PRIMARY KEY (`no`)
)ENGINE = InnoDB COMMENT = '과목 테이블';

-- -----------------------------------------------------
-- Table `cafe_schedule`.`t_course`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cafe_schedule`.`t_course_time` (
  `no` INT unsigned NOT NULL AUTO_INCREMENT COMMENT 'idx',
  `course_no` INT unsigned NOT NULL COMMENT '과목 idx',
  `parent_course_time_no` INT unsigned DEFAULT NULL COMMENT '3학점의 두번째 수업일 경우 첫번째 수업의 idx 입력',
  `start_time` SMALLINT NOT NULL COMMENT '과목 시작 시간',
  PRIMARY KEY (`no`),
  CONSTRAINT `fk_t_course_t_course_time_course_no`
    FOREIGN KEY (`course_no`)
    REFERENCES `t_course` (`no`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_t_course_time_t_course_time_parent_course_time_no`
   FOREIGN KEY (`parent_course_time_no`)
   REFERENCES `t_course_time` (`no`)
   ON DELETE NO ACTION
   ON UPDATE NO ACTION
)ENGINE = InnoDB COMMENT = '과목별 사용 수업 시간';
