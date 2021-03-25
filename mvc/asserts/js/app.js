//************************************************************************************** */
function getPageValue() {
    var page = new URL(window.location.href).searchParams.get("page");

    return (page != null) ? 'page=' + page + '&' : ''; 
}
//************************************************************************************** */
function setSortHref() {
    location.href = '/index/?' + getPageValue() + 'order=' + $("#order").val() + '&direction=' + $("#direction").val();
}
//************************************************************************************** */
function validation() {
    let email =  $('#email');

    let status = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email.val());

    if (status) {
        if (!email.hasClass("is-valid")) {
            email.addClass("is-valid");
            email.removeClass("is-invalid");
        }
    } else {
        if (!email.hasClass("is-invalid")) {
            email.addClass("is-invalid");
            email.removeClass("is-valid");
        }
    }

    return status;
}
//************************************************************************************** */
$(document).ready(function () {

    $("#order").on('change', function() {
        $('#direction option')[0].selected = true;
        $('#direction').attr("disabled", false);

        setSortHref();
    });

    $("#direction").on('change', function() {
        setSortHref();
    });

    $("#close").on("click", function () {
        $('#messageBoxError').modal('hide');
        location.reload();
    });

    /** Добавление новой задачи*/
    $('#new').on("click", function () {
        $("#id").val(-1);
        $("#name").val('');
        $("#text").val('');
        $("#email").val('');

        $("#isAdminTitle").hide();

        $('#titleMessage').html("Новая задача");
        
        $('#messageBoxNew').modal('show');
    });

    /** Валидация email */
    $('#email').on('input', function () {        
        validation();
    });

    $('#text').on('input', function () {        
        if( $("#isAdmin").val() == '1') {
            $("#isAdminEdit").val('1');
        }
    });

    /** Редактирование задачи*/
    $('div[class="card-edit"]').on("click", function () {
        $.ajax({
            method: "GET",
            url: "/index/getbyid/?id=" + this.id
        }).done(function (data) {
            $("#id").val(data['id']);
            $("#name").val(data['name']);
            $("#text").val(data['text']);
            $("#email").val(data['email']);

            $("#isAdminTitle").hide();

            if (data['isAdmin'] == 1) {
                $("#isAdminTitle").show();
            }

            $("#status").prop("checked", data['status'] == 'Выполнено' ? true : false);

            $('#titleMessage').html("Редактирование задачи");

            $('#messageBoxNew').modal('show');
        });
    });

    /** Добавление нового объявления */
    $('#add').on("click", function () {
        if (validation()) {
            $('#messageBoxNew').modal('hide');

            let data = {
                'id': $("#id").val(),
                'name': $("#name").val(),
                'text': $("#text").val(),
                'email': $("#email").val(),
                'status': $("#status").is(":checked"),
                'isAdmin': $("#isAdminEdit").val(),
            };

            $.ajax({
                method: "POST",
                url: "/index/newtask",
                data: data
            }).done(function (data) {
                if (data.status === "success") {
                    if ($("#id").val() === -1) {
                        $("#titleAlert").html('Результат');
                        $('#message').html('Добавлена новая задача');
                        $('#messageBoxError').modal('show');
                    } else {
                        location.reload();
                    }
                } else {
                    $("#titleAlert").html('Ошибка');
                    $('#message').html(data.message);
                    $('#messageBoxError').modal('show');
                }
            });
        }
    });

});
//************************************************************************************** */
