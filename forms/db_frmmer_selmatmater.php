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

//MODULO: issqn
$clrotulo = new rotulocampo;
$clrotulo->label("m60_codmater");
$clrotulo->label("m60_descr");
$op = 1;
if (isset($m60_descr) && $m60_descr != ""){
    $op = 3;
}
?>

<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm60_codmater?>" align="right">
       <?=@$Lm60_descr?>
    </td>
    <td> 
     <?
      db_input('m60_descr', 40, @$Im60_descr, true, 'text', $op, '');
      db_input('m60_codmater', 40, "", true, 'hidden', 3, '');
      db_input('me36_i_alimento', 40, "", true, 'hidden', 3, '');
     ?>
    </td>
  </tr>
  <?if (isset($m60_descr)&&$m60_descr != "") {?>
  
  <tr>
    <td colspan=2  align="center">
      <input name="atualizar" type="button"  id="db_opcao" value="Enviar" onclick="matmater.js_atualizar();" >
    </td>     
  </tr>  
  <tr>
    <td colspan="2">
       <iframe id="matmater"  frameborder="0" name="matmater"   leftmargin="0" topmargin="0" 
               src="mer4_selmatmateriframe.php?m60_descr=<?=@$m60_descr?>&m60_codmater=<?=@$m60_codmater?>&me36_i_alimento=<?=@$me36_i_alimento?>&me36_i_matmater=<?=@$me36_i_matmater?>" 
               height="400" width="900">
       </iframe> 
    </td>  
  </tr>
  
  <?} else {?>
    
    <tr>
    <td colspan=2  align="center">
      <input name="processar" type="submit"  id="db_opcao" value="Processar"  >
    </td>
     
  </tr>
    <?
    }
    ?>
  </table>
  </center>
</form>
<script>
function js_conclui() {
  parent.db_iframe_selmatmater.hide();
  parent.document.form1.submit();
}
</script>