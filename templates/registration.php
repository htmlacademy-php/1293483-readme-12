<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration">Регистрация</h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="/registration.php" method="post" enctype="multipart/form-data">
            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= $errors['email'] ? 'form__input-section--error' :''; ?>">
                            <input class="registration__input form__input" id="registration-email" type="text" name="email" placeholder="Укажите эл.почту" value="<?= htmlspecialchars($user['email']) ?? ''; ?>"><!--заменить тип на email!-->
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= $title_errors['email']; ?></h3>
                                <p class="form__error-desc"><?= $errors['email']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= $errors['login'] ? 'form__input-section--error' :''; ?>">
                            <input class="registration__input form__input" id="registration-login" type="text" name="login" placeholder="Укажите логин" value="<?= htmlspecialchars($user['login']) ?? ''; ?>">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= $title_errors['login']; ?></h3>
                                <p class="form__error-desc"><?= $errors['login']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password">Пароль<span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= $errors['password'] ? 'form__input-section--error' :''; ?>">
                            <input class="registration__input form__input" id="registration-password" type="password" name="password" placeholder="Придумайте пароль">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= $title_errors['password']; ?></h3>
                                <p class="form__error-desc"><?= $errors['password']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="registration__input-wrapper form__input-wrapper">
                        <label class="registration__label form__label" for="registration-password-repeat">Повтор пароля<span class="form__input-required">*</span></label>
                        <div class="form__input-section <?= $errors['password-repeat'] ? 'form__input-section--error' :''; ?>">
                            <input class="registration__input form__input" id="registration-password-repeat" type="password" name="password-repeat" placeholder="Повторите пароль">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title"><?= $title_errors['password-repeat']; ?></h3>
                                <p class="form__error-desc"><?= $errors['password-repeat']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (count($errors)): ?>
                    <div class="form__invalid-block">
                        <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                        <ul class="form__invalid-list">
                            <?php foreach ($errors as $error => $message): ?>
                            <li class="form__invalid-item"><?= "{$title_errors[$error]}. $message"; ?>.</li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="registration__input-file-container form__input-container form__input-container--file">
                <div class="registration__input-file-wrapper form__input-file-wrapper">
                    <input class="registration__input-file form__input-file visually-hidden" id="userpic-file" type="file" name="avatar" title=" ">
                    <label class="registration__input-file-button form__input-file-button button" for="userpic-file">
                        <span>Выбрать фото</span>
                        <svg class="registration__attach-icon form__attach-icon" width="10" height="20">
                            <use xlink:href="#icon-attach"></use>
                        </svg>
                    </label>
                </div>
            </div>
            <button class="registration__submit button button--main" type="submit">Отправить</button>
        </form>
    </section>
</main>
