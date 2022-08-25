var runningRequest = false;

$("#clientName").keyup(function (event) {    
    var input = event.target;
    var list_id = "client_list";
    var phpPage = "clientFind.php";
    
    if (!runningRequest && input.value != "")
    {
        $("#" + list_id).text("");
        runningRequest = true;
        $.get("/scripts/PHP/bills/" + phpPage, { dataToSearch: input.value } )
            .done(function(r) {
                runningRequest = false;
                
                if (r.maxIndex == 1)
                {
                    $("#clientNameText").css("display", "block").find("p").html("<span id='cliNameText'>" + r[0].name + "</span> DNI/CUIT: <span id='cliIDText'>" + r[0].ID + "</span>");
                }
                else if (r.maxIndex > 1) {
                    $("#clientNameText").css("display", "none").find("p").text("");
                }
            
                for(var i = 0; i < r.maxIndex; i++)
                {
                    var option = document.createElement('option');
                    option.value = r[i].name;
                    option.innerHTML = "(" + r[i].ID + ")";
                    document.getElementById(list_id).appendChild(option);
                }            
            })
            .fail(function () {
                runningRequest = false;
        });
    }
    else if (input.value == "") { $("#clientNameText").css("display", "none").find("p").text(""); }
});

$("#clientName").focusout(function () {
    if ($("#clientNameText").find("p").text() == "")
    {
        $("#clientAddModal").modal("show");
    }
});

$("#productName").keyup(function (event) {
    var input = event.target;
    var list_id = "product_list";
    var phpPage = "productFind.php";
    
    if (!runningRequest && input.value != "")
    {
        $("#" + list_id).text("");
        runningRequest = true;
        $.get("/scripts/PHP/bills/" + phpPage, { dataToSearch: input.value } )
            .done(function(r) {
                runningRequest = false;
                
                if (r.maxIndex == 1)
                {
                    $("#cantText").css("display", "block");
                    $("#stockAmount").css("display", "block").find("input").attr({"max" : r[0].units, "min" : 1});
                    $("#perUnitPrice").css("display", "block").find("span").text(r[0].price);
                    $("#maxStock").css("display", "block").find("span").text(r[0].units);
                    $("#divider").toggleClass("mt-3", true);
                }
                else if (r.maxIndex > 1)
                {
                    $("#cantText").css("display", "none");
                    $("#stockAmount").css("display", "none").find("input").attr({"max" : 0, "min" : 0}).val("0");
                    $("#perUnitPrice").css("display", "none").find("span").text("-");
                    $("#maxStock").css("display", "none").find("span").text("-");
                    $("#total").css("display", "none").find("span").text("-");
                    $("#addButton").css("display", "none");
                    $("#divider").toggleClass("mt-3", false);
                }
                for(var i = 0; i < r.maxIndex; i++)
                {
                    var option = document.createElement('option');
                    option.value = r[i].name;
                    option.innerHTML = "(" + r[i].code + ")";
                    document.getElementById(list_id).appendChild(option);
                }            
            })
            .fail(function () {
                runningRequest = false;
        });
    }
    else if (input.value == "")
    {
        $("#cantText").css("display", "none");
        $("#stockAmount").css("display", "none").find("input").attr({"max" : 0, "min" : 0}).val("0");
        $("#perUnitPrice").css("display", "none").find("span").text("-");
        $("#maxStock").css("display", "none").find("span").text("-");
        $("#total").css("display", "none").find("span").text("-");
        $("#addButton").css("display", "none");
        $("#divider").toggleClass("mt-3", false);
    }
});

$("#stockAmountInput").on('change', function (event) {
    if($("#stockAmountInput").val() != "")
    {
        var total = parseInt($("#perUnitPrice").css("display", "block").find("span").text()) * parseInt($("#stockAmountInput").css("display", "block").val());
        $("#total").css("display", "block").find("span").text(total);
        $("#addButton").css("display", "block");
    }
    else if($("#stockAmountInput").val() == "")
    {
        $("#total").css("display", "none").find("span").text("-");
        $("#addButton").css("display", "none");
    }
});

function searchProduct()
{
    var toSearch = document.getElementById("searchBox").value;
    var results = 0, found;
    if (toSearch != "")
    {
        var content = document.getElementsByTagName("td");
        for(var t = 0; t < content.length; t++)
        {
            if (content[t].innerHTML.includes(toSearch.toUpperCase()))
            {
                results++;
                found = content[t];
                found.parentElement.classList.add("animatedResult");
            }
        }

        if (results == 0)
        {
            $("#dangerMsg").text("No se encontraron resultados.");
            $("#toastDanger").toast("show");
        }
    }
    return false;
}

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

function AddNewClient()
{
    if ($("#newCliName").val() == "" || $("#newCliID").val() == "")
    {
        alert("Faltan llenar datos.");
    }
    else
    {
        $("#clientNameText").css("display", "block").find("p").html("<span id='cliNameText'>" + $("#newCliName").val() + "</span> DNI/CUIT: <span id='cliIDText'>" + $("#newCliID").val() + "</span>");
        
        $("#clientAddModal").modal("hide");
        
        $("#successMsg").text("Cliente agregado.");
        $("#toastSuccess").toast("show");
    }
}

function AddToList()
{
    var alreadyAdded = false;
    $('#factTable td').each(function () {
        if ($(this).text() == $("#productName").val())
            alreadyAdded = true;
    });
    
    if (alreadyAdded)
    {
        $("#dangerMsg").text("El producto ya se encuentra en la factura.");
        $("#toastDanger").toast("show");
    }
    else
    {
        $(".loading").css("display", "block");
        var pCode = $("#product_list").find("option").eq(0).text();
        pCode = pCode.replace('(','');
        pCode = pCode.replace(')','');
        $.get("scripts/PHP/stock/stockFind.php", { code: pCode } )
            .done(function(r) {
                $(".loading").css("display", "none");
                if(r.success)
                {
                    if ($("#stockAmountInput").val() > r.data.UNITS || $("#stockAmountInput").val() < 1)
                    {
                        $("#dangerMsg").text("La cantidad de stock ingresada no esta dentro del rango disponible (Productos agregados: " + $("#stockAmountInput").val() + " - En stock: " + $("#stockAmountInput").attr("max") + ")");
                        $("#toastDanger").toast("show");
                    }
                    else
                    {
                        $('#factTable > tbody:last-child').append("<tr><td class='text-center align-middle'><button class='btn btn-danger btn-sm ml-1' title='Eliminar producto' type='button' onclick='deleteProduct(this)'><i class='fas fa-trash-alt'></i></button></td><td class='align-middle'>" + $("#productName").val() + "</td><td class='align-middle'>" + parseInt($("#stockAmountInput").val()) + "</td><td class='align-middle'>$<span>" + $("#perUnitPrice").find("span").text() + "</span></td><td class='align-middle'>$<span>" + $("#total").find("span").text() + "</span></td></tr>");

                        $("#productName").val("");
                        $("#total").find("span").text("");
                        $("#stockAmount").find("input").attr({"max" : 0, "min" : 0}).val("0");
                        $("#productName").focus();
                        $("#cantText").css("display", "none");
                        $("#stockAmount").css("display", "none").find("input").attr({"max" : 0, "min" : 0}).val("0");
                        $("#perUnitPrice").css("display", "none").find("span").text("-");
                        $("#maxStock").css("display", "none").find("span").text("-");
                        $("#total").css("display", "none").find("span").text("-");
                        $("#addButton").css("display", "none");
                        $("#divider").toggleClass("mt-3", false);

                        $("#successMsg").text("Producto agregado.");
                        $("#toastSuccess").toast("show");
                        CalculateTotal();
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

function CalculateTotal()
{
    var total = 0;
    $("#factTable > tbody > tr").each(function() {
        total += parseInt($(this).children().eq(4).find("span").text());
    });
    $("#factTotal").text(total);
}

function deleteProduct(object)
{
    if(confirm("¿Está seguro de querer eliminar el producto seleccionado?"))
    {
        $(object).closest('tr').remove();
        $("#successMsg").text("Producto eliminado de la lista.");
        $("#toastSuccess").toast("show");
    }
}

function GenerateBill()
{
    if ($("#factTotal").text() != "0")
    {
        if ($("#clientNameText").find("p").text() != "")
        {
            if (confirm("¿Esta seguro de generar la factura por un total de $" + $("#factTotal").text() + "?"))
            {
                $(".loading").css("display", "block");
                var product = [],
                    productAmount = [],
                    pricePerUnit = [];

                $("#factTable > tbody > tr").each(function() {
                    product.push($(this).children().eq(1).text());
                    productAmount.push(parseInt($(this).children().eq(2).text()));
                    pricePerUnit.push(parseInt($(this).children().eq(3).find("span").text()));
                });
                
                $.post("scripts/PHP/bills/submitBill.php", { product: JSON.stringify(product), productAmount: JSON.stringify(productAmount), pricePerUnit: JSON.stringify(pricePerUnit), type: "Factura", cliName: $("#cliNameText").text(), cliID: $("#cliIDText").text() } )
                .done(function (r) {
                    $(".loading").css("display", "none");
                    if (r.success)
                    {                        
                        $("#productName").val("");
                        $("#total").find("span").text("");
                        $("#stockAmount").find("input").attr({"max" : 0, "min" : 0}).val("0");
                        $("#productName").focus();
                        $("#cantText").css("display", "none");
                        $("#stockAmount").css("display", "none").find("input").attr({"max" : 0, "min" : 0}).val("0");
                        $("#perUnitPrice").css("display", "none").find("span").text("-");
                        $("#maxStock").css("display", "none").find("span").text("-");
                        $("#total").css("display", "none").find("span").text("-");
                        $("#addButton").css("display", "none");
                        $("#divider").toggleClass("mt-3", false);
                        $("#clientNameText").find("p").text("");
                        $("#factN").text(r.data.actualFact);
                        
                        $("#factTable > tbody > tr").each(function() {
                            $(this).remove();
                        });
                        $("#factTotal").text("0");
                        
                        alert("Factura generada correctamente. Puede visualizarla desde el listado de facturas.");
                    }
                    else
                    {
                        $("#dangerMsg").text(r.message);
                        $("#toastDanger").toast("show");
                    }
                })
                .fail( function(xhr, textStatus, errorThrown) {
                    $(".loading").css("display", "none");
                    $("#dangerMsg").text("Error al recibir datos.");
                    $("#toastDanger").toast("show");
                });
            } 
        }
        else
        {
            $("#dangerMsg").text("Debe seleccionar un cliente.");
            $("#toastDanger").toast("show");
        }
    }
    else
    {
        $("#dangerMsg").text("No puede facturar en 0.");
        $("#toastDanger").toast("show");
    }
}

function RefundBill(object)
{
    if (confirm("¿Esta seguro de anular la factura seleccionada?"))
    {
        $(".loading").css("display", "block");
        $.post("scripts/PHP/bills/submitBill.php", { type: "Devolucion", factNum: $(object).closest('tr').attr('id') } )
        .done(function (r) {
            $(".loading").css("display", "none");
            if (r.success)
            {
                $(object).closest('tr').addClass("bg-danger text-white").find('td').eq(2).text("Anulada");
                $(object).closest('td').text("-");
                $("#successMsg").text("Factura anulada.");
                $("#toastSuccess").toast("show");
            }
            else
            {
                $("#dangerMsg").text(r.message);
                $("#toastDanger").toast("show");
            }
        })
        .fail( function(xhr, textStatus, errorThrown) {
            $(".loading").css("display", "none");
            $("#dangerMsg").text("Error al recibir datos.");
            $("#toastDanger").toast("show");
        });
    }
}

function BillDetails(object)
{
    $("#billDetailsTable > tbody > tr").each(function() { $(this).remove(); });
    $.post("scripts/PHP/bills/billDetails.php", { factNum: $(object).closest('tr').attr('id') } )
    .done(function (r) {
        $(".loading").css("display", "none");
        if (r.success)
        {
            var billDetails = r.data.details;
            var products = billDetails.split(";");
            var splitAgain;
            
            for (var i = 0; i < products.length -1; i++)
            {
                splitAgain = products[i].split("|");
                $('#billDetailsTable > tbody:last-child').append("<tr><td class='align-middle'>" +  splitAgain[0] + "</td><td class='align-middle'>" + parseInt(splitAgain[1]) + "</td><td class='align-middle'>$" + parseInt(splitAgain[2]) + "</td><td class='align-middle'>$" + (parseInt(splitAgain[1]) * parseInt(splitAgain[2])) + "</td>");
            }

            $("#billDetailsModal").modal("show");
            
            $("#successMsg").text(r.message);
            $("#toastSuccess").toast("show");
        }
        else
        {
            $("#dangerMsg").text(r.message);
            $("#toastDanger").toast("show");
        }
    })
    .fail( function(xhr, textStatus, errorThrown) {
        $(".loading").css("display", "none");
        $("#dangerMsg").text("Error al recibir datos.");
        $("#toastDanger").toast("show");
    });
}

function DismissBillDetails()
{
    $("#billDetailsTable > tbody > tr").each(function() { $(this).remove(); });
}