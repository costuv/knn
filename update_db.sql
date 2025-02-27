USE kaustuv_blog;

ALTER TABLE users
ADD COLUMN role VARCHAR(10) DEFAULT 'user' AFTER created_at;