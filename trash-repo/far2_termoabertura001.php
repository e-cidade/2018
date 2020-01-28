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
include("classes/db_far_farmacia_classe.php");
include("classes/db_far_modelolivro_classe.php");
$clfar_famacia = new cl_far_farmacia;
$clfar_modelolivro = new cl_far_modelolivro;
$clrotulo = new rotulocampo;
$clrotulo->label("fa13_i_codigo");
$clrotulo->label("fa13_i_departamento");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br><br>
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
     <fieldset style="width:95%"><legend><b>Termo Abertura/Encerramento</b></legend>
    <form name='form1'>
    <table>     
     <tr>
      <td><?db_ancora(@$Lfa13_i_departamento,"js_pesquisafa13_i_departamento(true);",1);?></td>
      <td>
       <?db_input('fa13_i_departamento',10,@$Ifa13_i_departamento,true,'text',1," onchange='js_pesquisafa13_i_departamento(false);'")?>
       <?db_input('descrdepto',50,@$Idescrdepto,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
        <td>
       <b>Livro:</b>
    </td>
    <td> 
       <?               
        $result_modlivro = $clfar_modelolivro->sql_record($clfar_modelolivro->sql_query("","fa16_i_codigo,fa16_c_livro","fa16_c_livro"));
        db_selectrecord("livro",$result_modlivro,"","","","","","  ","",1);
          ?>
    </td>
        </tr>
     <tr>
       <td colspan='6' align='center' >
          <input name="termo" type="submit" id="termo" value="Imprimir Termo" onclick='js_termo();' >
       </td>
     </tr>
    </table>
    </form>    
  </fieldset>
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
function js_pesquisafa13_i_departamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.fa13_i_departamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.fa13_i_departamento.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.fa13_i_departamento.focus(); 
    document.form1.fa13_i_departamento.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.fa13_i_departamento.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_termo(){    
    //parametros = "?livro="+document.form1.livro.value+;
	//parametros += "&fa13_i_departamento="+document.form1.fa13_i_departamento.value+"&descrdepto="+document.form1.descrdepto.value;
    //jan = window.open('far2_termoabertura002.php'+parametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan = window.open("far2_termoabertura002.php?livro="+document.form1.livro.value+"&fa13_i_departamento="+document.form1.fa13_i_departamento.value+"&descrdepto="+document.form1.descrdepto.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);	
} 
</script>