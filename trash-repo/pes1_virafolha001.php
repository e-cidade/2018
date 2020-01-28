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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;

// DIA, M�S E ANO INICIAL DO PER�ODO INICIAL
$diaii = "01";
$mesii = db_mesfolha();                                            // FUN��O QUE BUSCA O M�S CORRENTE DA FOLHA
$anoii = db_anofolha();                                            // FUN��O QUE BUSCA O ANO CORRENTE DA FOLHA

// DIA FINAL DO PER�ODO INICIAL
$diaif = db_dias_mes($anoii,$mesii);                               // QUANTIDADE DE DIAS DO M�S INFORMADO


// DIA, M�S E ANO INICIAL DO PER�ODO FINAL
$datafinal = date("d-m-Y",mktime(0,0,0,($mesii+1),$diaii,$anoii)); // BUSCA P�XIMO M�S
$arr_datafinal = split("-",$datafinal);                            // QUEBRA DATA NUM ARRAY
$diafi = "01";                                                     // PRIMEIRO DIA DO M�S
$mesfi = $arr_datafinal[1];                                        // M�S DO PER�ODO FINAL (POSI��O 1 DENTRO DO ARRAY)
$anofi = $arr_datafinal[2];                                        // ANO DO PER�ODO FINAL (POSI��O 2 DENTRO DO ARRAY)

// DIA FINAL DO PER�ODO FINAL
$diaff = db_dias_mes($anofi,$mesfi);                               // QUANTIDADE DE DIAS DO M�S INFORMADO
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<?
include("forms/db_frmvirafolha.php");
?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>