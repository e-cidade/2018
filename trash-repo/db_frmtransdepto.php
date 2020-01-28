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
include("classes/db_proctransfer_classe.php");
include("classes/db_proctransferproc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;
$clproctransfer->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("descrdepto");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
.dono {background-color:#FFFFFF;
       color:red 
      }
      
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr height="15">
    <td nowrap title="<?=@$Tp62_id_usorec?>">
       <?
       db_ancora(@$Lp62_id_usorec,"js_pesquisap62_id_usorec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p62_id_usorec',4,$Ip62_id_usorec,true,'text',$db_opcao," onchange='js_pesquisap62_id_usorec(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp62_coddeptorec?>">
       <?
       db_ancora(@$Lp62_coddeptorec,"js_pesquisap62_coddeptorec(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p62_coddeptorec',3,$Ip62_coddeptorec,true,'text',$db_opcao," onchange='js_pesquisap62_coddeptorec(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisap62_id_usorec(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_usuarios.php?pesquisa_chave='+document.form1.p62_id_usorec.value+'&funcao_js=parent.js_mostradb_usuarios';
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.p62_id_usorec.focus(); 
    document.form1.p62_id_usorec.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.p62_id_usorec.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisap62_coddeptorec(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_db_depart.php?funcao_js=parent.js_mostradb_depart1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_db_depart.php?pesquisa_chave='+document.form1.p62_coddeptorec.value+'&funcao_js=parent.js_mostradb_depart';
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.p62_coddeptorec.focus(); 
    document.form1.p62_coddeptorec.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.p62_coddeptorec.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_proctransfer.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
</center>
</body>
</html>