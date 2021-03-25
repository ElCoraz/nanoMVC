<html>

<head>
    <meta charset="utf-8">
    <title>
        TO DO
    </title>

    <link rel="shortcut icon" href="/img/favicon.png" type="image/png">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">

</head>

<body>
    <!-- HEADER -->
    <header>
        <div class="d-flex align-items-center justify-content-center" style="width: 100%;">
            <div class="p-2 bd-highlight">
                TO DO LIST
            </div>
        </div>
    </header>
    <!-- HEADER -->
    <section>
        <div class="container">
            <h2>Login</h2>
            <form action="/login/login" method="post">

                <div class="container">
                    <label for="uname"><b>Username</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required>

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required>

                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
                <? if (isset($data['data']['error'])) { ?>
                    <div class="row">
                        <div class="col-12" style="text-align: center;">
                            <h2 style='color:red'><?=$data['data']['error']?></h2>
                        </div>
                    </div>
                <? } ?>
            </form>

        </div>
    </section>
    <!-- FOOTER -->
    <footer>
        <div class="d-flex align-items-center justify-content-center" style="width: 100%;">
            <div class="p-2 bd-highlight">
                Copyright @<?= (new \DateTime())->format('Y') ?>
            </div>
        </div>
    </footer>
    <!-- FOOTER -->
</body>

<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

</html>