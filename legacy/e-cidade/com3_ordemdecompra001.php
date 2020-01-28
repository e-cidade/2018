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
require_once("libs/db_liborcamento.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_matordem_classe.php");

$clmatordem   = new cl_matordem;
$clempempenho = new cl_empempenho;
$clcgm        = new cl_cgm;
$clrotulo     = new rotulocampo;

$clcgm->rotulo->label();
$clempempenho->rotulo->label();
$clmatordem->rotulo->label();
$clrotulo->label("z01_nome");
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){

  if (document.form1.m51_codordem.value==''){

    if(document.form1.m51_numcgm.value!=''){

      js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_matordemempcgm.php?m51_numcgm='+document.form1.m51_numcgm.value+'&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|m51_codordem','Pesquisa',true);

    }else if(document.form1.e60_codemp.value!=''){

      js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_matordemempcgm.php?e60_codemp='+document.form1.e60_codemp.value+'&funcao_js=parent.js_mostraempempenho1|m52_numemp|m51_codordem','Pesquisa',true);

    }else if(document.form1.m52_numemp.value!=''){

      js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_matordemempcgm.php?m52_numemp='+document.form1.m52_numemp.value+'&funcao_js=parent.js_mostraempempenho1|m52_numemp|m51_codordem','Pesquisa',true);
    }

  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_ordemcompra002','com3_ordemdecompra002.php?m51_codordem='+document.form1.m51_codordem.value+'&e60_numcgm='+document.form1.m51_numcgm.value+'&e60_numemp='+document.form1.m52_numemp.value,'Pesquisa',true);
  }
}
function js_limpa(){
   location.href='emp3_ordemcompra001.php';
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">



<center>
<form name="form1" method="post" target="" action="com3_ordemdecompra002.php">

<fieldset style="margin-top: 30px; width: 600px;">
<legend>Consulta de Ordem de Compra</legend>

  <table border='0' align='left'>

    <tr>
      <td  align="left" nowrap title="<?=$Tm51_codordem?>"><?db_ancora(@$Lm51_codordem,"js_pesquisa_matordem(true);",1);?></td>
      <td align="left" nowrap>
        <? db_input("m51_codordem",10 ,$Im51_codordem,true,"text",4,"onchange='js_pesquisa_matordem(false);'");?>
      </td>
    </tr>

    <tr>
      <td  align="left" nowrap title="<?=$Te60_numcgm?>"><?db_ancora(@$Le60_numcgm,"js_pesquisae60_numcgm(true);",1);?></td>
      <td align="left" nowrap>
        <? db_input("m51_numcgm", 10,$Ie60_numcgm,true,"text",4,"onchange='js_pesquisae60_numcgm(false);'");
           db_input("z01_nome",40,"$Iz01_nome",true,"text",3);
        ?>
      </td>
    </tr>


    <tr>
  	  <td nowrap title="<?=@$Te60_codemp?>">
  	     <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1); ?>
  	  </td>
  	  <td>
  	     <? db_input('e60_codemp',10,$Ie60_codemp,true,'text',4,"")  ?>
  	  </td>
    </tr>


    <tr>
  	  <td nowrap title="<?=@$Te60_numemp?>">
  	   <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",1); ?>
  	  </td>
  	  <td>
  	   <? db_input('m52_numemp',10,$Ie60_numemp,true,'text',4," onchange='js_pesquisae60_numemp(false);'")  ?>
  	  </td>
    </tr>


  </table>

</fieldset>



<div style="margin-top: 10px;">

  <input name="processar" type="button" onclick='js_emite();'  value="Processar" >
  <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar">

</div>



</form>


</center>



<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//---------------------------------------------------------------
function js_pesquisae60_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_matordemempcgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome|m51_codordem','Pesquisa',true);
  }else{
     if(document.form1.m51_numcgm.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.m51_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.m51_numcgm.focus();
    document.form1.m51_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2,chave3){
  document.form1.m51_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.m51_codordem.value = chave3;
 db_iframe_cgm.hide();
}
//----------------------------------------------------------------------
function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_matordemempcgm.php?funcao_js=parent.js_mostraempempenho1|m52_numemp|m51_codordem','Pesquisa',true);  }else{
     if(document.form1.m52_numemp.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_matordemempcgm.php?pesquisa_chave='+document.form1.m52_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     }else{
       document.form1.m52_numemp.value = '';
     }
  }
}
function js_mostraempempenho(chave,erro){
  if(erro==true){

    document.form1.m52_numemp.focus();
    document.form1.m52_numemp.value = '';
  }
}
function js_mostraempempenho1(chave1,x){
  document.form1.m52_numemp.value = chave1;
  document.form1.m51_codordem.value = x;
  document.form1.e60_codemp.value = '';
  db_iframe_empempenho.hide();

}
//-----------------------------------------------------
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_matordemempcgm.php?funcao_js=parent.js_mostraempempenho1|m52_numemp|m51_codordem','Pesquisa',true);
  }else{
     if(document.form1.e60_numemp.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_matordemempcgm.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     }else{
       document.form1.e60_numemp.value = '';
     }
  }
}
//--------------------------------
function js_pesquisa_matordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordemanulada.php?funcao_js=parent.js_mostramatordem1|m51_codordem|','Pesquisa',true);
  }else{
     if(document.form1.m51_codordem.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordemanulada.php?pesquisa_chave='+document.form1.m51_codordem.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.m51_codordem.value = '';
     }
  }
}
function js_mostramatordem(chave, erro) {

  if (erro==true) {

    document.form1.m51_codordem.value = '';
    document.form1.m51_codordem.focus();
  }
}
function js_mostramatordem1(chave1){
   document.form1.m51_codordem.value = chave1;
   db_iframe_matordem.hide();
}
//------------------------------------------------------
</script>
</body>
</html>