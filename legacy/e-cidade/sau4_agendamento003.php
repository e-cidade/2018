<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("classes/db_agendamentos_ext_classe.php"));
require_once(modification("classes/db_sau_config_ext_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

/*ATENÇÃO: Codigo utilizado pelo plugin SMSAgendamento - Chamada require*/

db_postmemory( $_POST );

$clcgs_und             = new cl_cgs_und;
$clagendamentos        = new cl_agendamentos_ext;
$clsau_config          = new cl_sau_config_ext;
$clagendaproced        = new cl_agendaproced;
$clsau_agendaprograma  = new cl_sau_agendaprograma;

$resSau_Config = $clsau_config->sql_record( $clsau_config->sql_query_ext() );
$objSau_Config = db_utils::fieldsMemory($resSau_Config,0);

$db_botao        = true;
$lAgendaLiberada = true;
$lHorarioOcupado = false;

//comvert data tipo A e B
function formataData($data,$tipo) {

  if($tipo == 'b'){
    return implode('-',array_reverse(explode('/',$data)));
  } else {
    return implode('/',array_reverse(explode('-',$data)));
  }
}

$intDisponivel= 999;
if (isset($incluir)) {

  db_inicio_transacao();
  // Atualiza o telefone celular na base
  if ( !empty( $z01_i_cgsund ) ) {

    $clcgs_und->z01_v_telcel = preg_replace("/[^0-9]/", "",$z01_v_telcel);
    $clcgs_und->alterar( $z01_i_cgsund );
  }

  db_fim_transacao();

  $alert = 0;

  //Alerta de paciente ja marcado para a mesma especialidade
  if( $objSau_Config->s103_i_validaagenda != null && $objSau_Config->s103_i_validaagenda >= 0 && $sd30_i_codigo != "" ) {

    $sd27_i_codigo = pg_result(db_query("select sd30_i_undmed from undmedhorario where sd30_i_codigo=$sd30_i_codigo"), 0, 0);

    //Verificar se o CGS ja tem consulta marcada para mesma especialidade
    $vet          = explode("/",$sd23_d_consulta);
    $margem       = date('d/m/Y',mktime(0,0,0,$vet[1],$vet[2]+$objSau_Config->s103_i_validaagenda,$vet[0]));
    $z01_i_cgsund = ( !isset( $z01_i_cgsund ) || trim( $z01_i_cgsund ) == '' ) ? 'NULL' : $z01_i_cgsund;

    $where  = "     sd27_i_codigo   = {$sd27_i_codigo} ";
    $where .= " and sd23_i_numcgs   = {$z01_i_cgsund} ";
    $where .= " and sd23_d_consulta between '{$sd23_d_consulta}' and '{$margem}' ";
    $where .= " and not exists ( select * ";
    $where .= "                    from agendaconsultaanula ";
    $where .= "                   where s114_i_agendaconsulta = sd23_i_codigo ) ";

    if( isset( $s125_i_procedimento ) && !empty( $s125_i_procedimento ) ) {
      $where .= " and s125_i_procedimento = {$s125_i_procedimento}";
    }

    $campos         = " sd02_i_codigo as departamento, z01_nome as medico, sd23_i_codigo as agendamento, ";
    $campos        .= " sd23_c_hora as hora, sd23_d_agendamento as dia ";
    $sql            = $clagendamentos->sql_query_ext("","$campos","",$where);
    $result_valida  = db_query( $sql );

    if ( is_resource( $result_valida ) && pg_num_rows( $result_valida ) > 0 ) {

      $objSau_valida = db_utils::fieldsMemory( $result_valida, 0 );
      $alert = 1;

      echo"<script>
             alert('Paciente já tem consulta ou exame agendado: \\n POSTO : $objSau_valida->departamento \\n DIA ".formataData($objSau_valida->dia,'a')." $objSau_valida->hora \\n PROFISSIONAL : $objSau_valida->medico \\n Agendamento : $objSau_valida->agendamento');
          </script>";
    }
  }

  //Alert CGS não esta atualizado
  if ( ($objSau_Config->s103_i_revisacgs!=null) && ($objSau_Config->s103_i_revisacgs>0)) {

    $data       = date("Y",db_getsession("DB_datausu")).'/'.date("m",db_getsession("DB_datausu")).'/'.date("d",db_getsession("DB_datausu"));
    $vet        = explode("/",$data);
    $margem     = date('d/m/Y',mktime(0,0,0,$vet[1],$vet[2]-$objSau_Config->s103_i_revisacgs,$vet[0]));
    $sql        = "select z01_d_ultalt from cgs_und where z01_i_cgsund = {$z01_i_cgsund} and z01_d_ultalt < '{$margem}'";
    $result_cgs = db_query($sql) ;

    if (pg_num_rows($result_cgs)>0) {

      $data = pg_result($result_cgs,0,0);
      db_msgbox("Por favor atualize os dados cadastrais do paciente($z01_i_cgsund). Última atualização[".formataData($data,"a")."]");
    }
  }

  /*
   * =====================================================
   *   TESTA PARA VER SE O AGENDAMENTO É FEITO POR COTAS
   * =====================================================
   */
  $iUpssolicitante = db_getsession("DB_coddepto");
  $vet             = explode("/",$sd23_d_consulta);

  if ($iUpssolicitante != $sd02_i_codigo) {

    $oResult = getCotasAgendamento($iUpssolicitante, $sd02_i_codigo, $rh70_estrutural, $vet[0], $vet[1], $sd30_i_codigo, $iMedico );
    $dIni    = "$vet[0]-$vet[1]-1";
    $dFim    = "$vet[0]-$vet[1]-";
    $dFim   .= date("t", strtotime("$vet[0]-$vet[1]-1"));

    if ($oResult->lStatus == 1) {

      $sCampo = "count(sd23_i_codigo) as iAgendados";

      $sSubSqlWhere      = "     sd27_i_rhcbo          = {$rh70_sequencial} ";
      $sSubSqlWhere     .= " and sd23_i_upssolicitante = {$iUpssolicitante} ";
      $sSubSqlWhere     .= " and sd04_i_unidade        = {$sd02_i_codigo} ";
      $sSubSqlWhere     .= " and sd23_d_consulta between '{$dIni}' and '{$dFim}' ";
      $sSubSqlWhere     .= " and not EXISTS ( select * ";
      $sSubSqlWhere     .= "                    from agendaconsultaanula ";
      $sSubSqlWhere     .= "                   where s114_i_agendaconsulta = sd23_i_codigo ) ";
      $sSubSqlWhere     .= " AND sd04_i_medico = {$iMedico}";
      $sSubSql           = $clagendamentos->sql_query_consulta_geral( "", $sCampo, "", $sSubSqlWhere );
      $rs                = $clagendamentos->sql_record($sSubSql);
      $oAgendamentosAnt  = db_utils::getCollectionByRecord($rs, false, false, true);

      $sSubSqlWhere       .= " and sd23_i_undmedhor = $sd30_i_codigo";
      $sSubSql             = $clagendamentos->sql_query_consulta_geral( "", $sCampo, "", $sSubSqlWhere );
      $rs                  = $clagendamentos->sql_record($sSubSql);
      $oAgendamentosAntMed = db_utils::getCollectionByRecord($rs, false, false, true);

      if ($clagendamentos->numrows > 0) {

        if ($oResult->aCotasAgendamento[0]->saldo_medico != null) {
          $iSaldo = (int)$oResult->aCotasAgendamento[0]->saldo_medico - (int)$oAgendamentosAnt[0]->iagendados;
        } else {
          $iSaldo = (int)$oResult->aCotasAgendamento[0]->s163_i_quantidade - (int)$oAgendamentosAnt[0]->iagendados;
        }
      }

      $lAgendaLiberada = true;
      if ($iSaldo <= 0) {

        db_msgbox("Saldo insuficiente para agendamento");
        $lAgendaLiberada = false;
      }
    }
  } else {

    $sCampos  = "fc_saldoCotasPrestEspecComp";
    $sCampos .= "($sd02_i_codigo, '".$rh70_estrutural."', ".$vet[1].", ".$vet[0].") as saldo";
    $sSql     = "SELECT ";
    $sSql    .= $sCampos;
    $rs       = db_query($sSql);

    if (pg_num_rows($rs) > 0) {

      $oSaldoAgendamento = db_utils::fieldsMemory($rs, 0);
      $iSaldoCotas       = $oSaldoAgendamento->saldo;

      $lAgendaLiberada = true;

      if ($iSaldoCotas <= 0) {

        db_msgbox("Saldo insuficiente para agendamento");
        $lAgendaLiberada = false;
      }
    }
  }

  if ($lAgendaLiberada == true) {
    $lHorarioOcupado = !validaSaldo($sd30_c_tipograde, $sd30_i_codigo, $sd23_d_consulta, $sd23_i_ficha);
  }

  if ($lAgendaLiberada && !$lHorarioOcupado) {

    //efetua o agendamento
    $sData  = date("Y",db_getsession("DB_datausu")).'/'.date("m",db_getsession("DB_datausu"));
    $sData .= '/'.date("d",db_getsession("DB_datausu"));

    db_inicio_transacao();
    //agendamentos
    $clagendamentos->sd23_i_undmedhor      = $sd30_i_codigo;
    $clagendamentos->sd23_i_usuario        = db_getsession("DB_id_usuario");
    $clagendamentos->sd23_i_numcgs         = $z01_i_cgsund;
    $clagendamentos->sd23_d_agendamento    = $sData;
    $clagendamentos->sd23_d_consulta       = $sd23_d_consulta;
    $clagendamentos->sd23_i_ficha          = $sd23_i_ficha;
    $clagendamentos->sd23_c_hora           = $_GET['sd23_c_hora'];
    $clagendamentos->sd23_i_situacao       = 1;
    $clagendamentos->sd23_i_upssolicitante = $iUpssolicitante;
    $clagendamentos->incluir(null);

    if(($objSau_Config->s103_c_agendaprog=="S")&&($clagendamentos->erro_status!=0)) {

      if( $fa12_c_descricao > 0 ) {

        $clsau_agendaprograma->s141_i_agendamento = $clagendamentos->sd23_i_codigo;
        $clsau_agendaprograma->s141_i_acaoprog    = $fa12_c_descricao;
        $clsau_agendaprograma->s141_d_data        = $sData;
        $clsau_agendaprograma->s141_c_hora        = $clagendamentos->sd23_c_hora;
        $clsau_agendaprograma->incluir(null);

        if( $clsau_agendaprograma->numrows_incluir == 0 ) {

          $clagendamentos->erro_status = "0";
          $clagendamentos->erro_msg    = "Programa=[$fa12_c_descricao] ".$clsau_agendaprograma->erro_msg;
        }
      }
    }

    //agendaproced
    if( (int)$s125_i_procedimento > 0 && $clagendamentos->erro_status != "0" ) {

      $clagendaproced->s125_i_agendamento  = $clagendamentos->sd23_i_codigo;
      $clagendaproced->s125_i_procedimento = $s125_i_procedimento;
      $clagendaproced->incluir(null);

      if( $clagendaproced->numrows_incluir == 0 ) {

        $clagendamentos->erro_status = "0";
        $clagendamentos->erro_msg    = $clagendaproced->erro_msg;
      }
    }

    db_fim_transacao( $clagendamentos->erro_status == "0" );

    if ($clagendamentos->erro_status != "0") {

      //Verifica fichas disponíveis para dar reload no iframe do calendário
      /*ATENÇÃO: Codigo utilizado pelo plugin SMSAgendamento*/
      $intDisponivel = $clagendamentos->fichas($sd30_i_codigo,$sd23_d_consulta, "V_DISPONIVEL");
    }
  } else {

    $clagendamentos->erro_status = "1";
    unset($incluir);

    if ($lHorarioOcupado) {
    ?>
      <script>
       alert("Esse horário já possui Agendamentos!");
       parent.document.getElementById('frameagendados').contentDocument.location.reload(true);
       parent.db_iframe_agendamento.hide();
      </script>
    <?php
    }
  }
} else if( isset( $excluir ) ) {

  db_inicio_transacao();
  $clagendamentos->excluir($chavepesquisaagenda);
  db_fim_transacao();
} else if( isset( $chavepesquisaagenda ) && !empty( $chavepesquisaagenda ) ) {

  $result = $clagendamentos->sql_record($clagendamentos->sql_query_ext($chavepesquisaagenda));
  db_fieldsmemory($result,0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
try{
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("webseller.js");
  db_app::load("estilos.css");
}catch (Exception $eException){
  die( $eException->getMessage() );
}
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?php
        /*ATENÇÃO: Codigo utilizado pelo plugin SMSAgendamento*/
        require_once(modification("forms/db_frmagendamento003.php"));
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form3","s115_c_cartaosus",true,1,"s115_c_cartaosus",true);

</script>
<?php
if( isset( $incluir ) || isset( $excluir ) ) {

  if( $clagendamentos->erro_status == "0" ) {

    $clagendamentos->erro(true,false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clagendamentos->erro_campo != "" ) {

      echo "<script> document.form1.".$clagendamentos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagendamentos->erro_campo.".focus();</script>";
    }
  } else {

    if( $objSau_Config->s103_c_emitircomprovante == 'S' && $lAgendaLiberada == true ) {
      echo "<script> parent.js_comprovante($clagendamentos->sd23_i_codigo); </script> ";
    }

    if( $objSau_Config->s103_c_emitirfaa == 'S' && $lAgendaLiberada == true ) {

      ?>
      <script>
        sd23_d_consulta = parent.document.getElementById('sd23_d_consulta').value;
        a               =  sd23_d_consulta.substr(6,4);
        m               = (sd23_d_consulta.substr(3,2))-1;
        d               =  sd23_d_consulta.substr(0,2);
        data            = new Date(a,m,d);
        dia             = data.getDay()+1;

        rpcAjax                       = new  ws_ajax('sau4_agendamentoRPC.php');
        rpcAjax.param.unidade         = parent.document.form1.sd02_i_codigo.value;
        rpcAjax.param.agendamentofa   = true;
        rpcAjax.param.sd27_i_codigo   = parent.document.form1.sd27_i_codigo.value;
        rpcAjax.param.chave_diasemana = dia;
        rpcAjax.param.sd23_d_consulta = sd23_d_consulta;
        rpcAjax.param.codigos         = <?=$clagendamentos->sd23_i_codigo ?>;

        rpcAjax.execute( 'setAgendamento', 'js_retornoAgendamento' );
      </script>
      <?php
    }
    ?>
    <script>
      <?php
      if( $intDisponivel == 0 ) {
        echo "parent.document.getElementById('framecalendario').contentDocument.location.reload(true);";
      }
      ?>
      parent.document.getElementById('frameagendados').contentDocument.location.reload(true);
      parent.db_iframe_agendamento.hide();
    </script>
    <?php
  }
}
?>
<script>
function js_retornoAgendamento(objAjax) {

  var objRetorno = rpcAjax.monta( objAjax );

  if( objRetorno.status == 2 ) {
    alert( objRetorno.message.urlDecode() );
  }
}
</script>