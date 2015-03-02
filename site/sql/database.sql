use teamb;
drop table if exists administrators;
drop table if exists users;
drop table if exists shipments;
drop table if exists orderProducts;
drop table if exists orders;
drop table if exists payments;
drop table if exists customers;
drop table if exists products;
drop table if exists categories;

-- I've changed the name from category because table names are by convention plural
create table categories (
            catID integer not null auto_increment ,
            catName varchar(30),
-- I've added description to match the category class
			catDescription varchar(200),
            primary key (catID)
);
insert into categories (catName, catDescription) 
    values ('Category one','Description of category one');

create table products (
        productID integer not null auto_increment,
        productName varchar(30),
        productDescription  varchar(300),
        productPrice decimal (6,2),	
		productPic varchar (200),
        catID integer not null,
        primary key (productID),
        foreign key (catID) references categories (catID)
);

insert into products (productName, productDescription, productPrice, productPic, catID) 
    values ('Product one','Description of product one',123.45,null,1);

create table users (
		userID integer not null auto_increment, 
		name varchar(64), 
		email varchar(64), 
		pwCheck varchar(75), 
		dateCreated datetime, 
		lastLogin datetime, 
		primary key (userID), 
		unique key(email)
);
insert into users (name, email, pwCheck, dateCreated, lastLogin) 
			values ('Mike Lopez','mike.lopez@cpit.ac.nz','5000$1AGp0JbYCJWcH9ng$oKpRQEO9vkPVkPQTiOuF9DhV0aUaPjopKPcXo6Ic0d/','2014-08-01',null);
insert into users (name, email, pwCheck, dateCreated, lastLogin) 
			values ('Mike Lance','lancem@cpit.ac.nz','5000$w0dIUsUZWFB5eJ5f$B/FN5Z2Rwx5MKVuB0LkM29KM6F8LqsrlXJTg61sEtE8','2014-08-02',null);

			
			create table administrators (userID int not null auto_increment, primary key (userID));
insert into administrators (userID) values (1);

-- I've changed this to plural
create table customers (
       customerId integer not null auto_increment,
       customerFirstName varchar (20),
       customerLastName varchar (30),
       customerAddress varchar (100),
       customerCity varchar (20),
       customerPostCode varchar (4),
       customerEmail varchar (50),
       isBusinessAccount boolean,
       primary key (customerId)  
		-- suggect change 
		-- customerId integer not null auto_increment,
		-- customerName varchar (20),
		-- customerAddress varchar (100),
		-- customerEmail varchar (50),
		-- primary key (customerId)  

);

	insert into customers (customerFirstName,customerLastName,
                      customerAddress,customerCity,customerPostCode,customerEmail,isBusinessAccount) 
	values ('Jane','Doe','123 Main Street','Toytown','1234','JaneDoe@toys.com','yes');

-- orders table
-- I've deleted order quantity and price
create table orders (
      orderId integer not null auto_increment,
      -- orderPrice decimal (6,2),
	  orderDate date,
	  sendDate date, 
	  customerId integer not null,
	  orderAddress varchar (100),
	  TicketNo varchar (20),
      primary key (orderId),
      foreign key (customerId) references customers (customerId)
);
	insert into orders (orderDate,sendDate,customerId,TicketNo) 
	values ('2014-12-12','2014-12-13','1','1');

-- I've changed this to plural
create table orderProducts (
	  orderProductID integer not null auto_increment,
	  quantity integer,
      orderId int,
      productId int,
	 -- orderPrice decimal (6,2),
	 -- sendDate date,  
	  primary key (orderProductID),
	  foreign key (productId) references products(productId),
      foreign key (orderId) references orders (orderId)
);


-- extra code for database structure---------------------


create table payments (
	paymentId	integer not null auto_increment,
    custId 		integer, 
	amount		decimal(6, 2),
	paymentDate	date,
	lastUpdate	date,
	paymentType int,
	primary key (paymentId),
	foreign key (custId) references customers (customerId)
);


-- create table shipments (	--to beworkde on )
