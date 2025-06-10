# Scripts SQL para la Base de Datos `hermes002`

Este archivo contiene instrucciones y consultas SQL que deben ejecutarse en la base de datos `hermes002` para realizar actualizaciones o modificaciones específicas.

---

## IMPORTANTE

- Asegúrate de estar conectado a la base de datos `hermes002` antes de ejecutar estas consultas.
- Realiza una copia de seguridad de la base de datos antes de ejecutar cualquier script, por si acaso necesitas revertir los cambios.
- Ejecuta las consultas en el orden en que aparecen en este archivo, si el orden es relevante.

---

## Trigger para la tabla `detalle_prestamo`

Este trigger actualiza el estado del equipo en la tabla `equipos` según el tipo de préstamo (`Inmediato` o `Reservado`) cuando se inserta un nuevo registro en la tabla `detalle_prestamo`.

### Código SQL:

```sql
DELIMITER //

CREATE TRIGGER after_detalle_prestamo_insert
AFTER INSERT ON detalle_prestamo
FOR EACH ROW
BEGIN
    DECLARE v_tipo_prestamo VARCHAR(20);

    SELECT tipo_prestamo INTO v_tipo_prestamo
    FROM prestamos
    WHERE id_prestamo = NEW.id_prestamo;

    IF v_tipo_prestamo = 'Inmediato' THEN
        UPDATE equipos
        SET id_estado = 2
        WHERE equipo_id = NEW.equipo_id;
    ELSEIF v_tipo_prestamo = 'Reservado' THEN
        UPDATE equipos
        SET id_estado = 3
        WHERE equipo_id = NEW.equipo_id;
    END IF;
END;
//

DELIMITER ;
