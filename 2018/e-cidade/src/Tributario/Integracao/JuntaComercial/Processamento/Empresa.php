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

namespace ECidade\Tributario\Integracao\JuntaComercial\Processamento;

use ECidade\Tributario\Integracao\JuntaComercial\Dicionario;
use ECidade\Tributario\Integracao\JuntaComercial\Model\Atividade;
use ECidade\Tributario\Integracao\JuntaComercial\Regin;

/**
 * Classe que processa as informações de EMPRESA da Junta Comercial
 * @package ECidade\Tributario\Integracao\JuntaComercial
 * @author Roberto Carneiro <john.reis@dbseller.com.br>
 */
class Empresa extends Base
{
  /**
   * @var \Empresa $empresa
   */
  private $empresa;

  /**
   * @return \CgmBase
   */
  public function getCgm()
  {
    return $this->getEmpresa()->getCgmEmpresa();
  }

  /**
   * @param \CgmBase $cgm
   */
  public function setCgm($cgm)
  {
    $this->getEmpresa()->setCgmEmpresa($cgm);
  }

  /**
   * @return \Empresa
   */
  public function getEmpresa()
  {
    return $this->empresa;
  }

  /**
   * @param \Empresa $empresa
   */
  public function setEmpresa($empresa)
  {
    $this->empresa = $empresa;
  }

  public function __construct(Dicionario $dicionario)
  {
    parent::__construct($dicionario);
    $this->empresa    = new \Empresa();
    $this->montaDadosEmpresa();
  }

  private function montaDadosEmpresa()
  {
    $oDadosSocio = $this->dicionario->getDadosGrupo(Regin::SOCIO);
    $oDadosEnderecoEmpresa = $this->dicionario->getDadosGrupo(Regin::ENDERECO_INSCRICAO);
    $oDadosEmpresa = $this->dicionario->getDadosGrupo(Regin::EMPRESA);
    $oDadosProtocolo = $this->dicionario->getDadosGrupo(Regin::PROTOCOLO);
    $oDadosComplementar = $this->dicionario->getDadosGrupo(Regin::INFO_COMPLEMENTAR);
    $oDadosAtividade = $this->dicionario->getDadosGrupo(Regin::ATIVIDADE);

    foreach ($oDadosAtividade as $atividade) {

      $oAtividade = new Atividade(null);
      $oAtividade->setCodigo($atividade->codigo);
      $oAtividade->setDataInicio(new \Datetime($atividade->data_inicio));

      if($atividade->tipo_atividade == 1) {
        $oAtividade->setAtividadePrincipal(true);
      }

      $this->empresa->adicionarAtividade($oAtividade);
    }

    if($oDadosProtocolo->tipo_acao == 2 && empty($oDadosProtocolo->inscricao_municipal)) {
      throw new \Exception("Inscrição municipal inválida.");
    }

    if($oDadosProtocolo->tipo_acao == 2 && !empty($oDadosProtocolo->inscricao_municipal)){
      $this->empresa->setInscricao($oDadosProtocolo->inscricao_municipal);
    }

    $iMatricula = null;

    foreach ($oDadosComplementar as $complementar) {

      if ($complementar->chave == 5) {
        $iMatricula = $complementar->valor;
        $this->empresa->setMatricula($iMatricula);
        break;
      }
    }

    $oInformacoesMatricula = $this->getInformacoesMatricula($iMatricula);

    if ($iMatricula != null && !empty($oInformacoesMatricula)) {

      $this->empresa->setNumero($oInformacoesMatricula->numero);
      $this->empresa->setCodigoLogradouro($oInformacoesMatricula->rua);
      $this->empresa->setBairro($oInformacoesMatricula->bairro);
      $this->empresa->setComplemento($oInformacoesMatricula->complemento);


    }

    if ($iMatricula == null) {

      $this->empresa->setCodigoLogradouro($this->getCodigoDoLogradouro($oDadosEnderecoEmpresa->logradouro));
      $this->empresa->setBairro($this->getCodigoDoBairro($oDadosEnderecoEmpresa->bairro));
      $this->empresa->setComplemento($oDadosEnderecoEmpresa->complemento);
      $this->empresa->setNumero($oDadosEnderecoEmpresa->numero);
    }

    if (empty($oDadosEnderecoEmpresa->area)) {
      $oDadosEnderecoEmpresa->area = 0;
    }

    $this->empresa->setCep($oDadosEnderecoEmpresa->cep);
    $this->empresa->setArea($oDadosEnderecoEmpresa->area);
    $this->empresa->setDataCadastramento(new \Datetime($oDadosEmpresa->data_cadastro));
    $this->empresa->setDataJunta(new \Datetime($oDadosEmpresa->data_junta));
    $this->empresa->setPorte($oDadosEmpresa->porte);
    $this->empresa->setUf($oDadosEnderecoEmpresa->uf);

    $sObservacao = $this->getObvervacaoLayout($oDadosProtocolo->tipo_acao, $oDadosEnderecoEmpresa->protocolo);
    $this->empresa->setObservacao($sObservacao);

    $iValorCapital = 0;
    foreach ($oDadosSocio as $socio) {
      $iValorCapital += $socio->valor_capital;
    }

    $this->empresa->setValorCapital($iValorCapital);
  }

  /**
   * Tipos de ações do REGIN
   * 1- constituição
   * 2- alteração
   * 3- exclusão
   * 4- tratar como constituição
   * @param $iOpcao
   * @return string
   */
  private function getObvervacaoLayout($iOpcao, $iProtocolo)
  {
    $acao = "";

    switch ($iOpcao) {
      case 1:
        $acao = "CRIADA";
        break;
      case 2:
        $acao = "ALTERADA";
        break;
      case 3:
        $acao = "EXCLUÍDA";
        break;
      case 4:
        $acao = "CRIADA";
    }

    $sObservacao = "INSCRIÇÃO {$acao} VIA INTEGRAÇÃO REGIN PROTOCOLO Nº {$iProtocolo}.";
    return $sObservacao;
  }

  /**
   * @todo Mover para o local correto, ainda desconhecido...
   * @param $iMatricula
   * @return \_db_fields|null|\stdClass
   * @throws \DBException
   */
  private function getInformacoesMatricula($iMatricula)
  {
    $oDaoInptuConstr = new \cl_iptuconstr();
    $aCampos = array();

    $aCampos[] = "j39_idcons as construcao";
    $aCampos[] = "j39_codigo as rua";
    $aCampos[] = "j88_sigla || ' ' || j14_nome as nomerua";
    $aCampos[] = "j39_numero as numero";
    $aCampos[] = "j39_compl as complemento";
    $aCampos[] = "j34_bairro as bairro";

    $sqlRuas = $oDaoInptuConstr->sql_query($iMatricula,"",implode(",", $aCampos),"j39_idcons");
    $rsRuas  = db_query($sqlRuas);

    if (!$rsRuas) {
      throw new \DBException("Erro ao consultar o endereço da Matrícula Imobiliária.");
    }

    if ( pg_num_rows($rsRuas) == 0) {
      return null;
    }

    return \db_utils::fieldsMemory($rsRuas, 0);
  }

  /**
   * @todo Mover para o local correto, ainda desconhecido...
   * retorna o Codigo do Endereço
   */
  private function getCodigoDoLogradouro($logradouro) {

    $oDaoRuas = new \cl_ruas;
    $sSqlRua  = $oDaoRuas->sqlRuasPorLogradouro($logradouro);

    $rsRuas   = db_query($sSqlRua);

    if (!$rsRuas) {
      throw new \Exception("Não foi possível pesquisar dados das ruas.");
    }

    if (pg_num_rows($rsRuas) > 0) {

      $oRua = \db_utils::fieldsMemory($rsRuas, 0);
      return $oRua->j14_codigo;
    }

    if (pg_num_rows($rsRuas) == 0) {

      $sSqlCodigoRua = $oDaoRuas->sql_query_file(null, "max(j14_codigo)+1 as codigo");
      $rsRuas = db_query($sSqlCodigoRua);
      if (!$rsRuas) {
        throw new \Exception("Erro ao pesquisar código da rua.");
      }
      $codigoRua = \db_utils::fieldsMemory($rsRuas, 0)->codigo;
      $oDaoRuas->j14_tipo   = 11;
      $oDaoRuas->j14_codigo = $codigoRua;
      $oDaoRuas->j14_nome = $logradouro;
      $oDaoRuas->j14_rural = "false";
      $oDaoRuas->j14_obs = " ";
      $oDaoRuas->incluir($codigoRua);
      if ($oDaoRuas->erro_status == 0) {
        throw new \Exception("Erro ao incluir dados da rua.".pg_last_error());
      }
      return $oDaoRuas->j14_codigo;
    }
  }

  /**
   * @todo Mover para o local correto, ainda desconhecido...
   * @param $bairro
   * @return int
   * @throws \Exception
   */
  private function getCodigoDoBairro($bairro) {

    $oDaoBairros = new \cl_bairro();
    $where       = "j13_descr  = '{$bairro}'";
    $sSqlBairro  = $oDaoBairros->sql_query_file(null, "*", null, $where);
    $rsBairros   = db_query($sSqlBairro);
    if (!$rsBairros) {
      throw new \Exception("Não foi possível pesquisar dados do bairro.");
    }

    if (pg_num_rows($rsBairros) > 0) {
      $oBairro = \db_utils::fieldsMemory($rsBairros, 0);
      return $oBairro->j13_codi;
    }

    if (pg_num_rows($rsBairros) == 0) {
      $oDaoBairros->j13_descr = $bairro;
      $oDaoBairros->j13_rural = "false";

      $oDaoBairros->incluir(null);

      if ($oDaoBairros->erro_status == 0) {
        throw new \Exception("Erro ao inserir dados do bairro.\n");
      }

      return $oDaoBairros->j13_codi;
    }
  }
}
