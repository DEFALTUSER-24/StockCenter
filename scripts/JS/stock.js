$("#picture").change(function(e) {
    $("#picturesLabel").text(e.target.files[0].name);
});

//VER SI EXISTE
$.fn.exists = function () {
    return this.length !== 0;
}

function errorHandler(jqXHR, textStatus, errorThrown)
{
    if (jqXHR.status === 0) { alert("No hay conexión a internet."); } 
    else if (jqXHR.status == 404) { alert('No se encontró la pagina [404]'); }
    else if (jqXHR.status == 500) { alert('Error interno del servidor [500].'); }
    else if (textStatus === 'parsererror') { alert('Error de sintaxis en JSON.'); }
    else if (textStatus === 'timeout') { alert('Tiempo de espera agotado.'); }
    else if (textStatus === 'abort') { alert('Envío de AJAX abortado.'); }
    else { alert('Error no detectado: ' + jqXHR.responseText); }
}

function getTableElements()
{
    if($("#stockTable").find("tbody").exists)
    {
        return $("tr").length -1;
    }
    else
    {
        return 0;
    }
}


$("#searchBox").on("keyup", function() {
    var value = $("#searchBox").val().toUpperCase();
    $("#stockTable tbody tr").filter(function() {
        $(this).toggle($(this).text().toUpperCase().indexOf(value) > -1)
    });
});

function removeEveryClass()
{
    var content = document.getElementsByTagName("td");
    for(var t = 0; t < content.length; t++)
    {
        if (content[t].parentElement.classList.contains("animatedResult"))
            content[t].parentElement.classList.remove("animatedResult");
    }
}

function sortTable(n)
{
    var orderInfo = $("#stockTable th:nth-child(" + (n + 1) + ")" ).text();
    $("#successMsg").text("Tabla ordenada por " + orderInfo);
    $("#toastSuccess").toast("show");
    
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("stockTable");
    switching = true;
    dir = "asc";
    while (switching)
    {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++)
        {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir == "asc")
            {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase())
                {
                    shouldSwitch = true;
                    break;
                }
            }
            else if (dir == "desc")
            {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase())
                {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch)
        {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount ++; 
        }
        else
        {
            if (switchcount == 0 && dir == "asc")
            {
                dir = "desc";
                switching = true;
            }
        }
    }
}

function modifyProduct(object)
{
    $(".loading").css("display", "block");
    var codeInfo = $(object).closest('tr').children().eq(2).text();
    $.get("scripts/PHP/stock/stockFind.php", { code: codeInfo } )
        .done(function(r) {
            $(".loading").css("display", "none");
            if(r.success)
            {
                $("#codeToEdit").text(codeInfo);
                $("#codeIDEdit").val(codeInfo);
                $("#stockEditName").val(r.data.DESC);
                $("#stockEditUnits").val(r.data.UNITS);
                $("#stockEditPrice").val(r.data.PRICE);

                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");

                $("#stockEditModal").modal("show");
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

function deleteProduct(object)
{
    if(confirm("¿Está seguro de querer eliminar el producto seleccionado?"))
    {
        $(".loading").css("display", "block");
        var codeInfo = $(object).closest('tr').attr('id');
        $.get("scripts/PHP/stock/stockRemove.php", { code: codeInfo } )
            .done(function(r) {
                $(".loading").css("display", "none");
                if(r.success)
                {
                    if (getTableElements() > 1)
                    {
                        $(object).closest('tr').empty();
                        $("#" + codeInfo).closest('tr').remove();
                        $("#successMsg").text(r.message);
                        $("#toastSuccess").toast("show");
                    }
                    else
                    {
                        location.reload();
                    }
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

$("#addProductForm").submit(function(event){
	event.preventDefault(); 
    var formData = new FormData(this);

    $("#stockAddFooter").css("display", "none");    
    
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
                    $("#uploadBar").css("display", "block");
                    $("#uploadBar div").first().css("width", percentComplete + "%");
                    $("#uploadBar div").first().text("%" + percentComplete);
                }
            }, false );
            return jqXHR;
        },
        success: function (r) {
            $("#uploadBar").css("display", "none");
            $("#stockAddFooter").css("display", "flex"); 
            if (r.success)
            {
                if (getTableElements() > 0)
                {
                    $("#stockAddResponse").css("display", "none");
                    $('#stockTable > tbody:last-child').append("<tr id=" + r.data.CODE + "><td class='d-none d-md-table-cell text-center'>" + r.data.PIC + "</td><td class='align-middle'>" + r.data.DESC + "</td><td class='d-none d-xl-table-cell align-middle'>" + r.data.CODE + "</td><td class='align-middle'>" + r.data.UNITS + "</td><td class='align-middle'>$ " + r.data.PRICE + "</td><td class='align-middle text-center'><button class='btn btn-danger btn-sm ml-1' title='Eliminar producto' type='button' onclick='deleteProduct(this)'><i class='fas fa-trash-alt'></i> <small class='d-none d-md-inline'>Eliminar</small></button><button class='btn btn-info btn-sm' title='Editar producto' type='button' onclick='modifyProduct(this)'><i class='far fa-edit'></i> <small class='d-none d-md-inline'>Editar</small></button></td></tr>");
                    
                    $("#successMsg").text(r.message);
                    $("#toastSuccess").toast("show");
                    $("#stockAddReset").click();
                    
                    if(!confirm("¿Desea agregar otro producto?"))
                        $("#stockAddModal").modal("hide");
                }
                else
                {
                    location.reload();
                }
            }
            else
            {
                $("#stockAddResponse").css("display", "block")
                                      .text(r.message);
            }
        },
        error: function(xhr, status, error) {
            $("#stockAddResponse").css("display", "block")
                .text(xhr.responseText);

            $("#stockAddFooter").css("display", "flex");
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

$("#modifyProductForm").submit(function(event){
	event.preventDefault(); 
    var formData = new FormData(this);
    var code = "#" + $("#codeIDEdit").val();
    
    $("#stockEditFooter").css("display", "none");
    
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
                    $("#uploadBarEdit").css("display", "block");
                    $("#uploadBarEdit div").first().css("width", percentComplete + "%");
                    $("#uploadBarEdit div").first().text("%" + percentComplete);
                }
            }, false );
            return jqXHR;
        },
        success: function (r) {
            $("#uploadBarEdit").css("display", "none");
            $("#stockEditFooter").css("display", "flex"); 
            if (r.success)
            {
                $("#stockEditModal").modal("hide");
                $("#successMsg").text(r.message);
                $("#toastSuccess").toast("show");
                $("#stockAddReset").click();

                $(code).children("td").eq(1).text(r.data[0]);
                $(code).children("td").eq(3).text(r.data[1]);
                $(code).children("td").eq(4).text("$ " + r.data[2]);
            }
            else
            {
                $("#stockEditResponse").css("display", "block")
                                      .text(r.message);
            }
        },
        error: function(xhr, status, error) {
            $("#stockEditResponse").css("display", "block")
                .text(xhr.responseText);

            $("#stockEditResponse").css("display", "flex");
        },
        cache: false,
        contentType: false,
        processData: false
    });
});