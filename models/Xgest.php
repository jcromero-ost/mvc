<?php
require_once __DIR__ . '/../config/config_xgest.php';

class Xgest
{
    public static function getClientes()
    {
        global $pdo_xgest;
        $stmt = $pdo_xgest->query("
            SELECT 
                CCODCL AS codigo,
                CNOM AS nombre,
                CMAIL1 AS email,
                CTEL1 AS telefono,
                CDOM AS direccion
            FROM fccli001
            ORDER BY CNOM ASC
        ");
        return $stmt->fetchAll();
    }

    public static function getArticulo($codigo)
    {
        global $pdo_xgest;
        $sql = "SELECT 
                    ACODAR AS codigo,
                    ADESCR AS descripcion,
                    APVP1 AS precio,
                    AFAMILIA AS familia
                FROM fcart001
                WHERE ACODAR = ?";
        $stmt = $pdo_xgest->prepare($sql);
        $stmt->execute([$codigo]);
        return $stmt->fetch();
    }

    public static function obtenerLineasAppTicket()
    {
        global $pdo_xgest;
        $sql = "SELECT * FROM fcloc001 WHERE LCODAR = 'APP-TICKET'";
        $stmt = $pdo_xgest->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getArticulos()
    {
        global $pdo_xgest;
        $stmt = $pdo_xgest->query("
            SELECT 
                ACODAR AS codigo,
                ADESCR AS descripcion,
                APVP1 AS precio,
                AFAMILIA AS familia
            FROM fcart001
            ORDER BY ADESCR ASC
        ");
        return $stmt->fetchAll();
    }

    public static function obtenerTodosLosPedidosConLineas()
    {
        global $pdo_xgest;

        // Paso 1: Obtener todas las cabeceras
        $sqlCabeceras = "SELECT BOFE, BPED, BCODCL, BFECOFE, BUSUARIO, BTOBRU FROM fccoc001 ORDER BY BOFE DESC";
        $stmtCab = $pdo_xgest->query($sqlCabeceras);
        $cabeceras = $stmtCab->fetchAll(PDO::FETCH_ASSOC);

        $pedidosFinal = [];

        // Paso 2: Por cada pedido, buscar sus líneas
        foreach ($cabeceras as $cab) {
            $boFe = $cab['BOFE'];

            $sqlLineas = "SELECT LLINEA, LCODAR, LAMPDES, LCANTI, LIMPOR FROM fcloc001 WHERE LOFE = ?";
            $stmtLineas = $pdo_xgest->prepare($sqlLineas);
            $stmtLineas->execute([$boFe]);
            $lineas = $stmtLineas->fetchAll(PDO::FETCH_ASSOC);

            $pedidosFinal[] = [
                'NºOferta'     => $cab['BOFE'],
                'pedido'        => $cab['BPED'],
                'cliente_id'    => $cab['BCODCL'],
                'fecha'         => $cab['BFECOFE'],
                'usuario'       => $cab['BUSUARIO'],
                'importe_total' => $cab['BTOBRU'],
                'lineas'        => $lineas
            ];
        }

        return $pedidosFinal;
    }


    public static function insertarPedidoConLineasAvanzado($datos)
    {
        global $pdo_xgest;

        $emp = "001";
        $cli = "fccli" . $emp;
        $coc = "fccoc" . $emp;
        $loc = "fcloc" . $emp;

        try {
            $cliente = (int)$datos['cliente_id'];
            $textoped = $datos['texto'] ?? '';
            $almacen = (int)($datos['almacen'] ?? 1);
            $lineas = $datos['lineas'] ?? [];

            // 1. Cargar datos del cliente
            $stmt = $pdo_xgest->prepare("SELECT cforpa, crepre, crecargo, cexencargo FROM $cli WHERE ccodcl = ? LIMIT 1");
            $stmt->execute([$cliente]);
            $cliRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$cliRow) throw new Exception("Cliente no encontrado");

            $forpago = (int)$cliRow['cforpa'];
            $repre = (int)$cliRow['crepre'];
            $recargo = (int)$cliRow['crecargo'];

            // 2. Generar numeración
            $serie = 0;
            $ano = date('Y');
            $primerDoc = ($serie * 10000000000) + ($ano * 1000000) + 1;
            $ultimoDoc = $primerDoc + 999998;

            $pdo_xgest->exec("LOCK TABLES $coc WRITE");

            $numofe = $primerDoc;
            $numped = $primerDoc;

            $stmt = $pdo_xgest->query("SELECT MAX(bofe) AS max FROM $coc WHERE bofe BETWEEN $primerDoc AND $ultimoDoc");
            if ($row = $stmt->fetch()) {
                $numofe = max($numofe, $row['max'] + 1);
            }

            $stmt = $pdo_xgest->query("SELECT MAX(bped) AS max FROM $coc WHERE bped BETWEEN $primerDoc AND $ultimoDoc");
            if ($row = $stmt->fetch()) {
                $numped = max($numped, $row['max'] + 1);
            }

            // 3. Insertar cabecera
            $stmt = $pdo_xgest->prepare("INSERT INTO $coc (bofe, bped, bcodcl, bcentro) VALUES (?, ?, ?, 0)");
            $stmt->execute([$numofe, $numped, $cliente]);

            $pdo_xgest->exec("UNLOCK TABLES");

            // 4. Actualizar cabecera
            $hora = date('H:i');
            $stmt = $pdo_xgest->prepare("
                UPDATE $coc SET
                    bplazo = CURDATE(),
                    besped = 'S',
                    bfecofe = CURDATE(),
                    bfecped = CURDATE(),
                    bpedid = ?,
                    bobse = '',
                    busuario = 'INTRANET',
                    bhoraofe = ?,
                    bhoraped = ?,
                    bdto = 0,
                    balmacen = ?,
                    bcodrep = ?,
                    bforpa = ?,
                    bagencia = 0,
                    bpeddesweb = 'S',
                    brecargo = ?,
                    bpagadoweb = 'N',
                    bliquid = 'N'
                WHERE bofe = ? AND bped = ?
            ");
            $stmt->execute([
                $textoped, $hora, $hora,
                $almacen, $repre, $forpago, $recargo,
                $numofe, $numped
            ]);

            // 5. Insertar líneas
            $line = 1;
            foreach ($lineas as $l) {
                $codigo = $l['codigo'];
                $cantidad = (float)$l['cantidad'];
                $precio = (float)$l['precio'];
                $dto = (float)($l['descuento'] ?? 0);
                $importe = (float)($l['importe'] ?? ($precio * $cantidad));

                $stmt = $pdo_xgest->prepare("
                    INSERT INTO $loc (
                        lofe, lped, llinea, lfecofe, lfecped,
                        lcodar, lcodcl, lcanti, lpreci, ldto, limpor,
                        lcanser, lcanpen, lliquid, lalmacen,
                        lressn1, lrescar1, lampdes,
                        ltipiva, lporciva, lporcrec, limpconiva
                    ) VALUES (
                        ?, ?, ?, CURDATE(), CURDATE(),
                        ?, ?, ?, ?, ?, ?,
                        0, ?, 'N', ?,
                        'N', NOW(), '',
                        0, 0, 0, 0
                    )
                ");
                $stmt->execute([
                    $numofe, $numped, $line++,
                    $codigo, $cliente, $cantidad, $precio, $dto, $importe,
                    $cantidad, $almacen
                ]);
            }

            return ['success' => true, 'pedido' => $numped];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public static function buscarClientes($filtro)
    {
        global $pdo_xgest;
        $filtro = '%' . $filtro . '%';

        $stmt = $pdo_xgest->prepare("
            SELECT 
                CCODCL AS id,
                CNOM AS nombre,
                CTEL1 AS telefono,
                CDNI AS dni
            FROM fccli001
            WHERE 
                CNOM LIKE ? OR
                CTEL1 LIKE ?
                OR Cdni LIKE ?
            ORDER BY CNOM ASC
            LIMIT 15
        ");
        $stmt->execute([$filtro, $filtro, $filtro]); // Elimina el tercer parámetro si quitas CNIF
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




}
