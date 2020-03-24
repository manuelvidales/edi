## Descripcion

Recepcion de archivos mediante SFTP en formato .TXT el cual contiene informacion de una oferta de viaje en una sola linea.

Estos archivos se identifican por codigos que se manejan por EDI Standards (electronic data interchange) y estaremos usando el 204 y 990 de respuesta.

Solo se toman en cuenta algunos campos que son requeridos y se almacenan en una tabla de Sql server y se usara la informacion dentro del sistema ERP.

Cada respuesta se almacenan en otra tabla identificada por 990 y se dara un Id incremental el cual se incluira en el archivo de respuesta.

## Conocimiento 

Proceso del Funcionamiento del sistema:

- Conexion a un carpeta via Sftp
- Tarea con Scheduling de Laravel y con el Cron del servidor Centos, el cual estara revisando si existen nuevos archivos condicionando solo formatos .txt y ademas que el id de la oferta sea nuevo.
- Si pasa la validacion toma lectura del archivo y lo convierte en un array separandolo por los signos (~) es una fila & cada (*) seran campos.
- Se almacena el nombre del archivo en una tabla de la B.D. principal en Mysql.
- Enviamos los datos separados (del array) a la tabla de la B.D. en Sql server.
- Se Notifica por correo con un Link de acceso.
- El navegador muestra a detalle la oferta con 2 opciones ACEPTAR o RECHAZAR.
- La respuesta se envia y se Actuliza la tabla principal del archivo.
- Despues se almancenan los datos en la tabla 990 de Sql Server.
- Enseguida se generara un Archivo .txt con el code 990 y se envia al SFTP.
- Se notifica en misma la pantalla el envio y queda caducado el acceso al link.
- Al Cerrar la notificacion se muestra el aviso "Informacion no disponible".


Paquetes usados dentro del proyecto:

- [Laravel flysystem-sftp ](https://github.com/thephpleague/flysystem-sftp)
- [Laravel Mail](https://github.com/guzzle/guzzle)
- [Laravel Markdown ](https://laravel.com/docs/7.x/mail#markdown-mailables)
- [Laravel Telescope ](https://github.com/laravel/telescope)


## Screens

<p align="center"><img src="https://i.ibb.co/s9kmD8P/0001.png" width="650"></p>
<p align="center"><img src="https://i.ibb.co/48xNCvL/0002.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/syDVpXv/0003.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/fGT3N8L/0004.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/XVS7Z1L/00005.png" width="450"></p>
<p align="center"><img src="https://i.ibb.co/PDqSDbw/0006.png" width="450"></p>


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
