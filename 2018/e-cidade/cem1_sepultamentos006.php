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
require_once(modification("classes/db_sepultamentos_classe.php"));
require_once(modification("classes/db_renovacoes_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clsepultamentos = new cl_sepultamentos;
$clrenovacoes 	 = new cl_renovacoes;
$lErro           = false;

$sAuxCm01_c_nomemedico    = (isset($cm32_nome) ? $cm32_nome : null);
$sAuxCm01_c_nomehospital  = (isset($nome_hospital) ? $nome_hospital : null);
$sAuxCm01_c_nomefuneraria = (isset($nome_funeraria) ? $nome_funeraria : null);

if (isset($chavepesquisa)) {

  /**
   * Verificamos se o sepultado é pessoa juridica
   */
  $oCgmSepultado = CgmFactory::getInstanceByCgm($chavepesquisa);
  if (!$oCgmSepultado->isFisico()) {

    $lErro    = true;
    $sMsgErro = "Sepultado informado não pode ser Pessoa Jurídica.";
  }

  if (!empty($cm01_i_declarante)) {

    $oCgmDeclarante = CgmFactory::getInstanceByCgm($cm01_i_declarante);
    if (!$oCgmDeclarante->isFisico()) {

      $lErro    = true;
      $sMsgErro = "Declarante informado não pode ser Pessoa Jurídica.";
    }
  }

  $oDataHoje = new DBDate(date('d/m/Y'));
  $oDataFalecimento = new DBDate($cm01_d_falecimento);

  if (strtotime($oDataHoje->getDate()) < strtotime($oDataFalecimento->getDate())) {

    $lErro    = true;
    $sMsgErro = "Data de Falecimento não pode ser maior que a data atual.";
  }

  $sCampos  = " cm01_c_livro, ";
  $sCampos .= " cm01_i_folha, ";
  $sCampos .= " cm01_i_registro, ";
  $sCampos .= " cm01_i_medico, ";
  $sCampos .= " cm01_i_causa, ";
  $sCampos .= " cm04_c_descr, ";
  $sCampos .= " cm01_c_local, ";
  $sCampos .= " cm01_c_cartorio, ";
  $sCampos .= " cm01_i_hospital, ";
  $sCampos .= " case ";
  $sCampos .= "   when cgm1.z01_nome is not null then ";
  $sCampos .= "     cgm1.z01_nome ";
  $sCampos .= "   else ";
  $sCampos .= "     cm01_c_nomehospital ";
  $sCampos .= " end as nome_hospital, ";
  $sCampos .= " cm01_i_funeraria, ";
  $sCampos .= " case ";
  $sCampos .= "   when cgm2.z01_nome is not null then ";
  $sCampos .= "     cgm2.z01_nome ";
  $sCampos .= "   else ";
  $sCampos .= "     cm01_c_nomefuneraria ";
  $sCampos .= " end as nome_funeraria, ";
  $sCampos .= " cm01_i_declarante, ";
  $sCampos .= " cgm3.z01_nome as nome_declarante, ";
  $sCampos .= " case ";
  $sCampos .= "   when cgm4.z01_nome is not null then ";
  $sCampos .= "     cgm4.z01_nome ";
  $sCampos .= "   else ";
  $sCampos .= "     cm01_c_nomemedico ";
  $sCampos .= " end as cm32_nome ";

  $result = $clsepultamentos->sql_record($clsepultamentos->sql_query($chavepesquisa, $sCampos));
  db_fieldsmemory($result, 0);

  if ($lErro) {

    db_msgbox($sMsgErro);
    echo "<script>
            parent.document.formaba.a2.disabled=true;
            parent.document.formaba.a3.disabled=true;
            parent.mo_camada('a1');
          </script>";
  }

  $db_opcao = 2;
  $db_botao = true;

  //resgata os valores
  $clsepultamentos->cm01_i_codigo      = $cm01_i_codigo;
  $clsepultamentos->cm01_i_cemiterio   = $cm01_i_cemiterio;
  $clsepultamentos->cm01_c_conjuge     = $cm01_c_conjuge;
  $clsepultamentos->cm01_c_cor         = $cm01_c_cor;
  $clsepultamentos->cm01_d_falecimento = $cm01_d_falecimento;
  $clsepultamentos->cm01_observacoes   = $cm01_observacoes;
}

if (isset($alterar) && !$lErro) {

  $clsepultamentos->cm01_c_nomemedico    = $sAuxCm01_c_nomemedico;
  $clsepultamentos->cm01_c_nomehospital  = $sAuxCm01_c_nomehospital;
  $clsepultamentos->cm01_c_nomefuneraria = $sAuxCm01_c_nomefuneraria;

  db_inicio_transacao();
  $clsepultamentos->alterar($cm01_i_codigo);
  db_fim_transacao();
}
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="expires" CONTENT="0">
  <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="abas">
  <div class="container">
    <?php
      include(modification("forms/db_frmsepultamentos1.php"));
    ?>
  </div>
</body>
</html>
<?
if(isset($alterar) && !$lErro) {

  if($clsepultamentos->erro_status == "0") {

    db_msgbox($clsepultamentos->erro_msg);

    $db_botao = true;

    echo "<script>";
    echo "  document.form1.db_opcao.disabled=false;";
    echo "</script>";

    if($clsepultamentos->erro_campo != "") {
      echo "<script> document.form1.".$clsepultamentos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsepultamentos->erro_campo.".focus();</script>";
    }

  } else {

    db_msgbox($clsepultamentos->erro_msg);
    echo "<script>";
    echo " parent.document.formaba.a2.disabled=true;";
    echo " parent.document.formaba.a3.disabled=false;";
    echo " (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href='cem1_sepultamentos003.php?db_opcao=2&sepultamento={$cm01_i_codigo}';";
    echo " parent.mo_camada('a3'); ";
    echo "</script>";
  }
}
?>