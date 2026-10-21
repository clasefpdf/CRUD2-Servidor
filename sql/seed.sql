INSERT INTO users (username, password, name, email, role) VALUES
('admin', '', 'Admin', 'admin@sprintboard.local', 'teacher'),
('alice', '', 'Alice Johnson', 'alice@sprintboard.local', 'student'),
('bob', '', 'Bob Smith', 'bob@sprintboard.local', 'student'),
('carol', '', 'Carol White', 'carol@sprintboard.local', 'student'),
('david', '', 'David Brown', 'david@sprintboard.local', 'student'),
('eva', '', 'Eva Green', 'eva@sprintboard.local', 'student');
INSERT INTO teams (name) VALUES ('Equip Alpha'), ('Equip Beta'), ('Equip Gamma');
INSERT INTO sprints (name, goal, start_date, end_date, status) VALUES
('Sprint 1', 'Autenticació i dades', '2026-02-01', '2026-02-14', 'completed'),
('Sprint 2', 'Tauler Kanban', '2026-02-15', '2026-02-28', 'completed'),
('Sprint 3', 'Comentaris i evidències', '2026-03-01', '2026-03-14', 'active'),
('Sprint 4', 'Estadístiques', '2026-03-15', '2026-03-28', 'pending');
INSERT INTO sprint_team_members (sprint_id, team_id, user_id, role) VALUES
(1, 1, 2, 'scrum_master'), (1, 1, 3, 'developer'), (1, 2, 4, 'scrum_master'), (1, 2, 5, 'developer'), (1, 3, 6, 'developer'),
(2, 1, 2, 'developer'), (2, 1, 3, 'scrum_master'), (2, 2, 4, 'developer'), (2, 2, 5, 'scrum_master'), (2, 3, 6, 'developer'),
(3, 1, 2, 'scrum_master'), (3, 1, 3, 'developer'), (3, 2, 4, 'scrum_master'), (3, 2, 5, 'developer'), (3, 3, 6, 'developer');
INSERT INTO tasks (title, description, status, team_id, user_id, sprint_id) VALUES
('Dissenyar base de dades', 'Crear les taules i les relacions', 'done', 1, 2, 1),
('Implementar login', 'Accés amb sessions i contrasenyes', 'done', 1, 3, 1),
('Desenvolupar tauler', 'Tres columnes Kanban', 'done', 1, 2, 2),
('Afegir comentaris', 'Converses sobre les tasques', 'in_progress', 1, 2, 3),
('Preparar evidències', 'Documentar els resultats', 'todo', 2, 4, 3),
('Provar estadístiques', 'Mètriques de l’esprint', 'todo', 3, 6, 3);
INSERT INTO comments (task_id, user_id, content) VALUES
(1, 2, 'El model de dades està preparat.'),
(4, 3, 'Cal revisar els comentaris abans de tancar la tasca.');
