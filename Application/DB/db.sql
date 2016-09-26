drop database if EXISTS sanitationDB;

create database sanitationDB
DEFAULT CHARACTER SET 'utf8'
COLLATE 'utf8_general_ci';

use sanitationDB;

drop TABLE if EXISTS san_car;

create table san_car (
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
plate VARCHAR(10) NOT NULL,
company_id int(4),
service_district tinyint,
driver_id int(8),
car_type tinyint DEFAULT 1 COMMENT '1:普通车',
car_online tinyint(1),
car_state int,
cur_long DOUBLE(10,6),
cur_lat DOUBLE(10,6),
cur_report_time int,
cur_velocity DOUBLE,
cur_oil_amount DOUBLE ,
cur_move_direction VARCHAR(10),
cur_status VARCHAR (40),
update_time int,
cur_oil_consumption DOUBLE ,
week_ave_oil_consumption DOUBLE,
video_url VARCHAR (100)
)engine=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO san_car(plate, company_id, car_online, car_state, cur_long, cur_lat, cur_velocity, cur_oil_amount, cur_report_time, update_time, video_url)
VALUES
('沪A13871', 1, 1, 0, 121.641357,31.209006, 70, 55.5, 1466418002, 1466418002, 'carMonitor.flv'),
('沪A13872', 1, 1, 0, 121.639273,31.208512, 70, 55.5, 1466418002, 1466418002, 'carMonitor.flv'),
('沪A13873', 2, 0, 0, 121.63992,31.201933, 70, 30.5, 1466418002, 1466418002, 'carMonitor.flv'),
('沪A13874', 1, 0, 0, 121.63992,31.202933, 70, 30.5, 1466418002, 1466418002, 'carMonitor.flv');


drop TABLE if EXISTS san_company;
CREATE TABLE san_company(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
company_name VARCHAR (40),
company_address VARCHAR (100),
contact_id int(8)
)engine=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO san_company(company_name, company_address, contact_id)
VALUES
('清洁公司1', '盛夏路111弄', 1),
('清洁公司2', '益江路112弄', 2);

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
('张三', '人民路1号', '13184859483', '00001', 1, 1, 0, 121.640357,31.209006, 1466418002),
('李四', '人民路2号', '13184858888', '00002', 1, 1, 0, 121.650357,31.209006, 1466418002),
('王二', '人民路3号', '13184859999', '00001', 2, 1, 0, 121.650357,31.209006, 1466418002);

drop TABLE if EXISTS san_car_his_pos;
CREATE TABLE san_car_his_pos(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
car_id INT (11) NOT NULL,
report_time int NOT NULL,
his_long DOUBLE(10,6),
his_lat DOUBLE(10,6)
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_car_his_pos(car_id, report_time, his_long, his_lat)
VALUES
(1, 1473082656, 121.650071,31.212481),
(1, 1473082661, 121.64928,31.211987),
(1, 1473082671, 121.647627,31.211245),
(1, 1473082676,121.64619,31.210813),
(1, 1473082686, 121.643459,31.209639),
(1, 1473092686, 121.608515,31.210149),
(1, 1473092691, 121.60776,31.209871),
(1, 1473092696, 121.60715,31.209778),
(1, 1473092701, 121.606467,31.209809),
(1, 1473092706, 121.606611,31.209377),
(1, 1473092711, 121.606611,31.208852),
(1, 1473092716, 121.606754,31.207647),
(2, 1473092706, 121.610707,31.195786),
(2, 1473092711, 121.610024,31.195724),
(2, 1473092716, 121.608946,31.195539),
(2, 1473092721, 121.607868,31.195323),
(2, 1473092726, 121.607868,31.194489),
(2, 1473092731, 121.607796,31.193778),
(3, 1473092726, 121.608868,31.194489),
(3, 1473092731, 121.608796,31.193778);

drop TABLE if EXISTS san_person_his_pos;
CREATE TABLE san_person_his_pos(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
person_id INT (11) NOT NULL,
report_time int NOT NULL,
his_long DOUBLE(10,6),
his_lat DOUBLE(10,6)
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_person_his_pos(person_id, report_time, his_long, his_lat)
VALUES
(1, 1473082656, 121.650071,31.212481),
(1, 1473082661, 121.64928,31.211987),
(1, 1473082671, 121.647627,31.211245),
(1, 1473082676,121.64619,31.210813),
(1, 1473082686, 121.643459,31.209639),
(1, 1473092686, 121.608515,31.210149),
(1, 1473092691, 121.60776,31.209871),
(1, 1473092696, 121.60715,31.209778),
(1, 1473092701, 121.606467,31.209809),
(1, 1473092706, 121.606611,31.209377),
(1, 1473092711, 121.606611,31.208852),
(1, 1473092716, 121.606754,31.207647),
(2, 1473092706, 121.610707,31.195786),
(2, 1473092711, 121.610024,31.195724),
(2, 1473092716, 121.608946,31.195539),
(2, 1473092721, 121.607868,31.195323),
(2, 1473092726, 121.607868,31.194489),
(2, 1473092731, 121.607796,31.193778),
(3, 1473092726, 121.608868,31.194489),
(3, 1473092731, 121.608796,31.193778);

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
('00003', '唐镇垃圾中转站', 3, 1, '浦东新区顾唐路33号', 1, 0, 1466418002, 'carMonitor.flv');

DROP TABLE if EXISTS san_waste_station_his;
CREATE TABLE san_waste_station_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
waste_station_id INT (11) NOT NULL,
report_time int NOT NULL,
water_level DOUBLE
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_waste_station_his(waste_station_id, report_time, water_level)
VALUES
(1, 1473082656, 1.5),
(1, 1473087656, 1.5),
(1, 1473092656, 1.5),
(1, 1473097656, 2),
(1, 1473102656, 1.5),
(1, 1473107656, 1.5),
(2, 1473082656, 1.5),
(2, 1473087656, 1.5),
(2, 1473092656, 1.5),
(2, 1473097656, 1.5),
(2, 1473102656, 1.5),
(2, 1473107656, 1.5),
(3, 1473082656, 1.5),
(3, 1473087656, 1.5),
(3, 1473092656, 1.5),
(3, 1473097656, 1.5);

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
('00002', '2号回收点', '盛夏路32号', 1, 0, 1466418002, 1, 6),
('00003', '3号回收点', 'XX路33号', 0, 1, 1466418002, 2, 9);

DROP TABLE if EXISTS san_collect_point_his;
CREATE TABLE san_collect_point_his(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
collect_point_id VARCHAR (10) NOT NULL,
full_num int,
report_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_collect_point_his(collect_point_id, full_num, report_time)
VALUES
(1, 2, 1466418002),
(1, 1, 1466419002),
(1, 0, 1466420002),
(1, 1, 1466431002),
(1, 2, 1466432002),
(1, 2, 1466433002),
(1, 2, 1466434002),
(2, 1, 1466418002),
(2, 0, 1466419002),
(2, 1, 1466420002),
(2, 1, 1466421002);

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

DROP TABLE if EXISTS san_alert;
CREATE TABLE san_alert(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
device_type int,
source_id int,
content_type int,
content_desc VARCHAR (100),
status tinyint(1) DEFAULT 0,
report_time int
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_alert(device_type, source_id, content_type, content_desc, status, report_time)
VALUES
(101, 1, 101, '沪A13872发生故障', 0, 1466418002),
(201, 1, 201, '张三(编号00001)跌倒', 0, 1466419002);

DROP TABLE if EXISTS san_manager;
CREATE TABLE san_manager(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
manager_name VARCHAR (10),
password VARCHAR (32),
phone VARCHAR (11),
service_district tinyint  #100为市级权限
)engine=InnoDB DEFAULT CHARSET = utf8;

insert into san_manager (manager_name, service_district) values ('王局长', 100);


DROP TABLE IF EXISTS errcode;

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
	('CM0005', '数据库操作失败');