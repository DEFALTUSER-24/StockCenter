<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/main/sysFunctions.php";
    $user = $_SESSION['uName'];
    $access = $_SESSION['uAccess'];

    (new SysFunct())->ValidateUser($_SESSION['uName']);
?>

<html lang="es">
	<head>
        <title>Menú principal</title>
        <link rel="icon" type="image/png" href="img/icon.ico"/>
        
        <!-- METATAGS -->
		<meta charset='utf-8'/>   
        <meta name="robots" content="NOINDEX,NOFOLLOW,NOARCHIVE,NOSNIPPET,NOODP,NOTRANSLATE,NOIMAGEINDEX,NOYDIR">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ezequiel Castro">

        <!-- BOOTSTRAP 4 & ANIMATE -->
        <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="css/animate.min.css">
        
        <!-- CUSTOM STYLESHEET -->
		<link rel="stylesheet" href="css/styles.css" />
        
        <!-- FONTAWESOME -->
        <link rel="stylesheet" href="css/fontawesome/css/all.css">
	</head>

	<body>
        <!-- LOADING BACKGROUND -->
        <div class="loading" style="display: none;">
            <div class="loader"></div>
        </div>
       
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top animated slideInDown">
            <h1 class="navbar-brand align-middle mb-0" title="Nombre de usuario"><?php echo $user; ?></h1>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse text-center" id="navbar">
                <ul class="navbar-nav">
                    <li class="nav-item <?php if(isset($_GET['stock'])) echo 'active'; ?>">
                        <a class="nav-link font-weight-bold" href="system.php?stock&limit=10" title="Acceder al listado de productos">Stock</a>
                    </li>
                    
                    <?php if($access === "ADMIN" || $access === "EMPLEADO") : ?>
                    <li class="nav-item <?php if(isset($_GET['fact'])) echo 'active'; ?>">
                        <a class="nav-link font-weight-bold" href="system.php?fact" title="Generar factura para un cliente">Generar factura</a>
                    </li>
                    <?php endif ?>
                    
                    <?php if($access === "ADMIN" || $access === "EMPLEADO") : ?>
                    <li class="nav-item <?php if(isset($_GET['factList'])) echo 'active'; ?>">
                        <a class="nav-link font-weight-bold" href="system.php?factList&limit=10" title="Ver listado de facturas generadas">Ver facturas</a>
                    </li>
                    <?php endif ?>
                    
                    <?php if($access === "ADMIN") : ?>
                    <li class="nav-item <?php if(isset($_GET['users'])) echo 'active'; ?>">
                        <a class="nav-link font-weight-bold" href="system.php?users" title="Acceder al listado de usuarios">Usuarios</a>
                    </li>
                    <?php endif ?>
                    
                    <?php if($access === "ADMIN") : ?>
                    <li class="nav-item <?php if(isset($_GET['logs'])) echo 'active'; ?>">
                        <a class="nav-link font-weight-bold" href="system.php?logs&limit=10" title="Acceder al log del sistema">Logs</a>
                    </li>
                    <?php endif ?>
                </ul>
                
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-md-3 mb-2 mb-md-0">
                        <button type="button" class="btn btn-outline-info" title="Ver mis datos" onclick="showMyself();">Ver mis datos</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-outline-danger" title="Cerrar sesión" onclick="window.location.href = 'index.php';">Cerrar sesión</button>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- USER INFO MODAL -->
        <div class="modal fade" tabindex="-1" id="userInfo" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Información del usuario</h5>
                    </div>
                    <div class="modal-body">
                        <input id="oldUser" type="text" class="d-none" value="<?=$user; ?>" readonly>
                        <label>Usuario:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-dark" type="button" onclick="$('#nData').focus();" tabindex="-1">
                                    <i class="fas fa-user"></i>
                                </button>
                            </div>
                            <input id="nData" type="text" class="form-control" maxlength="255"  placeholder="Ingrese su nombre de usuario">
                        </div>

                        <label>Clave:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-dark" type="button" onclick="$('#pData').focus();" tabindex="-1">
                                    <i class="fas fa-key"></i>
                                </button>
                            </div>
                            <input id="pData" type="text" class="form-control" maxlength="255"  placeholder="Ingrese su clave">
                        </div>
                        
                        <label>Nivel de usuario:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-dark" type="button" tabindex="-1">
                                    <i class="fas fa-user-lock"></i>
                                </button>
                            </div>
                            <input id="uData" type="text" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success mr-auto" onclick="updateMyself();"><i class="far fa-check-circle"></i> Actualizar</button>
                        <button type="button" class="btn btn-danger" onclick="dismissMyData();"><i class="fas fa-times-circle"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOAST - SUCCESS -->
        <div aria-live="polite" aria-atomic="true">
            <div style="position: fixed; bottom: 3rem; left: 50;">
                <div class="toast text-white" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" id="toastSuccess">
                    <div class="toast-header bg-success text-white" style="border-bottom: 0px!important;" id="toast">
                        <strong class="mr-auto">Aviso</strong>
                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="toast-body bg-dark" id="successMsg"></div>
                </div>
            </div>
        </div>

        <!-- TOAST - DANGER -->
        <div aria-live="polite" aria-atomic="true">
            <div style="position: fixed; bottom: 3rem; left: 50;">
                <div class="toast text-white" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" id="toastDanger">
                    <div class="toast-header bg-danger text-white" style="border-bottom: 0px!important;" id="toast">
                        <strong class="mr-auto">Aviso</strong>
                        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="toast-body bg-dark" id="dangerMsg"></div>
                </div>
            </div>
        </div>
        
        <!-- SYSTEM MAIN PAGE -->
        <main class="mt-fix container-fluid">
            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script src="scripts/boostrap/bootstrap.bundle.min.js"></script>
        <?php switch($_GET): case isset($_GET['stock']): ?>
            <link rel="stylesheet" href="css/fancybox.css" />
            <script src="scripts/JS/fancybox.js"></script>
            
            <!-- MODAL PARA AGREGAR PRODUCTO -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInfo" aria-hidden="true" id="stockAddModal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" autocomplete="off" action="scripts/PHP/stock/stockAdd.php" id="addProductForm" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar producto</h5>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Nombre</span>
                                    </div>
                                    <input type="text" name="desc" class="form-control" placeholder="Insertar nombre del producto" maxlength="255" id="prodName" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Código</span>
                                    </div>
                                    <input type="text" name="code" class="form-control" placeholder="Insertar código de producto" maxlength="255" id="codeID" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Unidades</span>
                                    </div>
                                    <input type="number" name="units" class="form-control" placeholder="Insertar unidades en stock" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="5" id="stockUnits" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Precio</span>
                                    </div>
                                    <input type="number" name="precio" class="form-control" placeholder="Insertar precio" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="5" required>
                                </div>
                                <div class="form-group">
                                    <label for="picture" id="picturesLabel">Foto:</label>
                                    <input type="file" class="form-control-file" name="picture" id="picture" lang="es">
                                </div>
                                <div class="progress" style="display:none;" id="uploadBar">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <input type="reset" class="d-none" id="stockAddReset">
                                <div class="alert alert-danger fade show mt-3" style="display:none;" id="stockAddResponse">
                                </div>
                            </div>
                            <div class="modal-footer" id="stockAddFooter">
                                <button type="submit" name="addProduct" class="btn btn-primary mr-auto" id="addProductBtn"><i class="far fa-check-circle"></i> Agregar producto</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- MODAL PARA MODIFICAR PRODUCTO -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInfo" aria-hidden="true" id="stockEditModal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" autocomplete="off" action="scripts/PHP/stock/stockModify.php" id="modifyProductForm" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar producto "<span id="codeToEdit"></span>"</h5>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nombre</span>
                                    </div>
                                    <input type="text" name="desc" class="form-control" placeholder="Insertar nombre del producto" maxlength="255" id="stockEditName" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Unidades</span>
                                    </div>
                                    <input type="number" name="units" class="form-control" placeholder="Insertar unidades en stock" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="stockEditUnits" maxlength="5" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Precio</span>
                                    </div>
                                    <input type="number" name="precio" class="form-control" placeholder="Insertar precio" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="stockEditPrice" maxlength="5" required>
                                </div>
                                <div class="progress" style="display:none;" id="uploadBarEdit">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <input type="text" name="code" class="d-none" maxlength="10" id="codeIDEdit" readonly required>
                                <input type="reset" class="d-none" id="stockEditReset">
                                <div class="alert alert-danger fade show" style="display:none;" id="stockEditResponse">
                                </div>
                            </div>
                            <div class="modal-footer" id="stockEditFooter">
                                <button type="submit" name="submit" class="btn btn-primary mr-auto"><i class="far fa-check-circle"></i> Editar producto</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-8 col-md-10 col-lg-10 pr-0">
                    <h1 id="arriba" class="titulostock">Lista de Stock</h1>
                </div>
                <div class="col-4 col-md-2 col-lg-2 text-right pl-0">
                    <div class="dropdown mt-2">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opciones</button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#stockAddModal">Agregar producto</a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="divisorstock">
            
            <?php 
                include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/stock/stockFunctions.php";
                $stockLimit = 0;
                if (isset($_GET['limit'])) { $stockLimit = intval($_GET['limit']); }
                $result = (new StockManager())->DeployList($stockLimit);
                if ($result->num_rows == 0):
            ?>
            <h1 class="text-center" style="font-size: 25px; color:red;">No hay productos agregados, para agregarlos haga click en Opciones -> Agregar producto.</h1>
            <?php elseif ($result->num_rows > 0): ?>
            <div class="container py-3 bg-dark">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-success" type="submit">Buscar</button>
                    </div>
                    <input type="text" id="searchBox" class="form-control" placeholder="Busqueda por nombre, codigo, unidades y precio" onkeydown="removeEveryClass()" autofocus>
                </div>
            </div>
            <div class="my-2 text-center d-flex justify-content-center">
                <div class="btn-toolbar d-flex justify-content-center p-2 bg-dark">
                    <div class="btn-group btn-group-sm text-white text-center" role="group" aria-label="Limite de items">
                        <a href="system.php?stock&limit=10" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 10) { echo 'active'; } }?>">10</a>
                        <a href="system.php?stock&limit=50" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 50) { echo 'active'; } }?>">50</a>
                        <a href="system.php?stock&limit=100" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 100) { echo 'active'; } }?>">100</a>
                        <a href="system.php?stock" class="btn btn-secondary <?php if(!isset($_GET['limit'])) echo 'active'; ?>">Sin limite</a>
                    </div>
                </div>
            </div>
            
            <table class="table table-bordered table-hover mt-3" id="stockTable">
                <thead class="thead-dark">
                    <tr>
                       <th class="d-none d-md-table-cell">Foto</th>
                       <th onclick="sortTable(1)" style="cursor: pointer;">Descripción</th>
                       <th class="d-none d-xl-table-cell" onclick="sortTable(2)" style="cursor: pointer;">Código</th>
                       <th onclick="sortTable(3)" style="cursor: pointer;">Stock</th>
                       <th onclick="sortTable(4)" style="cursor: pointer;">Precio /u</th>
                       <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr id="<?=$row['PCODE']; ?>">
                        <td class="d-none d-md-table-cell text-center">
                            <?php if (strpos($row["PIC"], 'no_image.png') !== false): ?>
                                <img src="<?=$row['PIC']; ?>" class="img-thumbnail" width="60" height="60">
                            <?php else: ?>
                            <a data-fancybox="gallery" href="<?=$row['PIC']; ?>">
                                <img src="<?=$row['PIC']; ?>" class="img-thumbnail" width="60" height="60">
                            </a>
                            <?php endif ?>
                        </td>
                        <td class="align-middle"><?=$row['PNAME']; ?></td>
                        <td class="d-none d-xl-table-cell align-middle"><?=$row['PCODE']; ?></td>
                        <td class="align-middle"><?=$row['PUNITS']; ?></td>
                        <td class="align-middle">$ <?=$row['PRICE']; ?></td>
                        <td class="align-middle text-center">
                            <button class="btn btn-danger btn-sm ml-1" title="Eliminar producto" type="button" onclick="deleteProduct(this)"><i class="fas fa-trash-alt"></i> <small class="d-none d-md-inline">Eliminar</small></button>
                            <button class="btn btn-info btn-sm" title="Editar producto" type="button" onclick="modifyProduct(this)"><i class="far fa-edit"></i> <small class="d-none d-md-inline">Editar</small></button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php endif ?>
            <script src="scripts/JS/stock.js"></script>
        <?php break; ?>
        <?php case isset($_GET['users']): ?>
            <?php if($access === "ADMIN"): ?>
            
            <!-- MODAL PARA AGREGAR USUARIO -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInfo" aria-hidden="true" id="userAddModal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" autocomplete="off" action="scripts/PHP/users/userCreate.php" id="addUserForm" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar usuario</h5>
                            </div>
                            <div class="modal-body">
                                <label>Usuario:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-dark" type="button" onclick="$('#newuName').focus();" tabindex="-1">
                                            <i class="fas fa-user"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="uName" class="form-control" placeholder="Insertar nombre de usuario" maxlength="255" id="newuName" required>
                                </div>

                                <label>Clave:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-dark" type="button" onclick="$('#newuPass').focus();" tabindex="-1">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="uPassword" class="form-control" placeholder="Insertar clave de acceso" maxlength="255" id="newuPass" required>
                                </div>

                                <label>Nivel de usuario:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-dark" type="button" onclick="$('#newuAccess').focus();" tabindex="-1">
                                            <i class="fas fa-user-lock"></i>
                                        </button>
                                    </div>
                                    <select class="custom-select" name="uAccess" id="newuAccess">
                                        <option value="MANAGER" selected>MANAGER</option>
                                        <option value="EMPLEADO">EMPLEADO</option>
                                        <option value="ADMIN">ADMIN</option>
                                    </select>
                                </div>
                                
                                <div class="alert alert-info mt-3" role="alert">
                                    <h4 class="alert-heading">Categorias de usuario:</h4><hr>
                                    <ul>
                                        <li>MANAGER: Puede eliminar y agregar stock.</li>
                                        <li>EMPLEADO: MANAGER + Generar y ver lista de facturas.</li>
                                        <li>ADMIN: EMPLEADO + Puede agregar, eliminar y modificar usuarios y puede ver los logs del sistema.</li>
                                    </ul>
                                </div>
                                <div class="progress" style="display:none;" id="uploadBarUser">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <input type="reset" class="d-none" id="userAddReset">
                                <div class="alert alert-danger fade show mt-3" style="display:none;" id="userAddResponse">
                                </div>
                            </div>
                            <div class="modal-footer" id="userAddFooter">
                                <button type="submit" class="btn btn-primary mr-auto"><i class="far fa-check-circle"></i> Agregar usuario</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- MODAL PARA MODIFICAR USUARIO -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInfo" aria-hidden="true" id="userUpdateModal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="post" autocomplete="off" action="scripts/PHP/users/updateData.php" id="updateUserForm" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title">Modificar usuario <span id="pastUsername">-</span></h5>
                            </div>
                            <div class="modal-body">
                                <label>Usuario:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-dark" type="button" onclick="$('#updateduName').focus();" tabindex="-1">
                                            <i class="fas fa-user"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="name" class="form-control" placeholder="Insertar nombre de usuario" maxlength="255" id="updateduName" required>
                                </div>

                                <label>Clave:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-dark" type="button" onclick="$('#updateduPass').focus();" tabindex="-1">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="pass" class="form-control" placeholder="Insertar clave de acceso" maxlength="255" id="updateduPass" required>
                                </div>

                                <label>Nivel de usuario:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-dark" type="button" onclick="$('#updateduAccess').focus();" tabindex="-1">
                                            <i class="fas fa-user-lock"></i>
                                        </button>
                                    </div>
                                    <select class="custom-select" name="nAccess" id="updateduAccess">
                                        <option value="MANAGER" selected>MANAGER</option>
                                        <option value="EMPLEADO">EMPLEADO</option>
                                        <option value="ADMIN">ADMIN</option>
                                    </select>
                                </div>
                                
                                <div class="alert alert-info mt-3" role="alert">
                                    <h4 class="alert-heading">Categorias de usuario:</h4><hr>
                                    <ul>
                                        <li>MANAGER: Puede eliminar y agregar stock.</li>
                                        <li>EMPLEADO: MANAGER + Generar y ver lista de facturas.</li>
                                        <li>ADMIN: EMPLEADO + Puede agregar, eliminar y modificar usuarios y puede ver los logs del sistema.</li>
                                    </ul>
                                </div>
                                <div class="progress" style="display:none;" id="uploadBarUserUpdate">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <input type="text" name="old" class="d-none" maxlength="255" id="userNameEdit" readonly required>
                                <input type="reset" class="d-none" id="userUpdateReset">
                                <div class="alert alert-danger fade show mt-3" style="display:none;" id="userUpdateResponse">
                                </div>
                            </div>
                            <div class="modal-footer" id="userUpdateFooter">
                                <button type="submit" class="btn btn-primary mr-auto"><i class="far fa-check-circle"></i> Modificar</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-8 col-md-10 col-lg-10 pr-0">
                    <h1 id="arriba" class="titulouser">Lista de Usuarios</h1>
                </div>
                <div class="col-4 col-md-2 col-lg-2 text-right pl-0 mt-2">
                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#userAddModal">Agregar usuario</button>
                </div>
            </div>
            <hr class="divisoruser">
            
            <?php 
                include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/users/usersFunctions.php";
                $result = (new UsersFunctions())->DeployList();
            ?>            
            <table class="table table-bordered table-hover mt-3" id="userTable">
                <thead class="thead-dark">
                    <tr>
                       <th>Nombre</th>
                       <th>Clave</th>
                       <th>Tipo</th>
                       <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr id="<?=$row['NAME']; ?>">
                        <td class="align-middle text-center"><?=$row['NAME']; ?></td>
                        <td class="align-middle text-center"><?=openssl_decrypt($row['PASSWORD'], "AES-128-ECB", ENCRYPT_KEY); ?></td>
                        <td class="align-middle text-center"><?=$row['ACCESSTYPE']; ?></td>
                        <td class="align-middle text-center">
                            <button class="btn btn-danger btn-sm ml-1" title="Eliminar usuario" type="button" onclick="removeUser(this)"><i class="fas fa-trash-alt"></i> <small class="d-none d-md-inline">Eliminar</small></button>
                            <button class="btn btn-info btn-sm" title="Editar usuario" type="button" onclick="modifyUser(this)"><i class="far fa-edit"></i> <small class="d-none d-md-inline">Editar</small></button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php else: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:red;">Acceso denegado.</h1>
            <script>setTimeout(function() { window.location.href = "system.php"; }, 3000);</script>
            <?php endif ?>
        <?php break; ?>
        <?php case isset($_GET['fact']): ?>
            <?php if($access === "ADMIN" || $access === "EMPLEADO") : ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/bills/billsFunctions.php";
                $factManager = new bills();
            
                if ($factManager->CheckProducts()->num_rows > 0):
                    $numFact = $factManager->ActualBill();
            ?>
            
            <!-- MODAL PARA AGREGAR UN NUEVO CLIENTE -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInfo" aria-hidden="true" id="clientAddModal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Agregar cliente</h5>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" role="alert">No se encontró al cliente en el sistema, debe agregarlo.</div>
                            <label>Usuario:</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-dark" type="button" onclick="$('#newCliName').focus();" tabindex="-1">
                                        <i class="fas fa-user"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control" placeholder="Ingresar nombre del cliente" maxlength="255" id="newCliName">
                            </div>

                            <label>DNI/CUIT:</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-dark" type="button" onclick="$('#newCliID').focus();" tabindex="-1"><i class="far fa-id-card"></i></button>
                                </div>
                                <input type="text" class="form-control" placeholder="Ingresar DNI/CUIT" maxlength="11" id="newCliID">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary mr-auto" onclick="AddNewClient();"><i class="far fa-check-circle"></i> Agregar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 pr-0">
                    <h2 class="titulofact">Factura Nº <span id="factN"><?=++$numFact;?></span></h2>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 text-md-right">
                    <h2>Fecha: <span id="factDate"><?=date("d/m/Y");?></span></h2>
                </div>
            </div>
            <hr>
            <form method="post" autocomplete="off" id="createBill" enctype="multipart/form-data">
                <div class="container">
                    <div class="form-group row">
                        <div class="col-12 col-sm-3 col-md-2 col-lg-1">
                            <p class="mb-1 mb-md-0 mt-2 text-md-right">Cliente:</p>
                        </div>
                        <div class="col-12 col-sm-9 col-md-9 col-lg-6">
                            <input type="text" class="form-control" maxlength="255" id="clientName" list="client_list" placeholder="Buscar por nombre o por DNI/CUIT">
                            <datalist id="client_list">
                            </datalist>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-5" id="clientNameText" style="display: none;">
                            <p class="mb-0 mt-2 text-center text-lg-left"><span id="cliIDText"></span></p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-12 col-sm-3 col-md-2 col-lg-2">
                        <p class="mb-0 mt-2 text-left text-sm-right">Producto:</p>
                    </div>
                    <div class="col-12 col-sm-8 col-md-5 col-lg-5">
                        <input type="text" class="form-control" maxlength="255" id="productName" list="product_list" placeholder="Buscar por nombre o por codigo de producto">
                        <datalist id="product_list">
                        </datalist>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 text-center text-sm-right" id="maxStock" style="display: none;">
                        <p class="mb-0 mt-2">En stock: <span>-</span></p>
                    </div>
                    
                    <!-- Force next columns to break to new line at md breakpoint and up -->
                    <div class="w-100 d-none d-sm-none d-md-block" id="divider"></div>
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 text-center text-sm-left text-md-right" id="perUnitPrice" style="display: none;">
                        <p class="mb-0 mt-2">Precio /u: $<span>-</span></p>
                    </div>
                    <div class="col-12 col-sm-3 col-md-2 col-lg-2 mt-2 mt-md-0 p-sm-0 text-left text-sm-right" id="cantText" style="display: none;">
                        <p class="mb-0 mt-2">Cantidad:</p>
                    </div>
                    <div class="col-12 col-sm-3 col-md-2 col-lg-2 mt-2 mt-md-0" id="stockAmount" style="display: none;">
                        <input type="number" min="0" max="0" class="form-control" id="stockAmountInput">
                    </div>
                    <div class="col-12 col-sm-6 col-md-2 col-lg-2 mt-2 mt-md-0 text-center text-md-left text-md-right" id="total" style="display: none;">
                        <p class="mb-0 mt-2">Total: $<span>-</span></p>
                    </div>
                    <div class="col-12 col-sm-8 offset-sm-2 col-md-3 offset-md-0 col-lg-2 mt-2 mt-md-0 text-center" id="addButton" style="display: none;">
                        <button type="button" class="btn btn-block btn-primary mr-auto" onclick="AddToList()"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </div>
                <hr>
                <h3>Productos en la factura:</h3>
                <table class="table table-bordered table-hover mt-3" id="factTable">
                    <thead class="thead-dark">
                        <tr>
                            <th></th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio /u</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
                <hr>
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <h1 class="text-center text-md-left">Total final: $<span class="text-bold" id="factTotal">0</span></h1>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 text-center text-md-right">
                            <button type="button" class="btn btn-success btn-lg" onclick="GenerateBill();"><i class="fas fa-print"></i> Facturar</button>
                        </div>
                    </div>
                </div>
            </form>
            <script src="scripts/JS/bills.js"></script>
            <?php else: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:red;">No hay productos agregados.</h1>
            <script>setTimeout(function() { window.location.href = "system.php?stock&limit=10"; }, 3000);</script>
            <?php endif ?>
            <?php else: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:red;">Acceso denegado.</h1>
            <script>setTimeout(function() { window.location.href = "system.php?stock&limit=10"; }, 3000);</script>
            <?php endif ?>
        <?php break; ?>
        <?php case isset($_GET['factList']): ?>
            <?php if($access === "ADMIN" || $access === "EMPLEADO") : ?>
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/scripts/PHP/bills/billsFunctions.php";
                $factManager = new bills();
                $stockLimit = 0;
                if (isset($_GET['limit'])) { $stockLimit = intval($_GET['limit']); }
                $result = $factManager->DeployList($stockLimit);
                if ($result->num_rows > 0):
            ?>
            <!-- MODAL PARA VER DETALLE DE FACTURA -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInfo" aria-hidden="true" id="billDetailsModal" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalles de factura</h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-hover mt-3" id="billDetailsTable">
                                <thead class="thead-dark">
                                    <tr>
                                       <th>Producto</th>
                                       <th>Cantidad</th>
                                       <th>Precio /u</th>
                                       <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DismissBillDetails();"><i class="fas fa-times-circle"></i> Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <h1 class="titulofactlist">Lista de facturas</h1>
            
            <hr class="divisorfactlist">
            <div class="container py-3 bg-dark">
                <form autocomplete="off" onsubmit="return searchProduct();" class="align-middle mb-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-success" type="submit">Buscar</button>
                        </div>
                        <input type="text" id="searchBox" class="form-control" placeholder="Busqueda por fecha, nombre de cliente o por usuario" onkeydown="removeEveryClass()" autofocus>
                    </div>
                </form>
            </div>
            <div class="my-2 text-center d-flex justify-content-center">
                <div class="btn-toolbar d-flex justify-content-center p-2 bg-dark">
                    <div class="btn-group btn-group-sm text-white text-center" role="group" aria-label="Limite de items">
                        <a href="system.php?factList&limit=10" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 10) { echo 'active'; } }?>">10</a>
                        <a href="system.php?factList&limit=50" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 50) { echo 'active'; } }?>">50</a>
                        <a href="system.php?factList&limit=100" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 100) { echo 'active'; } }?>">100</a>
                        <a href="system.php?factList" class="btn btn-secondary <?php if(!isset($_GET['limit'])) echo 'active'; ?>">Sin limite</a>
                    </div>
                </div>
            </div>
            
            <table class="table table-bordered table-hover mt-3" id="stockTable">
                <thead class="thead-dark">
                    <tr>
                       <th onclick="sortTable(0)" style="cursor: pointer;">Fecha</th>
                       <th onclick="sortTable(1)" class="d-none d-md-table-cell" style="cursor: pointer;">Nº factura</th>
                       <th onclick="sortTable(2)" class="d-none d-lg-table-cell" style="cursor: pointer;">Tipo</th>
                       <th onclick="sortTable(3)" class="d-none d-md-table-cell" style="cursor: pointer;">Hecha por</th>
                       <th onclick="sortTable(4)" style="cursor: pointer;">Cliente</th>
                       <th>Total</th>
                       <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    
                    $productos = explode(";", $row["billProducts"], -1);
                    $arraySize = count($productos);?>
                    
                    <tr id="<?=$row['billNumber']; ?>" <?php if ($row['billType'] == "Anulada") { echo "class='bg-danger text-white'"; } ?>>
                        <td class="d-none d-md-table-cell text-center align-middle"><?=$row['billDate']; ?></td>
                        <td class="align-middle"><?=$row['billNumber']; ?></td>
                        <td class="d-none d-xl-table-cell align-middle"><?=$row['billType']; ?></td>
                        <td class="align-middle"><?=$row['billMadeBy']; ?></td>
                        <td class="align-middle"><?=$row['billClient']; ?></td>
                        <td class="align-middle">
                        <?php
                        $total = 0;
                        for($x = 0; $x < $arraySize; ++$x) {
                            $splitAgain = explode("|", $productos[$x]);
                            $total += intval($splitAgain[2]) * intval($splitAgain[1]);
                        }
                        echo "$" . $total;
                        ?>
                        </td>
                        <td class="align-middle text-center">
                            <?php if ($row['billType'] == "Anulada"): ?>
                            -
                            <?php else: ?>
                            <button class="btn btn-danger btn-sm ml-1" title="Eliminar producto" type="button" onclick="RefundBill(this)"><i class="fas fa-trash-alt"></i> <small class="d-none d-md-inline">Anular</small></button>
                            <button class="btn btn-info btn-sm ml-1" title="Ver detalle de factura" type="button" onclick="BillDetails(this)"><i class="fas fa-file-invoice"></i> <small class="d-none d-md-inline">Detalle</small></button>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <script src="scripts/JS/bills.js"></script>
            <?php else: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:red;">No hay facturas en la base de datos.</h1>
            <script>setTimeout(function() { window.location.href = "system.php?stock&limit=10"; }, 3000);</script>
            <?php endif ?>
            <?php else: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:red;">Acceso denegado.</h1>
            <script>setTimeout(function() { window.location.href = "system.php?stock&limit=10"; }, 3000);</script>
            <?php endif ?>
        <?php break; ?>
        <?php case isset($_GET['logs']): ?>
            <?php if($access === "ADMIN"): ?>
            <h1 class="titulostock text-center">Logs del sistema</h1>
            <hr class="divisorstock">
            <div class="my-2 text-center d-flex justify-content-center">
                <div class="btn-toolbar d-flex justify-content-center p-2 bg-dark">
                    <div class="btn-group btn-group-sm text-white text-center" role="group" aria-label="Limite de items">
                        <a href="system.php?logs&limit=10" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 10) { echo 'active'; } }?>">10</a>
                        <a href="system.php?logs&limit=50" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 50) { echo 'active'; } }?>">50</a>
                        <a href="system.php?logs&limit=100" class="btn btn-secondary <?php if(isset($_GET['limit'])) { if(intval($_GET['limit']) == 100) { echo 'active'; } }?>">100</a>
                        <a href="system.php?logs" class="btn btn-secondary <?php if(!isset($_GET['limit'])) echo 'active'; ?>">Sin limite</a>
                    </div>
                </div>
            </div>
            
            <?php
                $logsLimit = 0;
                if (isset($_GET['limit'])) { $logsLimit = intval($_GET['limit']); }
                $result = (new SysFunct())->DeployList($logsLimit);
                if ($result->num_rows == 0):
            ?>
            <h1 class="text-center" style="font-size: 25px; color:red;">No hay logs en la base de datos.</h1>
            <?php elseif ($result->num_rows > 0): ?>
            <table class="table table-bordered table-hover mt-3" id="stockTable">
                <thead class="thead-dark">
                    <tr>
                       <th class="text-center">Usuario</th>
                       <th class="text-center">Fecha</th>
                       <th class="text-center">Información</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = array_reverse(mysqli_fetch_array($result))) { ?>
                    <tr>
                        <td class="align-middle text-center"><?=$row['UNAME']; ?></td>
                        <td class="align-middle text-center"><?=$row['ACTIONDATE']; ?></td>
                        <td class="align-middle text-center"><?=$row['ACTIONINFO']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php endif ?>
            <?php else: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:red;">Acceso denegado.</h1>
            <script>setTimeout(function() { window.location.href = "system.php?stock&limit=10"; }, 3000);</script>
            <?php endif ?>
        <?php break; ?>
        <?php default: ?>
            <h1 class="mt-5 text-center" style="font-size: 25px; color:green;">Seleccione una opción de la barra de navegación para operar en el sistema.</h1>
        <?php break; ?>
        <?php endswitch; ?>
        </main>
        <script src="scripts/JS/users.js"></script>
	</body>
</html>