<?php

include('pendu.php');

session_start();
// On démarre la session
// session_start() crée une session ou restaure celle trouvée sur le serveur, via l'identifiant de session passé dans une requête GET, POST ou par un cookie

if(!isset($_SESSION['mot'])){ // isset() détermine si une variable est déclarée et est différente de null, le point d'exclamation indique que c'est le contrainre de isset
// Si il n'y a pas de mot en session
    $mots = file("mots.txt"); // Lit un fichier et renvoie le résultat dans un tableau
    $mot = rtrim(strtoupper($mots[array_rand($mots)])); // rtrim() supprime les espaces (ou d'autres caractères) de fin de chaîne
    $_SESSION['mot'] = $mot; // $_SESSION est une superglobale qui représente un tableau associatif des valeurs stockées dans les sessions, et accessible au script courant
    $_SESSION['essais'] = [];
    $_SESSION['vies'] = 6;
    // On récupère les mots du fichiers texte dans une variable
    // Dans une autre variable, on récupère un mot aléatoire de la variable précédente
    // On met ce mot en session
    // On met également un tableau vide en session, dans lequel on viendra rajouter, au fur et à mesure, les lettres qu'on devine
    // Et on met également en session le nombre de vies du joueur

    if(!isset($_SESSION['partiesGagnees'])){
        $_SESSION['partiesGagnees'] = 0;
    }
    // Si le nombre de parties gagnées en session n'existe pas, alors on l'initialise à 0
    if(!isset($_SESSION['partiesPerdues'])){
        $_SESSION['partiesPerdues'] = 0;
    }
    // Si le nombre de parties perdues en session n'existe pas, alors on l'initialise à 0
}

if(isset($_POST['essai'])){ // $_POST est une superglobale qui représente un tableau associatif des valeurs passées au script courant via le protocole HTTP et la méthode POST
// Si on indique une lettre dans le formulaire
    if(!in_array($_POST['essai'], $_SESSION['essais'])){ // in_array est une fonction qui permet de vérifier si une valeur appartient à un tableau
    // Si la lettre indiquée n'est pas dans le tableau des lettres devinées
        if(strpos($_SESSION['mot'], $_POST['essai']) === FALSE){ // strpos() cherche la position de la première occurrence dans une chaîne
        // Si la lettre qu'on vient d'indiquer n'est pas dans le mot
            $_SESSION['vies']--;
            // On perd une vie
        }
        $_SESSION['essais'][] = $_POST['essai'];
        // On ajoute la lettre dans le tableau des lettres déjà devinées
    }
}

$lettresRestantes = array_diff(range('A', 'Z'), $_SESSION['essais']);
// array_diff permet de faire la différence entre deux tableaux, et range permet d'avoir l'intervalle complet entre deux éléments, compris
// On crée une variable tableau, dans lequel on va mettre toutes les lettres de A à Z, qui ne sont pas dans le tableau des lettres devinées

if($_SESSION['vies'] <= 0){
// Si le nombre de vies tombe à 0
    echo "<div class='alert alert-dismissible alert-danger'>
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            <strong>Tu as perdu! </strong><br> Le mot était " . $_SESSION['mot']."
          </div>
          <figure class='lost'>
            <img src='./img/perdu.PNG' alt='fini'>
          </figure>
          <figure class='lost'>
            <img class='pendu' src='./img/loose.jpg' alt=''>
          </figure>
          <div class='boutons'>
            <div class='bouton'><a class='restart-link' href='./restart.php'>Recommencer</a></div>
          </div>";
    $_SESSION['partiesPerdues']++;
    unset($_SESSION['mot']); // unset() permet de détruire une variable
    // On affiche un message au joueur qui lui indique qu'il a perdu
    // On lui indique le mot qu'il devait deviner
    // On incrémente de 1 le nombre de parties perdues
    // On retire le mot de la session
} else {
// S'il nous reste encore des vies
    $lettresRestantesADeviner = 0;
    $etat = '';
    $longueurDuMot = strlen($_SESSION['mot']); // strlen() permet de compter le nombre de caractères d'une chaîne
    // On crée une variable qui va accueillir, juste après, le nombre de lettres qu'il reste à deviner
    // On crée également une variable etat qui va nous servir juste en dessous
    // On cré une dernière variable qui va contenir le nombre de lettres du mot en session
    ?>
<div class="affichage">
    <div class="card text-white bg-info mb-3" style="max-width: 20rem;">
        <div class="card-header">Partie en cours</div>
        <div class="card-body">
            <?php
                for($i = 0; $i < $longueurDuMot; $i++){ // for() est une boucle qui permet de répéter une action dans une intervalle donnée
                // Pour $i allant de 0 au nombre de lettres du mot
                    if(in_array($_SESSION['mot'][$i], $_SESSION['essais'])){
                    // Si dans le tableau des lettres devinées, il y a la lettre à la place $i du mot
                        $etat .= $_SESSION['mot'][$i];
                        // On affiche cette lettre
                    } else {
                    // Sinon
                        $etat .= "_";
                        $lettresRestantesADeviner++;
                        // On affiche un underscore et on rajoute +1 au nombre de lettres à deviner
                    }
                    $etat .= " ";
                    // On met un espace entre chaque occurence
                }
                // Le .= permet d'ajouter quelque chose en plus de ce qu'il y a déjà dans la variable ?>
                <div class="partieEnCours">
                    <div class='motcache'><?= $etat ?></div>
                    <!-- On affiche la variable etat, qui va donc contenir le mot, avec les lettres cachées ou non -->
                    <?php
                    if($_SESSION['vies'] !=0 && $lettresRestantesADeviner !=0){ // La double esperluette permet de rajouter une condition (revient à dire "ET")
                        // Si le nombre de vies en session et le nombre de lettres à deviner sont différents de 0
                        ?>
                        <form method = "post" action = "" >
                        <!-- On met en place un formulaire avec une méthode post -->
                        <!-- C'est la méthode post qui nous permet de récuperer, plus haut, les lettres que le joueur indique -->
                            <select name = "essai">
                            <!-- On met en place une liste déroulante et lui attribue le nom essai, nom qui va donc nous permettre de récupérer les lettres -->
                            <?php
                                foreach($lettresRestantes AS $lettre){ // foreach() fournit une façon simple de parcourir des tableaux
                                    echo '<option value = "'.strtoupper($lettre).'">'.strtoupper($lettre).'</option>';
                                }   
                            ?>
                            <!-- Pour remplir la liste déroulante, on fait une boucle -->
                            <!-- Pour chaque lettre restante, on rajoute une ligne dans la liste qui correspond à cette lettre, et qui a pour value la lettre, le tout en majuscule -->
                            </select>
                            <input type = "submit" name = "submit" value = "ESSAI">
                            <!-- On rajoute un bouton pour que le joueur puisse valider sa sélection -->
                        </form>
                </div>
                    <?php
                    }
                    ?>          
        </div>
    </div>

    <figure>
        <img class="pendu" src="./img/image<?=$_SESSION['vies']?>.PNG" alt="">
    </figure>
    
    <div class="card text-white bg-primary mb-3" style="max-width: 20rem;">
        <div class="card-header">Infos session</div>
        <div class="card-body">
            <p class="card-text">Il vous reste <?= $_SESSION['vies'] ?> vies</p>
            <p class="card-text">Nombre de victoires : <?= $_SESSION['partiesGagnees'] ?></p>
            <p class="card-text">Nombre de défaites : <?= $_SESSION['partiesPerdues'] ?></p>
        </div>
    </div>
</div>
<img src="" alt="" srcset="">
<div class="boutons">
    <div class='bouton'><a class='restart-link' href='./restart.php'>Recommencer</a></div>
    <div class='bouton'><a class='reinit-link' href='./reinitialiser.php'>Effacer statistiques</a></div>
</div>

<?php 
    if($lettresRestantesADeviner == 0){ // Le double égal permet de faire une vérification simple, uniquement au niveau des valeurs, sans se soucier de leur type
        // Si le nombre de lettres à deviner tombe à 0
            header('Location: victoire.php');
            $_SESSION['partiesGagnees']++;
            unset($_SESSION['mot']);
            // On indique au joueur qu'il a gagné (via une redirection sur une autre page)
            // On incrémente de 1 le nombre de parties gagnées
            // On retire le mot de la session
    }
}
?>
