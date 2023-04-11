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

        <a class="button button--transparent button--plus content__side-button" href="add-project.php">Добавить проект</a>
      </section>

      <main class="content__main">
      <?php if (isset($errors)): ?>
        <div class="form__errors">
           <p></p>
           <ul>
             <?php foreach ($errors as $val): ?>
                <li><strong><?= $val; ?>:</strong></li>
             <?php endforeach; ?>
           </ul>
        </div>
     <?php endif; ?>
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  action="add-project.php" method="post" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <?php $classname = isset($errors['title']) ? "form__input--error" : ""; ?>
            <input class="form__input <?= $classname ?>" type="text" name="name" id="project_name" value="<?= getPostVal('project_name'); ?>" placeholder="Введите название проекта">
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>
</div>