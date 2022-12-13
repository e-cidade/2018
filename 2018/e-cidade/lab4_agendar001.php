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
require_once(modification("classes/db_lab_requisicao_classe.php"));
require_once(modification("classes/db_lab_requiitem_classe.php"));
require_once(modification("classes/db_lab_laboratorio_classe.php"));
require_once(modification("classes/db_lab_exame_classe.php"));
require_once(modification("classes/db_lab_setorexame_classe.php"));
require_once(modification("classes/db_lab_medico_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
$oConfig = loadConfig("lab_parametros");

db_postmemory($_POST);

$cllab_requisicao  = new cl_lab_requisicao;
$cllab_requiitem   = new cl_lab_requiitem;
$cllab_laboratorio = new cl_lab_laboratorio;
$cllab_exame       = new cl_lab_exame;
$cllab_setorexame  = new cl_lab_setorexame;
$cllab_medico      = new cl_lab_medico;
$cllab_autoriza    = db_utils::getdao('lab_autoriza');
$departamento      = db_getsession("DB_coddepto");

function somardata($data, $dias= 0, $meses = 0, $ano = 0) {

  $data     = explode("/", $data);
  $novadata = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,   $data[0] + $dias, $data[2] + $ano) );
  return $novadata;
}

$db_opcao = 1;
$db_botao = true;
$lAlterar = false;

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado
 */
function laboratorioLogado() {

  $iUsuario        = db_getsession('DB_id_usuario');
  $iDepto          = db_getsession('DB_coddepto');
  $oLab_labusuario = db_utils::getdao('lab_labusuario');
  $oLab_labdepart  = db_utils::getdao('lab_labdepart');
  $sql             = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", " la05_i_usuario = $iUsuario");
  $rResult         = $oLab_labusuario->sql_record($sql);

  if ($oLab_labusuario->numrows == 0) {

    $sCamposDepartamento = "la02_i_codigo, la02_c_descr";
    $sWhereDepartamento  = " la03_i_departamento = {$iDepto}";
    $sql                 = $oLab_labdepart->sql_query( null, $sCamposDepartamento, 'la02_i_codigo', $sWhereDepartamento );
    $rResult             = $oLab_labdepart->sql_record($sql);

    if ($oLab_labdepart->numrows == 0) {
      return 0;
    }
  }

  $oLab = db_utils::getCollectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
}

$iLaboratorioLogado = laboratorioLogado();

if ( isset($excluir) ) {

   db_inicio_transacao();
   //excluir tabelas filhas
   $iRequisicao = $la22_i_codigo;
   db_query(" delete from lab_requiitem where la21_i_requisicao = {$iRequisicao}");
   db_query(" delete from lab_medico where la38_i_requisicao = {$iRequisicao}");
   $cllab_requisicao->excluir($iRequisicao);
   db_fim_transacao();

 } elseif ( isset($confirma) ) {

  db_inicio_transacao();

  if ( ($la22_i_codigo == '') || ($la22_i_codigo <= 0) ) {

    // incluir requisicao
    $cllab_requisicao->la22_i_departamento = $departamento;
    $cllab_requisicao->la22_i_usuario      = db_getsession("DB_id_usuario");
    $cllab_requisicao->la22_d_data         = date("Y-m-d", db_getsession("DB_datausu"));
    $cllab_requisicao->la22_c_hora         = date("H:i");
    $cllab_requisicao->la22_i_autoriza     = 1;
    $cllab_requisicao->la22_c_medico       = $z01_nome ; 
    $cllab_requisicao->incluir(null);
    $iRequisicao                           = $cllab_requisicao->la22_i_codigo;
  } else {

    $lAlterar = true;
    $cllab_requisicao->alterar($la22_i_codigo);
    $iRequisicao = $la22_i_codigo;
  }

  //para alterar os agendamentos eles são re-incluidos
  if ( $cllab_requisicao->erro_status != '0' ) {

    if ( $lAlterar == true ) {

      db_query(" delete from lab_requiitem where la21_i_requisicao = {$iRequisicao} ");
      db_query(" delete from lab_medico where la38_i_requisicao = {$iRequisicao} ");
    }
  }

  //incluir Exame da requisicao
  if ( $cllab_requisicao->erro_status != '0' ) {

    $aPartes  = explode("##", $sStr);
    $aUrgente = explode("##", $sUrgente);

    for ( $x = 0; $x < count($aPartes); $x++ ) {

      $aVet    = explode("#", $aPartes[$x]);
      $aVet[1] = urldecode($aVet[1]);
      $aVet[2] = urldecode($aVet[2]);

      if ( $aUrgente[$x] == 1 ) {
        $aVet[3] = date(DBDate::DATA_PTBR);
      }

      $sSituacao = RequisicaoExame::NAO_DIGITADO;

      if ( !empty($aVet[8]) ) {
        $sSituacao = urldecode($aVet[8]);
      }

      $dData_entrega                      = somardata($aVet[3], $aVet[5]);
      $cllab_requiitem->la21_i_requisicao = $iRequisicao;
      $cllab_requiitem->la21_i_setorexame = $aVet[0];
      $aData                              = explode("/",$dData_entrega);
      $cllab_requiitem->la21_d_entrega    = $aData[2]."-".$aData[1]."-".$aData[0];
      $cllab_requiitem->la21_c_hora       = $aVet[4];
      $aData                              = explode("/",$aVet[3]);
      $cllab_requiitem->la21_d_data       = $aData[2]."-".$aData[1]."-".$aData[0];
      $cllab_requiitem->la21_i_emergencia = $aUrgente[$x];
      $cllab_requiitem->la21_c_situacao   = $sSituacao;
      $cllab_requiitem->la21_i_quantidade = $aVet[7];

      if ( $cllab_requiitem->erro_status != '0' ) {
        $cllab_requiitem->incluir(null);
      }

      if ( $cllab_requiitem->erro_status == '0' ) {

        $cllab_requisicao->erro_status = '0';
        $cllab_requisicao->erro_sql    = $cllab_requiitem->erro_sql;
        $cllab_requisicao->erro_campo  = $cllab_requiitem->erro_campo;
        $cllab_requisicao->erro_banco  = $cllab_requiitem->erro_banco;
        $cllab_requisicao->erro_msg    = $cllab_requiitem->erro_msg;
      }
    }
  }

  // incluir medico do sistema se existir
  if ( $cllab_requisicao->erro_status != '0' ) {

    if ( isset($la38_i_medico) && ($la38_i_medico != '') ) {

      $cllab_medico->la38_i_medico     = $la38_i_medico;
      $cllab_medico->la38_i_requisicao = $iRequisicao;
      $cllab_medico->incluir(null);

      if ( $cllab_medico->erro_status == '0' ) {

        $cllab_requisicao->erro_status = '0';
        $cllab_requisicao->erro_sql    = $cllab_medico->erro_sql;
        $cllab_requisicao->erro_campo  = $cllab_medico->erro_campo;
        $cllab_requisicao->erro_banco  = $cllab_medico->erro_banco;
        $cllab_requisicao->erro_msg    = $cllab_medico->erro_msg;
      }
    }
  }
  db_fim_transacao();

} elseif ( isset($chavepesquisa) ) {

  $db_opcao = 2;
  $result   = $cllab_requisicao->sql_record($cllab_requisicao->sql_query($chavepesquisa));

  db_fieldsmemory($result,0);

  $sCampos     = " lab_requiitem.*,lab_exame.*,lab_laboratorio.*";
  $sWhere      = "     la21_i_requisicao = {$chavepesquisa} ";
  $sSql        = $cllab_requisicao->sql_query_requiitem( '', $sCampos, 'la21_c_situacao', $sWhere );
  $result      = $cllab_requisicao->sql_record($sSql);
  $alinhasgrid = array();

  for ( $x = 0; $x < $cllab_requisicao->numrows; $x++ ) {

    db_fieldsmemory($result,$x);
    //montar array com linhas do grid
    $aData            = explode("-", $la21_d_data);
    $alinhasgrid[$x]  = "$la21_i_setorexame#$la02_c_descr#$la08_c_descr#".$aData[2]."/".$aData[1]."/".$aData[0];
    $alinhasgrid[$x] .= "#$la21_c_hora#$la08_i_dias#$la21_i_emergencia#$la21_i_quantidade#$la21_c_situacao";
  }

  $sCamposMedico = " la38_i_medico,z01_nome,sd03_i_crm ";
  $sWhereMedico  = " la38_i_requisicao = {$chavepesquisa}";
  $sSqlMedico    = $cllab_medico->sql_query("",$sCamposMedico,"",$sWhereMedico);
  $result        = $cllab_medico->sql_record( $sSqlMedico );

  if ( $cllab_medico->numrows > 0 ) {
    db_fieldsmemory($result,0);
  }

  $db_botao = true;
}

?>
<html>
<head>
<title>DBSeller Informática Ltda - Página Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("/widgets/dbautocomplete.widget.js");
db_app::load("webseller.js");

?>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">

  table.alinhaColuna tr td:first-child {
    width: 100px;
  }

</style>

</head>
<body class="body-default" >
  <?php
    require_once(modification("forms/db_frmlab_requisicao.php"));
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<?php
if ( isset($autorizar) ) {

  if ( $cllab_autoriza->erro_status == "0" ) {
    $cllab_autoriza->erro(true,false);
  } else {

    $cllab_autoriza->erro(true,false);
    db_redireciona("lab4_agendar001.php");
  }
}

if ( (isset($confirma)) || (isset($excluir)) ) {

  if ( $cllab_requisicao->erro_status == "0" ) {

    $cllab_requisicao->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ( $cllab_requisicao->erro_campo != "" ) {
      echo "<script> document.form1." . $cllab_requisicao->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $cllab_requisicao->erro_campo . ".focus();</script>";
    }
  } else {

    $cllab_requisicao->erro(true,false);

    if ( isset($confirma) ) {
      db_redireciona("lab4_agendar001.php?chavepesquisa=".$iRequisicao);
    } else {
      db_redireciona("lab4_agendar001.php");
    }
  }
}
?>