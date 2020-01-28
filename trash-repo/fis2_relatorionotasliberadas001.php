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


/***************************************************************/
//
//          fis2_relatorionotasliberadas001.php
//
//    Descrição: Consulta Notas liberadas por inscrição
//    Criado por: Francis Jeziorowski
//    Data de Criação: 27/07/2005  
//    Última Modificação: 27/07/2005
//    Modificado por:
//
/**************************************************************/



require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aidof_classe.php");
include("classes/db_issbase_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$claidof = new cl_aidof;
$claidof->rotulo->label();

$clissbase = new cl_issbase;
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");

$db_opcao=1;

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="form1" method="post" action="">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0">
<br><br><strong>Relatório de Notas Fiscais Liberadas</strong>
<br><br><br><br>

 <tr> 
     <td height="25" title="<?=$Tq02_inscr?>">
         <?
           db_ancora($Lq02_inscr,'js_pesquisaq02_inscr(true);',4)
         ?>
       </td>
     <td> 
       <?
         db_input('q02_inscr',8,$Iy08_inscr,true,'text',$db_opcao,"");
       ?>
    </td>
  </tr>
   
  <tr>
    <td nowrap title="<?=@$Ty08_dtlanc?>">
       <?=@$Ly08_dtlanc?>
    </td>
   <td> 
   <?
       db_inputdata('',@$dia,@$mes,@$ano,true,'text',$db_opcao,"")
   ?>
   &nbsp;&nbsp;&nbsp;À&nbsp;&nbsp;&nbsp;
    <?
       db_inputdata('a',@$diaa,@$mesa,@$anoa,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>  

 
  </table><br>
<input name="consultar" type="button" value="Relatório" onClick="js_consultaNotasLiberadas();js_limpacampos();" >
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<script>

function js_consultaNotasLiberadas(){
  jan = window.open('fis2_relatorionotasliberadas002.php?inscricao='+document.form1.q02_inscr.value+'&dataini='+document.form1._ano.value+'-'+document.form1._mes.value+'-'+document.form1._dia.value+'&datafim='+document.form1.a_ano.value+'-'+document.form1.a_mes.value+'-'+document.form1.a_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
}

function js_pesquisaq02_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_q02_inscr','func_issbase.php?funcao_js=parent.js_mostraq02_inscr1|q02_inscr','Pesquisa',true);
  }else{
     if(document.form1.q02_inscr.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_q02_inscr','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostraq02_inscr','Pesquisa',false);
     }else{
       document.form1.q02_inscr.value = '';
     }
  }
}

function js_mostraq02_inscr(chave,erro){

  document.form1.q02_inscr.value = chave;
  if(erro==true){
    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = erro;
  }
}

function js_mostraq02_inscr1(chave1){

  document.form1.q02_inscr.value = chave1;
  db_iframe_q02_inscr.hide();
}

function js_limpacampos(){
    document.form1._dia.value = ''; 
    document.form1._mes.value = ''; 
    document.form1._ano.value = ''; 
    document.form1.a_dia.value = ''; 
    document.form1.a_mes.value = ''; 
    document.form1.a_ano.value = ''; 
}

</script>