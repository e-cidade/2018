<?php
/**
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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));

use ECidade\Tributario\Agua\Repository\Leitura as LeituraRepository;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clagualeitura = new cl_agualeitura;
$clagualeiturasaldoutilizado = new cl_agualeiturasaldoutilizado();

$db_opcao = 22;
$db_botao = false;
$naltera = false;
$sqlerro = false;

if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clagualeiturasaldoutilizado->excluir(null, "x34_agualeitura = $x21_codleitura");

  if ( $clagualeiturasaldoutilizado->erro_status == 0 ) {

    $erro_msg = $clagualeiturasaldoutilizado->erro_msg;
    $sqlerro = true;
  }

  $clagualeitura->x21_aguacontrato  = AguaLeitura::getContratoPorMatricula($x04_matric);
  $clagualeitura->alterar($x21_codleitura);
  if ( $clagualeitura->erro_status == 0 ) {

    $erro_msg = $clagualeitura->erro_msg;
    $sqlerro = true;
  }

  // Registra ocorrencia de ajuste de leitura com periodo maior que 30 dias
  if ( $leitura_base_ajuste && $sqlerro == false ) {

    $clhistocorrencia                    = new cl_histocorrencia;

    $sDataBaseAjuste                     = "{$dtleitura_base_ajuste_dia}/";
    $sDataBaseAjuste                    .= "{$dtleitura_base_ajuste_mes}/";
    $sDataBaseAjuste                    .= "{$dtleitura_base_ajuste_ano}";

    $sDataAjustada                       = "{$x21_dtleitura_dia}/{$x21_dtleitura_mes}/{$x21_dtleitura_ano}";

    $sOcorrencia                         = "Ajustada leitura de {$leitura_base_ajuste} ";
    $sOcorrencia                        .= "com data de {$sDataBaseAjuste} para ";
    $sOcorrencia                        .= "{$x21_leitura} e data {$sDataAjustada}.";

    $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
    $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
    $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
    $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
    $clhistocorrencia->ar23_descricao    = "Adequação Leitura Maior 30 Dias";
    $clhistocorrencia->ar23_ocorrencia   = $sOcorrencia;
    $clhistocorrencia->ar23_tipo         = 1;
    $clhistocorrencia->ar23_hora         = date("H:i");
    $clhistocorrencia->ar23_data         = date("d")."/".date("m")."/".date("Y");

    $clhistocorrencia->incluir(null);

    if ( $clhistocorrencia->erro_status == 0 ) {

      $erro_msg = $clhistocorrencia->erro_msg;
      $sqlerro = true;

    } else {

      $clhistocorrenciamatric = new cl_histocorrenciamatric;

      $clhistocorrenciamatric->ar25_matric         = $x04_matric;
      $clhistocorrenciamatric->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;

      $clhistocorrenciamatric->incluir(null);

      if ( $clhistocorrenciamatric->erro_status == 0 ) {

        $erro_msg = $clhistocorrencia->erro_msg;
        $sqlerro = true;
      }

      if ($erro_msg != "") {
        db_msgbox($erro_msg);
      }

    }

  }
  // Fim registra ocorrencia
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clagualeitura->sql_record(
                                        $clagualeitura->sql_query_dados(
                                          $chavepesquisa,
                        "
                   x21_codleitura,
                   x21_codhidrometro,
                   x21_aguacontrato,
                         x21_exerc,
                         x21_mes,
                         x04_matric,
                         x01_numcgm,
                         x01_codrua,
                         x01_numero,
                         x01_letra,
                         x01_zona,
                         x01_qtdeconomia,
                         case when x01_multiplicador = 'f' then 'Não' else 'Sim' end as x01_multiplicador,
                         x04_nrohidro,
                         x04_qtddigito,
                         x03_nomemarca,
                         x15_diametro,
                         x21_situacao,
                         x17_descr,
                         x21_numcgm,
                         cgm.z01_nome,
                         x21_dtleitura,
                         x21_leitura,
                         x21_consumo,
                         x21_excesso,
                   a.z01_nome as z01_nomedad,
                   j14_nome, 
                   x21_tipo,
                   x21_status, 
                   x21_saldo
                        "
                       )
                     );
   db_fieldsmemory($result,0);

  $oRepository = new LeituraRepository();
  $oLeitura = $oRepository->findUltimaMesAno($x04_matric, $x21_mes, $x21_exerc, $x21_aguacontrato, $x21_codleitura);

  if ($oLeitura) {

    $x21_situacant = $oLeitura->getSituacao();
    $x17_descrant = $oLeitura->getSituacaoLeitura()->getDescricao();
    $x21_numcgmant = $oLeitura->getCodigoLeiturista();
    $oLeiturista = CgmRepository::getByCodigo($oLeitura->getCodigoLeiturista());
    $z01_nomeant = $oLeiturista->getNome();
    $x21_dtleituraant_dia = $oLeitura->getDataLeitura()->getDia();
    $x21_dtleituraant_mes = $oLeitura->getDataLeitura()->getMes();
    $x21_dtleituraant_ano = $oLeitura->getDataLeitura()->getAno();
    $x21_dtleituraant = $oLeitura->getDataLeitura()->getDate(DBDate::DATA_PTBR);
    $x21_leituraant = $oLeitura->getLeitura();
    $x21_consumoant = $oLeitura->getConsumo();
    $x21_excessoant = $oLeitura->getExcesso();
    $x21_saldoant = $oLeitura->getSaldo();
    $x21_exercant = $oLeitura->getAno();
    $x21_mesant = $oLeitura->getMes();
  }

  $sOrder = implode(', ', array(
    'x21_exerc desc',
    'x21_mes desc',
    'x21_dtleitura desc',
    'x21_dtinc desc',
    'x21_codleitura desc',
  ));
  $sWhere = "x21_codhidrometro=$x21_codhidrometro and fc_anousu_mesusu(x21_exerc, x21_mes) > fc_anousu_mesusu({$x21_exerc}, {$x21_mes})";
  $sSqlLeiturasPosteriores = $clagualeitura->sql_query_file(null, "*", "{$sOrder} limit 1", $sWhere);
  $result_leituraant = $clagualeitura->sql_record($sSqlLeiturasPosteriores);

  if ($clagualeitura->numrows > 0) {
    $naltera = true;
  }

  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

<div class="container">
<?php require_once(modification("forms/db_frmagualeitura.php")) ?>
</div>

<?php db_menu() ?>
</body>
</html>
<?php
if (isset($alterar)) {

  if ($clagualeitura->erro_status == "0") {

    $clagualeitura->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clagualeitura->erro_campo != "") {
      echo "<script> document.form1.".$clagualeitura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clagualeitura->erro_campo.".focus();</script>";
    }
  } else {
    $clagualeitura->erro(true,true);
  }
}

if ($db_opcao==22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}

if ($naltera == true) {
  db_msgbox("Atenção: existem leituras posteriores à {$chavepesquisa} cadastradas.\\n\\nEsta alteração poderá gerar inconsistência nas próximas leituras.\\nVerifique.");
}
