<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require_once("libs/db_utils.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubase_classe.php");
include("classes/db_recadastroimobiliarioimoveisbic_classe.php");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?

  if (isset($parametro)) {

    db_inicio_transacao();

    $where2 = "ie29_iptubase = $parametro ";
    $oDaoRecadastroimobiliarioimoveisbic = db_utils::getDao('recadastroimobiliarioimoveisbic');
    $rsRecadastroimobiliarioimoveisbic   = $oDaoRecadastroimobiliarioimoveisbic->sql_record($oDaoRecadastroimobiliarioimoveisbic->sql_query_file(null,'ie29_arquivobic',null,$where2));
    
    if ($oDaoRecadastroimobiliarioimoveisbic->numrows > 0) {
    	$oDadosRecadastroimobiliarioimoveisbic = db_utils::fieldsMemory($rsRecadastroimobiliarioimoveisbic, 0, null);
    } else {
    	db_redireciona("db_erros.php?db_erro=Matrícula sem BIC gerada.");
    }

    $sArquivoDestino = "tmp/".$parametro.".pdf";
    $result = pg_lo_export($oDadosRecadastroimobiliarioimoveisbic->ie29_arquivobic,$sArquivoDestino,$conn);

    db_fim_transacao(!$result);

  }

?>
<table>
<tr>
<td>
<a href="tmp/<?=$parametro?>.pdf" target="_blank">Ver BIC antes do Recadastramento em PDF</a>
</td>
</tr>
</table>

</body>
</html>