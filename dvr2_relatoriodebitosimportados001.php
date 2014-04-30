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
require_once("libs/db_app.utils.php");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("dv05_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("k00_numpre");
$clrotulo->label("q02_inscr");

$oDaoDBConfig = db_utils::getDao('db_config');
$oParametros  = $oDaoDBConfig->getParametrosInstituicao(db_getsession("DB_instit"));

/** permite funcionalidade somente se prefeitura ou agua **/
if ( !($oParametros->prefeitura == 't'  || $oParametros->db21_usasisagua == 't') ) {
	$db_opcao = 33;
} else {
	$db_opcao = 1;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php 
  db_app::load('scripts.js, prototype.js');
  db_app::load('estilos.css');
?>
<style>
.selectWidth {
  width: 92px;
}
</style>
<script>

function js_relatorio() {

  var cgm         = document.form1.dv05_numcgm.value;
  var matric      = document.form1.j01_matric.value;
  var numpre      = document.form1.k00_numpre.value;
  var iInscricao  = document.form1.q02_inscr.value;
  var dataini     = '';
  if ( document.form1.dataini_ano.value && document.form1.dataini_mes.value && document.form1.dataini_dia.value ) {
	  dataini     = document.form1.dataini_ano.value+'-'+document.form1.dataini_mes.value+'-'+document.form1.dataini_dia.value;
	}  
  var datafim     = '';
  if ( document.form1.datafim_ano.value && document.form1.datafim_mes.value && document.form1.datafim_dia.value ) {
	  datafim     = document.form1.datafim_ano.value+'-'+document.form1.datafim_mes.value+'-'+document.form1.datafim_dia.value;
	}  
  var sTipo       = document.form1.sTipo.value;
  var sOrigem     = document.form1.sOrigem.value;
  var sFormato    = document.form1.sFormato.value;

  var sQuery      =  'iCgm='+cgm+
                     '&iMatricula='+matric+
                     '&iInscricao='+iInscricao+
                     '&iNumpre='+numpre+
                     '&dDataInicial='+dataini+
                     '&dDataFinal='+datafim+
                     '&sTipo='+sTipo+
                     '&sOrigem='+sOrigem+
                     '&sFormato='+sFormato;
  
  oJanela = window.open('dvr2_relatoriodebitosimportados002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJanela.moveTo(0,0);

}  

</script>
</head>
<body bgcolor=#CCCCCC>
  <form class="container" name="form1" id="form1">

        <?php if ( $db_opcao == 1 ) { ?>
          <fieldset>
            <legend>Relatório de Débitos Importados para Diversos:</legend>
            <table class="form-container">
              <tr>
                <td nowrap><?
                db_ancora($Ldv05_numcgm,' js_cgm(true); ',1);
                ?>
                </td>
                <td nowrap><?
                db_input('dv05_numcgm',10,$Idv05_numcgm,true,'text',1,"onchange='js_cgm(false)';jsLimpa(this.id);","dv05_numcgm");
                db_input('z01_nome',50,0,true,'text',3,"","z01_nomecgm");
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap><?
                db_ancora($Lj01_matric,' js_matri(true); ',1);
                ?>
                </td>
                <td nowrap><?
                db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false);jsLimpa(this.id);'");
                db_input('z01_nome',50,0,true,'text',3,"","z01_nomematri");
                ?>
                </td>
              </tr>
              
              <tr> 
      			    <td title="<?=$Tq02_inscr?>" align="right"> 
      			    <?php
      			    	db_ancora($Lq02_inscr, 'js_pesquisaInscricao(true);', 4);
      			    ?>
      			    </td>
      			    <td>
      			    	<?php 
      			    	  db_input('q02_inscr', 10, $Iq02_inscr, true, 'text', 1, "onchange='js_pesquisaInscricao(false)'");
      			    		db_input("z01_nome"  , 40, $Iz01_nome  , true, 'text', 3);
      			    	?>			    
      			    </td>
      			  </tr>
      			  
              <tr>
                <td title="<?=$Tk00_numpre?>"><?=$Lk00_numpre?></td>
                <td><?
                db_input('k00_numpre',10,$Ik00_numpre,true,'text',1,"onchange='jsLimpa(this.id);'");
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="À partir de qual data"><strong>Data inicial:</strong>
                </td>
                <td nowrap><?
                db_inputdata('dataini',"","","",true,'text',1)
                ?> <strong>até</strong> <?
                db_inputdata('datafim',"","","",true,'text',1)
                ?>
                </td>
              </tr>
              <tr>
                <td><strong>Tipo:</strong>
                </td>
                <td><select name="sTipo" id="sTipo" class="selectWidth" onchange="js_validaFormato();">
                    <option value="Sintetico">Sintético</option>
                    <option value="Analitico">Analítico</option>
                </select>
                </td>
              </tr>
              <tr style ="display:none;">
                <td><strong>Origem:</strong>
                </td>
                <td><select name="sOrigem" id="sOrigem" class="selectWidth">
                    <?php if ( $oParametros->prefeitura == 't' ) { ?>
                    <option value="IPTU">IPTU</option>
                    <?php } ?>
                    <?php if ( $oParametros->db21_usasisagua == 't' ) { ?>
                    <option value="AGUA">Água</option>
                    <?php } ?>
                </select>
                </td>
              </tr>
              <tr>
                <td><strong>Formato:</strong>
                </td>
                <td><select name="sFormato" id="sFormato" class="selectWidth">
                    <option value="PDF">PDF</option>
                    <option value="CSV">CSV</option>
                </select>
                </td>
            </table>
            </fieldset>
            <input type="button" name="gerar" value="Gerar Relatório" onclick="js_relatorio();">
        <?php } else { ?>
        <fieldset>
          <legend>Relatório de Débitos Importados para Diversos:</legend>
          <table class="form-container">
            <tr>
              <td align="center"><br /> <span>Esta rotina não está disponível para esta Instituição.</span>
              </td>
            </tr>
          </table>
        </fieldset>
        <?php } ?>
   
  </form>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>
function jsLimpa(iIdCampo) {
	  if ( iIdCampo != 'dv05_numcgm' ) {
      document.form1.dv05_numcgm.value = '';
      document.form1.z01_nomecgm.value = '';
	  }       
    if ( iIdCampo != 'j01_matric' ) { 
      document.form1.j01_matric.value = ''; 
      document.form1.z01_nomematri.value = '';
    } 
    if ( iIdCampo != 'k00_numpre' ) { 
    	document.form1.k00_numpre.value = '';
    }
}
function js_cgm(mostra){
  var cgm = document.form1.dv05_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    if (cgm == '') {
      document.form1.z01_nomecgm.value = ''; 
      return false;      
    }
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.dv05_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe.hide();
}
function js_mostracgm1(erro,chave){
	jsLimpa(document.form1.dv05_numcgm.id);
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.dv05_numcgm.focus(); 
    document.form1.dv05_numcgm.value = '';
  }
}
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome','Pesquisa',true);
  }else{
    if (matri == '') {
      document.form1.z01_nomematri.value = ''; 
      return false;
    }
    js_OpenJanelaIframe('top.corpo','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe.hide();
}
function js_mostramatri1(chave,erro){
	jsLimpa(document.form1.j01_matric.id);
	document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}

function js_validaFormato() {
	
	if ( document.form1.sTipo.value == 'Sintetico' ) {

		document.form1.sFormato.innerHTML = '<option value="PDF">PDF</option>'+
		                                    '<option value="CSV">CSV</option>';
		
	} else if ( document.form1.sTipo.value == 'Analitico' ) {
		
		document.form1.sFormato.innerHTML = '<option value="PDF">PDF</option>'; 
		
	}	
	
}
function js_detectaarquivo(sNomeArquivo) {
	oJanela.close();
	  sLista = sNomeArquivo+"#Download arquivo";
	  js_montarlista(sLista,"form1");
	}

function js_pesquisaInscricao(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostraInscricao|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostraInscricaoHide','Pesquisa',false);
  }
}

function js_mostraInscricao(iInscricao, sNome) {

	$('q02_inscr').value = iInscricao;
	$('z01_nome').value  = sNome;

	db_iframe.hide();
	
}

function js_mostraInscricaoHide(sNome, lErro) {

	$('z01_nome').value = sNome;
	
	if (lErro == true) {
		$('q02_inscr').value = '';
	}	
	
}		
</script>
<script>

$("dv05_numcgm").addClassName("field-size2");
$("z01_nomecgm").addClassName("field-size7");
$("j01_matric").addClassName("field-size2");
$("z01_nomematri").addClassName("field-size7");
$("q02_inscr").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("k00_numpre").addClassName("field-size2");
$("dataini").addClassName("field-size2");
$("datafim").addClassName("field-size2");

</script>