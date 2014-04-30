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
include("classes/db_inicial_classe.php");
include("classes/db_processoforoinicial_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$clinicial = new cl_inicial;
$clprocessoforoinicial = new cl_processoforoinicial;
$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$db_botao=1;
$db_opcao=1;
$retorno = false;

 if(isset($pesquisar)){
      $inicial=$v50_inicial;
      $res = $clinicial->sql_record($clinicial->sql_query($inicial,"z01_nome as advogado,v57_oab")); 
      $numrows= $clinicial->numrows;
      if($numrows==0){
         db_redireciona("jur2_inclupropri.php?testini=false");
      }else{
        db_fieldsmemory($res,0);//pega advogado
      }
      
      $sWhere = "processoforoinicial.v71_inicial = {$inicial} and processoforoinicial.v71_anulado is false";
      $result = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query(null,"v70_codforo",null,$sWhere)); 
      $numrows= $clprocessoforoinicial->numrows;
      if($numrows==0){
         db_redireciona("jur2_inclupropri.php?codforo=false");
      }else{
        db_fieldsmemory($result,0);//pega codigo do processo
      }
      $sql="
	select distinct k00_inscr,k00_matric from inicial
		inner join inicialcert 
			on v50_inicial=v51_inicial 
		inner join processoforoinicial
			 on processoforoinicial.v71_inicial=v51_inicial
			and processoforoinicial.v71_anulado is false
		inner join certid 
			on v13_certid=v51_certidao
		inner join certdiv 
			on v14_certid=v13_certid
		inner join divida 
			on v14_coddiv=v01_coddiv
		left outer join arreinscr 
			on arreinscr.k00_numpre=v01_numpre
		left outer join arrematric
			on arrematric.k00_numpre=v01_numpre
	where v50_inicial = $inicial";

      $result = pg_query($sql);
      db_fieldsmemory($result,0);
      if($k00_matric!=""){
	$modo="matricula";
	$j01_matric = $k00_matric;
	$chave = $j01_matric;
      }
      if($k00_inscr!=""){
	$modo="inscricao";
	$q02_inscr = $k00_inscr;
	$chave = $q02_inscr;
      }
      $retorno=true;
      db_redireciona("jur2_inclupropridad.php?inicial=$inicial&modo=$modo&chave=$chave");
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
  <form name="form1" method="post" action="">
   <table  border="0" cellspacing="0" cellpadding="0">
   <br>
   <br>
  <tr>
    <td nowrap title="<?=@$Tv50_inicial?>">
       <?
       db_ancora(@$Lv50_inicial,"js_pesquisav50_inicial(true);",1);
       ?>
    </td>
    <td> 
<?
db_input('v50_inicial',8,$Iv50_inicial,true,'text',1," onchange='js_pesquisav50_inicial(false);'")
?>
    </td>
  </tr>
  <tr>   
    <td colspan="2" align="center">
        <br>
	<input type="submit" name="pesquisar" value="Emitir" onclick="return js_veri();">
    </td>   	 
  </tr>	 
  </table> 	 
  </form>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_veri(){
  if(document.form1.v50_inicial.value==""){
    alert("Indique uma inicial!");
    return false;
  }
  return true;
}

function js_pesquisav50_inicial(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostrainicial1|0';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostrainicial';
  }
}
function js_mostrainicial1(chave){
  document.form1.v50_inicial.value=chave;    
  db_iframe.hide();
}
function js_mostrainicial(chave,erro){
  if(erro==true){
    document.form1.v50_inicial.value="";    
    document.form1.v50_inicial.focus(); 
  }else{
    document.form1.v50_inicial.value=chave;    
  }
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
if(isset($codforo) && $codforo!="" && $retorno==false){
  db_msgbox("Inicial sem o codigo do processo do fórum lançado!");
    empty($codforo);;
}
if(isset($testini) && $testini!="" && $retorno==false){
  db_msgbox("Inicial não existe!");
    empty($codforo);;
}
?>