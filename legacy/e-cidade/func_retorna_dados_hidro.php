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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));

use ECidade\Tributario\Agua\Repository\Leitura as LeituraRepository;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoHidrometro = new cl_aguahidromatric;
$oDaoTrocaHidrometro = new cl_aguahidrotroca;
$oDaoLeitura = new cl_agualeitura;

$lSemHidrometro = false;

$sCamposHidrometro = "x04_codhidrometro, x04_nrohidro, x04_qtddigito, x03_nomemarca, x15_diametro";
$sSqlHidrometro = $oDaoHidrometro->sql_query_diametromarca(null, $sCamposHidrometro, "", "x04_matric = $matric");
$rsHihdrometro = $oDaoHidrometro->sql_record($sSqlHidrometro);

if ($oDaoHidrometro->numrows == 0) {
  $lSemHidrometro = true;
} else {

  db_fieldsmemory($rsHihdrometro, 0);
  $oDadosHidrometro = pg_fetch_object($rsHihdrometro);
  $result_troca = $oDaoTrocaHidrometro->sql_record($oDaoTrocaHidrometro->sql_query_file($x04_codhidrometro));

  if($oDaoTrocaHidrometro->numrows > 0) {
    $lSemHidrometro = true;
  } else {

    $x21_aguacontrato = AguaLeitura::getContratoPorMatricula($matric);
    echo "
	  <script>
	    parent.document.form1.x21_aguacontrato.value = '$x21_aguacontrato';
	    parent.document.form1.x21_codhidrometro.value  = '$x04_codhidrometro';
	    parent.document.form1.x04_nrohidro.value  = '$x04_nrohidro';
	    parent.document.form1.x04_qtddigito.value = '$x04_qtddigito';
      parent.document.getElementById('x21_leitura').setAttribute('maxlength', $x04_qtddigito);
	    parent.document.form1.x03_nomemarca.value = '$x03_nomemarca';
	    parent.document.form1.x15_diametro.value  = '$x15_diametro';
	    parent.document.form1.x21_aguacontrato.value = '$x21_aguacontrato';
	  </script>";

    $oRepository = new LeituraRepository();
    $oLeitura = $oRepository->findUltimaMesAno($matric, $mes, $exerc, $x21_aguacontrato);
    $oLeiturista = CgmRepository::getByCodigo($oLeitura->getCodigoLeiturista());

    if($oLeitura) {

      echo "
	    <script>
	      parent.document.form1.x21_situacant.value = '{$oLeitura->getSituacao()}';
	      parent.document.form1.x17_descrant.value  = '{$oLeitura->getSituacaoLeitura()->getDescricao()}';
	      parent.document.form1.x21_numcgmant.value = '{$oLeitura->getCodigoLeiturista()}';
	      parent.document.form1.z01_nomeant.value   = '{$oLeiturista->getNome()}';

	      parent.document.form1.x21_dtleituraant_dia.value = '{$oLeitura->getDataLeitura()->getDia()}';
	      parent.document.form1.x21_dtleituraant_mes.value = '{$oLeitura->getDataLeitura()->getMes()}';
	      parent.document.form1.x21_dtleituraant_ano.value = '{$oLeitura->getDataLeitura()->getAno()}';
	      parent.document.form1.x21_dtleituraant.value = '{$oLeitura->getDataLeitura()->getDate(DBDate::DATA_PTBR)}';

	      parent.document.form1.x21_leituraant.value = '{$oLeitura->getLeitura()}';
	      parent.document.form1.x21_consumoant.value = '{$oLeitura->getConsumo()}';
	      parent.document.form1.x21_excessoant.value = '{$oLeitura->getExcesso()}';
	      parent.document.form1.x21_saldoant.value = '{$oLeitura->getSaldo()}';
        parent.document.form1.x21_exercant.value = '{$oLeitura->getAno()}';
        parent.document.form1.x21_mesant.value = '{$oLeitura->getMes()}';
	    </script>
      ";
    }
  }
}

if ($lSemHidrometro) {

  echo "
  <script>
	  alert('Matrícula sem hidrômetro cadastrado.');
	  parent.document.form1.x04_matric.value = '';
	  parent.js_pesquisax04_matric(false);
	  parent.document.form1.x04_matric.focus();
	</script>";
}
