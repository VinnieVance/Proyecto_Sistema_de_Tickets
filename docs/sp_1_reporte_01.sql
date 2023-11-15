CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_l_reporte_01`()
BEGIN
	SELECT
	tick.tick_id as id,
	tick.tick_titulo as Titulo,
	tick.tick_descrip as Descripcion,
	tick.tick_estado as Estado,
	tick.fech_crea as FechaCreacion,
	tick.fech_cierre as FechaCierre,
	tick.fech_asig as FechaAsignacion,
	CONCAT(usucrea.usu_nom,' ',usucrea.usu_ape) as NombreUsuario,
	IFNULL(CONCAT(usuasig.usu_nom,' ',usuasig.usu_ape), 'SinAsignar') as NombreSoporte,
	cat.cat_nom as Categoria,
	prio.prio_nom as Prioridad,
	sub.cats_nom as SubCategoria
	FROM 
	tm_ticket tick
	INNER join tm_categoria cat on tick.cat_id = cat.cat_id
	INNER join tm_subcategoria sub on tick.cats_id = sub.cats_id
	INNER join tm_usuario usucrea on tick.usu_id = usucrea.usu_id
	LEFT join tm_usuario usuasig on tick.usu_asig = usuasig.usu_id
	INNER join tm_prioridad prio on tick.prio_id = prio.prio_id
	WHERE
	tick.est = 1;
END