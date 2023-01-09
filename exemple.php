<?php

require_once 'src/EnteteSVG.php';

$svg = new EnteteSVG('svg/koala.svg');

//Attributs du <svg>
$svg->definirUnId('koala');
$svg->definirUneClasse('svg');
$svg->ajouterUneClasse('flex');
$svg->definirUneCouleur('#42A0FF');
$svg->redimensionner('128', '128');
//$svg->enregistrer('svg');

//Balises dans le <svg>
$svg->definirUnTitre(ucfirst('un Koala'));
$svg->definirUnLien(ucfirst('aller sur google'), 'https://www.google.com/', 'texte');

// TESTS UNITAIRES

/*
echo '<pre>';
print_r($svg);
echo '</pre>';
*/

/*
echo '<pre>';
print_r($svg->balises);
echo '</pre>';
*/

/*
echo '<pre>';
print_r($svg->balisesAttributs);
echo '</pre>';
*/

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVG | Exemple</title>
</head>

<body>

    <main>
        <section>

            <h1>Le SVG à rendre :</h1>

            <?= $svg->rendre($svg); ?>

        </section>

    </main>

</body>

</html>