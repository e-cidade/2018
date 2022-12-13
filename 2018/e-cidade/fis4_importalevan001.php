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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$cllevanta            = db_utils::getDao('levanta');
$clautolevanta        = db_utils::getDao('autolevanta');
$cllevvalor           = db_utils::getDao('levvalor');
$clissvarlevold       = db_utils::getDao('issvarlevold');
$cllevinscr           = db_utils::getDao('levinscr');
$cllevusu             = db_utils::getDao('levusu');
$clissvar             = db_utils::getDao('issvar');
$clissvarlev          = db_utils::getDao('issvarlev');
$clnumpref            = db_utils::getDao('numpref');
$clcadvenc            = db_utils::getDao('cadvenc');
$clparissqn           = db_utils::getDao('parissqn');
$cldb_confplan        = db_utils::getDao('db_confplan');
$clarrecad            = db_utils::getDao('arrecad');
$clarretipo           = db_utils::getDao('arretipo');
$clcadtipo            = db_utils::getDao('cadtipo');
$clarreinscr          = db_utils::getDao('arreinscr');
$clarrenumcgm         = db_utils::getDao('arrenumcgm');
$clparfiscal          = db_utils::getDao('parfiscal');
$oDaoInformacaoDebito = db_utils::getDao('informacaodebito');

$clrotulo = new rotulocampo;
$clrotulo->label("y60_codlev");
$clrotulo->label("z01_nome");
$clcadtipo->rotulo->label();

$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if ( isset($importar) ) {
  $result_fiscais=$cllevusu->sql_record($cllevusu->sql_query_file($y60_codlev));
  if ( $cllevusu->numrows!=0 ) {
    $passou=true;
    $sqlerro=false;
    db_inicio_transacao();
    $result = $cllevanta->sql_record($cllevanta->sql_query_file($y60_codlev));
    db_fieldsmemory($result,0);

    /**
     * Verifica se já foi  importado
     */
    if($y60_importado == 't'){

      $erro_msg = "Levantamento já exportado!";
      $sqlerro = true;
    }else{

      $cllevanta->y60_importado = 'true';
      $cllevanta->alterar($y60_codlev);
      $erro_msg= $cllevanta->erro_msg;
      if($cllevanta->erro_status==0){
        $sqlerro=true;
      }
    }
    //--------------------------------------------------

    //rotina que monta o array com o perido de exclusao...
    if ($sqlerro == false){
      $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as max_ano,y63_mes as max_mes","y63_ano desc,y63_mes desc ","y63_codlev=$y60_codlev"));
    if ($cllevvalor->numrows > 0) {
        $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as max_ano,y63_mes as max_mes","y63_ano desc,y63_mes desc ","y63_codlev=$y60_codlev"));
        db_fieldsmemory($result,0);

        $result = $cllevvalor->sql_record($cllevvalor->sql_query_file(null,"y63_ano as min_ano,y63_mes as min_mes","y63_ano asc,y63_mes asc ","y63_codlev=$y60_codlev"));
        db_fieldsmemory($result,0);

        $arr = array();
        $cont = 0;
        while(1==1){
          $arr[$cont][0] =  $min_ano;
          $arr[$cont][1] =  $min_mes;
          $cont++;
          $min_mes++;
         if($min_mes>12){
            $min_mes =1;
            if($min_ano != $max_ano){
              $min_ano++;
            }
          }
          if($min_ano == $max_ano){
            if($min_mes == $max_mes){
              $arr[$cont][0] =  $min_ano;
              $arr[$cont][1] =  $min_mes;
              break;
            }
          }
        }

      }else{

     db_msgbox('Levantamento sem valores. \\nVerifique.');
         $sqlerro=true;
     $passou = false;

      }
  }

    //ROTINA QUE EXCLUI DOS ISSVAR E ARRECAD
    if($sqlerro == false){

      $result_levinfo=$cllevanta->sql_record($cllevanta->sql_query_inf($y60_codlev));
      db_fieldsmemory($result_levinfo,0);
      if (isset($y62_inscr) && $y62_inscr!=""){

        $tab   = "arreinscr";
        $where = "arreinscr.k00_inscr=$y62_inscr";
      }else if (isset($y93_numcgm)&&$y93_numcgm!=""){

        $tab   = "arrenumcgm";
        $where = "arrenumcgm.k00_numcgm=$y93_numcgm";
      }

      $sql11="select arrecad.k00_numpre,k00_numpar,q05_codigo,q05_ano,q05_mes
                from issvar
                     left  join issvarlev on q18_codigo              = q05_codigo
                     left  join issarqsimplesregissvar on q68_issvar = q05_codigo
                     inner join $tab      on $tab.k00_numpre         = q05_numpre
                     inner join arrecad   on $tab.k00_numpre         = arrecad.k00_numpre
                                         and arrecad.k00_numpar      = q05_numpar
                     left  join issplannumpre          on q32_numpre = q05_numpre
               where $where
                 and q18_codigo is null
                 and q32_numpre is null
                 and q68_issvar is null";

      $result11  = db_query($sql11);
      $numrows11 = pg_numrows($result11);

      for($x=0; $x<$numrows11; $x++){

        db_fieldsmemory($result11,$x,true);
        $exclui = false;
        for($q=0; $q<count($arr); $q++ ){
          if($q05_ano==$arr[$q][0] &&$q05_mes==$arr[$q][1]  ){
            $exclui = true;
            break;
          }
        }
        if($exclui==false){
          continue;
        }
        if($sqlerro == false){

          $clarrecad->excluir_arrecad($k00_numpre,$k00_numpar);
          if($clarrecad->erro_status==0){

            $erro_msg = $clarrecad->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
        if($sqlerro == false){

          $clissvarlevold->y85_codissvar = $q05_codigo;
          $clissvarlevold->y85_codlev = $y60_codlev;
          $clissvarlevold->incluir();
          if($clissvarlevold->erro_status==0){

            $erro_msg = $clissvarlevold->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
        if($sqlerro == false){

          $clissvarlev->sql_record($clissvarlev->sql_query_file($q05_codigo,$y60_codlev));
          if($clissvarlev->numrows>0){

            $clissvarlev->q18_codigo = $q05_codigo;
            $clissvarlev->q18_codlev = $y60_codlev;
            $clissvarlev->excluir($q05_codigo,$y60_codlev);
            if($clissvarlev->erro_status==0){

              $erro_msg = $clissvarlev->erro_msg;
              $sqlerro  = true;
              break;
            }
          }
        }

        if($sqlerro == false){

          $clissvar->excluir_issvar($q05_codigo,$y60_codlev);
          if($clissvar->erro_status==0){

            $erro_msg = $clissvar->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
      }
    }
    //FINAL

    if($sqlerro == false){

      $sql=$cllevvalor->sql_query_inf(null,"sum(y63_saldo) as y63_saldo,
                                            y62_inscr,
                                            y93_numcgm,
                                            y63_mes,
                                            y63_ano,
                                            y63_aliquota,
                                            y63_sequencia,
                                            x.z01_numcgm,
                                            y63_dtvenc",
                                            "",
                                            "y63_codlev = $y60_codlev and y63_saldo > 0
                                             group by y63_ano,y63_mes,y63_aliquota,y62_inscr,y93_numcgm,y63_sequencia,x.z01_numcgm,y63_dtvenc
                                             order by y63_dtvenc");
      $resultado  = $cllevanta->sql_record($sql);
      $numrows = $cllevanta->numrows;
    }

    if($numrows == 0){

      $erro_msg = "Valores não disponíveis para este levantamento. Importação cancelada.";
      $sqlerro= true;
    }

    $parc   = 0;
    $numpre = $clnumpref->sql_numpre();
    for($i=0; $i<$numrows; $i++){

      if($sqlerro == true){
        break;
      }

      db_fieldsmemory($resultado,$i);
      $parc++;
      if($sqlerro == false){

        $clissvar->q05_numpre=$numpre;
        $clissvar->q05_numpar=$parc;
        $clissvar->q05_valor=$y63_saldo;
        $clissvar->q05_ano=$y63_ano;
        $clissvar->q05_mes=$y63_mes;
        $clissvar->q05_histor="Levantamento fiscal...";
        $clissvar->q05_aliq=$y63_aliquota;;

        $bruto = ($y63_saldo/$y63_aliquota)*100;
        $clissvar->q05_bruto="$bruto";
        $clissvar->q05_vlrinf="$bruto";
        $clissvar->incluir(null);
        $erro_msg = $clissvar->erro_msg;
        if($clissvar->erro_status==0){
          $sqlerro=true;
        }
        $codigo=$clissvar->q05_codigo;

      }
      //FINAL DA INCLUSÃO NO ARR*

      //INCLUI NA TABELA ISSVARLEV
      if($sqlerro==false){

        $clissvarlev->y60_codigo = $codigo;
        $clissvarlev->y60_codlev = $y60_codlev;
        $clissvarlev->incluir($codigo,$y60_codlev);
        $erro_msg = $clissvarlev->erro_msg;
        if($clissvarlev->erro_status==0){
          $sqlerro=true;
        }
      }

      if ($sqlerro==false) {

        /**
         * Rotina criada para guardar data do débito de issqn variável igual a data de realização do levantamento fiscal
         * Para essa função tambem existe uma trigger na tabela issvar, que a cada operação atualiza a data do débito com a data atual do sistema
         * Essa parte foi criada para atualizar os dados
         */
        $sWhere                = "k163_numpre = {$numpre} and k163_numpar = {$parc}";
        $sSqlInformacaoDebito  = $oDaoInformacaoDebito->sql_query_file(null, "*", null, $sWhere);
        $rsInformacaoDebito    = $oDaoInformacaoDebito->sql_record($sSqlInformacaoDebito);

        if ($oDaoInformacaoDebito->numrows > 0) {

          $oInformacaoDebito = db_utils::fieldsMemory($rsInformacaoDebito, 0);

          $oDaoInformacaoDebito->k163_sequencial = $oInformacaoDebito->k163_sequencial;
          $oDaoInformacaoDebito->k163_data       = $y60_data != '' ? $y60_data : date('Y-m-d', db_getsession('DB_datausu'));
          $oDaoInformacaoDebito->alterar($oInformacaoDebito->k163_sequencial);

        } else {

          $oDaoInformacaoDebito->k163_numpre = $numpre;
          $oDaoInformacaoDebito->k163_numpar = $parc;
          $oDaoInformacaoDebito->k163_data   = $y60_data != '' ? $y60_data : date('Y-m-d', db_getsession('DB_datausu'));
          $oDaoInformacaoDebito->incluir(null);
        }

        if ($oDaoInformacaoDebito->erro_status == '0') {
          $sqlerro = true;
        }
      }

      //inclui no arreinscr
      if(!$sqlerro && isset($y62_inscr) && $y62_inscr!=""){

        $clarreinscr->sql_record($clarreinscr->sql_query_file($numpre,$y62_inscr));
        if($clarreinscr->numrows==0){

          $clarreinscr->k00_numpre=$numpre;
          $clarreinscr->k00_inscr=$y62_inscr;
          $clarreinscr->k00_perc=100;
          $clarreinscr->incluir($numpre,$y62_inscr);

          if($clarreinscr->erro_status==0){

            $erro_msg=$clarreinscr->erro_msg;
            $sqlerro=true;
          }
        }
      }
      if(!$sqlerro && isset($y93_numcgm) && $y93_numcgm!=""){

        $clarrenumcgm->sql_record($clarrenumcgm->sql_query_file($y93_numcgm,$numpre));
        if($clarrenumcgm->numrows==0){

          $clarrenumcgm->k00_numpre=$numpre;
          $clarrenumcgm->k00_numcgm=$y93_numcgm;
          $clarrenumcgm->incluir($y93_numcgm,$numpre);
          if($clarrenumcgm->erro_status==0){

            $sqlerro=true;
            $erro_msg=$clarrenumcgm->erro_msg;
          }
        }
      }

      $iTipoDebitoAlterado = $_POST['k00_tipo'];
      //INCLUSÃO NO ARRECAD
      if(!$sqlerro){

        $result66 = $clparissqn->sql_record($clparissqn->sql_query_file());
        db_fieldsmemory($result66,0);

        /**
         * Altera pelo tipo de debito selecionado
         */
        $clarrecad->k00_tipo = $q60_tipo;
        if( !empty($iTipoDebitoAlterado) ){
          $clarrecad->k00_tipo = $iTipoDebitoAlterado;
        }

        $result_parfiscal=$clparfiscal->sql_record($clparfiscal->sql_query_file());
        db_fieldsmemory($result_parfiscal,0);

        if ($y60_espontaneo=='f'){
          $clarrecad->k00_receit = $y32_receit;
        }else{
          $clarrecad->k00_receit = $y32_receitexp;
        }

        $result77 = $clcadvenc->sql_record($clcadvenc->sql_query_file($q60_codvencvar,$y63_mes,"q82_venc,q82_hist"));
        db_fieldsmemory($result77,0);
        $clarrecad->k00_hist = $q82_hist;
        if($y63_ano == db_getsession("DB_anousu")){
          $clarrecad->k00_dtvenc="$y63_dtvenc";
        }else{

          $res = $cldb_confplan->sql_record($cldb_confplan->sql_query());
          if($cldb_confplan->numrows > 0){
            db_fieldsmemory($res,0);
          }else{

            db_msgbox("Tabela db_confplan vazia!");
            db_redireciona("iss1_issvar014.php");
            exit;
          }
          $qmes = $y63_mes;
          $qano = $y63_ano;
          $qmes += 1;
          if($qmes > 12){
            $qmes = 1;
            $qano += 1;
          }
          $clarrecad->k00_dtvenc="$y63_dtvenc";
        }

        $arr = split  ("-",$clarrecad->k00_dtvenc);

        $clarrecad->k00_numcgm=$z01_numcgm;
        $clarrecad->k00_dtoper= $arr[0]."-".$arr[1]."-01";
        $clarrecad->k00_valor=$y63_saldo;
        $clarrecad->k00_numpre=$numpre;
        $clarrecad->k00_numtot=1;
        $clarrecad->k00_numpar=$parc;
        $clarrecad->k00_numdig='0';
        $clarrecad->k00_tipojm='0';
        $clarrecad->incluir();

        $erro_msg = $clarrecad->erro_msg;
        if($clarrecad->erro_status==0){
          $sqlerro=true;
        }
      }
    }

    db_fim_transacao($sqlerro);
  }else{
    db_msgbox("Não existem fiscais cadastrados para o levantamento!!Exportação Cancelada!!");
    $passou=false;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

  <div class="container">
   <form name="form1" id="form1" method="post">
    <fieldset>
      <legend>Exportação</legend>

      <table>
        <tr>
          <td nowrap title="<?=@$Ty60_codlev?>">
            <?php
              db_ancora(@$Ly60_codlev,"js_lev(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('y60_codlev',10,$Iy60_codlev,true,'text',$db_opcao," onchange='js_lev(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3);
            ?>
          </td>
        </tr>
        <tr id="tipoDebito" style="display: none;">
          <td>
            <?php
              db_ancora("Tipo do Débito:", "js_pesquisaTipo(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input("k00_tipo",10,$Ik03_tipo,true,"text",$db_opcao,"onChange='js_pesquisaTipo(false);'");
              db_input("descrTipo",40,"",true,"text",3,"");
            ?>
          </td>
        </tr>
      </table>
      </fieldset>
      <input name="importar" type="submit"  onClick="return js_Exportar();" value="Exportar"/>
   </form>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

function js_pesquisaTipo(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe("top.corpo","db_iframe_arretipo_nova","func_arretipo_nova.php?k03_tipo=3,10,11&funcao_js=parent.js_preencheTipo|k00_tipo|k00_descr","Pesquisa",true);
  } else {
    js_OpenJanelaIframe("top.corpo","db_iframe_arretipo_nova","func_arretipo_nova.php?k03_tipo=3,10,11&funcao_js=parent.js_preencheTipo1&pesquisa_chave="+document.form1.k00_tipo.value,"Pesquisa",false);
  }
 }

 function js_preencheTipo(iChave,sChave){

    $('k00_tipo').value  = iChave;
    $('descrTipo').value = sChave;
    db_iframe_arretipo_nova.hide();
 }

 function js_preencheTipo1(sChave,lErro){

  $('descrTipo').value = sChave;

  if(lErro == true){

    $('k00_tipo').focus();
    $('k00_tipo').value = '';
  }
  db_iframe_arretipo_nova.hide();
}

function js_Exportar(){

  if(document.form1.y60_codlev.value == ''){

    alert("Campo Levantamento é preenchimento obrigatório.");
    return false;
  }
  return true;
}

function js_lev(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?funcao_js=parent.js_mostralev1|y60_codlev|DBtxtnome_origem','Pesquisa',true);
  }else{

    lev = document.form1.y60_codlev.value;
    if(lev != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?pesquisa_chave='+lev+'&funcao_js=parent.js_mostralev','Pesquisa',false);
    }else{
      document.form1.z01_nome.value='';
    }
  }

  document.form1.k00_tipo.value='';
  document.form1.descrTipo.value='';
}

function js_mostralev(chave,erro){

  if(erro==true){

    alert('Levantamento não encontrado para exportação.');
    document.form1.y60_codlev.value = "";
    document.form1.z01_nome.value   = ""
    document.form1.y60_codlev.focus();
  } else{
    document.form1.z01_nome.value = chave;
  }

  js_verificaVinculoLevantamentoAuto(document.form1.y60_codlev.value);
}

function js_mostralev1(chave1,chave2){

  document.form1.y60_codlev.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe.hide();

  js_verificaVinculoLevantamentoAuto(document.form1.y60_codlev.value);
}

function js_verificaVinculoLevantamentoAuto (y60_codlev) {
  if (y60_codlev != "") {
    js_OpenJanelaIframe('top.corpo','db_iframe','func_autolevanta.php?y117_levanta='+y60_codlev+'&lovrot=false&funcao_js=parent.js_mostraTipoDebito','Pesquisa',false);
  }
}

function js_mostraTipoDebito(mostra) {

  if (mostra) {
    document.getElementById("tipoDebito").style.display = "";
  } else {
    document.getElementById("tipoDebito").style.display = "none";
  }
}

</script>
<?php
if( isset($importar) && $passou == true ){

  db_msgbox($erro_msg);
  echo "<script>location.href='fis4_importalevan001.php';</script>";
}
?>