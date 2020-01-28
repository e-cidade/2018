<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
include_once("classes/db_lab_atributo_componente_classe.php");
include_once("classes/db_lab_requiitem_classe.php");
require_once("libs/db_app.utils.php");
$oAtributos = new cl_lab_atributo_componente;
$oRequiitem = new cl_lab_requiitem;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<?
   if(isset($iRequiitem)){
   	   $sSql=$oRequiitem->sql_query_nova($iRequiitem,"la08_i_codigo,la42_i_atributo");
   	   $rResult=$oRequiitem->sql_record($sSql);
   	   if($oRequiitem->numrows>0){
   	   	  db_fieldsmemory($rResult,0);
   	   }
   }
   echo"<center>";
   if((isset($la08_i_codigo))&&(isset($la42_i_atributo))){
   	  if(($la08_i_codigo!="")&&($la42_i_atributo!="")){
          $oAtributos->Atributos($la08_i_codigo,$la42_i_atributo,$iRequiitem,3,1,0);
   	  }
   }
   echo"</center>";
?>