CREATE TABLE USERS (
    ID VARCHAR(255) PRIMARY KEY,
    FIRST_NAME VARCHAR(50),
    LAST_NAME VARCHAR(50),
    PHONE_NUMBER VARCHAR(20),
    GENDER VARCHAR(255),
    CONTACT_NUMBER VARCHAR(255),
    ADDRESS VARCHAR(255)
    EMAIL VARCHAR(100),
    PASSWORD VARCHAR(100),
    ROLE VARCHAR(50)
);

CREATE TABLE STUDENT (
    ID VARCHAR(255) PRIMARY KEY,
    USER_ID VARCHAR(255),
    STUDENT_ID VARCHAR(255),
    YEAR_LEVEL VARCHAR(255),
    DEPARTMENT VARCHAR(255),
    FOREIGN KEY (USER_ID) REFERENCES USERS(ID) ON DELETE CASCADE
);

CREATE TABLE OPERATOR (
    ID VARCHAR(255) PRIMARY KEY,
    USER_ID VARCHAR(255),
    ORGANIZATION VARCHAR(255),
    POSITION VARCHAR(255),
    FOREIGN KEY (USER_ID) REFERENCES USERS(ID) ON DELETE CASCADE
);


CREATE TABLE EVENT (
    ID VARCHAR(255) PRIMARY KEY,
    NAME VARCHAR(255) NOT NULL,
    DESCRIPTION TEXT,
    START_DATE DATE NOT NULL,
    START_TIME TIME NOT NULL,
    END_DATE DATE NOT NULL,
    END_TIME TIME NOT NULL,
    LOCATION VARCHAR(255),
    CREATED_BY VARCHAR(255),
    FOREIGN KEY (CREATED_BY) REFERENCES OPERATOR(ID) ON DELETE SET NULL
);