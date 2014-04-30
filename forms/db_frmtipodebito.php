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
<center>
  <form name="form1" method="post" action="">
    <table width="68%" border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="35%" height="22"><strong>C&oacute;digo:</strong></td>
        <td width="65%" height="22"><input name="k00_tipo" type="text" value="<?=@$k00_tipo?>" size="10" readonly></td>
      </tr>
      <tr> 
        <td height="22"><strong>Descri&ccedil;&atilde;o:</strong></td>
        <td height="22"><input name="k00_descr" type="text" id="k00_descr" value="<?=@$k00_descr?>" size="40" maxlength="40"></td>
      </tr>
      <tr> 
        <td height="24"><strong>Libera recibo:</strong></td>
        <td height="24"><select name="k00_emrec">
            <option value="f" <? echo isset($k00_emrec)?($k00_emrec=="f"?"selected":""):"" ?>>N&atilde;o</option>
            <option value="t" <? echo isset($k00_emrec)?($k00_emrec=="t"?"selected":""):"" ?>>Sim</option>
          </select></td>
      </tr>
      <tr> 
        <td height="24"><strong>Agrupa por Numpre:</strong></td>
        <td height="24"><select name="k00_agnum">
            <option value="f" <? echo isset($k00_agnum)?($k00_agnum=="f"?"selected":""):"" ?>>N&atilde;o</option>
            <option value="t" <? echo isset($k00_agnum)?($k00_agnum=="t"?"selected":""):"" ?>>Sim</option>
          </select></td>
      </tr>
      <tr> 
        <td height="25"><strong>Agrupa por Parcelamento:</strong></td>
        <td height="25"><select name="k00_agpar">
            <option value="f" <? echo isset($k00_agpar)?($k00_agpar=="f"?"selected":""):"" ?>>N&atilde;o</option>
            <option value="t" <? echo isset($k00_agpar)?($k00_agpar=="t"?"selected":""):"" ?>>Sim</option>
          </select></td>
      </tr>
      <tr> 
        <td height="22"><strong>C&oacute;digo do Banco:</strong></td>
        <td height="22"><input name="k00_codbco" type="text" id="k00_codbco" value="<?=@$k00_codbco?>"></td>
      </tr>
      <tr> 
        <td height="22"><strong>C&oacute;digo da Ag&ecirc;ncia:</strong></td>
        <td height="22"><input name="k00_codage" type="text" id="k00_codage" value="<?=@$k00_codage?>" maxlength="5"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 1:</strong></td>
        <td height="25"><input name="k00_hist1" type="text" id="k00_hist1" value="<?=@$k00_hist1?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 2:</strong></td>
        <td height="25"><input name="k00_hist2" type="text" value="<?=@$k00_hist2?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 3:</strong></td>
        <td height="25"><input name="k00_hist3" type="text" value="<?=@$k00_hist3?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 4:</strong></td>
        <td height="25"><input name="k00_hist4" type="text" value="<?=@$k00_hist4?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 5:</strong></td>
        <td height="25"><input name="k00_hist5" type="text" value="<?=@$k00_hist5?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 6:</strong></td>
        <td height="25"><input name="k00_hist6" type="text" value="<?=@$k00_hist6?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 7:</strong></td>
        <td height="25"><input name="k00_hist7" type="text" value="<?=@$k00_hist7?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Linha 8:</strong></td>
        <td height="25"><input name="k00_hist8" type="text" value="<?=@$k00_hist8?>" size="60" maxlength="80"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Tipo Carne:</strong></td>
        <td height="24"> 
           <select name="codmodelo">
            <option value="0">Nenhum ...</option>
            <?
		$sql = "select codmodelo as a_codmodelo,nomemodelo from db_carnesimg";
		$result = pg_exec($sql);
		if(pg_numrows($result)!=0){
		  for($i=0;$i<pg_numrows($result);$i++){
		     db_fieldsmemory($result,$i);
		     echo "<option value='".$a_codmodelo."' ".($codmodelo==$a_codmodelo?"selected":"").">".$a_codmodelo." -> ".$nomemodelo."</option>";
		  }
		}
		?>
          </select> 
         </td>
      </tr>

      <tr> 
        <td height="24"><strong>Imprime Valor:</strong></td>
        <td height="24"> 
           <select name="k00_impval">
	    <option value="t" <?=(@$k00_impval=='t'?"selected":"")?>>SIM</option>";
	    <option value="f" <?=(@$k00_impval=='f'?"selected":"")?>>NÃO</option>";
          </select> 
         </td>
      </tr>
      <tr> 
        <td height="25"><strong>Valor Mínimo:</strong></td>
        <td height="22"><input name="k00_vlrmin" type="text" value="<?=@$k00_vlrmin?>" size="15" maxlength="15"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Tipo Débito:</strong></td>
        <td height="24"> 
           <select name="k03_tipo">
            <?
		$sql = "select k03_tipo as tipo,k03_descr from cadtipo order by k03_tipo";
		$result = pg_exec($sql);
		if(pg_numrows($result)!=0){
		  for($i=0;$i<pg_numrows($result);$i++){
		     db_fieldsmemory($result,$i);
		     echo "<option value='".$tipo."' ".($k03_tipo==$tipo?"selected":"").">".$tipo." -> ".$k03_descr."</option>";
		  }
		}
		?>
          </select> 
         </td>
      </tr>


      <tr> 
        <td height="25">&nbsp;</td>
        
        <td height="25"><input name="enviar" type="submit" id="enviar" value="Enviar">&nbsp;&nbsp;&nbsp;
        <?if(isset($db_opcao)&&$db_opcao==1){?><input name="importa" type="button" id="importa" value="Importar Tipo" onclick="js_importatipo();">
        	<?}?></td>
      </tr>
    </table>
  </form>
</center>
<script>

function js_importatipo(){
  js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_import|k00_tipo|k00_descr','Pesquisa',true);
}
function js_import(chave1,chave2){
  db_iframe_arretipo.hide();
  if(confirm('Deseja realmente importar o Tipo de Débito Nº '+chave1+'-'+chave2+'?')){
      js_OpenJanelaIframe('','db_iframe_importa','cai4_importatipo001.php?tipo='+chave1,'',false);
  }
}
function js_retornaimport(cod,descr,erro){
     db_iframe_importa.hide();
     if (erro=="true"){
         alert("Operação Cancelada!!Contate Suporte!!");   
     }else{

      	alert("Foi incluido o Tipo de Débito Nº  "+cod+"-"+descr);      	
      	location.href='cai1_tipodebito002.php?chavepesquisa='+cod;
     }
}
  js_Ipassacampo();
  if(document.form1)
    document.form1.elements[1].focus();
</script>