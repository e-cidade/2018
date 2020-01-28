<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

try {

  $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
  db_inicio_transacao();
  switch ($oParam->exec) {

    case 'getBancosConfigurados':

      $oRetorno->bancos = array();
      $aConfiguracoes   = ConfiguracaoConsignadoRepository::getConfiguracaoInstituicao($oInstituicao);
      $aBancos          = array_map(function(ConfiguracaoConsignado $oConfiguracao) use ($oRetorno) {

        /**
         * @todo ao implementar mais bancos retirar este if
         */
        if($oConfiguracao->getBanco()->getCodigo() != '104') {
          return null;  
        }

        $oBanco            = new \stdClass();
        $oBanco->banco     = (string)$oConfiguracao->getBanco()->getCodigo();
        $oBanco->descricao = urlencode($oConfiguracao->getBanco()->getNome());
        $oRetorno->bancos[] = $oBanco;
        return true;
      }, $aConfiguracoes);

      if (count($aBancos) == 0) {
        throw new BusinessException("N�o existem configura��es de consigna��es. Antes de importar configure em\nProcedimentos > Manuten��o de Empr�stimos Consignados > Par�metros > Configura��o Consignados.");
      }
      
      break;

    case 'importarArquivo':

      if (!isset($_FILES['aArquivoMovimento'])) {
        throw new ParameterException('Nenhum arquivo informado.');
      }

      if ($_FILES['aArquivoMovimento']['error'] !== UPLOAD_ERR_OK) {
        throw new FileException('Ocorreu um erro ao fazer envio do arquivo.');
      }

      $sNomeArquivo = 'tmp/'.$_FILES['aArquivoMovimento']['name'];
      move_uploaded_file($_FILES['aArquivoMovimento']['tmp_name'], $sNomeArquivo);
      $oBanco        = new Banco($oParam->banco);
      $oConfiguracao = ConfiguracaoConsignadoRepository::getConfiguracaoDoBancoNaInstituicao($oBanco, $oInstituicao);
      if (empty($oConfiguracao)) {
        throw new BusinessException('N�o foram encotradas configura��es para o banco informado');
      }
      $oCompetencia = DBPessoal::getCompetenciaFolha();
      $oImportacao  = ImportacaoArquivoConsignadoFactory::getByBanco($oBanco, $sNomeArquivo, $oCompetencia, $oInstituicao);
      $oImportacao->setConfiguracao($oConfiguracao);
      $oImportacao->processar();

      $oRetorno->sMessage = "Arquivo importado com sucesso.";
      unlink($sNomeArquivo);
      break;
  }
  db_fim_transacao(false);
}catch (Exception $eErro) {


  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

$oRetorno->sMessage = $oRetorno->sMessage;
echo $oJson->encode($oRetorno);