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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");
include_once 'dbagata/classes/core/AgataAPI.class';

$objGet  = db_utils::postmemory($_GET);
ini_set("error_reporting","E_ALL & ~NOTICE");

$sWhere	= "";
$sOrdem	= "";
$and		= "";
$desc		= "";

//'1'=>'Tarefa', '2'=>'Data Cria&ccedil;&atilde;o', '3'=>'Data Atualiza&ccedil;&atilde;o', '4'=>'Tempo Atualização'
$orderBy = '';
if($objGet->ordenarPor == 1) {
	$sOrdem = 'area, at40_sequencial';
}
if($objGet->ordenarPor == 2) {
	$sOrdem = 'area, dt_criacao';
}
if($objGet->ordenarPor == 3) {
	$sOrdem = 'area,  dt_atucli';
}
if($objGet->ordenarPor == 4) {
	$sOrdem = 'area, tempo_atualizacao';
}

if($objGet == true) {
	$desc = ' DESC';
}else {
	$desc = '';
}

//'1'=>'Todas', '2'=>'Autorizadas', '3'=>'N&atilde;o Autorizadas'
if($objGet->tarefasAutorizadas == 1) {
	$sWhere  .= '';
}
if($objGet->tarefasAutorizadas == 2) {
	$sWhere 	.= 'at40_autorizada is true';
	$and		 = ' and ';
}
if($objGet->tarefasAutorizadas == 3) {
	$sWhere 	.= 'at40_autorizada is false';
	$and		 = ' and ';
}

//'1'=>'Todas', '2'=>'Conclu&iacute;das', '3'=>'Em Andamento'
if($objGet->considerarTarefas == 1) {
	$sWhere .= '';
}
if($objGet->considerarTarefas == 2) {
	$sWhere .= $and.'at40_progresso = 100';
}
if($objGet->considerarTarefas == 3) {
	$sWhere .= $and.'at40_progresso < 100';
}

$clagata	= new cl_dbagata('atendimento/ate2_relatoriotempo002.agt');
$api			= $clagata->api;

$api->setParameter('$head1', "Relatório Indicador Tempo Solução de Erros");
$api->setParameter('$head2', 'Periodo: '.$objGet->dataInicial." até ".$objGet->dataFinal);
$api->setParameter('$head3', "");
$api->setParameter('$head4', "");
$api->setParameter('$head5', "");
$api->setParameter('$head6', "");

$api->setParameter('$datainicial', implode("-",array_reverse(explode("/",$objGet->dataInicial))));
$api->setParameter('$datafinal', implode("-",array_reverse(explode("/",$objGet->dataFinal))));


$xml = $api->getReport();

$xml["Report"]["DataSet"]["Query"]["Where"]		= $sWhere;
$xml["Report"]["DataSet"]["Query"]["OrderBy"]	= $sOrdem.$desc;

$api->setReport($xml);

$ok = $api->generateReport();
if (!$ok){
    echo $api->getError();
}
else{ 
    db_redireciona($clagata->arquivo);
}

?>