<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBseller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilha as InicialPartilhaEntity;
use ECidade\Tributario\Juridico\InicialPartilha\InicialPartilhaCustas as InicialPartilhaCustasEntity;
use ECidade\Tributario\Juridico\Repository\Inicial as InicialRepository;
use ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilha as InicialPartilhaRepository;
use ECidade\Tributario\Juridico\InicialPartilha\Repository\InicialPartilhaCustas as InicialPartilhaCustasRepository;
use ECidade\Tributario\Arrecadacao\Repository\Taxa as TaxaRepository;

$oJson = new services_json(0, true);
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->erro = false;
$oRetorno->sMessage = null;

try {

    switch ($oParametros->sExecucao) {

        case "getDadosInicialTaxa":

            $lDebitoPago = false;
            $lReciboEmitido = false;
            $aTaxas = array();

            $oInicialRepository = new InicialRepository();

            $lDebitoPago = $oInicialRepository->isDebitoPago($oParametros->iInicial);

            if ($lDebitoPago == false) {

                $oInicialPartilhaRepository = InicialPartilhaRepository::getInstance();
                $oInicialPartilha = $oInicialPartilhaRepository->getUltimaByInicial($oParametros->iInicial);

                $oCollectionInicialPartilhaCustas = array();

                if (!empty($oInicialPartilha)) {

                    $iCodigoInicialPartilha = $oInicialPartilha->getCodigo();

                    $oInicialPartilhaCustasRepository = InicialPartilhaCustasRepository::getInstance();
                    $oCollectionInicialPartilhaCustas = $oInicialPartilhaCustasRepository->getByInicialPartilha($iCodigoInicialPartilha);
                }

                $oTaxaRepository = TaxaRepository::getInstance();
                $oCollectionTaxas = $oTaxaRepository->getTodasSemProcesso();

                foreach ($oCollectionTaxas as $oTaxa) {

                    $lDispensaLancamentoRecibo = false;
                    
                    foreach ($oCollectionInicialPartilhaCustas as $oInicialPartilhaCustas) {

                        if ($oInicialPartilhaCustas->isDispensaLancamentoRecibo() == 'f' && 
                            $oInicialPartilhaCustas->getCodigoTaxa() == $oTaxa->getCodigoTaxa()
                        ) {
                            $lDispensaLancamentoRecibo = true;
                        }
                    }

                    $aTaxas[] = array(
                        "iCodigoTaxa" => $oTaxa->getCodigoTaxa(),
                        "sDescricao" => $oTaxa->getDescricao(),
                        "lChecked" => $lDispensaLancamentoRecibo
                    );
                }

                $lReciboEmitido = $oInicialRepository->isReciboEmitidoDebito($oParametros->iInicial);
            }

            $oDados = new stdClass();
            $oDados->aTaxas = $aTaxas;
            $oDados->lDebitoPago = $lDebitoPago;
            $oDados->lReciboEmitido = $lReciboEmitido;

            $oRetorno->oDados = DBString::utf8_encode_all($oDados);

            break;

        case "processaInicialTaxaIsencao":

            $oInicialPartilhaRepository = InicialPartilhaRepository::getInstance();
            $oInicialPartilha = $oInicialPartilhaRepository->getUltimaByInicial($oParametros->iInicial);

            $iCodigoInicialPartilha = null;

            if (!empty($oInicialPartilha)) {
                $iCodigoInicialPartilha = $oInicialPartilha->getCodigo();
            }

            $oInicialPartilhaEntity = new InicialPartilhaEntity();

            $oInicialPartilhaEntity->setCodigo((int)$iCodigoInicialPartilha);
            $oInicialPartilhaEntity->setCodigoInicial((int)$oParametros->iInicial);
            $oInicialPartilhaEntity->setValorPartilha(0);
            $oInicialPartilhaEntity->setDataPartilha(new DateTime(date('Y-m-d', db_getsession('DB_datausu'))));
            
            $lTipoLancamento = 1;

            foreach ($oParametros->aTaxas as $iIndiceTaxa => $oTaxa) {

                $iCodigoInicialPartilhaCusta = null;
                $fInicialPartilhaCustaValor = 0;

                if (!empty($iCodigoInicialPartilha)) {

                    $oInicialPartilhaCustasRepository = InicialPartilhaCustasRepository::getInstance();
                    $oInicialPartilhaCusta = $oInicialPartilhaCustasRepository->getByCustaInicialPartilha($oTaxa->iTaxa, $iCodigoInicialPartilha);

                    if (!empty($oInicialPartilhaCusta)) {
                        $iCodigoInicialPartilhaCusta = $oInicialPartilhaCusta->getCodigo();
                        $fInicialPartilhaCustaValor = $oInicialPartilhaCusta->getValor();
                    }
                }

                if ((boolean)$oTaxa->lIsencao === true) {
                    $lTipoLancamento = 3;
                }

                $oCustasEntity = new InicialPartilhaCustasEntity();

                $oCustasEntity->setCodigo($iCodigoInicialPartilhaCusta);
                $oCustasEntity->setCodigoTaxa($oTaxa->iTaxa);
                $oCustasEntity->setValor($fInicialPartilhaCustaValor);
                $oCustasEntity->setDispensaLancamentoRecibo((boolean)$oTaxa->lIsencao);

                $oInicialPartilhaEntity->addCustas($oCustasEntity);
            }

            $oInicialPartilhaEntity->setTipoLancamento($lTipoLancamento);

            $oInicialPartilhaRepository->persist($oInicialPartilhaEntity);

            break;
    }

} catch (Exception $oErro) {

    $oRetorno->erro = true;
    $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

echo $oJson->encode($oRetorno);