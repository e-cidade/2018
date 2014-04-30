<?php
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
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_regimemat_classe.php");
include("classes/db_regimematdiv_classe.php");
include("classes/db_serieregimemat_classe.php");
include("classes/db_serie_classe.php");
include("dbforms/db_funcoes.php");
$clregimemat = new cl_regimemat;
$clregimematdiv = new cl_regimematdiv;
$clserieregimemat = new cl_serieregimemat;
$clserie = new cl_serie;

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'PesquisaRegime') {
 $result = $clserieregimemat->sql_record($clserieregimemat->sql_query("","DISTINCT ed218_i_codigo,ed218_c_nome,ed218_c_divisao","ed218_c_nome"," ed11_i_ensino = {$oPost->ensino}"));
 $aResult = db_utils::getColectionByRecord($result, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}

if($oPost->sAction == 'PesquisaDivisao') {
 $result = $clserie->sql_record($clserie->sql_query("","ed11_i_codigo,ed11_c_descr","ed11_i_sequencia"," ed11_i_ensino = {$oPost->ensino}"));
 $result1 = $clserieregimemat->sql_record($clserieregimemat->sql_query("","ed223_i_codigo,ed219_c_nome,ed11_c_descr","ed223_i_ordenacao,ed223_i_codigo"," ed223_i_regimemat = {$oPost->regime} AND ed11_i_ensino = {$oPost->ensino}"));
 $aResult = db_utils::getColectionByRecord($result, false, false, true);
 $aResult1 = db_utils::getColectionByRecord($result1, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode(array($aResult,$aResult1));
}

if($oPost->sAction == 'PesquisaSerie') {
 $result = $clserie->sql_record($clserie->sql_query("","ed11_i_codigo,ed11_c_descr","ed11_i_sequencia"," ed11_i_ensino = {$oPost->ensino}"));
 $result1 = $clserieregimemat->sql_record($clserieregimemat->sql_query("","ed223_i_codigo,ed11_c_descr","ed223_i_ordenacao,ed223_i_codigo"," ed223_i_regimemat = {$oPost->regime} AND ed11_i_ensino = {$oPost->ensino}"));
 $aResult = db_utils::getColectionByRecord($result, false, false, true);
 $aResult1 = db_utils::getColectionByRecord($result1, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode(array($aResult,$aResult1));
}

if($oPost->sAction == 'UpdateSerieRegime') {
 $codregistro = explode(",",$oPost->registros);
 for($t=0;$t<count($codregistro);$t++){
  db_inicio_transacao();
  $clserieregimemat->ed223_i_ordenacao = ($t+1);
  $clserieregimemat->ed223_i_codigo = $codregistro[$t];
  $clserieregimemat->alterar($codregistro[$t]);
  db_fim_transacao();
 }
 if($clserieregimemat->erro_status=="0"){
  $mensagem = $clserieregimemat->erro_msg;
 }else{
  $mensagem = "Alteraçao Efetuada com Sucesso!";
 }
 $oJson = new services_json();
 echo $oJson->encode(urlencode($mensagem));
}

if($oPost->sAction == 'UpdateSerie') {
 $codregistro = explode(",",$oPost->registros);
 for($t=0;$t<count($codregistro);$t++){
  db_inicio_transacao();
  $clserie->ed11_i_sequencia = ($t+1);
  $clserie->ed11_i_codigo = $codregistro[$t];
  $clserie->alterar($codregistro[$t]);
  db_fim_transacao();
 }
 if($clserie->erro_status=="0"){
  $mensagem = $clserie->erro_msg;
 }else{
  $mensagem = "Alteraçao Efetuada com Sucesso!";
 }
 $oJson = new services_json();
 echo $oJson->encode(urlencode($mensagem));
}

?>