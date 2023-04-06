<div class="content">
            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                        <?php foreach ($projects as $project): ?>
                            <li class="main-navigation__list-item<?php if (isset($_GET['project_id']) && $_GET['project_id'] === $project['id']) echo('--active')?>">
                                <a class="main-navigation__list-item-link" href="http://task-scheduler/?project_id=<?=$project['id']?>"><?= $project['title']?></a>
                                <span class="main-navigation__list-item-count"><?= task_counter($tasks, $project['title'])?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button"
                   href="pages/form-project.html" target="project_add">Добавить проект</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="post" autocomplete="off">
                    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                        <a href="/" class="tasks-switch__item">Повестка дня</a>
                        <a href="/" class="tasks-switch__item">Завтра</a>
                        <a href="/" class="tasks-switch__item">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox"<?php if ($show_complete_tasks == 1):?> checked<?php endif; ?>>
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>
                <table class="tasks">
                    <?php foreach ($tasks as $task): 
                        $time_to_task = strtotime($task['date']) -  time();
                        $hours_left = floor($time_to_task / 3600);
                        ?>
                        <?php if ($show_complete_tasks == 0 && $task['status'] == true): ?> <?continue;?><?php endif ?>
                    <tr class="tasks__item task<?php if ($task['status'] == true): ?> <?=' task--completed';?><?php endif ?><?php if ($hours_left >= 0 && $hours_left <= 24): ?> <?=' task--important';?><?php endif ?>">
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1"<?php if ($task['status'] == true):?> checked<?php endif; ?>>
                                <span class="checkbox__text"><?=$task['task_name'];?></span>
                            </label>
                        </td>

                        <td class="task__file">
                            <a class="download-link<?php if($task['file_path'] === NULL):?><?=' hidden'?><?php endif?>" href="<?=$task['file_path'];?>">Home.psd</a>
                        </td>

                        <td class="task__date"><?=date('d.m.Y',strtotime($task['date']));?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </main>
        </div>