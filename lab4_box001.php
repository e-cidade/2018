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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
//include("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
$db_opcao=1;
$db_botao=true;
?>
<form name="form2" method="post" action="">
   <center>
   <fieldset><legend><b><?=$sNome?></b></legend>
	     <table border="0">
			    <tr>
			       <td>
                 <textarea title="" name="sTexto"  type="text" id="sTexto" rows="1" cols="70" ><?=@$sTexto?></textarea>
			       </td>
			    </tr>
			 </table>
   </fieldset>	
   </center>
   <p>
   <input name="salvar" type="button" id="salvar" value="Salvar" onclick="js_envia();">
   <input name="fechar" type="button" id="fechar" value="Fechar" onclick="js_fechar();">
</form>
<script type="text/javascript">
  function js_fechar(){
	   parent.db_iframe_lab_box.hide();
  }
  function js_envia(){
     parent.document.getElementById('<?=$sCampo?>').value=document.form2.sTexto.value;
     js_fechar();
  }
</script>