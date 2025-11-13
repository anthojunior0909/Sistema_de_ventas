<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta #<?= $venta['id'] ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f2f2f2; border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 8px; }
        .total { text-align: right; font-size: 16px; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #777; }
    </style>
</head>
<body>

    <div class="header">
        <h2>MI SISTEMA POS</h2>
        <p>Av. Siempre Viva 123, Puno - Perú</p>
        <h3>Comprobante de Venta #<?= str_pad($venta['id'], 6, '0', STR_PAD_LEFT) ?></h3>
    </div>

    <div class="info">
        <strong>Fecha:</strong> <?= $venta['fecha'] ?><br>
        <strong>Cliente:</strong> <?= esc($venta['cliente'] ?? 'Público General') ?><br>
        <strong>Atendido por:</strong> <?= esc($venta['vendedor']) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant.</th>
                <th>P. Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($detalles as $d): ?>
                <tr>
                    <td><?= esc($d['nombre']) ?> <br><small><?= $d['codigo'] ?></small></td>
                    <td><?= $d['cantidad'] ?></td>
                    <td>S/ <?= number_format($d['precio_unitario'], 2) ?></td>
                    <td>S/ <?= number_format($d['cantidad'] * $d['precio_unitario'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        TOTAL A PAGAR: S/ <?= number_format($venta['total'], 2) ?>
    </div>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Este documento no tiene valor fiscal.</p>
    </div>

</body>
</html>