CREATE DATABASE CalidadSoftware;

USE CalidadSoftware;

CREATE TABLE auditor(
	id_auditor int primary key auto_increment,
	nombre varchar(30),
	ap_pat varchar(20),
	ap_mat varchar(20),
	email varchar(100),
	telefono varchar(10),
	imagen text,
	username varchar(15) unique,
	password varchar(255)
);

CREATE TABLE empresa(
	id_empresa int primary key auto_increment,
	nombre varchar(255),
	RFC varchar(15),
	codigopostal varchar(10),
	email varchar(100),
	telefono varchar(10),
	imagen varchar(255)
);

CREATE TABLE categoriaPregunta(
	id_categoria int primary key auto_increment,
	nombre varchar(255),
	valor int
);

CREATE TABLE subcategoriaPregunta(
	id_subcategoria int primary key auto_increment,
	id_categoria int,
	nombre varchar(255),
	valor decimal(9,2),
	foreign key (id_categoria) REFERENCES categoriaPregunta(id_categoria)
);

CREATE TABLE pregunta(
	id_pregunta int primary key auto_increment,
	id_subcategoria int,
	pregunta text,
	critico boolean,
	foreign key(id_subcategoria) REFERENCES subcategoriaPregunta(id_subcategoria)
);

CREATE TABLE opcionPregunta(
	id_opcion_pregunta int primary key auto_increment,
	nombre varchar(15),
	valor int
);

CREATE TABLE calificacion(
	id_calificacion int primary key auto_increment,
	id_auditor int,
	id_empresa int,
	id_pregunta int,
	id_opcion_pregunta int,
	fecha_calificacion date,
	foreign key(id_auditor) REFERENCES auditor(id_auditor),
	foreign key(id_empresa) REFERENCES empresa(id_empresa),
	foreign key(id_pregunta) REFERENCES pregunta(id_pregunta),
	foreign key(id_opcion_pregunta) REFERENCES opcionPregunta(id_opcion_pregunta)
);

CREATE TABLE resultadoCertamen(
	id_resultado int primary key auto_increment,
	id_auditor int,
	id_empresa int,
	resultado varchar(255),
	observacion text,
	ncmEncontradas varchar(2),
	cumpleCritico boolean,
	fecha_certamen date,
	foreign key(id_auditor) REFERENCES auditor(id_auditor),
	foreign key(id_empresa) REFERENCES empresa(id_empresa)
);

/*INSERCION OPCION PREGUNTA*/
INSERT INTO opcionPregunta values(
	1,
	'Ok',
	10
);
INSERT INTO opcionPregunta values(
	2,
	'Ncm',
	5
);
INSERT INTO opcionPregunta values(
	3,
	'NCM',
	0
);
INSERT INTO opcionPregunta values(
	4,
	'NA',
	0
);
INSERT INTO opcionPregunta values(
	5,
	'CriticoNo',
	0
);
INSERT INTO opcionPregunta values(
	6,
	'CriticoSi',
	1
);



/*INSERCION AUDITOR*/
INSERT INTO auditor values(
	null,
	'Temolzin Itzae',
	'Roldan',
	'Palacios',
	'temolzin@hotmail.com',
	'5535092965',
	'url.com',
	'temolzin',
	'root'
);
INSERT INTO auditor values(
	null,
	'Monserratt',
	'Motaño',
	'Redonda',
	'monse@hotmail.com',
	'5510101010',
	'url.com',
	'monse',
	'root'
);

/*INSERCION EMPRESA*/
INSERT INTO empresa values(
	null,
	'SystemTI',
	'SYSTI1234',
	'55801',
	'systemti@gmail.com',
	'5500000010',
	'url.com'
);

/*INSERCION CATEGORIAS*/
INSERT INTO categoriaPregunta values(
	1,
	'SISTEMA DE GESTIÓN DE CALIDAD',
	15
);
INSERT INTO categoriaPregunta values(
	2,
	'RESPONSABILIDAD DE LA DIRECCIÓN',
	15
);
INSERT INTO categoriaPregunta values(
	3,
	'GESTIÓN DE RECURSOS',
	10
);
INSERT INTO categoriaPregunta values(
	4,
	'REALIZACIÓN DEL PRODUCTO',
	50
);
INSERT INTO categoriaPregunta values(
	5,
	'MEDICIÓN ANÁLISIS Y MEJORA',
	10
);

/*INSERCION SUBCATEGORIA SISTEMA DE GESTION DE CALIDAD*/
INSERT INTO subcategoriaPregunta values(
	1,
	1,
	'Requisitos generales',
	0.5
);
INSERT INTO subcategoriaPregunta values(
	2,
	1,
	'Requisitos de documentación',
	0.2
);
INSERT INTO subcategoriaPregunta values(
	3,
	1,
	'Manual de calidad',
	0
);
INSERT INTO subcategoriaPregunta values(
	4,
	1,
	'Control de Documentos',
	0.3
);
INSERT INTO subcategoriaPregunta values(
	5,
	1,
	'Registros',
	0
);


/*INSERCION SUBCATEGORIA RESPONSABILIDAD DE LA DIRECCIÓN*/
INSERT INTO subcategoriaPregunta values(
	6,
	2,
	'Compromiso de la dirección',
	0.3
);
INSERT INTO subcategoriaPregunta values(
	7,
	2,
	'Enfoque del cliente',
	0.06
);
INSERT INTO subcategoriaPregunta values(
	8,
	2,
	'Política de calidad',
	0.24
);
INSERT INTO subcategoriaPregunta values(
	9,
	2,
	'Responsabilidad, autoridad y comunicación',
	0.4
);

/*INSERCION SUBCATEGORIA GESTIÓN DE RECURSOS*/
INSERT INTO subcategoriaPregunta values(
	10,
	3,
	'Gestión de Recursos',
	1
);

/*INSERCION SUBCATEGORIA REALIZACIÓN DEL PRODUCTO*/
INSERT INTO subcategoriaPregunta values(
	11,
	4,
	'Planificación de la realización del producto',
	0.2
);
INSERT INTO subcategoriaPregunta values(
	12,
	4,
	'Procesos relacionados con el cliente',
	0.2
);
INSERT INTO subcategoriaPregunta values(
	13,
	4,
	'Diseño y desarrollo',
	0.25
);
INSERT INTO subcategoriaPregunta values(
	14,
	4,
	'Compras',
	0.15
);
INSERT INTO subcategoriaPregunta values(
	15,
	4,
	'Producción y prestación del servicio',
	0.15
);
INSERT INTO subcategoriaPregunta values(
	16,
	4,
	'Control de los dispositivos de seguimiento y medición',
	0.05
);

/*INSERCION SUBCATEGORIA MEDICIÓN ANÁLISIS Y MEJORA*/
INSERT INTO subcategoriaPregunta values(
	17,
	5,
	'Generalidades',
	0
);
INSERT INTO subcategoriaPregunta values(
	18,
	5,
	'Seguimiento y medición',
	0.4
);
INSERT INTO subcategoriaPregunta values(
	19,
	5,
	'Control del producto no conforme',
	0.3
);
INSERT INTO subcategoriaPregunta values(
	20,
	5,
	'Análisis de datos',
	0.2
);
INSERT INTO subcategoriaPregunta values(
	21,
	5,
	'Mejora',
	0.1
);

/*INSERCION TABLA PREGUNTAS 1.1 Requisitos Generales*/
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿La organización ha establecido un sistema de gestión de la Calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿Está documentado el sistema de gestión de la Calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'Se han identificado todos los procesos y todas las relaciones entre los procesos en los que se va implantar el sistema de Calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿Cuántos procesos se ha implantado el sistema de gestión de calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿Para cada proceso se indica cuales son los estándares mínimos de calidad y las técnicas para comprobar la calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿Hay algún protocolo para seguir en caso de que un proceso no cumpla lo dictado en el sistema de calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿La organización tiene una posición activa para mejorar el sistema de gestión de Calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿La organización tiene una posición activa para mantener el sistema de gestión de Calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	1,
	'¿Cuándo se realiza una subcontrata externa se tiene en cuenta que la empresa externa cumpla con el sistema de calidad?',
	false
);

/*INSERCIÓN 1.2 REQUISITOS DE LA DOCUMENTACIÓN*/
INSERT INTO pregunta VALUES (
	null, 
	2,
	'¿Los procedimientos de cada proceso identificado están documentados conforme a la norma?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	2,
	'¿Existe un manual de calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	2,
	'¿La documentación se acompaña de los documentos que necesita la organización para asegurarse que su sistema de calidad tenga una planificación, operación y control de sus procesos correcta?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	2,
	'¿Existen la documentación sobre los procedimientos para asegurar la identificación, almacenamiento, protección, recuperación, tiempo de retención y disposición de los registros?',
	true
);

/*INSERCIÓN 1.3 MANUAL DE CALIDAD*/
INSERT INTO pregunta VALUES (
	null, 
	3,
	'¿En el manual de Calidad se identifica cual es el alcance?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	3,
	'¿Se detalla y se justifica el alcance?',
	true
);

/*INSERCIÓN PREGUNTAS 1.4 CONTROL DE DOCUMENTOS*/
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Se revisan y se actualizan los documentos?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Existe un procedimiento para aprobar documentos?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Se identifican los cambios en los documentos?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Es fácil de idenficar la versión (revisión) de los documentos?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Los documentos están accesibles a los usuarios del sistema de calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Son legibles?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Se facilita documentos externos necesarios al sistema de calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	4,
	'¿Se asegura que siempre se usa la última versión?',
	false
);

/*INSERCIÓN PREGUNTAS 1.5 REGISTROS*/
INSERT INTO pregunta VALUES (
	null, 
	5,
	'¿Existen registros?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	5,
	'¿Se usan y se mantienen los registros?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	5,
	'¿La información de los registros es accesible y legible?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	5,
	'¿Sobre los tiempos de retención de los registros, cuando se almacenan en medios electrónicos se tiene en cuenta la tasa de degradación de la información, disponibilidad de dispositivos, las necesidades de software para acceder, así como protección ante vi',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	5,
	'¿Está determinado en algún documento el tiempo de borrado de los registros?',
	true
);

/*INSERCIÓN PREGUNTAS 2.1 COMPROMISO DE LA DIRECCIÓN*/
INSERT INTO pregunta VALUES (
	null, 
	6,
	'¿Existe una política de calidad clara y bien definida por la dirección?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	6,
	'¿Existe una lista de objetivos de calidad establecidos por la dirección?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	6,
	'¿Realiza la dirección revisiones periódicas sobre el sistema de gestión de la calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	6,
	'¿Existe un registro de estas revisiones?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	6,
	'¿Se asegura la dirección de que existen recursos disponibles para la gestión de la calidad?',
	false
);

/*INSERCIÓN PREGUNTAS 2.2 ENFOQUE AL CLIENTE*/
INSERT INTO pregunta VALUES (
	null, 
	7,
	'¿Se asegura la dirección que se satisfacen todos los requisitos establecidos por los clientes?',
	false
);

/*INSERCIÓN PREGUNTAS 2.2 ENFOQUE AL CLIENTE*/
INSERT INTO pregunta VALUES (
	null, 
	7,
	'¿Se asegura la dirección que se satisfacen todos los requisitos establecidos por los clientes?',
	false
);

/*INSERCIÓN PREGUNTAS 2.3 POLÍTICA DE CALIDAD*/
INSERT INTO pregunta VALUES (
	null, 
	8,
	'¿Se adecua la política de calidad a los propósitos de la organización?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	8,
	'¿Existe un marco de referencia para establecer y revisar los objetivos de calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	8,
	'¿Conocen los miembros de la organización que existe una política y unos objetivos de calidad?',
	false
);

/*INSERCIÓN PREGUNTAS 2.4 RESPONSABILIDAD, AUTORIDAD Y COMUNICACIÓN*/
INSERT INTO pregunta VALUES (
	null, 
	9,
	'¿Existe un organigrama de toda la organización?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	9,
	'¿Es conocido el organigrama por todos los miembros de la organización?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	9,
	'¿Existe un responsable designado por la dirección que se encargue de controlar todos los procesos relacionados con la gestión de calidad?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	9,
	'¿Conoce la dirección todos los documentos del sistema de calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	9,
	'¿Existe algún protocolo de comunicación dentro de la organización para asegurar que ésta se realiza correctamente entre las distintas partes de la organización?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	9,
	'¿Se realiza un estudio de las revisiones de calidad para elaborar un plan de mejora del sistema?',
	false
);

/*INSERCIÓN PREGUNTAS 3 GESTIÓN DE RECURSOS*/
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Existe un encargado de controlar y suministrar todos los recursos necesarios para poder mantener el sistema de gestión de la calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Está debidamente cualificado dicho encargado para realizar su trabajo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Se le asigna la competencia necesaria a los responsables del área de calidad para que realicen adecuadamente su trabajo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Se le proporciona formación a los empleados para satisfacer las necesidades de calidad y hacerlos conscientes de la necesidad de la calidad en los procesos?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Existen infraestructuras suficientes para llevar a cabo el sistema de gestión de calidad y lograr la conformidad con los requisitos de los productos?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Existen informes y encuestas realizadas a los empleados de la organización dónde se determine si hay un ambiente de trabajo adecuado?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	10,
	'¿Se toman medidas para tratar de mejorar el ambiente de trabajo?',
	false
);

/*INSERCIÓN PREGUNTAS 4.1 PLANIFICACIÓN DE LA REALIZACIÓN DEL PRODUCTO*/
INSERT INTO pregunta VALUES (
	null, 
	11,
	'¿Se realiza planificación de procesos antes de la realización del producto?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	11,
	'¿Se utiliza alguna metodología de ciclo de vida en el desarrollo del software?',
	true
);
INSERT INTO pregunta VALUES (
	null, 
	11,
	'¿La planificación de la calidad es suficiente?',
	false
);

/*INSERCIÓN PREGUNTAS 4.2 PROCESOS RELACIONADOS CON EL CLIENTE*/
INSERT INTO pregunta VALUES (
	null, 
	12,
	'¿Están claramente identificados y documentados los requisitos del cliente?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	12,
	'¿Ha intervenido el cliente de manera activa en la especificación de los requisitos del cliente?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	12,
	'¿Han sido revisados y estudiados los requisitos antes de comenzar el 4 desarrollo del producto?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	12,
	'¿Se realizan estudios de viabilidad sobre las ofertas de software antes 4 de su aceptación?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	12,
	'¿Existe un método eficaz de comunicación con el cliente?',
	false
);

/*INSERCIÓN PREGUNTAS 4.3 DISEÑO Y DESARROLLO*/
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Están claramente identificadas las etapas del diseño y desarrollo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Están claramente identificadas las funciones de cada una de las etapas 4 del diseño y desarrollo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Existe un calendario de diseño y desarrollo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Están claramente identificadas los contenidos de los elementos de 4 entrada y salida a cada una de las fases?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Están debidamente aprobados los resultados de cada una de las fases?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Se realizan revisiones al diseño y desarrollo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Se realizan verificaciones y validaciones del diseño y desarrollo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Se realizan pruebas unitarias, de integración, validación, aceptación y regresión?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	13,
	'¿Existe un sistema de gestión de los cambios?',
	false
);

/*INSERCIÓN PREGUNTAS 4.4 COMPRAS*/
INSERT INTO pregunta VALUES (
	null, 
	14,
	'¿Se realizan evaluaciones de proveedores en función de su capacidad de suministrar productos acordes con los requisitos de la organización?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	14,
	'¿Están descritos los requisitos del producto a comprar?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	14,
	'¿Está debidamente documentado el software libre utilizado?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	14,
	'¿Existe un inventario en el que se describan las compras realizadas?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	14,
	'Una vez realizada la compra, ¿se realiza una verificación de los productos comprados?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	14,
	'¿Cumplen los datos comprados los criterios de calidad necesarios?',
	false
);

/*INSERCIÓN PREGUNTAS 4.5 PRODUCCIÓN Y PRESTACIÓN DE SERVICIO*/
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Se realiza planificación en la producción y prestación del servicio?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Existe una gestión de la configuración adecuada?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Se realiza un control sobre las réplicas?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'En los casos en los que la instalación del producto la pueda llevar a cabo el cliente. ¿Estas especificados claramente la secuencia de pasos necesarios para la instalación?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'En los casos en los que la instalación del producto la lleve a cabo la organización ¿Existe una planificación de la instalación?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Están especificadas en el contrato las condiciones del mantenimiento del producto?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Existe un plan de mantenimiento?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Se realizan validaciones de los procesos de producción y prestación del servicio?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Existe gestión de la configuración?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Están controlados y protegidos debidamente los bienes proporcionados por el cliente para la elaboración del producto?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	15,
	'¿Se preserva el producto realizado hasta su instalación final?',
	false
);

/*INSERCIÓN PREGUNTAS 4.6 CONTROL DE DISPOSITIVOS DE SEGUIMIENTO DE MEDICIÓN*/
INSERT INTO pregunta VALUES (
	null, 
	16,
	'¿El hardware utilizado para proporcionar evidencia de la conformidad del producto con los requisitos determinados está correctamente calibrado?',
	false
);

/*INSERCIÓN PREGUNTAS 5.1 GENERALIDADES*/
INSERT INTO pregunta VALUES (
	null, 
	17,
	'¿Los procesos de seguimiento, medición, análisis y mejora se identifican como parte de la planificación de la calidad?',
	true
);

/*INSERCIÓN PREGUNTAS 5.2 SEGUIMIENTO Y MEDICIÓN*/
INSERT INTO pregunta VALUES (
	null, 
	18,
	'¿Existen métodos para llevara a cabo la obtención de información de la satisfacción del cliente en cuanto a su percepción del cumplimiento de requisitos por parte de la organización?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	18,
	'¿La organización lleva a cabo auditorías internas a intervalos planificados?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	18,
	'¿En ningún caso los auditores auditan su propio trabajo?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	18,
	'¿La organización aplica métodos apropiados para el seguimiento y la medición de los procesos del sistema de gestión de la calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	18,
	'Cuando no se alcanzan los resultados planificados, ¿se llevan a cabo correcciones y acciones correctivas?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	18,
	'¿La organización mide y hace seguimiento de las características del producto verificando así que cumplen los requisitos exigidos?',
	false
);

/*INSERCIÓN PREGUNTAS 5.3 CONTROL DE PRODUCTO NO CONFORME*/
INSERT INTO pregunta VALUES (
	null, 
	19,
	'¿La organización se asegura de que el producto que no sea conforme con los requisitos sea identificado y controlado para prevenir su uso o entrega no intencional?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	19,
	'Cuando se corrige un producto no conforme, ¿se somete a una nueva verificación?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	19,
	'¿El proveedor identifica en qué puntos se requiere el control y el registro de productos no conformes?',
	false
);

/*INSERCIÓN PREGUNTAS 5.4 ANÁLISIS DE DATOS*/
INSERT INTO pregunta VALUES (
	null, 
	20,
	'¿La organización determina, recopila y analiza los datos apropiados para demostrar la idoneidad y la eficacia del sistema de gestión de la calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	20,
	'¿La organización determina, recopila y analiza los datos apropiados para demostrar la satisfacción del cliente?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	20,
	'¿La organización determina, recopila y analiza los datos apropiados sobre la conformidad con los requisitos del producto?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	20,
	'¿La organización determina, recopila y analiza los datos apropiados para demostrar las características y tendencia de los procesos y los productos, incluyendo las oportunidades para llevar a cabo acciones preventivas?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	20,
	'¿La organización se asegura de que el producto que no sea conforme con los requisitos sea identificado y controlado para prevenir su uso o entrega no intencional?',
	false
);

/*INSERCIÓN PREGUNTAS 5.5 MEJORA*/
INSERT INTO pregunta VALUES (
	null, 
	21,
	'¿La organización mejora continuamente la eficacia del sistema de gestión de la calidad?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	21,
	'¿Hay establecido un proceso de mejora? En caso afirmativo ¿a cuántos procesos del ciclo de vida del software se aplica?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	21,
	'¿La organización toma acciones para eliminar la causa de no conformidades?',
	false
);
INSERT INTO pregunta VALUES (
	null, 
	21,
	'¿La organización determina acciones para eliminar las causas de no conformidades potenciales para prevenir su ocurrencia?',
	false
);

