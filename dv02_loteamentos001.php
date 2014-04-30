<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clrotulo->label('DBtxt34');
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>

function js_emite(){
 jan = window.open('dv02_loteamentos002.php?procdiver='+document.form1.procdiver.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}


</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

<form class="container" name="form1" method="post">
  <fieldset>
    <legend>Relatórios - Pagamento Loteamento</legend>
    <table class="form-container">
      <tr> 
        <td>Loteamento:</td>
        <td><select name="procdiver" id="select5">
            <option value="221" selected>POR DO SOL</option>
            <option value="1284" >SOL NASCENTE</option>
          </select> </td>
      </tr>
      <!-- <tr> 
        <td align="right" title="<?=$TDBtxt34?>"> 
          <?=@$LDBtxt34?>&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td> 
          <?
            db_input('DBtxt34',3,$IDBtxt34,true,'text',2);
          ?>
        </td>
      </tr>-->
    </table>
  </fieldset>
  <input name="processar" type="button" id="processar" value="Processar" onClick="js_emite();">
</form>

      <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisaprocdiver(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_procdiver.php?funcao_js=parent.js_mostratermo1|0';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_procdiver.php?pesquisa_chave='+document.form1.v14_certid.value+'&funcao_js=parent.js_mostratermo';
     }
}
function js_mostratermo(chave,erro){
  if(erro==true){
     document.form1.procdiver.focus();
     document.form1.procdiver.value = '';
  }
}
function js_mostratermo1(chave1){
     document.form1.procdiver.value = chave1;
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