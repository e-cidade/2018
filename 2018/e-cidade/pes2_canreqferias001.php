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


//21.833.694.
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('r01_regist');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
</style>
<script>
function js_pesquisa(){
      F = 'document.forms';
      jan = window.open('pes2_canreqferias002.php?&ano='+document.form1.DBtxt23.value+
                                           '&mes='+document.form1.DBtxt25.value+
                                           '&matric='+document.form1.r01_regist.value
					   ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post">
   <table align="center" border="0" cellspacing="0" cellpadding="0" >
  <tr> 
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
  <tr> 
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
     <tr> 
       <td align="right" title="<?=$Tr01_regist?>"> 
            <?
    			db_ancora($Lr01_regist,'js_pesquisaregistro(true);',2)
    			?>&nbsp;&nbsp;&nbsp;
       </td >
       <td align="left" > 
            <?
    			db_input("r01_regist",8,$Ir01_regist,true,'text',4,"onchange='js_pesquisaregistro(false);'")
    			?>
            <?
    			db_input("z01_nome",40,$Iz01_nome,true,'text',3)
    			?>
       </td>
     </tr>
<!--     <tr> 
       <td align="right" height="25" title="<?=$Tz01_nome?>"> 
//            <?
//    			db_ancora($Lz01_nome,'js_mostranomes(true);',4)
//    			?>&nbsp;&nbsp;&nbsp;
       </td>
       <td  align="left" height="25"> 
//            <?
  //  			db_input("z01_numcgm",6,$Iz01_numcgm,true,'text',4," onchange='js_mostranomes(false);'")
//    			?>
//            <?
    //			db_input("z01_nome",40,$Iz01_nome,true,'text',5)
//    			?>
       </td>
     </tr>-->
  <tr> 
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr> 
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
     <tr> 
       <td colspan="2" align="center">
       <input onclick="js_pesquisa();"  type="button" value="Imprimir" name="pesquisar">
      &nbsp;&nbsp;&nbsp; 
     </tr>
   </table>
 </form>

<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaregistro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframepessoal','func_rhpessoal.php?funcao_js=parent.js_mostraregistro1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.r01_regist.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframepessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.r01_regist.value+'&funcao_js=parent.js_mostraregistro','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraregistro(chave,erro){
  document.form1.z01_nome.value  = chave;
  if(erro==true){
    document.form1.r01_regist.value = '';
    document.form1.r01_regist.focus();
  }
}
function js_mostraregistro1(chave1,chave2){
  document.form1.r01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframepessoal.hide();
}

</script>

<?

$VisualizacaoTodasMatCad = new janela("VisualizacaoTodasMatCad","");
$VisualizacaoTodasMatCad->posX=1;
$VisualizacaoTodasMatCad->posY=20;
$VisualizacaoTodasMatCad->largura=785;
$VisualizacaoTodasMatCad->altura=430;
$VisualizacaoTodasMatCad->titulo="Visualização das matriculas cadastradas";
$VisualizacaoTodasMatCad->iniciarVisivel = false;
$VisualizacaoTodasMatCad->mostrar();

// Cria a janela para visualizacao da matricula
$VisualizacaoMatricula = new janela("VisualizacaoMatricula","");
$VisualizacaoMatricula->posX=1;
$VisualizacaoMatricula->posY=20;
$VisualizacaoMatricula->largura=770;
$VisualizacaoMatricula->altura=410;
$VisualizacaoMatricula->titulo="Visualização dos dados do funcionário";
$VisualizacaoMatricula->iniciarVisivel = false;
$VisualizacaoMatricula->mostrar();


?>