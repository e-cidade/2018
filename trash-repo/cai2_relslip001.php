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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("classes/db_orctiporec_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($_GET);

$clorctiporec=new cl_orctiporec;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo           = new rotulocampo;
$clrotulo->label('c60_codcon');
$clrotulo->label('c60_descr');
$clrotulo->label("k17_codigo");
$clrotulo->label("c50_descr");
$clrotulo->label("k17_hist");


$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  var var_obj     = document.getElementById('cgm').length;
  var cods        = "";
  var vir         = "";
  var selinstit   = document.form1.db_selinstit.value;
  var sel_instit  = new Number(selinstit);
  
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }
  
  for(y=0;y<var_obj;y++){
    var_if = parseInt(document.getElementById('cgm').options[y].value)
    cods += vir + var_if;
    vir = ",";
  }
  
  qry = 'codigos='+cods;
  qry+= '&situac='+document.form1.situacao.value;
  qry+= '&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value;
  qry+= '&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
  qry+= '&parametro='+document.form1.parametro.value;
  qry+= '&slip1='+document.form1.k17_codigo.value;
  qry+= '&recurso='+document.form1.o15_codigo.value;
  qry+= '&slip2='+document.form1.k17_codigo02.value;
  qry+= '&hist='+document.form1.k17_hist.value;
  qry+= '&db_selinstit='+selinstit;
  jan = window.open('cai2_relslip002.php?'+qry,'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<br>
  <table  align="center" cellspacing='0' border="0">
    <tr><td><fieldset><table>
    <form name="form1">
      <tr>
	<td nowrap title="<?=@$Tk17_codigo?>" align='left'>
	   <? db_ancora(@$Lk17_codigo,"js_pesquisak17_codigo(true);",$db_opcao);  ?>
	 </td><td>  
	   <? db_input('k17_codigo',16,$Ik17_codigo,true,'text',$db_opcao," onchange='js_pesquisak17_codigo(false);'")  ?>
		 </td><td>
           <? db_ancora("<b>até</b>","js_pesquisak17_codigo02(true);",$db_opcao);  ?>
			</td><td>		 
	   <? db_input('k17_codigo',16,$Ik17_codigo,true,'text',$db_opcao," onchange='js_pesquisak17_codigo02(false);'","k17_codigo02")?>
	</td>
      </tr>
      <tr>
        <td align=left>
	  <b>De:</b></td><td><?db_inputdata("data","","","","true","text",2);?>
		  </td><td>
          <b>Até:</b>
					</td>
					<td>
					<?db_inputdata("data1","","","","true","text",2);?>
	      </td>
      </tr>
      <tr>
    <td align="left"><? db_ancora(@$Lk17_hist,"js_pesquisac50_codhist(true);",$db_opcao);  ?></td>
    <td colspan="4"><?
                  db_input('k17_hist',10,$Ik17_hist,true,'text',$db_opcao," onchange='js_pesquisac50_codhist(false);'");
                  db_input('c50_descr',30,$Ic50_descr,true,'text',3);
            ?>
    </td>
      <tr>
      <tr>
        <td><strong>Recurso:</strong></td>
				<td colspan='5'>
	     <?
	        $dbwhere = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
	        $rs      = $clorctiporec->sql_record($clorctiporec->sql_query(null,"o15_codigo,o15_descr","o15_codigo",$dbwhere));
          db_selectrecord("o15_codigo",$rs,false,1,"","","","0");
	     ?>
	</td>
      </tr>

      <tr>
	<td align=left><strong>Situacao :&nbsp;&nbsp;</strong></td><td colspan=3>
	  <? 
	  $tipo_ordem = array("A"=>"Todas","1"=>"Não Autenticado","2"=>"Autenticado","3"=>"Estornado","4"=>"Cancelado");
	  db_select("situacao",$tipo_ordem,true,2); ?>
		<script>
		   document.getElementById('situacao').style.width='100%';
		</script>
        </td>
      </tr>
      
      <tr>
        <td></td>
        <td colspan=3>
         <?
           db_selinstit('',300,100);
         ?>
        </td>
      </tr>
      
			</table></fieldset></tr></td>
      <tr>
        <td colspan="6">
	  <table>
	  <?
	  $aux = new cl_arquivo_auxiliar;
	  $aux->cabecalho = "<strong>CGM</strong>";
	  $aux->codigo = "z01_numcgm";
	  $aux->descr  = "z01_nome";
	  $aux->isfuncnome = true;
	  $aux->nomeobjeto = 'cgm';
	  $aux->funcao_js = 'js_mostra';
	  $aux->funcao_js_hide = 'js_mostra1';
	  $aux->sql_exec  = "";
	  $aux->func_arquivo = "func_nome.php";
	  $aux->nomeiframe = "func_nome";
	  $aux->localjan = "";
	  $aux->db_opcao = 2;
	  $aux->tipo = 2;
	  $aux->top = 0;
	  $aux->linhas = 5;
	  $aux->vwhidth = 200;
	  $aux->funcao_gera_formulario();
	  ?>
	  </table>
        </td>
      </tr>
			<tr><td><fieldset><table>
		   <tr>
        <td align="right"> <strong>Opção de Seleção :<strong></td>
        <td align="left" colspan='4'>
          <?
          $xxx = array("S"=>"Somente Selecionados&nbsp;&nbsp;","N"=>"Menos os Selecionados&nbsp;&nbsp;");
          db_select('parametro',$xxx,true,2);
          ?>
		     <script>
       	   document.getElementById('parametro').style.width='100%';
        </script>
        </td>
				</tr>
		   </table>
   		</fieldset></td></tr>
				<tr>
        <td align="center" colspan='5'>
          <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
       </td>
      </tr>
    </form>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
//-----------------------------------------------------------
//---slip 01
function js_pesquisak17_codigo(mostra){

	sFuncao = 'func_slip.php?';
	<?php
	if (isset($modulo) and $modulo == 'pessoal') {
		?>
		sFuncao += 'modulo=pessoal&';
		<?php
	} 
	?>
	
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip',sFuncao+'funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
  }else{
    slip01 = new Number(document.form1.k17_codigo.value);
    if(slip01 != ""){
       js_OpenJanelaIframe('top.corpo','db_iframe_slip',sFuncao+'pesquisa_chave='+slip01+'&funcao_js=parent.js_mostraslip','Pesquisa',false);
    }else{
        document.form1.k17_codigo.value='';
    }   
  }
}
function js_mostraslip(chave,erro){
  if(erro==true){ 
    document.form1.k17_codigo.focus(); 
    document.form1.k17_codigo.value = ''; 
  }
}
function js_mostraslip1(chave1,chave2){
  document.form1.k17_codigo.value = chave1;
  db_iframe_slip.hide();
}
//-----------------------------------------------------------
//---slip 02
function js_pesquisak17_codigo02(mostra){

	sFuncao = 'func_slip.php?';
	<?php
	if (isset($modulo) and $modulo == 'pessoal') {
		?>
		sFuncao += 'modulo=pessoal&';
		<?php
	} 
	?>

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip',sFuncao+'funcao_js=parent.js_mostraslip12|k17_codigo','Pesquisa',true);
  }else{
    slip01 = new Number(document.form1.k17_codigo02.value);
    if(slip01 != ""){
       js_OpenJanelaIframe('top.corpo','db_iframe_slip',sFuncao+'pesquisa_chave='+slip01+'&funcao_js=parent.js_mostraslip2','Pesquisa',false);
    }else{
        document.form1.k17_codigo02.value='';
    }   
  }
}
function js_mostraslip2(chave,erro){
  if(erro==true){ 
    document.form1.k17_codigo02.focus(); 
    document.form1.k17_codigo02.value = ''; 
  }
}
function js_mostraslip12(chave1,chave2){
  document.form1.k17_codigo02.value = chave1;
  db_iframe_slip.hide();
}
function js_pesquisac50_codhist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conhist.php?funcao_js=parent.js_mostrahist1|c50_codhist|c50_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_conhist.php?pesquisa_chave='+document.form1.k17_hist.value+'&funcao_js=parent.js_mostrahist','Pesquisa',false);
  }
}
function js_mostrahist(chave,erro){
  document.form1.c50_descr.value = chave;
  if(erro==true){
    document.form1.k17_hist.focus();
    document.form1.k17_hist.value = '';
  }
}
function js_mostrahist1(chave1,chave2){
  document.form1.k17_hist.value = chave1;
  document.form1.c50_descr.value = chave2;
  db_iframe.hide();
}
</script>