<form class="form form--add-lot container <?= isset($errors) ? "form--invalid" : ""; ?>" action="" method="post"
      enctype="multipart/form-data"><!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($errors['title']) ? "form__item--invalid" : ""; ?>">
            <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <?php $value = isset($_POST['title']) ? $_POST['title'] : "" ?>
            <input id="lot-name" type="text" name="title" placeholder="Введите наименование лота"
                   value="<?= $value; ?>">
            <span class="form__error"><?= $errors['title']; ?></span>
        </div>
        <div class="form__item <?= isset($errors['category_id']) ? "form__item--invalid" : ""; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category_id">
                <option disabled selected>Выберите категорию</option>
                <?php foreach ($categories as $key) : ?>
                    <option value="<?= $key['id'] ?>"><?= $key['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= $errors['category_id']; ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($errors['description']) ? "form__item--invalid" : ""; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description" placeholder="Напишите описание лота"></textarea>
        <span class="form__error"><?= $errors['description']; ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($errors['file']) ? "form__item--invalid" : ""; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" value="" name="image">
            <label for="lot-img">
                Добавить
            </label>
        </div>
        <span class="form__error"><?= $errors['file']; ?></span>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?= isset($errors['starting_price']) ? "form__item--invalid" : ""; ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <?php $value = isset($_POST['starting_price']) ? $_POST['starting_price'] : "" ?>
            <input id="lot-rate" type="text" name="starting_price" placeholder="0" value="<?= $value; ?>">
            <span class="form__error"><?= $errors['starting_price']; ?></span>
        </div>
        <div class="form__item form__item--small <?= isset($errors['bet_step']) ? "form__item--invalid" : ""; ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <?php $value = isset($_POST['bet_step']) ? $_POST['bet_step'] : "" ?>
            <input id="lot-step" type="text" name="bet_step" placeholder="0" value="<?= $value; ?>">
            <span class="form__error"><?= $errors['bet_step']; ?></span>
        </div>
        <div class="form__item <?= isset($errors['completed_at']) ? "form__item--invalid" : ""; ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <?php $value = isset($_POST['completed_at']) ? $_POST['completed_at'] : "" ?>
            <input class="form__input-date" id="lot-date" type="text" name="completed_at"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $value; ?>">
            <span class="form__error"><?= $errors['completed_at']; ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
