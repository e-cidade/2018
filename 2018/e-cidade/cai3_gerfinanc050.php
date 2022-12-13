<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);


require_once(modification("fpdf151/scpdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
if (!empty($Parcelamento)) {
  $sSqlNumpreParcelamento = "select v07_numpre as numpre from termo where v07_parcel = {$Parcelamento}";
  $rsNumpreParcelamento   = db_query($sSqlNumpreParcelamento);
  if (pg_num_rows($rsNumpreParcelamento) > 0 ) {
    $oNumpreParcelamento    = db_utils::fieldsMemory($rsNumpreParcelamento,0);
    $numpre = $oNumpreParcelamento->numpre;
  }
}

if(isset($db_datausu)){
  if(!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
    echo "Data para cálculo inválida. <br><br>";
    echo "Data deverá se superior a : ".date('Y-m-d',$HTTP_SESSION_VARS["DB_datausu"]);
    //   exit;
  }
  if(mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) <
  mktime(0,0,0,date('m',$HTTP_SESSION_VARS["DB_datausu"]),date('d',$HTTP_SESSION_VARS["DB_datausu"]),date('Y',$HTTP_SESSION_VARS["DB_datausu"])) ){
    echo "Data não permitida para cálculo. <br><br>";
    echo "Data deverá se superior a : ".date('Y-m-d',$HTTP_SESSION_VARS["DB_datausu"]);
    //   exit;
  }
  $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
}else{
  $DB_DATACALC = $HTTP_SESSION_VARS["DB_datausu"];
}

if(isset($HTTP_POST_VARS["inicial"])) {
  global $HTTP_SESSION_VARS;
  include(modification("cai3_gerfinanc003.php"));
  exit;
}
/**
 * Busca parâmetro partilha na tabela parjuridico
 */
$sSqlParametrosJuridico  = "select v19_partilha from parjuridico where v19_anousu = ".db_getsession('DB_anousu');
$rsSqlParametrosJuridico = db_query($sSqlParametrosJuridico);
$sPartilha               = db_utils::fieldsMemory($rsSqlParametrosJuridico,0)->v19_partilha ;
$lPartilha               = $sPartilha == 't' ? true : false;


$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v50_data");
$clrotulo->label("nome");
$clrotulo->label("v50_id_login");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v70_vara");
$clrotulo->label("v50_codmov");
$clrotulo->label("v53_descr");
$clrotulo->label("v54_descr");
$clrotulo->label("v70_codforo");
$clrotulo->label("v52_descr");

if (isset($matric)) {
  $tabela="  inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
  $campo='k00_matric';
  $valor=$matric;
  $sVariavel = "matric";
} else if (isset($inscr)) {
  $tabela="inner join arreinscr   on arreinscr.k00_numpre = arrecad.k00_numpre ";
  $campo='k00_inscr';
  $valor=$inscr;
  $sVariavel = "inscr";
} else if (isset($numcgm)) {
  $tabela = "  inner join arrenumcgm   on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
  $campo='arrenumcgm.k00_numcgm';
  $valor=$numcgm;
  $sVariavel = "numcgm";
} else {
  $tabela='';
  $campo='arrecad.k00_numpre ';
  $valor=$numpre;
}

$sql=" select distinct
              v50_inicial,
              v50_advog,
              v50_data,
              v50_id_login,
              nome,
              v50_codlocal,
              v54_descr,
              v70_vara,
              v53_descr,
              v50_codmov,
              v70_codforo,
              v52_descr,
              z01_nome as nomeadvog
         from arrecad
              inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
                                   and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                                   $tabela
              inner join arretipo             on arrecad.k00_tipo                = arretipo.k00_tipo
              inner join inicialnumpre        on inicialnumpre.v59_numpre        = arrecad.k00_numpre
              inner join inicial              on inicial.v50_inicial             = inicialnumpre.v59_inicial
              inner join cgm 	                on inicial.v50_advog               = cgm.z01_numcgm
              inner join db_usuarios          on db_usuarios.id_usuario          = inicial.v50_id_login
              inner join localiza             on inicial.v50_codlocal            = localiza.v54_codlocal
              inner join inicialmov           on inicial.v50_codmov              = inicialmov.v56_codmov
              inner join situacao             on inicialmov.v56_codsit           = situacao.v52_codsit
               left  join processoforoinicial on processoforoinicial.v71_inicial = inicial.v50_inicial
                                             and processoforoinicial.v71_anulado is false
               left  join processoforo        on processoforo.v70_sequencial     = processoforoinicial.v71_processoforo
               left  join vara                on vara.v53_codvara                = processoforo.v70_vara
         where $campo = $valor
           and k03_tipo = 18
           and v50_situacao = 1";

$result = db_query($sql) or die($sql);
$numrows= pg_numrows($result);

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/prototype.js"></script>
<script>
  function js_desabilitaBotoes() {
    parent.document.getElementById("geranotif").disabled      = true; //botao Gerar Notificacao
    parent.document.getElementById("btnSuspender").disabled   = true; //botao Suspender
    parent.document.getElementById("btparc").disabled         = true; //botao Parcelamento
    parent.document.getElementById("btcda").disabled          = true; //botao Certidao
    parent.document.getElementById("btcancela").disabled      = true; //botao Cancelamento
    parent.document.getElementById("btnSuspender").disabled   = true; //botao Parcelamento
    parent.document.getElementById("btcarne").disabled        = true; //botao emite carne
    parent.document.getElementById("emisscarne").disabled     = true; //botao emite carne
    parent.document.getElementById("btjust").disabled         = true; //botao justifica
    parent.document.getElementById("btnotifica").disabled     = true;

    if ( parent.document.getElementById("enviar").value == 'Recibo'){
      parent.document.getElementById("enviar").disabled     = true; //botao Recibo
    }
  }
</script>

<style type="text/css">
<!--
.borda {
  border-right-width: 1px;
  border-right-style: solid;
  border-right-color: #000000;
}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
  onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'; js_desabilitaBotoes();">
<center>
<form name="form1" id="form1" method="post" action="" target="reciboweb2">
<table id="tabdebitos">
<?
if($numrows>0){
  $sDesabilitaMarcarTodos = $lPartilha ? "display: none;" : "";
  echo "
  <tr bgcolor=\"#FFCC66\">   \n
  <th class=\"borda\" style=\"font-size:11px\" nowrap>O</td>\n
  <th title=\"Marca/Desmarca Todas\" class=\"borda\" style=\"font-size:12px\" nowrap><a id=\"marca\" href=\"\" style=\"color:black; $sDesabilitaMarcarTodos\" onclick=\"js_marca();return false\">M</a>
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv50_inicial' nowrap>Inicial</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='Lista Débitos' nowrap>Rec</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='Valor Total'   nowrap>Valor Total</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv50_advog'   nowrap>$RLv50_data</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv70_codforo' nowrap>Processo</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv50_advog'   nowrap>Advogado</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tnome' 	     nowrap>$RLnome</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv54_descr'   nowrap>Localizacao</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv53_descr'   nowrap>Vara</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv50_codmov'  nowrap>$RLv50_codmov</th>\n
  <th class=\"borda\" style=\"font-size:11px\" title='$Tv52_descr'   nowrap>$RLv52_descr</th>\n
  </tr>
  ";
  $valor_total = 0;
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
    //      if($i == 0){
    $sql="select distinct
                   arrecad.k00_numpre as numpres
              from inicial
                   inner join inicialcert on v51_inicial           =  v50_inicial
                   inner join certdiv     on v51_certidao          = v14_certid
                   inner join divida      on v14_coddiv            = v01_coddiv
                   inner join arrecad     on arrecad.k00_numpre    = divida.v01_numpre
                                         and arrecad.k00_numpar    = divida.v01_numpar
                   inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre
                                         and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where v50_inicial = {$v50_inicial}
             union all
            select distinct
                   arrecad.k00_numpre as numpres
              from inicial
                   inner join inicialcert on v51_inicial           =  v50_inicial
                   inner join certter     on v51_certidao          = v14_certid
                   inner join termo       on v14_parcel            = v07_parcel
                   inner join arrecad     on arrecad.k00_numpre    = termo.v07_numpre
                   inner join arreinstit  on arreinstit.k00_numpre = arrecad.k00_numpre
                                         and arreinstit.k00_instit = ".db_getsession('DB_instit')."
             where v50_inicial = {$v50_inicial}";

    $result1 = db_query($sql);
    $numrows1= pg_numrows($result1);
    $virgula = "";
    $numpre1 = "";
    $valor_geral  = 0;
    $valor_corr   = 0;
    $valor_juros  = 0;
    $valor_multa  = 0;

    for ($j = 0;$j < $numrows1;$j++) {
      db_fieldsmemory($result1,$j);
      $numpre1 .= $virgula.$numpres;
      $result_valinicial = debitos_numpre($numpres,0,0,$DB_DATACALC,date("Y", $DB_DATACALC),0,true,"", " and y.k00_hist <> 918");
      db_fieldsmemory($result_valinicial,0);
      $valor_geral += $total;
      $valor_corr  += $vlrcor;
      $valor_juros += $vlrjuros;
      $valor_multa += $vlrmulta;
      $virgula = ",";
    }

    $valor_total += $valor_geral;

    if ($i % 2 == 0) {
      $color = '#E4F471';
    } else {
      $color = '#EFE029';
    }

    $funcao           =  "js_inicial($v50_inicial);";
    $sDisabilitaCheck =  $lPartilha ? "js_desabilitaProcessoForo(this);" : "";
    $sLinhaRegistros  =  "  <tr bgcolor=\"$color\">   \n                                                                                       ";
    $sLinhaRegistros .=  "  <input type='hidden' name='valor$i'      value='$valor_geral' id='valor$i'>                                        ";
    $sLinhaRegistros .=  "  <input type='hidden' name='valorcorr$i'  value='$valor_corr' id='valorcorr$i'>                                     ";
    $sLinhaRegistros .=  "  <input type='hidden' name='valorjuros$i' value='$valor_juros' id='valorjuros$i'>                                   ";
    $sLinhaRegistros .=  "  <input type='hidden' name='valormulta$i' value='$valor_multa' id='valormulta$i'>                                   ";
    $sLinhaRegistros .=  "  <td class=\"borda\" style=\"font-size:11px\" nowrap><a href='#' onclick=\"$funcao return false;\">MI</a></td>\n  	 ";

    $sLinhaRegistros .=  "  <td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>                                               ";
    $sLinhaRegistros .=  "    <input style       =\"visibility:'visible'\"                                                                     ";
    $sLinhaRegistros .=  "           type        =\"checkbox\"                                                                                 ";

    if($lPartilha){
      $sLinhaRegistros .=  "         class       ='check_processoforo'                                                                         ";
      $sLinhaRegistros .=  "         processoforo='$v70_codforo'                                                                               ";
    }
    $sLinhaRegistros .=  "           value       =\"".$v50_inicial."\"                                                                         ";
    $sLinhaRegistros .=  "           ".($v70_codforo == ""?"disabled = \"disabled\"":"")."                                                     ";
    $sLinhaRegistros .=  "           id          =\"CHECK$i\"                                                                                  ";
    $sLinhaRegistros .=  "           name        =\"CHECK$i\"                                                                                  ";
    $sLinhaRegistros .=  "           id          ='$i'                                                                                         ";
    $sLinhaRegistros .=  "           onClick     ='js_vermarcados();js_soma();{$sDisabilitaCheck}'>                                            ";
    $sLinhaRegistros .=  "  <input type='hidden' id='_VALORES{$i}' value='{$v50_inicial}'>";
    $sLinhaRegistros .=  "  </td>                                                                                                              ";

    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv50_inicial' nowrap>$v50_inicial</td>\n                                     ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='Lista Receita' nowrap><a href='#' onclick='js_listaDebitos()'>DE</a></td>\n   ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='Valor Total'   nowrap> ".db_formatar($valor_geral,'f')."</td>\n               ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv50_advog'   nowrap>" . db_formatar($v50_data,"d") . "</td>\n               ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv70_codforo' nowrap>$v70_codforo</td>\n                                     ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv50_advog'   nowrap>$nomeadvog</td>\n                                       ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tnome' 	      nowrap>$nome</td>\n                                            ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv54_descr'   nowrap>$v54_descr</td>\n                                       ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv53_descr'   nowrap>$v53_descr</td>\n                                       ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv50_codmov'  nowrap>$v50_codmov</td>\n                                      ";
    $sLinhaRegistros .=  "  <td  style=\"font-size:11px\" title='$Tv52_descr'   nowrap>$v52_descr</td>\n                                       ";
    $sLinhaRegistros .=  "  </tr>  																																																						 ";
    echo $sLinhaRegistros;
  }

  echo "</table>";

  if (isset($matric)) {
    echo "<input type=\"hidden\" name=\"ver_matric\" value=\"".$matric."\">\n";
  } elseif (isset($inscr)) {
    echo "<input type=\"hidden\" name=\"ver_inscr\" value=\"".@$inscr."\">\n";
  }
  if (isset($numcgm)) {
    echo "<input type=\"hidden\" name=\"ver_numcgm\" value=\"".@$numcgm."\">\n";
  }

  echo "<input type=\"hidden\" name=\"totregistros\" value=\"".@$numrows."\">\n";

  // Configuração de parcelamento
  $sSqlTipo  = " select cadtipo.k03_tipo,k03_parcelamento,k03_permparc,k00_formemissao ";
  $sSqlTipo .= "   from arretipo                                                       ";
  $sSqlTipo .= "        inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo     ";
  $sSqlTipo .= "  where k00_tipo   = {$tipo}                                           ";
  $sSqlTipo .= "    and k00_instit = ".db_getsession('DB_instit')."                    ";

  $result_k03_tipo = db_query($sSqlTipo);

  db_fieldsmemory($result_k03_tipo, 0);

  echo "<input type=\"hidden\" name=\"tipo_debito\" id=\"tipo_debito\" value=\"".$tipo."\">\n";
  echo "<input type=\"hidden\" name=\"k03_tipo\" value=\"".$k03_tipo."\">\n";
  echo "<input type=\"hidden\" name=\"perfil_procuradoria\" value=\"".$perfil_procuradoria."\">\n";
  echo "<input type=\"hidden\" name=\"k03_parcelamento\" value=\"".$k03_parcelamento."\">\n";
  echo "<input type=\"hidden\" name=\"k03_permparc\" value=\"".$k03_permparc."\">\n";
  echo "<input type=\"hidden\" name=\"k00_formemissao\" id=\"k00_formemissao\" value=\"".$k00_formemissao."\">\n";

  echo "<input type='hidden' name='inicial' value='t'>";

  echo "</form>";

}else{
  ?>
  </table>
  </form>
  <tr>
    <td><small>Nenhum registro encontrado</small></td>
  </tr>
  <?
}
?>
</center>
</body>
</html>
<script>
/**
 * Função para desabilitar os checkbox do processo do foro
 */
function js_desabilitaProcessoForo(oElemento){

   var aChecks       = new Array();
   var sProcessoForo = oElemento.getAttribute('processoforo');

   $$('.check_processoforo').each(function(oCheckBox, iIndice){
     if(oCheckBox.checked){
       aChecks[iIndice] = new Array(oCheckBox.id);
     }
     if(oCheckBox.getAttribute('processoforo') != sProcessoForo) {
       oCheckBox.disabled = true;
     }
   });

   if (aChecks.length == 0){

     $$('.check_processoforo').each(function(oCheckBox){

       if(oCheckBox.getAttribute('processoforo') != ""){
         oCheckBox.disabled = false;
       }
     });
   }
}

function js_inicial(inicial){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe12','cai3_gerfinanc040.php?tabela=<?=$tabela?>&campo=<?=$campo?>&valor=<?=$valor?>&origem=inicial&inicial='+inicial+'&tipo=<?=$tipo?>','Pesquisa',true);
}

function js_listaDebitos(){
  document.location.href = 'cai3_gerfinanc002.php?<?=$sVariavel?>=<?=$valor?>&tipo=<?=$tipo?>&emrec=t&agnum=t&agpar=t&certidao=<?=$certidao?>&k03_tipo=<?=$k03_tipo?>&k00_tipo=<?=$k00_tipo?>&db_datausu=<?=$db_datausu?>';
}

function js_marca() {

  var ID = document.getElementById('marca');
  var BT = parent.document.getElementById('btmarca');

  var k03_tipo = '<?=$k03_tipo?>';
  var perfil_procuradoria = '<?=$perfil_procuradoria?>';

  var permissao_parcelamento = <?=db_permissaomenu(db_getsession("DB_anousu"),81,3415)?>;
  var permissao_cancelar     = <?=db_permissaomenu(db_getsession("DB_anousu"),81,4554)?>;
  var permissao_justif       = <?=db_permissaomenu(db_getsession("DB_anousu"),81,5024)?>;
  var permissao_suspender    = <?=db_permissaomenu(db_getsession("DB_anousu"),81,7653)?>;

  if(!ID) {
		return false;
	}
  var F = document.form1;
  if(ID.innerHTML == 'M') {
    var dis = true;
    ID.innerHTML = 'D';
    BT.value = "Desmarcar";
  } else {
    var dis = false;
    ID.innerHTML = 'M';
    BT.value = "Marcar";
  }
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox"){
      if (F.elements[i].disabled == false) {
        if(F.elements[i].style.visibility!="hidden")
        F.elements[i].checked = dis;
        if(F.elements[i].checked == true){
          parent.document.js_parc = js_a;
          //parent.document.getElementById('btparc').onclick = 'document.js_a()';
          if (parent.document.getElementById('btparc').disabled == true && permissao_parcelamento == true) {
            parent.document.getElementById("btparc").disabled = false; // botao parcelamento
          }
          if (parent.document.getElementById('btcancela').disabled == true && permissao_cancelar == true) {
            parent.document.getElementById("btcancela").disabled = false; // botao cancelamento
          }
          if (parent.document.getElementById('btjust').disabled == true && permissao_cancelar == true) {
            parent.document.getElementById("btjust").disabled = false; // botao justifica
          }
          if (parent.document.getElementById('btnSuspender').disabled == true && permissao_suspender == true) {
            parent.document.getElementById("btnSuspender").disabled = false; // botao suspender
          }
        }else{
          if (parent.document.getElementById('btparc').disabled == true && permissao_parcelamento == true) {
            parent.document.getElementById('btparc').disabled = true;
          }
          if (parent.document.getElementById('btcarne').disabled == true && permissao_cancelar == true) {
            parent.document.getElementById('btcancela').disabled = true;
          }
          if (parent.document.getElementById('btjust').disabled == true && permissao_justif == true) {
            parent.document.getElementById('btjust').disabled = true;
          }
          if (parent.document.getElementById('btnSuspender').disabled == true && permissao_suspender == true) {
            parent.document.getElementById('btnSuspender').disabled = true;
          }
        }
      }
    }
  }
  js_soma(this,2);
  if ( ( k03_tipo == 13 || k03_tipo == 18 ) && perfil_procuradoria == 0 ) {
    parent.document.getElementById("enviar").disabled = true;//botao emite recibo
    parent.document.getElementById("btparc").disabled = true;
    parent.document.getElementById("btcarne").disabled = true;
  }
}
function js_vermarcados(){

  var permissao_parcelamento = <?=db_permissaomenu(db_getsession("DB_anousu"),81,3415)?>;
  var permissao_cancelar     = <?=db_permissaomenu(db_getsession("DB_anousu"),81,4554)?>;
  var permissao_justif       = <?=db_permissaomenu(db_getsession("DB_anousu"),81,5024)?>;
  var permissao_suspender    = <?=db_permissaomenu(db_getsession("DB_anousu"),81,7653)?>;

  var k03_tipo = '<?=$k03_tipo?>';
  var perfil_procuradoria = '<?=$perfil_procuradoria?>';

  F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox"){

      if(F.elements[i].checked == true){

        parent.document.js_parc = js_a;
				//parent.document.getElementById('btparc').onclick = js_a();

        if (parent.document.getElementById('btparc').disabled == true && permissao_parcelamento == true) {
          parent.document.getElementById("btparc").disabled = false; // botao parcelamento
        }
        if (parent.document.getElementById('btcancela').disabled == true && permissao_cancelar == true) {
          parent.document.getElementById("btcancela").disabled = false; // botao cancelamento
        }
        if (parent.document.getElementById('btjust').disabled == true && permissao_justif == true) {
          parent.document.getElementById("btjust").disabled = false; // botao justifica
        }
        if (parent.document.getElementById('btnSuspender').disabled == true && permissao_suspender == true) {
          parent.document.getElementById("btnSuspender").disabled = true; // botao suspender
        }
        return true;
      }else{

        if (parent.document.getElementById('btparc').disabled == true && permissao_parcelamento == true) {
          parent.document.getElementById('btparc').disabled = true;
        }
        if (parent.document.getElementById('btcarne').disabled == true && permissao_cancelar == true) {
          parent.document.getElementById('btcancela').disabled = true;
        }
        if (parent.document.getElementById('btjust').disabled == true && permissao_justif == true) {
          parent.document.getElementById("btjust").disabled = true; // botao justifica
        }
        if (parent.document.getElementById('btnSuspender').disabled == true && permissao_suspender == true) {
          parent.document.getElementById("btnSuspender").disabled = true; // botao suspender
        }

      }
    }
  }

  if ( ( k03_tipo == 13 || k03_tipo == 18 ) && perfil_procuradoria == 0 ) {
    parent.document.getElementById("enviar").disabled = true;//botao emite recibo
    parent.document.getElementById("btparc").disabled = true;
    parent.document.getElementById("btcarne").disabled = true;
  }

}
function js_a(){
  inicial = "";
  deb = document.form1
  x = 0;
  vir = "";
  for(i=0;i<deb.length;i++) {
    if (deb.elements[i].type == "checkbox") {
      if (deb.elements[i].checked == true) {
        inicial += vir + deb.elements[i].value;
        vir = ","
      }
    }
  }
  if(x == 0){
    valtotalinicial = parent.document.getElementById('total2').innerHTML;
    document.form1.action = 'cai3_gerfinanc062.php?inicial='+inicial+'&valor='+valtotalinicial+'&valorcorr='+parent.document.getElementById('valorcorr2').innerHTML+'&juros='+parent.document.getElementById('juros2').innerHTML+'&multa='+parent.document.getElementById('multa2').innerHTML;
    document.form1.target = '_self';
    document.form1.submit();
  }
}

function js_soma1(obj,linha) {
  linha = ((typeof(linha)=="undefined") || (typeof(linha)=="object")?2:linha);
  var F = obj;
  var emrec = '<?=$emrec?>';
  var tab = document.getElementById('tabdebitos');
  if(emrec == 't'){
    parent.document.getElementById("enviar").disabled = false;//botao emite recibo
    if (document.form1.k03_permparc.value == 't') {
      parent.document.getElementById("btparc").disabled = false;//botao parcelamento
      parent.document.getElementById("btcda").disabled = false;//botao certidao
    }
    parent.document.getElementById("btcarne").disabled = true;//botao emite carne
    parent.document.getElementById("btnotifica").disabled = false;
//    parent.document.getElementById("btcarnep").disabled = false;//botao emite carne
  }
  var indi = js_parse_int(obj.id);

  var total     = parent.document.getElementById('valor'+linha).innerHTML;

  if(obj.checked == true){
    total += new Number(document.getElementById('total'+indi).value.replace(",",""));
  }else{
    total -= new Number(document.getElementById('total'+indi).value.replace(",",""));
  }
  parent.document.getElementById('total'+linha).innerHTML = total.toFixed(2);
  if(linha == 2) {
    valor = Number(parent.document.getElementById('valor1').innerHTML) - valor;
    valorcorr = Number(parent.document.getElementById('valorcorr1').innerHTML) - valorcorr;
    juros = Number(parent.document.getElementById('juros1').innerHTML) - juros;
    multa = Number(parent.document.getElementById('multa1').innerHTML) - multa;
    desconto = Number(parent.document.getElementById('desconto1').innerHTML) - desconto;
    total = Number(parent.document.getElementById('total1').innerHTML) - total;

    parent.document.getElementById('valor3').innerHTML = valor.toFixed(2);
    parent.document.getElementById('valorcorr3').innerHTML = valorcorr.toFixed(2);
    parent.document.getElementById('juros3').innerHTML = juros.toFixed(2);
    parent.document.getElementById('multa3').innerHTML = multa.toFixed(2);
    parent.document.getElementById('desconto3').innerHTML = desconto.toFixed(2);
    parent.document.getElementById('total3').innerHTML = total.toFixed(2);
  }

}

function js_soma(linha) {
  linha = ((typeof(linha)=="undefined") || (typeof(linha)=="object")?2:linha);
  var F = document.form1;
  var valor      = 0;
  var valorcorr  = 0;
  var valorjuros = 0;
  var valormulta = 0;
  var juros      = 0;
  var multa      = 0;
  var desconto   = 0;
  var total      = 0;
  var emrec      = '<?=$emrec?>';
  var k03_tipo = '<?=$k03_tipo?>';
  var perfil_procuradoria = '<?=$perfil_procuradoria?>';
  var permissao_parcelamento = <?=db_permissaomenu(db_getsession("DB_anousu"),81,3415)?>;
  var tab = document.getElementById('tabdebitos');
  if(emrec == 't'){
    parent.document.getElementById("enviar").disabled = false;//botao emite recibo
    parent.document.getElementById("btcarne").disabled = true;//botao carne
    parent.document.getElementById("btnotifica").disabled = false;
//    parent.document.getElementById("btcarnep").disabled = false; //botao emite carne
  }
  for(var i = 0;i < F.length;i++) {
    if((F.elements[i].type == "checkbox" || F.elements[i].type == "submit") && (F.elements[i].checked == true || linha == 1)) {
      var indi   = js_parse_int(F.elements[i].id);
      total      += new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr  += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      valorjuros += new Number(document.getElementById('valorjuros'+indi).value.replace(",",""));
      valormulta += new Number(document.getElementById('valormulta'+indi).value.replace(",",""));
    }
  }
  parent.document.getElementById('total'+linha).innerHTML     = total.toFixed(2);
  parent.document.getElementById('valorcorr'+linha).innerHTML = valorcorr.toFixed(2);
  parent.document.getElementById('juros'+linha).innerHTML     = valorjuros.toFixed(2);
  parent.document.getElementById('multa'+linha).innerHTML     = valormulta.toFixed(2);
  if(linha == 2) {
    total = Number(parent.document.getElementById('total1').innerHTML) - total;
  }

      var aux = 0;
      for(i = 0;i < F.length;i++) {
        if(F.elements[i].type == "checkbox")
        if(F.elements[i].checked == true)
        aux = 1;
      }
      if(aux == 0) {
        parent.document.getElementById("enviar").disabled       = true
        parent.document.getElementById("btparc").disabled       = true
        parent.document.getElementById("btcda").disabled        = true
        parent.document.getElementById("btcancela").disabled    = true
        parent.document.getElementById("btnSuspender").disabled = true
        parent.document.getElementById("btjust").disabled       = true
        parent.document.getElementById("btcarne").disabled      = true
        parent.document.getElementById("emisscarne").disabled   = true
        parent.document.getElementById("btnotifica").disabled   = true
        document.getElementById('marca').innerHTML              = "M";
        parent.document.getElementById('btmarca').value         = "Marcar Todas";
        parent.document.getElementById("geranotif").disabled    = true;
      }

  if ( ( k03_tipo == 13 || k03_tipo == 18 ) && perfil_procuradoria == 0 ) {
    parent.document.getElementById("enviar").disabled = true;//botao emite recibo
    parent.document.getElementById("btparc").disabled = true;
    parent.document.getElementById("btcarne").disabled = true;
  }

}
</script>