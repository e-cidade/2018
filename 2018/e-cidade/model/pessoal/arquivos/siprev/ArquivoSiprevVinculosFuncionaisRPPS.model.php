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
class ArquivoSiprevVinculosFuncionaisRPPS extends ArquivoSiprevBase {

  private   $aServidores  = null;
  protected $sNomeArquivo = "08.2-VinculoFuncionalRPPS";
  protected $sRegistro    = "vinculosFuncionaisRpps";

  public function __construct() {
    ArquivoSiprevBase::$aErrosProcessamento["08.2"] = array();
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

    return array_splice($this->aServidores, 0, $quantidade);
  }

  /**
   * Pesquisa no banco de dados todos os servidores que irão nos arquivos.
   */
  public function pesquisarServidores() {

    $sCase  = " case ";
    $sCase .= "   when h13_descr ilike '%comiss%' ";
    $sCase .= "         then 4 "; // Cargo Comissionado
    $sCase .= "    when rh02_vincrais = 50 ";
    $sCase .= "         then 3 "; // Cargo Temporario
    $sCase .= "    when rh02_vincrais = 35 ";
    $sCase .= "         then 7 "; // Servidor estravel nao efetivo
    $sCase .= "    else 1 "; // Cargo Efetivo

    $sCondicaoAssenta  = "    (((rh02_anousu, rh02_mesusu) = (extract(year from h16_dtconc), extract(month from h16_dtconc))) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) >= (extract(year from h16_dtconc), extract(month from h16_dtconc)) ";
    $sCondicaoAssenta .= "      and h16_dtterm is null) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) between (extract(year from h16_dtconc), extract(month from h16_dtconc))";
    $sCondicaoAssenta .= "      and (extract(year from h16_dtterm), extract(month from h16_dtterm)) ))";

    $sCamposVinculos  = "rh02_regist, rh02_instit, rh01_numcgm, z01_nome, z01_cgccpf, z01_pis, z01_nasc, z01_mae";
    $sCamposVinculos .= ", rh37_descr, rh02_tbprev, rh01_admiss, {$sCase} end as tipo_vinculo, rh02_codreg, rh01_tipadm";

    $sSqlVinculos  = "select {$sCamposVinculos}                                                                   \n";
    $sSqlVinculos .= "  from rhpessoal                                                                            \n";
    $sSqlVinculos .= "       inner join rhpessoalmov  on rh02_regist                = rh01_regist                 \n";
    $sSqlVinculos .= "       inner join rhregime      on rh30_codreg                = rh02_codreg                 \n";
    $sSqlVinculos .= "       inner join cgm           on z01_numcgm                 = rh01_numcgm                 \n";
    $sSqlVinculos .= "       inner join rhfuncao      on (rh01_funcao, rh01_instit) = (rh37_funcao, rh37_instit)  \n";
    $sSqlVinculos .= "       left  join tpcontra      on h13_codigo                 = rh02_tpcont                 \n";
    $sSqlVinculos .= " where rh02_anousu  = {$this->iAnoInicial}                                                  \n";
    $sSqlVinculos .= "   AND rh02_mesusu  = {$this->iMesInicial}                                                  \n";
    $sSqlVinculos .= "   AND {$sCase} end not in (3,4,5,6)                                                        \n";
    $sSqlVinculos .= "   AND exists(select 1                                                                      \n";
    $sSqlVinculos .= "                from assenta                                                                \n";
    $sSqlVinculos .= "                     inner join tipoasse  on h12_codigo = h16_assent                        \n";
    $sSqlVinculos .= "                                         AND h12_tipo = 'S'                                 \n";
    $sSqlVinculos .= "               where h16_regist = rh02_regist                                               \n";
    $sSqlVinculos .= "                 AND {$sCondicaoAssenta})                                                   \n";
    $sSqlVinculos .= " order by rh02_regist                                                                       \n";

    $rsVinculos = db_query($sSqlVinculos);

    if(!$rsVinculos) {
      throw new DBException('Erro ao buscar os vínculos funcionais do RPPS.');
    }
    $instancia = $this;
    return \db_utils::makeCollectionFromRecord($rsVinculos, function ($oDadosRetorno) use (&$aErros, $instancia) {

      $oCgm = new CgmFisico();
      $oCgm->setCodigo($oDadosRetorno->rh01_numcgm);
      $oCgm->setNome($oDadosRetorno->z01_nome);
      $oCgm->setCpf($oDadosRetorno->z01_cgccpf);
      $oCgm->setPIS($oDadosRetorno->z01_pis);

      $oServidor = new Servidor(null, $instancia->getAnoInicial(), $instancia->getMesInicial(), $oDadosRetorno->rh02_instit);
      $oServidor->setMatricula($oDadosRetorno->rh02_regist);
      $oServidor->setCodigoInstituicao($oDadosRetorno->rh02_instit);
      $oServidor->setCgm($oCgm);
      $oServidor->setDataAdmissao(new DBDate($oDadosRetorno->rh01_admiss));
      $oServidor->setCodigoRegime($oDadosRetorno->rh02_codreg);
      $oServidor->setTipoAdmissao($oDadosRetorno->rh01_tipadm);
      $oServidor->descricaoCargo = $oDadosRetorno->rh37_descr;
      $oServidor->tipoVinculo    = $oDadosRetorno->tipo_vinculo;

      return $oServidor;
    });
  }

  public function getDados($iQuantidadeRegistros) {

    if (!$servidores = $this->getServidores($iQuantidadeRegistros)) {
      return false;
    }

    $retorno = array();

    foreach ($servidores as $servidor) {

      if ($aErrosRegistro = $this->validarDados($servidor)) {

        foreach ($aErrosRegistro as $erro) {
          ArquivoSiprevBase::$aErrosProcessamento["08.2"][] = $erro;
        }

        continue;
      }

      $retorno[] = (object)array(
        "vinculosFuncionaisRpps" => $this->preencheVinculosFuncionaisRPPS($servidor)
      );
    }
    return $retorno;
  }

  /**
   * Retorna os elementos e propriedades do arquivo
   * @return array
   */
  public function getElementos() {
    return array($this->atributosVinculosFuncionaisRPPS());
  }

  /**
   * Atributos do registro vinculosFuncionaisRpps
   * @return array
   */
  private function atributosVinculosFuncionaisRPPS() {

    $aVinculosFuncionaisRPPS                 = array();
    $aVinculosFuncionaisRPPS['nome']         = 'vinculosFuncionaisRpps';
    $aVinculosFuncionaisRPPS['propriedades'] = array(
      'operacao',
      'dataExercicioCargo',
      'dataIngressoCarreira',
      'dataIngressoOrgao',
      'situacaoFuncional',
      'matricula',
      'regime',
      'tipoServidor',
      'tipoVinculo',
      $this->atributosOrgao(),
      $this->atributosServidor(),
      $this->atributosCargo(),
      $this->atributosMovimentacoesFuncionaisRPPS()
    );

    return $aVinculosFuncionaisRPPS;
  }

  /**
   * Atributos do registro servidor
   * @return array
   */
  private function atributosServidor() {

    $aServidor                 = array();
    $aServidor['nome']         = 'servidor';
    $aServidor['propriedades'] = array('nome', 'numeroCPF', 'numeroNIT', 'dataNascimento', 'nomeMae');

    return $aServidor;
  }

  /**
   * Atributos do registro orgao
   * @return array
   */
  private function atributosOrgao() {

    $aOrgao                 = array();
    $aOrgao['nome']         = 'orgao';
    $aOrgao['propriedades'] = array('nome', 'poder');

    return $aOrgao;
  }

  /**
   * Atributso do registro cargo
   * @return array
   */
  private function atributosCargo() {

    $aCargo                 = array();
    $aCargo['nome']         = 'cargo';
    $aCargo['propriedades'] = array('nome', $this->atributosCarreira());

    return $aCargo;
  }

  /**
   * Atributos do registro carreira
   * @return array
   */
  private function atributosCarreira() {

    $aCarreira                 = array();
    $aCarreira['nome']         = 'carreira';
    $aCarreira['propriedades'] = array('nome', $this->atributosOrgaoCarreira());

    return $aCarreira;
  }

  /**
   * Atributos do registro orgao, pertencente a carreira
   * @return array
   */
  private function atributosOrgaoCarreira() {

    $aOrgaoCarreira                 = array();
    $aOrgaoCarreira['nome']         = 'orgao';
    $aOrgaoCarreira['propriedades'] = array('nome', 'poder');

    return $aOrgaoCarreira;
  }

  /**
   * Atributos do registro movimentacoesFuncionaisRpps
   * @return array
   */
  private function atributosMovimentacoesFuncionaisRPPS() {

    $aMovimentacoesFuncionais                 = array();
    $aMovimentacoesFuncionais['nome']         = 'movimentacoesFuncionaisRpps';
    $aMovimentacoesFuncionais['propriedades'] = array(
      'operacao',
      'dataSaidaCargo',
      'situacaoFuncional',
      'dataMovimentacao',
      'tipoMagisterio'
    );

    return $aMovimentacoesFuncionais;
  }

  /**
   * Preenche os valores do registro vinculosFuncionaisRpps
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheVinculosFuncionaisRPPS(Servidor $oServidor) {

    $aVinculoFuncional                                = array();
    $aVinculoFuncional["operacao"]                    = 'I';
    $aVinculoFuncional["dataExercicioCargo"]          = $oServidor->getDataAdmissao()->getDate();
    $aVinculoFuncional["dataIngressoCarreira"]        = $oServidor->getDataAdmissao()->getDate();
    $aVinculoFuncional["dataIngressoOrgao"]           = $oServidor->getDataAdmissao()->getDate();
    $aVinculoFuncional["situacaoFuncional"]           = "1";
    $aVinculoFuncional["matricula"]                   = $oServidor->getMatricula();
    $aVinculoFuncional["regime"]                      = $oServidor->getTabelaPrevidencia() == 2 ? 1 : 2; // - 1-RPPS, 2-RGPS
    $aVinculoFuncional["tipoServidor"]                = 1; //Servidor CIVIL
    $aVinculoFuncional["tipoVinculo"]                 = $oServidor->tipoVinculo;
    $aVinculoFuncional["orgao"]                       = $this->preencheOrgao($oServidor);
    $aVinculoFuncional["servidor"]                    = $this->preencheServidor($oServidor);
    $aVinculoFuncional["cargo"]                       = $this->preencheCargo($oServidor);
    $aVinculoFuncional["movimentacoesFuncionaisRpps"] = $this->getAssentamentosServidor($oServidor);

    return (object) $aVinculoFuncional;
  }

  /**
   * Preenche os valores dos registros orgao
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheOrgao(Servidor $oServidor) {

    return (object) array(
      "nome"  => $oServidor->getInstituicao()->getDescricao(),
      "poder" => $oServidor->getInstituicao()->getTipo() > 6 ? 6 : $oServidor->getInstituicao()->getTipo(),
    );
  }

  /**
   * Preenche os valores do registro servidor
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheServidor(Servidor $oServidor) {

    return (object)array(
      "nome"           => $oServidor->getCgm()->getNome(),
      "numeroCPF"      => $oServidor->getCgm()->getCpf(),
      "numeroNIT"      => $oServidor->getCgm()->getPIS(),
      "dataNascimento" => $oServidor->getCgm()->getDataNascimento(),
      "nomeMae"        => $oServidor->getCgm()->getNomeMae(),
    );
  }

  /**
   * Preenche os valores do registro cargo
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheCargo(Servidor $oServidor) {

    return (object) array(
      "nome"     => $oServidor->descricaoCargo,
      "carreira" => $this->preencheCarreira($oServidor),
    );
  }

  /**
   * Preenche os valores do registro carreira
   * @param Servidor $oServidor
   * @return object
   */
  private function preencheCarreira(Servidor $oServidor) {

    return (object) array(
      "nome"  => "Servidor Público",
      "orgao" => $this->preencheOrgao($oServidor),
    );
  }

  /**
   * Busca as movimentações por servidor
   * @param Servidor $oServidor
   * @return array
   * @throws DBException
   */
  private function getAssentamentosServidor(Servidor $oServidor) {

    $sCondicaoAssenta  = "    (((rh02_anousu, rh02_mesusu) = (extract(year from h16_dtconc), extract(month from h16_dtconc))) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) >= (extract(year from h16_dtconc), extract(month from h16_dtconc)) ";
    $sCondicaoAssenta .= "      and h16_dtterm is null) ";
    $sCondicaoAssenta .= " OR (     (rh02_anousu, rh02_mesusu) between (extract(year from h16_dtconc), extract(month from h16_dtconc))";
    $sCondicaoAssenta .= "      and (extract(year from h16_dtterm), extract(month from h16_dtterm)) ))";

    $sCamposAssenta  = " h16_dtconc, rh02_instit,";
    $sCamposAssenta .= "  case when rh05_recis is not null AND h16_dtconc < rh05_recis";
    $sCamposAssenta .= "            then null ";
    $sCamposAssenta .= "       else rh05_recis";
    $sCamposAssenta .= "   end as data_saida_cargo";
    $sCamposAssenta .= ", case ";
    $sCamposAssenta .= "       when rh05_recis is not null AND h16_dtconc < rh05_recis ";
    $sCamposAssenta .= "            then case ";
    $sCamposAssenta .= "                      when {$oServidor->getTipoAdmissao()} = 3 ";
    $sCamposAssenta .= "                           then 6 ";
    $sCamposAssenta .= "                      when {$oServidor->getTipoAdmissao()} = 4 ";
    $sCamposAssenta .= "                           then 5 ";
    $sCamposAssenta .= "                      else 1  ";
    $sCamposAssenta .= "                  end ";
    $sCamposAssenta .= "       else case ";
    $sCamposAssenta .= "                 when r59_movsef = 'U1' ";
    $sCamposAssenta .= "                      then 12 ";
    $sCamposAssenta .= "                 when r59_movsef = 'S2' OR r59_movsef = 'S3' ";
    $sCamposAssenta .= "                      then 11 ";
    $sCamposAssenta .= "                 else 2 ";
    $sCamposAssenta .= "             end ";
    $sCamposAssenta .= "   end as situacao_funcional ";

    $sSqlAssenta  = "select {$sCamposAssenta}                                                               \n";
    $sSqlAssenta .= "  from assenta                                                                         \n";
    $sSqlAssenta .= "       inner join tipoasse on h12_codigo = h16_assent                                  \n";
    $sSqlAssenta .= "                          AND h12_tipo = 'S'                                           \n";
    $sSqlAssenta .= "       inner join rhpessoalmov   on rh02_regist = h16_regist                           \n";
    $sSqlAssenta .= "                                AND rh02_anousu = {$this->iAnoInicial}                 \n";
    $sSqlAssenta .= "                                AND rh02_mesusu = {$this->iMesInicial}                 \n";
    $sSqlAssenta .= "       left  join rhpesrescisao  on rh05_seqpes = rh02_seqpes                          \n";
    $sSqlAssenta .= "       left  join rescisao       on r59_anousu  = {$this->iAnoInicial}                 \n";
    $sSqlAssenta .= "                                AND r59_mesusu  = {$this->iMesInicial}                 \n";
    $sSqlAssenta .= "                                AND r59_regime  = {$oServidor->getCodigoRegime()}      \n";
    $sSqlAssenta .= "                                AND r59_instit  = {$oServidor->getCodigoInstituicao()} \n";
    $sSqlAssenta .= "                                AND r59_causa   = rh05_causa                           \n";
    $sSqlAssenta .= "                                AND r59_caub    = rh05_caub                            \n";
    $sSqlAssenta .= " where h16_regist = {$oServidor->getMatricula()}                                       \n";
    $sSqlAssenta .= "   AND {$sCondicaoAssenta}                                                             \n";
    $sSqlAssenta .= " order by 1                                                                            \n";

    $rsAssenta = db_query($sSqlAssenta);

    if(!$rsAssenta) {
      throw new DBException('Erro ao buscar os assentamentos do servidor.');
    }
    $instancia = $this;
    $aMovimentacoesFuncionais = db_utils::makeCollectionFromRecord($rsAssenta, function ($oDadosRetorno, $instancia) {
      return $instancia->preencheMovimentacoesFuncionaisRPPS($oDadosRetorno);
    });

    return $aMovimentacoesFuncionais;
  }

  /**
   * Preenche os valores do registro movimentacoesFuncionaisRpps
   * @param stdClass $oDadosRetorno
   * @return object
   */
  public function preencheMovimentacoesFuncionaisRPPS($oDadosRetorno) {

    $aMovimentacoes                      = array();
    $aMovimentacoes['operacao']          = 'I';
    $aMovimentacoes['situacaoFuncional'] = $oDadosRetorno->situacao_funcional;
    $aMovimentacoes['dataMovimentacao']  = $oDadosRetorno->h16_dtconc;
    $aMovimentacoes['tipoMagisterio']    = '1';

    if(!empty($oDadosRetorno->data_saida_cargo)) {
      $aMovimentacoes['dataSaidaCargo'] = $oDadosRetorno->data_saida_cargo;
    }

    return (object) $aMovimentacoes;
  }

  /**
   * Realiza as validações dos campos
   * @param stdClass $oDadosRetorno
   * @return array
   */
  private function validarDados(Servidor $oServidor) {

    $aErrosRegistro = array();
    $lPisValido     = DBString::isPIS($oServidor->getCgm()->getPIS());
    $lCpfValido     = DBString::isCPF($oServidor->getCgm()->getCpf());

    if(!$lPisValido) {
      $aErrosRegistro[] = $this->getErro($oServidor, "PIS '{$oServidor->getCgm()->getPIS()}' é inválido.");
    }

    if(!$lCpfValido) {
      $aErrosRegistro[] = $this->getErro($oServidor, "CPF '{oServidor->getCgm()->getCpf()}' é inválido.");
    }

    if($oServidor->getCgm()->getDataNascimento() == '') {
      $aErrosRegistro[] = $this->getErro($oServidor, "Data de nascimento não informada.");
    }

    if($oServidor->getCgm()->getNomeMae() == '') {
      $aErrosRegistro[] = $this->getErro($oServidor, "Nome da mãe não informado.");
    }

    return $aErrosRegistro;
  }

  /**
   * Monta o array dos erros com os dados para apresentação no relatório
   * @param stdClass $oDadosRetorno
   * @param $sErro
   * @return array
   */
  private function getErro(Servidor $oServidor, $sErro) {

    return array(
      InstituicaoRepository::getInstituicaoByCodigo($oServidor->getInstituicao()->getCodigo())->getDescricao(),
      $oServidor->getCgm()->getCodigo() . " - " . $oServidor->getCgm()->getNome(),
      $sErro,
    );
  }

  /**
   * @return string
   */
  public function getAnoInicial() {

    return $this->iAnoInicial;
  }

  /**
   * @return int
   */
  public function getMesInicial() {

    return $this->iMesInicial;
  }

  /**
   * @return string
   */
  public function getAnoFinal() {

    return $this->iAnoFinal;
  }

  /**
   * @return int
   */
  public function getMesFinal() {

    return $this->iMesFinal;
  }

}
