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

// Desabilita tempo maximo de execucao
set_time_limit(0);

// Hora de Inicio do Script
$sHoraInicio = date( "H:i:s" );

// Bibliotecas
require_once("db_libconversao.php");
require_once("db_utils.php");

// Timestamp para data/Hora
$sTimeStampInicio = date("Ymd_His");

// Verifica se nao foi setado o nome do script
if(!isset($sNomeScript)) {
  $sNomeScript = basename(__FILE__);
}

// Seta nome do arquivo de Log, caso jรก nรฃo exista
if(!defined("DB_ARQUIVO_LOG")) {
  $sArquivoLog = "log/".$sNomeScript."_".$sTimeStampInicio.".log";
  define("DB_ARQUIVO_LOG", $sArquivoLog);
}

require_once("db_conecta.php");

// Logs...
db_log("", $sArquivoLog);
db_log("*** INICIO Script ".$sNomeScript." ***", $sArquivoLog);
db_log("", $sArquivoLog);

db_log("Arquivo de Log: $sArquivoLog", $sArquivoLog);
db_log("    Script PHP: ".$sNomeScript, $sArquivoLog);
db_log("", $sArquivoLog);

db_log("Iniciando Sessao", $sArquivoLog);
db_query($pConexao, "select fc_startsession()") or die ("Erro inicializando sessใo");

db_log("Setando Variแveis da Sessao", $sArquivoLog);
db_query($pConexao, "select fc_putsession('DB_instit',{$ConfigINI["ConfigInstit"]}::varchar)") or die ("Erro inicializando sessใo");
db_query($pConexao, "select fc_putsession('DB_anousu',{$ConfigINI["ConfigAnoUsu"]}::varchar)") or die ("Erro inicializando sessใo");
db_query($pConexao, "select fc_putsession('DB_datausu','{$ConfigINI["ConfigDataUsu"]}'::varchar)") or die ("Erro inicializando sessใo");

?>