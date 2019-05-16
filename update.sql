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

/* ��ʽ�� */
create table `raffle_formula`(
  `id` int(10) not null auto_increment,
  `title` varchar(255) null,
  `formula` varchar(255) not null,
  `accuracy` float(10,2) null default 0,
  primary key(`id`)
)engine=innodb default charset=utf8;

/* �ֶα� */
create table `raffle_fields`(
  `id` int(10) not null auto_increment,
  `title` varchar(100) not null,
  `sign` varchar(100) not null,
  primary key(`id`)
);

/****************************************************************
ǰ̨��
****************************************************************/
/* ˫ɫ�򿪽������ */
create table `raffle_double`(
  `id` int(10) not null auto_increment,
  `issue` varchar(10) not null comment '�ں�',
  `year` int(4) not null comment '���',
  `issue_no` int(4) not null comment '���',
  `red_ball` varchar(255) not null comment '��ɫ��',
  `blue_ball` varchar(10) not null comment '��ɫ��',
  `whole` varchar(255) not null comment '�����ĺ���',
  primary key(`id`),
  unique key `issue` (`issue`)
)engine=innodb default charset=utf8