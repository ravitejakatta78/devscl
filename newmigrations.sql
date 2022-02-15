ALTER TABLE `faculity` ADD `faculity_pic` VARCHAR(100) NULL DEFAULT NULL AFTER `id`;

-- 15-Feb-2022
ALTER TABLE `exam_details` CHANGE `exam_date` `exam_date` DATETIME NOT NULL; 