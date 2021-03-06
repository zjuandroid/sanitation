drop database if EXISTS sanitationDB_dev;

create database sanitationDB_dev
DEFAULT CHARACTER SET 'utf8'
COLLATE 'utf8_general_ci';

use sanitationDB_dev;

drop TABLE if EXISTS san_car;

create table san_car (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
plate VARCHAR(10) NOT NULL,
company_id int(4),
service_district tinyint,
driver_id int(8),
car_type int DEFAULT 101 COMMENT '1:普通车',
car_online tinyint(1),
car_state int,
cur_long DOUBLE(10,6),
cur_long_std DOUBLE(10,6),
cur_lat DOUBLE(10,6),
cur_lat_std DOUBLE(10,6),
cur_report_time int,
cur_velocity DOUBLE,
cur_oil_amount DOUBLE ,
cur_move_direction VARCHAR(10),
cur_wind_eng_speed DOUBLE ,
cur_status VARCHAR (40),
update_time int,
cur_oil_consumption DOUBLE ,
week_ave_oil_consumption DOUBLE,
video_url VARCHAR (100)
)engine=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO san_car(plate,car_type, company_id, car_online, car_state, cur_long, cur_lat, cur_velocity, cur_oil_amount, cur_report_time, update_time, video_url, cur_wind_eng_speed)
VALUES
('沪A13871',101, 1, 1, 0, 121.641357,31.209006, 70, 55.5, 1466418002, 1466418002, 'carMonitor.flv', 2200),
('沪A13872',101, 1, 1, 0, 121.639273,31.208512, 70, 55.5, 1466418002, 1466418002, 'carMonitor.flv', 2200),
('沪A13873',102, 2, 0, 0, 121.63992,31.201933, 70, 30.5, 1466418002, 1466418002, 'carMonitor.flv', 2200),
('沪A13874',102, 1, 0, 0, 121.63992,31.202933, 70, 30.5, 1466418002, 1466418002, 'carMonitor.flv', 2200);


drop TABLE if EXISTS san_company;
CREATE TABLE san_company(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
company_name VARCHAR (40),
company_address VARCHAR (100),
contact_id int(8)
)engine=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO san_company(company_name, company_address, contact_id)
VALUES
('小红帽清洁公司', '上海市浦东区乳山路161号', 1),
('洁宜清洁公司', '黄浦区人民路888号', 11),
('新新清洁公司', '新村路1999弄', 21),
('伊德泰清洁公司', '上海市浦东新区川沙镇栏杆村王家宅58号', 31),
('清悠泰清洁公司', '坦仁路168', 41);

drop TABLE if EXISTS san_employee;
CREATE TABLE san_employee(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR (10),
employee_no VARCHAR (10),
company_id int(4),
service_district tinyint,
online tinyint(1),
state int,
cur_long DOUBLE(10,6),
cur_lat DOUBLE(10,6),
cur_report_time int,
cur_velocity DOUBLE,
cur_move_direction VARCHAR(10),
update_time int,
address VARCHAR (100),
phone_no varchar (11)
)engine=InnoDB DEFAULT CHARSET = utf8;
INSERT INTO san_employee(name, address, phone_no, employee_no, company_id, online, state, cur_long, cur_lat,update_time)
VALUES
('安晓芳', '人民路1号', '13184859483', '00001', 1, 1, 0, 121.528587,31.241344, 1466418002),
('樊文玉', '人民路2号', '13184858888', '00002', 1, 1, 0, 121.528227,31.241205, 1466418002),
('何鹏', '人民路2号', '13184858888', '00003', 1, 1, 0, 121.523628,31.239569, 1466418002),
('黄浩', '人民路2号', '13184858888', '00004', 1, 1, 0, 121.525137,31.242811, 1466418002),
('黄淑雯', '人民路2号', '13184858888', '00005', 1, 1, 0, 121.538288,31.242317, 1466418002),
('蒋林朋', '人民路2号', '13184858888', '00006', 1, 1, 0, 121.534695,31.237068, 1466418002),
('李华', '人民路2号', '13184858888', '00007', 1, 1, 0, 121.524706,31.233671, 1466418002),
('龙润', '人民路2号', '13184858888', '00008', 1, 1, 0, 121.536492,31.233054, 1466418002),
('潘燕清', '人民路1号', '13184859483', '00009', 1, 1, 0, 121.536635,31.233023, 1466418002),
('彭胤允', '人民路2号', '13184858888', '00010', 1, 1, 0, 121.518418,31.234876, 1466418002),
('舒诚', '人民路2号', '13184858888', '00001', 1, 1, 0, 121.650357,31.209006, 1466418002),
('谭敬山', '人民路2号', '13184858888', '00002', 1, 1, 0, 121.497128,31.234656, 1466418002),
('唐浩', '人民路2号', '13184858888', '00003', 1, 1, 0, 121.493786,31.234656, 1466418002),
('田会龙', '人民路2号', '13184858888', '00004', 1, 1, 0, 121.499248,31.233452, 1466418002),
('田亚', '人民路2号', '13184858888', '00005', 1, 1, 0, 121.495044,31.237605, 1466418002),
('田亚梅', '人民路2号', '13184858888', '00006', 1, 1, 0, 121.650357,31.209006, 1466418002),
('吴蝶', '人民路1号', '13184859483', '00007', 1, 1, 0, 121.640357,31.209006, 1466418002),
('吴艳', '人民路2号', '13184858888', '00008', 1, 1, 0, 121.650357,31.209006, 1466418002),
('向代立', '人民路2号', '13184858888', '00009', 1, 1, 0, 121.650357,31.209006, 1466418002),
('肖婷', '人民路2号', '13184858888', '00010', 1, 1, 0, 121.650357,31.209006, 1466418002),
('徐晓俊', '人民路2号', '13184858888', '00001', 1, 1, 0, 121.650357,31.209006, 1466418002),
('杨润', '人民路2号', '13184858888', '00002', 1, 1, 0, 121.650357,31.209006, 1466418002),
('杨新宇', '人民路2号', '13184858888', '00003', 1, 1, 0, 121.650357,31.209006, 1466418002),
('易金凤', '人民路2号', '13184858888', '00004', 1, 1, 0, 121.650357,31.209006, 1466418002),
('曾越', '人民路1号', '13184859483', '00005', 1, 1, 0, 121.640357,31.209006, 1466418002),
('张顺', '人民路2号', '13184858888', '00006', 1, 1, 0, 121.650357,31.209006, 1466418002),
('张友华', '人民路2号', '13184858888', '00007', 1, 1, 0, 121.650357,31.209006, 1466418002),
('陈玉林', '人民路2号', '13184858888', '00008', 1, 1, 0, 121.650357,31.209006, 1466418002),
('何敏', '人民路2号', '13184858888', '00009', 1, 1, 0, 121.650357,31.209006, 1466418002),
('洪灿', '人民路2号', '13184858888', '00010', 1, 1, 0, 121.650357,31.209006, 1466418002),
('黄苹', '人民路2号', '13184858888', '00001', 1, 1, 0, 121.650357,31.209006, 1466418002),
('黄艳', '人民路2号', '13184858888', '00002', 1, 1, 0, 121.650357,31.209006, 1466418002),
('李春艳', '人民路1号', '13184859483', '00003', 1, 1, 0, 121.640357,31.209006, 1466418002),
('刘丽', '人民路2号', '13184858888', '00004', 1, 1, 0, 121.650357,31.209006, 1466418002),
('罗春梅', '人民路2号', '13184858888', '00005', 1, 1, 0, 121.650357,31.209006, 1466418002),
('彭凤', '人民路2号', '13184858888', '00006', 1, 1, 0, 121.650357,31.209006, 1466418002),
('邱学燕', '人民路2号', '13184858888', '00007', 1, 1, 0, 121.650357,31.209006, 1466418002),
('覃潮钧', '人民路2号', '13184858888', '00008', 1, 1, 0, 121.650357,31.209006, 1466418002),
('唐成华', '人民路2号', '13184858888', '00009', 1, 1, 0, 121.650357,31.209006, 1466418002),
('田冬梅', '人民路2号', '13184858888', '00010', 1, 1, 0, 121.650357,31.209006, 1466418002),
('田亚玲', '人民路3号', '13184859999', '00001', 5, 1, 0, 121.650357,31.209006, 1466418002);

drop TABLE if EXISTS san_car_his;
CREATE TABLE san_car_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
car_id INT (11) NOT NULL,
report_time int NOT NULL,
his_long DOUBLE(10,6),
his_lat DOUBLE(10,6),
his_velocity DOUBLE,
his_oil_amount DOUBLE
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_car_his(car_id, report_time, his_long, his_lat, his_velocity, his_oil_amount)
VALUES
(1, 1473082656, 121.650071,31.212481, 15, 40),
(1, 1473082661, 121.64928,31.211987, 20, 39.5),
(1, 1473082671, 121.647627,31.211245,8, 39),
(1, 1473082676,121.64619,31.210813,9, 38),
(1, 1473082686, 121.643459,31.209639,40, 37),
(1, 1473092686, 121.608515,31.210149,50, 30),
(1, 1473092691, 121.60776,31.209871,60, 20),
(1, 1473092696, 121.60715,31.209778,70, 19),
(1, 1473092701, 121.606467,31.209809,80,18),
(1, 1473092706, 121.606611,31.209377,90,17),
(1, 1473092711, 121.606611,31.208852,0,16),
(1, 1473092716, 121.606754,31.207647,0,15),
(2, 1473092706, 121.610707,31.195786,30, 60),
(2, 1473092711, 121.610024,31.195724,30, 50),
(2, 1473092716, 121.608946,31.195539,40, 40),
(2, 1473092721, 121.607868,31.195323,50, 30),
(2, 1473092726, 121.607868,31.194489,20, 20),
(2, 1473092731, 121.607796,31.193778,10, 10),
(3, 1473092726, 121.608868,31.194489,10, 30),
(3, 1473092731, 121.608796,31.193778,10, 20);

drop TABLE if EXISTS san_person_his;
CREATE TABLE san_person_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
person_id INT (11) NOT NULL,
report_time int NOT NULL,
his_long DOUBLE(10,6),
his_lat DOUBLE(10,6),
his_velocity DOUBLE
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_person_his(person_id, report_time, his_long, his_lat, his_velocity)
VALUES
(1, 1473082656, 121.650071,31.212481, 6),
(1, 1473082661, 121.64928,31.211987, 5.8),
(1, 1473082671, 121.647627,31.211245, 5),
(1, 1473082676,121.64619,31.210813, 4),
(1, 1473082686, 121.643459,31.209639, 3),
(1, 1473092686, 121.608515,31.210149, 0),
(1, 1473092691, 121.60776,31.209871, 0),
(1, 1473092696, 121.60715,31.209778, 0),
(1, 1473092701, 121.606467,31.209809, 0),
(1, 1473092706, 121.606611,31.209377, 0),
(1, 1473092711, 121.606611,31.208852, 2),
(1, 1473092716, 121.606754,31.207647, 3),
(2, 1473092706, 121.610707,31.195786, 3),
(2, 1473092711, 121.610024,31.195724, 0),
(2, 1473092716, 121.608946,31.195539, 3),
(2, 1473092721, 121.607868,31.195323, 3),
(2, 1473092726, 121.607868,31.194489, 3),
(2, 1473092731, 121.607796,31.193778, 3),
(3, 1473092726, 121.608868,31.194489, 3),
(3, 1473092731, 121.608796,31.193778, 3);

DROP TABLE if EXISTS san_district;
CREATE TABLE san_district(
id int(4) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR (20),
pid int(11)
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_district(id, name, pid)
VALUES
(1, '浦东新区', 0),
(2,'普陀区', 0),
(3,'宝山区', 0),
(4,'静安区', 0),
(5,'徐汇区', 0),
(6, '张江镇', 1),
(7,' 川沙镇', 1),
(8,'幸福街道', 2),
(9,'平安街道', 3),
(10,'陆家嘴街道', 1);

DROP TABLE if EXISTS san_waste_station;
CREATE TABLE san_waste_station(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
station_no VARCHAR (10) NOT NULL,
name VARCHAR (40),
company_id int(4),
district_id tinyint(1),
address VARCHAR (100),
online tinyint(1),
state int,
update_time int,
video_url VARCHAR (50)
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_waste_station(station_no, name, company_id, district_id, address,online, state, update_time, video_url)
VALUES
('00001', '三林垃圾中转站', 1, 1, '浦东新区杨高南路34号', 1, 0, 1466418002, 'carMonitor.flv'),
('00002', '徐汇垃圾中转站', 2, 5, '徐汇区桂林路111号', 0, 0, 1466418002, 'carMonitor.flv'),
('00003', '唐镇垃圾中转站', 3, 1, '浦东新区顾唐路33号', 1, 0, 1466418002, 'carMonitor.flv'),
('00004', '普陀区垃圾中转站', 4, 2, '普陀区滨河路31号', 1, 0, 1466418002, 'carMonitor.flv'),
('00005', '宝山区垃圾中转站', 5, 3, '宝山区宝钢路235号', 1, 0, 1466418002, 'carMonitor.flv'),
('00006', '静安区垃圾中转站', 5, 4, '静安区宝钢路235号', 1, 0, 1466418002, 'carMonitor.flv');

DROP TABLE if EXISTS san_waste_station_his;
CREATE TABLE san_waste_station_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
waste_station_id INT (11) NOT NULL,
report_time int NOT NULL,
water_level DOUBLE,
delta_weight DOUBLE
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_waste_station_his(waste_station_id, report_time, water_level, delta_weight)
VALUES
(1, 1473082656, 1.5, 1000),
(1, 1473087656, 1.5, 500),
(1, 1473092656, 1.5, 200),
(1, 1473097656, 2 , 500),
(1, 1473102656, 1.5, 500),
(1, 1473107656, 1.5, 500),
(2, 1473082656, 1.5, 500),
(2, 1473087656, 1.5, 500),
(2, 1473092656, 1.5, 500),
(2, 1473097656, 1.5, 500),
(2, 1473102656, 1.5, 500),
(2, 1473107656, 1.5, 500),
(3, 1473082656, 1.5, 500),
(3, 1473087656, 1.5, 500),
(3, 1473092656, 1.5, 500),
(3, 1473097656, 1.5, 500);

DROP TABLE if EXISTS san_collect_point;
CREATE TABLE san_collect_point(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
point_no VARCHAR (10) NOT NULL,
name VARCHAR (40),
company_id int(4),
district_id int(4),
address VARCHAR (100),
online tinyint(1),
state int,
update_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_collect_point(point_no, name, address, online, state, update_time, company_id, district_id)
VALUES
('00001', '1号回收点', '盛夏路31号', 1, 0, 1466418002, 1, 6),
('00002', '2号回收点', '天台路32号', 1, 0, 1466418002, 1, 6),
('00003', '3号回收点', '民防路31号', 1, 0, 1466418002, 1, 7),
('00004', '4号回收点', '古桐路32号', 1, 0, 1466418002, 1, 7),
('00005', '5号回收点', '河南路32号', 1, 0, 1466418002, 1, 8),
('00006', '6号回收点', '西安路33号', 0, 1, 1466418002, 2, 9),
('00007', '7号回收点', '银城路33号', 0, 1, 1466418002, 2, 10);

DROP TABLE if EXISTS san_collect_point_his;
CREATE TABLE san_collect_point_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
collect_point_id VARCHAR (10) NOT NULL,
full_num int,
delta_weight DOUBLE ,
report_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_collect_point_his(collect_point_id, full_num, report_time, delta_weight)
VALUES
(1, 2, 1466418002, 100),
(1, 1, 1466419002, 200),
(1, 0, 1466420002, 100),
(1, 1, 1466431002, 100),
(1, 2, 1466432002, 100),
(1, 2, 1466433002, 100),
(1, 2, 1466434002, 100),
(2, 1, 1466418002, 100),
(2, 0, 1466419002, 100),
(2, 1, 1466420002, 100),
(2, 1, 1466421002, 100);

DROP TABLE if EXISTS san_dustbin;
CREATE TABLE san_dustbin(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
dustbin_no VARCHAR (10) NOT NULL,
name VARCHAR (40),
collect_point_id int(11) NOT NULL,
cur_long DOUBLE (10,6),
cur_lat DOUBLE (10, 6),
online tinyint(1),
state int,
update_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_dustbin(dustbin_no, collect_point_id, cur_long, cur_lat, online, state, update_time)
VALUES
('00001', 1, 121.650071,31.212481, 0, 0, 1466418002),
('00002', 1, 121.650071,31.211481, 1, 0, 1466418002),
('00003', 2, 121.650071,31.211481, 1, 0, 1466418002);

DROP TABLE if EXISTS san_alert1;
CREATE TABLE san_alert1(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
device_type int,
source_id int,
content_type int,
content_desc VARCHAR (100),
status tinyint(1) DEFAULT 0,
report_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_alert1(device_type, source_id, content_type, content_desc, status, report_time)
VALUES
(101, 1, 101, '沪A13872发生故障', 0, 1466418002),
(201, 1, 201, '张三(编号00001)跌倒', 0, 1466419002);

DROP TABLE if EXISTS san_alert;
CREATE TABLE san_alert(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
device_type int,
source_id int,
source_name VARCHAR (40),
source_company_id int,
source_company_name VARCHAR (40),
source_district_id int,
source_district_name VARCHAR (20),
content_type int,
content_desc VARCHAR (100),
status tinyint(1) DEFAULT 0,
report_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_alert(device_type, source_id, source_name, source_company_id, source_company_name, source_district_id, source_district_name, content_type, content_desc, status, report_time)
VALUES
(101, 1, 'A13871', 1, '清洁公司1', NULL , NULL , 101, '骤降7', 0, 1474953967),
(201, 3, '张三', 2, '清洁公司2', NULL , NULL , 201, '环卫人员跌倒', 0, 1474964967),
(301, 2, '徐汇垃圾中转站', 2, '清洁公司2', 5 , '徐汇区' , 301, '中转站已满', 0, 1474973967),
(401, 1, '1号回收点', 1, '清洁公司1', 1 , '浦东区' , 401, '回收点已满', 0, 1474983967);

DROP TABLE if EXISTS san_manager;
CREATE TABLE san_manager(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
manager_name VARCHAR (10),
password VARCHAR (32),
phone VARCHAR (11),
service_district tinyint  #100为市级权限
)engine=InnoDB DEFAULT CHARSET = utf8;

insert into san_manager (manager_name, service_district) values ('王局长', 100);


DROP TABLE IF EXISTS san_errcode;

CREATE TABLE san_errcode(
code varchar(10) NOT NULL PRIMARY KEY,
msg varchar(40) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO san_errcode (code, msg)
VALUES
	('CM0000','操作成功'),
	('CM0001','HTTP请求方式错误'),
	('CM0002', '查询时间跨度不正确'),
	('CM0003', '缺少必须参数'),
	('CM0004', '参数错误'),
	('CM0005', '数据库操作失败'),
	('LG0001','用户名或密码错误'),
	('LG0002','验证码检验失败'),
	('LG0003','用户名已注册'),
	('LG0004','用户名还未注册'),
	('LG0005','用户已被禁止登陆');

DROP TABLE if EXISTS san_collect_station;

DROP TABLE if EXISTS san_collect_station;
CREATE TABLE san_collect_station(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
collect_station_no VARCHAR (10) NOT NULL,
name VARCHAR (40),
company_id int(4),
district_id int(4),
address VARCHAR (100),
online tinyint(1),
state int,
update_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_collect_station(collect_station_no, name, address, online, state, update_time, company_id, district_id)
VALUES
('00001', '1号回收站', '盛夏路31号', 1, 0, 1466418002, 1, 6),
('00002', '2号回收站', '盛夏路32号', 1, 0, 1466418002, 1, 6),
('00003', '3号回收站', 'XX路33号', 0, 1, 1466418002, 2, 9);

DROP TABLE if EXISTS san_collect_station_his;
CREATE TABLE san_collect_station_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
collect_station_id VARCHAR (10) NOT NULL,
full_num int,
delta_weight DOUBLE ,
report_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_collect_station_his(collect_station_id, full_num, report_time, delta_weight)
VALUES
(1, 2, 1466418002, 100),
(1, 1, 1466419002, 200),
(1, 0, 1466420002, 100),
(1, 1, 1466431002, 100),
(1, 2, 1466432002, 100),
(1, 2, 1466433002, 100),
(1, 2, 1466434002, 100),
(2, 1, 1466418002, 100),
(2, 0, 1466419002, 100),
(2, 1, 1466420002, 100),
(2, 1, 1466421002, 100);

DROP TABLE if EXISTS san_evaluation_car;

CREATE TABLE san_evaluation_car(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
date_time VARCHAR (10),
car_id int(8),
preset_road VARCHAR(50) NOT NULL,
preset_start_time VARCHAR(5),
preset_end_time VARCHAR (5),
preset_velocity DOUBLE ,
preset_work_times DOUBLE,
actual_start_time VARCHAR(5),
actual_end_time VARCHAR (5),
actual_max_velocity DOUBLE ,
actual_velocity DOUBLE ,
actual_work_times DOUBLE,
reaching_standard_rate DOUBLE
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_evaluation_car(car_id, date_time, preset_road, preset_start_time, preset_end_time, preset_velocity, preset_work_times, actual_start_time, actual_end_time, actual_velocity, actual_max_velocity, actual_work_times, reaching_standard_rate)
VALUES
(1, '2016-10-01', '祖冲之路金科路','04:00', '08:00', 10, 2, '03:50', '07:50', 8, 12, 1.98, 99),
(1, '2016-10-02', '祖冲之路金科路','04:00', '08:00', 10, 2, '03:50', '07:50', 8, 12, 1.98, 99),
(2, '2016-10-07', '张江路紫薇路','14:00', '18:00', 10, 2, '13:50', '17:50', 8, 12, 2, 100);

DROP TABLE if EXISTS san_evaluation_person;

CREATE TABLE san_evaluation_person(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
date_time VARCHAR (10),
person_id INT (11) NOT NULL,
preset_road VARCHAR(50) NOT NULL,
preset_start_time VARCHAR(5),
preset_end_time VARCHAR (5),
preset_length DOUBLE ,
actual_start_time VARCHAR(5),
actual_end_time VARCHAR (5),
actual_length DOUBLE ,
reaching_standard_rate DOUBLE
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_evaluation_person(person_id, date_time, preset_road, preset_start_time, preset_end_time, preset_length, actual_start_time, actual_end_time, actual_length, reaching_standard_rate)
VALUES
(1, '2016-10-01', '祖冲之路金科路','04:00', '08:00', 5, '03:50', '07:50', 5.5, 99),
(1, '2016-10-02', '祖冲之路金科路','04:00', '08:00', 5, '03:50', '07:50', 5.5, 99),
(2, '2016-10-07', '张江路紫薇路','14:00', '18:00', 10, '13:50', '17:50', 8, 100);

DROP TABLE IF EXISTS san_member;

CREATE TABLE san_member (
   id int(11) NOT NULL AUTO_INCREMENT,
   username varchar(20) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  password varchar(32) DEFAULT NULL,
  avatar varchar(255) DEFAULT NULL COMMENT '头像',
  create_at varchar(20) DEFAULT '0',
  update_at varchar(20) DEFAULT '0',
  login_ip varchar(20) DEFAULT NULL,
  address varchar(40) DEFAULT NULL,
  has_new_message tinyint(1) DEFAULT '0' COMMENT '0:没有新消息 1:有新消息',
  gender varchar(2) DEFAULT NULL,
  status tinyint(1) DEFAULT '1' COMMENT '0:禁止登陆 1:正常',
  type tinyint(1) DEFAULT '1' COMMENT '1:前台用户 2:管理员 ',
  PRIMARY KEY (id),
  KEY username (username) USING BTREE,
  KEY password (password) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO san_member (id, username, email, password, avatar, create_at, update_at, login_ip, status, type, gender, address)
VALUES
	(1,'18121380371','515343908@qq.com','96e79218965eb72c92a549dd5a330112','57610b640b1d5.jpg','1467211497','1467211497','0.0.0.0',1,2,'男','杭州市西湖区文三路33号');
