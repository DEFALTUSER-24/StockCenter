var identityConfirmed = false;
var timesFailed = 0;

function showMyself()
{
    var checker = prompt("Escriba su clave para verificar identidad. AVISO: la clave es visible.");
    if (checker != null && checker != "")
    {
        console.log(checker);

        $(".loading").css("display", "flex");
        $.post("scripts/PHP/users/getMyData.php", { pass: checker } )
        .done(function (r) {
            $(".loading").css("display", "none");
            if (r.success)
            {
                identityConfirmed = true;
                timesFailed = 0;
                
                $("#nData").val(r.data.NAME);
                $("#pData").val(r.data.PASS);
                $("#uData").val(r.data.ACCESS);
                
                $("#userInfo").modal("show");
                
                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");
            }
            else
            {
                timesFailed++;
                if (timesFailed >= 3)
                {
                    window.location.href = 'index.php';
                }
                else
                {
                    $("#dangerMsg").text(r.message + " - " + timesFailed + "/3");
                    $("#toastDanger").toast("show");
                }
            }
        })
        .fail(function(xhr, status, error) {
            $(".loading").css("display", "none");
            $("#dangerMsg").text(xhr.responseText);
            console.log(xhr.responseText);
            $("#toastDanger").toast("show");
        });
    }
}

function dismissMyData()
{
    identityConfirmed = false;
    $("#userInfo").modal("hide");
    $("#nData").val("");
    $("#pData").val("");
    $("#uData").val("");
    
    var uOld = $("#oldUser").val();
    $.post("scripts/PHP/users/disableConfirmed.php", { user: uOld } )
        .fail(function () {
            $("#dangerMsg").text("Error al enviar datos.");
            $("#toastDanger").toast("show");
        });
}

function updateMyself()
{
    if (identityConfirmed)
    {
        var uName = $("#nData").val();
        var uPass = $("#pData").val();
        var uOld = $("#oldUser").val();
        if (uName != "" && uPass != "")
        {
            $(".loading").css("display", "block");
            $.post("scripts/PHP/users/updateData.php", { name: uName, pass: uPass, old: uOld } )
            .done(function (r) {
                $(".loading").css("display", "none");
                if (r.success)
                {
                    alert(r.message);
                    window.location.href = 'index.php';
                }
                else
                {
                    $("#dangerMsg").text(r.message);
                    $("#toastDanger").toast("show");
                }
            })
            .fail(function () {
                $(".loading").css("display", "none");
                $("#dangerMsg").text("Error al recibir datos.");
                $("#toastDanger").toast("show");
            });
        }
        else if(uName == "" || uPass == "")
        {
            $("#dangerMsg").text("Los campos no pueden estar vacíos.");
            $("#toastDanger").toast("show");
        }
    }
    else
    {
        $("#dangerMsg").text("LA IDENTIDAD NO ESTABA CONFIRMADA.");
        $("#toastDanger").toast("show");
    }
}

function removeUser(object)
{
    if (confirm("¿Está seguro de querer eliminar al usuario?"))
    {
        $(".loading").css("display", "block");
        var userInfo = $(object).closest('tr').children().eq(0).text();
        $.post("scripts/PHP/users/userRemove.php", { uNameData: userInfo } )
        .done(function (r) {
            $(".loading").css("display", "none");
            if (r.success)
            {
                $(object).closest('tr').remove();
                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");
            }
            else
            {
                $("#dangerMsg").text(r.message);
                $("#toastDanger").toast("show");
            }
        })
        .fail(function () {
            $(".loading").css("display", "none");
            $("#dangerMsg").text("Error al recibir datos.");
            $("#toastDanger").toast("show");
        });
    }
}

function modifyUser(object)
{
    $(".loading").css("display", "block");
    var nameInfo = $(object).closest('tr').children().eq(0).text();
    $.get("scripts/PHP/users/userFind.php", { searchingName: nameInfo } )
        .done(function(r) {
            $(".loading").css("display", "none");
            if(r.success)
            {
                $("#pastUsername").text(r.data.NAME);
                $("#userNameEdit").val(r.data.NAME);
                $("#updateduName").val(r.data.NAME);
                $("#updateduPass").val(r.data.PASSWORD);
                $("#updateduAccess").val(r.data.ACCESS);

                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");

                $("#userUpdateModal").modal("show");
            }
            else
            {
                $("#dangerMsg").text(r.message);
                $("#toastDanger").toast("show");
            }
        })
        .fail(function () {
            $(".loading").css("display", "none");
            $("#dangerMsg").text("Error al recibir datos.");
            $("#toastDanger").toast("show");
    });
}

$("#addUserForm").submit(function(event){
	event.preventDefault(); 
    var formData = new FormData(this);

    $("#userAddFooter").css("display", "none");    
    
    $.ajax({
        url: $(this).attr("action"),
        type: 'POST',
        data: formData,
        xhr: function () {
            var jqXHR = null;
            if ( window.ActiveXObject )
            {
                jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
            }
            else
            {
                jqXHR = new window.XMLHttpRequest();
            }
            //Upload progress
            jqXHR.upload.addEventListener( "progress", function ( evt )
            {
                if ( evt.lengthComputable )
                {
                    var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                    $("#uploadBarUser").css("display", "block");
                    $("#uploadBarUser div").first().css("width", percentComplete + "%");
                    $("#uploadBarUser div").first().text("%" + percentComplete);
                }
            }, false );
            return jqXHR;
        },
        success: function (r) {
            $("#uploadBarUser").css("display", "none");
            $("#userAddFooter").css("display", "flex"); 
            if (r.success)
            {
                $("#userAddResponse").css("display", "none");
                    
                $('#userTable > tbody:last-child').append("<tr><td class='align-middle text-center'>" + r.data.NAME + "</td><td class='align-middle text-center'>" + r.data.PASS + "</td><td class='align-middle text-center'>" + r.data.ACCESS + "</td><td class='align-middle text-center'><button class='btn btn-danger btn-sm ml-1' title='Eliminar usuario' type='button' onclick='removeUser(this)'><i class='fas fa-trash-alt'></i> <small>Eliminar</small></button><button class='btn btn-info btn-sm' title='Editar usuario' type='button' onclick='modifyUser(this)'><i class='far fa-edit'></i> <small>Editar</small></button></td></tr>");

                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");
                $("#userAddReset").click();

                if(!confirm("¿Desea agregar otro usuario?"))
                    $("#userAddModal").modal("hide");
            }
            else
            {
                $("#userAddResponse").css("display", "block")
                                      .text(r.message);
            }
        },
        error: function (r) {
            $("#userAddResponse").css("display", "block")
                                 .text("Error al enviar datos.");
            
            $("#userAddFooter").css("display", "flex"); 
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

$("#updateUserForm").submit(function(event){
	event.preventDefault(); 
    var formData = new FormData(this);

    $("#userUpdateFooter").css("display", "none");    
    
    $.ajax({
        url: $(this).attr("action"),
        type: 'POST',
        data: formData,
        xhr: function () {
            var jqXHR = null;
            if ( window.ActiveXObject )
            {
                jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
            }
            else
            {
                jqXHR = new window.XMLHttpRequest();
            }
            //Upload progress
            jqXHR.upload.addEventListener( "progress", function ( evt )
            {
                if ( evt.lengthComputable )
                {
                    var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                    $("#uploadBarUserUpdate").css("display", "block");
                    $("#uploadBarUserUpdate div").first().css("width", percentComplete + "%");
                    $("#uploadBarUserUpdate div").first().text("%" + percentComplete);
                }
            }, false );
            return jqXHR;
        },
        success: function (r) {
            $("#uploadBarUserUpdate").css("display", "none");
            $("#userUpdateFooter").css("display", "flex"); 
            if (r.success)
            {
                $("#userUpdateResponse").css("display", "none");
                
                var uName = $("#userNameEdit").val();
                $("#" + uName).closest('tr').children().eq(0).text(r.data.NAME);
                $("#" + uName).closest('tr').children().eq(1).text(r.data.PASS);
                $("#" + uName).closest('tr').children().eq(2).text(r.data.ACCESS);

                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");
                $("#userUpdateReset").click();
                $("#userUpdateModal").modal("hide");
            }
            else
            {
                $("#userUpdateResponse").css("display", "block")
                                        .text(r.message);
            }
        },
        error: function (r) {
            $("#userUpdateResponse").css("display", "block")
                                    .text("Error al enviar datos.");
            
            $("#userUpdateFooter").css("display", "flex"); 
        },
        cache: false,
        contentType: false,
        processData: false
    });
});