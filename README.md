## Descripcion

Se trata de la recepcion de archivos del cliente que nos envian mediante SFTP en formato .TXT el cual contiene informacion de una oferta de viaje en una sola linea separando cada campo por el signo * y cada signo ~ es una linea.

Estos archivos se identifican por codigos que se manejan por EDI Standards (electronic data interchange) y estaremos usando los code 204 y 990(respuesta).

Solo se toman en cuenta los campos que son requeridos para almacenarlos en una tabla en B.D. de Sql server para tomarlos en cuenta al responder cada recepcion.

Cada contestacion se almacenan en otra tabla identificada por 990 y se dara un Id incremental el cual se incluira en el archivo 990.

## Conocimiento 

Proceso del Funcionamiento del sistema:

- Conexion a un carpeta via Sftp
- Tarea con Scheduling de Laravel y con el Cron del servidor Centos 7 el cual estara revisando si existen nuevos archivos condicionando solo formatos .txt y ademas que el id de la oferta sea nuevo.
- Si es null va a leer el archivo TxT y lo convertira en un array separandolo por los signos ~ & *.
- Almacenara el nombre del archivo en una tabla de la B.D. principal en Mysql.
- Enviamos los datos separados a la tabla de la B.D. en Sql server.
- Se Notifica por correo con algunos datos principales del archivo y un Boton con el Link de acceso.`
- El navegador muestra a detalle la oferta y con 2 opciones ACEPTAR o RECHARZAR.
- Al elegir una opcion se almancenan los datos en la tabla 990 de Sql Server.
- Despues se Generara un Archivo .txt identificado con el code 990 y se envia por el acceso SFTP.
- Al usuario se le notifica en misma pantalla el envio y queda caducado el acceso a este link.
- Cerrando la notificacion se muestra un aviso "Informacion no disponible".


Paquetes se usan dentro del proyecto:

- [Laravel flysystem-sftp ](https://github.com/thephpleague/flysystem-sftp)
- [Laravel Mail](https://github.com/guzzle/guzzle)
- [Laravel Markdown ](https://laravel.com/docs/7.x/mail#markdown-mailables)


## Screens

<p align="center"><img src="https://i.ibb.co/s9kmD8P/0001.png" width="650"></p>
<p align="center"><img src="https://i.ibb.co/48xNCvL/0002.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/syDVpXv/0003.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/fGT3N8L/0004.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/XVS7Z1L/00005.png" width="450"></p>


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
