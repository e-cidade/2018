<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oCriaAbas = new cl_criaabas;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load(" prototype.js, strings.js, webseller.js, scripts.js, estilos.css ");
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="formaba">
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="100%">&nbsp;</td>
  </tr>
</table>
<table marginwidth="0" width="100%" border="1" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <?
   $oCriaAbas->identifica    = array('a1' => 'Parâmetros Globais',
                                     'a2' => 'Agendamento',
                                     'a3' => 'Cartão SUS',
                                     'a4' => 'BPA',
                                     'a5' => 'Procedimento de Triagem'
                                    );

   $oCriaAbas->src           = array('a1' => 'sau4_sau_config001.php',
                                     'a2' => 'sau4_sau_config002.php',
                                     'a3' => 'sau4_sau_config003.php',
                                     'a4' => 'sau4_sau_config004.php',
                                     'a5' => 'sau4_sau_config005.php'
                                    );
   $oCriaAbas->sizecampo     = array('a1'=>20,'a2'=>20, 'a3'=>20, 'a4'=>20, 'a5'=>25);
   $oCriaAbas->disabled      = array('a1'=>'false','a2'=>'true', 'a3'=>'true', 'a4'=>'true', 'a5'=>'true');
   $oCriaAbas->scrolling     = 'no';
   $oCriaAbas->iframe_height = '600';
   $oCriaAbas->iframe_width  = '100%';
   $oCriaAbas->cria_abas();
   ?>
  </td>
 </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
</body>
</html>