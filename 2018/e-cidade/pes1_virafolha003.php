<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 3;


// DIA, MÊS E ANO INICIAL DO PERÍODO INICIAL
$diaii = "01";
$mesii = db_mesfolha();                                            // FUNÇÃO QUE BUSCA O MÊS CORRENTE DA FOLHA
$anoii = db_anofolha();                                            // FUNÇÃO QUE BUSCA O ANO CORRENTE DA FOLHA

// DIA FINAL DO PERÍODO INICIAL
$diaif = db_dias_mes($anoii,$mesii);                               // QUANTIDADE DE DIAS DO MÊS INFORMADO


// DIA, MÊS E ANO INICIAL DO PERÍODO FINAL
$datafinal = date("d-m-Y",mktime(0,0,0,($mesii-1),$diaii,$anoii)); // BUSCA PÓXIMO MÊS
$arr_datafinal = split("-",$datafinal);                            // QUEBRA DATA NUM ARRAY
$diafi = "01";                                                     // PRIMEIRO DIA DO MÊS
$mesfi = $arr_datafinal[1];                                        // MÊS DO PERÍODO FINAL (POSIÇÃO 1 DENTRO DO ARRAY)
$anofi = $arr_datafinal[2];                                        // ANO DO PERÍODO FINAL (POSIÇÃO 2 DENTRO DO ARRAY)

// DIA FINAL DO PERÍODO FINAL
$diaff = db_dias_mes($anofi,$mesfi);                               // QUANTIDADE DE DIAS DO MÊS INFORMADO

// Variável que controla a existência ou não de lotes
$lLotesFechados    = 0;
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
include(modification("forms/db_frmvirafolha.php"));
?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>