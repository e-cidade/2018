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
  $clrotulo = new rotulocampo;
  $clrotulo->label("v50_inicial");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_AbreJanelaRelatorio() { 
    if (  document.form1.v50_inicial.value!='' || document.form1.v50_inicial.value!='' ) {
       jan = window.open('jur2_inicialetiqueta002.php?v50_inicial_fim='+document.form1.v50_inicial_fim.value+'&tamanho='+document.form1.tamanho.value+'&v50_inicial='+document.form1.v50_inicial.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
       jan.moveTo(0,0);
    } else {
       alert('Você deverá digitar o código da inicial.');
    }
  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
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
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"><table  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><form name="form1" method="post" action="div2_inicial_002.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="center"><table  border="0" cellspacing="0" cellpadding="0">
                      <tr align="center"> 
                        <td nowrap title="<?=@$Tv50_inicial?>" >&nbsp;
			<br>
			 <?	
			   db_ancora(@$Lv50_inicial,"js_pesquisainicial(true,false);",4)
			 ?>
			<?
			   db_input('v50_inicial',8,$Iv50_inicial,true,'text',4,"onchange='js_pesquisainicial(false,false);'")
			?>
			 <?
			   db_ancora("<b>até</b>","js_pesquisainicial(true,true);",4)
			 ?>
			<?
			   db_input('v50_inicial',8,$Iv50_inicial,true,'text',4,"onchange='js_pesquisainicial(false,false);'","v50_inicial_fim")
			?>
            </td>
			</td>
			
                      </tr>
                      <tr>
                         <td ><b>Tamanho</b>
                         <?
                         $x = array("1"=>"8cm x 6,5cm");
                         db_select('tamanho',$x,true,4,"");
                        ?>
	
	                </td>
                      </tr>
                      <tr align="center"> 
                        <td colspan="2">
			<br>
				<input size='10' name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()">
			</td>
                      </tr>
                    </table>
		  </td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisainicial(mostra,fim){
     if(mostra==true){
       if(fim==true){
         db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostratermofim|0';
       }else{
         db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostratermo1|0';
       } 	 
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostratermo';
     }
}
function js_mostratermo(chave,erro){
  if(erro==true){
     document.form1.v50_inicial.focus();
     document.form1.v50_inicial.value = '';
  }
}
function js_mostratermo1(chave1){
     document.form1.v50_inicial.value = chave1;
     db_iframe.hide();
}
function js_mostratermofim(chave1){
     document.form1.v50_inicial_fim.value = chave1;
     db_iframe.hide();
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