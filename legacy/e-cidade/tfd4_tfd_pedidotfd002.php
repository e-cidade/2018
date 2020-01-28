<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

// ABA TRATAMENTO
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));

db_postmemory($_POST);

$oDaoTfdPedidoTfd           = new cl_tfd_pedidotfd();
$oDaoTfdTipoTratamento      = new cl_tfd_tipotratamento();
$oDaoTfdTipoTransporte      = new cl_tfd_tipotransporte();
$oDaoTfdSituacaoPedidoTfd   = new cl_tfd_situacaopedidotfd();
$oDaoTfdDocumentosEntregues = new cl_tfd_documentosentregues();
$oDaoTfdProcPedidoTfd       = new cl_tfd_procpedidotfd();
$oDaoTfdProntPedidoTfd      = new cl_tfd_prontpedidotfd();
$oDaoTfdEncaminPedidoTfd    = new cl_tfd_encaminpedidotfd();
$oDaoTfdPedidoRegulado      = new cl_tfd_pedidoregulado();
$oDaoTfdParametros          = new cl_tfd_parametros();

$db_opcao         = 1;
$iDbOpcaoRegulado = 1;
$db_botao         = true;
$sRegulador       = "";
$lTemRegulador    = false;

function formataData($dData, $iTipo = 1) {

  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  }

 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;
}

if (isset($confirmar)) {

  db_inicio_transacao();

  /* Pedido */
  $oDaoTfdPedidoTfd->tf01_i_situacao    = 1;
  $oDaoTfdPedidoTfd->tf01_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoTfdPedidoTfd->tf01_c_horasistema = date('H:i');
  $oDaoTfdPedidoTfd->tf01_i_login       = db_getsession('DB_id_usuario');
  $oDaoTfdPedidoTfd->tf01_i_depto       = db_getsession('DB_coddepto');
  $oDaoTfdPedidoTfd->incluir($tf01_i_codigo);

  /* Documentos */
  $aDocumentos = explode(' ## ', $entregues);
  $iTam        = count($aDocumentos) - 1;
  for ($iCont = 0; $iCont < $iTam; $iCont++) {

    if ($oDaoTfdPedidoTfd->erro_status != '0') {

      $aInfo                                          = explode(',', $aDocumentos[$iCont]);
      $oDaoTfdDocumentosEntregues->tf22_i_pedidotfd   = $oDaoTfdPedidoTfd->tf01_i_codigo;
      $oDaoTfdDocumentosEntregues->tf22_i_documento   = $aInfo[0];
      $oDaoTfdDocumentosEntregues->tf22_d_dataentrega = formataData($aInfo[1]);
      $oDaoTfdDocumentosEntregues->tf22_c_horaentrega = date('H:i');
      $oDaoTfdDocumentosEntregues->tf22_c_numdoc      = $aInfo[2];
      $oDaoTfdDocumentosEntregues->incluir(null);

      if ($oDaoTfdDocumentosEntregues->erro_status == '0') {

        $oDaoTfdPedidoTfd->erro_status = '0';
        $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção dos Documentos: \\n\\n';
        $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdDocumentosEntregues->erro_msg;
      }
    }
  }

  /* Situação */
  $oDaoTfdSituacaoPedidoTfd->tf28_i_pedidotfd   = $oDaoTfdPedidoTfd->tf01_i_codigo;
  $oDaoTfdSituacaoPedidoTfd->tf28_i_situacao    = 1;
  $oDaoTfdSituacaoPedidoTfd->tf28_i_login       = db_getsession('DB_id_usuario');
  $oDaoTfdSituacaoPedidoTfd->tf28_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaoTfdSituacaoPedidoTfd->tf28_c_horasistema = date('H:i');
  $oDaoTfdSituacaoPedidoTfd->tf28_c_obs         = 'PEDIDO ATIVO';

  if ($oDaoTfdPedidoTfd->erro_status != '0') {

    $oDaoTfdSituacaoPedidoTfd->incluir(null);
    if ($oDaoTfdSituacaoPedidoTfd->erro_status == '0') {

      $oDaoTfdPedidoTfd->erro_status = '0';
      $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção da Situação: \\n\\n';
      $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdSituacaoPedidoTfd->erro_msg;
    }
  }

  /* Procedimentos */
  $iTam                                   = count($select_procedimento);
  $oDaoTfdProcPedidoTfd->tf23_i_pedidotfd = $oDaoTfdPedidoTfd->tf01_i_codigo;
  for ($iCont = 0; $iCont < $iTam; $iCont++) {

    $oDaoTfdProcPedidoTfd->tf23_i_procedimento = $select_procedimento[$iCont];
    if ($oDaoTfdPedidoTfd->erro_status != '0') {

      $oDaoTfdProcPedidoTfd->incluir(null);
      if ($oDaoTfdProcPedidoTfd->erro_status == '0') {

        $oDaoTfdPedidoTfd->erro_status = '0';
        $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção dos Procedimentos: \\n\\n';
        $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdProcPedidoTfd->erro_msg;
      }
    }
  }

  /* Prontuário */
  if (isset($tf29_i_prontuario) && !empty($tf29_i_prontuario)) {

    $oDaoTfdProntPedidoTfd->tf29_i_pedidotfd  = $oDaoTfdPedidoTfd->tf01_i_codigo;
    $oDaoTfdProntPedidoTfd->tf29_i_prontuario = $tf29_i_prontuario;

    if ($oDaoTfdPedidoTfd->erro_status != '0') {

      $oDaoTfdProntPedidoTfd->incluir(null);
      if ($oDaoTfdProntPedidoTfd->erro_status == '0') {

        $oDaoTfdPedidoTfd->erro_status = '0';
        $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção do Prontuário: \\n\\n';
        $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdProntPedidoTfd->erro_msg;
      }
    }
  }

  /* Encaminhamento */
  if (isset($tf30_i_encaminhamento) && !empty($tf30_i_encaminhamento)) {

    $oDaoTfdEncaminPedidoTfd->tf30_i_pedidotfd      = $oDaoTfdPedidoTfd->tf01_i_codigo;
    $oDaoTfdEncaminPedidoTfd->tf30_i_encaminhamento = $tf30_i_encaminhamento;

    if ($oDaoTfdPedidoTfd->erro_status != '0') {

      $oDaoTfdEncaminPedidoTfd->incluir(null);
      if ($oDaoTfdEncaminPedidoTfd->erro_status == '0') {

        $oDaoTfdPedidoTfd->erro_status = '0';
        $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção dos Encaminhamento: \\n\\n';
        $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdEncaminPedidoTfd->erro_msg;
      }
    }
  }

  $iEspecMedico = null;

  /* Incluir o regulador padrão */
  $sInner    = " inner join tfd_procpedidotfd on sd96_i_procedimento = tf23_i_procedimento";
  $sSubWhere = " sd96_i_cbo=rh70_sequencial  and tf23_i_pedidotfd = ".$oDaoTfdPedidoTfd->tf01_i_codigo;
  $sWhere    = " exists (select * from sau_proccbo $sInner where $sSubWhere)";
  $sSql      = $oDaoTfdParametros->sql_query("", "tf11_especmedico", "", $sWhere);
  $rs        = $oDaoTfdParametros->sql_record($sSql);

  if ($oDaoTfdParametros->numrows > 0) {

    $lTemRegulador = true;
    $iEspecMedico  = db_utils::fieldsmemory($rs, 0)->tf11_especmedico;
  } else {

    $lTemRegulador = false;
    $sSql          = $oDaoTfdParametros->sql_query("", "tf11_especmedico", "", "tf11_especmedico is not null");
    $rs            = $oDaoTfdParametros->sql_record($sSql);

    if ($oDaoTfdParametros->numrows > 0) {

      $lTemRegulador = true;
      $iEspecMedico  = db_utils::fieldsmemory($rs, 0)->tf11_especmedico;

      $sRegulador    = "Regulador padrão informado nos parâmetros não pode regular este pedidos,";
      $sRegulador   .= "pois sua especialidade não engloba todos os procedimentos deste pedido de TFD.";
    }
  }

  if( $lTemRegulador ) {

    $oDaoTfdPedidoRegulado->tf34_i_codigo      = null;
    $oDaoTfdPedidoRegulado->tf34_i_especmedico = $iEspecMedico;
    $oDaoTfdPedidoRegulado->tf34_i_pedidotfd   = $oDaoTfdPedidoTfd->tf01_i_codigo;
    $oDaoTfdPedidoRegulado->tf34_i_login       = db_getsession('DB_id_usuario');
    $oDaoTfdPedidoRegulado->tf34_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));;
    $oDaoTfdPedidoRegulado->tf34_c_horasistema = date('H:i');
    $oDaoTfdPedidoRegulado->incluir(null);

    if ($oDaoTfdPedidoRegulado->erro_status == '0') {

      $oDaoTfdPedidoTfd->erro_status = '0';
      $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção do Regulador: \\n\\n';
      $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdEncaminPedidoTfd->erro_msg;
    }
  }

  db_fim_transacao($oDaoTfdPedidoTfd->erro_status == '0' ? true : false);
}

if (isset($alterar)) {

  db_inicio_transacao();

  /* Pedido */
  $oDaoTfdPedidoTfd->tf01_i_codigo = $tf01_i_codigo;
  $oDaoTfdPedidoTfd->tf01_i_profissionalsolic = empty($tf01_i_profissionalsolic) ? 'null' : $tf01_i_profissionalsolic;
  $oDaoTfdPedidoTfd->alterar($tf01_i_codigo);


  if (isset($lProcedimentosAlterados) && $lProcedimentosAlterados == 'true' && $oDaoTfdPedidoTfd->erro_status != '0') {

    $sSql                              = $oDaoTfdProcPedidoTfd->sql_query2(null, ' tf23_i_codigo',
                                                                           ' sd63_c_nome ',
                                                                           ' tf23_i_pedidotfd = '.$tf01_i_codigo
                                                                          );
    $rs                                = $oDaoTfdProcPedidoTfd->sql_record($sSql);
    $iLinhas                           = $oDaoTfdProcPedidoTfd->numrows;
    $oDaoTfdProcPedidoTfd->erro_status = null; // faço isso porque se a busca não retornar resultado,
                                                // o erro_status é setado para 0, com a mensagem de record vazio
    /*
    * Laco que exclui todos os procedimentos já cadastrados
    */
    for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

      $oDados                              = db_utils::fieldsmemory($rs, $iCont);
      $oDaoTfdProcPedidoTfd->tf23_i_codigo = $oDados->tf23_i_codigo;
      $oDaoTfdProcPedidoTfd->excluir($oDados->tf23_i_codigo);
      if ($oDaoTfdProcPedidoTfd->erro_status == '0') {

        $oDaoTfdPedidoTfd->erro_status = '0';
        $oDaoTfdPedidoTfd->erro_msg    = $oDaoTfdProcPedidoTfd->erro_msg;
        break;
      }
    }

    /* Insere os procedimentos */
    $iTam                                   = count($select_procedimento);
    $oDaoTfdProcPedidoTfd->tf23_i_pedidotfd = $tf01_i_codigo;
    for ($iCont = 0; $iCont < $iTam; $iCont++) {

      $oDaoTfdProcPedidoTfd->tf23_i_procedimento = $select_procedimento[$iCont];
      if ($oDaoTfdPedidoTfd->erro_status != '0') {

        $oDaoTfdProcPedidoTfd->incluir(null);
        if ($oDaoTfdProcPedidoTfd->erro_status == '0') {

          $oDaoTfdPedidoTfd->erro_status = '0';
          $oDaoTfdPedidoTfd->erro_msg    = 'Problema na Inserção dos Procedimentos: \\n\\n';
          $oDaoTfdPedidoTfd->erro_msg   .= $oDaoTfdProcPedidoTfd->erro_msg;
        }
      }
    }
  }

  db_fim_transacao($oDaoTfdPedidoTfd->erro_status == '0' ? true : false);
}

if (isset($chavepesquisa)) {

  /* Carrego os dados do pedido para as globais */
  $sCampos  = 'tfd_pedidotfd.*, cgs_und.*, rhcbo.*, ';
  $sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmmedico.z01_nome else s154_c_nome end as z01_nome ';
  $sSql     = $oDaoTfdPedidoTfd->sql_query_protocolo($chavepesquisa, $sCampos);
  $rs       = $oDaoTfdPedidoTfd->sql_record($sSql);
  db_fieldsmemory($rs, 0);

  /* Verifico se o pedido já foi regulado para bloquear a alteração de procedimentos */
  $sSql = $oDaoTfdPedidoRegulado->sql_query_file(null, 'tf34_i_codigo', '', " tf34_i_pedidotfd = $chavepesquisa");
  $oDaoTfdPedidoRegulado->sql_record($sSql);
  if ($oDaoTfdPedidoRegulado->numrows > 0) {
    $iDbOpcaoRegulado = 3;
  }
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, webseller.js, /widgets/dbautocomplete.widget.js");
db_app::load("grid.style.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <fieldset style='width: 83%; padding-bottom: 1px;'> <legend><b>Pedido de Tratamento Fora do Município</b></legend>
	        <?
	        require_once(modification("forms/db_frmtfd_pedidotfd.php"));
          ?>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1", "tf01_i_cgsund", true, 1, "tf01_i_cgsund", true);
</script>
<?php
if ($sRegulador != '') {
  echo "<script>alert('$sRegulador');</script>";
}

if (isset($lRegulador)) {
  echo "<script>js_regulador();</script>";
}

if (isset($confirmar) || isset($alterar)) {

  if ($oDaoTfdPedidoTfd->erro_status == '0') {

    $oDaoTfdPedidoTfd->erro(true, false);
    $db_botao = true;
  } else {

    $oDaoTfdPedidoTfd->erro(true, false);
    if( !$lTemRegulador ) {
      $sRegulador = '&lRegulador=true';
    }

    db_redireciona('tfd4_tfd_pedidotfd002.php?chavepesquisa='.$oDaoTfdPedidoTfd->tf01_i_codigo);
  }
}