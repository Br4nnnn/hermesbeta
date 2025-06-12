###  Creación de la tabla de salidas para almacenar el registro de autorizaciones


CREATE TABLE `salidas` (
  `id_salida` INT NOT NULL AUTO_INCREMENT,
  `id_prestamo` INT NOT NULL,
  `id_usuario` INT NOT NULL,
  `fecha_salida` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_salida`));

ALTER TABLE `salidas` 
ADD INDEX `salidas_ibfk_1_idx` (`id_prestamo` ASC) VISIBLE,
ADD INDEX `salidas_ibfk_2_idx` (`id_usuario` ASC) VISIBLE;
;
ALTER TABLE `salidas` 
ADD CONSTRAINT `salidas_ibfk_1`
  FOREIGN KEY (`id_prestamo`)
  REFERENCES `prestamos` (`id_prestamo`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `salidas_ibfk_2`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `usuarios` (`id_usuario`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;




### 1. Agregar trigger a la tabla  `prestamos`
-- Este trigger se ejecutará después de cada actualización en la tabla prestamos.
```sql
DELIMITER //

CREATE TRIGGER actualizar_estado_equipo_por_prestamo
AFTER UPDATE ON prestamos
FOR EACH ROW
BEGIN
    
    IF OLD.estado_prestamo = 'Autorizado' AND NEW.estado_prestamo = 'Prestado' THEN
        
        UPDATE equipos e
        JOIN detalle_prestamo dp ON e.equipo_id = dp.equipo_id
        SET e.id_estado = 2
        WHERE dp.id_prestamo = NEW.id_prestamo
          AND e.id_estado = 3; 
    END IF;
END//

DELIMITER ;

```
