<main>
    <div class="btn btn__round btn--red">
        <a href="#bottomPageIndex">
            <img src="./asset/arrow.png" alt="">
        </a>
    </div>
    <div id="#welcomePage" class="welcome-page">
        <img src="./asset/home-hero.jpg" class="background-picture" alt="fond arriere plan">
        <div class="welcome-content">
            <h1>Retrouvez la fluidité et la simplicité de Gmail sur tous vos appareil</h1>
            <a href="#signUpForm">
                <div class="btn btn__square btn--red text--uppercase">créer un compte</div>
            </a>
        </div>
    </div>
    <div class="inbox-container">
        <h2>Une boîte de réception entièrement repensée</h2>
        <p>Avec les nouveaux onglets personnalisables, repérez immédiatement les nouveaux messages et choisissez ceux que vous souhaitez lire en priorité.</p>
    </div>
    <div id="signUpForm" class="form">
        <?php
        use pages\controller\UserManager;

        include_once './pages/controller/UserManager.php';

        UserManager::createDB(UserManager::dbName);
        UserManager::createTable("user", UserManager::dbName);
        UserManager::signUp();
        ?>
        <div class="form__title">Créer un compte</div>
        <form method="post">
            <ul class="form__content">
                <li class="form__question">
                    <label class="form__label" for="lastName">Nom : *</label>
                    <input class="form__input" type="text" id="lastName" name="user_last_name" placeholder="Votre nom">
                </li>
                <li class="form__question">
                    <label class="form__label" for="firstName">Prénom : *</label>
                    <input class="form__input" type="text" id="firstName" name="user_first_name" placeholder="Votre prénom">
                </li>
                <li class="form__question">
                    <label class="form__label" for="mail">Mail : *</label>
                    <input class="form__input" type="email" id="mail" name="user_mail" placeholder="Votre mail">
                </li>
                <li class="form__question">
                    <label class="form__label" for="password">Choisissez votre mot de passe : *</label>
                    <input class="form__input" type="password" id="password" name="user_password" placeholder="Veuillez entrer un mot de passe">
                </li>
                <li class="form__submit">
                    <input class="btn btn__square btn--blue text--uppercase" type="submit" value="valider votre compte">
                </li>
            </ul>
        </form>
    </div>
</main>