<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_conplano.php");
require_once("classes/db_orcreceita_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
require_once("classes/db_conlancamval_classe.php");
require_once("classes/db_conplano_classe.php");
require_once("classes/db_contranslan_classe.php");
require_once("classes/db_contranslr_classe.php");
require_once("classes/db_conplanoexe_classe.php");
require_once("classes/db_saltes_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empautitem_classe.php");
require_once("classes/db_solicitemele_classe.php");
require_once("classes/db_pcmaterele_classe.php");
require_once("classes/db_emprestotipo_classe.php");
require_once("classes/db_db_config_classe.php");
require_once "model/transacaoContabilLancamento.model.php";
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clorcreceita         = new cl_orcreceita;
$clconplanoreduz      = new cl_conplanoreduz;
$clconlancamval       = new cl_conlancamval;
$clconplano           = new cl_conplano;
$clconplanoexe        = new cl_conplanoexe;
$db_conplano          = new db_conplano;
$clemprestotipo       = new cl_emprestotipo;
$cldbconfig           = new cl_db_config;
$oDaoConplanoreduzCgm = db_utils::getDao("conplanoreduzcgm");
// dbforms
$clsaltes             = new cl_saltes;
// Para incluir conta na tesouraria
$clempagetipo         = new cl_empagetipo;
// Para incluir conta pagadora do fornecedor

// Verifica se existe reduzido a autorização de empenho, solicitação de compras ou cadastro de materiais do compras
$clempautitem    = new cl_empautitem;
$clsolicitemele  = new cl_solicitemele;
$clpcmaterele    = new cl_pcmaterele;

$clrotulo = new rotulocampo;
$clrotulo->label("c60_codcon");
$clrotulo->label("c60_descr");
$clrotulo->label("c60_anousu");
$clrotulo->label("c61_reduz");
$clrotulo->label("c61_instit");
$clrotulo->label("z01_numcgm");
$clrotulo->label("c22_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("nomeinst");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("c47_debito");
$clrotulo->label("c47_credito");
$clrotulo->label("c47_tiporesto");
$clrotulo->label("c61_contrapartida");

$db_opcao = 1;
$db_botao = true;
$anousu   = db_getsession("DB_anousu");

$res_conplano = $clconplano->sql_record($clconplano->sql_query_file(@$c60_codcon,$anousu,"c60_estrut,c60_codsis"));
if ($clconplano->numrows > 0) {
  db_fieldsmemory($res_conplano,0);
}

$sConta = substr($c60_estrut, 0,5);
$iAno   = $anousu;
$sSqlGrupoConta  = "select fc_conplano_grupo({$iAno},'{$sConta}%',9005) as grupo_rp_processado,";
$sSqlGrupoConta .= "fc_conplano_grupo({$iAno},'$sConta%',9006) as grupo_rp_nao_processado";
$rsGrupoConta    = db_query($sSqlGrupoConta);
$lContaRP        = false;
$lTemContaSaltes = false;


/* regras
- é permitido incluir dois reduzidos no mesmo estrutural na mesma instituição, desde que sejam recursos diferentes
- não pode excluir reduzidos que tenham lançamentos contábeis no conlancamval
- se a conta mãe for analitica, a aba de reduzidos não pode aparecer (?)
- se conta superior for analitica, não é permitido incluir reduzidos
- se a conta mãe for sintética, a conta atual pode se tornar analitica
*/

$iCodigoPCASP = $c60_codcon;
if (isset($incluir) && $incluir == "Incluir") {


  // verifica se já existe conta reduzida na mesma instit
  $res = $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null, "*", null, "c61_instit=$c61_instit and c61_codcon=$c60_codcon and c61_anousu=$anousu"));
  if ($clconplanoreduz->numrows > 0) {
    db_msgbox("Já existe nessa Instituição uma conta reduzida! ");
    db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
  }

  $res_plano = $clconplano->sql_record($clconplano->sql_query_file($c60_codcon,$anousu,"c60_estrut,c60_descr,c60_codsis"));
  if ($clconplano->numrows > 0) {
    db_fieldsmemory($res_plano,0);
    if (substr($c60_estrut,0,1)=="3") {
      if ($c61_contrapartida==0||$c61_contrapartida=="") {
        db_msgbox("Contra Partida para esta conta despesa é obrigatória");
        db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
      }
    }
  }

  if (!isset($c61_contrapartida)) {
    $c61_contrapartida = "0";
  }
  $erro = false;
  db_inicio_transacao();

  $sCamposConplano = "max(c60_anousu) as c60_anousu, c60_estrut";
  $sWhereConplano  = "c60_codcon = {$c60_codcon} group by c60_estrut";
  $sSqlConPlano    = $clconplano->sql_query_file(null, null, $sCamposConplano, null, $sWhereConplano);
  echo "{$sSqlConPlano}<br><br>";
  $res_conplano = $clconplano->sql_record($sSqlConPlano);
  $numrows      = $clconplano->numrows;
  if ($numrows > 0) {
    db_fieldsmemory($res_conplano,0);

    /**
     * Buscamos os dados referentes a esta conta no plano orçamentário. Isso porque na inclusão de contas, incluímos
     * na tabela conplanoorcamento quando o ANO for >= 2013
     */
    if (USE_PCASP) {

      $oDaoConPlanoOrcamento = db_utils::getDao('conplanoorcamento');
      $sSqlBuscaPlano        = $oDaoConPlanoOrcamento->sql_query_file(null,
                                                                      null,
                                                                      "max(c60_anousu) as c60_anousu, c60_codcon",
                                                                      null,
                                                                      "c60_estrut = '{$c60_estrut}' group by c60_codcon");
      $rsBuscaPlano = $oDaoConPlanoOrcamento->sql_record($sSqlBuscaPlano);
      if ($oDaoConPlanoOrcamento->numrows > 0) {

        $oDadoPlanoOrcamento        = db_utils::fieldsMemory($rsBuscaPlano, 0);
        $c60_anousu                 = $oDadoPlanoOrcamento->c60_anousu;
        $iCodigoContaPlanoOrcamento = $oDadoPlanoOrcamento->c60_codcon;
      }
    }
    $contador = $c60_anousu - $anousu;
  }

  $clconplanoreduz->c61_codcon        = $c60_codcon;
  $clconplanoreduz->c61_anousu        = $anousu;
  $clconplanoreduz->c61_instit        = $c61_instit;
  $clconplanoreduz->c61_codigo        = $c61_codigo ;
  $clconplanoreduz->c61_contrapartida = $c61_contrapartida;
  $res = $clconplanoreduz->incluir("",$anousu);

  $c61_reduz = $clconplanoreduz->c61_reduz;

  if ($clconplanoreduz->erro_status == 0) {

    db_msgbox($clconplanoreduz->erro_msg);
    $erro = true;

  }

  $clconplanoexe->c62_anousu = $anousu;
  $clconplanoexe->c62_reduz =  $c61_reduz;
  $clconplanoexe->c62_codrec = $clconplanoreduz->c61_codigo;
  $clconplanoexe->c62_vlrcre = '0';
  $clconplanoexe->c62_vlrdeb = '0';

  $res_conplanoexe = $clconplanoexe->incluir($anousu,$c61_reduz);

  if (!$res_conplanoexe) {

    db_msgbox($clconplanoexe->erro_msg);
    $erro = true;

  }

  $anousu_cont = 0;
  for ($i=0; $i < $contador; $i++) {

    $iAnoContador = ($anousu + $i)+1;
    $anousu_cont  = $iAnoContador;
    if ( $iAnoContador <= 2012 || !USE_PCASP) {

      $clconplanoreduz->c61_codcon = $c60_codcon;
      $clconplanoreduz->c61_anousu = $iAnoContador;
      $clconplanoreduz->c61_instit = $c61_instit;
      $clconplanoreduz->c61_codigo = $c61_codigo;
      $clconplanoreduz->c61_contrapartida = $c61_contrapartida;
      $clconplanoreduz->incluir($c61_reduz,$iAnoContador);
      if ($clconplanoreduz->erro_status == '0') {
        $erro = true;
        break;
      }

      $clconplanoexe->c62_anousu = $anousu_cont;
      $clconplanoexe->c62_reduz =  $c61_reduz;
      $clconplanoexe->c62_codrec = $clconplanoreduz->c61_codigo;
      $clconplanoexe->c62_vlrcre = '0';
      $clconplanoexe->c62_vlrdeb = '0';
      $res_conplanoexe = $clconplanoexe->incluir($anousu_cont,$c61_reduz);

      if (!$res_conplanoexe) {
        db_msgbox($clconplanoexe->erro_msg);
        $erro = true;
        break;
      }
    } else {

      /**
       * Validamos se o c61_reduz da tabela conplanoorcamentoanalitica não está vazio e o ano do contador seja maior que
       * 2013. Isso porque não podemos incluir reduzidos diferentes pra uma mesma conta (c60_codcon). Desta forma, ele utiliza o último
       * utilizado
       */
      $oDaoConplanoOrcamentoAnalitica = db_utils::getDao('conplanoorcamentoanalitica');

      $iCodigoReduzido = null;
      if (isset($oDaoConplanoOrcamentoAnalitica->c61_Reduz) && $oDaoConplanoOrcamentoAnalitica->c61_Reduz != "" && $iAnoContador > 2013) {
        $iCodigoReduzido = $oDaoConplanoOrcamentoAnalitica->c61_Reduz;
      }

      $oDaoConplanoOrcamentoAnalitica->c61_codcon        = $iCodigoContaPlanoOrcamento;
      $oDaoConplanoOrcamentoAnalitica->c61_anousu        = $iAnoContador;
      $oDaoConplanoOrcamentoAnalitica->c61_reduz         = $iCodigoReduzido;
      $oDaoConplanoOrcamentoAnalitica->c61_instit        = $clconplanoreduz->c61_instit;
      $oDaoConplanoOrcamentoAnalitica->c61_codigo        = $clconplanoreduz->c61_codigo;
      $oDaoConplanoOrcamentoAnalitica->c61_contrapartida = $clconplanoreduz->c61_contrapartida;
      $oDaoConplanoOrcamentoAnalitica->incluir($iCodigoReduzido, $iAnoContador);
      if ($oDaoConplanoOrcamentoAnalitica->erro_status == "0") {
        db_msgbox($oDaoConplanoOrcamentoAnalitica->erro_msg);
        $erro = true;
        break;
      }
    }
  }

  if ($c60_codsis == 6) {
    // Faz inclusao da conta na tesouraria e conta pagadora de fornecedor quando sistema financeiro for 6 - FINANCEIRO - BANCOS
    $clsaltes->k13_conta         = $clconplanoreduz->c61_reduz;
    $clsaltes->k13_reduz         = $clconplanoreduz->c61_reduz;
    $clsaltes->k13_saldo         = "0";
    $clsaltes->k13_vlratu        = null;
    $clsaltes->k13_ident         = "";

    $dtAtualizacao = explode("-",date("Y-m-d",db_getsession("DB_datausu")),3);
    $dtAno         = $dtAtualizacao[0];
    $dtMes         = $dtAtualizacao[1];
    $dtDia         = $dtAtualizacao[2];
    $dtAtualizacao = date('Y-m-d', mktime(0,0,0, $dtMes, $dtDia-1, $dtAno));

    $clsaltes->k13_datvlr        = $dtAtualizacao;
    $clsaltes->k13_dtimplantacao = date("Y-m-d",db_getsession("DB_datausu"));
    $clsaltes->k13_descr         = substr($c60_descr,0,40);
    $clsaltes->k13_limite        = null;

    $clsaltes->incluir($clconplanoreduz->c61_reduz);
    if ($clsaltes->erro_status == 0) {
      db_msgbox($clsaltes->erro_msg);
      $erro = true;
    }

    $clempagetipo->e83_descr     = $c60_descr;
    $clempagetipo->e83_conta     = $clconplanoreduz->c61_reduz;
    $clempagetipo->e83_codmod    = "2";
    // CHEQUE
    $clempagetipo->e83_convenio  = "0";
    $clempagetipo->e83_codigocompromisso = "00";
    $iProximoCheque = $clempagetipo->getMaxCheque($clconplanoreduz->c61_reduz);
    if (empty($iProximoCheque)) {
      $iProximoCheque = 0;
    }
    $clempagetipo->e83_sequencia = "{$iProximoCheque}";

    $clempagetipo->incluir(null);
    if ($clempagetipo->erro_status == 0) {
      db_msgbox($clempagetipo->erro_msg);
      $erro = true;
    }
  }

  if ($c22_numcgm != '') {

    $oDaoConplanoreduzCgm->c22_anousu = $anousu;
    $oDaoConplanoreduzCgm->c22_reduz  = $clconplanoreduz->c61_reduz;
    $oDaoConplanoreduzCgm->c22_numcgm = $c22_numcgm;
    $oDaoConplanoreduzCgm->incluir(null);
    if ($oDaoConplanoreduzCgm->erro_status == 0) {

      db_msgbox($oDaoConplanoreduzCgm->erro_msg);
      $erro = true;

    }
  }

  $db_opcao = 1;
  // efetua inserção nas transações de receita, despesa..etc
  $db_conplano->evento($clconplanoreduz->c61_reduz, $c61_contrapartida, db_getsession("DB_anousu"), $clconplanoreduz->c61_instit);
  // ---------------------------
  $c60_codcon = $clconplanoreduz->c61_codcon;
  // um furo

  if (isset($c60_estrut) && substr(@$c60_estrut,0,1) == "3") {
    if ($c61_contrapartida==0||$c61_contrapartida=="") {
      db_msgbox("Contra Partida para esta conta despesa é obrigatória");
      db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
    }
  }

  /**
   * Caso a conta for de RP, é obrigatrio a informacao dos campos para inclusao da transacao para lancamentos contabeis;
   *
   */
  if ($lContaRP && !$erro) {

    try {

      foreach  ($aDocumentosRP as $iCodDoc => $oDocumento) {

        $oTransacao = new TransacaoContabil($iCodDoc, db_getsession("DB_instit"));
        $oPrimeiroLancamento= $oTransacao->getPrimeiroLancamento();
        $iCredito           = $aDocumentosRP[$iCodDoc]->credita!=null?${$aDocumentosRP[$iCodDoc]->credita}:"0";
        $iDebito            = $aDocumentosRP[$iCodDoc]->debita!=null?${$aDocumentosRP[$iCodDoc]->debita}:"0";
        $oPrimeiroLancamento->addConta($anotransacao, $iCredito, $iDebito, $clconplanoreduz->c61_instit,
                                      $c47_tiporesto);

         $oPrimeiroLancamento->save();
        /**
         * incluimos os outros lancamentos (Apenas duplicamos a ultima conta lancada, modificando o ano para o
         * ano informado pelo usuario
         */
        $aLancamentos = $oTransacao->getLancamentos();
        foreach ($aLancamentos as $oLancamento) {

          if (strtolower(substr($oLancamento->getObservacao(),0,3)) != "pri") {

            $aContas      = $oLancamento->getContas();
            $oUltimaConta = end($aContas);
            $oLancamento->addConta($anotransacao, $oUltimaConta->credito,
                                   $oUltimaConta->debito,
                                   $clconplanoreduz->c61_instit,
                                   $oUltimaConta->tiporesto);
            $oLancamento->save();
          }
        }
      }
    } catch (Exception $eErro) {

      $erro = true;
      db_msgbox(str_replace("\n","\\n",$eErro->getMessage()));

    }
  }
  //$erro = true;
  db_fim_transacao($erro);
}
if (isset($alterar) && $alterar == "Alterar") {

  $sCamposConplano = "max(c60_anousu) as c60_anousu, c60_estrut";
  $sWhereConplano  = "c60_codcon = {$c60_codcon} group by c60_estrut";
  $sSqlConPlano    = $clconplano->sql_query_file(null, null, $sCamposConplano, null, $sWhereConplano);
  //$sql_conplano = "select max(c60_anousu) as c60_anousu from conplano where c60_codcon={$c60_codcon}";
  $res_conplano = $clconplano->sql_record($sSqlConPlano);
  $numrows      = $clconplano->numrows;
  $iUltimoAno   = db_getsession("DB_anousu");
  if ($numrows > 0) {

    $iAnoConPlano = db_utils::fieldsmemory($res_conplano,0)->c60_anousu;
    /**
     * Buscamos os dados referentes a esta conta no plano orçamentário. Isso porque na inclusão de contas, incluímos
     * na tabela conplanoorcamento quando o ANO for >= 2013
     */
    if (USE_PCASP) {

      $oDaoConPlanoOrcamento = db_utils::getDao('conplanoorcamentoanalitica');
      $sSqlBuscaPlano        = $oDaoConPlanoOrcamento->sql_query_simples(null,
                                                                         null,
                                                                         "max(c60_anousu) as c60_anousu, c60_codcon, c61_reduz",
                                                                         null,
                                                                         "c60_estrut = '{$c60_estrut}' group by c60_codcon, c61_reduz");
      $rsBuscaPlano = $oDaoConPlanoOrcamento->sql_record($sSqlBuscaPlano);
      if ($oDaoConPlanoOrcamento->numrows > 0) {

        $oDadoPlanoOrcamento        = db_utils::fieldsMemory($rsBuscaPlano, 0);
        $iAnoConPlano               = $oDadoPlanoOrcamento->c60_anousu;
        $iCodigoContaPlanoOrcamento = $oDadoPlanoOrcamento->c60_codcon;
        $iCodigoReduzidoOrcamento   = $oDadoPlanoOrcamento->c61_reduz;
      }
    }
    $iUltimoAno = $iAnoConPlano;

  }
  for ($iAno = db_getsession("DB_anousu"); $iAno <= $iUltimoAno; $iAno++) {


  // isto evitava ter dois redizidos abaixo do mesmo codcon que tivessem na mesma instituição o mesmo recurso
    $sSqlVerificaRecurso = $clconplanoreduz->sql_query_file(null, null, "*",
                                                            null,
                                                            "c61_anousu={$iAno}
                                                             and c61_instit={$c61_instit}
                                                             and c61_codigo={$c61_codigo}
                                                             and c61_codcon={$c60_codcon}
                                                             and c61_reduz <> {$c61_reduz}"
                                                            );
    $res = $clconplanoreduz->sql_record($sSqlVerificaRecurso);
    if ($clconplanoreduz->numrows > 0) {

      db_msgbox("Alteração Cancelada : já existe nessa instituição uma conta analítica com esse recurso ! ");
      db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
    }

    $res_plano = $clconplano->sql_record($clconplano->sql_query_file($c60_codcon,$iAno,"c60_estrut"));
    if ($clconplano->numrows > 0) {
      db_fieldsmemory($res_plano,0);
      if (substr($c60_estrut,0,1)=="3") {
        if ($c61_contrapartida==0||$c61_contrapartida=="") {
          db_msgbox("Contra Partida para esta conta despesa é obrigatória");
          db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
        }
      }
    }

    if (!isset($c61_contrapartida)){
         $c61_contrapartida = "0";
    }

    /**
     * Definimos o OBJETO que será utilizado para inserir registros
     */
    if ($iAno >= 2013 && USE_PCASP) {

      $clconplanoreduz = db_utils::getDao('conplanoorcamentoanalitica');
      $c60_codcon      = $iCodigoContaPlanoOrcamento;
      $c61_reduz       = $iCodigoReduzidoOrcamento;
    }

    $erro = false;
    db_inicio_transacao();
    $clconplanoreduz->c61_codcon        = $c60_codcon;
    $clconplanoreduz->c61_anousu        = $iAno;
    $clconplanoreduz->c61_instit        = $c61_instit;
    $clconplanoreduz->c61_codigo        = $c61_codigo;
    $clconplanoreduz->c61_contrapartida = $c61_contrapartida;
    $clconplanoreduz->c61_reduz         = $c61_reduz;
    $res = $clconplanoreduz->alterar("$c61_reduz",$iAno);
    if ($clconplanoreduz->erro_status == 0) {

      $erro_msg = $clconplanoreduz->erro_msg;
      $erro = true;
    }

    /**
     * Só alteramos as tabelas abaixo caso o ano não seja menor ou igual a 2012
     */
    if ($iAno <= 2012 || !USE_PCASP) {

    //se o usuário setou o cgm, devemos incluir o cgm na conplanoreduzcgm
      if ($c22_numcgm != "" && $erro == false) {

        $rsReduzCgm                       = $oDaoConplanoreduzCgm->sql_record(
                                            $oDaoConplanoreduzCgm->sql_query_file(null,"*",null,
                                                                                  "c22_anousu={$iAno}
                                                                                   and c22_reduz={$c61_reduz}"));
        $oDaoConplanoreduzCgm->c22_anousu = $iAno;
        $oDaoConplanoreduzCgm->c22_reduz  = $c61_reduz;
        $oDaoConplanoreduzCgm->c22_numcgm = $c22_numcgm;

        //caso o reduzido já tenha cgm, apenas alteramos o cadastro. senao incluimos.
        if ($oDaoConplanoreduzCgm->numrows > 0) {

          $oReduzCgm = db_utils::fieldsMemory($rsReduzCgm, 0);

          $oDaoConplanoreduzCgm->c22_sequencial = $oReduzCgm->c22_sequencial;
          $oDaoConplanoreduzCgm->alterar($oReduzCgm->c22_sequencial);

        } else {
          $oDaoConplanoreduzCgm->incluir(null);

        }

        if ($oDaoConplanoreduzCgm->erro_status == '0') {

          $erro_msg = $oDaoConplanoreduzCgm->erro_msg;
          $erro = true;
        }

      } else if($c22_numcgm == "" && $erro == false){
        //o usuário apagou , ou nao preencheu o cgm.
        //devemos verificar o se existe um cgm para esse reduzido e o excluimos
        $rsReduzCgm  = $oDaoConplanoreduzCgm->sql_record(
                       $oDaoConplanoreduzCgm->sql_query_file(null,"*",null,
                                                                      "c22_anousu={$iAno}
                                                                       and c22_reduz={$c61_reduz}"));

        if ($oDaoConplanoreduzCgm->numrows > 0) {

           $oReduzCgm = db_utils::fieldsMemory($rsReduzCgm, 0);
           $oDaoConplanoreduzCgm->excluir($oReduzCgm->c22_sequencial);
           if ($oDaoConplanoreduzCgm->erro_status == '0') {

             $erro_msg = $oDaoConplanoreduzCgm->erro_msg;
             $erro = true;

           }
        }
      }

      if ($erro == false) {

        $clconplanoexe->c62_anousu = $iAno;
        $clconplanoexe->c62_reduz  =  $clconplanoreduz->c61_reduz;
        $clconplanoexe->c62_codrec = $clconplanoreduz->c61_codigo;
        $clconplanoexe->alterar($anousu,$clconplanoreduz->c61_reduz);
        if ($clconplanoexe->erro_status == 0) {

          $erro_msg = $clconplanoexe->erro_msg;
          $erro = true;

        }
      }

      // Faz update caso a conta tenha previsão de orçamento ambos ficam com mesmo codigo de recurso para evitar que a
      // previsão tenha recurso diferente da conta
      if ($erro==false) {

        $clorcreceita->o70_codigo = $c61_codigo;
        $clorcreceita->o70_anousu = $iAno;
        $clorcreceita->o70_codrec = $c60_codcon;
        $clorcreceita->alterar($iAno, $c60_codcon);
        if ($clorcreceita->erro_status == 0) {

          $erro_msg = $clorcreceita->erro_msg;
          $erro = true;

        }
      }

      $db_opcao = 1;
      // ---------------------------
      $db_conplano->evento($c61_reduz, $c61_contrapartida, $iAno, $c61_instit);
    }
  // ---------------------------
  }
  //db_msgbox($erro_msg);
 // $erro = true;
  db_fim_transacao($erro);

}
if (isset($excluir) && $excluir == "Excluir") {
  // leia as Regras acima

  $sql_conplano = "select max(c60_anousu) as c60_anousu from conplano where c60_codcon={$c60_codcon}";
  $res_conplano = $clconplano->sql_record($sql_conplano);
  $numrows      = $clconplano->numrows;
  $iUltimoAno   = db_getsession("DB_anousu");
  if ($numrows > 0) {

    $iAnoConPlano = db_utils::fieldsmemory($res_conplano,0)->c60_anousu;
    /**
     * Buscamos os dados referentes a esta conta no plano orçamentário. Isso porque na inclusão de contas, incluímos
     * na tabela conplanoorcamento quando o ANO for >= 2013
     */
    if (USE_PCASP) {

      $oDaoConPlanoOrcamento = db_utils::getDao('conplanoorcamentoanalitica');
      $sSqlBuscaPlano        = $oDaoConPlanoOrcamento->sql_query_simples(null,
                                                                         null,
                                                                         "max(c60_anousu) as c60_anousu, c60_codcon, c61_reduz",
                                                                         null,
                                                                         "c60_estrut = '{$c60_estrut}' group by c60_codcon, c61_reduz");
      $rsBuscaPlano = $oDaoConPlanoOrcamento->sql_record($sSqlBuscaPlano);
      if ($oDaoConPlanoOrcamento->numrows > 0) {

        $oDadoPlanoOrcamento        = db_utils::fieldsMemory($rsBuscaPlano, 0);
        $iAnoConPlano               = $oDadoPlanoOrcamento->c60_anousu;
        $iCodigoContaPlanoOrcamento = $oDadoPlanoOrcamento->c60_codcon;
        $iCodigoReduzidoOrcamento   = $oDadoPlanoOrcamento->c61_reduz;
      }
    }
    $iUltimoAno = $iAnoConPlano;
  }
  db_inicio_transacao();
  $erro = false;
  for ($iAno = db_getsession("DB_anousu"); $iAno <= $iUltimoAno; $iAno++) {

    if ($iAno <= 2012 || !USE_PCASP) {

      $sSqlVerificaLanc = $clconlancamval->sql_query_file(null,
                                                       "*",
                                                       null,
                                                       "c69_anousu={$iAno}
                                                        and (c69_debito={$c61_reduz} or c69_credito={$c61_reduz})"
                                                        );
      $res = $clconlancamval->sql_record($sSqlVerificaLanc);
      if ($clconlancamval->numrows > 0) {

        db_msgbox("Conta possui lançamentos, não pode ser excluida ! ");
        db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
        exit;

      }
      /**
       * Caso tenha estimativas de receita no ppa, nao pode excluir reduzido
       */
      $sSQlVerificaPPA   = "select 1 ";
      $sSQlVerificaPPA  .= "  from ppaestimativareceita  ";
      $sSQlVerificaPPA  .= " where o06_codrec  = {$c60_codcon} ";
      $sSQlVerificaPPA  .= "   and o06_anousu  = {$iAno} ";
      $rsVerificaPPA     = db_query($sSQlVerificaPPA);
      if (pg_num_rows($rsVerificaPPA) > 0) {

        $sMsg  = "Você está tentando excluir uma receita com registro nas projeções do PPA. Se realmente deseja fazer a";
        $sMsg .= "exclusão, você deverá excluir ou substituir esta receita no menu: ";
        $sMsg .= "ORÇAMENTO > PROCEDIMENTOS > PPA > RECEITAS DO PPA > ALTERAÇÃO DE RECEITAS";
        db_msgbox($sMsg);
        db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
        exit;

      }
      /* não permite excluir conta que possua saldo inicial */
      $sSqlVerificaSaldoIni = $clconplanoexe->sql_query_file(null,null,
                                                             "*",null,
                                                             "c62_anousu={$iAno}
                                                              and c62_reduz={$c61_reduz}
                                                              and (c62_vlrcre >0 or c62_vlrdeb >0 ) "
                                                             );
      $res = $clconplanoexe->sql_record($sSqlVerificaSaldoIni);
      if ($clconplanoexe->numrows >0 ) {

        db_msgbox("Não posso excluir esta conta porque ela possui saldo inicial lançado ! ");
        db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
        exit;

      }

      /* Não pode excluir reduzidos se possuir conta na Tesouraria */
        $sql_saltes = "select saltes.*
                       from saltes
                            left join corrente    on corrente.k12_conta    = saltes.k13_reduz
                            left join placaixarec on placaixarec.k81_conta = saltes.k13_reduz
                            left join slip        on slip.k17_debito       = saltes.k13_reduz or
                                                     slip.k17_credito      = saltes.k13_reduz
                       where saltes.k13_reduz = {$c61_reduz}
    				      and (corrente.k12_conta      is not null or
                                  placaixarec.k81_conta  is not null or
                                  slip.k17_codigo             is not null)
    					  and k13_vlratu > 0";

    //die($sql_saltes);

          $res_saltes = @pg_query($sql_saltes);
          $numrows    = @pg_numrows($res_saltes);
          if ($numrows > 0) {

            db_msgbox("Conta não pode ser excluida. Conta com movimentacao!");
            db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
            exit;

          }
    //    }
        $updateSaltes = "update saltes set k13_limite = '".date("Y-m-d", db_getsession("DB_datausu"))."' where k13_conta = {$c61_reduz}";
        $rsUpdateSaltes = db_query($updateSaltes);
        if (!$rsUpdateSaltes) {

          db_msgbox("Conta não pode ser excluida. Erro ao  desativar a conta na Tesouraria!".pg_last_error());
          db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
          exit;

        }

    //die($sql_saltes);

          $res_saltes = @pg_query($sql_saltes);
          $numrows    = @pg_numrows($res_saltes);
          if ($numrows > 0) {

            db_msgbox("Conta não pode ser excluida. Conta com movimentacao!");
            db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
            exit;

          }
        // Não pode excluir reduzidos que possuam autorizacao de empenho
        $sSqlVerificaAutorizacoes = $clempautitem->sql_query(null,null,
                                                             "*",
                                                             "e55_codele",
                                                             "e55_codele = {$c60_codcon}
                                                              and e54_anousu = {$iAno}"
                                                             );
        $res_empautitem = $clempautitem->sql_record($sSqlVerificaAutorizacoes);
        if ($clempautitem->numrows > 0) {

          db_msgbox("Não é possível excluir este reduzido, pois seu código consta em Autorizações de Empenho. Verifique.");
          db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
          exit;

        }

        // Não pode excluir reduzidos que possuam solicitação de compras
        $sSqlVerificaSolicitacoes  =$clsolicitemele->sql_query(null,null,
                                                                    "*",
                                                                    "pc18_codele",
                                                                    "pc18_codele = {$c60_codcon}
                                                                     and o56_anousu = {$iAno}"
                                                                   );
        $res_solicitemele = $clsolicitemele->sql_record($sSqlVerificaSolicitacoes);
        if ($clsolicitemele->numrows > 0) {

          db_msgbox("Não é possível excluir este reduzido, pois seu código consta em Solicitações de Compra. Verifique.");
          db_redireciona(basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c60_codcon=$c60_codcon");
          exit;

        }

      $rsReduzCgm  = $oDaoConplanoreduzCgm->sql_record(
                     $oDaoConplanoreduzCgm->sql_query_file(null,"*",null,
                                                           "c22_anousu={$iAno}
                                                            and c22_reduz={$c61_reduz}"));
      if ($oDaoConplanoreduzCgm->numrows > 0) {

        $oReduzCgm = db_utils::fieldsMemory($rsReduzCgm, 0);
        $oDaoConplanoreduzCgm->excluir($oReduzCgm->c22_sequencial);
        if ($oDaoConplanoreduzCgm->erro_status == '0') {
          $erro = true;
        }
      }

      $sSqlConplanoExe = $clconplanoexe->sql_query_file($iAno, $c61_reduz);
      $rsConplanoExe   = $clconplanoexe->sql_record($sSqlConplanoExe);
      if ($clconplanoexe->numrows > 0) {

        $res = $clconplanoexe->excluir($iAno, $c61_reduz);
        if ($clconplanoexe->erro_status == 0) {
          db_msgbox($clconplanoexe->erro_msg);
          $erro = true;
        }
      }
    }

    if ($iAno >= 2013 && USE_PCASP) {

      $clconplanoreduz = db_utils::getDao('conplanoorcamentoanalitica');
      $clconplanoreduz->c61_reduz  = $iCodigoReduzidoOrcamento;
      $clconplanoreduz->c61_anousu = $iAno;
      $c61_reduz = $iCodigoReduzidoOrcamento;
    }

    $res = $clconplanoreduz->excluir($c61_reduz,$iAno);
    if ($clconplanoreduz->erro_status == 0) {
      db_msgbox($clconplanoreduz->erro_msg);
      $erro = true;
    }
  }
  db_fim_transacao($erro);
  $db_opcao = 1;
}

// as opções abaixo são geradas pelo iframe_automatico
if (isset($opcao) && $opcao == "alterar") {

  $res = $clconplanoreduz->sql_record($clconplanoreduz->sql_query("", "","*", "", " c60_codcon=$c60_codcon and c61_reduz=$c61_reduz and c61_anousu=$anousu"));
  if ($clconplanoreduz->numrows > 0) {
    db_fieldsmemory($res, 0);

    $res = $clconplano->sql_record($clconplano->sql_query(null,null, "c60_descr", null, " c61_anousu=$anousu and c61_reduz=$c61_contrapartida "));
    if ($clconplano->numrows > 0 && $c61_contrapartida !=0) {
      db_fieldsmemory($res, 0);
    } else {

      // zeramos a variavel para aparecer a contrapartica em branco
      $c60_descr ="";
    }
    $rsReduzCgm  = $oDaoConplanoreduzCgm->sql_record($oDaoConplanoreduzCgm->sql_query(
                                                      null,"*",null,
                                                      "c22_anousu={$anousu}
                                                       and c22_reduz={$c61_reduz}")
                                                    );
    if ($oDaoConplanoreduzCgm->numrows > 0) {

      db_fieldsmemory($rsReduzCgm,0);
    } else {
      $c22_numcgm = "";
      $z01_nome   = "";
    }

  }
  $db_opcao = 2;
}
if (isset($opcao) && $opcao == "excluir") {

  $res = $clconplanoreduz->sql_record($clconplanoreduz->sql_query("", "","*", "", "c60_codcon=$c60_codcon and c61_reduz=$c61_reduz and c61_anousu=$anousu"));
  if ($clconplanoreduz->numrows > 0) {
    db_fieldsmemory($res, 0);
  }
  $rsReduzCgm  = $oDaoConplanoreduzCgm->sql_record($oDaoConplanoreduzCgm->sql_query(
                                                   null,"*",null,
                                                   "c22_anousu={$anousu}
                                                    and c22_reduz={$c61_reduz}")
                                                   );
   if ($oDaoConplanoreduzCgm->numrows > 0) {

     db_fieldsmemory($rsReduzCgm,0);
  } else {
    $c22_numcgm = "";
    $z01_nome   = "";
  }
  /**
   * Verificamos se a conta possui cadastro no saltes
   */
   $sSqlSaltes = "select saltes.*
                    from saltes
                   where saltes.k13_reduz = {$c61_reduz}";

  $rsSaltes = db_query($sSqlSaltes);
  if (pg_num_rows($rsSaltes) > 0) {
     $lTemContaSaltes = true;
  }
  $db_opcao = 3;
}
if ($db_opcao == 1) {
  // limpamos as iformações
  $c61_reduz   = "";
  $c61_instit  = "";
  $c61_codigo  = "";
  $nomeinst    = "";
  $o15_descr   = "";
  $c61_contrapartida = "";
  $c60_descr   = "";
  $c22_numcgm  = '';
  $z01_nome  = '';
  $anotransacao = '';
  $c47_tiporesto = '';
  $contrapartidaliq = '';
  $contrapartidaanu = '';
}
$c60_codcon = $iCodigoPCASP;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
var lTemSaltes = <?=$lTemContaSaltes==true?"true":"false";?>;
function js_pesquisac61_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.c61_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave;
  if(erro==true){
    document.form1.c61_codigo.focus();
    document.form1.c61_codigo.value = '';
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.c61_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisa_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_instit','func_instit.php?funcao_js=parent.js_mostrainstit1|codigo|nomeinst','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_instit','func_instit.php?pesquisa_chave='+document.form1.c61_instit.value+'&funcao_js=parent.js_mostrainstit','Pesquisa',false);
  }
}
function js_mostrainstit1(chave1,chave2){
  document.form1.c61_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_instit.hide();
}
function js_mostrainstit(chave,erro){
  document.form1.nomeinst.value = chave;
  if(erro==true){
    document.form1.c61_instit.focus();
    document.form1.c61_instit.value = '';
  }
}
function js_pesquisac61_contrapartida(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_contrapartida','func_conplanocontrapartida.php?funcao_js=parent.js_mostraContrapartida|c61_reduz|c60_descr','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_contrapartida','func_conplanocontrapartida.php?pesquisa_chave='+document.form1.c61_contrapartida.value+'&funcao_js=parent.js_mostraContrapartida1','Pesquisa',false);
  }
}

function js_mostraContrapartida(chave1,chave2) {

  document.form1.c61_contrapartida.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_contrapartida.hide();
}

function js_mostraContrapartida1(chave,erro){
  document.form1.c60_descr.value = chave;
  if(erro==true){
    document.form1.c61_contrapartida.focus();
    document.form1.c61_contrapartida.value = '';
  }
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostraz01_numcgm|z01_numcgm|z01_nome','Pesquisa',true,'10');
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.c22_numcgm.value+'&funcao_js=parent.js_mostraz01_numcgm1','Pesquisa',false);
  }
}
function js_mostraz01_numcgm(chave1,chave2){
  document.form1.c22_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_mostraz01_numcgm1(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.c22_numcgm.focus();
    document.form1.z01_nome.value = chave;
  }
}
function js_pesquisac47_debito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_reduzido','db_iframe_conplanoexe_credito',
                        'func_conplanoexe.php?funcao_js=parent.js_mostraconplanoexe1_credito|c62_reduz|c60_descr',
                        'Pesquisa',true,'0','1'
                        );
  }else{
     if(document.form1.contrapartidaliq.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_reduzido',
                            'db_iframe_conplanoexe_credito',
                            'func_conplanoexe.php?pesquisa_chave='+document.form1.contrapartidaliq.value+
                            '&funcao_js=parent.js_mostraconplanoexe_credito','Pesquisa',
                            false
                           );
     }else{
       document.form1.c60_descr_liq.value = '';
     }
  }
}
function js_mostraconplanoexe_credito(chave,erro){
  document.form1.c60_descr_liq.value = chave;

  if(erro==true){
    document.form1.contrapartidaliq.focus();
    document.form1.contrapartidaliq.value = '';
  }
}
function js_mostraconplanoexe1_credito(chave1,chave2){
  document.form1.contrapartidaliq.value = chave1;
  document.form1.c60_descr_liq.value = chave2;
  db_iframe_conplanoexe_credito.hide();
}


function js_pesquisac47_credito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_reduzido',
                        'db_iframe_conplanoexe',
                        'func_conplanoexe.php?funcao_js=parent.js_mostraconplanoexe1|c62_reduz|c60_descr',
                        'Pesquisa',true,'0','1'
                        );
  }else{
     if(document.form1.contrapartidaanu.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_reduzido',
                            'db_iframe_conplanoexe','func_conplanoexe.php?pesquisa_chave='+
                            document.form1.contrapartidaanu+'&funcao_js=parent.js_mostraconplanoexe',
                            'Pesquisa',false);
     }else{
       document.form1.c60_descr_anu.value = '';
     }
  }
}
function js_mostraconplanoexe(chave,erro){
  document.form1.c60_descr_anu.value = chave;
  if(erro==true){
    document.form1.contrapartidaanu.focus();
    document.form1.contrapartidaanu.value = '';
  }
}
function js_mostraconplanoexe1(chave1,chave2){
  document.form1.contrapartidaanu.value = chave1;
  document.form1.c60_descr_anu.value = chave2;
  db_iframe_conplanoexe.hide();
}


function js_validaProcessamento() {

  if (lTemSaltes) {

    var sMsg  = "Você está tentando excluir uma conta que possui cadastro na tesouraria, podendo haver ainda ";
        sMsg += "autenticações a ela associadas.\nSe desejar continuar, o sistema irá desativar a conta nesse cadastro\n";
        sMsg += "Deseja efetivar a exclusão";
    if (confirm(sMsg)) {
      return true;
    } else {
      return false;
    }
  } else {
    return true;
  }

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<form name="form1" method="post" action="">
<center>
<?

if ($c60_codsis == 7) {

  if (!isset($c61_codigo) || (isset($c61_codigo) && $c61_codigo == "")) {

    $c61_codigo = "1";
    $o15_descr  = "RECURSO LIVRE";
  }
}
if (isset ($c60_codcon)) {
?>
    <table border=0 width=100%>
    <tr>
    <td><table border=0 align=center>
         <tr>
	   <td><? db_ancora($Lc60_codcon,'','3'); ?></td>
	   <td><? db_input('c60_codcon',10,$Ic60_codcon,true,'text',3,""); ?></td>
	 </tr>
         <tr>
	   <td><? db_ancora($Lc61_reduz,'','3'); ?></td>
	   <td><? db_input('c61_reduz',10,$Ic61_reduz,true,'text',3,""); ?></td>
	 </tr>
         <tr>
	   <td><? db_ancora($Lc61_instit,"js_pesquisa_instit(true);",(isset($opcao)&&$opcao=="alterar")?3:$db_opcao); ?></td>
	   <td><? db_input('c61_instit',10,$Ic61_instit,true,'text',(isset($opcao)&&$opcao=="alterar")?3:$db_opcao,"onchange='js_pesquisa_instit(false);'"); ?>
	       <? db_input('nomeinst',50,$Inomeinst,true,'text',3,""); ?></td>
	 </tr>
         <tr>
	   <td><? db_ancora($Lc61_codigo,"js_pesquisac61_codigo(true);",1); ?></td>
	   <td><? db_input('c61_codigo',10,$Ic61_codigo,true,'text',$db_opcao,"onchange='js_pesquisac61_codigo(false);'"); ?>
	       <? db_input('o15_descr',50,$Io15_descr,true,'text',3,""); ?></td>
	 </tr>
   <tr>
     <td>
        <? db_ancora($Lz01_nome,"js_pesquisaz01_numcgm(true);",1); ?>
     </td>
     <td>
	   <?
        db_input('c22_numcgm',10,$Ic22_numcgm,true,'text',$db_opcao,"onchange='js_pesquisaz01_numcgm(false);'");
	      db_input('z01_nome',50,$Iz01_nome,true,'text',3,"");
     ?>
     </td>
   </tr>
<?
   if (isset($c60_estrut) && @substr($c60_estrut,0,1) == "3"){
?>
         <tr>
	   <td><? db_ancora($Lc61_contrapartida,"js_pesquisac61_contrapartida(true);",1); ?></td>
	   <td><? db_input('c61_contrapartida',10,$Ic61_contrapartida,true,'text',$db_opcao,"onchange='js_pesquisac61_contrapartida(false);'"); ?>
	       <? db_input('c60_descr',45,$Ic60_descr,true,'text',3,""); ?></td>
	 </tr>
<?
  }
   $sDisplayCamposTransacao = 'none';
  /**
   * Verificamos se a conta é uma conta de RP pelo Grupo 9005, ou 9006
   * 9005 é RP processado, e 9006 e RP nao Processado
   */
    $sConta = substr($c60_estrut, 0,5);
    $iAno   = db_getsession("DB_anousu");
    $sSqlGrupoConta  = "select fc_conplano_grupo({$iAno},'{$sConta}%',9007) as grupo_rp_processado,";
    $sSqlGrupoConta .= "fc_conplano_grupo({$iAno},'$sConta%',9008) as grupo_rp_nao_processado";
    $rsGrupoConta    = db_query($sSqlGrupoConta);
    if ($rsGrupoConta) {

      $oTipoGrupo = db_utils::fieldsMemory($rsGrupoConta, 0);
      if ($oTipoGrupo->grupo_rp_processado == "t" || $oTipoGrupo->grupo_rp_nao_processado == 't') {
        $sDisplayCamposTransacao = '';
      }
    }
    $sDisplayCamposTransacao='none'
  ?>
  <tr style='display: <?=$sDisplayCamposTransacao?>'>
     <td><b>Ano:</b></td>
     <td><? db_input('anotransacao',10,$Ic60_anousu,true,'text',1,""); ?></td>
  </tr>
  <tr style='display: <?=$sDisplayCamposTransacao?>'>
     <td nowrap title="<?=@$Tc47_debito?>">
         <?
         db_ancora("<b>Contrapartida na Liquidação de RP:</b>","js_pesquisac47_debito(true);",$db_opcao);
         ?>
      </td>
      <td>
         <?
          db_input('contrapartidaliq',10,$Ic47_debito,true,'text',$db_opcao," onchange='js_pesquisac47_debito(false);'");
          db_input('c60_descr_liq',50,$Ic61_reduz,true,'text',3);
         ?>
      </td>
    </tr>
    <tr style='display: <?=$sDisplayCamposTransacao?>'>
      <td nowrap title="<?=@$Tc47_credito?>">
         <?
         db_ancora("<b>Contrapartida na Anulação de RP:</b>","js_pesquisac47_credito(true);",$db_opcao);
         ?>
      </td>
      <td>
        <?
        db_input('contrapartidaanu',10,$Ic47_credito,true,'text',$db_opcao," onchange='js_pesquisac47_credito(false);'");
        db_input('c60_descr_anu',50,$Ic61_reduz,true,'text',3,'');
        ?>
      </td>
    </tr>
  <tr style='display: <?=$sDisplayCamposTransacao?>'>
    <td nowrap title="<?=@$Tc47_tiporesto?>">
      <?=@$Lc47_tiporesto?>
     </td>
     <td>
      <?
        $result=$clemprestotipo->sql_record($clemprestotipo->sql_query_file(null,"e90_codigo,e90_descr"));
        db_selectrecord("c47_tiporesto",$result,true,$db_opcao,"","","","0");
      ?>

      </td>
  </tr>
   <tr>
   <?
   $where_db_config = " case when ( select count(*) from db_usuarios where id_usuario = " . db_getsession("DB_id_usuario") . " and administrador = 1 ) = 1 then true else codigo in ( select id_instit from db_userinst where id_usuario = " . db_getsession("DB_id_usuario") . " ) end ";
   //$where_db_config .= " and codigo = $c61_instit ";
   $rs_db_config = $cldbconfig->sql_record($cldbconfig->sql_query(null,"*",null,$where_db_config));
   if ( $db_opcao != 1 and $cldbconfig->numrows == 0 ) {
     $db_botao = false;
     $mensagem = "Usuario sem permissao para efetuar este procedimento de " . ($db_opcao == 2?"alteracao":"exclusao") . " nesta instituicao!";
     echo "<script>alert('$mensagem');</script>";
   }
   ?>
     <td colspan=2 align=center>
	    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
	     type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
	      <?=($db_botao==false?"disabled":"") ?>  onclick="return js_validaProcessamento()">

<?
   if ($db_opcao != 1){
?>
      <input name="novo" id="novo" value="Novo" type="submit">
<?
   }
?>
	   </td>
	 </tr>
	</table>
    </td>
    </tr>
    </table>

    <?

	$db_opcao = 1;
	$clconplanoreduz =  db_utils::getDao('conplanoreduz');
	if (db_getsession("DB_anousu") >= 2013 && USE_PCASP) {
    $clconplanoreduz = db_utils::getDao('conplanoorcamentoanalitica');
  }

	$chavepri = array ("c60_codcon" => $c60_codcon,"c61_anousu" => @ $c61_anousu , "c61_reduz" => @ $c61_reduz);
	$cliframe_alterar_excluir->chavepri = $chavepri;
	$cliframe_alterar_excluir->sql = $clconplanoreduz->sql_query("","", "*", "", "c60_codcon=$c60_codcon and c60_anousu=$anousu");
	$cliframe_alterar_excluir->campos = "c61_codcon,c61_reduz,c61_instit,nomeinst,c61_codigo,o15_descr,c61_contrapartida";
	$cliframe_alterar_excluir->legenda = "lista";
	$cliframe_alterar_excluir->iframe_height = "240";
	$cliframe_alterar_excluir->iframe_width = "100%";
	$cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
}
?>
</center>
<?

?>
</form>
</td>
</tr>
</table>
</body>
</html>