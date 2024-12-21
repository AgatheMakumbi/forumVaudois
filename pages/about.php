<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>À Propos</title>
</head>

<body>
    <?php include '../components/header.php'; ?>
    <main>
        <!-- Introduction Section -->
        <section class="introduction">
            <div class="content">
                <h2>Introduction</h2>
                <p>Bienvenue sur le Forum Vaudois, une plateforme dédiée aux échanges et aux discussions pour les habitants et les visiteurs du canton de Vaud. Notre objectif est de faciliter la communication, de promouvoir les activités locales, et de renforcer les liens entre les membres de la communauté. Que vous soyez à la recherche d'événements, de bons plans, ou simplement d'un espace pour partager vos idées, le Forum Vaudois est là pour vous.</p>
            </div>
            <div class="objectives">
                <h2>Objectifs et mission</h2>
                <ul>
                    <li>Promouvoir les lieux et activités locales : Offrir une visibilité aux événements culturels, aux entreprises locales et aux initiatives communautaires.</li>
                    <li>Faciliter les échanges : Proposer un espace de discussion convivial et inclusif où chacun peut contribuer.</li>
                    <li>Renforcer la communauté : Créer un sentiment d'appartenance en favorisant la collaboration et le partage d'expériences.</li>
                </ul>
            </div>
        </section>

        <!-- Team Section -->
        <section class="team">
    <h2>L'équipe</h2>
    <div class="team-members">
        <div class="member">
            <div class="photo"></div>
            <p>Abessolo Joshua</p>
            <p class="role">Développeur Backend</p>
        </div>
        <div class="member">
            <div class="photo"></div>
            <p>Desgalier Michael</p>
            <p class="role">Développeur Frontend</p>
        </div>
        <div class="member">
            <div class="photo"></div>
            <p>Jaccard Mathilde</p>
            <p class="role">Responsable Planning et Design</p>
        </div>
        <div class="member">
            <div class="photo"></div>
            <p>Makumbi Agathe</p>
            <p class="role">Chef de Projet</p>
        </div>
        <div class="member">
            <div class="photo"></div>
            <p>Mayor Joanah</p>
            <p class="role">Experte en Base de Données</p>
        </div>
    </div>
</section>


        <!-- Footer Section -->
        <footer>
            <div class="footer-content">
                <div class="info">
                    <h3>Information</h3>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
                <div class="company">
                    <h3>Company</h3>
                    <p>Adresse : Av. des Sports 20, 1401 Yverdon-les-Bains</p>
                    <p>Téléphone : <a href="tel:+41245577600">024 557 76 00</a></p>
                </div>
                <div class="contact-form">
                    <form action="#" method="post">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" placeholder="Nom" required>

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>

                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone" placeholder="Téléphone" required pattern="^\\+41\\d{9}$">

                        <label for="message">Message</label>
                        <textarea id="message" name="message" placeholder="Message" required></textarea>

                        <button type="submit">Envoyer</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>Copyright 2024 HEIG-VD ProgServ2</p>
            </div>
        </footer>
    </main>
</body>

</html>
