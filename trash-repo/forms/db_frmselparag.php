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
$clrotulo->label("db03_docum");
$clrotulo->label("db03_descr");

?>

<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb03_docum?>" align="right">
       <?=@$Ldb03_docum?>
    </td>
    <td> 
<?
$result=$cldb_documento->sql_record($cldb_documento->sql_query($db03_docum));
if ($cldb_documento->numrows>0){
  db_fieldsmemory($result,0);
}

db_input('db03_docum', 8, $Idb03_docum, true, 'text', 3)
?>
       <?

 db_input('db03_descr', 40, $Idb03_descr, true, 'text', 3, '')
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
       <iframe id="parag"  frameborder="0" name="parag"   leftmargin="0" topmargin="0" src="con4_docparag008.php?db04_docum=<?=@$db03_docum?>&ordem=db02_idparag" height="400" width="900">
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
		parag.document.form1.ordem.value='db02_descr';
	}else{
		parag.document.form1.ordem.value='db02_idparag';
	}
	parag.document.form1.submit();
}
</script>