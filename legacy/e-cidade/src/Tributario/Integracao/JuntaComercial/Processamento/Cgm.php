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
use ECidade\Tributario\Integracao\JuntaComercial\Repository;
/**
 * Classe que processa as informações de CGM da Junta Comercial
 * @package ECidade\Tributario\Integracao\JuntaComercial\Processamento
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class Cgm extends Base
{
  private $tipoEmpresa;

  /**
   * ProcessaCgm constructor.
   * @param Dicionario $dicionario  objeto que contém os dados do arquivo xml
   */
  public function __construct(Dicionario $dicionario)
  {
    parent::__construct($dicionario);
    $this->tipoEmpresa = null;
  }

  /**
   * Buscamos os cgms processados
   * @return array
   */
  public function getCgm()
  {
    $retorno = array();
    foreach ($this->registro as $dadosCgm){

      $municipio = null;

      if ( !is_null($dadosCgm->codigo_municipio) ) {

        $repository = new Repository();
        $municipio = $repository->getMunicipio($dadosCgm->codigo_municipio);
      }

      $cgm = \CgmFactory::getInstanceByCnpjCpf(trim($dadosCgm->cpfcnpj));

      if ( $cgm == false ) {
        $cgm = \CgmFactory::getInstanceByType($dadosCgm->tipo_pessoa);
      }

      if ( $cgm instanceof \CgmJuridico) {

        $cgm->setCnpj(trim($dadosCgm->cpfcnpj));
        $cgm->setNire($dadosCgm->nire);
        $cgm->setNomeFantasia($dadosCgm->razao_social);
      }

      if ($cgm instanceof \CgmFisico) {
        $cgm->setCpf(trim($dadosCgm->cpfcnpj));
      }

      $cgm->setInscricaoEstadual($dadosCgm->inscricao_estadual);
      $cgm->setTipoEmpresa($dadosCgm->tipo_empresa);
      $cgm->setNome($dadosCgm->razao_social);
      $cgm->setNomeCompleto($dadosCgm->razao_social);

      $dataCadastroAtual = $cgm->getCadastro();

      if (empty($dataCadastroAtual)) {
        $dataCadastro = new \DateTime($dadosCgm->data_cadastro);
        $cgm->setCadastro($dataCadastro->format("Y-m-d"));
      }

      $cgm->setLogradouro($dadosCgm->logradouro);
      $cgm->setNumero(is_numeric($dadosCgm->numero) ? $dadosCgm->numero : 0);
      $cgm->setComplemento($dadosCgm->complemento);
      $cgm->setBairro($dadosCgm->bairro);
      $cgm->setUf($dadosCgm->uf);
      $cgm->setCep($dadosCgm->cep);
      $cgm->setMunicipio($municipio);

      $retorno[trim($dadosCgm->cpfcnpj)] = $cgm;
    }

    return $retorno;
  }

  /**
   * Alteramos o tipo de empresa que será usado cadastro do cgm.
   * Essa informação não será o que vem informado no arquivo XML
   * @param $tipoEmpresa
   */
  public function setTipoEmpresa($tipoEmpresa)
  {
    $this->tipoEmpresa = $tipoEmpresa;
  }

  /**
   * Processamos as informações de cgm de acordo com os grupos informados
   */
  public function processar()
  {
    foreach ($this->grupo as $grupo) {

      $this->dicionario->selecionarGrupo($grupo);

      while ($this->dicionario->next()){
        $indice = $this->dicionario->key();

        if ( !is_null($this->dicionario->getCampo("identificador")) ) {
          $indice = trim($this->dicionario->getCampo("identificador"));
        }

        if (!isset($this->registro[$indice])) {
          $this->registro[$indice] = new \stdClass();
        }

        $this->registro[$indice]->cpfcnpj = $this->getValor($indice, "cpfcnpj");
        $this->registro[$indice]->tipo_empresa = $this->tipoEmpresa;

        $iTipoPessoa = $this->getValor($indice, "tipo_pessoa");

        if ($this->tipoEmpresa == null) {

          switch ($iTipoPessoa) {
            case "1":
              $this->registro[$indice]->tipo_empresa = "31";
              break;
            case "2":
              $this->registro[$indice]->tipo_empresa = "36";
              break;
          }
        }

        $this->registro[$indice]->tipo_pessoa = $iTipoPessoa;
        $this->registro[$indice]->inscricao_estadual = $this->getValor($indice, "inscricao_estadual");
        $this->registro[$indice]->data_cadastro = $this->getValor($indice, "data_cadastro");
        $this->registro[$indice]->razao_social = $this->getValor($indice, "razao_social");
        $this->registro[$indice]->nire = $this->getValor($indice, "nire");
        $this->registro[$indice]->logradouro = $this->getValor($indice, "logradouro");
        $this->registro[$indice]->numero = $this->getValor($indice, "numero");
        $this->registro[$indice]->complemento = $this->getValor($indice, "complemento");
        $this->registro[$indice]->bairro = $this->getValor($indice, "bairro");
        $this->registro[$indice]->uf = $this->getValor($indice, "uf");
        $this->registro[$indice]->cep = $this->getValor($indice, "cep");
        $this->registro[$indice]->codigo_municipio = $this->getValor($indice, "codigo_municipio");
      }
    }
  }
}
