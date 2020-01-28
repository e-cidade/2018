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
class ArquivoSiprevHistoricosFinanceiros extends ArquivoSiprevBase {

  protected $sNomeArquivo = "09-HistoricosFinanceiros";
  private $aServidores    = null;
  protected $sRegistro    = "historicosFinanceiros";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["09"] = array();
  }
  /**
   * Retona a parcela de servidores que será manipulada
   */
  private function getServidores($quantidade) {

    if ($this->aServidores === null) {
      $this->aServidores = $this->pesquisarServidores();
    }
    return array_splice($this->aServidores, 0, $quantidade);//$this->aDados;
  }

  /**
   * Pesquisa no banco de dados todos os servidores que irão nos arquivos.
   */
  public function pesquisarServidores() {

    $sCondicaoAssenta  = "    (((rh02_anousu, rh02_mesusu) = (extract(year from h16_dtconc), extract(month from h16_dtconc))) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) >= (extract(year from h16_dtconc), extract(month from h16_dtconc)) ";
    $sCondicaoAssenta .= "      and h16_dtterm is null) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) between (extract(year from h16_dtconc), extract(month from h16_dtconc))";
    $sCondicaoAssenta .= "      and (extract(year from h16_dtterm), extract(month from h16_dtterm)) ))";

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
    $sSql.= "               then 4                        /* Cargo Comissionado */ ";
    $sSql.= "             when rh02_vincrais = 50 ";
    $sSql.= "               then 3                        /* Cargo Temporario */ ";
    $sSql.= "             when rh02_vincrais = 35 ";
    $sSql.= "               then 7                        /* Servidor estravel nao efetivo*/ ";
    $sSql.= "             else 1                          /* Cargo Efetivo */ ";
    $sSql.= "          end as tipo_vinculo, ";

    $sSql .= "  case ";
    $sSql .= "       when rh05_recis is not null AND h16_dtconc < rh05_recis ";
    $sSql .= "            then case ";
    $sSql .= "                      when rh01_tipadm = 3 ";
    $sSql .= "                           then 6 ";
    $sSql .= "                      when rh01_tipadm = 4 ";
    $sSql .= "                           then 5 ";
    $sSql .= "                      else 1  ";
    $sSql .= "                  end ";
    $sSql .= "       else case ";
    $sSql .= "                 when r59_movsef = 'U1' ";
    $sSql .= "                      then 12 ";
    $sSql .= "                 when r59_movsef = 'S2' OR r59_movsef = 'S3' ";
    $sSql .= "                      then 11 ";
    $sSql .= "                 else 2 ";
    $sSql .= "             end ";
    $sSql .= "   end as situacao_funcional, ";

    $sSql.= "          rh02_anousu      as ano, ";
    $sSql.= "          rh02_mesusu      as mes ";
    $sSql.= "     from rhpessoal ";
    $sSql.= "          inner join rhfuncao      on (rh01_funcao, rh01_instit)  = (rh37_funcao, rh37_instit)";
    $sSql.= "          inner join cgm           on z01_numcgm   = rh01_numcgm ";
    $sSql.= "          inner join rhpessoalmov  on rh02_regist  = rhpessoal.rh01_regist  ";
    $sSql.= "                                  and rh02_anousu  = {$this->iAnoInicial} ";
    $sSql.= "                                  and rh02_mesusu  = {$this->iMesInicial} ";
    $sSql.= "          inner join rhregime      on rh02_codreg  = rh30_codreg   ";
    $sSql.= "          left  join assenta       on h16_regist   = rh02_regist ";
    $sSql.= "                                  AND {$sCondicaoAssenta} ";
    $sSql.= "          inner join tipoasse on h12_codigo = h16_assent  ";
    $sSql.= "                             AND h12_tipo = 'S'           ";
    $sSql.= "          left  join rhpesrescisao on rh05_seqpes  = rh02_seqpes   ";
    $sSql.= "          left  join rescisao       on r59_anousu  = {$this->iAnoInicial} ";
    $sSql.= "                                   AND r59_mesusu  = {$this->iMesInicial} ";
    $sSql.= "                                   AND r59_regime  = rh02_codreg ";
    $sSql.= "                                   AND r59_instit  = rh02_instit ";
    $sSql.= "                                   AND r59_causa   = rh05_causa ";
    $sSql.= "                                   AND r59_caub    = rh05_caub  ";
    $sSql.= "          left  join tpcontra      on rh02_tpcont  = h13_codigo ";
    $sSql.= "    where rh30_vinculo = 'A' ";
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
      $servidor->descricaoCargo    = $dados->cargo;
      $servidor->tipoVinculo       = $dados->tipo_vinculo;
      $servidor->situacaoFuncional = $dados->situacao_funcional;
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

    $classe  = $this;
    return array_map(function($servidor) use ($classe) {
      return (object)array("historicosFinanceiros" => $classe->valoresHistoricosFinanceiros($servidor));
    }, $servidores);
  }

  /**
   * Retorna o "esqueleto" do arquivo xml
   */
  public function getElementos() {
    return array($this->tagHistoricosFinanceiros());
  }


  private function tagHistoricosFinanceiros() {

    return self::makeTag("historicosFinanceiros", array(
      "operacao",
      $this->tagVinculoFuncional(),
      $this->tagDadosHistoricoFinanceiro(),
    ));
  }

  private function processamentoValoresCalculo(CalculoFolha $folha) {

    $nTotalProventos = 0.00;
    $nValorContribuicao = 0.00;

    $eventos = $folha->getEventosFinanceiros();

    foreach($eventos as $eventoFinanceiro) {

      if($eventoFinanceiro->getNatureza() == EventoFinanceiroFolha::DESCONTO) {
        continue;
      }

      $rubrica = $eventoFinanceiro->getRubrica()->getCodigo();

      if($eventoFinanceiro->getNatureza() == EventoFinanceiroFolha::PROVENTO) {
        $nTotalProventos += $eventoFinanceiro->getValor();
      }

      if($rubrica == "R985" || $rubrica == "R986" || $rubrica == "R987") {
        $nValorContribuicao += $eventoFinanceiro->getValor();
      }
    }

    if ($nTotalProventos == 0) {
      return;
    }


    return (object)array(
      'nTotalProventos' => $nTotalProventos,
      'nValorContribuicao' => $nValorContribuicao,
    );

  }

  public function valoresHistoricosFinanceiros($servidor) {

    $folhas = array(
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_13o),
      $servidor->getCalculoFinanceiro(CalculoFolha::CALCULO_FERIAS),
    );

    $aDadosFinanceiros = array();

    foreach ($folhas as $indiceFolha => $folha) {

      $valores            = $this->processamentoValoresCalculo($folha);
      $nTotalProventos    = 0.00;
      $nValorContribuicao = 0.00;

      if ($valores) {// && $folha->getTabela() != CalculoFolha::CALCULO_SALARIO) {

        $nTotalProventos     = $valores->nTotalProventos;
        $nValorContribuicao  = $valores->nValorContribuicao;
      } elseif (!$valores && $folha->getTabela() != CalculoFolha::CALCULO_SALARIO) {
        continue;
      }

      $aDadosFinanceiros[] = $this->valoresDadosHistoricoFinanceiro($servidor, $folha->getTabela() == CalculoFolha::CALCULO_13o, $indiceFolha, $nValorContribuicao, $nTotalProventos);


    }
// dump($aDadosFinanceiros);
    return (object)array(
      "operacao"  => "I",
      "vinculoFuncional"  => $this->valoresvinculoFuncional($servidor),
      "dadosHistoricoFinanceiro"  => $aDadosFinanceiros
    );
  }

  private function tagVinculoFuncional() {

    return self::makeTag("vinculoFuncional", array(
      "dataExercicioCargo",
      "dataIngressoCarreira",
      "dataIngressoOrgao",
      "situacaoFuncional",
      "matricula",
      "regime",
      "tipoServidor",
      "tipoVinculo",
      $this->tagOrgao(),
      $this->tagServidor(),
      $this->tagCargo(),
    ));
  }

  private function valoresVinculoFuncional(Servidor $servidor) {

    return (object)array(
      "dataExercicioCargo"   => $servidor->getDataAdmissao()->getDate(),
      "dataIngressoCarreira" => $servidor->getDataAdmissao()->getDate(),
      "dataIngressoOrgao"    => $servidor->getDataAdmissao()->getDate(),
      "situacaoFuncional"    => $servidor->situacaoFuncional,
      "matricula"            => $servidor->getMatricula(),
      "regime"               => $servidor->getTabelaPrevidencia() == 2 ? 1 : 2, // - 1-RPPS, 2-RGPS
      "tipoServidor"         => 1,//Servidor CIVIL
      "tipoVinculo"          => $servidor->tipoVinculo,
      "orgao"                => $this->valoresOrgao($servidor),
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
      //"numeroNIT"      => "NÂO-OBRIGATÓRIO",
      //"numeroRG"       => "NÂO-OBRIGATÓRIO",
      //"dataNascimento" => "NÂO-OBRIGATÓRIO",
      //"nomeMae"        => "NÂO-OBRIGATÓRIO",
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

  private function tagDadosHistoricoFinanceiro(){
    return self::makeTag("dadosHistoricoFinanceiro", array(
      "anoContribuicao",
      "mesContribuicao",
      "baseCalculoPatronal",
      "baseCalculoSegurado",
      "compoeMediaBeneficio",
      "contribPatronal",
      "contribSegurado",
      "decimoTerceiroSalario",
      "devolucaoContrib",
      "diferencaContrib",
      "folhaPagamento",
      "remuneracaoBruta",
      "remuneracaoCargo",
      "remuneracaoContrib",
    ));
  }

  private function valoresDadosHistoricoFinanceiro($servidor, $lDecimoTerceiro, $iFolhaPagamento, $valorContribuicao, $valorBruto) {

    $valorCargo = $servidor->getValorVariaveisCalculo(
      $servidor->getAnoCompetencia(),
      $servidor->getMesCompetencia(),
      $servidor->getMatricula(),
      $servidor->getInstituicao()->getCodigo(),
      Servidor::VARIAVEL_SALARIO_BASE_PROGRESSAO
    );

    return (object)array(
      "anoContribuicao"       => $servidor->getAnoCompetencia(),
      "mesContribuicao"       => $servidor->getMesCompetencia(),
      "compoeMediaBeneficio"  => 0,//"0.00",
      "decimoTerceiroSalario" => $lDecimoTerceiro ? 1 : 0,
      "folhaPagamento"        => $iFolhaPagamento,

      "remuneracaoBruta"      => number_format(+$valorBruto, 2, '.', ''),
      "remuneracaoCargo"      => number_format(+$valorCargo, 2, '.', ''),
      "remuneracaoContrib"    => number_format(+$valorContribuicao, 2, '.', ''),

      //"baseCalculoPatronal"   => "0.00", //Não-Obrigatório
      //"baseCalculoSegurado"   => "0.00", //Não-Obrigatório
      //"contribPatronal"       => "0.00", //Não-Obrigatório
      //"contribSegurado"       => "0.00", //Não-Obrigatório
      //"devolucaoContrib"      => "0.00", //Não-Obrigatório
      //"diferencaContrib"      => "0.00", //Não-Obrigatório
    );
  }

}
