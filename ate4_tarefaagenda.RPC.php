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
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

if ( $oParam->exec == "removerLembrete" ){

  $oDaoTarefaagenda = db_utils::getDao('tarefaagenda');
  $lErro = false;
  $sMensagem = "Exclusão Efetuada com sucesso";
  db_inicio_transacao();
	$oDaoTarefaagenda->at77_datavalidade = date('Y-m-d',db_getsession('DB_datausu'));
	$oDaoTarefaagenda->alterar($oParam->iLembrete); 
	if ( $oDaoTarefaagenda->erro_status == 0 ) {
	  $lErro     = true;     
	  $sMensagem = $oDaoTarefaagenda->erro_msg;
	}
  
  db_fim_transacao($lErro);

  if ($lErro) {
    $iStatus = 2;
  }else{
    $iStatus = 1;
  }
  
  $aRegistros = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem),"iLembrete"=>$oParam->iLembrete);
  
  echo $oJson->encode($aRegistros);

}	elseif ( $oParam->exec == "getDadosLembrete" ){

  $sMensagem = "";
  $iStatus   = 1;

  $oDaoTarefaagenda = db_utils::getDao('tarefaagenda');
  $rsLembrete = $oDaoTarefaagenda->sql_record($oDaoTarefaagenda->sql_query_file($oParam->iLembrete));
  if (!$rsLembrete || pg_num_rows($rsLembrete) == 0) {
    $iStatus = 2;
    $sMensagem = "Dados do lembrete {$oParam->iLembrete} não encontrado";
    $oLembrete = new stdClass();
  }else{
    $oLembrete = db_utils::fieldsMemory($rsLembrete,0);
  }
  
  $aRegistros = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem),"oLembrete"=>$oLembrete); 
  echo $oJson->encode($aRegistros);


} elseif ( $oParam->exec == "prorrogarLembrete" ){

  $oDaoTarefaagenda = db_utils::getDao('tarefaagenda');
  $lErro = false;
  $sMensagem = "Inclusão Efetuada com sucesso";

  $rsLembrete = $oDaoTarefaagenda->sql_record($oDaoTarefaagenda->sql_query_file($oParam->iLembrete));
  if (!$rsLembrete || pg_num_rows($rsLembrete) == 0) {
    $iStatus = 2;
    $sMensagem = "Dados do lembrete {$oParam->iLembrete} não encontrado";
  }else{
    $oLembrete = db_utils::fieldsMemory($rsLembrete,0);

    db_inicio_transacao();
    $oDaoTarefaagenda->at77_tarefa       = '0';
    $oDaoTarefaagenda->at77_id_usuario   = db_getsession('DB_id_usuario'); 
    $oDaoTarefaagenda->at77_usuenvolvido = $oLembrete->at77_usuenvolvido;
    $oDaoTarefaagenda->at77_datainclusao = date('Y-m-d',db_getsession('DB_datausu'));
    $oDaoTarefaagenda->at77_datavalidade = "null";
    $oDaoTarefaagenda->at77_dataagenda   = implode('-',array_reverse(explode('/',$oParam->sData)));
    $oDaoTarefaagenda->at77_observacao   = $oParam->sObs;
    $oDaoTarefaagenda->at77_hora         = $oParam->sHora;
    $oDaoTarefaagenda->at77_cliente      = $oLembrete->at77_cliente;
    $oDaoTarefaagenda->incluir(null);
    if ( $oDaoTarefaagenda->erro_status == 0 ) {
      $lErro     = true;     
      $sMensagem = $oDaoTarefaagenda->erro_msg;
    }
    db_fim_transacao($lErro);
    if ($lErro) {
      $iStatus = 2;
    }else{
      $iStatus = 1;
    }
  }

  $aRegistros = array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem)); 
  echo $oJson->encode($aRegistros);

}else if ( $oParam->exec == "getFiltrosConsulta" ){
  
  $iStatus = 1;

  $oDaoTarefaagenda = db_utils::getDao('tarefaagenda');

  $sSqlUsuariosAgenda  = " select 0 as id_usuario,              ";
  $sSqlUsuariosAgenda .= "        'Selecione o Usuário' as nome ";
  $sSqlUsuariosAgenda .= "  union all         ";
  $sSqlUsuariosAgenda .= " select distinct id_usuario, ";
  $sSqlUsuariosAgenda .= "        nome        ";
  $sSqlUsuariosAgenda .= "  from db_usuarios  ";
  $sSqlUsuariosAgenda .= "       inner join tarefaagenda on tarefaagenda.at77_id_usuario = db_usuarios.id_usuario ";
  $sSqlUsuariosAgenda .= " order by id_usuario      ";
  
  $rsUsuariosAgenda = $oDaoTarefaagenda->sql_record($sSqlUsuariosAgenda);
  $aUsuariosAgenda  = db_utils::getColectionByRecord($rsUsuariosAgenda,false,false,true);

  $sSqlClientesAgenda  = " select 0                     as at01_codcli,  ";
  $sSqlClientesAgenda .= "        'Selecione o Cliente' as at01_nomecli ";
  $sSqlClientesAgenda .= "  union all         ";
  $sSqlClientesAgenda .= " select distinct at01_codcli, ";
  $sSqlClientesAgenda .= "        at01_nomecli      ";
  $sSqlClientesAgenda .= "  from clientes  ";
  $sSqlClientesAgenda .= "       inner join tarefaagenda on tarefaagenda.at77_cliente = clientes.at01_codcli ";
  $sSqlClientesAgenda .= " order by at01_codcli ";

  $rsClientesAgenda = $oDaoTarefaagenda->sql_record($sSqlClientesAgenda);
  $aClientesAgenda  = db_utils::getColectionByRecord($rsClientesAgenda,false,false,true);

  $sSqlDepartamentosAgenda  = "   select 0 as coddepto,'Selecione o Departamento' as descrdepto ";
  $sSqlDepartamentosAgenda .= " union ";
  $sSqlDepartamentosAgenda .= "   select coddepto,descrdepto from db_depart order by coddepto ";
  $rsDepartamentosAgenda = $oDaoTarefaagenda->sql_record($sSqlDepartamentosAgenda);
  $aDepartamentosAgenda  = db_utils::getColectionByRecord($rsDepartamentosAgenda,false,false,true);
  
  $aRegistros = array("iStatus"=>$iStatus, "aUsuarios"=>$aUsuariosAgenda,"aClientes"=>$aClientesAgenda, "aDepartamentos"=>$aDepartamentosAgenda);

  echo $oJson->encode($aRegistros);
  
} else if ( $oParam->exec == "getDadosConsulta" ){

  $iStatus = 1;
  $oDaoTarefaagenda = db_utils::getDao('tarefaagenda');
  $where = "where true";
  if ($oParam->sDataIni != "") {
    $sDataIni = implode('-',array_reverse(explode('/',$oParam->sDataIni)));
    $where   .= " and at77_datainclusao >= '{$sDataIni}'";
  }

  if ($oParam->sDataFim != "") {
    $sDataFim = implode('-',array_reverse(explode('/',$oParam->sDataFim)));
    $where   .= " and at77_datainclusao <= '{$sDataFim}'";
  }

  if ($oParam->iUsuario != "" && $oParam->iUsuario != 0) {
    $where   .= " and at77_id_usuario = {$oParam->iUsuario} ";
  }

  if ($oParam->iDepartamento != "" && $oParam->iDepartamento != 0) {
    $where   .= " and at77_id_usuario in (select id_usuario from db_depusu where coddepto = {$oParam->iDepartamento}) ";
  }

  if ($oParam->iCliente != "" && $oParam->iCliente != 0) {
    $where   .= " and at77_cliente = {$oParam->iCliente} ";
  }
  
  $sSqlConsulta  = " select at77_datainclusao, ";
  $sSqlConsulta .= "        at77_hora,         ";
  $sSqlConsulta .= "        at77_tarefa,       ";
  $sSqlConsulta .= "        at77_id_usuario||' - '||login as login,            ";
  $sSqlConsulta .= "        nome,                                            ";
  $sSqlConsulta .= "        at77_cliente||' - '||at01_nomecli as nome_cliente, ";
  $sSqlConsulta .= "        at77_datavalidade, ";
  $sSqlConsulta .= "        at77_observacao    ";
  $sSqlConsulta .= "   from tarefaagenda       ";
  $sSqlConsulta .= "        inner join db_usuarios on db_usuarios.id_usuario = tarefaagenda.at77_id_usuario ";
  $sSqlConsulta .= "        left  join clientes    on clientes.at01_codcli   = tarefaagenda.at77_cliente    ";  
  $sSqlConsulta .= " $where ";

  $rsConsulta     = $oDaoTarefaagenda->sql_record($sSqlConsulta);
  $aDadosConsulta = db_utils::getColectionByRecord($rsConsulta,false,false,true);
  $aRegistros = array("iStatus"=>$iStatus, "aRegistros"=>$aDadosConsulta);

  echo $oJson->encode($aRegistros);

}

?>