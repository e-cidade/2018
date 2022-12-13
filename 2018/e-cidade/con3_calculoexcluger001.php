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
include("classes/db_editalrua_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arreold_classe.php");
include("classes/db_contrib_classe.php");
include("classes/db_contricalc_classe.php");
include("classes/db_contrinot_classe.php");
include("dbforms/db_funcoes.php");
$cleditalrua = new cl_editalrua;
$clcontrib = new cl_contrib;
$clcontricalc = new cl_contricalc;
$clcontrinot = new cl_contrinot;
$clarrecad = new cl_arrecad;
$clarreold = new cl_arreold;
$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label("d02_autori");
$clrotulo->label("j39_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$db_opcao = 1;
$db_botao = true;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

//echo "<br><br><br><br><br>";
if(isset($confirmar)){
  $resu = $clcontricalc->sql_record($clcontricalc->sql_query("","d09_numpre, d09_matric as j01_matric","","d09_contri=$d02_contri"));
  $numr = $clcontricalc->numrows;
  if($numr>0){
    $sqlerro=false;
    db_inicio_transacao();
    $falhou="";
    for($i=0; $i<$numr; $i++){
      db_fieldsmemory($resu,$i);
      $result=$clcontricalc->sql_record($clcontricalc->sql_query(null,"d09_sequencial,d09_contri",null,"d09_contri = $d02_contri and d09_matric = $j01_matric"));
      if (pg_numrows($result) > 0) {
        db_fieldsmemory($result,0); 
        $result = pg_query("select k00_numpre from arrecant where arrecant.k00_numpre=$d09_numpre");
        if(pg_numrows($result)>0){
          die("Cotribuição em processo de pagamento!");
        }else{
          $clcontricalc->excluir_arrecad($d09_numpre);
          if($clcontricalc->erro_status=="0"){
            $erro=$clcontricalc->erro_msg; 
            $sqlerro=true; 	
						break;
          }
          //$clcontrinot->excluir($d02_contri,$j01_matric,"");
          $clcontrinot->excluir(null," d08_contricalc = $d09_sequencial " );
          if($clcontrinot->erro_status==0){ 
            $erro=$clcontrinot->erro_msg;
            $falhou="ok";
            $sqlerro=true;
						break;
          }

          
          $clcontricalc->d09_contri=$d02_contri ;
          $clcontricalc->d09_matric=$j01_matric;
          $clcontricalc->excluir(null,"d09_contri = $d02_contri and d09_matric = $j01_matric");
          if($clcontricalc->erro_status==0){ 
            $falhou="ok";
            $sqlerro=true;
						break;
          }
          $erro=$clcontricalc->erro_msg;
        }  
      }
    }
    db_fim_transacao($sqlerro);
  }else{
    $noexis="ok";
  }
  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_confirmar(){
  if(document.form1.d02_contri.value==""){
    alert("Selecione uma matricula.");
    return false;
  }  
  return true;
}    
</script>


<link href="estilos.css" rel="stylesheet" type="text/css">
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
<form name="form1" method="post" action="">
<center>
<table border="0">
<tr>
<td nowrap title="<?=@$Td02_contri?>">
<?
db_ancora(@$Ld02_contri,"js_contri(true);",$db_opcao);
?>
</td>	
<td>	
<?
db_input('d02_contri',4,$Id02_contri,true,'text',$db_opcao," onchange='js_contri(false);'");
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'');
?>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<br>
<input name="confirmar" type="submit" id="confirmar" value="Confirmar" onclick="return js_confirmar()">
</td>
</tr>
</table>
</center>
</form>
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
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rua','func_editalruaalt.php?funcao_js=parent.js_mostracontri1|d02_contri|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_rua','func_editalruaalt.php?pesquisa_chave='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    document.form1.d02_contri.focus(); 
    document.form1.d02_contri.value=""; 
    document.form1.j14_nome.value=""; 
  }else{
    document.form1.j14_nome.value = chave;
  }  
}
function js_mostracontri1(chave1,chave2){
  document.form1.d02_contri.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_rua.hide();
}
</script>
<?
if(isset($confirmar)){
  if(isset($noexis) && $noexis=="ok"){
    db_msgbox("Não foi encontrado calculos para esta contribuição.");
  }else{
    db_msgbox($erro); 
  }  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>