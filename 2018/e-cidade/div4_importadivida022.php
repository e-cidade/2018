<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_arretipo_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;
$clarretipo         = new cl_arretipo;
$clarrecad          = new cl_arrecad;
$clrotulo           = new rotulocampo;
$oPost              = db_utils::postMemory($_POST);
$oGet               = db_utils::postMemory($_GET);

$clarrecad->rotulo->label();
$clrotulo->label("k00_tipo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">

function js_habilita(){

  if(document.form1.tipor.value==0 || document.form1.tipdes.value==0){
    document.form1.pesquisa.disabled = true;
  }else{
    document.form1.pesquisa.disabled = false;
  }
}

function js_passainfo(valor){

  document.form1.controle.value = valor;
  document.form1.submit();
}

function js_submit_form(){

  js_gera_chaves();
  document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
</head>
<body class="body-default">
<div class="container">
<form name="form1" method="post">
<fieldset>
<legend>  Importação Parcial de Dívida </legend>
<table>
  <tr>
    <td nowrap title="<?=$Tk00_tipo?>">Tipo de origem:</td>
    <td nowrap>
      <select name="tipor" id="tipor" onchange='js_habilita();js_passainfo(this.value);' >

      <?php
         $inner_arrecad = "";
         $inner_tipo    = "";
         $inner         = "";
         $where         = "";
      	 $tab           = " arretipo ";
         if (isset($z01_numcgm)&&$z01_numcgm!=""){

      	   $inner_arrecad = " inner join arrecad    on arrecad.k00_numpre = arrenumcgm.k00_numpre
                         		  inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
      												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
      	   $inner_tipo = " inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
      	   $inner      = " inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
      	   $where      = " and arrenumcgm.k00_numcgm = $z01_numcgm ";
      	   $tab        = " arrenumcgm  ";
      	 }else if (isset($j01_matric)&&$j01_matric!=""){

      	   $inner_arrecad = " inner join arrecad    on arrecad.k00_numpre = arrematric.k00_numpre
                         		  inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
      												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
      	   $inner_tipo = " inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
           $inner      = " inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
      	   $where      = " and arrematric.k00_matric = $j01_matric ";
      	   $tab        = " arrematric  ";
      	 }else if (isset($q02_inscr)&&$q02_inscr!=""){

      	   $inner_arrecad = " inner join arrecad    on arrecad.k00_numpre = arreinscr.k00_numpre
                              inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre
      												                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
      	   $inner_tipo = " inner join arretipo on arretipo.k00_tipo = arrecad.k00_tipo ";
           $inner      = " inner join arreinscr on arreinscr.k00_numpre = arrecad.k00_numpre ";
      	   $where      = " and arreinscr.k00_inscr = $q02_inscr ";
      	   $tab        = " arreinscr  ";
      	 }
         $campos = " distinct arretipo.k00_tipo,k00_descr ";
         $sql    = "select $campos
      	              from $tab
                        	 $inner_arrecad
                           $inner_tipo
                           inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo
                     where cadtipo.k03_parcano is true and arrecad.k00_valor > 0
      							       $where
                   	 order by arretipo.k00_tipo";

            $result = $clarretipo->sql_record($sql);
            $numrows=$clarretipo->numrows;
            if ($numrows==0){
               db_msgbox('Não existem debitos a serem importados');
            }
            $entra=false;
      	 if ($numrows>1){
           echo "<option value=\"0\" >Escolha origem</option>\n";
      	 }else{
      	   $entra=true;
      	 }
      	 for($i=0;$i<$numrows;$i++){

      	   db_fieldsmemory($result,$i);
      	   if ($entra==true){
      	     $controle=$k00_tipo;
      	   }
      	   echo "<option value=\"$k00_tipo\" >$k00_descr</option>\n";
      	 }
      ?>
      </select>
    </td>
  </tr>
    <?php
    if (isset($tipor)&&$tipor!=""){
      echo "<script>document.form1.tipor.value=$tipor;</script>";
    }
    ?>
  <tr>
    <td nowrap title="Tipo de destino para novos dados que serao gerados">Tipo de destino:</td>
    <td nowrap>
      <select name="tipdes" id="tipdes" onchange='js_habilita();' >
        <option value="0">Escolha destino</option>
      <?php
         $sql1 = "select distinct
				                 arretipo.k00_tipo,
      	                 k00_descr
         	          from arretipo
	                 where k03_tipo = 5
									   and k00_instit = ".db_getsession('DB_instit');
         $result1 = $clarretipo->sql_record($sql1);
         $numrows1=$clarretipo->numrows;
	       for($i=0;$i<$numrows1;$i++){

	         db_fieldsmemory($result1,$i,true);
	         echo "<option value=\"$k00_tipo\" >$k00_descr</option>";
	       }

      ?>
      </select>
    </td>
  </tr>
    <?php
      db_input('controle',10,'',true,'hidden',3);
    ?>
  <tr>

    <td colspan="2">
    <?php
    if (isset($where)&&$where!=""&&isset($controle)&&$controle!=""){
         $campos = " distinct arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit,k02_descr,arrecad.k00_dtvenc ";

         $sql_numpres = "select $campos
                           from $tab
                          			$inner_arrecad
                          			$inner_tipo
                                inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo
                          			inner join tabrec on tabrec.k02_codigo = arrecad.k00_receit
                          where cadtipo.k03_parcano is true
													  and arrecad.k00_tipo=$controle and k00_valor > 0 $where
                      	  order by arrecad.k00_numpre,arrecad.k00_numpar";

           $cliframe_seleciona->campos        = "k00_numpre,k00_numpar,k00_receit,k02_descr,k00_dtvenc";
           $cliframe_seleciona->legenda       = "Numpre's";
           $cliframe_seleciona->sql           = $sql_numpres;
           $cliframe_seleciona->iframe_height = "300";
           $cliframe_seleciona->iframe_width  = "550";
           $cliframe_seleciona->iframe_nome   = "numpres";
           $cliframe_seleciona->chaves        = "k00_numpre,k00_numpar,k00_receit";
           $cliframe_seleciona->iframe_seleciona(1);
    }
    ?>
    </td>
  </tr>

				<tr>
					<td nowrap title="Processos registrado no sistema?">
						<strong>Processo do Sistema:</strong>
					</td>
					<td nowrap>
						<?php
						  $lProcessoSistema = true;
							db_select('lProcessoSistema', array(true=>'SIM', false=>'NÃO'), true, 1, "onchange='js_processoSistema()'")
						?>
					</td>
				</tr>

				<tr id="processoSistema">
					<td nowrap title="<?=@$Tp58_codproc?>">
					  <strong>
						<?php
							db_ancora('Processo:', 'js_pesquisaProcesso(true)', 1);
						?>
					  </strong>
					</td>
					<td nowrap>
						<?php
	 					  db_input('v01_processo', 10, false, true, 'text', 1, 'onchange="js_pesquisaProcesso(false)"');
  						db_input('p58_requer', 40, false, true, 'text', 3);
						?>
					</td>
				</tr>

				<tr id="processoExterno1" style="display: none;">
					<td nowrap title="Número do processo">Processo:</td>
					<td nowrap>
						<?php
						  db_input('v01_processoExterno', 10, "", true, 'text', 1, null, null, null, "background-color: rgb(230, 228, 241);");
						?>
					</td>
				</tr>

				<tr id="processoExterno2" style="display: none;">
					<td nowrap title="Titular do processo">Titular do Processo:</td>
					<td nowrap>
					<?php
						db_input('v01_titular', 54, 'false', true, 'text', 1);
					?>
					</td>
				</tr>

				<tr id="processoExterno3" style="display: none;">
					<td nowrap title="Data do processo">Data do Processo:</td>
					<td nowrap>
						<?php
						db_inputdata('v01_dtprocesso', @$v01_dtprocesso_dia, @$v01_dtprocesso_mes, @$v01_dtprocesso_ano, true, 'text', 1);
						?>
					</td>
				</tr>
  		      </table>
			  <?php
			    db_input('z01_numcgm',10,'',true,'hidden',3);
			    db_input('j01_matric',10,'',true,'hidden',3);
			    db_input('q02_inscr',10,'',true,'hidden',3);
			    db_input('inner',10,'',true,'hidden',3);
			    db_input('where',10,'',true,'hidden',3);
			  ?>
			</fieldset>
  		<input name="pesquisa" type="button"  disabled  value="Pesquisa" onclick="js_submit_form();" style="margin-top: 20px;">
	  </form>
</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

function js_mandadados(tipor,tipdes, oProcessos){

    //as variáveis de WHERE e INNER são passadas via sessão pois em alguns casos estavam ultrapassando o limite
    js_OpenJanelaIframe('top.corpo','db_iframe','div4_importadivida033.php?k00_tipo_or='+tipor+'&k00_tipo_des='+tipdes,'Pesquisa',true);
    jan.moveTo(0,0);
}

/*
 * FUNCOES DE PESQUISA
 */
function js_pesquisaProcesso(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?funcao_js=parent.js_mostraProcesso|p58_codproc|z01_nome','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_matric', 'func_protprocesso.php?pesquisa_chave='+document.form1.v01_processo.value+'&funcao_js=parent.js_mostraProcessoHidden','Pesquisa',false);
  }

}
function js_mostraProcesso(iCodProcesso, sRequerente) {

  document.form1.v01_processo.value = iCodProcesso;
  document.form1.p58_requer.value   = sRequerente;
  db_iframe_matric.hide();
}

function js_mostraProcessoHidden(iCodProcesso, sNome, lErro) {

  if(lErro == true) {

    document.form1.v01_processo.value = "";
    document.form1.p58_requer.value  = sNome;
  } else {
    document.form1.p58_requer.value  = sNome;
  }
}

/**
 * Funcao que trata se o processo é externo ou interno
 */
function js_processoSistema() {

var lProcessoSistema = $F('lProcessoSistema');

  if (lProcessoSistema == 1) {

    document.getElementById('processoExterno1').style.display = 'none';
    document.getElementById('processoExterno2').style.display = 'none';
    document.getElementById('processoExterno3').style.display = 'none';
    document.getElementById('processoSistema').style.display  = '';
    $('v01_processo').value = "";
    $('p58_requer').value = "";
  }	else {

    document.getElementById('processoExterno1').style.display = '';
    document.getElementById('processoExterno2').style.display = '';
    document.getElementById('processoExterno3').style.display = '';
    document.getElementById('processoSistema').style.display  = 'none';
  }
}
</script>
<?php
if ( isset($tipor) && isset($tipdes) &&
     isset($inner) && isset($where)  &&
     isset($chaves) ) {

  $numpre  = "";
  $numpar  = "";
  $receita = "";
  $or      = "and ( ";
  $info    = split('#',$chaves);
  for($w=0;$w<count($info);$w++){

    $dados   = split('-',$info[$w]);
    $numpre  = $dados[0];
    $numpar  = $dados[1];
    $receita = $dados[2];
    $where  .= " $or  (arrecad.k00_numpre=$numpre and arrecad.k00_numpar=$numpar and arrecad.k00_receit=$receita)";
    $or     = "or";
  }

  $where .= " ) ";
  $_SESSION["where_divida"] = $where;
  $_SESSION["inner_divida"] = $inner;

  if ((int)$oPost->lProcessoSistema == 1) {

    $lProcessoSistema = $oPost->lProcessoSistema;
    $iProcesso        = $oPost->v01_processo;
    $sTitular         = "";
    $dDataProcesso    = "";
  } else {

    $lProcessoSistema = $oPost->lProcessoSistema;
    $iProcesso        = $oPost->v01_processoExterno;
    $sTitular         = $oPost->v01_titular;
    $dDataProcesso    = implode("-", array_reverse(explode("/", $oPost->v01_dtprocesso)));
  }

  $oProcesso = new stdClass();
  $oProcesso->lProcessoSistema =  $lProcessoSistema;
  $oProcesso->iProcesso        =  $iProcesso;
  $oProcesso->sTitular         =  $sTitular;
  $oProcesso->dDataProcesso    =  $dDataProcesso;

  db_putsession("oDadosProcesso", $oProcesso);

  echo "<script>js_mandadados($tipor,$tipdes)</script>";
}
?>
<script type="text/javascript">

$("v01_processo").addClassName("field-size2");
$("p58_requer").addClassName("field-size9");
$("lProcessoSistema").setAttribute("rel","ignore-css");
$("lProcessoSistema").addClassName("field-size2");
$("v01_processoExterno").addClassName("field-size2");
$("v01_titular").addClassName("field-size9");
$("v01_dtprocesso").addClassName("field-size2");
$("tipdes").setAttribute("rel","ignore-css");
$("tipdes").addClassName("field-size5");
$("tipor").setAttribute("rel","ignore-css");
$("tipor").addClassName("field-size5");
</script>