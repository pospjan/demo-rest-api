INSERT INTO user (name, email, password_hash, role) VALUES
    ('Admin', 'admin@example.com', 'hash1', 'Admin'),
    ('Reader', 'reader@example.com', 'hash2', 'Reader'),
    ('Author', 'author@example.com', 'hash3', 'Author')
;

INSERT INTO article (title, content, author_id, created_at, updated_at) VALUES
    ('Titulek 1', 'Obsah 1', 1, '2024-04-01T10:00:00+00:00', '2024-04-01T10:00:00+00:00'),
    ('Titulek 2', 'Obsah 2', 3, '2024-04-02T10:00:00+00:00', '2024-04-02T12:00:00+00:00')
;

INSERT INTO access_token (token, user_id, created_at, expires_at) VALUES
  ('abc123', 1, '2024-04-01T08:00:00+00:00', '2054-05-01T08:00:00+00:00'),
  ('token_reader', 2, '2024-04-02T09:00:00+00:00', '2054-05-02T09:00:00+00:00'),
  ('ghi789', 3, '2024-04-03T10:00:00+00:00', '2054-05-03T10:00:00+00:00')
;