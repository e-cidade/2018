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
?>
<script>
	function js_copiar() {
     if (document.form1.arquivo.value == "") {
	   alert("Escolha um arquivo antes de clicar no botão anexar.");
	   return false;
	 } else {
	   document.form1.copiando.style.visibility='visible';
       document.form1.arquivo.style.visibility='hidden';
       document.form1.anexar.style.visibility='hidden';
	   return true;
	 }
	}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
  if (isset($HTTP_POST_VARS["anexar"])) {
    db_postmemory($_FILES["arquivo"]);
?>

<script>
  var F = parent.document.form1.elements['arquivos[]'];
  F.options[F.length] = new Option('<?=$name?>', '<?=$tmp_name?>', false, false);
  parent.js_trocacordeselect();
 // alert('Arquivo foi anexado com sucesso!');
</script>

<?
	  // Verifica o tamanho do arquivo a ser copiadoé menor que o permitido, caso nao seja exibe alerta  de erro, senao passa a linha de baixo
      system("cp ".$tmp_name." ".$tmp_name.".dbordem");
  } 
?>
<table  bgcolor="#CCCCCC" align="top" width="100%">
  <tr>
    <td>
      <form enctype="multipart/form-data" method="POST" name="form1">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td align="left" >
              <input name="arquivo" type="file"  id="arquivo6" size="50" > 
	      <? if($db_opcao==1 || $db_opcao==2){  ?>
                   <input name="anexar" type="submit" id="anexar5" onClick="return js_copiar()" value="Anexar" > 
	      <?  }else{?>
                   <input name="anexar" type="submit" id="anexar5" disabled value="Anexar" > 
	      <?  }?>
                   <input name="copiando" style="visibility:hidden" disabled type="text" id="copiando" value="Copiando Arquivo. Aguarde...">
	    </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
</table>