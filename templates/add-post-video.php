<input type="hidden" name="content_type_id" value="<?= $content_types[$index]['id']; ?>">
<div class="adding-post__input-wrapper form__input-wrapper">
    <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
    <div class="form__input-section <?= $errors['video'] ? 'form__input-section--error' :''; ?>">
        <input class="adding-post__input form__input" id="video-url" type="text" name="video" placeholder="Введите ссылку" value="<?= htmlspecialchars($post['video']) ?? ''; ?>">
        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title"><?= $title_errors['video']; ?></h3>
            <p class="form__error-desc"><?= $errors['video']; ?></p>
        </div>
    </div>
</div>