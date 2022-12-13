<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empage_classe.php"));
require_once(modification("classes/db_empagetipo_classe.php"));
require_once(modification("classes/db_empagemov_classe.php"));
require_once(modification("classes/db_empagemovconta_classe.php"));
require_once(modification("classes/db_empord_classe.php"));
require_once(modification("classes/db_empagepag_classe.php"));
require_once(modification("classes/db_empageslip_classe.php"));
require_once(modification("classes/db_empagemovforma_classe.php"));
require_once(modification("classes/db_empagegera_classe.php"));
require_once(modification("classes/db_empageconf_classe.php"));
require_once(modification("classes/db_empageconfgera_classe.php"));
require_once(modification("classes/db_conplanoconta_classe.php"));
require_once(modification("classes/db_empagemod_classe.php"));
require_once(modification("classes/db_pagordem_classe.php"));
require_once(modification("classes/db_pagordemconta_classe.php"));
require_once(modification("classes/db_pcfornecon_classe.php"));
require_once(modification("classes/db_empageforma_classe.php"));
require_once(modification('model/agendaPagamento.model.php'));
require_once(modification("classes/db_empageordem_classe.php"));
require_once(modification("classes/db_empagenotasordem_classe.php"));
require_once(modification("classes/db_empparametro_classe.php"));

$empagetipo       = new cl_empagetipo();
$clpagordem       = new cl_pagordem();
$clpagordemconta  = new cl_pagordemconta();
$clempord         = new cl_empord();
$clempagemov      = new cl_empagemov();
$clempagemovconta = new cl_empagemovconta();
$clempagepag      = new cl_empagepag();
$clpcfornecon     = new cl_pcfornecon();
$clempageforma    = new cl_empageforma();
$clempagemovforma = new cl_empagemovforma();
$clempage         = new cl_empage();
$clempagetipo     = new cl_empagetipo();
$clempageslip     = new cl_empageslip();
$clempagemovforma = new cl_empagemovforma();
$clempagegera     = new cl_empagegera();
$clempageconf     = new cl_empageconf();
$clempageconfgera = new cl_empageconfgera();
$clempagemod      = new cl_empagemod();
$oDaoNotasOrdem   = new cl_empagenotasordem();
$oDaoOrdemAgenda  = new cl_empageordem();

db_postmemory($_POST);

$db_opcao = 1;
$db_botao = false;

if(isset($e80_data_ano)){
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if(isset($atualizar)){
  $sqlerro = false;
  db_inicio_transacao();

  $arr_valores = split("XX",$ords);
  $movimentoss = "";
  $virgulamovi = "";
  /*
   * incluimos a ordem de pagamento de agenda.
   *
   */
  $e42_dtpagamento = date("Y-m-d",db_getsession("DB_anousu"));
  if ($e42_dtpagamento == "") {

    $sqlerro  = true;
    $erro_msg = "Campo data de pagamento deve ser preenchido";

  }
  if (!$sqlerro) {


    $dtPagamento     = implode("-",array_reverse(explode("/", $e42_dtpagamento)));
    $sSqlAgendaDoDia = $oDaoOrdemAgenda->sql_query_file(null,"*",null,"e42_dtpagamento = '{$dtPagamento}'");
    $rsAgendaDoDia   = $oDaoOrdemAgenda->sql_record($sSqlAgendaDoDia);
    if ($oDaoOrdemAgenda->numrows > 0) {

      $oDaoOrdemAgenda->e42_sequencial = db_utils::fieldsMemory($rsAgendaDoDia, 0)->e42_sequencial;
    } else {

      $oDaoOrdemAgenda->e42_dtpagamento = $dtPagamento;
      $oDaoOrdemAgenda->incluir(null);
      if ($oDaoOrdemAgenda->erro_status == 0) {

        $sqlerro  = true;
        $erro_msg = $oDaoOrdemAgenda->erro_msg;

      }
    }
  }

  for($i=0;$i<sizeof($arr_valores);$i++) {

    $arr_dados = split("-",$arr_valores[$i]);
    $agenda    = $arr_dados[0];
    $aordem    = $arr_dados[1];
    $numemp    = $arr_dados[2];
    $avalor    = $arr_dados[3];
    $agtipo    = $arr_dados[4];
    $aforma    = $arr_dados[5];
    $aconta    = $arr_dados[6];

    $result_movimentos = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"distinct e81_codmov",""," e80_instit = " . db_getsession("DB_instit") . " and e81_codage=$agenda and e82_codord=$aordem "));
    $numrows_movimentos = $clempagemov->numrows;
    if($numrows_movimentos>0){
      db_fieldsmemory($result_movimentos,0);
      if($sqlerro==false){
        $clempagemov->e81_codmov = $e81_codmov;
        $clempagemov->e81_valor  = $avalor;
        $clempagemov->alterar($e81_codmov);
        if($clempagemov->erro_status==0){
          $erro_msg = $clempagemov->erro_msg;
          $sqlerro=true;
        }
      }
      if($sqlerro==false){
        $clempagepag->excluir($e81_codmov);
        if($clempagepag->erro_status==0){
          $erro_msg = $clempagepag->erro_msg;
          $sqlerro=true;
        }
      }

      if($agtipo!=0){
        if($sqlerro==false){
          $clempagepag->incluir($e81_codmov,$agtipo);
          if($clempagepag->erro_status==0){
            $erro_msg = $clempagepag->erro_msg;
            $sqlerro=true;
          }
        }
      }

      if($sqlerro==false){
        $clempagemovforma->excluir($e81_codmov);
        if($clempagemovforma->erro_status==0){
          $erro_msg = $clempagemovforma->erro_msg;
          $sqlerro=true;
        }
      }
      if($aforma == 3 && $agtipo !=0 && $aconta != 0 && $aconta!="n"){
        if($sqlerro==false){
          $clempageconf->excluir($e81_codmov);
          if($clempageconf->erro_status==0){
            $erro_msg = $clempageconf->erro_msg;
            $sqlerro=true;
          }
        }
      }
      if($aforma!=0){
        if($sqlerro==false){
          $clempagemovforma->e97_codmov   = $e81_codmov;
          $clempagemovforma->e97_codforma = $aforma;
          $clempagemovforma->incluir($e81_codmov);
          if($clempagemovforma->erro_status==0){
            $erro_msg = $clempagemovforma->erro_msg;
            $sqlerro=true;
          }
        }
      }

      if($sqlerro==false){
        $clempagemovconta->excluir($e81_codmov);
        if($clempagemovconta->erro_status==0){
          $erro_msg = $clempagemovconta->erro_msg;
          $sqlerro=true;
        }
      }

       if($aconta!=0 && $aconta!="n"){
        if($sqlerro==false){
          $clempagemovconta->e98_contabanco = $aconta;
          $clempagemovconta->incluir($e81_codmov);
          if($clempagemovconta->erro_status==0){
            $erro_msg = $clempagemovconta->erro_msg;
            $sqlerro=true;
          }
        }
      }

      /*
       * Incluimos a nota de liquidação na ordem de pagamento
       */
      if (!$sqlerro) {

        $dtPagamento = implode("-",array_reverse(explode("/", $e42_dtpagamento)));
        $sSqlAgenda  = $oDaoNotasOrdem->sql_query(null,"e43_sequencial",
                                                  null,
                                                  "e43_empagemov = {$e81_codmov}"
                                                  );
        $rsAgenda  = $oDaoNotasOrdem->sql_record($sSqlAgenda);
        if ($oDaoOrdemAgenda->numrows == 0) {


          $oDaoNotasOrdem->e43_autorizado     = "true";
          $oDaoNotasOrdem->e43_valor          = "{$avalor}";
          $oDaoNotasOrdem->e43_ordempagamento = $oDaoOrdemAgenda->e42_sequencial;
          $oDaoNotasOrdem->e43_empagemov      = $e81_codmov;
          $oDaoNotasOrdem->incluir(null);
          if ($oDaoNotasOrdem->erro_status == 0) {

            $sqlerro  = true;
            $erro_msg = $oDaoNotasOrdem ->erro_msg;
          }
        }
      }
      $oAgendaPagamento = new agendaPagamento();
      $rsNumCgmOrdem    = $clpagordem->sql_record($clpagordem->sql_query($aordem,"e60_numcgm"));
      $oAgendaPagamento->setFormaPagamentoCGM(db_utils::fieldsMemory($rsNumCgmOrdem,0)->e60_numcgm, $aforma);

      if($agtipo != 0 && (($aforma == 3 && $aconta != 0 && $aconta!="n"))) {
      	/*
        $result = $clempagetipo->sql_record($clempagetipo->sql_query_file($agtipo,'e83_sequencia as tipsequencia'));
        if($clempagetipo->numrows>0){
          db_fieldsmemory($result,0);
        */
          if($sqlerro==false){
            $clempageconf->e86_codmov = $e81_codmov;
            $clempageconf->e86_data   = date("Y-m-d",db_getsession("DB_datausu"));
            $clempageconf->e86_cheque = "0";
            $clempageconf->e86_correto= "true";
            $clempageconf->incluir($e81_codmov);
            if($clempageconf->erro_status==0){
              $erro_msg = $clempageconf->erro_msg;
              $sqlerro = true;
            }
          }
        /*
        }
        */
      }
    }
  }
  if(isset($geraragenda) && 1==2){
    if($sqlerro==false){
      $result_facilita  = $clempagemod->sql_record($clempagemod->sql_query_modforma(null,"distinct e81_codmov as codmovimento,e83_sequencia as tipsequencia,e83_conta,e83_codmod,e83_codtipo,c63_banco ","c63_banco","e84_codmod <> 1 and e97_codforma=3 and e86_codmov is null and c63_anousu=".db_getsession("DB_anousu") . " and e80_instit = " . db_getsession("DB_instit")));
      $numrows_facilita = $clempagemod->numrows;
      $passargera = true;
      $antigobanco = "";
      for($i=0;$i<$numrows_facilita;$i++){
        db_fieldsmemory($result_facilita,$i);
      }
    }
  }
 // $sqlerro = true;
  db_fim_transacao($sqlerro);
}
//quando entra pela primeira vez
if(empty($e80_data_ano)){
  $e80_data_ano = date("Y",db_getsession("DB_datausu"));
  $e80_data_mes = date("m",db_getsession("DB_datausu"));
  $e80_data_dia = date("d",db_getsession("DB_datausu"));
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if(isset($data)){
  $result01 = $clempage->sql_record($clempage->sql_query_file(null,'e80_codage','',"e80_instit = " . db_getsession("DB_instit") . " and e80_data='$data'"));
  $numrows01 = $clempage->numrows;
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("AjaxRequest.js");
db_app::load("classes/empenho/ViewLiquidacoesPendentes.js");
db_app::load("widgets/DBLookUp.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("widgets/dbtextFieldData.widget.js");
db_app::load("widgets/dbtextField.widget.js");
db_app::load("classes/DBViewManutencaoEmpenho.classe.js");
db_app::load("widgets/DBViewConfiguracaoEnvioTransmissao.js");

?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
.configurada {
    background-color: #d1f07c;
}
.ComMov {
    background-color: rgb(222, 184, 135);
}
.naOPAuxiliar {
    background-color: #ffff99;
}
.configuradamarcado {
    background-color: #EFEFEF;
}
.ComMovmarcado {
    background-color: #EFEFEF;
}
.naOPAuxiliarmarcado {
    background-color: #EFEFEF;
}
.normalmarcado{ background-color:#EFEFEF}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="450" align="left" valign="top" bgcolor="#CCCCCC">
    <?
    $oDaoEmpParametro = new cl_empparametro;
    $rsParam  = $oDaoEmpParametro->sql_record($oDaoEmpParametro->sql_query_file(db_getsession("DB_anousu")));
    $oParam   = db_utils::fieldsMemory($rsParam, 0);
    if ($oParam->e30_agendaautomatico == "t") {
      include(modification(Modification::getFile("forms/db_frmmanutencaoagenda.php")));
    } else {
      include(modification("forms/db_frmempageforma.php"));
    }
    ?>
    </td>
  </tr>
</table>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<?
if(isset($atualizar) && $sqlerro==true){
  db_msgbox($erro_msg);
}
?>