/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.6.12-log : Database - jebediah_ss
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`jebediah_ss` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `jebediah_ss`;

/*Table structure for table `ss_categories` */

DROP TABLE IF EXISTS `ss_categories`;

CREATE TABLE `ss_categories` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(50) DEFAULT NULL,
  `cat_parent` int(11) DEFAULT NULL,
  `cat_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `ss_categories` */

insert  into `ss_categories`(`cat_id`,`cat_name`,`cat_parent`,`cat_order`) values (1,'Administration',0,1),(2,'Operations',0,2),(3,'Development',0,3);

/*Table structure for table `ss_employeeavailable` */

DROP TABLE IF EXISTS `ss_employeeavailable`;

CREATE TABLE `ss_employeeavailable` (
  `eav_emp_id` int(11) NOT NULL,
  `eav_jsh_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `eav_day` int(11) DEFAULT NULL,
  `eav_value` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`eav_emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_employeeavailable` */

insert  into `ss_employeeavailable`(`eav_emp_id`,`eav_jsh_name`,`eav_day`,`eav_value`) values (2,'eav_jsh_name 1',1,1),(3,'eav_jsh_name 2',1,1),(4,'eav_jsh_name 2',1,1);

/*Table structure for table `ss_employeejobs` */

DROP TABLE IF EXISTS `ss_employeejobs`;

CREATE TABLE `ss_employeejobs` (
  `ejo_emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `ejo_job_id` int(11) DEFAULT NULL,
  `ejo_priority` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`ejo_emp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `ss_employeejobs` */

insert  into `ss_employeejobs`(`ejo_emp_id`,`ejo_job_id`,`ejo_priority`) values (2,1,1),(3,2,1);

/*Table structure for table `ss_employees` */

DROP TABLE IF EXISTS `ss_employees`;

CREATE TABLE `ss_employees` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_first_name` varchar(50) NOT NULL,
  `emp_last_name` varchar(50) NOT NULL,
  `emp_job_id` int(11) NOT NULL,
  `emp_address` varchar(255) NOT NULL,
  `emp_phone` varchar(15) NOT NULL,
  `emp_email` varchar(50) NOT NULL,
  `emp_hours` int(11) NOT NULL,
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `ss_employees` */

insert  into `ss_employees`(`emp_id`,`emp_first_name`,`emp_last_name`,`emp_job_id`,`emp_address`,`emp_phone`,`emp_email`,`emp_hours`) values (1,'Admin','Admin',1,'5th Street','2783243456','admin@mail.com',8),(2,'Kim','Sue',1,'3rd Block','0980999','kimsue@mail.com',8),(3,'Jim','Simpson',2,'24th Avenue','4728736362','jimsimpson@mail.com',8),(4,'Mark','Adam',-1,'34 Model Town','','madam@mail.com',8),(5,'John','Silver',-1,'Moon Markeet','7643653','jsilver@mail.com',8);

/*Table structure for table `ss_jobs` */

DROP TABLE IF EXISTS `ss_jobs`;

CREATE TABLE `ss_jobs` (
  `job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_name` varchar(50) DEFAULT NULL,
  `job_short` int(11) DEFAULT NULL,
  `job_start` datetime DEFAULT NULL,
  `job_end` datetime DEFAULT NULL,
  `job_start2` datetime DEFAULT NULL,
  `job_end2` datetime DEFAULT NULL,
  `job_hours` int(11) DEFAULT NULL,
  `job_parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `ss_jobs` */

insert  into `ss_jobs`(`job_id`,`job_name`,`job_short`,`job_start`,`job_end`,`job_start2`,`job_end2`,`job_hours`,`job_parent`) values (1,'List of employees',1,'2013-09-14 09:00:00','2013-11-14 14:00:00',NULL,NULL,5,0),(2,'Send Emails',1,'2013-09-14 09:00:00','2013-09-14 13:00:00',NULL,NULL,4,0);

/*Table structure for table `ss_jobshifts` */

DROP TABLE IF EXISTS `ss_jobshifts`;

CREATE TABLE `ss_jobshifts` (
  `jsh_name` varchar(50) DEFAULT NULL,
  `jsh_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_jobshifts` */

insert  into `ss_jobshifts`(`jsh_name`,`jsh_order`) values ('First Shift',1),('Second Shift',2);

/*Table structure for table `ss_jobshiftsneeded` */

DROP TABLE IF EXISTS `ss_jobshiftsneeded`;

CREATE TABLE `ss_jobshiftsneeded` (
  `jsn_job_id` int(11) DEFAULT NULL,
  `jsn_jsh_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_jobshiftsneeded` */

insert  into `ss_jobshiftsneeded`(`jsn_job_id`,`jsn_jsh_name`) values (1,'First Needed Shift'),(2,'Second Needed Shift');

/*Table structure for table `ss_messages` */

DROP TABLE IF EXISTS `ss_messages`;

CREATE TABLE `ss_messages` (
  `msg_usr_id` int(11) DEFAULT NULL,
  `msg_subject` varchar(255) DEFAULT NULL,
  `msg_message` text,
  `msg_sender_usr_id` int(11) DEFAULT NULL,
  `msg_sender_username` varchar(50) DEFAULT NULL,
  `msg_read` tinyint(3) DEFAULT NULL,
  `msg_flag` tinyint(3) DEFAULT NULL,
  `msg_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_messages` */

insert  into `ss_messages`(`msg_usr_id`,`msg_subject`,`msg_message`,`msg_sender_usr_id`,`msg_sender_username`,`msg_read`,`msg_flag`,`msg_date`) values (1,'Your Assignment','Your today\'s assignment is data entry.',2,'kimsue',1,1,'2013-09-14 00:00:00'),(2,'Send emails to all emmployees','Please send emails to all employees before you leave.',1,'kimsue',1,1,'2013-09-15 00:00:00'),(3,'Waiting for you','<p>Hi Jim,</p><p>Why didn&#39;t you join me yesterday. I am waiting for you now.</p><p>Regards</p><p>Admin&nbsp;</p>',1,'admin',1,0,'2013-09-15 11:20:34'),(3,'Hi John how are you','<p>Hi John,</p><p>How are you doing? Please see me in the evening in front mall.</p><p>Regrds</p><p>Kim </p>',2,'ksue',1,0,'2013-09-23 15:53:55');

/*Table structure for table `ss_requests` */

DROP TABLE IF EXISTS `ss_requests`;

CREATE TABLE `ss_requests` (
  `req_id` int(11) NOT NULL AUTO_INCREMENT,
  `req_usr_id` int(11) DEFAULT NULL,
  `req_type` tinyint(3) DEFAULT NULL,
  `req_message` text,
  `req_start` datetime DEFAULT NULL,
  `req_end` datetime DEFAULT NULL,
  `req_flag` char(1) DEFAULT NULL,
  PRIMARY KEY (`req_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `ss_requests` */

insert  into `ss_requests`(`req_id`,`req_usr_id`,`req_type`,`req_message`,`req_start`,`req_end`,`req_flag`) values (1,2,1,'I need vacations in next month.','2013-09-15 00:00:00','2013-09-18 00:00:00','1'),(2,3,2,'I am sick and unable to join office today.','2013-09-16 00:00:00','2013-09-17 00:00:00','1'),(3,1,3,'I want to change my shift.','2013-09-15 00:00:00','2013-09-16 00:00:00','1'),(4,2,4,'I need writing pad urgently.','2013-09-24 00:00:00','2013-09-25 00:00:00','2'),(5,2,1,'TEST REQ','2013-09-02 00:00:00','2013-09-09 00:00:00','2');

/*Table structure for table `ss_scheduleshifts` */

DROP TABLE IF EXISTS `ss_scheduleshifts`;

CREATE TABLE `ss_scheduleshifts` (
  `sch_shf_id` int(11) NOT NULL AUTO_INCREMENT,
  `sch_sunday` date DEFAULT NULL,
  PRIMARY KEY (`sch_shf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `ss_scheduleshifts` */

insert  into `ss_scheduleshifts`(`sch_shf_id`,`sch_sunday`) values (1,'2013-09-15'),(2,'2013-09-20');

/*Table structure for table `ss_schedulestaff` */

DROP TABLE IF EXISTS `ss_schedulestaff`;

CREATE TABLE `ss_schedulestaff` (
  `scs_job_id` int(11) DEFAULT NULL,
  `scs_emp_id` int(11) DEFAULT NULL,
  `scs_year` year(4) DEFAULT NULL,
  `scs_month` smallint(5) DEFAULT NULL,
  `scs_day` smallint(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_schedulestaff` */

insert  into `ss_schedulestaff`(`scs_job_id`,`scs_emp_id`,`scs_year`,`scs_month`,`scs_day`) values (1,1,2013,9,14),(2,2,2013,9,14);

/*Table structure for table `ss_settings` */

DROP TABLE IF EXISTS `ss_settings`;

CREATE TABLE `ss_settings` (
  `set_id` int(11) NOT NULL AUTO_INCREMENT,
  `set_open` time DEFAULT NULL,
  `set_close` time DEFAULT NULL,
  PRIMARY KEY (`set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `ss_settings` */

insert  into `ss_settings`(`set_id`,`set_open`,`set_close`) values (1,'09:00:00','18:00:00'),(2,'09:00:00','16:00:00');

/*Table structure for table `ss_shiftjobs` */

DROP TABLE IF EXISTS `ss_shiftjobs`;

CREATE TABLE `ss_shiftjobs` (
  `shj_shf_id` int(11) DEFAULT NULL,
  `shj_job_id` int(11) DEFAULT NULL,
  `shj_num` int(11) DEFAULT NULL,
  `shj_day` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_shiftjobs` */

insert  into `ss_shiftjobs`(`shj_shf_id`,`shj_job_id`,`shj_num`,`shj_day`) values (1,1,1,1),(2,2,1,1);

/*Table structure for table `ss_shifts` */

DROP TABLE IF EXISTS `ss_shifts`;

CREATE TABLE `ss_shifts` (
  `shf_id` int(11) NOT NULL AUTO_INCREMENT,
  `shf_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`shf_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `ss_shifts` */

insert  into `ss_shifts`(`shf_id`,`shf_name`) values (1,'ss_shift_shf_name 1'),(2,'ss_shift_shf_name 2');

/*Table structure for table `ss_templates` */

DROP TABLE IF EXISTS `ss_templates`;

CREATE TABLE `ss_templates` (
  `tmp_id` int(11) NOT NULL AUTO_INCREMENT,
  `tmp_name` varchar(50) DEFAULT NULL,
  `tmp_shf_id` int(11) DEFAULT NULL,
  `tmp_date` datetime DEFAULT NULL,
  PRIMARY KEY (`tmp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `ss_templates` */

insert  into `ss_templates`(`tmp_id`,`tmp_name`,`tmp_shf_id`,`tmp_date`) values (1,'ss_templates_tmp_name 1',1,'2013-09-14 01:16:53'),(2,'ss_templates_tmp_name 2',2,'2013-09-14 01:17:09');

/*Table structure for table `ss_tickets` */

DROP TABLE IF EXISTS `ss_tickets`;

CREATE TABLE `ss_tickets` (
  `tic_subject` varchar(50) DEFAULT NULL,
  `tic_message` text,
  `tic_usr_id` int(11) DEFAULT NULL,
  `tic_username` varchar(50) DEFAULT NULL,
  `tic_admin` varchar(50) DEFAULT NULL,
  `tic_status` char(10) DEFAULT NULL,
  `tic_rating` smallint(5) DEFAULT NULL,
  `tic_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ss_tickets` */

insert  into `ss_tickets`(`tic_subject`,`tic_message`,`tic_usr_id`,`tic_username`,`tic_admin`,`tic_status`,`tic_rating`,`tic_date`) values ('Help Ticket','This is ticket message.',2,'kimsue','1','1',1,'2013-09-14 02:58:06'),('HelpDesk Ticket','This is bus ticket.',3,'jimsimpson','1','1',1,'2013-09-14 02:59:14');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_eid` int(11) DEFAULT NULL,
  `usr_username` varchar(50) DEFAULT NULL,
  `usr_password` varchar(50) DEFAULT NULL,
  `usr_type` tinyint(3) NOT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`usr_id`,`usr_eid`,`usr_username`,`usr_password`,`usr_type`) values (1,1,'admin','admin',3),(2,2,'ksue','user',1),(3,3,'jsimpson','user',1),(4,4,'madam','user',4),(5,1,'jsilver','user',5),(13,1,'ddddd','eeeee',10);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
