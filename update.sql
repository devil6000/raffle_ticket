/* Ȩ�ޱ� */
create table `raffle_jurisdiction`(
  `id` int(10) not null auto_increment,
  `title` varchar(100) not null comment 'Ȩ������',
  `create_time` int(6) null default 0 comment '����ʱ��',
  `perm` mediumtext comment 'Ȩ��',
  primary key(`id`)
)engine=innodb default charset=utf8;

/* ����Ա�� */
create table `raffle_user`(
  `id` int(10) not null auto_increment,
  `jurisdiction` int(10) not null comment 'Ȩ�ޱ�ID',
  `username` varchar(100) not null comment '�û���',
  `password` varchar(100) not null comment '����',
  `signkey` varchar(10) not null comment '����key',
  `create_time` int(6) null default 0 comment '����ʱ��',
  primary key(`id`),
  foreign key(`jurisdiction`) references raffle_jurisdiction(`id`)
)engine=innodb default charset=utf8;