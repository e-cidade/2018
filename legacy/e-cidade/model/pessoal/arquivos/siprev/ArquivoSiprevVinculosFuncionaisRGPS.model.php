<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
class ArquivoSiprevVinculosFuncionaisRGPS extends ArquivoSiprevBase {

  private   $aServidores  = null;
  protected $sNomeArquivo = "08.1-VinculosFuncionaisRGPS";
  protected $sRegistro    = "vinculosFuncionaisRgps";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["08.1"] = array();
  }

  /**
   * Retona a parcela de servidores que será manipulada
   */
  private function getServidores($quantidade = null) {

    if ($this->aServidores === null) {
      $this->aServidores = $this->pesquisarServidores();
    }

    if (!$quantidade) {
      $quantidade = count($this->aServidores);
    }

    return array_splice($this->aServidores, 0, $quantidade);//$this->aDados;
  }

  /**
   * Pesquisa no banco de dados todos os servidores que irão nos arquivos.
   */
  public function pesquisarServidores() {

    $sSql = "   select distinct ";
    $sSql.= "          rh01_regist      as matricula, ";
    $sSql.= "          rh01_instit      as instituicao ,";
    $sSql.= "          rh01_numcgm      as cgm, ";
    $sSql.= "          z01_cgccpf       as cpf, ";
    $sSql.= "          rh37_descr       as cargo, ";
    $sSql.= "          z01_nome         as nome, ";
    $sSql.= "          rh02_tbprev      as tabela_previdencia, ";
    $sSql.= "          rh01_admiss      as data_admissao, ";

    $sSql.= "          case ";
    $sSql.= "            when h13_descr ilike '%comiss%' ";
    $sSql.= "               then 4 "; // Cargo Comissionado
    $sSql.= "             when rh02_vincrais = 50 ";
    $sSql.= "               then 3 "; // Cargo Temporario
    $sSql.= "             when rh02_vincrais = 35 ";
    $sSql.= "               then 7 "; // Servidor estravel nao efetivo
    $sSql.= "             else 1 "; // Cargo Efetivo
    $sSql.= "          end as tipo_vinculo, ";

    $sSql.= "          rh02_anousu      as ano, ";
    $sSql.= "          rh02_mesusu      as mes ";
    $sSql.= "     from rhpessoal ";
    $sSql.= "          inner join rhfuncao          on (rh01_funcao, rh01_instit)  = (rh37_funcao, rh37_instit) ";
    $sSql.= "          inner join cgm               on z01_numcgm                  = rh01_numcgm ";
    $sSql.= "          inner join rhpessoalmov      on rh02_regist                 = rhpessoal.rh01_regist ";
    $sSql.= "                                      and rh02_anousu                 = {$this->iAnoInicial} ";
    $sSql.= "                                      and rh02_mesusu                 = {$this->iMesInicial} ";
    $sSql.= "          inner join rhregime          on rh02_codreg                 = rh30_codreg ";

    $sSql.= "          left  join rhpesrescisao     on rh05_seqpes                 = rh02_seqpes ";
    $sSql.= "          left  join tpcontra          on rh02_tpcont                 = h13_codigo ";
    $sSql.= "    where case ";
    $sSql.= "            when h13_descr ilike '%comiss%' ";
    $sSql.= "               then 4 "; // Cargo Comissionado
    $sSql.= "             when rh02_vincrais = 50 ";
    $sSql.= "               then 3 "; // Cargo Temporario
    $sSql.= "             when rh02_vincrais = 35 ";
    $sSql.= "               then 7 "; // Servidor estravel nao efetivo
    $sSql.= "             else 1 "; // Cargo Efetivo
    $sSql.= "          end in (3,4,5,6) ";
    $sSql.= "    order by instituicao, nome, matricula";

    $rsDados    = db_query($sSql);

    /**
     * Monta um servidor sem acessar o banco para melhoria de performance
     */
    return \db_utils::makeCollectionFromRecord($rsDados, function($dados) {

      $cgm      = new \CgmFisico();
      $cgm->setCodigo($dados->cgm);
      $cgm->setNome($dados->nome);
      $cgm->setCpf($dados->cpf);

      $servidor = new \Servidor(null, $dados->ano, $dados->mes, $dados->instituicao);
      $servidor->setMatricula($dados->matricula);
      $servidor->setCodigoInstituicao($dados->instituicao);
      $servidor->setCgm($cgm);
      $servidor->setDataAdmissao(new DBDate($dados->data_admissao));
      $servidor->descricaoCargo = $dados->cargo;
      $servidor->tipoVinculo    = $dados->tipo_vinculo;

      return $servidor;
    });
  }

  /**
   * Retorna nos dados para geração dos arquivos
   */
  public function getDados($iQuantidadeRegistros) {

    if (!$servidores = $this->getServidores($iQuantidadeRegistros)) {
      return false;
    }

    $retorno = array();

    foreach ($servidores as $servidor) {

      if ($aErrosRegistro = $this->validarDados($servidor)) {

        foreach ($aErrosRegistro as $erro) {
          ArquivoSiprevBase::$aErrosProcessamento["08.1"][] = $erro;
        }

        continue;
      }

      $retorno[] = (object)array(
        "vinculosFuncionaisRgps" => $this->valoresVinculosFuncionaisRgps($servidor)
      );
    }
    return $retorno;
  }

  /**
   * Realiza as validações dos campos
   */
  private function validarDados($servidor) {

    $aErrosRegistro = array();
    if(!DBString::isCPF($servidor->getCgm()->getCpf())) {
      $aErrosRegistro[] = $this->getErro($servidor, "CPF '{$servidor->getCgm()->getCpf()}' é inválido.");
    }

    return $aErrosRegistro;
  }

  /**
   * Monta o array dos erros com os dados para apresentação no relatório
   */
  private function getErro($oDadosRetorno, $sErro) {

    return array(
      $oDadosRetorno->getInstituicao()->getDescricao(),
      "{$oDadosRetorno->getCgm()->getCodigo()} - {$oDadosRetorno->getCgm()->getNome()}",
      $sErro,
    );
  }

  /**
   * Retorna o "esqueleto" do arquivo xml
   */
  public function getElementos() {
    return array($this->tagVinculosFuncionaisRgps());
  }


  private function tagVinculosFuncionaisRgps() {

    return self::makeTag("vinculosFuncionaisRgps", array(
      /**
       * atributos
       */
      "operacao",
      "dataInicioFuncao",
      "descricaoFuncao",
      "matricula",
      "regime",
      "tipoVinculo",

      /**
       * Tags
       */
      $this->tagOrgao(),
      $this->tagServidor(),
      $this->tagMovimentacoesFuncionaisRgps(),

    ));
  }

  private function valoresVinculosFuncionaisRgps($servidor) {

    return (object)array(
      "operacao"             => "I",
      "dataInicioFuncao"     => $servidor->getDataAdmissao()->getDate(),
      "descricaoFuncao"      => $servidor->descricaoCargo,
      "matricula"            => $servidor->getMatricula(),
      "regime"               => $servidor->getTabelaPrevidencia() == 2 ? 1 : 2, // - 1-RPPS, 2-RGPS
      "tipoVinculo"          => $servidor->tipoVinculo,
      "servidor"             => $this->valoresServidor($servidor),
      "orgao"                => $this->valoresOrgao($servidor),
      "movimentacoesFuncionaisRgps" => $this->valoresMovimentacoesFuncionaisRgps($servidor),
    );
  }

  private function tagMovimentacoesFuncionaisRgps() {
    return self::makeTag("movimentacoesFuncionaisRgps", array(
      /**
       * atributos
       */
      "operacao",
      "descricaoFuncao",
      "dataMovimentacao",
    ));
  }

  private function valoresMovimentacoesFuncionaisRgps($servidor) {

    return (object)array(
      "operacao" => "I",
      "descricaoFuncao" => $servidor->descricaoCargo,
      "dataMovimentacao" => $servidor->getAnoCompetencia() . "-" . $servidor->getMesCompetencia() . '-01',
    );
  }

  private function tagOrgao() {
    return self::makeTag("orgao",array("nome", "poder"));
  }

  private function valoresOrgao(Servidor $servidor) {

    return (object)array(
      "nome"  => $servidor->getInstituicao()->getDescricao(),
      "poder" => $servidor->getInstituicao()->getTipo() > 6 ? 6 : $servidor->getInstituicao()->getTipo(),
    );
  }

  private function tagServidor() {

    return self::makeTag("servidor", array(
      "nome",
      "numeroCPF",
      "numeroNIT",
      "numeroRG",
      "dataNascimento",
      "nomeMae",
    ));
  }

  private function valoresServidor(Servidor $servidor) {
    return (object)array(
      "nome"           => $servidor->getCgm()->getNome(),
      "numeroCPF"      => $servidor->getCgm()->getCpf(),
    );
  }

  private function tagCargo() {

    return self::makeTag("cargo", array(
      "nome",
      $this->tagCarreira()
    ));
  }

  private function valoresCargo(Servidor $servidor) {
    return (object)array(
      "nome"     => $servidor->descricaoCargo,
      "carreira" => $this->valoresCarreira($servidor),
    );
  }

  private function tagCarreira() {

    return self::makeTag("carreira", array(
      "nome",
      $this->tagOrgao(),
    ));
  }

  private function valoresCarreira(Servidor $servidor) {

    return (object)array(
      "nome" => "Servidor Público",
      "orgao" => $this->valoresOrgao($servidor),
    );
  }
}
