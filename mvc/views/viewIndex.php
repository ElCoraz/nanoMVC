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

    <div class="container">

        <!-- HEADER -->
        <header>

            <div class="d-flex align-items-center justify-content-center" style="width: 100%;">
                <div class="p-2 bd-highlight">
                    TO DO LIST
                </div>
            </div>

            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <? if (Authorization::IsAuthorization()) { ?>
                    <a href='/login/logout' class="btn btn-primary"></i>(Admin)&nbsp;Выход</a>
                    <? } else { ?>
                    <a href='/login' class="btn btn-primary"></i>Вход</a>
                    <? } ?>
                </div>
            </div>

        </header>
        <!-- HEADER -->

        <!-- TO DO LIST -->
        <section>
        
            <div class='row'>
                <div class="col-12">
                    Сортировка:&nbsp;
                    <select id='order'>
                        <option style="display:none" value="default" <?=!isset($data['order']) ? 'selected="selected"' : '' ?>></option>
                        <option value="name" <?=(isset($data['order']) && ($data['order'] == 'name')) ? 'selected="selected"' : '' ?>>Name</option>
                        <option value="email" <?=(isset($data['order']) && ($data['order'] == 'email')) ? 'selected="selected"' : '' ?>>Email</option>
                        <option value="status" <?=(isset($data['order']) && ($data['order'] == 'status')) ? 'selected="selected"' : '' ?>>Status</option>
                    </select>&nbsp;
                    <select id='direction'>
                        <option value="ASC"<?=(isset($data['direction']) && ($data['direction'] == 'ASC')) ? 'selected="selected"' : '' ?>>По возрастанию</option>
                        <option value="DESC"<?=(isset($data['direction']) && ($data['direction'] == 'DESC')) ? 'selected="selected"' : '' ?>>По убыванию</option>
                    </select>
                </div>
            </div>

            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <button type="button" class="btn btn-primary" id="new"><i class="fas fa-plus"></i>&nbsp;Добавить</button>
                </div>
            </div>

            <div class='row'>

                <? foreach ($data['values'] as $field)  { ?>
                <!-- NEW CARD -->
                <div class='col-4'>
                    <div class="card<?=(Authorization::IsAuthorization()? '-edit' : '')?>" id="<?= $field['id'] ?>">
                        <div class="card-body p-3">
                            <div class="row">
                                <label>Name:&nbsp;<h2><?= $field['name'] ?></h2></label>
                            </div>
                            <div class="row">
                                <label>Text:&nbsp;<?= $field['text'] ?></label>
                            </div>
                            <div class="row">
                                <label>Email:&nbsp;<?= $field['email'] ?></label>
                            </div>
                            <div class="row">
                                <label>Status:&nbsp;<?= $field['status'] ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- NEW CARD -->
                <? } ?>

            </div>

        </section>
        <!-- TO DO LIST -->

        <!-- PAGINATION -->
        <?
            $order = '';

            if (isset($data['order'])) {
                $order .= '&order='.$data['order'];
            }
            if (isset($data['direction'])) {
                $order .= '&direction='.$data['direction'];
            }

        ?>
        <div class="row">
            <div class="d-flex align-items-center justify-content-center" style="width: 100%;">
                <div class="p-2 bd-highlight">
                    <? $count = round(count($data['pagination'])/3, PHP_ROUND_HALF_UP); $last = -1;?>
                    <? if ($count > 1) {
                    if (isset($params['page'])) { ?>
                    <a href="/index?page=<?= ($params['page'] - 1 < 0? '0' : $params['page'] - 1) . $order ?>"><i class="fas fa-backward"></i></a>
                    <? } else { ?>
                    <a href="/index?page=0<?=$order?>"><i class="fas fa-backward"></i></a>
                    <? }
                    for ($i= 0; $i < $count; $i++, $last++) { ?>
                    <a href="/index?page=<?= $i . $order ?>"><?= ($i + 1) ?></a>
                    <? } 
                    if (isset($params['page']) && ($params['page'] + 1) < $count)  { ?>
                    <a href="/index?page=<?= ($params['page'] + 1) . $order ?>"><i class="fas fa-forward"></i></a>
                    <? } else { ?>
                    <a href="/index?page=<?= $last . $order ?>"><i class="fas fa-forward"></i></a>
                    <? } }?>
                </div>
            </div>
        </div>
        <!-- PAGINATION -->

        <!-- Alert message box -->
        <div class="modal" tabindex="-1" role="dialog" id="messageBoxError">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="titleError" class="modal-title">Ошибка</h5>
                    </div>
                    <div class="modal-body">
                        <p id="message"></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary" value="Закрыть" id="close">
                    </div>
                </div>
            </div>
        </div>
        <!-- Alert message box -->

        <!-- New task box -->
        <div class="modal" tabindex="-1" role="dialog" id="messageBoxNew">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="titleMessage" class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="id">
                        <input type="hidden" class="form-control" id="isAdmin" value='<?=(Authorization::IsAuthorization()? '1' : '0')?>'>
                        <input type="hidden" class="form-control" id="isAdminEdit" value='0'>

                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" class="form-control" id="name" placeholder="Введите имя">
                        </div>

                        <div class="form-group">
                            <label for="text">Текст</label>
                            <textarea class="form-control" id="text" rows="10" placeholder="Введите текст"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="name">E-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="Введите email">
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="status">
                            <label class="form-check-label" for="status">Выполнено</label>
                        </div>

                        <div class="form-group" style="color: red;" id="isAdminTitle">
                            <h5>Отредактировано администратором</h5>
                        </div>

                        <div class="modal-footer">
                            <input type="button" class="btn btn-primary" value="Сохранить" id="add">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- New task box -->

        <!-- FOOTER -->
        <footer>

            <div class="d-flex align-items-center justify-content-center" style="width: 100%;">
                <div class="p-2 bd-highlight">
                    Copyright @<?= (new \DateTime())->format('Y') ?>
                </div>
            </div>

        </footer>
        <!-- FOOTER -->

    </div>

</body>

<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/app.js"></script>

</html>