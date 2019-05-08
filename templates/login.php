<form class="form container <?= isset($errors) ? "form--invalid" : ""; ?>" action="" method="post"
      enctype="application/x-www-form-urlencoded"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : ""; ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <?php $value = isset($_POST['email']) ? $_POST['email'] : "" ?>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $value; ?>">
        <span class="form__error"><?= $errors['email']; ?></span>
    </div>
    <div class="form__item form__item--last <?= isset($errors['password']) ? "form__item--invalid" : ""; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <?php $value = isset($_POST['password']) ? $_POST['password'] : "" ?>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $value; ?>">
        <span class="form__error"><?= $errors['password']; ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>