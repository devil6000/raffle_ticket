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