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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification('classes/db_db_cadattdinamicoatributosvalor_classe.php'));
require_once(modification('classes/db_db_cadattdinamicovalorgrupo_classe.php'));

require_once(modification('model/DBAttDinamico.model.php'));
require_once(modification('model/DBAttDinamicoAtributo.model.php'));

$oJson  = \JSON::create();
$oParam = $oJson->parse(str_replace("\\","",$_POST["json"]), \JSON::UTF8_DECODE);

$oRetorno = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMsg      = '';
$oRetorno->erro      = false;
$oRetorno->iGrupoAtt = null;

try {

  switch ($oParam->sMethod) {

    /**
     * Consulta todos atributos cadastrados a partir do código agrupador de atributos
     */
    case "consultarAtributos":

      if (!empty($oParam->iCodigoArquivo)) {
        $oParam->iGrupoAtt = DBAttDinamico::getCodigoPorArquivo((int) $oParam->iCodigoArquivo);
      }

      $oDBAttDinamico = new DBAttDinamico($oParam->iGrupoAtt);

      $oRetorno->iGrupoAtt  = $oDBAttDinamico->getCodigo();
      $oRetorno->sTitulo    = urlencode($oDBAttDinamico->getDescricao());
      $oRetorno->aAtributos = array();

      foreach ($oDBAttDinamico->getAtributos() as $oAtributo ) {

        $oStdAtributo = new stdClass;
        $oStdAtributo->oOpcoes = array();

        if ($oAtributo->getCampo()) {

          $oStdAtributo->iCodCampo   = $oAtributo->getCampo()->iCodigo;
          $oStdAtributo->sDescrCampo = $oAtributo->getCampo()->sDescricao;
        } else {

          $oStdAtributo->iCodCampo   = '';
          $oStdAtributo->sDescrCampo = '';
        }

        $oStdAtributo->iCodigo          = $oAtributo->getCodigo();
        $oStdAtributo->iTipo            = $oAtributo->getTipo();
        $oStdAtributo->sNome            = $oAtributo->getNome();
        $oStdAtributo->sDescricao       = $oAtributo->getDescricao();
        $oStdAtributo->oCampoReferencia = $oAtributo->getCampo();
        $oStdAtributo->lObrigatorio     = $oAtributo->isObrigatorio();
        $oStdAtributo->sValorDefault    = $oAtributo->getValorDefault();
        $oStdAtributo->lAtivo           = $oAtributo->ativo();

        $aOpcoes = $oAtributo->getOpcoes();
        if ($oAtributo->getNome() == 'codigofundamentacao') {
          usort($aOpcoes, function (DBAttDinamicoAtributoOpcao $oOpcao1, DBAttDinamicoAtributoOpcao $oOpcao2) {
            return strnatcmp($oOpcao1->getOpcao(), $oOpcao2->getOpcao());
          });
        }
        foreach ($aOpcoes as $oOpcao) {
          $oStdAtributo->oOpcoes[$oOpcao->getOpcao()] = urlencode($oOpcao->getValor());
        }

        $oRetorno->aAtributos[] = $oStdAtributo;
      }

      $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

      break;

    /**
     * Salva as informações do objeto em sessão na base de dados
     */
    case "confirmar":

      if (isset($_SESSION['oDBAttDinamico'])) {
        $oDBAttDinamico = unserialize($_SESSION['oDBAttDinamico']);
      } else {
        $oDBAttDinamico = new DBAttDinamico($oParam->iGrupoAtt);
      }

      if ($oDBAttDinamico->getDescricao() == '') {
        $oDBAttDinamico->setDescricao('Grupo de Atributos Dinâmicos');
      }
      db_inicio_transacao();

      $oDBAttDinamico->salvar();

      $oRetorno->iGrupoAtt = $oDBAttDinamico->getCodigo();

      db_fim_transacao(false);

      break;

    /**
     * Adiciona ou altera os atributos do objeto em sessão
     */
    case "salvarAtributo":

      if (isset($_SESSION['oDBAttDinamico'])) {
        $oDBAttDinamico = unserialize($_SESSION['oDBAttDinamico']);
      } else {

        $oDBAttDinamico = new DBAttDinamico($oParam->iGrupoAtt);
        $oDBAttDinamico->setDescricao($oParam->sTitulo);
      }

      $oDBAttDinamicoAtributo = new DBAttDinamicoAtributo();
      $oDBAttDinamicoAtributo->setCodigo       ($oParam->iCodigo);
      $oDBAttDinamicoAtributo->setDescricao    ($oParam->sDescricao);
      $oDBAttDinamicoAtributo->setCampo        ($oParam->iCampo);
      $oDBAttDinamicoAtributo->setGrupoAtributo($oParam->iGrupoAtt);
      $oDBAttDinamicoAtributo->setTipo         ($oParam->iTipo);
      $oDBAttDinamicoAtributo->setValorDefault ($oParam->sValorDefault);
      $oDBAttDinamicoAtributo->setObrigatorio($oParam->lObrigatorio);
      $oDBAttDinamicoAtributo->setAtivo($oParam->lAtivo);

      if (isset($oParam->iIndAtributo) && trim($oParam->iIndAtributo) != '') {

        if (!empty($oParam->iCodigo)) {
          $oParam->iIndAtributo = $oParam->iCodigo;
        }
        $oDBAttDinamico->alterarAtributo($oParam->iIndAtributo,$oDBAttDinamicoAtributo);
      } else {
        $oDBAttDinamico->adicionarAtributo($oDBAttDinamicoAtributo);
      }

      $oRetorno->aAtributos = array();

      foreach ($oDBAttDinamico->getAtributos() as $oAtributo ) {

        $oStdAtributo = new stdClass;

        if ($oAtributo->getCampo()) {
          $oStdAtributo->iCodCampo   = $oAtributo->getCampo()->iCodigo;
          $oStdAtributo->sDescrCampo = urlencode($oAtributo->getCampo()->sDescricao);
        } else {
          $oStdAtributo->iCodCampo   = '';
          $oStdAtributo->sDescrCampo = '';
        }

        $oStdAtributo->iCodigo          = $oAtributo->getCodigo();
        $oStdAtributo->iTipo            = $oAtributo->getTipo();
        $oStdAtributo->sNome            = $oAtributo->getNome();
        $oStdAtributo->sDescricao       = urlencode($oAtributo->getDescricao());
        $oStdAtributo->oCampoReferencia = $oAtributo->getCampo();
        $oStdAtributo->lObrigatorio     = $oAtributo->isObrigatorio();
        $oStdAtributo->lAtivo           = $oAtributo->ativo();
        $oStdAtributo->sValorDefault    = urlencode($oAtributo->getValorDefault());
        $oRetorno->aAtributos[] = $oStdAtributo;
      }

      $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

      break;

    /**
     * Remove os atributos do objeto em sessão
     */
    case "removerAtributo":

      $oDBAttDinamico = unserialize($_SESSION['oDBAttDinamico']);

      db_inicio_transacao();

      try {

        $oRetorno->aAtributos = array();

        foreach ($oDBAttDinamico->getAtributos() as $oAtributo ) {

          if($oAtributo->getCodigo() == $oParam->iIndAtributo) {

            $oAtributo->excluir();

          } else {

            $oStdAtributo = new stdClass;

            if ($oAtributo->getCampo()) {

              $oStdAtributo->iCodCampo   = $oAtributo->getCampo()->iCodigo;
              $oStdAtributo->sDescrCampo = urlencode($oAtributo->getCampo()->sDescricao);
            } else {

              $oStdAtributo->iCodCampo   = '';
              $oStdAtributo->sDescrCampo = '';
            }

            $oStdAtributo->iCodigo          = $oAtributo->getCodigo();
            $oStdAtributo->iTipo            = $oAtributo->getTipo();
            $oStdAtributo->sNome            = $oAtributo->getNome();
            $oStdAtributo->sDescricao       = urlencode($oAtributo->getDescricao());
            $oStdAtributo->oCampoReferencia = $oAtributo->getCampo();
            $oStdAtributo->lObrigatorio     = $oAtributo->isObrigatorio();
            $oStdAtributo->lAtivo           = $oAtributo->ativo();
            $oStdAtributo->sValorDefault    = urlencode($oAtributo->getValorDefault());

            $oRetorno->aAtributos[] = $oStdAtributo;
          }
        }

        $oDBAttDinamico->removerAtributo($oParam->iIndAtributo);

        db_fim_transacao(false);

      } catch (Exception $e) {

        $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

        db_fim_transacao(true);
        throw new Exception($e->getMessage());
      }

      $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

      break;

    /**
     * Salva os valores informados na tela
     */
    case "salvarValorAtributo":

      $clCadAttValor      = new cl_db_cadattdinamicoatributosvalor();
      $clCadAttValorGrupo = new cl_db_cadattdinamicovalorgrupo();

      $iGrupoAtributos    = $oParam->iGrupoAtributos;

      db_inicio_transacao();

      /**
       * Caso não exista valor para o agrupador de registros então gerado um novo
       */
      if ($oParam->iGrupoValor == null) {

        $clCadAttValorGrupo->db120_sequencial = $oParam->iGrupoValor;
        $clCadAttValorGrupo->incluir($oParam->iGrupoValor);

        $iGrupoValor = $clCadAttValorGrupo->db120_sequencial;
      } else {
        $iGrupoValor = $oParam->iGrupoValor;
      }

      $oRetorno->iGrupoValor = $iGrupoValor;

      /**
       * Exclui todos os valores já lançados para o agrupador de valor informado
       */
      $clCadAttValor->excluir(null," db110_cadattdinamicovalorgrupo = {$iGrupoValor} ");

      if ($clCadAttValor->erro_status == 0) {
        throw new Exception($clCadAttValor->erro_msg);
      }

      /**
       * Inclui na base de dados todos os valores informados na tela
       */
      foreach ($oParam->aAtributos as $oAtributo) {

        $clCadAttValor->db110_cadattdinamicovalorgrupo   = $iGrupoValor;
        $clCadAttValor->db110_db_cadattdinamicoatributos = $oAtributo->iCodigoAtributo;
        $clCadAttValor->db110_valor                      = $oAtributo->sValor;

        $clCadAttValor->incluir(null);

        if ($clCadAttValor->erro_status == 0) {
          throw new Exception($clCadAttValor->erro_msg);
        }
      }

      db_fim_transacao(false);
      break;

    /**
     *  Consulta os valores lançados a partir de um agrupador de valores lançados
     */
    case "consultaAtributosValor":

      if (empty($oParam->iGrupoValor)) {
        throw new ParameterException('O grupo de valores não foi informado.');
      }

      $oRetorno->aValoresAtributos  = array();
      $oRetorno->aAtributos         = array();

      $clCadAttValorGrupo    = new cl_db_cadattdinamicovalorgrupo();

      $rsDadosAtributosValor = $clCadAttValorGrupo->sql_record($clCadAttValorGrupo->sql_query($oParam->iGrupoValor));

      if ($clCadAttValorGrupo->numrows > 0) {

        $oRetorno->aValoresAtributos = db_utils::getCollectionByRecord($rsDadosAtributosValor,false,false,true);

        $iGrupoAtributos = $oRetorno->aValoresAtributos[0]->db109_db_cadattdinamico;

        $oDBAttDinamico = new DBAttDinamico($iGrupoAtributos);

        foreach ($oDBAttDinamico->getAtributos() as $oAtributo) {

          $oStdAtributo = new stdClass;

          $oStdAtributo->iCodigo          = $oAtributo->getCodigo();
          $oStdAtributo->iTipo            = $oAtributo->getTipo();
          $oStdAtributo->sNome            = $oAtributo->getNome();
          $oStdAtributo->sDescricao       = urlencode($oAtributo->getDescricao());
          $oStdAtributo->oCampoReferencia = $oAtributo->getCampo();
          $oStdAtributo->lObrigatorio     = $oAtributo->isObrigatorio();
          $oStdAtributo->sValorDefault    = urlencode($oAtributo->getValorDefault());

          $oRetorno->aAtributos[] = $oStdAtributo;
        }

      } else {
        throw new Exception('Nenhum valor encontrado para os atributos informados!');
      }
      break;

    case "finalizarSessao":

      unset($_SESSION['oDBAttDinamico']);
      break;
  }

} catch (Exception $eException) {

  if ( db_utils::inTransaction() ) {
    db_fim_transacao(true);
  }

  $oRetorno->iStatus = 2;
  $oRetorno->erro    = true;
  $oRetorno->sMsg    = urlencode(str_replace("\\n","\n",$eException->getMessage()));
}

echo $oJson->stringify($oRetorno);