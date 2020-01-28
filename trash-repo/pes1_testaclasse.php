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
echo "<BR><BR>";
db_postmemory($HTTP_POST_VARS,2);
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
<BR><BR>
<center>
<table border = 2>
<form name="form1" method="post">
<?
include("dbforms/db_classesgenericas.php");
$clform = new cl_formulario_rel_pes;
$clform->manomes = true; // Mostrar ano e mês no formulário.
$clform->desabam = true; // Desabilitar ano e mês
$clform->valpadr = true; // Mostrar data atual nos campos que tem data, ano ou mês
$clform->intregi = true; // Mostrar um campo para registro.
$clform->uniregi = true; // Mostrar um campo para registro.
$clform->unilota = true; // Mostrar um campo para lotação.
$clform->intlota = true; // Mostrar um intervalo de lotações com lotação inicial, final ou selecionadas.

$clform->unirubr = true; // Mostrar um campo para rubrica.

$clform->tipofol = true; // Mostrar o tipo de folha (gerfsal, gerfres, gerffer, etc...).
$clform->tipopon = true; // Mostrar o tipo de ponto (pontofs, pontofx, pontofa, etc...).
$clform->tipores = true; // Mostrar tipo de resumo (geral, por lotação, por registro, etc...).
$clform->mostord = true; // Mostrar ordem (alfabética, numérica, etc...).
$clform->mostasc = true; // Mostrar se é em ordem ordem ascendente ou descendente.
$clform->mosttot = true; // Mostrar totalização (por conta, por registro, etc...).
$clform->mbgerar = true; // True para mostrar botão de processar dados
$clform->jsgerar = "document.form1.submit();";

$clform->anonome = "a123"; // Nome do campo ANOFOLHA.
$clform->mesnome = "b456"; // Nome do campo MESFOLHA.
$clform->re1nome = "c789"; // Nome do campo Registro 1.
$clform->re2nome = "d012"; // Nome do campo Registro 2.
$clform->lo1nome = "e345"; // Nome do campo LOTAÇÃO 1.
$clform->lo2nome = "f678"; // Nome do campo LOTAÇÃO 2.
$clform->ru1nome = "g901"; // Nome do campo RUBRICA 1.
$clform->tfonome = "h234"; // Nome do campo TIPO DE FOLHA.
$clform->tponome = "i567"; // Nome do campo TIPO DE PONTO.
$clform->trenome = "j890"; // Nome do campo RESUMO.
$clform->mornome = "k123"; // Nome do campo ORDEM.
$clform->masnome = "l456"; // Nome do campo TIPO DE ORDEM.
$clform->mtonome = "m789"; // Nome do campo TOTALIZAÇÃO.

$clform->arr_tipofol = Array("gerfadi"=>"Adiantamento","1"=>"qualquer coisa");
$clform->arr_tipopon = Array("gerfsal"=>"Salário","gerfcom"=>"Complementar","gerfres"=>"Rescisão","gerfs13"=>"13o. Salário","gerfadi"=>"Adiantamento");
$clform->arr_tipores = Array("gerfsal"=>"Salário","gerfcom"=>"Complementar","gerfres"=>"Rescisão","gerfs13"=>"13o. Salário","gerfadi"=>"Adiantamento");
$clform->arr_mostord = Array("a"=>"Matrícula","b"=>"Lotação","c"=>"Rubrica");
$clform->arr_mosttot = Array("gerfsal"=>"Salário","gerfcom"=>"Complementar","gerfres"=>"Rescisão","gerfs13"=>"13o. Salário","gerfadi"=>"Adiantamento");

$clform->gera_form(db_anofolha(),db_mesfolha());
?>
</form>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>