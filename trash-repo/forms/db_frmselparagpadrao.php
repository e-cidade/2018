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
$clrotulo->label("db60_coddoc");
$clrotulo->label("db60_descr");

?>

<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb60_coddoc?>" align="right">
       <?=@$Ldb60_coddoc?>
    </td>
    <td> 
<?
$result=$cldb_documentopadrao->sql_record($cldb_documentopadrao->sql_query($db60_coddoc));
if ($cldb_documentopadrao->numrows>0){
  db_fieldsmemory($result,0);
}

db_input('db60_coddoc', 8, $Idb60_coddoc, true, 'text', 3)
?>
       <?

 db_input('db60_descr', 40, $Idb60_descr, true, 'text', 3, '')
?>
<b>Ordem:</b>
      <?
        $tipo_ordem = array("b"=>"Numérica","a"=>"Alfabética");
	    db_select("ordem",$tipo_ordem,true,2,"onchange='js_ordem(this.value);'"); ?>
    </td>
  </tr>
  
  <tr>
    <td colspan=2  align="center">
      <input name="atualizar" type="button"  id="db_opcao" value="Enviar" onclick="parag.js_atualizar();" >
    </td>
     
  </tr>
  <tr>
    <td colspan="2">
       <iframe id="parag"  frameborder="0" name="parag"   leftmargin="0" topmargin="0" src="con4_docpadrao008.php?db62_coddoc=<?=@$db60_coddoc?>&ordem=db61_codparag" height="400" width="900">
       </iframe> 
    </td>  
  </tr>
  </table>
  </center>
</form>
<script>
function js_conclui(){
	parent.db_iframe_selparag.hide();
	parent.document.form1.submit();
}
function js_ordem(ordem){
	if (ordem=='a'){
		parag.document.form1.ordem.value='db61_descr';
	}else{
		parag.document.form1.ordem.value='db61_codparag';
	}
	parag.document.form1.submit();
}
</script>