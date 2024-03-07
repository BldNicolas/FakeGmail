<main class="vertical__full">
    <?php
    if ($_SESSION['user_created'] == 'true')
    {
        echo "<script>showNotification('Votre compte a bien été créé, veuillez vous connectez', 'success');</script>";
        $_SESSION['user_created'] = 'false';
    }
    ?>
    <div id="logInForm" class="form">
        <?php
        use pages\controller\UserManager;

        include_once './pages/controller/UserManager.php';

        UserManager::logIn();
        ?>
        <div class="form__title">Connectez-vous à votre compte</div>
        <form action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <ul class="form__content">
                <li class="form__question">
                    <label class="form__label" for="mail">Mail : *</label>
                    <input class="form__input" type="email" id="mail" name="user_mail" placeholder="Votre mail">
                </li>
                <li class="form__question">
                    <label class="form__label" for="password">Mot de passe : *</label>
                    <input class="form__input" type="password" id="password" name="user_password" placeholder="Votre mot de passe">
                </li>
                <li class="form__submit">
                    <input class="btn btn__square btn--blue text--uppercase" type="submit" value="Envoyer">
                </li>
            </ul>
        </form>
    </div>
</main>