<main>
    <h3 class="inbox-container">Bienvenue dans votre espace null</h3>
    <div id="logInForm" class="form">
        <div class="form__title">Connectez-vous à votre compte</div>
        <form action="./connexion.html" method="post">
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