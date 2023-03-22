/*
  Создаём пользователей.
*/
INSERT INTO users (login, email, password) VALUES ('Keks', 'keks@cat.ru', '123456789');
INSERT INTO users (login, email, password) VALUES ('Pluto', 'bark@dog.ru', 'qwerty');
/*
  Создаём проекты, связанные с пользователями.
*/
INSERT INTO projects (title, user_id) VALUES ('Входящие', 1);
INSERT INTO projects (title, user_id) VALUES ('Учеба', 1);
INSERT INTO projects (title, user_id) VALUES ('Работа', 1);
INSERT INTO projects (title, user_id) VALUES ('Домашние дела', 2);
INSERT INTO projects (title, user_id) VALUES ('Авто', 2);
/*
  Создаём задачи, связанные с проектами и пользователями.
*/
INSERT INTO tasks (title, status, date, user_id, project_id) VALUES ('Собеседование в IT компании', 0, '2023-12-01', 1, 3);
INSERT INTO tasks (title, status, date, user_id, project_id) VALUES ('Выполнить тестовое задание', 0, '2023-11-01', 1, 3);
INSERT INTO tasks (title, status, date, user_id, project_id) VALUES ('Сделать задание первого раздела', 1, '2023-03-13', 1, 2);
INSERT INTO tasks (title, status, date, user_id, project_id) VALUES ('Встреча с другом', 0, '2023-12-22', 1, 1);
INSERT INTO tasks (title, status, user_id, project_id) VALUES ('Купить корм для кота', 0, 2, 4);
INSERT INTO tasks (title, status, user_id, project_id) VALUES ('Заказать пиццу', 0, 2, 4);
/*Получаем список из всех проектов для одного пользователя*/
SELECT projects.title FROM projects JOIN users ON user_id = users.id WHERE login = 'Keks';
/*Получаем список из всех задач для одного проекта*/
SELECT tasks.title FROM tasks JOIN projects ON project_id = projects.id WHERE projects.title = 'Работа';
/*Помечаем задачу как выполненную*/
UPDATE tasks SET status = 1 WHERE tasks.title = 'Купить корм для кота';
/*Обновляем название задачи по её идентификатору*/
SELECT id from tasks WHERE title = 'Встреча с другом';
UPDATE tasks SET title = 'Встреча с ЛУЧШИМ другом' WHERE tasks.id = 4;