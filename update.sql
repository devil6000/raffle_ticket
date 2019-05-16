/* 权限表 */
create table `raffle_jurisdiction`(
  `id` int(10) not null auto_increment,
  `title` varchar(100) not null comment '权限名称',
  `create_time` int(6) null default 0 comment '创建时间',
  `perm` mediumtext comment '权限',
  primary key(`id`)
)engine=innodb default charset=utf8;

/* 操作员表 */
create table `raffle_user`(
  `id` int(10) not null auto_increment,
  `jurisdiction` int(10) not null comment '权限表ID',
  `username` varchar(100) not null comment '用户名',
  `password` varchar(100) not null comment '密码',
  `signkey` varchar(10) not null comment '密码key',
  `create_time` int(6) null default 0 comment '创建时间',
  primary key(`id`),
  foreign key(`jurisdiction`) references raffle_jurisdiction(`id`)
)engine=innodb default charset=utf8;

/* 公式表 */
create table `raffle_formula`(
  `id` int(10) not null auto_increment,
  `title` varchar(255) null,
  `formula` varchar(255) not null,
  `accuracy` float(10,2) null default 0,
  primary key(`id`)
)engine=innodb default charset=utf8;

/* 字段表 */
create table `raffle_fields`(
  `id` int(10) not null auto_increment,
  `title` varchar(100) not null,
  `sign` varchar(100) not null,
  primary key(`id`)
);

/****************************************************************
前台表
****************************************************************/
/* 双色球开奖号码表 */
create table `raffle_double`(
  `id` int(10) not null auto_increment,
  `issue` varchar(10) not null comment '期号',
  `year` int(4) not null comment '年份',
  `issue_no` int(4) not null comment '编号',
  `red_ball` varchar(255) not null comment '红色球',
  `blue_ball` varchar(10) not null comment '蓝色球',
  `whole` varchar(255) not null comment '完整的号码',
  primary key(`id`),
  unique key `issue` (`issue`)
)engine=innodb default charset=utf8