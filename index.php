<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Estado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eee;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* MODAL */
        .modal-estado {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

       .modal-contenido {
    background-color: white;
    padding: 40px 20px 30px;
    border-radius: 12px;
    text-align: center;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 0 30px rgba(0,0,0,0.4);
    position: relative;
    animation: aparecer 0.3s ease-out;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Botón centrado estilo "barra superior" */
.cerrar-btn {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 20px;
    cursor: pointer;
    font-weight: bold;
    font-size: 0.9em;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}


        @keyframes aparecer {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .cerrar-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 0.9em;
        }

        .cerrar-btn:hover {
            background-color: #c82333;
        }

        .mensaje-error {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Buscar Estado de una Persona</h2>
    <div class="card">
        <form method="post">
            <label for="folio">Folio:</label>
            <input type="text" name="folio" required>

            <label for="curp">CURP:</label>
            <input type="text" name="curp" required>

            <button type="submit" name="buscar">Buscar Estado</button>
        </form>
    </div>

    <?php
    if (isset($_POST['buscar'])) {
        $folio = $_POST['folio'];
        $curp = $_POST['curp'];

        $conexion = new mysqli("localhost", "root", "", "celso");

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $stmt = $conexion->prepare("SELECT Estado FROM datos WHERE Folio = ? AND Curp = ?");
        $stmt->bind_param("ss", $folio, $curp);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            $estado = htmlspecialchars($fila['Estado']);
            echo "
            <div class='modal-estado' id='modal'>
                <div class='modal-contenido'>
                    <button class='cerrar-btn' onclick=\"document.getElementById('modal').style.display='none'\">
                        Cerrar
                    </button>
                    <h3>El estado es:</h3>
                    <p style='font-size: 1.3em; font-weight: bold;'>$estado</p>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('modal').style.display = 'flex';
                });
            </script>";
        } else {
            echo "<p class='mensaje-error'>No se encontró ningún registro con ese Folio y CURP.</p>";
        }

        $stmt->close();
        $conexion->close();
    }
    ?>
</div>

</body>
</html>
