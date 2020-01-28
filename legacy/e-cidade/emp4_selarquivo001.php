<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("classes/db_empagegera_classe.php");
require_once("classes/db_empageconfgera_classe.php");
require_once("classes/db_empagetipo_classe.php");
require_once("classes/db_empagedadosret_classe.php");
$clempagegera     = new cl_empagegera;
$clempageconfgera = new cl_empageconfgera;
$clempagetipo     = new cl_empagetipo;
$clempagedadosret = new cl_empagedadosret;
$clrotulo         = new rotulocampo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();
$clempagedadosret->rotulo->label();

db_postmemory($HTTP_POST_VARS);

$action = "Confirmar ";
$formul = "emp4_empageretornoconf001.php?lCancelado=0";
$TorF = "true";
if(isset($canc)){
  $action = "Cancelar ";
  $formul = "emp4_empageretornocanc001.php?lCancelado=0";
  $TorF = "false";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e87_codgera.focus();" bgcolor="#cccccc">
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?
  if(isset($e87_codgera)){
    $result_dadosret = $clempagedadosret->sql_record($clempagedadosret->sql_query("","e75_codret,e87_codgera,e87_descgera"," e75_codret desc limit 1 "," e75_codgera = $e87_codgera"));
    if($clempagedadosret->numrows>0){
      db_fieldsmemory($result_dadosret,0);
      echo "
      <tr> 
	<td align='left' nowrap title='$Te75_codret'>
      ";
	  db_ancora(@$Le75_codret,"",3);
      echo "      
	</td>
	<td align='left' nowrap>
      ";
	  db_input("e75_codret",8,$Ie75_codret,true,"text",3); 
      echo "      
	</td>
      </tr>
      ";
    }
  }
  ?>
  <tr> 
    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",1);?>  </td>
    <td align="left" nowrap>
  <?
   db_input("e87_codgera",8,$Ie87_codgera,true,"text",4,"onchange='js_pesquisa_gera(false);'"); 
   db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
  ?>
    </td>
  </tr>
  <?
  $db_passapar = "true";
  if(isset($e87_codgera)){
    echo "
    <tr> 
      <td  align='left' nowrap title='Conta pagadora'>
    ";
    db_ancora("<strong>Conta pagadora:</strong>","",3);
    echo "
      <td align='left' nowrap>
    ";
    $result_empagetipo = $clempageconfgera->sql_record($clempageconfgera->sql_query_inf(null,@$e87_codgera,"distinct e83_codtipo,e83_descr"));
    if($clempagetipo->numrows == 0){
      $db_passapar = "false";
    }
    
    db_selectrecord("e83_codtipo",$result_empagetipo,true,1,"","","","0");
    echo "
      </td>
    </tr>
    ";
  }
  ?>  
  <tr>
    <td colspan="2" align="center"><br>
      <input name="act" type="button" <?=("onclick='js_geraact($db_passapar);'")?>  value="Mostrar retorno">
      <input name="pes" type="button" onclick='js_OpenJanelaIframe("top.corpo","db_iframe_empagegera","func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera","Pesquisa",true);'  value="Pesquisar arquivos">
    </td>
  </tr>
</table>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//--------------------------------
function js_geraact(x){
  if(document.form1.e75_codret && document.form1.e75_codret.value!=""){
    qry = "retornoarq="+document.form1.e75_codret.value;
    if(document.form1.e83_codtipo.value!="0"){
      qry+= "&contapaga="+document.form1.e83_codtipo.value;
    }
    qry+= "&retornomn=<?=@$TorF?>";
    location.href = "<?=@$formul?>&"+qry;
  }else if(!document.form1.e75_codret){
    alert("Informe o código de um arquivo já processado.");
  }else{
    alert("Informe o código do retorno válido para <?=@$action?> movimentos.");
  }
}
function js_pesquisa_gera(mostra){
  if(document.form1.e75_codret){
    document.form1.e75_codret.value = "";
  }
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa',true);
  }else{
     if(document.form1.e87_codgera.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
     }else{
       document.form1.e87_descgera.value = ''; 
     }
  }
}
function js_mostragera(chave,erro){
  if(erro==true){ 
    document.form1.e87_codgera.focus(); 
    document.form1.e87_codgera.value = ''; 
  }
  document.form1.e87_descgera.value = chave; 
  document.form1.submit();
}
function js_mostragera1(chave1,chave2){
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
  document.form1.submit();
}
//--------------------------------
</script>
</body>
</html>