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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "dbforms/db_classesgenericas.php";

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveicmanutitem = new cl_veicmanutitem;
$clveicmanutitempcmater = new cl_veicmanutitempcmater;
$clveicmanut = new cl_veicmanut;

$db_opcao = 22;
$db_botao = false;
$lBloquearDesconto = false;

$oVeiculoManutencao = VeiculoManutencao::getInstanciaPorCodigo($ve63_veicmanut);

if(isset($alterar) || isset($excluir) || isset($incluir)){

  $sqlerro = false;

  if ($desconto > 100) {

    $erro_msg = "% de Desconto deve ser menor ou igual a 100.";
    $clveicmanutitem->erro_campo = 'desconto';
    $sqlerro = true;
  }

  if (!$sqlerro) {

    $clveicmanutitem->ve63_valortotalcomdesconto = ($ve63_quant * $ve63_vlruni);

    /**
     * Aplica o desconto quando o item for do tipo peça
     */
    if ($ve63_tipoitem == VeiculoManutencaoItem::TIPO_SERVICO_PECA) {

      $nValorDesconto = $oVeiculoManutencao->getPercentualDesconto();
      $desconto = $nValorDesconto ?: $desconto;
      $clveicmanutitem->ve63_valortotalcomdesconto *= 1 - ($desconto ? $desconto/100 : 0);
    }
  }

  /**
   * Validação das chaves estrangeiras
   */
  if(!empty($ve64_pcmater)) {

    $oDaoMaterial = new cl_pcmater;
    $sSqlMaterial = $oDaoMaterial->sql_query_file($ve64_pcmater);
    $rsMaterial   = $oDaoMaterial->sql_record($sSqlMaterial);
    if (!$rsMaterial || pg_num_rows($rsMaterial) == 0) {

      $erro_msg = "O valor informado para o campo Material é inválido.";
      $sqlerro  = true;
    }
  }
  if(!empty($ve63_unidade)) {

    $oDaoUnidade = new cl_matunid;
    $sSqlUnidade = $oDaoUnidade->sql_query_file($ve63_unidade);
    $rsUnidade   = $oDaoUnidade->sql_record($sSqlUnidade);
    if (!$rsUnidade || pg_num_rows($rsUnidade) == 0) {

      $erro_msg = "O valor informado para o campo Unidade é inválido.";
      $sqlerro  = true;
    }
  }
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clveicmanutitem->incluir($ve63_codigo);
    $erro_msg = $clveicmanutitem->erro_msg;
    if($clveicmanutitem->erro_status==0){
      $sqlerro=true;
    }
    if ($sqlerro==false){
    	if (isset($ve64_pcmater)&&$ve64_pcmater){
    		$clveicmanutitempcmater->ve64_veicmanutitem=$clveicmanutitem->ve63_codigo;
    		$clveicmanutitempcmater->incluir(null);
    		if($clveicmanutitempcmater->erro_status==0){
    			$erro_msg = $clveicmanutitempcmater->erro_msg;
      			$sqlerro=true;
    		}
    	}
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clveicmanutitem->alterar($ve63_codigo);
    $erro_msg = $clveicmanutitem->erro_msg;
    if($clveicmanutitem->erro_status==0){
      $sqlerro=true;
    }
    if ($sqlerro==false){
    	$result_mat=$clveicmanutitempcmater->sql_record($clveicmanutitempcmater->sql_query_file(null,"ve64_codigo",null,"ve64_veicmanutitem=$ve63_codigo"));
    	if (isset($ve64_pcmater)&&$ve64_pcmater){
    		if ($clveicmanutitempcmater->numrows>0){
    			db_fieldsmemory($result_mat,0);
    			$clveicmanutitempcmater->ve64_codigo=$ve64_codigo;
    			$clveicmanutitempcmater->alterar($ve64_codigo);
    			if($clveicmanutitempcmater->erro_status==0){
    				$erro_msg = $clveicmanutitempcmater->erro_msg;
      				$sqlerro=true;
    			}
    		}else{
    			$clveicmanutitempcmater->ve64_veicmanutitem=$clveicmanutitem->ve63_codigo;
    			$clveicmanutitempcmater->incluir(null);
    			if($clveicmanutitempcmater->erro_status==0){
    				$erro_msg = $clveicmanutitempcmater->erro_msg;
      				$sqlerro=true;
    			}
    		}
    	}else{
    		if ($clveicmanutitempcmater->numrows>0){
    			$clveicmanutitempcmater->excluir(null,"ve64_veicmanutitem=$ve63_codigo");
    			if($clveicmanutitempcmater->erro_status==0){
    				$erro_msg = $clveicmanutitempcmater->erro_msg;
      				$sqlerro=true;
    			}
    		}
    	}
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $result_mat=$clveicmanutitempcmater->sql_record($clveicmanutitempcmater->sql_query_file(null,"*",null,"ve64_veicmanutitem=$ve63_codigo"));
    if ($clveicmanutitempcmater->numrows>0){
     	$clveicmanutitempcmater->excluir(null,"ve64_veicmanutitem=$ve63_codigo");
    	if($clveicmanutitempcmater->erro_status==0){
    		$erro_msg = $clveicmanutitempcmater->erro_msg;
    		$sqlerro=true;
    	}
    }
    if ($sqlerro==false){
    	$clveicmanutitem->excluir($ve63_codigo);
    	$erro_msg = $clveicmanutitem->erro_msg;
    	if($clveicmanutitem->erro_status==0){
      		$sqlerro=true;
    	}
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clveicmanutitem->sql_record($clveicmanutitem->sql_query_pcmater($ve63_codigo));
   if($result!=false && $clveicmanutitem->numrows>0){
     db_fieldsmemory($result,0);
   }
}

/**
 * Preenche os valores totais e atualiza os mesmos ao incluir/alterar/excluir algum item
 */
if (!empty($ve63_veicmanut)) {

  if ((isset($alterar) || isset($excluir) || isset($incluir)) && !$sqlerro) {
    $oVeiculoManutencao->atualizarValores();
  }

  $oValores = $oVeiculoManutencao->getValoresAtualizados();

  $valor_mao_obra    = trim( db_formatar($oValores->getValorMaoDeObra(), 'f') );
  $valor_pecas       = trim( db_formatar($oValores->getValorPecas(), 'f') );
  $valor_lavagem     = trim( db_formatar($oValores->getValorLavagem(), 'f') );
  $numero_manutencao = $oVeiculoManutencao->getNumero() . "/" . $oVeiculoManutencao->getAno();

  $desconto            = !empty($desconto) ? $desconto : null;
  $nPercentualDesconto = $oVeiculoManutencao->getPercentualDesconto();
  $desconto            = ($nPercentualDesconto !== null) ? $nPercentualDesconto : $desconto;
  $lBloquearDesconto   = ($nPercentualDesconto !== null);
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  	<?php include "forms/db_frmveicmanutitem.php"; ?>
  </body>
</html>
<?php
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clveicmanutitem->erro_campo!=""){
        echo "<script> document.form1.".$clveicmanutitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clveicmanutitem->erro_campo.".focus();</script>";
    }
}
?>
