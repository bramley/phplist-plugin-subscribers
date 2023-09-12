<?php
/**
 * SubscribersPlugin for phplist.
 *
 * This file is a part of SubscribersPlugin.
 *
 * SubscribersPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * SubscribersPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2011-2017 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * This file contains the Spanish text.
 *
 * @category  phplist
 */

/*
 *    Important - this file must be saved in UTF-8 encoding
 *
 */
$lan = array(
    'plugin_title' => 'Plugin Suscriptores',
/*  Menu items */
    'Advanced search' => 'Búsqueda Avanzada',
    'Subscriber commands' => 'Comandos de suscriptores',
    'Subscriber reports' => 'Informes de suscriptores',
/*  Advanced Search page */
    'id' => 'id',
    'lists' => 'listas',
    'email' => 'email',
    'Subscribers' => 'Suscriptores',
    'confirmed_heading' => 'Conf',
    'not confirmed' => 'no confirmado',
    'blacklisted_heading' => 'Bl',
    'User is blacklisted' => 'Usuario en la lista negra',
    'Subs page' => 'Página Susc',
    'Campaigns' => 'Campañas',
    'Campaigns sent' => 'Campañas enviadas',
    'Campaigns opened' => 'Campañas abiertas',
    'Campaigns clicked' => 'Campañas con click',
    'Copy results to command' => 'Copiar los resultados a Comandos de suscriptores',
/* Subscriber History page */
    'Show events' => 'Mostrar eventos',
    'All' => 'Todos',
    'Since' => 'Desde',
    'Contains' => 'Contiene',
    'Show' => 'Mostrar',
//    controller
    'Filter' => 'Filtro',
    'Events' => 'Eventos',
    'event' => 'evento',
    'date' => 'fecha',
    'summary' => 'resumen',
    'detail' => 'detalle',
    'IP address' => 'dirección IP',
/*  Subscriptions page */
    'period' => 'Período',
    'subscriptions' => 'Suscripciones',
    'unconfirmed' => 'No confirmado',
    'blacklisted' => 'En la Lista Negra',
    'active' => 'Activo',
    'unsubscriptions' => 'Dados de baja',
/*  Action page */
    'Apply command to a group of subscribers' => 'Aplicar comando a un grupo de suscriptores',
    'Action for each subscriber' => 'Acción para cada suscriptor',
    'Confirm' => 'Confirmar',
    'Unconfirm' => 'Desconfirmar',
    'Blacklist' => 'Añadir a la Lista Negra',
    'Unblacklist' => 'Eliminar de la Lista Negra',
    'Delete' => 'Borrar',
    'Add to list' => 'Añadir a la lista',
    'Move between lists' => 'Mover entre listas',
    'Remove from list' => 'Eliminar de la lista',
    'Remove from all subscribed lists' => 'Eliminar de todas las listas suscritas',
    'Resend confirmation request' => 'Reenviar petición de confirmación',
    'Change subscribe page' => 'Cambiar página de suscripción',
    'Reset bounce count' => 'Resetear contador de rebotes',
    'Empty attribute value' => 'Vaciar valor de atributo',
    'Copy/paste a list of email addresses, then click the Process button' => 'Copiar/pegar una lista de direcciones de email, después hacer click en el botón Procesar',
    'Process' => 'Procesar',
    'Or select a file of email addresses to upload, then click the Upload button' => 'O seleccione un fichero de direcciones de email para cargar, después hacer click en el botón Subir',
    'Upload' => 'Subir',
    'Or enter a partial email address to be matched, then click the Match button' => 'O introduzca una dirección de email parcial para ser emparejada, después hacer click en el botón Emparejar',
    'Match' => 'Emparejar',
    'Confirm action and subscribers' => 'Confirmar acción y suscriptores',
    'Review the action and the email addresses, then click Apply or Cancel.' => 'Revisar la acción y las direcciones de email, después hacer click en Aplicar o Cancelar.',
    'Apply' => 'Aplicar',
    'Cancel' => 'Cancelar',
    'emails not entered' => 'emails no introducidos',
    'no valid email addresses entered' => 'no se han introducido direcciones de email válidas',
    'error_match_not_entered' => 'se tiene que introducir una dirección de email parcial',
    'error_no_match' => 'No se han encontrado suscriptores emparejados por "%s"',
    'error_no_acceptable' => 'No se han encontrado suscriptores eligibles para la acción seleccionada',
    'error_action_not_selected' => 'Se tiene que seleccionar una acción',
    'upload_error_0' => 'No hay errores, el fichero se ha subido correctamente',
    'upload_error_1' => 'El fichero subido excede la directiva upload_max_filesize en php.ini',
    'upload_error_2' => 'El fichero subido excede la directiva MAX_FILE_SIZE que se especifica en el formulario HTML',
    'upload_error_3' => 'El fichero subido sólo fue cargado parcialmente',
    'upload_error_4' => 'No se ha cargado ningún fichero',
    'upload_error_6' => 'No se encuentra una carpeta temporal',
    'error_extension' => 'La extensión del fichero tiene que ser txt ó csv',
    'error_empty' => 'el fichero "%s" parece estar vacío',
    'history_confirmed' => 'Confirmado',
    'history_unconfirmed' => 'Desconfirmado',
    'history_added' => 'Añadido a la lista %s',
    'history_blacklisted' => 'Añadido a la Lista Negra por %s',
    'history_moved' => 'Movido de la lista %s a la lista %s',
    'history_removed' => 'Eliminado de la lista "%s"',
    'history_removed_all' => 'Eliminado de las listas %d',
    'history_subscribe_page' => 'Página de Suscripción cambiada de %d a %d',
    'result_blacklisted' => 'Suscriptores añadidos a la Lista Negra: %d',
    'result_unblacklisted' => 'Suscriptores eliminados de la Lista Negra: %d',
    'result_confirmed' => 'Suscriptores confirmados: %d',
    'result_unconfirmed' => 'Suscriptores desconfirmados: %d',
    'result_added' => 'Subscriptores añadidos a la lista "%s": %d',
    'result_deleted' => 'Suscriptores eliminados: %d',
    'result_moved' => 'Suscriptores eliminados de la lista "%s": %d, añadidos a la lista "%s": %d',
    'result_removed' => 'Suscriptores eliminados de la lista "%s": %d',
    'result_removed_all' => 'Suscriptores eliminados de la listas a las que estaban suscritos: %d',
    'result_resent' => 'Solicitudes de Confirmación reenviadas: %d',
    'result_subscribe_page_changed' => 'Páginas de Suscripción cambiadas: %d',
    'result_bounce_count' => 'Contador de Rebotes reseteado: %d',
    'result_empty_attribute' => 'Atributo "%s" vaciado para %d suscriptores',
    'Subscriber' => 'Suscriptor',
    'Text to prepend to the confirmation request email' => 'Texto para anteponer al email de solicitud de confirmación',
    'resend_prepend' => <<<'END'
Lamentamos molestarle..
Estamos realizando una limpieza de nuestra base de datos y aparece que usted se registro previamente en nuestra lista de correo y no confirmo su suscripción.
Nos gustaría darle la oportunidad de re-confirmar sus suscripción. Las instrucciones de como confirmar están más abajo.
END
    ,
    'Number of subscribers to be processed' => 'Número de suscriptores a ser procesados',
/* Reports */
    'Subscriber history' => 'Historial del suscriptor',
    'Subscriptions' => 'Suscripciones',
    'Subscribers with an invalid email address' => 'Suscriptores con una dirección de email inválida',
    'Inactive subscribers' => 'Suscriptores Inactivos',
    'Subscribers who do not belong to a list' => 'Suscriptores que no pertenecen a una lista',
    'Unsubscribe reasons' => 'Razones para darse de baja',
    'Bounce count' => 'Contador de rebotes',
    'Consecutive bounces' => 'Rebotes consecutivos',
    'Domain subscriber counts' => 'Contadores de suscriptores de Dominio',
    'Subscribers who have not confirmed' => 'Suscriptores que no se han confirmado',
    'Run' => 'Ejecutar',
/* Invalid subscribers */
    'All subscribers have a valid email address' => 'Todos los suscriptores tienen una dirección de email válida',
/* Inactive subscribers */
    'Enter period of inactivity' => 'Introduzca el período de inactividad',
    'Inactivity period' => 'Período de inactividad',
    'Number of campaigns' => 'Número de campañas',
    'Last view' => 'Ultima vista',
    'Recent campaigns' => 'Campañas recientes',
    'Total campaigns' => 'Total de Campañas',
    "Invalid interval value '%s'" => "Valor de intervalo inválido '%s'",
/* Subscribers who do not belong to a list */
    'All subscribers belong to at least one list' => 'Todos los suscriptores pertenecen al menos a una lista',
/* Unsubscribe reasons */
    'reason' => 'Razón',
/* Domain subscriber counts */
    'Subscribers on domain %s' => 'Suscriptores en el dominio %s',
    'Domain %s does not have any subscribers' => 'El dominio %s no tiene ningún suscriptor',
);
