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
class ArquivoSiprevFuncoesGratificadas extends ArquivoSiprevBase {

  protected $sNomeArquivo = "16-FuncoesGratificadas";
  private   $aServidores = null;

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["16"] = array();
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
    $sSql.= "          rh01_regist          as matricula, ";
    $sSql.= "          rh01_instit          as instituicao ,";
    $sSql.= "          rh01_numcgm          as cgm, ";
    $sSql.= "          z01_cgccpf           as cpf, ";
    $sSql.= "          rh37_descr           as cargo, ";
    $sSql.= "          z01_nome             as nome, ";
    $sSql.= "          rh02_tbprev          as tabela_previdencia, ";
    $sSql.= "          rh01_admiss          as data_admissao, ";
    $sSql.= "          r11_basefgintegral   as base_fg_integral, ";
    $sSql.= "          r11_basefgparcial    as base_fg_parcial, ";

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
    $sSql.= "          inner join cfpess on (r11_anousu, r11_mesusu, r11_instit) = (fc_anofolha(rh01_instit), fc_mesfolha(rh01_instit), rh01_instit) ";
    $sSql.= "                           and r11_basefgintegral is not null ";
    $sSql.= "                           and r11_basefgparcial  is not null ";
    $sSql.= "          inner join rhfuncao          on (rh01_funcao, rh01_instit)  = (rh37_funcao, rh37_instit) ";
    $sSql.= "          inner join cgm               on z01_numcgm                  = rh01_numcgm ";
    $sSql.= "          inner join rhpessoalmov      on rh02_regist                 = rhpessoal.rh01_regist ";
    $sSql.= "                                      and rh02_anousu                 = {$this->iAnoInicial} ";
    $sSql.= "                                      and rh02_mesusu                 = {$this->iMesInicial} ";
    $sSql.= "          inner join rhregime          on rh02_codreg                 = rh30_codreg ";

    $sSql.= "          left  join rhpesrescisao     on rh05_seqpes                 = rh02_seqpes ";
    $sSql.= "          left  join tpcontra          on rh02_tpcont                 = h13_codigo ";
    $sSql.= " order by nome, matricula";
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
      $servidor->baseFGIntegral = $dados->base_fg_integral;
      $servidor->baseFGParcial  = $dados->base_fg_parcial;
      return $servidor;
    });
  }


  private function getEventos(Servidor $servidor) {

    /**
     * base com o FG que conta como total doi salario
     */
    $baseFGParcial = new \Base(
      $servidor->baseFGParcial,
      $competencia = new \DBCompetencia(
        $servidor->getAnoCompetencia(),
        $servidor->getMesCompetencia()
      ),
      $instituicao = $servidor->getInstituicao()
    );

    /**
     * Base do FG que como integral do salario
     */
    $baseFGIntegral = new \Base(
      $servidor->baseFGIntegral,
      $competencia,
      $instituicao
    );
    $parcial                = true;

    /**
     * Callback do processamento para cada evento financeiro
     */
    $fCallbackProcessamento = function() use ($parcial) {

      $parametros = array_filter(func_get_args());
      list($chave, $evento) = each($parametros);

      $tabela = $evento->getCalculo()->getTabela();
      $sigla  = $evento->getCalculo()->getSigla();

      $sSqlDataInicio = " select min(({$sigla}_anousu||'-'||{$sigla}_mesusu||'-01')::date) as inicio";
      $sSqlDataInicio.= "   from {$tabela} ";
      $sSqlDataInicio.= "  where {$sigla}_regist = {$evento->getServidor()->getMatricula()} ";
      $sSqlDataInicio.= "    and {$sigla}_instit = {$evento->getServidor()->getInstituicao()->getCodigo()} ";
      $sSqlDataInicio.= "    and {$sigla}_rubric = '{$evento->getRubrica()->getCodigo()}' ";

      $rsResultado = db_query($sSqlDataInicio);
      if (!$rsResultado) {
        throw new DBException("Erro ao buscar data de origem da Rubrica ");
      }

      return (object)array(
        'sigla' => $evento->getRubrica()->getCodigo(),
        'descricao' => $evento->getRubrica()->getDescricao(),
        'parcial' => $parcial,
        'dataNomeacao' => db_utils::fieldsMemory($rsResultado, 0)->inicio
      );
    };

    /**
    * Processa os eventos parciais
    */
    $rubricasFGParcial      = $baseFGParcial->getRubricasBaseServidor($servidor);
    $parcial                = true;
    $aEventosParciais = array_map(
      $fCallbackProcessamento,
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(null, $rubricasFGParcial),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR)->getEventosFinanceiros(null, $rubricasFGParcial),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_13o)->getEventosFinanceiros(null, $rubricasFGParcial)
    );
    /**
     * Processa os eventos integrais
     */
    $rubricasFGIntegral = $baseFGIntegral->getRubricasBaseServidor($servidor);
    $parcial            = false;
    $aEventosIntegrais  = array_map(
      $fCallbackProcessamento,
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO)->getEventosFinanceiros(null, $rubricasFGParcial),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR)->getEventosFinanceiros(null, $rubricasFGParcial),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_13o)->getEventosFinanceiros(null, $rubricasFGParcial)
    );

    return array_merge($aEventosParciais, $aEventosIntegrais);
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
          ArquivoSiprevBase::$aErrosProcessamento["16"][] = $erro;
        }
        continue;
      }

      foreach($this->getEventos($servidor) as $valoresFg) {

        $retorno[] = (object)array(
          "funcaoGratificada" => $this->valoresFuncaoGratificada($servidor, $valoresFg)
        );
      }
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
      $oDadosRetorno->getCgm()->getCodigo() . " - " . $oDadosRetorno->getCgm()->getNome(),
      $sErro,
    );
  }

  /**
   * Retorna o "esqueleto" do arquivo xml
   */
  public function getElementos() {
    return array($this->tagFuncaoGratificada());
  }

  private function tagFuncaoGratificada() {

    return self::makeTag("funcaoGratificada", array(
      /**
       * atributos
       */
      "operacao",
      "descricao",
      "opcaoFuncaoGratificada",
      "dataNomeacao",

      /**
       * Tags
       */
      $this->tagServidor(),
      $this->tagVinculoFuncional(),
    ));
  }

  private function valoresFuncaoGratificada($servidor, $valoresFg) {

    return (object)array(
      "operacao"                => "I",
      "descricao"               => $valoresFg->descricao,
      "opcaoFuncaoGratificada"  => $valoresFg->parcial ? 0 : 1,
      "dataNomeacao"            => $valoresFg->dataNomeacao,
      "servidor"                => $this->valoresServidor($servidor),
      "vinculoFuncional"        => $this->valoresVinculoFuncional($servidor),
    );
  }

  private function tagVinculoFuncional() {

    return self::makeTag("vinculoFuncional", array(
      "dataExercicioCargo",
      "regime",
      "tipoServidor",
      "tipoVinculo",
      $this->tagServidor(),
      $this->tagCargo(),
    ));
  }

  private function valoresVinculoFuncional(Servidor $servidor) {

    return (object)array(
      "dataExercicioCargo"   => $servidor->getDataAdmissao()->getDate(),
      "regime"               => $servidor->getTabelaPrevidencia() == 2 ? 1 : 3, // - 1-RPPS, 2-RGPS --- No validador RGPS está como 3
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
