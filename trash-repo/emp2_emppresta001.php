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
require("libs/db_liborcamento.php");
include("classes/db_empempenho_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_pcmater_classe.php");
include("classes/db_cgm_classe.php");

$clempempenho = new cl_empempenho;
$clorcdotacao = new cl_orcdotacao;
$clpcmater  = new cl_pcmater;
$clcgm    = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("o40_descr");
$clpcmater->rotulo->label();
$clcgm->rotulo->label();

$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(){
  
  codemp = document.form1.e60_codemp.value ;    
  if (codemp!=""){
    jan = window.open('emp2_emppresta002.php?&e60_codemp='+codemp,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    //js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_emppresta.php?chave_e45_numemp='+document.form1.e60_numemp.value+'&funcao_js=parent.js_pesqemp|e45_numemp','Pesquisa',true);
  }else if (document.form1.e60_numemp.value!=""){
     jan = window.open('emp2_emppresta002.php?&e60_numemp='+document.form1.e60_numemp.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
  }else{
    alert("Campos vazios!!Preencha algum campo!!");  
  }
}
function js_pesqemp(chave1){
 jan = window.open('emp2_emppresta002.php?&e60_numemp='+chave1,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);

 db_iframe_empconsulta002.hide(); 
   
}
function js_limpa(){
   location.href='emp2_emppresta001.php'; 
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
    function js_mascara(evt){
      var evt = (evt) ? evt : (window.event) ? window.event : "";
      
      if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
	return true;
      }else{
	return false;
      }  
    }
</script>
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
<center>
<form name="form1" method="post">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>

          <tr> 
            <td  align="left" nowrap title="<?=$Te60_numemp?>">
                 <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1);  ?>
            </td>
	    
            <td  nowrap> 
             
	      <input name="e60_codemp" title='<?=$Te60_codemp?>' size="12" type='text'   >
            </td>
          </tr> 
  <tr> 
    <td  align="left" nowrap title="<?=$Te60_numemp?>"> <? db_ancora(@$Le60_numemp,"js_pesquisa_empenho(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("e60_numemp",13,$Ie60_numemp,true,"text",4); 
         //  db_input("z01_nome1",40,"",true,"text",3);  
        ?></td>
  </tr>



  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="pesquisa" type="button" onclick='js_abre();'  value="Emitir Relatório">
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar campos">
  </td>
  </tr>
  </table>
  </form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_emppresta.php?funcao_js=parent.js_mostraempenho1|e60_numemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}

//--------------------------------
function js_pesquisa_empenho(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_emppresta.php?funcao_js=parent.js_mostraempenho1|e60_numemp','Pesquisa',true);
  }else{
     if(document.form1.e60_numemp.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_emppresta','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempenho','Pesquisa',false);
     }else{
       document.form1.z01_nome1.value = ''; 
     }
  }
}
function js_mostraempenho(erro,chave){
  if(erro==true){ 
    document.form1.e60_numemp.focus(); 
    document.form1.z01_nome1.value = ''; 
  }
}
function js_mostraempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  // document.form1.z01_nome1.value = chave2;
  db_iframe_emppresta.hide();
}
//--------------------------------
</script>
</body>
</html>