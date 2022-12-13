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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oJson  = new Services_JSON();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "gerarArquivos":

      if (empty($oParam->aArquivos)) {
        throw new Exception("Nenhum arquivo informado para geração.");
      }

      $oInstituicao = new Instituicao(db_getsession("DB_instit"));
      $oDataAtual = new DBDate( date('Y-m-d', db_getsession("DB_datausu")) );
      $oDataInicial = new DBDate( "{$oDataAtual->getAno()}-01-01" );
      $oDataFinal = new DBDate( "{$oDataAtual->getAno()}-{$oDataAtual->getMes()}-" . DBDate::getQuantidadeDiasMes($oDataAtual->getMes(), $oDataAtual->getAno()) );

      $oCabecalho = new CabecalhoLicitaCon();
      $oCabecalho->setInstituicao($oInstituicao);
      $oCabecalho->setDataFinal($oDataFinal);
      $oCabecalho->setDataGeracao($oDataAtual);
      $oCabecalho->setDataInicial($oDataInicial);

      $oRetorno->aArquivos = array();

      $oArquivoCompactado = new ZipArchive();

      $open = $oArquivoCompactado->open("tmp/LicitaCon.zip", ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
      if ($open !== true) {
        throw new Exception("Erro ao gerar arquivo compactado.");
      }

      foreach ($oParam->aArquivos as $sArquivo) {

        $oArquivoGeracao = ArquivoLicitaConFactory::getArquivo($sArquivo, $oCabecalho);
        $oArquivoGerado  = $oArquivoGeracao->gerar();

        $oRetorno->aArquivos[] = (object) array(
            'name' => $oArquivoGerado->getBaseName(),
            'path' => $oArquivoGerado->getFilePath()
          );

        if (!$oArquivoCompactado->addFile($oArquivoGerado->getFilePath(), $oArquivoGerado->getBaseName())) {
          throw new Exception("Erro ao compactar arquivo {$sArquivo}.");
        }

        /**
         * Compacta os anexos correspondentes a cada arquivo
         */
        foreach ($oArquivoGeracao->getAnexos() as $iOID => $sArquivo) {

          $sPrefixoCaminho   = 'tmp/';
          $sCaminhoFinal     = $sPrefixoCaminho . $sArquivo;
          $sArquivo          = str_replace('\\', '/', $sArquivo);
          $lArquivoExportado = pg_lo_export($iOID, $sCaminhoFinal);
          if (!$lArquivoExportado) {
            throw new DBException("Não foi possível exportar o arquivo {$sArquivo}.");
          }

          if (!$oArquivoCompactado->addFile($sCaminhoFinal, $sArquivo)) {
            throw new Exception("Erro ao compactar documentos do arquivo {$sArquivo}.");
          }
        }
      }

      $oArquivoCompactado->close();
      $oRetorno->oArquivoCompactado = (object) array(
          'name' => "LicitaCon.zip",
          'path' => "tmp/LicitaCon.zip"
        );

    break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  $oRetorno->erro     = true;
  $oRetorno->mensagem = $e->getMessage();
  db_fim_transacao(true);
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);