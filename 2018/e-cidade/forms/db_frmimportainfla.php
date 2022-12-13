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
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<center>
<table border="0">
  <tr>
    <td nowrap align='right'>
      <b>Indique o caminho do arquivo:</b>
    </td>
    <td nowrap align='left'>
      <?
      db_input('arquivo',40,0,true,'file',1)
      ?>
    </td>
  </tr> 
  <tr>
    <td nowrap title="Informe o separador dos campos" align="right">
      <strong>Separador:</strong>
    </td>
    <td> 
      <?
      if(!isset($separador) || (isset($separador) && ($separador) == "")){
	$separador = ";";
      }
      db_input('separador',3,0,true,'text',1,"")
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="incluir" type="submit" id="db_opcao" value="Processar arquivo" onclick="return js_processar();" >
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_processar(){
  if(document.form1.arquivo.value == ""){
    alert("Informe o arquivo a ser processado.");
    return false;
  }
  return true;
}
</script>