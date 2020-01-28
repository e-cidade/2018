<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);
$clcriaabas = new cl_criaabas;
?>

  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
     <td>
     <?


       $clcriaabas->identifica = array("g1"=>"Dados Programa",
                                       "g5"=>"Objetivos",
                                       "g4"=>"Indicadores",
                                       "g2"=>"Orgão",
                                       "g3"=>"Unidade"
                                       );

       $clcriaabas->title      = array("g1"=>"Dados Programa",
                                       "g5"=>"Objetivos",
                                       "g4"=>"Indicadores",
       								                 "g2"=>"Orgão",
                                       "g3"=>"Unidade"
                                       );


       $clcriaabas->src        = array("g1" => "orc1_orcprograma013.php",
                                       "g3" => "",
       								                 "g2" => "orc1_orcprogramaorgao001.php",
       									               "g3" => "orc1_orcprogramaunidade001.php",
       								                 "g4" => "orc1_orcindicaprograma001.php" );

       $clcriaabas->cria_abas();
     ?>
     </td>
  </tr>
<tr>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>

  document.formaba.g1.size = 25;
  document.formaba.g2.size = 25;
  document.formaba.g3.size = 25;
  document.formaba.g4.size = 25;
  document.formaba.g5.size = 25;

  document.formaba.g2.disabled = true;
  document.formaba.g3.disabled = true;
  document.formaba.g4.disabled = true;
  document.formaba.g5.disabled = true;

</script>
</html>