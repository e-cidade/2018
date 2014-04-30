<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_efetividaderh_classe.php");
include("dbforms/db_funcoes.php");
$clefetividaderh = new cl_efetividaderh;
$escola = db_getsession("DB_coddepto");

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'PesquisaUltimo'){
 $result = $clefetividaderh->sql_record($clefetividaderh->sql_query("","ed98_i_mes,ed98_i_ano,ed98_d_dataini,ed98_d_datafim,ed98_c_tipo,ed98_c_tipocomp","ed98_d_datafim desc limit 1"," ed98_i_escola = {$oPost->escola} AND ed98_c_tipo = '{$oPost->tipo}'"));
 if($clefetividaderh->numrows>0){
  $aResult = db_utils::getColectionByRecord($result, false, false, true);
 }else{
  $aResult = 0;
 }
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}
if($oPost->sAction == 'PesquisaDatas'){
 $data_ini = date("Y-m-d",mktime(0, 0, 0, $oPost->mes, 1, $oPost->ano));
 $data_fim = date("Y-m-t",mktime(0, 0, 0, $oPost->mes, 1, $oPost->ano));
 $retorno = $data_ini."|".$data_fim;
 $oJson = new services_json();
 echo $oJson->encode($retorno);
}
if($oPost->sAction == 'PesquisaProxMensal'){
 if($oPost->ult_mes!=""){
  if($oPost->ult_mes==12){
   $prox_mes = 1;
   $prox_ano = $oPost->ult_ano+1;
  }else{
   $prox_mes = $oPost->ult_mes+1;
   $prox_ano = $oPost->ult_ano;
  }
  $data_ini = date("Y-m-d",mktime(0, 0, 0, $prox_mes, 1, $prox_ano));
  $data_fim = date("Y-m-t",mktime(0, 0, 0, $prox_mes, 1, $prox_ano));
  $retorno = $prox_mes."|".$prox_ano."|".$data_ini."|".$data_fim;
 }elseif($oPost->ult_mes=="" && $oPost->ult_dtfim!=""){
  $ultimo_mes = substr($oPost->ult_dtfim,5,2);
  $ultimo_ano = substr($oPost->ult_dtfim,0,4);
  if($ultimo_mes==12){
   $prox_mes = 1;
   $prox_ano = $ultimo_ano+1;
  }else{
   $prox_mes = $ultimo_mes+1;
   $prox_ano = $ultimo_ano;
  }
  $data_ini = date("Y-m-d",mktime(0, 0, 0, $prox_mes, 1, $prox_ano));
  $data_fim = date("Y-m-t",mktime(0, 0, 0, $prox_mes, 1, $prox_ano));
  $retorno = $prox_mes."|".$prox_ano."|".$data_ini."|".$data_fim;
 }else{
  $data_ini = date("Y-m-d",mktime(0, 0, 0, date("m"), 1, date("Y")));
  $data_fim = date("Y-m-t",mktime(0, 0, 0, date("m"), 1, date("Y")));
  $retorno = date("n")."|".date("Y")."|".$data_ini."|".$data_fim;
 }
 $oJson = new services_json();
 echo $oJson->encode($retorno);
}
if($oPost->sAction == 'PesquisaProxPeriodo'){
 if($oPost->ult_dtfim!=""){
  $data_ini = date("Y-m-d",mktime(0, 0, 0, substr($oPost->ult_dtfim,5,2), substr($oPost->ult_dtfim,8,2)+1, substr($oPost->ult_dtfim,0,4)));
 }else{
  $data_ini = date("Y-m-d",mktime(0, 0, 0, date("m"), 1, date("Y")));
 }
 $oJson = new services_json();
 echo $oJson->encode($data_ini);
}
if($oPost->sAction == 'VerificaInclusao'){
 $ed98_d_dataini = substr($oPost->dt_ini,6,4)."-".substr($oPost->dt_ini,3,2)."-".substr($oPost->dt_ini,0,2);
 $ed98_d_datafim = substr($oPost->dt_fim,6,4)."-".substr($oPost->dt_fim,3,2)."-".substr($oPost->dt_fim,0,2);
 $result = $clefetividaderh->sql_record($clefetividaderh->sql_query("","ed98_i_mes,ed98_i_ano,ed98_d_dataini,ed98_d_datafim,ed98_c_tipo,ed98_c_tipocomp"," ed98_d_datafim desc limit 1"," ed98_i_escola = {$oPost->escola} AND ed98_c_tipo = '{$oPost->tipo}' AND (ed98_d_dataini BETWEEN '$ed98_d_dataini' AND '$ed98_d_datafim' OR ed98_d_datafim BETWEEN '$ed98_d_dataini' AND '$ed98_d_datafim')"));
 if($clefetividaderh->numrows>0){
  db_fieldsmemory($result,0);
  if($ed98_c_tipocomp=="M"){
   $tipo = "MENSAL";
   $descricao = "Mês/Ano: ".db_mes($ed98_i_mes,1)." / ".$ed98_i_ano;
  }else{
   $tipo = "PERIÓDICA";
   $descricao = "Data Inicial: ".db_formatar($ed98_d_dataini,'d')." Data Final: ".db_formatar($ed98_d_datafim,'d');
  }
  $retorno = "Competência informada está em conflito \ncom o registro abaixo, já cadastrado anteriormente:\n\n";
  $retorno .= "Tipo de Efetividade: ".(trim($ed98_c_tipo)=="P"?"PROFESSORES":"FUNCIONÁRIOS")." \n";
  $retorno .= "Tipo de Competência: $tipo \n";
  $retorno .= "$descricao \n";
 }else{
  $retorno = 0;
 }
 $oJson = new services_json();
 echo $oJson->encode(urlencode($retorno));
}
if ($oPost->sAction == 'Relatorio') {
  
  $sSqlEfetividade = $clefetividaderh->sql_query("", 
                                                 "efetividaderh.*, 
                                                 (select count(*) 
                                                    from efetividade 
                                                    where ed97_i_efetividaderh = ed98_i_codigo) as qtde",
                                                    " ed98_d_datafim desc,ed98_c_tipo",
                                                    " ed98_i_escola = {$iEscola}
                                                    AND (ed98_i_ano = {$oPost->ano} 
                                                     OR extract(year from ed98_d_dataini) = '{$oPost->ano}' 
                                                     OR extract(year from ed98_d_datafim) = '{$oPost->ano}'
                                                    )");
  $result          = $clefetividaderh->sql_record($sSqlEfetividade);
  $aResult         = db_utils::getColectionByRecord($result, false, false, true);
  $oJson           = new services_json();
  echo $oJson->encode($aResult);
}

?>