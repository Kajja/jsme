DROP TABLE IF EXISTS "user";
CREATE TABLE "user" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 
    "name" VARCHAR NOT NULL , 
    "email" VARCHAR NOT NULL , 
    "passw" VARCHAR NOT NULL , 
    "created" DATETIME NOT NULL , 
    "changed" DATETIME
);

DROP TABLE IF EXISTS "question";
CREATE TABLE "question" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 
    "user_id" INTEGER NOT NULL , 
    "title" VARCHAR NOT NULL , 
    "text" TEXT NOT NULL , 
    "created" DATETIME NOT NULL , 
    "edited" DATETIME
);

DROP TABLE IF EXISTS "tag";
CREATE TABLE "tag" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 
    "name" VARCHAR NOT NULL UNIQUE, 
    "description" VARCHAR, 
    "created" DATETIME NOT NULL 
);

DROP TABLE IF EXISTS "questiontag";
CREATE TABLE "questiontag" (
    "question_id" INTEGER NOT NULL , 
    "tag_id" INTEGER NOT NULL 
);

DROP TABLE IF EXISTS "answer";
CREATE TABLE "answer" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 
    "user_id" INTEGER NOT NULL , 
    "question_id" INTEGER NOT NULL , 
    "text" TEXT NOT NULL , 
    "created" DATETIME NOT NULL , 
    "edited" DATETIME, 
    "accepted" BOOL
);

DROP TABLE IF EXISTS "comment";
CREATE TABLE "comment" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 
    "user_id" INTEGER NOT NULL , 
    "text" TEXT NOT NULL , 
    "type" VARCHAR NOT NULL , 
    "related_id" INTEGER NOT NULL , 
    "created" DATETIME NOT NULL , 
    "edited" DATETIME
);