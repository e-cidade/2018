<?php
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

//21.833.694.
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_libpessoal.php"));

require_once(modification("funcoes/db_func_pesdiver.php"));

$matric     = null;
$r01_regist = '';
$iMatricula = 0;
$oPost  = db_utils::postMemory($_POST);
$oGet   = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($HTTP_POST_VARS);

db_postmemory($HTTP_GET_VARS);

$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('q02_inscr');
$clrotulo->label('k00_numpre');
$clrotulo->label('v07_parcel');
$clrotulo->label('rh37_funcao');
$clrotulo->label('rh37_descr');

$lPermisaoCadastroServidor = db_permissaomenu(db_getsession('DB_anousu'), 952, 4356);

/*
 * Variável que controla se campos são editáveis ou não.
 */

if (!isset($lReadOnly)){
  $lReadOnly = false;
}

if( isset($_GET['lConsulta']) ) {
	$r01_regist = $_GET['iMatricula'];
}

if ( !empty($oGet->iMatricula) ) {

	$r01_regist = $oGet->iMatricula;
	$matricula  = $oGet->iMatricula;
	
} 

/**
 * GET - Define datas para consulta
 */
$ano = !empty($oGet->ano)  ? $oGet->ano  : null;
$mes = !empty($oGet->mes)  ? $oGet->mes  : null;

/**
 * POST - Define datas para consulta
 */
if ( empty($ano) || empty($mes) ) {

  $ano = !empty($oPost->ano) ? $oPost->ano : db_anofolha();
  $mes = !empty($oPost->mes) ? $oPost->mes : db_mesfolha();
}

/**
 * Paginacao de matriculas
 * Matricula posterior e anterior a atual
 */
$iMatriculaPosterior = 0;
$iMatriculaAnterior  = 0;

if ( !empty($r01_regist) )  {

  $iMatricula = $r01_regist;

  $oDaoRhPessoalMov = db_utils::getDao('rhpessoalmov');
  $sSqlPaginacao    = $oDaoRhPessoalMov->sql_queryPaginacao($r01_regist, $ano, $mes); 
  $rsPaginacao      = $oDaoRhPessoalMov->sql_record($sSqlPaginacao);

  if ( $oDaoRhPessoalMov->numrows > 0 ) {

    $oPaginacao          = db_utils::fieldsMemory($rsPaginacao, 0);
    $iMatriculaPosterior = $oPaginacao->posterior;
    $iMatriculaAnterior  = $oPaginacao->anterior;
  }
}

$iAnoImplantacao = date("Y", db_getsession("DB_datausu"));
$iMesImplantacao = date("m", db_getsession("DB_datausu"));

$iAnoLimite      = db_anofolha();
$iMesLimite      = db_mesfolha();

$sqlanomes       = "select min(r11_implan)
                      from cfpess
                     where r11_instit = ".db_getsession('DB_instit');
$resultanomes    = db_query($sqlanomes);
db_fieldsmemory($resultanomes,0);

$sCompetencia    = str_replace("/", "-", $min);
$aImplantacao    = explode("-", $sCompetencia);
$iAnoImplantacao = $aImplantacao[0];
$iMesImplantacao = $aImplantacao[1];

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
      db_app::load('scripts.js, prototype.js, dbcomboBox.widget.js, dbtextField.widget.js, strings.js, DBHint.widget.js');
    ?>
    <style type="text/css">

      .tabcols {
        font-size:11px;
      }
      .tabcols1 {
        text-align: right;
        font-size:11px;
      }
      .btcols {
        height: 17px;
        font-size:10px;
      }
      .links {
        font-weight: bold;
        color: #0033FF;
        text-decoration: none;
        font-size:10px;
        cursor: hand;
      }
      a.links:hover {
        color:black;
        text-decoration: underline;
      }
      .links2 {
        font-weight: bold;
        color: #0587CD;
        text-decoration: none;
        font-size:10px;
      }
      a.links2:hover {
        color:black;
        text-decoration: underline;
      }
      .nome {
        color:black;  
      }
      a.nome:hover {
        color:blue;
      }

      #ctnPeriodoInput {
        margin-right:14px;
      }

      #ano, #mes {
        height:20.5px;
      }

      #boxToglePeriodo {
        margin-left:15px;
        text-align:left;
      }

      #oComboAno, #oComboMes {
        padding:0;
        position:relative;
        margin-top:-4px;
        top:3px;
      }
      #togglePeriodo {
        cursor:pointer;
      }

      iframe{
        border: 0;
      }


      .box-pontos, .box-calculos{
        height    : 107px;
        width     : 125px;
        overflow-y: auto;
        overflow-x: hidden;
        text-align:left;
      }

      .manutencao-ponto {
        width      : 24px;
        float      : left;
        font-weight: bold;
        min-height : 5px;
      }

    </style>

    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  
  <body bgcolor=#CCCCCC onload='if(document.form1.r01_regist) js_tabulacaoforms("form1","r01_regist",true,1,"r01_regist",true);'>
    <div id="DDD"></div>
    
    <div id="processando" style="position:absolute; left:27px; top:126px; width:957px; height:344px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" valign="middle" id="processandoTD" onclick="document.getElementById('processando').style.visibility='hidden'"></td>
      </tr>
    </table>
    </div>
    
    
      <input type="hidden" id="anoAtual"       value="<?php echo $ano; ?>" />
      <input type="hidden" id="mesAtual"       value="<?php echo $mes; ?>" />
      
      <input type="hidden" id="anoLimite"      value="<?php echo $iAnoLimite; ?>" />
      <input type="hidden" id="mesLimite"      value="<?php echo $iMesLimite; ?>" />
      
      <input type="hidden" id="anoImplantacao" value="<?php echo $iAnoImplantacao; ?>" />
      <input type="hidden" id="mesImplantacao" value="<?php echo $iMesImplantacao; ?>" />
    
        <center>
        <?  
      $mensagem_semdebitos = false;
      $com_debitos = true;
      if(isset($HTTP_POST_VARS["pesquisar"]) || isset($matricula) ) {
          echo "<form id='formatu' name=\"formatu\" action=\"pes3_gerfinanc001.php".($lReadOnly ? "?lReadOnly=true" :"")."\" method=\"post\">\n";
          //aqui é pra se clicar no link da matricula em cai3_gerfinanc002.php
          if(isset($matricula) && !empty($matricula))
          $HTTP_POST_VARS["r01_regist"] = $matricula;
      
        if(!empty($HTTP_POST_VARS["r01_regist"])) {
            $sql = "select rh02_regist as r01_regist,
                           rh01_numcgm as k00_numcgm,
                           z01_numcgm,
                           rh02_tbprev as r01_tbprev 
                    from   rhpessoalmov
                           inner join rhpessoal on rh01_regist = rh02_regist
                           inner join cgm on rh01_numcgm = z01_numcgm 
                where 
                           rh02_regist = ".$HTTP_POST_VARS["r01_regist"]."
                       and rh02_anousu = ".db_anofolha()."
                       and rh02_mesusu = ".db_mesfolha()."
                       and rh02_instit = ".db_getsession("DB_instit")." limit 1" ;
            // echo $sql;
          $result = db_query($sql);
          if(pg_numrows($result) == 0) {
            echo "
                    <script>
                      alert('Funcionário sem cálculo')
                    </script>";
            db_redireciona("pes3_gerfinanc001.php");
              // exit;
          } else {
            db_fieldsmemory($result,0);
            $resultaux = $result;
              $arg = "matric=".$HTTP_POST_VARS["r01_regist"]; 
          }
    
         $matricula = $HTTP_POST_VARS["r01_regist"];
            $iInstit   = db_getsession("DB_instit");

          ///////// VERIFICA SE A MATRÍCULA POSSUI SALÁRIO

          if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
            $matricula = $HTTP_POST_VARS["r01_regist"];
            $iInstit   = db_getsession("DB_instit");

            $sSql      = "select rh143_rubrica as r14_rubric                                      ";
            $sSql     .= "  from rhhistoricocalculo                                               ";
            $sSql     .= " inner join rhfolhapagamento ON rh143_folhapagamento = rh141_sequencial ";
            $sSql     .= " where     rh143_regist    = {$matricula}                               ";
            $sSql     .= "       and rh141_anousu    = {$ano}                                     ";
            $sSql     .= "       and rh141_mesusu    = {$mes}                                     ";
            $sSql     .= "       and rh141_instit    = {$iInstit}                                 ";
            $sSql     .= "       and rh141_tipofolha = " . FolhaPagamento::TIPO_FOLHA_SALARIO .  "";
            $sSql     .= "       and not exists (select 1                                         ";
            $sSql     .= "                         from gerfres                                   ";
            $sSql     .= "                        where     r20_anousu = {$ano}                   ";
            $sSql     .= "                              and r20_mesusu = {$mes}                   ";
            $sSql     .= "                              and r20_regist = {$matricula}             ";
            $sSql     .= "                              and r20_instit = {$iInstit})              ";

            $resultgerfsal = db_query($sSql);

            // die(pg_numrows($resultgerfsal));

            if ($resultgerfsal && pg_numrows($resultgerfsal) != 0 ) {
              $temsalario = true;
            } else {
              $temsalario = false;
            }

          } else {
            $matricula = $HTTP_POST_VARS["r01_regist"];
            $resultgerfsal = db_query("select * 
                                    from gerfsal 
                                  where     r14_regist = $matricula 
                                            and r14_anousu = $ano 
                                            and r14_mesusu = $mes
                                            and r14_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfsal) != 0){
              $temsalario = true;
            }else{
              $temsalario = false;
            }
          }

            ///////// VERIFICA SE A MATRÍCULA POSSUI SUPLEMENTAR
            if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
              $iInstit = db_getsession("DB_instit");
              $sSql    = "SELECT *
                            FROM rhhistoricocalculo
                            INNER JOIN rhfolhapagamento
                              ON rh143_folhapagamento = rh141_sequencial
                            WHERE rh143_regist    = {$matricula}
                              AND rh141_anousu    = {$ano}
                              AND rh141_mesusu    = {$mes}
                              AND rh141_instit    = {$iInstit}
                              AND rh141_tipofolha = " . FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;

              $rsQuerySuplementar = db_query($sSql);

              if ($rsQuerySuplementar && pg_numrows($rsQuerySuplementar) != 0 ) {
                $lTemSuplementar = true;
              }
            }

            ///////// VERIFICA SE A MATRÍCULA POSSUI FÉRIAS
           $resultgerffer = db_query("select * 
                                    from gerffer 
                                where     r31_regist = $matricula 
                                      and r31_anousu = $ano 
                                      and r31_mesusu = $mes
                                       and r31_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerffer) != 0){
            $temferias = true;
          }else{
              $temferias = false;
          }
            ///////// VERIFICA SE A MATRÍCULA POSSUI RESCISAO
           $resultgerfres = db_query("select * 
                                    from gerfres 
                                      where     r20_regist = $matricula 
                                      and r20_anousu = $ano 
                                      and r20_mesusu = $mes
                                      and r20_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfres) != 0){
            $temrescisao = true;
          }else{
              $temrescisao = false;
          }
            ///////// VERIFICA SE A MATRÍCULA POSSUI ADIANTAMENTO  
           $resultgerfadi = db_query("select * 
                                    from gerfadi 
                                where     r22_regist = $matricula 
                                      and r22_anousu = $ano 
                                      and r22_mesusu = $mes
                                      and r22_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfadi) != 0){
            $temadiantamento = true;
          }else{
              $temadiantamento = false;
          }
            ///////// VERIFICA SE A MATRÍCULA POSSUI 13 SALÁRIO
           $resultgerfs13 = db_query("select * 
                                    from gerfs13 
                                where     r35_regist = $matricula 
                                      and r35_anousu = $ano 
                                      and r35_mesusu = $mes
                                      and r35_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfs13) != 0){
            $tem13salario = true;
          }else{
              $tem13salario = false;
          }

          ///////// VERIFICA SE A MATRÍCULA POSSUI COMPLEMENTAR
          if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
            $iInstit = db_getsession("DB_instit");
            $sSql = "SELECT *
                       FROM rhhistoricocalculo
                            INNER JOIN rhfolhapagamento ON rh143_folhapagamento = rh141_sequencial
                      WHERE rh143_regist    = {$matricula}
                        AND rh141_anousu    = {$ano}
                        AND rh141_mesusu    = {$mes}
                        AND rh141_instit    = {$iInstit}
                        AND rh141_tipofolha = " . FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;

            $rsQueryComplementar = db_query($sSql);
            if ( $rsQueryComplementar && pg_num_rows($rsQueryComplementar) != 0 ) {
              $temcomplementar = true;
            } else {
              $temcomplementar = false;
            }

          } else {
            $resultgerfcom = db_query("select * 
                                  from gerfcom 
                                  where     r48_regist = $matricula 
                                    and r48_anousu = $ano 
                                    and r48_mesusu = $mes
                                    and r48_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfcom) != 0){
              $temcomplementar = true;
            }else{
              $temcomplementar = false;
            }
          }
           
            ///////// VERIFICA SE A MATRÍCULA POSSUI ponto fixo
           $resultgerffx = db_query("select * 
                                   from gerffx 
                               where     r53_regist = $matricula 
                                     and r53_anousu = $ano 
                                     and r53_mesusu = $mes
                                     and r53_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerffx) != 0){
            $tempontofixo = true;
          }else{
              $tempontofixo = false;
          }
            ///////// VERIFICA SE A MATRÍCULA POSSUI ajuste previdencia
           $resultpreviden = db_query("select * 
                                       from rhpessoalmov
                                            inner join rhpessoal on rh01_regist = rh02_regist   
                                            inner join previden  on r60_numcgm = rh01_numcgm
                                                                and r60_anousu = $ano
                                                                and r60_mesusu = $mes
                                            where     rh02_regist = $matricula 
                                                  and rh02_anousu = $ano 
                                                  and rh02_mesusu = $mes
                                                   and rh02_instit = ".db_getsession("DB_instit")." limit 1");
            if(pg_numrows($resultpreviden) != 0){
            $temajustepreviden = true;
          }else{
              $temajustepreviden = false;
          }
    
            //////// VERIFICA SE A MATRÍCULA POSSUI ajuste irf
           $resultajusteir = db_query("select * 
                                        from rhpessoalmov
                                        inner join rhpessoal on rh01_regist = rh02_regist
                                        inner join ajusteir on r61_numcgm = rh01_numcgm
                                                     and r61_anousu = $ano
                                                     and r61_mesusu = $mes
                                        where     rh02_regist = $matricula 
                                              and rh02_anousu = $ano 
                                              and rh02_mesusu = $mes 
                                               and rh02_instit = ".db_getsession("DB_instit")." limit 1");
            if(pg_numrows($resultajusteir) != 0){
            $temajusteir = true;
          }else{
              $temajusteir = false;
          }
        }
            ///////// VERIFICA SE A MATRÍCULA POSSUI PROVISÃO DE FÉRIAS
           $resultgerfprovfer = db_query("select * 
                                    from gerfprovfer 
                                where     r93_regist = $matricula 
                                      and r93_anousu = $ano 
                                      and r93_mesusu = $mes
                                      and r93_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfprovfer) != 0){
            $temgerfprovfer = true;
          }else{
              $temgerfprovfer = false;
          }
            ///////// VERIFICA SE A MATRÍCULA POSSUI PROVISÃO 13
           $resultgerfprovs13 = db_query("select * 
                                    from gerfprovs13
                                where     r94_regist = $matricula 
                                      and r94_anousu = $ano 
                                      and r94_mesusu = $mes
                                      and r94_instit = ".db_getsession("DB_instit"));
            if(pg_numrows($resultgerfprovs13) != 0){
            $temgerfprovs13 = true;
          }else{
              $temgerfprovs13 = false;
          }
        
        $sCampos = "z01_numcgm, z01_nome, z01_ender, z01_munic, z01_uf, z01_cgccpf, z01_ident, rh37_funcao, rh37_descr";  
        
        $sWhere  = "     z01_numcgm  = " . pg_result($result,0,"k00_numcgm");
        $sWhere .= " AND rh01_regist = " . $r01_regist;
        $sWhere .= " AND rh01_instit = " . db_getsession('DB_instit'); 
        
        $sSqlInformacoesServidor  = "SELECT {$sCampos}                                                                  ";  
        $sSqlInformacoesServidor .= " FROM cgm                                                                          ";
        $sSqlInformacoesServidor .= "   INNER JOIN rhpessoal ON z01_numcgm  = rh01_numcgm                               ";
        $sSqlInformacoesServidor .= "   INNER JOIN rhfuncao  ON rh01_funcao = rh37_funcao AND rh01_instit = rh37_instit ";
        $sSqlInformacoesServidor .= " WHERE {$sWhere}                                                                   "; 
            
        $dados = db_query($sSqlInformacoesServidor);
        db_fieldsmemory($dados,0);    
    
      ?>
      <div class="container">
            <table width="100%" height="80%">
              <tr> 
                <td colspan="2" height="15%"> 
                <table width="100%" >
                    <tr> 
                      <td width="33%">
                       
                       <!-- Informações Matricula -->
                       <form action="">
                         <fieldset style="height:89%">
                           <legend>Informações do Servidor</legend>
                        
                          <table width="90%" >
                            <tr> 
                              <td title="Clique Aqui para ver os dados cadastrais." class="tabcols">
                                <strong style="color:blue">
                                  <a href='' onclick='js_mostracgm();return false;'>NumCgm:&nbsp;</a>
                                </strong>
                              </td>
                              <td class="tabcols" nowrap title="Clique Aqui para ver os dados cadastrais.">
                                <?php db_input('z01_numcgm', null, $Iz01_numcgm, true, 'text', 3, 'class="field-size1"'); ?>
                              </td>

                            </tr>
                            <tr>
                              <td class="tabcols" title="Clique Aqui para ver os dados cadastrais." nowrap>  
                                <?
                                parse_str($arg);
                                $Label = "<a href='' onclick='js_mostrapessoal();return false;'>$Lr01_regist</a>";
                                echo "<strong style=\"color:blue\">$Label</strong>";
                                ?>
                              </td>
                              <td>
                                <?php 

                                $sDesabilitaMatriculaPosterior = '';
                                $sDesabilitaMatriculaAnterior  = '';

                                if ( empty($iMatriculaPosterior) ) {
                                  $sDesabilitaMatriculaPosterior = 'disabled';
                                } 

                                if ( empty($iMatriculaAnterior) ) {
                                  $sDesabilitaMatriculaAnterior = 'disabled';
                                } 
                                echo "<input type='button' ".($lReadOnly ? 'disabled' : $sDesabilitaMatriculaAnterior) ." onClick='return js_abrirMatricula($iMatriculaAnterior)' style='font-weight:bold;' rel='ignore-css' value='<' />";
        		                    db_input("r01_regist", 8, $Ir01_regist, true, 'text', ($lReadOnly ? 3 : 1), "onchange='js_buscarMatricula();'");
        		                    db_input("iMatricula", 8, $Ir01_regist, true, 'hidden', 3);
                                echo "<input type='button' ".($lReadOnly ? 'disabled' : $sDesabilitaMatriculaPosterior) ." onClick='return js_abrirMatricula($iMatriculaPosterior)' style='font-weight:bold;' rel='ignore-css' value='>' />";

                            
                                ?>

                              </td>
                            </tr>
                            <tr> 
                              <td nowrap class="tabcols"><strong>Nome:</strong></td>
                              <td nowrap>
                                <?php db_input('z01_nome', null, $Iz01_nome, true, 'text', 3, 'class="field-size6"'); ?>
                              </td>
                            </tr>
                            <tr> 
                              <td nowrap class="tabcols"><strong>Endereço:</strong></td>
                              <td nowrap>
                                <?php db_input('z01_ender', null, $Sz01_ender, true, 'text', 3, 'class="field-size6"'); ?>
                              </td>
                            </tr>
                            <tr> 
                              <td nowrap class="tabcols"><strong>Município:</strong></td>
                              <td> 
                                <?php db_input('z01_munic', 24, $Sz01_munic, true, 'text', 3, "", "", "", "width: 194px;"); ?>
                                <strong class="tabcols">
                                  UF:
                                </strong>
                                <?php db_input('z01_uf', null, $Sz01_uf, true, 'text', 3, "", "", "", "width: 35px;"); ?>
                              </td>
                            </tr>
                            <tr> 
                              <td nowrap class="tabcols"><strong>Cargo:</strong></td>
                              <td>
                                <?php
                                  db_input('rh37_funcao', 2 , $Srh37_funcao, true, 'text', 3, "", "", "", "width: 41px;");
                                  db_input('rh37_descr' , 29, $Srh37_descr , true, 'text', 3, "", "", "", "width: 210px;"); 
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <?php if ( $lPermisaoCadastroServidor == 'true' && !empty($matric) && !$lReadOnly ) : ?>
                                  <input type="button" id="cadastroServidor" onclick="js_redirecionarCadastroServidor(<?php echo $matric ?>, this);" value="Alterar cadastro" title="Alterar cadastro do servidor" />
                                <?php endif; ?>
                              </td>
                            </tr>
                          </table>  
                         </fieldset>
                         <?php
                         if(isset($HTTP_POST_VARS["r01_regist"]) && !empty($HTTP_POST_VARS["r01_regist"]))
                           echo "<input type=\"hidden\" name=\"r01_regist\"  value=\"".$HTTP_POST_VARS["r01_regist"]."\">";
                         if(isset($HTTP_POST_VARS["q02_inscr"]) && !empty($HTTP_POST_VARS["q02_inscr"]))
                           echo "<input type=\"hidden\" name=\"q02_inscr\"  value=\"".$HTTP_POST_VARS["q02_inscr"]."\">";
                         if(isset($HTTP_POST_VARS["z01_numcgm"]) && !empty($HTTP_POST_VARS["z01_numcgm"]))
                           echo "<input type=\"hidden\" name=\"z01_numcgm\"  value=\"".$HTTP_POST_VARS["z01_numcgm"]."\">";
                         if(isset($HTTP_POST_VARS["v07_parcel"]) && !empty($HTTP_POST_VARS["v07_parcel"]))
                           echo "<input type=\"hidden\" name=\"v07_parcel\"  value=\"".$HTTP_POST_VARS["v07_parcel"]."\">";
                         if(isset($HTTP_POST_VARS["k00_numpre"]) && !empty($HTTP_POST_VARS["k00_numpre"]))
                           echo "<input type=\"hidden\" name=\"k00_numpre\"  value=\"".$HTTP_POST_VARS["k00_numpre"]."\">";
                         ?>
                       </form>
                       <!-- Informações Matricula / Fim--> 
                        
                      </td>
                      <td width="67%" height="100%" valign="top">

                        <!-- Calculo Pontos -->
                        <fieldset>
                        <legend>Informações Cálculo</legend>
                        <table>
                          <tr class="links">
                            <td valign="top" width="33,3%" height="90%">
                              <fieldset>
                                <legend>Cálculos:</legend>

                                <?php
                                  $aCalculos = array();
                                  $xopcao    = '';

                                  if ( @$temsalario == true || @$lTemSuplementar) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'salario';
                                    }

                                    $aCalculos['salario'] = array("sLabel" => "SALÁRIO");
                                    
                                    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()){
                                      $aCalculos['salario'] = array("sLabel" => "SALÁRIO/SUPLEMENTAR");  
                                    }
                                  }

                                  if (@$temrescisao == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'rescisao';
                                    }
                                    $aCalculos['rescisao'] = array("sLabel" => "RESCISÃO");
                                  }

                                  if(@$temferias == true ){

                                    if (empty($xopcao)) {
                                      $xopcao = 'ferias';
                                    }
                                    $aCalculos['ferias'] = array("sLabel" => "FÉRIAS");
                                  }

                                  if (@$tem13salario == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = '13salario';
                                    }                                      
                                    $aCalculos['13salario'] = array("sLabel" => "13o. SALÁRIO");                                                                                
                                  }

                                  if (@$temadiantamento == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'adiantamento';
                                    }
                                    $aCalculos['adiantamento'] = array("sLabel" => "ADIANTAMENTO");
                                  }
                                  
                                  if (@$temcomplementar == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'complementar';
                                    }                                      
                                    $aCalculos['complementar'] = array("sLabel" => "COMPLEMENTAR");
                                  }

                                 if (@$tempontofixo == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'fixo';
                                    }
                                    $aCalculos['fixo'] = array("sLabel" => "PONTO FIXO");
                                  }

                                  if (@$temgerfprovfer == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'gerfprovfer';
                                    }
                                    $aCalculos['gerfprovfer'] = array("sLabel" => "PROV. FÉRIAS");
                                  }

                                  if (@$temgerfprovs13 == true ){

                                    if (empty($xopcao)) {
                                      $xopcao = 'gerfprovs13';
                                    }
                                    $aCalculos['gerfprovs13'] = array("sLabel" => "PROV. 13o. SALÁRIO");
                                  }

                                  if (@$temajustepreviden == true ) {
                                    
                                    if (empty($xopcao)) {
                                      $xopcao = 'previden';
                                    }
                                    $aCalculos['previden'] = array("sLabel" => "AJUSTE PREVIDÊNCIA");
                                  }

                                  if (@$temajusteir == true ) {

                                    if (empty($xopcao)) {
                                      $xopcao = 'irf';
                                    }
                                    $aCalculos['irf'] = array("sLabel" => "AJUSTE I.R.R.F");
                                  }
                                
                                  echo '<div class="box-calculos">';
                                  foreach ($aCalculos as $sTipoCalculo => $aDados) {

                                    $oDados  = (object) $aDados;
                                    $sFuncao = "js_chama_link(\"{$sTipoCalculo}\"); js_MudaLink(this.parentNode);";
                                    echo "<div><a href='#' id='{$sTipoCalculo}' class='links2' onClick='{$sFuncao}'>{$oDados->sLabel}</a></div>";
                                  }
                                  echo '</div>';
                                ?>
        </fieldset>
    <script>
        
    function js_mostraPonto(sPonto, iMatricula, sChama, sMuda) {

      /**
       * TIPOS de PONTO:
       * Salário      : fs 
       * Adiantamento : fa
       * Férias       : fe
       * Rescisão     : fr
       * 13o          : f13
       * Complementar : com
       * Fixo         : fx        
       */
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_ponto','pes1_pontofx001.php?lConsulta=1&sPonto='+sPonto+'&iMatricula='+iMatricula+'&sChama='+sChama+'&sMuda='+sMuda+'&funcao_js=parent.js_preenche|0|1|2|3','Manutenção do Ponto',true);
    }      
    
    </script>
        
        </td>
        <td valign="top" style="font-size:11px" width="33,3%" height="100%">
        <fieldset style='height: 120px'>
          <legend><strong>Pontos</strong></legend>

            <?php
            $aPontos           = array();
            $aPrevidencia      = array();

            $iAnoUsu           = db_getsession('DB_anousu');
            $iMesusu           = DBPessoal::getMesFolha();

            $oCompetenciaFolha = new DBCompetencia($ano, $mes);
            $lFolhaAberta      = true;

            if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {  

              /**
               * Desabilita manutenção do ponto para competencias anteriores
               */
              if( ($ano.$mes) < (DBPessoal::getAnoFolha().DBPessoal::getMesFolha())) {
                $lFolhaAberta = false;
              }
            }

            if ( @$temsalario ) {
              
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {  
                $lFolhaAberta                  = FolhaPagamentoSalario::hasFolhaAberta($oCompetenciaFolha);
              }

              $lPermiteManutecao             = (db_permissaomenu( $iAnoUsu, 952, 4506 ) == "true" && $lFolhaAberta) ;
              $aPontos['salario']            = array("sLabel" => "SALÁRIO", "lPermiteManutencao" => $lPermiteManutecao);
            }
            
            if (isset($lTemSuplementar)) {
              
              $lFolhaAberta           = FolhaPagamentoSuplementar::hasFolhaAberta($oCompetenciaFolha);
              $lPermiteManutecao      = (db_permissaomenu( $iAnoUsu, 952, 4506 ) == "true" && $lFolhaAberta) ;
              $aPontos['suplementar'] = array("sLabel" => "SUPLEMENTAR", "lPermiteManutencao" => $lPermiteManutecao);
            }
              
            if ( @$temrescisao ) {
              
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $lFolhaAberta                = FolhaPagamentoRescisao::hasFolhaAberta($oCompetenciaFolha);
              }     
              $lPermiteManutecao             = db_permissaomenu( $iAnoUsu, 952, 4510 ) == "true" && $lFolhaAberta;
              $aPontos['rescisao']           = array("sLabel" => "RESCISÃO", "lPermiteManutencao" => $lPermiteManutecao);
            }
            
            if ( @$temferias ) {

              // Remove a opção de editar o ponto de férias caso o salário e a complementar estejam fechados
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                if ( !FolhaPagamentoSalario::hasFolhaAberta($oCompetenciaFolha)
                  && !FolhaPagamentoComplementar::hasFolhaAberta($oCompetenciaFolha) ) {

                  $aPontos['ferias'] = array("sLabel" => "FÉRIAS", "lPermiteManutencao" => false);
                } else {

                $lPermiteManutecao = db_permissaomenu( $iAnoUsu, 952, 4509) == "true" && $lFolhaAberta;
                $aPontos['ferias'] = array("sLabel" => "FÉRIAS", "lPermiteManutencao" => $lPermiteManutecao);
                }
              } else {

                $lPermiteManutecao = db_permissaomenu( $iAnoUsu, 952, 4509) == "true" && $lFolhaAberta;
                $aPontos['ferias'] = array("sLabel" => "FÉRIAS", "lPermiteManutencao" => $lPermiteManutecao);
              }
            }
            
            if ( @$tem13salario ) {
              
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $lFolhaAberta                = FolhaPagamento13o::hasFolhaAberta($oCompetenciaFolha);
              }
              $lPermiteManutecao             = db_permissaomenu( $iAnoUsu, 952, 4511 ) == "true" && $lFolhaAberta;
              $aPontos['13salario']          = array("sLabel" => "13º SALÁRIO", "lPermiteManutencao" => $lPermiteManutecao);
            }
            
            if ( @$temadiantamento ) {
              
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $lFolhaAberta                = FolhaPagamentoAdiantamento::hasFolhaAberta($oCompetenciaFolha);
              }
              $lPermiteManutecao             = db_permissaomenu( $iAnoUsu, 952, 4508 ) == "true" && $lFolhaAberta;
              $aPontos['adiantamento']       = array("sLabel" => "ADIANTAMENTO", "lPermiteManutencao" => $lPermiteManutecao);
            }
            
            if ( @$temcomplementar ) {

              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $lFolhaAberta                  = FolhaPagamentoComplementar::hasFolhaAberta($oCompetenciaFolha);
              }
              
              $lPermiteManutecao             = db_permissaomenu( $iAnoUsu, 952, 4512 ) == "true" && $lFolhaAberta;
              $aPontos['complementar2']      = array("sLabel" => "COMPLEMENTAR", "lPermiteManutencao" => $lPermiteManutecao);
            }
            
            if ( @$tempontofixo ) {

              /**
               * Desabilita manutenção do ponto para competencias anteriores
               */
              if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
                $lFolhaAberta   = true;
                if( ($ano.$mes) < (DBPessoal::getAnoFolha().DBPessoal::getMesFolha())) {
                  $lFolhaAberta = false;
                }
              }

              $lPermiteManutecao             = db_permissaomenu( $iAnoUsu, 952, 4507 ) == "true" && $lFolhaAberta;
              $aPontos['fixo']               = array("sLabel" => "PONTO FIXO", "lPermiteManutencao" => $lPermiteManutecao);
            }

            if ( @$temgerfprovfer ) {

              $lPermiteManutecao  = null;
              $aPontos['provfer'] = array("sLabel" => "PROV. DE FÉRIAS", "lPermiteManutencao" => $lPermiteManutecao);
            }

            if ( @$temgerfprovs13 ) {

              $lPermiteManutecao  = null;
              $aPontos['provs13'] = array("sLabel" => "PROV. DE 13º", "lPermiteManutencao" => $lPermiteManutecao);
            }

            if ( @$temajustepreviden ) {

              $lPermiteManutecao        = null;
              $aPrevidencia['previden'] = array("sLabel" => "AJUSTE PREVIDÊNCIA", "lPermiteManutencao" => $lPermiteManutecao);
            }

            if ( @$temajusteir ) {

              $lPermiteManutecao   = null;
              $aPrevidencia['irf'] = array("sLabel" => "AJUSTE IRRF", "lPermiteManutencao" => $lPermiteManutecao);
            }


            echo "<div class='box-pontos'>";


            foreach ($aPontos as $sTipoPonto => $aDados ) {

              $oDados = (object) $aDados;

              $sFuncao = "js_chama_link2(\"{$sTipoPonto}\"); js_MudaLink( this.parentNode );";

              echo "<div class='tem{$sTipoPonto}2'> ";
              
              if(!$lReadOnly) {
                echo "<div class='manutencao-ponto links2'>";
  
                  /**
                   * Habilita link para ponto caso permita manutencao
                   */
                  if ( $oDados->lPermiteManutencao ) {
  
                    /**
                     * Array com siglas por tipo de ponto 
                     */
                    $aSiglasPonto = array(
                      'salario'       => 'fs', 
                      'adiantamento'  => 'fa',    
                      'ferias'        => 'fe',    
                      'rescisao'      => 'fr',    
                      '13salario'     => 'f13',   
                      'complementar2' => 'com',   
                      'fixo'          => 'fx',
                      'suplementar'   => 'fs'         
                    );
  
                    $sSiglaPonto = $aSiglasPonto[ $sTipoPonto ];
  
                    $sFuncaoManutencao = "js_mostraPonto(\"$sSiglaPonto\", \"$matricula\", \"{$sTipoPonto}\", this.parentNode)";
                    echo "<a href='#' onClick='{$sFuncaoManutencao}' class='links2' >[ P ]</a> ";
                  } 
  
                echo "</div>";
              }
              echo "  <a href='#' class='links2' onclick='{$sFuncao}'> " . $oDados->sLabel ." </a>";
              echo "</div>";
              
            }

            
            foreach ($aPrevidencia as $tipo => $aDados) {
              
              $oDados  = (object) $aDados;
              $sFuncao = " js_chama_link(\"{$tipo}\"); js_MudaLink( this.parentNode ); ";
              
              echo "<div>";
              echo "  <div > ";
              echo "    <a href='#' class='links2' onclick='{$sFuncao}'> " . $oDados->sLabel ." </a>";
              echo "  </div>";
              echo "<div>";
            }
            

            echo "</div>";
            

    
                            global $subpes;
                            $subpes = db_anofolha()."/".db_mesfolha();
                            global $diversos;
                            db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ));
                            for($Idiversos=0;$Idiversos<count($diversos);$Idiversos++){
                              $codigo = $diversos[$Idiversos]["r07_codigo"];
                              global $$codigo;
                              eval('$$codigo = '.$diversos[$Idiversos]["r07_valor"].";");
                            }
                            $result_variaveis = db_retorno_variaveis($ano, $mes, $matricula);
          
    $campos_pessoal_ = "RH02_ANOUSU as r01_anousu, 
                        RH02_MESUSU as r01_mesusu, 
                        RH01_REGIST as r01_regist,
                        RH01_NUMCGM as r01_numcgm, 
                        trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
                        RH01_ADMISS as r01_admiss, 
                        RH05_RECIS as r01_recis, 
                        RH02_tbprev as r01_tbprev,
                        RH30_REGIME as r01_regime, 
                        RH30_VINCULO as r01_tpvinc,
                        RH02_salari as r01_salari,
                        RH03_PADRAO as r01_padrao,
                        RH02_HRSSEM as r01_hrssem,
                        RH02_HRSMEN as r01_hrsmen, 
                        RH01_NASC as r01_nasc,
                        rh65_rubric as r01_rubric, 
                        rh65_valor as r01_arredn,
                        RH02_EQUIP  as r01_equip,
                        RH01_PROGRES as r01_anter,  
                        RH01_TRIENIO as r01_trien, 
                        (case when RH01_PROGRES IS NOT NULL then 'S' else 'N' end) as r01_progr, 
                        RH15_DATA as r01_fgts,
                        RH05_CAUSA as r01_causa,  
                        RH05_CAUB as r01_caub,  
                        RH05_MREMUN as r01_mremun,
                        RH01_FUNCAO as r01_funcao,
                        RH01_CLAS1 as r01_clas1,
                        RH01_CLAS2 as r01_clas2,
                        RH02_TPCONT as r01_tpcont,
                        RH02_OCORRE as r01_ocorre, 
                        rh51_b13fo as r01_b13fo, 
                        rh51_basefo as r01_basefo,
                        rh51_descfo as r01_descfo, 
                        rh51_d13fo as r01_d13fo,
                        RH02_TIPSAL as r01_tipsal,
                        RH19_PROPI as r01_propi ,
                        rh01_depirf as r01_depirf, 
                        rh02_codreg, 
                        rh01_depsf as r01_depsf";
                $condicaoaux      = " and rh02_regist = ".db_sqlformat( $matricula );
                db_selectmax("pessoal", "select ".$campos_pessoal_." from rhpessoalmov 
                         inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                         inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                                 and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                         inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                         left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                         left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                         left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                           and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                         left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                     and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                         left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                         left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                         left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                         left join rhpesprop on rh19_regist = rh02_seqpes
                         ".bb_condicaosubpes("rh02_" ).$condicaoaux );
          
                $Ipessoal = 0;

                if ( $xopcao == 'salario' ){
                  $result = &$resultgerfsal;
                  $sigla   = 'r14_';
                }elseif ( $xopcao == 'ferias' ){
                  $result = &$resultgerffer;
                  $sigla   = 'r31_';
                }elseif ( $xopcao == 'rescisao' ){
                  $result = &$resultgerfres;
                  $sigla   = 'r20_';
                }elseif ($xopcao == 'adiantamento'){
                  $result = &$resultgerfadi;
                  $sigla   = 'r22_';
                }elseif ($xopcao == '13salario'){
                  $result = &$resultgerfs13;
                  $sigla   = 'r35_';
                }elseif ($xopcao == 'complementar'){
                  $result = &$resultgerfcom;
                  $sigla   = 'r48_';
                }elseif ($xopcao == 'fixo'){
                  $result = &$resultgerffx;
                  $sigla   = 'r53_';
                }elseif ($xopcao == 'gerfprovfer'){
                  $result = &$resultgerfprovfer;
                  $sigla   = 'r93_';
                }               $rub_cond    = "";
                $rub_bases   = "";
                $rub_formula = "";
                
                if ( $xopcao == 'fixo' ) {

                  $result = $resultgerfsal;
                  $sigla   = 'r14_'; 
                }


                if(isset($sigla)){
                  // echo "<br><br>  sandro 1    sigla --> $sigla   xopcao --> $xopcao ";
                  $arr_cond     = array();
                  $arr_bases    = array();
                  $arr_rub_base = array();
                  $arr_formula  = array();
                  $conta_base   = 1;
                  $iRegistros   = $result ? pg_numrows($result) : 0;

                  for ($x = 0; $x < $iRegistros; $x++) {

                    db_fieldsmemory($result,$x,true);
                    $strrubrica  = $sigla."rubric";
                    $rubrica     = $$strrubrica;
                    $condicaoaux = " where rh27_rubric = ".db_sqlformat( $rubrica);
                    global $rubr_;
                    db_selectmax( "rubr_", "select * from rhrubricas ".$condicaoaux );

                    $r10_pd = ( 1 == $rubr_[0]["rh27_pd"] );
                    $formula = $rubr_[0]["rh27_form"];
                    $qual_form = 1;
                    $cond = trim($rubr_[0]["rh27_cond2"]);
                    $cond = str_replace('$f','$F',$cond);

                    $cond = trim($rubr_[0]["rh27_cond3"]);
                    $cond = str_replace('$f','$F',$cond);

                    $arr_cond[$rubrica]     = $rubrica.$qual_form;
                    $arr_formula[$rubrica]  = $rubrica."|".trim($formula);
                    $pos_base               = strpos("#".$formula,"B")+0;

                    if( $pos_base > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){
                      $base_mae = substr("#".$formula,$pos_base,4);
                      while( $pos_base  > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){
                        $base = substr("#".$formula,$pos_base,4);
                        $pos = db_ascan($arr_bases,$base);
                        if($pos == 0){
                          $arr_bases[$base] = $base;
                          if(!isset($chaves) && $conta_base == 1){
                            $bases = $base;
                          }
                          $conta_base++;
                        }
                        $arr_rub_base[$base.$rubrica] = $base.$rubrica;
                        $formula = db_strtran($formula,$base,"|") ;
                        $pos_base = (strpos("#".$formula,"B")+0);
                      }
                    }
                  }
                  if(@$temferias == true ){

                    $condicaoaux = " and r33_codtab = ".db_sqlformat($r01_tbprev+2);
                    global $inssirf_;
                    $achou_tabela = db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
                    $inssirf_base_ferias = "B002";
                    if( !db_empty( $inssirf_[0]["r33_basfer"] )){
                      $arr_bases[$inssirf_[0]["r33_basfer"]] = $inssirf_[0]["r33_basfer"];
                    }
                  }
                  $rub_cond    = implode($arr_cond,',');
                  $rub_bases   = implode($arr_rub_base,',');
                  $rub_formula = implode($arr_formula,',');
                  if(isset($rub_cond)){
                    echo "<script> $('rub_cond').value = '".$rub_cond."';</script>";
                  }
                  if(isset($rub_bases)){
                    echo "<script> $('rub_bases').value = '".$rub_bases."';</script>";
                  }
                  if(isset($rub_formula)){
                    echo "<script> $('rub_formula').value = '".$rub_formula."';</script>";

                  }
                }
                            ?>
        </fieldset>
                    </td>
                    <td valign="top" style="font-size:11px" width="33,3%" height="100%">
                      <fieldset style="height: 120px">
                        <legend><strong>Legendas</strong></legend>
                        <table width="100%" border="0">
                          <tr>
                            <td nowrap class="tabcols">
                              <strong># - Incidência da Base </strong>
                            </td>
                          </tr>
                          <tr>
                            <td nowrap class="tabcols">
                              <strong>B - Fórmula com a Base</strong>
                            </td>
                          </tr>
                          <tr>
                            <td nowrap class="tabcols">
                              <strong>* - Número da Fórmula Escolhida </strong>
                            </td>
                          </tr>
                        </table>
                      </fieldset>
                    </td>
                  </table>
                      
                      <!-- Calculos / Legendas fim -->
                    </td>
                  </tr>
                </table>
                </fieldset>
              </td>
            </tr>
            <tr> 
              <td colspan="2"  align="center" valign="middle"> 

              <!--Calculo-->
              <fieldset id="calculoFolha" style="padding: 0 0 5px 0; overflow: hidden;">
                <legend id="tituloFolha"></legend>
                <table border="0" height="100%" width="100%" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td align="center">
                      <iframe id="debitos" height="100%" width="100%" name="debitos" border="0" src="pes3_gerfinanc018.php?opcao=<?=$xopcao?>&numcgm=<?=$z01_numcgm?>&matricula=<?=$matricula?>&ano=<?=$ano?>&mes=<?=$mes?>&tbprev=<?$r01_tbprev?>&bases=<?=@$bases?>&rub_bases=<?=@$rub_bases?>&rub_cond=<?=@$rub_cond?>&rub_formula=<?=@$rub_formula?>"></iframe> 
                      <input type="hidden" name="matricula"  value="<?=$matricula?>">
                      <input type="hidden" name="numcgm"  value="<?=$z01_numcgm?>">
                      <input type="hidden" name="opcao"  value="<?=$xopcao?>">
                      <input type="hidden" name="tbprev"  value="<?=$r01_tbprev?>">
                      <input type="hidden" name="ano"  value="<?=$ano?>">
                      <input type="hidden" name="mes"  value="<?=$mes?>">
                    </td>
                  </tr>
                </table>
              </fieldset>
              <!-- Calculo / fim -->
              </td>
            </tr>
            <tr> 
              <td height="28px" valign="middle" colspan="2" align="center"> 
                <?
                if(!isset($novapesquisa)){
                  $novapesquisa = "pes3_gerfinanc001.php";
                } 
                if(isset($voltarcorreto)){
                  if(isset($rubric)){
                    $novapesquisa = "pes3_codfinanc001.php";
                    echo "
                    <input name='retornar' type='button' id='voltar' value='Voltar' title='Voltar para consulta financeira por código' onclick='location.href=\"pes3_codfinanc002.php?rubric=".$rubric."&ano=".$ano."&mes=".$mes."&opcao=".$xopcao."\"'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ";
                  }else if(isset($funcao)){
                    $novapesquisa = "pes3_consrhfuncao001.php";
                    echo "
                    <input name='retornar' type='button' id='voltar' value='Voltar' title='Voltar para consulta cargo' onclick='location.href=\"pes3_consrhfuncao002.php?funcao=".$funcao."&ano=".$ano."&mes=".$mes."\"'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ";
                  }else if(isset($lotacao)){
                    $novapesquisa = "pes3_consrhlotacao001.php";
                    echo "
                    <input name='retornar' type='button' id='voltar' value='Voltar' title='Voltar para consulta lotação' onclick='location.href=\"pes3_consrhlotacao002.php?lotacao=".$lotacao."&ano=".$ano."&mes=".$mes."\"'>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    ";
                  }
                }
                
                
                
          db_input("novapesquisa",8 ,0,true,'hidden',4);
          db_input("rub_cond"    ,8 ,0,true,'hidden',4);
          db_input("rub_bases"   ,8 ,0,true,'hidden',4);
          db_input("rub_formula" ,20,0,true,'hidden',4);
          
      if(isset($rub_cond)){
        echo "<script> $('rub_cond').value = '".$rub_cond."';</script>";
      }
      
      if(isset($rub_bases)){
        echo "<script> $('rub_bases').value = '".$rub_bases."';</script>";
      }
      
      if(isset($rub_formula)){
        echo "<script> $('rub_formula').value = '".$rub_formula."';</script>";
      }
      
      if( isset($arr_bases) ) {
        db_select("bases", $arr_bases, true, 1,"onchange='js_submitbase();' rel='ignore-css'");
      }
      if (!$lReadOnly) {
                ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              
              <input class="field-size2" type="button"    name="vars1" value="Bases"            onclick="js_Pesquisa('Bases');" >
              <input class="field-size2" type="button"    name="vars2" value="Diversos"         onclick="js_Pesquisa('Diversos');" >
              <input class="field-size2" type="button"    name="vars"  value="Variáveis"        onclick="js_Pesquisa('Variaveis');" >
              <input class="field-size3" type="button"    name="vars3" value="Cheques Emitidos" onclick="js_Pesquisa('ChequesEmitidos');" >
              <input class="field-size3" name="retornar"  type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='<?=($novapesquisa)?>'"> 
              <input class="field-size2" name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">  
              <input class="field-size2" name="imprimir"  type="button" id="imprimir" value="Imprimir" title="Imprimir" onclick="js_relatorio();">
              <?}?>
              <span id="boxToglePeriodo">
                <a id="togglePeriodo"  <? echo $lReadOnly ? "" : "href=''"; ?>><strong>Período:</strong></a>
                <span id="ctnPeriodoAno"></span>
                <span id="ctnPeriodoMes"></span>
                <span id="ctnPeriodoInput">
                <?
                  db_input("ano", 4, '', true, 'text', ($lReadOnly ? 3 : 4), null, null, null, "display:none;");
                  db_input("mes", 2, '', true, 'text', ($lReadOnly ? 3 : 4), null, null, null, "display:none;");
                ?>
                </span>
              </span>
    
              </td>   
             </tr>
          </table>
        </form>
      <?
    } else {
    ?>
        <form name="form1" id='form1' method="post">
          <table border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td align="right" title="<?=$Tr01_regist?>"> 
                  <?
                    $iDbOpcao = 1;
                    if(isset($_GET['lConsulta'])){
                      $iDbOpcao = 3;
                    }  
                    db_ancora($Lr01_regist,'js_pesquisaregist(true);', $iDbOpcao);
                  ?>
                  &nbsp;&nbsp;&nbsp;
                </td >
                <td align="left" > 
                  <?
                    db_input("r01_regist",8,$Ir01_regist,true,'text',$iDbOpcao,"onchange='js_pesquisaregist(false);'");
                    db_input("z01_nome",40,$Iz01_nome,true,'text',3);
                  ?>
                </td>
              </tr>
              <tr> 
                <td height="25" align="center" colspan="2">
                  <input onClick="return js_verificaregistro();" id="pesquisar" type="submit" value="Pesquisar" name="pesquisar">
                </td>
              </tr>
            </table>
          </form>
        <?  }
      ?>
      </center>
      </div>
    <?if (!$lReadOnly) {
     db_menu();
     }
    ?>
    </body>
</html>


<script type="text/javascript">

function iframeLoaded(iSize) {

  if (iSize === undefined)
    iSize = 0;

  var iFrameID = document.getElementById('debitos');
  if(iFrameID) {
        // here you can make the height, I delete it first, then I make it again
        iFrameID.height = "";
        iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + iSize + "px";
  }   
}

var   oGet         = js_urlToObject(window.location.search);
/*
 * Variável que controla se campos são editáveis ou não.
 */
var   lReadOnly    = oGet.lReadOnly;

/**
 * Busca matricula
 * Atualiza tela passando por get a matricula
 *
 * @param integer $iMatricula
 * @access public
 * @return boolean | void
 */
function js_abrirMatricula(iMatricula) {

  if ( js_empty(iMatricula) ) {
    return false;
  } 

  var iAno = '';
  var iMes = '';

  if ($('ano')) {
    iAno = $('ano').value;
  }

  if ($('mes')) {
    iMes = $('mes').value;
  }
     
  location.href = 'pes3_gerfinanc001.php?iMatricula=' + iMatricula + '&ano=' + iAno + '&mes=' + iMes;
}

/**
 * Busca matricula
 *
 * @access public
 * @return boolean | void
 */
function js_buscarMatricula() {

  var iMatricula  = '';

  if ($('iMatricula')) {
    iMatricula = $('iMatricula').value;
  }
  var iMatriculaPesquisar = $('r01_regist').value;

  if ( js_empty(iMatriculaPesquisar) ) {

    $('r01_regist').value = iMatricula; 
    return false;
  }

  if ( iMatriculaPesquisar == iMatricula ) {
    return
  }

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_rhpessoal', 'func_rhpessoal.php?pesquisa_chave=' + iMatriculaPesquisar + '&funcao_js=parent.js_retornoBuscaMatricula', 'Pesquisa', false);
}

/**
 * Chama funcao js_buscarMatricula no onblur do input
 * - Para apos usar funcoes de validacao do campo, nao deixar vazio, funcao js_buscarMatricula retorna matricula anterior a alteracao
 */
$('r01_regist').observe('blur', function() {
  return js_buscarMatricula();
});

/**
 * Retorno da pesquisa pelo servidor
 *
 * @param string $sNomeServidor
 * @param boolean $lErro
 * @access public
 * @return boolean | void
 */
function js_retornoBuscaMatricula(sNomeServidor, lErro) {

  var iMatricula  = '';

  if ($('iMatricula')) {
    iMatricula = $('iMatricula').value;
  }
  var iMatriculaPesquisar = $('r01_regist').value;

  if ( lErro ) {

    alert('Matrícula não encontrada.');
    $('r01_regist').value = iMatricula; 
    return false;
  }

  js_abrirMatricula(iMatriculaPesquisar);
}



/**
 * Redireciona para pagina manutencao servidores 
 */
function js_redirecionarCadastroServidor(iServidor, oElemento) {

  if ( iServidor == ''  && oElemento.id != 'cadastroServidor') {
    return false;
  }

  location.href = 'pes1_rhpessoal002.php?iServidor=' + iServidor;
}

/**
 * Mes e ano inicial
 */   
var iAnoAtual       = parseFloat($F('ano'));
var iMesAtual       = parseFloat($F('mes'));

/**
 * Mes e ano limite
 */   
var iAnoLimite      = parseFloat($F('anoLimite'));
var iMesLimite      = parseFloat($F('mesLimite'));

/**
 * Ano e Mês de implantação do sistema
 */   
var iAnoImplantacao = parseFloat($F('anoImplantacao'));
var iMesImplantacao = parseFloat($F('mesImplantacao'));

             
/**
 * Cria array com anos para exibir no "select"
 */   
var aAnos = new Array();

for (var i = iAnoLimite; i >= iAnoImplantacao; i--){

  if(aAnos.length == 15) {
    break;
  }
  aAnos.push(i);
};

/**
 * Retorna array com meses 
 */
function js_atualizaMeses(iMinimo, iMaximo) {

  if (iMinimo === null) {
    iMinimo = 1;
  } 

  if (iMaximo === null) {
    iMaximo = 12;
  }

  var aMeses            = new Array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
  var aRetorno          = new Array();
  oComboMes.clearItens();
  
  for ( var iIndice = iMinimo; iIndice <= iMaximo; iIndice++) {
    
    var sMes = js_strLeftPad(iIndice, 2, '0');
    oComboMes.addItem(sMes, sMes);
    aRetorno.push(iIndice);
  }

  return true;
}

/**
 * Cria select do ano
 */   
var oComboAno = new DBComboBox('oComboAno', 'oComboAno', new Array(), '60');
 
for(var i = 0; i < aAnos.length; i++){
  oComboAno.addItem(aAnos[i], aAnos[i]);
}
oComboAno.show( $('ctnPeriodoAno') );

/**
 * Cria select do mês
 */   
var oComboMes = new DBComboBox('oComboMes', 'oComboMes', new Array(), '40');

js_atualizaMeses(null,null);

if (iAnoAtual == iAnoLimite) {
  js_atualizaMeses(null, iMesLimite);  
}
if (iAnoAtual == iAnoImplantacao) {
  js_atualizaMeses(iMesImplantacao, null);  
}
oComboMes.show($('ctnPeriodoMes'));

/**
 * Toggle do periodo, muda entre "select" e "input"
 */   
(function js_togglePeriodo() {

  var iMesSelecionado     = parseInt(0);
  
  var togglePeriodo       = document.getElementById('togglePeriodo');
  var lPeriodo            = true;

  // inputs
  var oInputAno           = document.getElementById('ano');
      oInputAno.maxLength = 4;

  var oInputMes           = document.getElementById('mes');
      oInputMes.maxLength = 2;

  // selects
  var oSelectAno          = document.getElementById('oComboAno');
      oSelectAno.value    = iAnoAtual;
      
  var oSelectMes          = document.getElementById('oComboMes');
      oSelectMes.value    = iMesAtual;

  if (iAnoAtual == iAnoLimite){

    iMesSelecionado      = iMesLimite;
    js_atualizaMeses(null, iMesLimite);  
    
  } else if (iAnoAtual  == iAnoImplantacao) {

    iMesSelecionado      = iMesImplantacao;
    js_atualizaMeses(iMesImplantacao, null);  
  } else {

    iMesSelecionado     = iMesAtual;
    js_atualizaMeses(null, null); 
  }

  oComboMes.setValue(iMesSelecionado);

  /**
   * DBHint
   * Exibe mensagem ao passar mouse na tag strong #togglePeriodo
   */
  if (!lReadOnly) {   
  oHint             = new DBHint('oHint');
  oHint.setText('Clique aqui para digitar a data manualmente');
  oHint.setShowEvents(new Array('onmouseover'));
  oHint.setHideEvents(new Array('onmouseout'));
  oHint.make(togglePeriodo);
  }
  /**
   * Select ano
   * verifica se é ano limite(atual) ou inicial(data da implantação do sistema)
   * atualiza valor do imput #ano
   */   
  oSelectAno.observe('change', function() {

    $('ano').value = oComboAno.getValue();
    
    var iAnoSelecionado = parseFloat(this.value);
    var iMesSelecionado = oComboMes.getValue();

    js_atualizaMeses(null, null);
    
    if ( iAnoSelecionado == iAnoLimite ) {

      js_atualizaMeses(null, iMesLimite);      
      $("mes").value = $F('oComboMes');
      //js_divCarregando("Aguarde, processando ...","processamento_calculo");

      document.formatu.submit();
    } else if (iAnoSelecionado == iAnoImplantacao) {
      js_atualizaMeses(iMesImplantacao, null);  
    } 

    oComboMes.setValue(iMesSelecionado);

  });             

  /**
   * Select mês
   * atualiza do valor do input #mes
   */   
  oSelectMes.observe('change', function() {    

    $('mes').value = oComboMes.getValue();
    //js_divCarregando("Aguarde, processando ...","processamento_calculo");
    document.formatu.submit();
  });

  /**
   * Input ano
   */   
  oInputAno.observe('focus', function() {
    this.value = ''; 
  });

  oInputAno.observe('keyup', function() {

    if (oInputAno.value.length == 4) {
      oInputMes.focus();
    };
  });

  oInputAno.observe('blur', function(){

    var iAnoSelecionado = parseFloat(this.value);

    if (this.value.length < 4) {
      iAnoSelecionado = iAnoAtual;
    } else {

      if (this.value > iAnoLimite) {
        iAnoSelecionado = iAnoLimite;
      }
    }    
      
    js_atualizaMeses(null, null);
    if ( iAnoSelecionado == iAnoLimite ) {
      js_atualizaMeses(null, iMesLimite);  
    } 
    if (iAnoSelecionado == iAnoImplantacao) {
      js_atualizaMeses(iMesImplantacao, null);  
    } 
      
    this.value       = iAnoSelecionado;
    oComboAno.setValue(iAnoSelecionado);
  });

  /**
   * Input mês
   */   
  oInputMes.observe('focus', function() {
    this.value = ''; 
  });

  oInputMes.observe('keyup', function() {

    if(this.value.length == 1 && this.value > 1 && this.value < 10) {
      this.value = js_strLeftPad(this.value, 2, '0'); 
    }

    if(this.value.length == 2) {

      var iAnoSelecionado = parseFloat(oInputAno.value);

      if ( iAnoSelecionado  == iAnoLimite && this.value > iMesLimite) {

        this.value = iMesLimite;

      } else if ( iAnoSelecionado  == iAnoImplantacao && this.value < iMesImplantacao) {

        this.value = iMesImplantacao;

      } else if ( iAnoSelecionado  < iAnoLimite && this.value > 12 ) {

         this.value = 12; 

      } 

      if(this.value == '00') {
        return;
      }
      
      //js_divCarregando("Aguarde, processando ...","processamento_calculo");
      document.formatu.submit();
    } 

    this.value = this.value.replace(/[^0-9]/g, '');
    
  });

  oInputMes.observe('blur', function(){

    this.value = js_strLeftPad(this.value, 2, '0');
    
    if (oInputAno.value == iAnoLimite) {
    
      if (this.value > iMesLimite) {
        this.value = iMesLimite;          
      } 
      
    } else if (oInputAno.value == iAnoImplantacao) {
    
      if (this.value < iMesImplantacao) {
        this.value = iMesImplantacao;
      }
    
    }
    if (parseFloat(this.value) == 0) {
      this.value = iMesAtual;
      this.value = js_strLeftPad(this.value, 2, '0');
      
      return;
    } 

    if (this.value.length == 0) {
        
      this.value = iMesAtual;
      this.value = js_strLeftPad(this.value, 2, '0');
      return;
    } 
    
    this.value = js_strLeftPad(this.value, 2, '0');
    
    oComboMes.setValue(this.value);     
    document.formatu.submit();
  });

  if (lReadOnly) {
   
   $('ano').style.display = "";
   $('mes').style.display = "";

   oSelectAno.style.display = 'none';
   oSelectMes.style.display = 'none';

  }

  if (!lReadOnly) {
  /**
   * Se lPeriodo for true muda de 2 select pra 2 input e muda bool pra false
   */   
  togglePeriodo.observe('click', function() {

    if (lPeriodo) {

     lPeriodo = false;
     
     $('ano').style.display = "";
     $('mes').style.display = "";

     oSelectAno.style.display = 'none';
     oSelectMes.style.display = 'none';

     oHint.setText('Clique aqui para selecionar a data.');
     
    } else {

      lPeriodo = true;

      $('ano').style.display = "none";
      $('mes').style.display = "none";
      
      oSelectAno.style.display = '';
      oSelectMes.style.display = '';
      
      oHint.setText('Clique aqui para digitar a data manualmente.');
    }

  });
}
  /**
   * Desabilita link do toggle
   */   
  togglePeriodo.onclick = function() {
    return false;
  };

  oComboAno.setValue($F('ano'));
  oComboMes.setValue($F('mes'));
    
})();

function js_submitbase(){
   obj=document.createElement('input');
   obj.setAttribute('name','chaves');
   obj.setAttribute('type','hidden');
   obj.setAttribute('value',1);
   document.formatu.appendChild(obj);
   document.formatu.submit();
}
function js_verificaregistro(){
  if($F('r01_regist') =='' ) 
  { 
    alert('Informe matricula.');
    return false; 
  }
  return true;
}

function js_pesquisaregist(mostra){
     if(mostra==true){
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostraregist1|rh01_regist|z01_nome','Pesquisa',true);
     }else{
       js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+$F('r01_regist')+'&funcao_js=parent.js_mostraregist','Pesquisa',false);
//     db_iframepessoal.jan.location.href =               'func_rhpessoal.php?pesquisa_chave='+document.form1.r01_regist.value+'&funcao_js=parent.js_mostraregist';                              
       
     }
}
function js_mostraregist(chave,erro) {
  
  $('z01_nome').value = chave;
  if(erro==true){
    
     $('r01_regist').focus();
     $('r01_regist').value = '';
  }
}
function js_mostraregist1(chave1,chave2){
 $('r01_regist').value = chave1;
 $('z01_nome').value   = chave2;
 db_iframe_rhpessoal.hide();
}


function js_mostradetalhes(chave){
  db_iframepessoal.jan.location.href = chave;
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}

// mostra os dados do cgm do contribuinte
function js_mostracgm() {
  
  db_iframepessoal.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=<?=@$z01_numcgm?>';
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}


// esta funcao é utilizada quando clicar na matricula após pesquisar
// a mesma
function js_mostrapessoal() {
  
  db_iframepessoal.jan.location.href = 'pes3_conspessoal002.php?regist=<?=@$matric?>';
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
  

function js_mostradetalhes(chave) {
  
  db_iframepessoal.jan.location.href = chave;
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}

function js_chama_link(ponto){

  //js_divCarregando("Aguarde, processando ...","processamento_calculo");
  var sBases = '';
  if ( document.formatu.bases ) {
    sBases = document.formatu.bases.value;
  }
  debitos.location.href = 'pes3_gerfinanc018.php?opcao='+ponto+'&numcgm='+$F('z01_numcgm')+'&matricula='+document.formatu.matricula.value+'&ano=<?=@$ano?>&mes=<?=@$mes?>&tbprev=<?=@$r01_tbprev?>&bases='+sBases+'&rub_bases='+document.formatu.rub_bases.value+'&rub_cond='+document.formatu.rub_cond.value+'&rub_formula='+document.formatu.rub_formula.value;
}

function js_chama_link2(ponto) {

  //js_divCarregando("Aguarde, processando ...","processamento_calculo_ponto");
  var sBases = '';
  if ( document.formatu.bases ) {
    sBases = document.formatu.bases.value;
  }

  debitos.location.href = 'pes3_consponto021.php?opcao='+ponto+'&numcgm='+$F('z01_numcgm')+'&matricula='+document.formatu.matricula.value+'&ano=<?=@$ano?>&mes=<?=@$mes?>&tbprev=<?=@$r01_tbprev?>&bases='+sBases+'&rub_bases='+document.formatu.rub_bases.value+'&rub_cond='+document.formatu.rub_cond.value+'&rub_formula='+document.formatu.rub_formula.value;
}
      
function js_MudaLink( oObjetoMenu ) {
  
  var iLeft = $('calculoFolha').style.position.left;
  var iTop  = $('calculoFolha').style.position.top;

  var aCalculos = $$('.box-calculos div');
  var aPontos   = $$('.box-pontos > div');

  for (var i = 0; i < aCalculos.length; i++) {
    aCalculos[i].style.backgroundColor = '#CCC';
  }

  for (var i = 0; i < aPontos.length; i++) {
    aPontos[i].style.backgroundColor = '#CCC';
  }

  oObjetoMenu.style.backgroundColor = '#E8EE6F';
  return;
}

function js_relatorio(){
  
  jan = window.open('pes3_gerfinanc017.php?opcao='+document.formatu.opcao.value+'&numcgm='+document.formatu.numcgm.value+'&matricula='+document.formatu.matricula.value+'&ano=<?=$ano?>&mes=<?=$mes?>&tbprev='+document.formatu.tbprev.value,'sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_Pesquisa(solicitacao) {
  var descricao_janela = 'CONSULTAS';
  js_OpenJanelaIframe('CurrentWindow.corpo','func_pesquisa','pes3_conspessoal002_detalhes.php?solicitacao='+solicitacao+'&parametro=<?=$r01_regist?>&ano=<?=$ano?>&mes=<?=$mes?>',descricao_janela,true,'20');
}

</script>
<?

$func_nome = new janela('db_iframepessoal','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=780;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

$fnome = new janela('fnome','');
$fnome ->posX=20;
$fnome ->posY=20;
$fnome ->largura=770;
$fnome ->altura=430;
$fnome ->titulo="Pesquisa";
$fnome ->iniciarVisivel = false;
$fnome ->mostrar();

if(isset($_GET['lConsulta'])){
  
  //$r01_regist = $_GET['iMatricula'];
 
  echo "<script>
         
         js_pesquisaregist(false);
         document.getElementById('pesquisar').click();
         
       </script>";
 
}

?>
