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

//MODULO: pessoal
$clrhinssoutros->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh51_seqpes");
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh01_regist?>">
      <?
      db_ancora(@$Lrh01_regist,"js_pesquisarh01_regist(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('rh01_regist',6,$Irh01_regist,true,'text',$db_opcao," onchange='js_pesquisarh01_regist(false);'");
      db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
      db_input('rh51_seqpes',6,$Irh51_seqpes,true,'hidden',3,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh51_basefo?>">
      <?=@$Lrh51_basefo?>
    </td>
    <td> 
      <?
      db_input('rh51_basefo',15,$Irh51_basefo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh51_descfo?>">
      <?=@$Lrh51_descfo?>
    </td>
    <td> 
      <?
      db_input('rh51_descfo',15,$Irh51_descfo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh51_b13fo?>">
      <?=@$Lrh51_b13fo?>
    </td>
    <td> 
      <?
      db_input('rh51_b13fo',15,$Irh51_b13fo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh51_d13fo?>">
      <?=@$Lrh51_d13fo?>
    </td>
    <td> 
      <?
      db_input('rh51_d13fo',15,$Irh51_d13fo,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh51_ocorre?>">
       <?=@$Lrh51_ocorre?>
    </td>
    <td colspan="3"> 
       <?
       $arr_ocorre = array(
                           '05'=>'05 - Não exposto no momento',
                           '06'=>'06 - Exposta (aposentadoria esp. 15 anos)',
                           '07'=>'07 - Exposta (aposentadoria esp. 20 anos)',
                           '08'=>'08 - Exposta (aposentadoria esp. 25 anos)'
                          );
       db_select("rh51_ocorre",$arr_ocorre,true,$db_opcao);
       ?>
    </td>
  </tr>
</table>
<?
if($db_opcao == 1){
?>
<input name="incluir" type="submit" id="db_opcao" value="Incluir" onblur='js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);'>
<?
}else{
?>
<input name="alterar" type="submit" id="db_opcao" value="Alterar">
<input name="excluir" type="submit" id="db_opcao" value="Excluir" onblur='js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);'>
<?
}
?>
</center>
</form>
<script>
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&funcao_js=parent.js_preenchepesquisa|rh01_regist&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.rh01_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      document.form1.rh51_basefo.value = ''; 
      document.form1.rh51_descfo.value = ''; 
      document.form1.rh51_b13fo.value = ''; 
      document.form1.rh51_d13fo.value = ''; 
      document.form1.rh51_seqpes.value = ''; 
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
    document.form1.rh51_basefo.value = ''; 
    document.form1.rh51_descfo.value = ''; 
    document.form1.rh51_b13fo.value = ''; 
    document.form1.rh51_d13fo.value = ''; 
    document.form1.rh51_seqpes.value = ''; 
  }else{
    js_preenchepesquisa(document.form1.rh01_regist.value);
  }
}
/*
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  js_submita();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_preenchepesquisa|rh01_regist&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
}
*/
function js_preenchepesquisa(chave){
  db_iframe_rhpessoal.hide();
  location.href = 'pes1_rhinssoutros001.php?chavepesquisa='+chave;
}
</script>