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
include("classes/db_orcdotacao_classe.php");
include("classes/db_pcmater_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_empautoriza_classe.php");

$clempautoriza = new cl_empautoriza;
$clorcdotacao = new cl_orcdotacao;
$clpcmater  = new cl_pcmater;
$clcgm    = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("o40_descr");
$clrotulo->label("e60_emiss");
$clpcmater->rotulo->label();
$clcgm->rotulo->label();

$clempautoriza->rotulo->label();
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
   // js_OpenJanelaIframe('','db_iframe_orgao','func_saldoorcdotacao.php?coddot='+coddot,'pesquisa',true);
   obj = document.form1;
   if (    (obj.e60_emiss1_dia.value !='') 
        && (obj.e60_emiss2_dia.value !='')
        && (obj.e60_emiss1_mes.value !='')
        && (obj.e60_emiss2_mes.value !='')
        && (obj.e60_emiss1_ano.value !='')
        && (obj.e60_emiss1_ano.value !='')) {
    dt1 = obj.e60_emiss1_ano.value+'-'+obj.e60_emiss1_mes.value+'-'+obj.e60_emiss1_dia.value ;
    dt2 = obj.e60_emiss2_ano.value+'-'+obj.e60_emiss2_mes.value+'-'+obj.e60_emiss2_dia.value ;
   } else {
      dt1='';
      dt2='';
   }  
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_empconsulta003',
		      'emp1_empconsulta003.php?e54_autori='+document.form1.e54_autori.value+
		      '&o58_coddot='+document.form1.o58_coddot.value+
		      '&pc01_codmater='+document.form1.pc01_codmater.value+
		      '&z01_numcgm='+document.form1.z01_numcgm.value+
		      '&dt1='+dt1+
		      '&dt2='+dt2+
		      '&funcao_js=parent.js_consulta002|e54_autori',
		      'Pesquisa',
		       true);
}
function js_consulta002(chave1){
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_empempenhoaut001',
		      'func_empempenhoaut001.php?e54_autori='+chave1,
		      'Pesquisa',
		      true);
  // db_iframe_empconsulta003.hide(); 
   
}
function js_limpa(){
   location.href='emp1_empconsultaaut001.php'; 
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
<center>
<form name="form1" method="post">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>

  <tr> 
    <td align="left" nowrap > <? db_ancora(@$Le54_autori,"js_pesquisa_aut(true);",1);?>  </td>
    <td align="left" nowrap>
      <?
         db_input("e54_autori",6,$Ie54_autori,true,"text",4,"onclick='js_pesquisa_aut(false);'"); 
         // db_input("z01_nome1",40,"",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$To58_coddot?>"><?db_ancora(@$Lo58_coddot,"js_pesquisa_dotacao(true);",1);?> </td>
    <td align="left" nowrap>
      <? db_input("o58_coddot",6,$Io58_coddot,true,"text",4,"onchange='js_pesquisa_dotacao(false);'"); 
         db_input("o40_descr",40,"",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tpc01_codmater ?>"><?db_ancora(@$Lpc01_codmater,"js_pesquisa_pcmater(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("pc01_codmater",6,$Ipc01_codmater,true,"text",4,"onchange='js_pesquisa_pcmater(false);'"); 
         db_input("pc01_descrmater",40,"",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tz01_numcgm?>"><?db_ancora(@$Lz01_nome,"js_pesquisa_cgm(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",4,"onchange='js_pesquisa_cgm(false);'");
         db_input("z01_nome2",40,"",true,"text",3);  
        ?></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Te60_emiss?>"><?db_ancora(@$Le60_emiss,"",1);?></td>
    <td align="left" nowrap>
      <? db_inputdata('e60_emiss1',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");   		          
         echo " a ";
         db_inputdata('e60_emiss2',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");
        ?></td>
  </tr>



  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="pesquisa" type="button" onclick='js_abre();'  value="Pesquisa">
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar campos">
  </td>
  </tr>
  </table>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome2.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome2.value = chave; 
  if(erro==true){ 
    document.form1.z01_nome2.value = ''; 
    document.form1.z01_numcgm.focus(); 
  }
}
function js_mostracgm1(chave1,chave2){
   document.form1.z01_numcgm.value = chave1;  
   document.form1.z01_nome2.value = chave2;
   func_nome.hide();
}
//--------------------------------
function js_pesquisa_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true);
  }else{
     if(document.form1.pc01_codmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.pc01_codmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.pc01_codmater.focus(); 
    document.form1.pc01_descrmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
   document.form1.pc01_codmater.value = chave1;  
   document.form1.pc01_descrmater.value = chave2;
   db_iframe_pcmater.hide();
}
//--------------------------------
function js_pesquisa_dotacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostradotacao1|o58_coddot|o56_descr','Pesquisa',true);
  }else{
     if(document.form1.o58_coddot.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o58_coddot.value+'&funcao_js=parent.js_mostradotacao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostradotacao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_coddot.focus(); 
    document.form1.o58_coddot.value = ''; 
  }
}
function js_mostradotacao1(chave1,chave2){
  document.form1.o58_coddot.value = chave1;  
  document.form1.o40_descr.value = chave2;
  db_iframe_orcdotacao.hide();
}
//--------------------------------
function js_pesquisa_aut(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empautoriza','func_empautoriza.php?funcao_js=parent.js_mostraautori1|e54_autori','Pesquisa',true);
  }else{
     if(document.form1.e54_autori.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empautoriza','func_empautoriza.php?pesquisa_chave='+document.form1.e54_autori.value+'&funcao_js=parent.js_mostraautori','Pesquisa',false);
     }else{
      // document.form1.z01_nome1.value = ''; 
     }
  }
}
function js_mostraautori(erro,chave){
  // document.form1.z01_nome1.value = chave; 
  if(erro==true){ 
    document.form1.e54_autori.focus(); 
  //  document.form1.z01_nome1.value = ''; 
  }
}
function js_mostraautori1(chave1){
  // alert(chave1);
  document.form1.e54_autori.value = chave1;
  // document.form1.z01_nome1.value = chave2; 
  db_iframe_empautoriza.hide();
}
//--------------------------------
</script>
</body>
</html>