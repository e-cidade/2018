<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBDate.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson               = new Services_JSON();
$oParam              = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  switch($oParam->sExecucao) {

    /**
     * Retorna um array com os estados vinculados a um Pais
     * @param integer $oParam->iPais - Codigo do Pais
     * @return array stdClass
     *               -> iSequencial - Sequencial do Estado
     *               -> sDescricao  - Descricao do Estado
     */
    case 'getEstados':

      if (isset($oParam->iPais)) {

        $oRetorno->aEstados = array();
        $oPaisEndereco      = new PaisEndereco($oParam->iPais);
        foreach($oPaisEndereco->getEstadosVinculados() as $oEstado) {

          $oDadosEstado              = new stdClass();
          $oDadosEstado->iSequencial = $oEstado->getSequencial();
          $oDadosEstado->sDescricao  = urlencode($oEstado->getDescricao());
          $oDadosEstado->sSigla      = urlencode($oEstado->getSigla());
          $oRetorno->aEstados[]      = $oDadosEstado;
        }
      }
      break;

    /**
     * Retorna um array com os municipios vinculados a um Estado
     * @param integer $oParam->iEstado - Codigo do estado
     * @return array stdClass
     *               -> iSequencial - Sequencial do Municipio
     *               -> sDescricao  - Descricao do Municipio
     */
    case 'getMunicipios':

      if (isset($oParam->iEstado)) {

        $oRetorno->aMunicipios = array();
        $oEstado               = new Estado($oParam->iEstado);

        foreach($oEstado->getMunicipiosVinculados() as $oMunicipio) {

          $oDadosMunicipio              = new stdClass();
          $oDadosMunicipio->iSequencial = $oMunicipio->getSequencial();
          $oDadosMunicipio->sDescricao  = urlencode($oMunicipio->getDescricao());
          $oRetorno->aMunicipios[]      = $oDadosMunicipio;
        }
        
        usort($oRetorno->aMunicipios, "ordernarMunicipios");
      }
      break;

    /**
     * Retorna um array com os bairros vinculados a um Municipio
     * @param integer $oParam->iMunicipio - Codigo do municipio
     * @return array stdClass
     *               -> iSequencial - Sequencial do Bairro
     *               -> sDescricao  - Descricao do Bairro
     */
    case 'getBairros':

      if (isset($oParam->iMunicipio)) {

        $oRetorno->aBairros = array();
        $oMunicipio         = new Municipio($oParam->iMunicipio);

        foreach ($oMunicipio->getBairroVinculados() as $oBairro) {

          $oDadosBairro              = new stdClass();
          $oDadosBairro->iSequencial = $oBairro->getSequencial();
          $oDadosBairro->sDescricao  = urlencode($oBairro->getDescricao());
          $oRetorno->aBairros[]      = $oDadosBairro;
        }
      }
      break;

    /**
     * Salva um novo bairro
     * @param integer $oParam->iSequencial  - Codigo sequencial do bairro, caso setado (alteracao)
     * @param integer $oParam->iMunicipio   - Codigo do municipio
     * @param string  $oParam->sBairro      - Descricao do Bairro
     * @param string  $oParam->sSiglaBairro - Sigla do bairro
     */
    case 'salvarBairro':

      if (isset($oParam->iMunicipio)) {

        db_inicio_transacao();

        if (isset($oParam->iCodigoBairro)) {
          $oBairro = new Bairro($oParam->iCodigoBairro);
        } else {
          $oBairro = new Bairro();
        }

        $oBairro->setDescricao(mb_strtoupper(db_stdClass::normalizeStringJsonEscapeString($oParam->sBairro)));
        $oBairro->setSigla(mb_strtoupper(db_stdClass::normalizeStringJsonEscapeString($oParam->sSiglaBairro)));
        $oBairro->setMunicipio(new Municipio($oParam->iMunicipio));
        $oBairro->salvar();

        $oRetorno->sMensagem = urlencode("Bairro salvo com sucesso.");

        db_fim_transacao();
      }
      break;

    /**
     * Salvar um novo logradouro, vinculando este a um ou mais bairros
     * @param array   $oParam->aBairros         - Array com os codigos dos bairros a serem vinculados
     * @param string  $oParam->sLogradouro      - Descricao do logradouro
     * @param integer $oParam->iMunicipio - Codigo do municipio que o logradouro estara vinculado
     */
    case 'salvarLogradouro':

      if (isset($oParam->aBairros) && isset($oParam->sLogradouro) && isset($oParam->iMunicipio)) {

        db_inicio_transacao();

        if (isset($oParam->iLogradouro) && !empty($oParam->iLogradouro)) {
          $oLogradouro = new Logradouro($oParam->iLogradouro);
        } else {
          $oLogradouro = new Logradouro();
        }
        $oLogradouro->setDescricao(mb_strtoupper(db_stdClass::normalizeStringJsonEscapeString($oParam->sLogradouro)));
        $oLogradouro->setMunicipio(new Municipio($oParam->iMunicipio));
        $oLogradouro->salvar();

        foreach($oParam->aBairros as $oBairro) {
          $oLogradouro->adicionarBairro(new Bairro($oBairro->sCodigo));
        }

        $oRetorno->sMensagem = urlencode("Logradouro salvo com sucesso.");

        db_fim_transacao();
      }
      break;

    /**
     * Retorna um array com os dados dos logradouros vinculados a um bairro.
     * Pode receber como parametro o codigo do bairro, ou o codigo do municipio. Caso seja o codigo do municipio,
     * percorre os bairros vinculados e em seguida os logradouros vinculados a este bairro
     * @param integer $oParam->iBairro    - Codigo do bairro
     * @param integer $oParam->iMunicipio - Codigo do municipio
     * @return array stdClass
     *               -> iSequencial - Sequencial do Logradouro
     *               -> sDescricao  - Descricao do Logradouro
     */
    case 'getLogradouros':

      if (isset($oParam->iBairro)) {

        $oRetorno->aLogradouros = array();
        $oBairro                = new Bairro($oParam->iBairro);

        foreach ($oBairro->getLogradourosVinculados() as $oLogradouro) {

          $oDadosLogradouro              = new stdClass();
          $oDadosLogradouro->iSequencial = $oLogradouro->getSequencial();
          $oDadosLogradouro->sDescricao  = urlencode($oLogradouro->getDescricao());
          $oRetorno->aLogradouros[]      = $oDadosLogradouro;
        }
      } else if (isset($oParam->iMunicipio)) {

        $oRetorno->aLogradouros = array();
        $oMunicipio             = new Municipio($oParam->iMunicipio);

        foreach ($oMunicipio->getBairroVinculados() as $oBairro) {

          foreach ($oBairro->getLogradourosVinculados() as $oLogradouro) {

            $oDadosLogradouro              = new stdClass();
            $oDadosLogradouro->iSequencial = $oLogradouro->getSequencial();
            $oDadosLogradouro->sDescricao  = urlencode($oLogradouro->getDescricao());
            $oRetorno->aLogradouros[]      = $oDadosLogradouro;
          }
        }
      }
      break;

    /**
     * Retorna um array de stdClass com as informacoes sobre os bairros que um logradouro esta vinculado
     * @param integer $oParam->iLogradouro - Codigo do logradouro
     * @return array stdClass
     *               -> iSequencial - Sequencial do Bairro
     *               -> sDescricao  - Descricao do Bairro
     */
    case 'getBairroLogradouro':

      if ($oParam->iLogradouro) {

        $oRetorno->aBairros  = array();
        $oLogradouro         = new Logradouro($oParam->iLogradouro);

        foreach ($oLogradouro->getBairrosVinculados() as $oBairro) {

          $oDadosBairro = new stdClass();
          $oDadosBairro->iSequencial = $oBairro->getSequencial();
          $oDadosBairro->sDescricao  = urlencode($oBairro->getDescricao());
          $oRetorno->aBairros[]      = $oDadosBairro;
        }
      }
      break;

    /**
     * Retorna um objeto com as informacoes sobre o município que um bairro está vinculado
     * @param integer $oParam->iBairro - Codigo do bairro
     * @return int iMunicipio
     */
    case 'getMunicipioBairro':

      if ($oParam->iBairro) {

        $oBairro = new Bairro($oParam->iBairro);

        $oRetorno->iMunicipio = $oBairro->getMunicipio()->getSequencial();
      }
      break;

    /**
     * Retorna um objeto com as informacoes sobre o estado que um município está vinculado
     * @param integer $oParam->iMunicipio - Codigo do município
     * @return object Estado
     */
    case 'getEstadoMunicipio':

      if ($oParam->iMunicipio) {

        $oMunicipio = new Municipio($oParam->iMunicipio);

        $oRetorno->iEstado = $oMunicipio->getEstado()->getSequencial();
      }
      break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($oErro->getMessage());
}

/**
 * Ordena os municipios pela descricao
 * @param array $aArrayAtual
 * @param string $aProximoArray
 */
function ordernarMunicipios($aArrayAtual, $aProximoArray) {
  return strcasecmp(urldecode($aArrayAtual->sDescricao), urldecode($aProximoArray->sDescricao));
}

echo $oJson->encode($oRetorno);
?>