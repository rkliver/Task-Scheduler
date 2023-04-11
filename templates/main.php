<div class="content">
            <section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                        <?php foreach ($projects as $project): ?>
                            <li class="main-navigation__list-item<?php if (isset($_GET['project_id']) && $_GET['project_id'] === $project['id']) echo('--active')?>">
                                <a class="main-navigation__list-item-link" href="?project_id=<?=$project['id']?>"><?= $project['title']?></a>
                                <span class="main-navigation__list-item-count"><?= task_counter($tasks, $project['title'])?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button"
                   href="add-project.php" target="project_add">Добавить проект</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="get" autocomplete="off">
                    <input class="search-form__input" type="text" name="task_search" placeholder="Поиск по задачам" 
                    <?php if (isset($_GET['task_search'])):?>
                    value="<?php print($_GET['task_search']); ?>">
                    <?php endif;?>

                    <input class="search-form__submit" type="submit" name="">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="/?sort=all" class="tasks-switch__item <?php if (!isset($_GET['sort']) || $_GET['sort'] == 'all'):?>tasks-switch__item--active<?php endif;?>">Все задачи</a>
                        <a href="/?sort=today" class="tasks-switch__item <?php if (isset($_GET['sort']) && $_GET['sort'] == 'today'):?>tasks-switch__item--active<?php endif;?>">Повестка дня</a>
                        <a href="/?sort=tomorrow" class="tasks-switch__item <?php if (isset($_GET['sort']) && $_GET['sort'] == 'tomorrow'):?>tasks-switch__item--active<?php endif;?>">Завтра</a>
                        <a href="/?sort=past" class="tasks-switch__item <?php if (isset($_GET['sort']) && $_GET['sort'] == 'past'):?>tasks-switch__item--active<?php endif;?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox"<?php if ($show_complete_tasks == 1):?> checked<?php endif; ?>>
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>
                <table class="tasks">
                    <p><?=$task_search_null?></p>
                    <?php foreach ($tasks as $task): 
                        $time_to_task = strtotime($task['date']) -  time();
                        $hours_left = floor($time_to_task / 3600);
                        ?>
                        <?php if ($show_complete_tasks == 0 && $task['status'] == true): ?> <?continue;?><?php endif ?>
                    <tr class="tasks__item task <?php if ($task['status'] == true): ?> <?=' task--completed';?><?php endif ?><?php if ($hours_left >=0 && $hours_left <= 24): ?> <?=' task--important';?><?php endif ?>">
                        <td class="task__select">
                            <label class="checkbox task__checkbox">
                                <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=($task['task_ident']);?>"<?php if ($task['status'] == true):?> checked<?php endif; ?>>
                                <span class="checkbox__text"><?=$task['task_name'];?></span>
                            </label>
                        </td>

                        <td class="task__file">
                            <a class="download-link<?php if($task['file_path'] === NULL):?><?=' hidden'?><?php endif?>" href="<?=$task['file_path'];?>"><?=$task['file_path'];?></a>
                        </td>

                        <td class="task__date"><?=date('d.m.Y',strtotime($task['date']));?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </main>
        </div>