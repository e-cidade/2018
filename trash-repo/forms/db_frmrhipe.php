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

//MODULO: pessoal
$clrhipe->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh62_regist");
$clrotulo->label("rh63_numcgm");

$chamarFuncao = false;
if(!isset($incluir) && !isset($alterar) && !isset($excluir)){
  if(isset($rh62_regist) && trim($rh62_regist) != ""){
    $result_numcgm = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh62_regist, "rh01_numcgm  as rh63_numcgm, z01_nome as z01_nomecgm"));
    if($clrhpessoal->numrows > 0){
      db_fieldsmemory($result_numcgm, 0);
    }
  }else if(isset($rh63_numcgm) && trim($rh63_numcgm) != ""){
    $result_regist = $clcgm->sql_record($clcgm->sql_query_file(null, "z01_numcgm  as rh63_numcgm, z01_nome as z01_nomecgm", "", "z01_numcgm = " . $rh63_numcgm));
    if($clcgm->numrows > 0){
      db_fieldsmemory($result_regist, 0);
//      $chamarFuncao = true;
    }
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td>
    <?
    db_input('rh14_sequencia',6,$Irh14_sequencia,true,'hidden',"3","")
    ?>
    <fieldset>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Trh62_regist?>">
           <?
           db_ancora(@$Lrh62_regist,"js_pesquisarh62_regist(true, false);",$db_opcao==1?"1":"3");
           ?>
        </td>
        <td colspan="3"> 
    <?
    db_input('rh62_regist',6,$Irh62_regist,true,'text',$db_opcao==1?"1":"3"," onchange='js_pesquisarh62_regist(false, false);'")
    ?>
           <?
    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh63_numcgm?>">
           <?
           db_ancora(@$Lrh63_numcgm,"js_pesquisarh63_numcgm(true);",$db_opcao==1?"1":"3");
           ?>
        </td>
        <td colspan="3"> 
    <?
    db_input('rh63_numcgm',6,$Irh63_numcgm,true,'text',$db_opcao==1?"1":"3"," onchange='js_pesquisarh63_numcgm(false);'")
    ?>
           <?
    db_input('z01_nome',40,$Iz01_nome,true,'text',3,'',"z01_nomecgm")
           ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh14_matipe?>">
           <?=@$Lrh14_matipe?>
        </td>
        <td> 
    <?
    db_input('rh14_matipe',13,$Irh14_matipe,true,'text',$db_opcao,"")
    ?>
        </td>
        <td align="right" nowrap title="<?=@$Trh14_contrato?>">
           <?=@$Lrh14_contrato?>
        </td>
        <td> 
    <?
    if(!isset($rh14_contrato) || (isset($rh14_contrato) && trim($rh14_contrato) == "")){
      $rh14_contrato = 0;
    }
    db_input('rh14_contrato',8,$Irh14_contrato,true,'text',$db_opcao,"")
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh14_dtvinc?>">
           <?=@$Lrh14_dtvinc?>
        </td>
        <td> 
    <?
    db_inputdata('rh14_dtvinc',@$rh14_dtvinc_dia,@$rh14_dtvinc_mes,@$rh14_dtvinc_ano,true,'text',$db_opcao,"")
    ?>
        </td>
        <td align="right" nowrap title="<?=@$Trh14_valor?>">
           <?=@$Lrh14_valor?>
        </td>
        <td> 
    <?
    if(!isset($rh14_valor) || (isset($rh14_valor) && trim($rh14_valor) == "")){
      $rh14_valor = 0;
    }
    db_input('rh14_valor',8,$Irh14_valor,true,'text',$db_opcao,"")
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh14_estado?>">
           <?=@$Lrh14_estado?>
        </td>
        <td colspan="3"> 
    <?
    $arr_estado = Array (
                         "10" => "10 - Ativo",
                         "11" => "11 - Inativo",
                         "21" => "21 - Licenciado",
                         "22" => "22 - Lic c/ recolhimento",
                         "30" => "30 - Exonerado",
                         "31" => "31 - Falecido",
                         "39" => "39 - Pensionista"
                        );
    db_select('rh14_estado',$arr_estado,true,$db_opcao);
    ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh14_dtalt?>">
           <?=@$Lrh14_dtalt?>
        </td>
        <td colspan="3"> 
    <?
    db_inputdata('rh14_dtalt',@$rh14_dtalt_dia,@$rh14_dtalt_mes,@$rh14_dtalt_ano,true,'text',$db_opcao,"")
    ?>
        </td>
      </tr>
    </table>
    </fieldset>
    </td>
  </tr>
</table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh63_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostranome1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.rh63_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.rh63_numcgm.value+'&funcao_js=parent.js_mostranome','Pesquisa',false);
     }else{
       document.form1.z01_nomecgm.value = ''; 
     }
  }
}
function js_mostranome(erro, chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.rh63_numcgm.focus(); 
    document.form1.rh63_numcgm.value = ''; 
  }else{
    document.form1.rh62_regist.value = "";
    document.form1.z01_nome.value = "";
    document.form1.submit();
  }
}
function js_mostranome1(chave1,chave2){
  document.form1.rh63_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  document.form1.rh62_regist.value = "";
  document.form1.z01_nome.value = "";
  func_nome.hide();
  document.form1.submit();
}
function js_pesquisarh62_regist(mostra, cgm){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
  }else{
     if(cgm == true){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&chave_rh01_numcgm='+document.form1.rh63_numcgm.value+'&funcao_js=parent.js_mostrarhpessoal2|rh01_regist|z01_nome&instit=<?=db_getsession("DB_instit")?>','Pesquisa',true);
     }else if(document.form1.rh62_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?testarescisao=ra&pesquisa_chave='+document.form1.rh62_regist.value+'&funcao_js=parent.js_mostrarhpessoal&instit=<?=db_getsession("DB_instit")?>','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh62_regist.focus(); 
    document.form1.rh62_regist.value = '';
  }else{
    document.form1.rh63_numcgm.value = "";
    document.form1.z01_nomecgm.value = "";
    document.form1.submit();
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.rh62_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.rh63_numcgm.value = "";
  document.form1.z01_nomecgm.value = "";
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}
function js_mostrarhpessoal2(chave1,chave2){
  document.form1.rh62_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhipe','func_rhipe.php?depend=func&testarescisao=ra&funcao_js=parent.js_preenchepesquisa|rh14_sequencia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhipe.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
<?
if($chamarFuncao == true){
  echo "js_pesquisarh62_regist(false, true);";
}
?>
</script>