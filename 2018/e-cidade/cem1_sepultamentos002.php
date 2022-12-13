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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_sepultamentos_classe.php"));
include(modification("classes/db_renovacoes_classe.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clsepultamentos = new cl_sepultamentos;
$clrenovacoes 	 = new cl_renovacoes;
$db_opcao 			 = 1;
$db_botao 			 = true;
$lErro           = false;
$sMsgErro        = "AVISO!\\nCGM informado já Sepultado.";

  /*
  * Verifica se o sepultamente já está cadastrado
  */
  if(isset($cm01_i_codigo)) {

    $rsResult = $clsepultamentos->sql_record($clsepultamentos->sql_query_file($cm01_i_codigo));
    if($rsResult) {

      if($clsepultamentos->numrows > 0) {
       $lErro = true;
      }
    }
  }

  /**
   * Verificamos se o sepultado é pessoa juridica
   */
  $oCgmSepultado = CgmFactory::getInstanceByCgm($cm01_i_codigo);
  if(!$oCgmSepultado->isFisico()) {

    $lErro    = true;
    $sMsgErro = "Sepultado informado não pode ser Pessoa Jurídica.";
  }

  /**
   * Verificamos se o declarante é pessoa juridica
   */
  if(!empty($cm01_i_declarante)) {

    $oCgmDeclarante = CgmFactory::getInstanceByCgm($cm01_i_declarante);

    if(!$oCgmDeclarante->isFisico()) {

      $lErro    = true;
      $sMsgErro = "Declarante informado não pode ser Pessoa Jurídica.";
    }
  }

  $oDataHoje = new DBDate(date('d/m/Y'));
  $oDataFalecimento = new DBDate($cm01_d_falecimento);

  if(strtotime($oDataHoje->getDate()) < strtotime($oDataFalecimento->getDate())) {

    $lErro    = true;
    $sMsgErro = "Data de Falecimento não pode ser maior que a data atual.";
  }

  if($lErro) {

    db_msgbox($sMsgErro);
    echo "<script>
            parent.document.formaba.a2.disabled=true;
            parent.document.formaba.a3.disabled=true;
            parent.mo_camada('a1');
          </script>";
  }

 //resgata os valores
 $clsepultamentos->cm01_i_medico        = (isset($cm01_i_medico) ? $cm01_i_medico : null);
 $clsepultamentos->cm01_c_nomemedico    = (isset($cm32_nome) ? $cm32_nome : null);
 $clsepultamentos->cm01_c_nomehospital  = (isset($nome_hospital) ? $nome_hospital : null);
 $clsepultamentos->cm01_c_nomefuneraria = (isset($nome_funeraria) ? $nome_funeraria : null);
 $clsepultamentos->cm01_i_cemiterio     = $cm01_i_cemiterio;
 $clsepultamentos->cm01_c_conjuge       = $cm01_c_conjuge;
 $clsepultamentos->cm01_c_cor           = $cm01_c_cor;
 $clsepultamentos->cm01_d_falecimento   = $cm01_d_falecimento;
 $clsepultamentos->cm01_observacoes     = urldecode($cm01_observacoes);
 $clsepultamentos->cm01_d_cadastro      = date("Y-m-d",db_getsession("DB_datausu"));
 $clsepultamentos->cm01_i_funcionario   = db_getsession("DB_id_usuario");

if(isset($incluir) && !$lErro) {

  db_inicio_transacao();
  $clsepultamentos->incluir($cm01_i_codigo);
  db_fim_transacao();

  //cadastra em renovacoes
  $clrenovacoes->cm07_i_sepultamento = $clsepultamentos->cm01_i_codigo;
  $clrenovacoes->cm07_i_renovante    = $clsepultamentos->cm01_i_declarante;
  $clrenovacoes->cm07_d_vencimento   = $cm07_d_vencimento_ano."-".$cm07_d_vencimento_mes."-".$cm07_d_vencimento_dia;
  $clrenovacoes->cm07_d_ultima       = $clrenovacoes->cm07_d_vencimento;
  $clrenovacoes->incluir(null);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
<?php
  if(isset($incluir) && !$lErro) {

    if($clsepultamentos->erro_status == "0") {

      db_msgbox($clsepultamentos->erro_msg);
      $db_botao = true;

      echo "<script>document.form1.db_opcao.disabled=false;</script>";

      if($clsepultamentos->erro_campo != "") {
        echo "<script> document.form1.".$clsepultamentos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clsepultamentos->erro_campo.".focus();</script>";
      }

    } else {

    db_msgbox($clrenovacoes->erro_msg);
  ?>
    <script>
      parent.document.formaba.a2.disabled = true;
      parent.document.formaba.a3.disabled = false;
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href='cem1_sepultamentos003.php?sepultamento=<?=$cm01_i_codigo?>&cemiterio=<?=$cm01_i_cemiterio?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href='cem1_itenserv001.php?cm31_i_sepultamento=<?=$cm01_i_codigo?>&cm01_i_declarante=<?=$cm01_i_declarante?>&tp=1';
      parent.mo_camada('a3');
    </script>
  <?php
    }
  }
?>
