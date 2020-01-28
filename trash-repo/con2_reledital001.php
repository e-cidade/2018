<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
$clprojmelhorias = new cl_projmelhorias;
$clprojmelhorias->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("d01_codedi");
$clrotulo->label("d01_descr");
$clrotulo->label("d04_forma");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_relatorio1() {

  var formaCalculo = document.form1.d04_forma.value;
  var tipoCusto    = document.form1.tipocusto.value;

  if ( formaCalculo != '3' ) {
    jan = window.open('con2_reledital002.php?edital='+document.form1.d01_codedi.value+'&d04_forma='+document.form1.d04_forma.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  } else {  
    jan = window.open('con2_reledital_testadaproporcional002.php?tipocusto='+tipoCusto+'&edital='+document.form1.d01_codedi.value+'&d04_forma='+document.form1.d04_forma.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
  jan.moveTo(0,0);
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">

      <tr>
      <br>
        <td nowrap title="<?=@$Td01_codedi?>">
        <?
          db_ancora(@$Ld01_codedi,"js_edi(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('d01_codedi',6,$Id01_codedi,true,'text',$db_opcao," onchange='js_edi(false);'");
      db_input('d01_descr',40,$Id01_descr,true,'text',3);
         ?>
        </td>			
      </tr>
			
      <tr>
						<td nowrap title="<?=@$Td04_forma?>">
							<b>Forma de Cálculo :</b>
						</td>
						<td nowrap> 
							<?

							$x = array('1'=>'utilizando valor para calculo','2'=>'utilizando valor para valorizacao', '3'=>'testada proporcional');
							db_select('d04_forma',$x,true,$db_opcao,"");
							?>
						</td>

      </tr>
      <tr>
						<td nowrap title="">
              <b>Mostrar custo individual : </b>
						</td>
						<td nowrap> 
							<?

							$y = array('1'=>'C.Melhoria','2'=>'Custo Obra', '3'=>'Ambos');
							db_select('tipocusto',$y,true,$db_opcao,"");
							?>
						</td>

      </tr>


			
            <tr>
              <td colspan="2" align="center"  height="25" nowrap><input name="boletim" type="button" id="boletim" onClick="js_relatorio1()" value="Gerar relatório">
	      </td>
              <td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_edi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalvalimovel.php?funcao_js=parent.js_mostracontri1|d01_codedi|d01_descr|d04_forma','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalvalimovel.php?pesquisa_chave='+document.form1.d01_codedi.value+'&funcao_js=parent.js_mostracontri1|d01_codedi|d01_descr|d04_forma','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){


  if(erro==true){ 
    alert('Edital inválido.');  
    document.form1.d01_codedi.value=""; 
    document.form1.d01_codedi.focus(); 
  } else{
    document.form1.d01_descr.value = chave;
  } 
}
function js_mostracontri1(chave1,chave2,forma){  

  document.form1.d01_codedi.value = chave1;
  document.form1.d01_descr.value = chave2;
  document.form1.d04_forma.value = forma;
  db_iframe.hide();

}
</script>