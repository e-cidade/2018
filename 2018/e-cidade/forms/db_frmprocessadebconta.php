<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: caixa
$clrotulo = new rotulocampo;
$clrotulo->label('arqret');
$clrotulo->label('d63_banco');
$clrotulo->label('k15_codage');
$clrotulo->label('k00_valor');
$clrotulo->label('k00_numpar');
?>
<form name="form1" enctype="multipart/form-data" method="post" action="">
<center>
<table border="0">
  <tr>   
      <td>
      <?
      db_ancora($Ld63_banco,' js_bancos(true); ',$db_opcao);
      ?>
       </td>
       <td> 
      <?
       db_input('d63_banco',5,$Id63_banco,true,'text',1,"onchange='js_bancos(false)'");
       db_input('nome_banco',40,"",true,'text',3);
       
      ?>
       </td>
     </tr>
  <tr>
    <td nowrap title="<?=@$Tk15_codage?>">
       <?=@$Lk15_codage?>
    </td>
    <td> 
<?
db_input('k15_codage',10,$Ik15_codage,true,'text',$db_opcao,"")
?>
    </td>
  </tr>



      <?
      if (isset($arq_name)) {
      ?>
      <tr> 
        <td nowrap><?=$Larqret?> </td>
        <td> 
	<?
	db_input('arq_name',50,"",true,'text',3,"");
        ?>	
      </tr>
	<tr> 
	  <td nowrap><?=$Lk00_numpar?> </td>
	  <td> 
	  <?
	  //db_input("k00_numpar",50,$Ik00_numpar,true,"file",4)
    db_input('k00_numpar',3,$Ik00_numpar,true,'text',3,"")
	  ?>	
	</tr>

      <?
      } else {
      ?>
	<tr> 
	  <td nowrap><?=$Larqret?> </td>
	  <td> 
	  <?
	  db_input("arqret",50,$Iarqret,true,"file",4)
	  ?>	
	</tr>
  
	<tr> 
	  <td nowrap><?=$Lk00_numpar?> </td>
	  <td> 
	  <?
	  //db_input("k00_numpar",50,$Ik00_numpar,true,"file",4)
    db_input('k00_numpar',3,$Ik00_numpar,true,'text',$db_opcao,"")
	  ?>	
	</tr>

      <?
      }
      ?>

      <?
	if (isset($totalvalorpago)) {
      ?>
      <tr> 
        <td nowrap><?=$Lk00_valor?> </td>
        <td> 
	<?
	db_input('totalvalorpago',10,"",true,'text',3,"");
        ?>	
      </tr>
      <?
      }
      ?>


      <?
	if (isset($totalproc)) {
      ?>
      <tr> 
        <td nowrap><b>Linhas</b></td>
        <td><input name="totalproc" type="text" id="totalproc" value="<?=$totalproc?>" size="11" maxlength="10"></td>
      </tr>
      <?
      }
      ?>
      
  
  </table>
  </center>
<!--<input name="processar" type="submit" id="processar" value="Processar"> -->
	<?
	if(isset($processar)) {
	?>
<input name="arq_tmpname" type="hidden" id="arq_tmpname" value="<?=ECIDADE_PATH."tmp/".$arq_tmpname?>">
<input name="k00_numpar" type="hidden" id="k00_numpar" value="<?=$k00_numpar?>">
<input name="geradebcta" type="submit" id="geradebcta" value="Processar">

  <?
	} else {
	?>
<input name="processar" type="submit" id="processar" value="Processar">
  <?
	}
	?>

</form>
<script>
function js_bancos(mostra){
  var bancos=document.form1.d63_banco.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_bancos.php?funcao_js=parent.js_mostrabancos|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_bancos.php?pesquisa_chave='+bancos+'&funcao_js=parent.js_mostrabancos1','Pesquisa',false);
  }
}
function js_mostrabancos(chave1,chave2){
  document.form1.d63_banco.value = chave1;
  document.form1.nome_banco.value = chave2;  
  db_iframe2.hide();
}
function js_mostrabancos1(chave,erro){
  document.form1.nome_banco.value = chave;
  if(erro==true){ 
    document.form1.d63_banco.focus(); 
    document.form1.d63_banco.value = ''; 
  }
}
</script>