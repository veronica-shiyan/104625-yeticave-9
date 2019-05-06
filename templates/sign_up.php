<form class="form container <?= isset($errors) ? "form--invalid" : ""; ?>" action="" method="post"
      autocomplete="off" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : ""; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <?php $value = isset($_POST['email']) ? $_POST['email'] : "" ?>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $value; ?>">
        <span class="form__error"><?= $errors['email']; ?></span>
    </div>
    <div class="form__item <?= isset($errors['password']) ? "form__item--invalid" : ""; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <?php $value = isset($_POST['password']) ? $_POST['password'] : "" ?>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $value; ?>">
        <span class="form__error"><?= $errors['password']; ?></span>
    </div>
    <div class="form__item <?= isset($errors['login']) ? "form__item--invalid" : ""; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <?php $value = isset($_POST['login']) ? $_POST['login'] : "" ?>
        <input id="name" type="text" name="login" placeholder="Введите имя" value="<?= $value; ?>">
        <span class="form__error"><?= $errors['login']; ?></span>
    </div>
    <div class="form__item <?= isset($errors['contact']) ? "form__item--invalid" : ""; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contact" placeholder="Напишите как с вами связаться"></textarea>
        <span class="form__error"><?= $errors['contact']; ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($errors['file']) ? "form__item--invalid" : ""; ?>">
        <label>Аватар</label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="avatar" value="" name="avatar">
            <label for="avatar">
                Добавить
            </label>
        </div>
        <span class="form__error"><?= $errors['file']; ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>