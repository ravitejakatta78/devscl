ALTER TABLE `faculity` ADD `faculity_pic` VARCHAR(100) NULL DEFAULT NULL AFTER `id`;

-- 15-Feb-2022
ALTER TABLE `exam_details` CHANGE `exam_date` `exam_date` DATETIME NOT NULL;

-- 26-Feb-2022
CREATE TABLE `student_marks_summary` (
    `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key' ,
    `exam_id` INT(11) NOT NULL COMMENT 'Exam Id' ,
    `student_id` INT NOT NULL COMMENT 'Student Id' ,
    `exam_status` INT(1) NOT NULL DEFAULT '1' COMMENT '1.Present,2.Absent' ,
    `total_marks` DOUBLE NOT NULL ,
    `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `created_by` INT(11) NOT NULL ,
    `updated_on` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `updated_by` INT(11) NULL DEFAULT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `student_marks_details` (
    `id` INT(11) NOT NULL AUTO_INCREMENT ,
    `summary_marks_id` INT(11) NOT NULL ,
    `subject_id` INT(11) NOT NULL ,
    `marks` DOUBLE NOT NULL ,
    `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `created_by` INT(11) NOT NULL ,
    `updated_on` INT NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `updated_by` DATETIME NULL DEFAULT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;