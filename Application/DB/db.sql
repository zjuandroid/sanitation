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
(1, 1473082686, 121.643459,31.209639);

DROP TABLE if EXISTS san_district;
CREATE TABLE san_district(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR (20)
)engine=InnoDB DEFAULT CHARSET = utf8;

INSERT INTO san_district(name)
VALUES
('浦东新区'),
('普陀区'),
('宝山区'),
('静安区'),
('徐汇区');

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

DROP TABLE if EXISTS san_manager;
CREATE TABLE san_manager(
id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
manager_name VARCHAR (10),
password VARCHAR (32),
phone VARCHAR (11),
service_district tinyint  #100为市级权限
)engine=InnoDB DEFAULT CHARSET = utf8;

insert into SAN_MANAGER (manager_name, service_district) values ('王局长', 100);


DROP TABLE IF EXISTS errcode;

CREATE TABLE san_errcode(
code varchar(10) NOT NULL PRIMARY KEY,
msg varchar(40) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO san_errcode (code, msg)
VALUES
	('CM0000','操作成功'),
	('CM0001','HTTP请求方式错误');