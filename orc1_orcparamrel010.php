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
include("classes/db_orcparamrel_classe.php");
include("classes/db_orcparamelemento_classe.php");
include("classes/db_orcparamfontes_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

$clorcparamrel = new cl_orcparamrel;
$clorcparamrel->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");
$clrotulo->label("o56_elemento");

if(isset($qreceitas)){
  db_inicio_transacao();
  
  $clorcparamfontes = new cl_orcparamfontes;
  $result = $clorcparamfontes->sql_record($clorcparamfontes->sql_query(db_getsession("DB_anousu"),$o42_codparrel));
  if($result!= false && $clorcparamfontes->numrows>0){
    $clorcparamfontes->excluir(db_getsession("DB_anousu"),$o42_codparrel);
  } 
  if($qreceitas!=""){
    $rec = split("-",$qreceitas);
    for($i=0;$i<sizeof($rec);$i++){
      $clorcparamfontes->o43_anousu = db_getsession("DB_anousu");
      $clorcparamfontes->o43_codparrel = $o42_codparrel;
      $clorcparamfontes->o43_codfon = $rec[$i];
      $clorcparamfontes->incluir(db_getsession("DB_anousu"),$o42_codparrel,$rec[$i]);
      if($clorcparamfontes->erro_status == "0"){
        $clorcparamfontes->erro(true,false);
      }
    }
    
  }

  // despesa

  $clorcparamelemento = new cl_orcparamelemento;
  $result = $clorcparamelemento->sql_record($clorcparamelemento->sql_query(db_getsession("DB_anousu"),$o42_codparrel));
  if($result!= false && $clorcparamelemento->numrows>0){
    $clorcparamelemento->excluir(db_getsession("DB_anousu"),$o42_codparrel);
  } 
  if($qdespesas!=""){
    $rec = split("-",$qdespesas);
    for($i=0;$i<sizeof($rec);$i++){
      $clorcparamelemento->o43_anousu = db_getsession("DB_anousu");
      $clorcparamelemento->o43_codparrel = $o42_codparrel;
      $clorcparamelemento->o43_codfon = $rec[$i];
      $clorcparamelemento->incluir(db_getsession("DB_anousu"),$o42_codparrel,$rec[$i]);
      if($clorcparamelemento->erro_status == "0"){
        $clorcparamelemento->erro(true,false);
      }
    }
    
  }


  db_fim_transacao();

}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function  js_atualiza(){
  var despesa = "";
  var receita = "";
  x = elementos.document.form1.elements;
  sep = "";
  for(i=0;i<x.length;i++){
    if(x[i].checked){
      despesa = despesa + sep + x[i].name;
      sep = "-";
    }
  }

  sep = "";
  x = fontes.document.form1.elements;
  for(i=0;i<x.length;i++){
    if(x[i].checked){
      receita = receita + sep +  x[i].name;
      sep = "-";
    }
  }
  document.form1.qdespesas.value = despesa;
  document.form1.qreceitas.value = receita;
  document.form1.submit();
  
}
</script>


</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <table>
    <tr>
    <td nowrap title="<?=@$To42_codparrel?>">
       <?
       db_ancora(@$Lo42_codparrel,"js_pesquisao42_codparrel(true);",2);
       ?>
    </td>
    <td> 
       <?
       db_input('o42_codparrel',8,$Io42_codparrel,true,'text',2," onchange='js_pesquisao42_codparrel(false);'")
       ?>
       <?
       db_input('o42_descrrel',50,$Io42_descrrel,true,'text',3,'')
       ?>
    </td>
    </tr>
    <tr>
    <td colspan="2">
    <table>
    <?
    if(isset($o42_codparrel)){
     ?>
    <tr>
      <td>
      <iframe name="elementos" height="350" width="350" src="orc1_orcparamrel011.php?o42_codparrel=<?=$o42_codparrel?>"></iframe>
      </td>
      <td>
      <iframe name="fontes" height="350" width="350" src="orc1_orcparamrel012.php?o43_codparrel=<?=$o42_codparrel?>"></iframe>
      </td>
    </tr>
   </table>
    </td>
    </tr>
    <tr>
    <td colspan="2" align="center">
      <input name="atualiza" value="Atualiza" type="button" onclick="js_atualiza();">
      <input name="qdespesas" value="" type="hidden" >
      <input name="qreceitas" value="" type="hidden" >
    </td>
    </tr>
    <?
    }
    ?>
 
    </table>
    </center>
	</td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
function js_pesquisao42_codparrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel','Pesquisa',true);
  }else{
     if(document.form1.o42_codparrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.o42_codparrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.o42_codparrel.focus(); 
    document.form1.o42_codparrel.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_mostraorcparamrel1(chave1,chave2){
  document.form1.o42_codparrel.value = chave1;
  document.form1.o42_descrrel.value = chave2;
  db_iframe_orcparamrel.hide();
  document.form1.submit();
}
</script>