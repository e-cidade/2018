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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

use ECidade\Tributario\Agua\EmissaoCarnes\Parcial;
use ECidade\Tributario\Agua\EmissaoCarnes\Processamento;
use ECidade\Tributario\Agua\Configuracao;

$oParam   = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = (object) array(
  'mensagem' => null,
  'erro'     => false
);

try {

	switch ($oParam->exec) {

    case 'emissaoParcial':

      db_inicio_transacao();

      if (empty($oParam->iContrato)) {
        throw new ParameterException('Código do Contrato não informado.');
      }

      if (empty($oParam->iAno)) {
        throw new ParameterException('Ano não informado.');
      }

      if (empty($oParam->iMesInicial) || empty($oParam->iMesFinal)) {
        throw new ParameterException('Mês Inicial/Final não informado.');
      }

      if ($oParam->iMesInicial > $oParam->iMesFinal) {
        throw new ParameterException('Mês Inicial não pode ser maior que Mês Final.');
      }

      $oConfiguracao = Configuracao::create();
      $oDataEmissao = new DateTime(date('Y-m-d', db_getsession('DB_datausu')));

      $oRegraEmissao = new regraEmissao(
        $oConfiguracao->getCodigoTipoArrecadacao(),
        $iModeloEmissaoTXT = 7,
        $oConfiguracao->getCodigoInstituicao(),
        $oDataEmissao->format('Y-m-d'),
        db_getsession('DB_ip')
      );

      $oEmissaoAguaModel = new AguaEmissao();
      $oEmissaoAguaModel->removerTabelaTemporaria();
      $oEmissaoAguaModel->criarTabelaTemporaria();

      $oContrato = new AguaContrato($oParam->iContrato);
      $rsInformacoesEmissao = $oEmissaoAguaModel->getInformacoesEmissao($oParam->iContrato);

      $sNomeArquivoEmissao = "{$oParam->iContrato}_{$oDataEmissao->getTimestamp()}";
      $sNomeArquivoEmissao = "tmp/emissao_parcial_$sNomeArquivoEmissao.txt";
      $sNomeArquivoLayout = "tmp/emissao_parcial_layout.txt";

      $oProcessamento = new Processamento($sNomeArquivoEmissao, $sNomeArquivoLayout);

      while ($oInformacoesEmissao = pg_fetch_object($rsInformacoesEmissao)) {

        $oEmissao = new Parcial();
        $oEmissao->setCodigoContrato($oParam->iContrato);
        $oEmissao->setContrato($oContrato);
        $oEmissao->setAno($oParam->iAno);
        $oEmissao->setMesInicial($oParam->iMesInicial);
        $oEmissao->setMesFinal($oParam->iMesFinal);
        $oEmissao->setCodigoInstituicao($oConfiguracao->getCodigoInstituicao());
        $oEmissao->setCodigoTipoArrecadacao($oConfiguracao->getCodigoTipoArrecadacao());

        $oEmissao->setAguaEmissao($oEmissaoAguaModel);
        $oEmissao->setRegraEmissao($oRegraEmissao);
        $oEmissao->setDataEmissao($oDataEmissao);
        $oEmissao->setInformacoesEmissao($oInformacoesEmissao);

        $oRegistro = $oEmissao->emitir();
        $oProcessamento->escrever($oRegistro);
      }
      $oProcessamento->finalizar();
      $oEmissaoAguaModel->removerTabelaTemporaria();

      $oRetorno->aArquivos = array(
        (object) array(
          'nome' => 'Emissão_Parcial',
          'link' => $sNomeArquivoEmissao
        ),
        (object) array(
          'nome' => 'Layout_Emissao',
          'link' => $sNomeArquivoLayout
        )
      );

      $oRetorno->mensagem = 'Emissão Concluída.';
      db_fim_transacao();
      break;

    default:
      throw new Exception('Opção é inválida.');
  }

} catch (Exception $exception) {

    db_fim_transacao($lErro = true);

    $oRetorno->mensagem = $exception->getMessage();
    $oRetorno->erro     = $lErro;
}

echo JSON::create()->stringify($oRetorno);
