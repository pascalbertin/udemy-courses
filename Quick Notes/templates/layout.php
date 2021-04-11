<html>
    <head>
        <title>Magazyn</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="./public/style.css" type="text/css">
        <link rel="icon" href="./public/favicon.png">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;700&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <div class="wrapper">
            <div class="header">
                <h1><i class="fas fa-box"></i> MAGAZYN</h1>
            </div>

            <div class="container">
                <div class="nav">
                    <ul>
                        <li><a href="/">Lista produktów</a></li>
                        <li><a href="/?action=add">Dodaj produkt</a><br></li>
                    </ul>
                </div>

                <div class="content">
                    <?php require_once("templates/pages/$page.php"); ?>
                </div>
            </div>

            <div class="footer">
                <p>Storage Project by Pascal Bertin</p>
            </div>
        </div>
    </body>
</html>