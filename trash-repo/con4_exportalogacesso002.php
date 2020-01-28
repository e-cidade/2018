<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet      = db_utils::postMemory($_GET);

$sSqlLogs  = "select distinct                                                                                           \n";
$sSqlLogs .= "       munic                                    as municipio,                                             \n";
$sSqlLogs .= "       nomeinst                                 as instituicao,                                           \n";
$sSqlLogs .= "       at25_descr                               as area,                                                  \n";
$sSqlLogs .= "       nome_modulo                              as modulo,                                                \n";
$sSqlLogs .= "       descrdepto                               as departamento,                                          \n";
$sSqlLogs .= "       extract (year   from db_logsacessa.data) as exercicio,                                             \n";
$sSqlLogs .= "       extract (year   from db_logsacessa.data) as ano,                                                   \n";
$sSqlLogs .= "       extract (month  from db_logsacessa.data) as mes,                                                   \n";
$sSqlLogs .= "       extract (day    from db_logsacessa.data) as dia,                                                   \n";
$sSqlLogs .= "       extract (hour   from cast(db_logsacessa.data || ' ' || db_logsacessa.hora as timestamp)) as hora,  \n";
$sSqlLogs .= "       extract (minute from cast(db_logsacessa.data || ' ' || db_logsacessa.hora as timestamp)) as minuto,\n";
$sSqlLogs .= "       db_usuarios.nome                         as usuario,                                               \n";
$sSqlLogs .= "       db_logsacessa.data                       as data_log                                               \n";
$sSqlLogs .= "  from db_logsacessa                                                                                      \n";
$sSqlLogs .= "       inner join db_config        on db_config.codigo             = db_logsacessa.instit                 \n";
$sSqlLogs .= "       inner join db_modulos       on db_modulos.id_item           = db_logsacessa.id_modulo              \n";
$sSqlLogs .= "       inner join atendcadareamod  on atendcadareamod.at26_id_item = db_modulos.id_item                   \n";
$sSqlLogs .= "       inner join atendcadarea     on atendcadarea.at26_sequencial = atendcadareamod.at26_codarea         \n";
$sSqlLogs .= "       inner join db_depart        on db_depart.coddepto           = db_logsacessa.coddepto               \n";
$sSqlLogs .= "       inner join db_usuarios      on db_usuarios.id_usuario       = db_logsacessa.id_usuario             \n";
$sSqlLogs .= " where db_logsacessa.data between '{$oGet->dtInicial}' and '{$oGet->dtFinal}'                             \n";

$rsLogs    = db_query($sSqlLogs);

if (!$rsLogs ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=[ 1 ]Erro ao retornar registros.');
  exit;
}
if (pg_num_rows($rsLogs) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=[ 2 ]Nenhum registro no período Informado.');
  exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("estilos.css");
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  ?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<?php 
$sArquivo  = "tmp/log_acesso".date("Y-m-d", db_getsession("DB_datausu")).".txt";
$fArquivo  = fopen($sArquivo, "w");

$aLogs     = db_utils::getCollectionByRecord($rsLogs);

db_criatermometro("termometro", "Concluido...", "blue", 1);
flush();

$iTotal = count($aLogs);

$sCabecalho = "MUNICIPIO;";
$sCabecalho.= "INSTITUICAO;";
$sCabecalho.= "AREA;";
$sCabecalho.= "MODULO;";
$sCabecalho.= "DEPARTAMENTO;";
$sCabecalho.= "EXERCICIO;";
$sCabecalho.= "ANO;";
$sCabecalho.= "MES;";
$sCabecalho.= "DIA;";
$sCabecalho.= "HORA;";
$sCabecalho.= "MINUTO;";
$sCabecalho.= "USUARIO;";
$sCabecalho.= "QUANTIDADE;";
$sCabecalho.= "DATA\n";

fputs($fArquivo, $sCabecalho);

foreach ($aLogs as $iIndice => $oLogs) {
  
  $sLinha  = db_removeAcentuacao($oLogs->municipio   ) . ";";
  $sLinha .= db_removeAcentuacao($oLogs->instituicao ) . ";";
  $sLinha .= db_removeAcentuacao($oLogs->area        ) . ";";
  $sLinha .= db_removeAcentuacao($oLogs->modulo      ) . ";";
  $sLinha .= db_removeAcentuacao($oLogs->departamento) . ";";
  $sLinha .= $oLogs->exercicio                         . ";";
  $sLinha .= $oLogs->ano                               . ";";
  $sLinha .= $oLogs->mes                               . ";";
  $sLinha .= $oLogs->dia                               . ";";
  $sLinha .= $oLogs->hora                              . ";";
  $sLinha .= $oLogs->minuto                            . ";";
  $sLinha .= db_removeAcentuacao($oLogs->usuario     ) . ";";
  $sLinha .= "1"                                       . ";";
  $sLinha .= db_formatar($oLogs->data_log,"d")         . "\n";
  
  fputs($fArquivo, $sLinha);
  $iLinha = $iIndice + 1;
  db_atutermometro($iIndice, $iTotal, "termometro", 1, "Processando Registro {$iLinha}/ $iTotal ...");
  
}

fclose($fArquivo);
db_app::load("scripts.js");

echo "<script>parent.js_retorno('{$sArquivo}');</script>";

?>
</body>
</html>