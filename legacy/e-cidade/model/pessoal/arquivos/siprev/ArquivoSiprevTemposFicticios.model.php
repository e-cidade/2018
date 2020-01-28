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
class ArquivoSiprevTemposFicticios extends ArquivoSiprevBase {

  protected $sNomeArquivo = "14-TemposFicticios";
  private   $aServidores = null;

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["14"] = array();
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
    $sSql.= "          h16_codigo       as assentamento, ";
    $sSql.= "          rh01_regist      as matricula, ";
    $sSql.= "          rh01_instit      as instituicao ,";
    $sSql.= "          rh01_numcgm      as cgm, ";
    $sSql.= "          z01_cgccpf       as cpf, ";
    $sSql.= "          rh37_descr       as cargo, ";
    $sSql.= "          z01_nome         as nome, ";
    $sSql.= "          rh02_tbprev      as tabela_previdencia, ";
    $sSql.= "          rh01_admiss      as data_admissao, ";
    $sSql.= "          h16_quant        as quantidade_dias, ";
    $sSql.= "          h16_dtconc       as data_inicial, ";
    $sSql.= "          h16_dtterm       as data_final, ";
    $sSql.= "          h12_descr        as descricao, ";
    $sSql.= "          h16_histor       as justificativa, ";
    $sSql.= "          h31_numero       as numero, ";
    $sSql.= "          h31_anousu       as anousu, ";
    $sSql.= "          h31_dtportaria   as data_portaria, ";
    $sSql.= "          h31_dtinicio     as data_inicio, ";

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
    $sSql.= "          inner join rhparam           on h36_instit                  = rh01_instit ";
    $sSql.= "          inner join assenta           on h16_regist                  = rh01_regist ";
    $sSql.= "                                      and h16_assent                  = h36_temposficticios ";
    $sSql.= "                                      and (";
    $sSql.= "                                        ((rh02_anousu, rh02_mesusu) = (extract(year from h16_dtconc), extract(month from h16_dtconc)))";
    $sSql.= "                                          or ";
    $sSql.= "                                        ((rh02_anousu, rh02_mesusu) >= (extract(year from h16_dtconc), extract(month from h16_dtconc)) and h16_dtterm is null) ";
    $sSql.= "                                          or ";
    $sSql.= "                                        ((rh02_anousu, rh02_mesusu) between (extract(year from h16_dtconc), extract(month from h16_dtconc))
                                                                                     and (extract(year from h16_dtterm), extract(month from h16_dtterm)) ) ";
    $sSql.= "                                      ) ";
    $sSql.= "          inner join tipoasse          on h16_assent                  = h12_codigo ";

    $sSql.= "          inner join portariaassenta   on h33_assenta                 = h16_codigo ";
    $sSql.= "          inner join portaria          on h33_portaria                = h31_sequencial ";

    $sSql.= "          left  join rhpesrescisao     on rh05_seqpes                 = rh02_seqpes ";
    $sSql.= "          left  join tpcontra          on rh02_tpcont                 = h13_codigo ";
    $sSql.= " order by nome, matricula ";
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
      $servidor->quantidadeDias = $dados->quantidade_dias;
      $servidor->dataInicial    = $dados->data_inicial;
      $servidor->dataFinal      = $dados->data_final;
      $servidor->descricao      = $dados->descricao;
      $servidor->justificativa  = $dados->justificativa;
      $servidor->portaria       = (object)array(
        "numero"       => $dados->numero,
        "ano"          => $dados->anousu,
        "data"         => $dados->data_portaria,
        "dataInicio"   => $dados->data_inicio,
        "assentamento" => $dados->assentamento,
      );

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
          ArquivoSiprevBase::$aErrosProcessamento["14"][] = $erro;
        }

        continue;
      }

      $retorno[] = (object)array(
        "tempoFicticio" => $this->valoresTempoFicticio($servidor)
      );
    }
    return $retorno;
  }

  /**
   * Realiza as validações dos campos
   * @param stdClass $oDadosRetorno
   * @return array
   */
  private function validarDados($servidor) {

    $aErrosRegistro = array();
    if(!DBString::isCPF($servidor->getCgm()->getCpf())) {
      $aErrosRegistro[] = $this->getErro($servidor, "CPF inválido.");
    }

    if($servidor->dataFinal == '') {
      $aErrosRegistro[] = $this->getErro($servidor, "Data final do assentamento não informada.");
    }

    return $aErrosRegistro;
  }

  /**
   * Monta o array dos erros com os dados para apresentação no relatório
   */
  private function getErro($oDadosRetorno, $sErro) {

    return array(
      $oDadosRetorno->portaria->assentamento,
      $oDadosRetorno->getInstituicao()->getDescricao(),
      $oDadosRetorno->getCgm()->getCodigo()  . " - " . $oDadosRetorno->getCgm()->getNome(),
      $sErro,
    );

  }

  /**
   * Retorna o "esqueleto" do arquivo xml
   */
  public function getElementos() {
    return array($this->tagTempoFicticio());
  }

  private function tagTempoFicticio() {

    return self::makeTag("tempoFicticio", array(
      /**
       * atributos
       */
      "operacao",
      "descricao",
      "anteriorEC20",
      "quantidadeDias",
      "dataInicioPeriodo",
      "dataFimPeriodo",
      "justificativa",

      /**
       * Tags
       */
      $this->tagVinculosFuncionaisRPPS(),
      $this->tagAtoLegal(),
    ));
  }

  private function valoresTempoFicticio($servidor) {


    return (object)array(
      "operacao"                => "I",
      "descricao"               => $servidor->descricao,
      "anteriorEC20"            => "N",
      "quantidadeDias"          => $servidor->quantidadeDias,
      "dataInicioPeriodo"       => $servidor->dataInicial,
      "dataFimPeriodo"          => $servidor->dataFinal,
      "justificativa"           => $servidor->justificativa,
      "vinculosFuncionaisRpps"  => $this->valoresVinculosFuncionaisRpps($servidor),
      "atoLegal"                => $this->valoresAtoLegal($servidor)
    );
  }

  private function tagVinculosFuncionaisRPPS() {

    return self::makeTag("vinculosFuncionaisRpps", array(
      "dataExercicioCargo",
      "regime",
      "tipoServidor",
      "tipoVinculo",
      $this->tagServidor(),
      $this->tagCargo(),
    ));
  }

  private function valoresVinculosFuncionaisRpps(Servidor $servidor) {

    return (object)array(
      "dataExercicioCargo"   => $servidor->getDataAdmissao()->getDate(),
      "regime"               => $servidor->getTabelaPrevidencia() == 2 ? 1 : 2, // - 1-RPPS, 2-RGPS
      "tipoServidor"         => 1,//Servidor CIVIL
      "tipoVinculo"          => $servidor->tipoVinculo,
      "servidor"             => $this->valoresServidor($servidor),
      "cargo"                => $this->valoresCargo($servidor),
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

  private function tagAtoLegal(){
    return self::makeTag("atoLegal", array(
      "tipoAto",
      "numero",
      "ano",
      "dataPublicacao",
      "dataInicioVigencia",
    ));
  }

  private function valoresAtoLegal($servidor) {

    return (object)array(
      "tipoAto"             => "PORTARIA",
      "numero"              => $servidor->portaria->numero,
      "ano"                 => $servidor->portaria->ano,
      "dataPublicacao"      => $servidor->portaria->data,
      "dataInicioVigencia"  => $servidor->portaria->dataInicio,
    );
  }

}
