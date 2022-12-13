<?PHP
/*
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

/**
 * Classe generica para relatórios da folha de pagamento 
 * relacionados ao funcionarios
 * @author   Rafael Lopes rafael.lopes@dbseller.com.br	
 * @package  Pessoal
 * @revision $Author: dbrenan $
 * @version  $Revision: 1.12 $
 *
 */
abstract class RelatorioFolhaPagamento {
   
  /**
   *
   * define o tipo de filtro agrupador
   * @var string
   */
  private   $sTipoFiltro         = null;

  /**
   * codigo da instituicao
   *
   * @var integer
   */
  protected $iInstituicao        = null;

  /**
   * string que guarda valor do campo r44_where, da tabela selecao
   *
   * @var string
   */
  private   $sWhereSelecao       = "";

  /**
   * string que guarda valor do campo r44_where, da tabela selecao
   *
   * @var string
   */
  private   $sWhereRegime        = "";

  /**
   * string para a comparação do numero da folha complementar
   *
   * @var string
   */
  private   $sWhereComplementar  = "";

  /**
   * Atributo para retornar os campos da consulta
   *
   * @var string
   */
  private   $sCampos             = "";

  /**
   * array com tipos de folhas a serem emitidos
   * 1 - salario            - r14 - gerfsal
   * 2 - folha complementar - r48 - gerfcom
   * 3 - rescisao           - r20 - gerfres
   * 4 - 13 salario         - r35 - gerfs13
   * 5 - adiantamento       - r22 - gerfadi
   * 6 - ferias             - r31 - gerffer
   * 7 - ponto fixo         - r53 - gerffx
   * 8 - provisao de Ferias - r93 - gerfprovfer
   * 9 - provisao de 13o    - r94 - gerfprovs13
   * @var array
   */
  private   $aTipoFolha          = array();

  /**
   * variavel que define tipo agrupamento do relatorio
   *
   * @var integer
   * 0 - geral
   * 1 - lotacao
   * 2 - orgao
   * 3 - matricula
   * 4 - locais de trabalho
   * 5 - recursos
   */
  private   $iAgrupador          = 0;

  /**
   * Valida o tipo de AGRUPADOR
   */
  private   $iTipoAgrupador      = null;

  /**
   * opção do filtro do agrupador
   * selecionados
   * intervalo
   *
   * @var object
   */
  private   $oFiltroAgrupador    = null;

  /**
   *
   * ano da competencia do relatorio
   *
   * @var integer
   */
  protected $iAno                = null;

  /**
   * Mes da competencia do relatorio
   *
   * @var integer
   */
  protected $iMes                = null;

  /**
   * define a seleçao de servidores cadastrados da tabela seleção
   * caso nao venha selecionado, exibe todos
   *
   * @var integer
   */
  private   $iCodigoSelecao      = null;

  /**
   * Seleciona o regime dos servidores
   *
   * 0 - Todos
   * 1 - Estatutário
   * 2 - CLT
   * 3 - Extra Quadro
   *
   * @var integer
   */
  private   $iRegime             = 0;

  /**
   * numero da folha complentar, somente utilizado quando a opçao complementar estiver dentro do tipo folha
   *
   * @var integer
   */
  private   $iCodigoComplementar = 0;

  /**
   * Array de dados de retorno do relatorio
   *
   * @var array
   */
  private   $aDadosRelatorio     = array();

  /**
   * Construtor da classe
   *
   */
  public    function __construct(){
     
    $this->iInstituicao = db_getsession("DB_instit");
     
  }

  /**
   * Seter para instituicao, caso queremos defini-la
   *
   * @param integer $iInstituicao
   */
  public    function setInstituicao($iInstituicao){
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Setamos o valor da string de campos utilizados em cada consulta
   *
   * @param string $sCampos
   */
  public    function setCamposQuery($sCampos){
    $this->sCampos = $sCampos;
  }

  /**
   * Define o tipo de agrupador
   * @param $iAgrupador
   */
  public    function setAgrupador($iAgrupador) {
    $this->iAgrupador = $iAgrupador;
  }

  /**
   * define os agrupadores e seus filtros
   * @param $oFiltroAgrupador
   */
  public    function setFiltroAgrupador() {
     
    $iArgumentos      = func_num_args();
    $aArgumentos      = func_get_args();
     
    $oFiltroAgrupador = new stdClass();
    /**
     * Caso o haja apenas 1 parametro a funcao e este for um array seta a propriedade aListaFiltro
     * Caso o numero de argumento seja = 2 e eles forem string     seta as propriedades sFiltroInicio e sFiltroFim
     * Caso contrario diparara exception
     */
    if ( $iArgumentos == 1 && is_array( $aArgumentos[0] ) ) {

      $oFiltroAgrupador->aListaFiltro  = $aArgumentos[0];
      $this->sTipoFiltro = "SELECIONADOS";
    } elseif ( $iArgumentos == 2 && is_string($aArgumentos[0]) && is_string($aArgumentos[1] ) ) {

      $oFiltroAgrupador->sFiltroInicio = $aArgumentos[0];
      $oFiltroAgrupador->sFiltroFim    = $aArgumentos[1];
      $this->sTipoFiltro = "INTERVALO";

    } else {
      throw new ErrorException("Tipos de parametros incorretos");
    }
     
    $this->oFiltroAgrupador = $oFiltroAgrupador;
  }

  /**
   * Define a competencia das folhas selecionadas
   *
   * @param integer $iMes
   * @param integer $iAno
   * @return boolean || object
   */
  public    function setCompetencia($iMes, $iAno) {
     
    if($iMes > 12 || strlen($iAno == 4)){
      return false;
    }
    $this->iAno = $iAno;
    $this->iMes = $iMes;
  }

  /**
   * Define o tipo de seleção a ser utilzada no relatório
   * @param $iCodigoSelecao
   */
  public    function setSelecao($iCodigoSelecao) {

    if ( !empty($iCodigoSelecao) && $iCodigoSelecao <> 0 ) {

      $oSelecao             = db_utils::getDao("selecao", true);
      $rsSelecao            = $oSelecao->sql_record($oSelecao->sql_query_file($iCodigoSelecao ,$this->iInstituicao," r44_where "));
      $sSelecaoWhere        = db_utils::fieldsMemory($rsSelecao, 0)->r44_where;
      $this->iCodigoSelecao = $iCodigoSelecao;
      $this->sWhereSelecao  = trim($sSelecaoWhere) != "" ?  " and ".$sSelecaoWhere : "";
    } else {
      $this->iCodigoSelecao = 0;
      $this->sWhereSelecao  = "";
    }
  }

  /**
   * Define o regime da folha aser utilizado
   * @param $iRegime
   */
  public    function setRegime($iRegime) {

    if($iRegime != 0){
      $this->sWhereRegime  = " and rh30_regime = {$iRegime} ";
      $this->iRegime = $iRegime;
    }
  }

  /**
   * Define o código da folha complementar da competencia
   * @param $iCodigoComplementar
   */
  public    function setCodigoComplementar($iCodigoComplementar) {

    if (!empty($iCodigoComplementar) && $iCodigoComplementar > 0 ) {
      $this->sWhereComplementar  = " and r48_semest = {$iCodigoComplementar}";
      $this->iCodigoComplementar = $iCodigoComplementar;
    }
  }

  /**
   * Retorna um array de strings sql
   * @return array
   */
  protected function retornaSQLBaseRelatorio($sWhere = null, $sOrderBy = null, $sCampos = '*') {
    
    $aRetorno         = array();
    $oFiltroAgrupador = $this->makeDadosAgrupador();
     
    if ( !empty($sWhere) ) {
       
      $sWhere   = " and {$sWhere}";
    }
    if (empty($sOrderBy) ) {
       
      $sOrderBy = $oFiltroAgrupador->sOrderBy;
    }
    if ( !empty($sCampos) ) {
       
      $sCampos = "{$sCampos},";
    } 
    /**
     * instancia a classe DAO rhpessoal
     */
    $oDaoRHPessoal  = db_utils::getDao("rhpessoal",true);

    
    /**
     * Percorre os tipos de folha selecionados
     */
    foreach($this->aTipoFolha as $iIndiceTipoFolha => $oTipoFolha) {
      
      $sWhereSqlBase = " rh01_regist is not null and {$oTipoFolha->sSigla}_instit = " . db_getsession("DB_instit"); 
      if (!empty($this->sWhereSelecao)) {
        $sWhereSqlBase .= $this->sWhereSelecao;
      }
      if (!empty($sWhere)) {
        $sWhereSqlBase .= " {$sWhere}";
      }
    
      /**
       * Pega o relatório base da folha de pagamento
       */
      $sSqlBase1 = $oDaoRHPessoal->sql_query_baseRelatorios($oTipoFolha->sTabela,
                                                            $oTipoFolha->sSigla,
                                                            $this->iAno,
                                                            $this->iMes,
                                                            db_getsession("DB_instit"),
                                                            "*",
                                                            $sWhereSqlBase,
                                                            null
                                                           );
      /**
       * Adiciona campos de validações adicionais ao sql base da folha
       */
      $sSqlBase   = "	select distinct                                                                                \n";
      $sSqlBase  .= "        {$sCampos}                                                                              \n";
      $sSqlBase  .= "        rh01_regist  as matricula_servidor,                                                     \n";
      $sSqlBase  .= "        rh01_numcgm,                                                                            \n";
      $sSqlBase  .= "        rh02_codreg  as classe,                                                                 \n";
      $sSqlBase  .= "        z01_nome     as nome_servidor,                                                          \n";
      $sSqlBase  .= "        z01_nasc     as data_nascimento,                                                        \n";
      $sSqlBase  .= "        {$oTipoFolha->sSigla}_valor    as valor_rubrica,                                        \n";
      $sSqlBase  .= "        {$oTipoFolha->sSigla}_quant    as quant_rubrica,                                        \n";
      $sSqlBase  .= "        {$oTipoFolha->sSigla}_rubric   as rubrica,                                              \n";
      $sSqlBase  .= "        {$oTipoFolha->sSigla}_pd       as provento_desconto,                                    \n";
      
      if ( in_array( $oTipoFolha->sSigla, array('r93', 'r20', 'r31') ) ) {
        $sSqlBase  .= "        {$oTipoFolha->sSigla}_tpp    as tipo_folha,                                           \n";
      }
      $sSqlBase  .= "        rh27_descr   as descr_rubrica,                                                          \n";
      $sSqlBase  .= "        rh37_funcao  as codigo_cargo,                                                           \n"; // Invertido por lógica no banco de dados estar errada
      $sSqlBase  .= "        rh37_descr   as descr_cargo,                                                            \n"; // Invertido por lógica no banco de dados estar errada
      $sSqlBase  .= "        r70_codigo   as codigo_lotacao,                                                         \n";
      $sSqlBase  .= "        r70_estrut   as estrutural_lotacao,                                                     \n";
      $sSqlBase  .= "        r70_descr    as descr_lotacao,                                                          \n";
      $sSqlBase  .= "        rh04_codigo  as codigo_funcao,                                                          \n"; // Invertido por lógica no banco de dados estar errada
      $sSqlBase  .= "        rh04_descr   as descr_funcao,                                                           \n"; // Invertido por lógica no banco de dados estar errada
      $sSqlBase  .= "        rh02_hrsmen  as horas_mensais,                                                          \n";
      $sSqlBase  .= "        rh01_admiss  as data_admissao,                                                          \n";
      $sSqlBase  .= "        rh02_tbprev  as tabela_previdencia,                                                     \n";
      $sSqlBase  .= "        provdesc,                                                                               \n";
      $sSqlBase  .= "        {$oFiltroAgrupador->sCampos},                                                           \n";
      $sSqlBase  .= "        rh30_vinculo as vinculo,                                                                \n";
      $sSqlBase  .= "        ( select case when r45_regist is not null                                               \n";
      $sSqlBase  .= "                       and (   max(r45_dtreto) is null                                          \n";
      $sSqlBase  .= "                            or max(r45_dtreto) > '".$this->iAno."-".$this->iMes."-01')          \n";
      $sSqlBase  .= "                      then                                                                      \n";
      $sSqlBase  .= "                          case r45_situac                                                       \n";
      $sSqlBase  .= "                            when 2 then 'S/Remuneração'                                         \n";
      $sSqlBase  .= "                            when 3 then 'Acidente'                                              \n";
      $sSqlBase  .= "                            when 4 then 'S.Militar'                                             \n";
      $sSqlBase  .= "                            when 5 then 'Gestante'                                              \n";
      $sSqlBase  .= "                            when 6 then 'Doença'                                                \n";
      $sSqlBase  .= "                            when 8 then 'Doença'                                                \n";
      $sSqlBase  .= "                          else 'S/Venc.' end                                                    \n";
      $sSqlBase  .= "                      else 'Normal'                                                             \n";
      $sSqlBase  .= "                 end as situacao_funcionario                                                    \n";
      $sSqlBase  .= "            from afasta                                                                         \n";
      $sSqlBase  .= "           where r45_anousu = {$this->iAno}                                                     \n";
      $sSqlBase  .= "             and r45_mesusu = {$this->iMes}                                                     \n";
      $sSqlBase  .= "             and r45_regist = rh01_regist                                                       \n";
      $sSqlBase  .= "             and (    r45_regist is null                                                        \n";
      $sSqlBase  .= "                   or r45_regist is not null                                                    \n";
      $sSqlBase  .= "                  and (r45_dtreto is null or r45_dtreto > '".$this->iAno."-".$this->iMes."-01') \n";
      $sSqlBase  .= "                 )                                                                              \n";
      $sSqlBase  .= "           group by r45_regist, r45_dtreto, r45_situac                                          \n";
      $sSqlBase  .= "           order by r45_dtreto limit 1                                                          \n";
      $sSqlBase  .= "        ) as situacao_funcionario,                                                              \n";
      $sSqlBase  .= "        r02_descr as padrao_descr                                                               \n";
      $sSqlBase  .= "   from ({$sSqlBase1}) as sql_base 		                                                         \n";
      $sSqlBase  .= "  where rh01_regist is not null                                                                 \n";
      $sSqlBase  .= "        {$this->sWhereSelecao}                                                                  \n";
      $sSqlBase  .= "        {$this->sWhereRegime}                                                                   \n";
      if($iIndiceTipoFolha == 2 ){
        $sSqlBase  .= "        {$this->sWhereComplementar}                                                           \n";
      }
      $sSqlBase  .= "        {$oFiltroAgrupador->sWhere}                                                             \n";
      $sSqlBase  .= "        {$sWhere}                                                                               \n";

      if ( !empty($sOrderBy) ) {
        $sSqlBase  .= "  order by {$sOrderBy}                                                                        \n";
      }
      $aRetorno[$oTipoFolha->sTabela] = $sSqlBase;

    }
    return $aRetorno;
  }

  /**
   * Adiciona um tipo de folha a lista de folhas a serem geradas
   * @param integer $iTipoFolha
   * @return this
   */
  public    function addTipoFolha($iTipoFolha) {

    $oTipoFolha = new stdClass();
    switch ($iTipoFolha){

    		default :
    		  return false;
    		break;
    		case "1":
    		  $oTipoFolha->iCodigo     = $iTipoFolha;
    		  $oTipoFolha->sTabela     = "gerfsal";
    		  $oTipoFolha->sSigla  = "r14";
    		  break;
    		case "2":
    		  $oTipoFolha->iCodigo = $iTipoFolha;
    		  $oTipoFolha->sTabela = "gerfcom";
    		  $oTipoFolha->sSigla  = "r48";
        break;
    		case "3":
    		  $oTipoFolha->iCodigo = $iTipoFolha;
    		  $oTipoFolha->sTabela = "gerfres";
    		  $oTipoFolha->sSigla  = "r20";
        break;
    		case "4":
    		  $oTipoFolha->iCodigo = $iTipoFolha;
    		  $oTipoFolha->sTabela = "gerfs13";
    		  $oTipoFolha->sSigla  = "r35";
        break;
    		case "5":
    		  $oTipoFolha->iCodigo = $iTipoFolha;
    		  $oTipoFolha->sTabela = "gerfadi";
    		  $oTipoFolha->sSigla  = "r22";
        break;
    }
    $this->aTipoFolha[] = $oTipoFolha;
    
  }

  /**
   * Processa tipo de filtro agrupador
   * e retorna string where
   */
  private   function makeDadosAgrupador() {

    if($this->sTipoFiltro == "SELECIONADOS") {

      $sLista = implode("', '", $this->oFiltroAgrupador->aListaFiltro);

      switch ($this->iAgrupador) {
        default:
          $sRetorno = "";
        break;
        case 1: //lotacao
          $sLista   = "'" . implode("', '", $this->oFiltroAgrupador->aListaFiltro) . "'";
          $sRetorno = " r70_estrut  in ({$sLista}) ";
          break;
        case 2://orgao
          $sRetorno = " o40_orgao   in ({$sLista})";
          break;
        case 3://matricula
          $sRetorno = " rh01_regist in ({$sLista})";
          break;
        case 4://locais de trabalho
          $sLista   = "'" . implode("', '", $this->oFiltroAgrupador->aListaFiltro) . "'";
          $sRetorno = " rh55_estrut in ({$sLista})";
          break;
      }
    } elseif ($this->sTipoFiltro == "INTERVALO") {

      switch ($this->iAgrupador) {
        default:
          $sRetorno = "";
        break;
        case 1: //lotacao
          $sRetorno = " r70_estrut  between '{$this->oFiltroAgrupador->sFiltroInicio}' and  '{$this->oFiltroAgrupador->sFiltroFim}' ";
          break;
        case 2://orgao
          $sRetorno = " o40_orgao   between  {$this->oFiltroAgrupador->sFiltroInicio}  and   {$this->oFiltroAgrupador->sFiltroFim}  ";
          break;
        case 3://matricula
          $sRetorno = " rh01_regist between  {$this->oFiltroAgrupador->sFiltroInicio}  and   {$this->oFiltroAgrupador->sFiltroFim}  ";
          break;
        case 4://locais de trabalho
          $sRetorno = " rh55_estrut between '{$this->oFiltroAgrupador->sFiltroInicio}' and  '{$this->oFiltroAgrupador->sFiltroFim}' ";
          break;
      }
    }
    /**
     * dados especificos do agrupamento
     */
    switch ($this->iAgrupador) {
      default:
        $sCampos  = " '0'         as codigofiltro, \n";
        $sCampos .= " 'GERAL'     as descrifiltro, \n";
        $sCampos .= " '0'         as estrutfiltro  \n";
  
        $sOrderBy = " z01_nome,                    \n";
        $sOrderBy.= " matricula_servidor,          \n";
        $sOrderBy.= " rubrica                      \n";
      break;
      case 1: //lotacao
        $sCampos  = " r70_codigo  as codigofiltro, \n";
        $sCampos .= " r70_descr   as descrifiltro, \n";
        $sCampos .= " r70_estrut  as estrutfiltro  \n";

        $sOrderBy = " estrutfiltro,                \n";
        $sOrderBy.= " z01_nome,                    \n";
        $sOrderBy.= " matricula_servidor,          \n";
        $sOrderBy.= " rubrica                      \n";
        break;
      case 2://orgao
        $sCampos  = " o40_orgao   as codigofiltro, \n";
        $sCampos .= " o40_descr   as descrifiltro, \n";
        $sCampos .= " o40_orgao   as estrutfiltro  \n";

        $sOrderBy = " codigofiltro,                \n";
        $sOrderBy.= " z01_nome,                    \n";
        $sOrderBy.= " matricula_servidor,          \n";
        $sOrderBy.= " rubrica                      \n";
        break;
      case 3://matricula
        $sCampos  = " rh01_regist as codigofiltro, \n";
        $sCampos .= " rh01_regist as descrifiltro, \n";
        $sCampos .= " rh01_regist as estrutfiltro  \n";
        
        $sOrderBy = " matricula_servidor,          \n";
        $sOrderBy.= " rubrica                      \n";
        break;
      case 4://locais de trabalho
        $sCampos  = " rh55_codigo as codigofiltro, \n";
        $sCampos .= " rh55_descr  as descrifiltro, \n";
        $sCampos .= " rh55_estrut as estrutfiltro  \n";

        $sOrderBy = " estrutfiltro,                \n";
        $sOrderBy.= " z01_nome,                    \n";
        $sOrderBy.= " matricula_servidor,          \n";
        $sOrderBy.= " rubrica                      \n";
        break;
      case 5://recurso
        $sCampos  = " rh25_codigo as codigofiltro, \n";
        $sCampos .= " o15_descr   as descrifiltro, \n";
        $sCampos .= " rh25_codigo as estrutfiltro  \n";
        
        $sOrderBy = " estrutfiltro,                \n";
        $sOrderBy.= " z01_nome,                    \n";
        $sOrderBy.= " matricula_servidor,          \n";
        $sOrderBy.= " rubrica                      \n";
        break;
    }
    $oFiltro = new stdClass();
    $oFiltro->sWhere   = !empty($sRetorno) ? " and ".$sRetorno : "";
    $oFiltro->sCampos  = $sCampos;
    $oFiltro->sOrderBy = $sOrderBy;
    return $oFiltro;
  }
}