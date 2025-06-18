
   INFO  Running migrations.  

  2025_06_13_200024_create_sessions_table ...........................................................................  
  ⇂ create table "sessions" ("id" varchar(255) not null, "user_id" bigint null, "ip_address" varchar(45) null, "user_agent" text null, "payload" text not null, "last_activity" integer not null)  
  ⇂ alter table "sessions" add primary key ("id")  
  ⇂ create index "sessions_user_id_index" on "sessions" ("user_id")  
  ⇂ create index "sessions_last_activity_index" on "sessions" ("last_activity")  
  2025_06_13_200623_create_cache_table ..............................................................................  
  ⇂ create table "cache" ("key" varchar(255) not null, "value" text not null, "expiration" integer not null)  
  ⇂ alter table "cache" add primary key ("key")  
  ⇂ create table "cache_locks" ("key" varchar(255) not null, "owner" varchar(255) not null, "expiration" integer not null)  
  ⇂ alter table "cache_locks" add primary key ("key")  

