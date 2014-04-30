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

//MODULO: caixa
$clbancoaplic->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k13_descr");
$clrotulo->label("k02_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk90_id?>">
       <?=@$Lk90_id?>
    </td>
    <td> 
<?
db_input('k90_id',10,$Ik90_id,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_conta?>">
       <?
       db_ancora(@$Lk90_conta,"js_pesquisak90_conta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k90_conta',10,$Ik90_conta,true,'text',$db_opcao," onchange='js_pesquisak90_conta(false);'");
?>
<?
db_input('k13_descr',40,$Ik13_descr,true,'text',3,"");

?>

  <tr>
    <td nowrap title="<?=@$Tk02_codigo?>">   
<?
    db_ancora(@$Lk02_codigo,"js_pesquisatabrec(true);",$db_opcao);
?>
    </td>
    <td> 
<?
    db_input('k02_codigo',10,$Ik02_codigo,true,'text',$db_opcao," onchange='js_pesquisatabrec(false);'");
?>
<?
db_input('k02_drecei',40,$Ik02_drecei,true,'text',3);
/*
coalesce é função para retornar 0 qdo for null
*/
if(isset($k90_conta)){  
  $sql = "select coalesce(k90_cpsldaplic,0) as saldo_anterior_cp 
          from bancoaplic 
	  where k90_conta=$k90_conta and k90_codreceita=$k02_codreceita order by k90_data desc limit 1";
  $rs  = pg_query($sql);
  $sldantcp = @pg_result($rs,0,'saldo_anterior_cp');
  $sql               = "select coalesce(k90_pfsldaplicf,0) as saldo_anterior_pf 
                      from bancoaplic 
		      where k90_conta=$k90_conta and k90_codreceita=$k02_codreceita order by k90_data desc limit 1";
  $rs                = pg_query($sql);
  $k90_pfsldaplicant = @pg_result($rs,0,'saldo_anterior_pf');
}
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_data?>">
       <?=@$Lk90_data?>
    </td>
    <td> 
<?
if(!isset($k90_data)){  // fazer data - 1dia
  $k90_data_dia = date("d",db_getsession("DB_datausu"));
  $k90_data_mes = date("m",db_getsession("DB_datausu"));
  $k90_data_ano = date("YYYY",db_getsession("DB_datausu"));
}
db_inputdata('k90_data',@$k90_data_dia,@$k90_data_mes,@$k90_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
   <!-- inicando tag fieldset(frame);-->
   <tr>
      <td colspan='2'><fieldset><legend><b>Curto Prazo</b></legend><table>
    <td  nowrap title="<?'Saldo anterior'?>">
       <b> <?='Saldo anterior:'?> </b>
    </td>
    <td>
<?
db_input('sldantcp',17,'',true,'text',$db_opcao,"");
?>
    </td>
   </tr>

   <tr>
    <td nowrap title="<?=@$Tk90_cpvlraplic?>">
       <?=@$Lk90_cpvlraplic?>
    </td>
    <td> 
<?
db_input('k90_cpvlraplic',17,$Ik90_cpvlraplic,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_cpvlrresg?>">
       <?=@$Lk90_cpvlrresg?>
    </td>
    <td> 
<?
db_input('k90_cpvlrresg',17,$Ik90_cpvlrresg,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_cpsldaplic?>">
       <?=@$Lk90_cpsldaplic?>
    </td>
    <td> 
<?
db_input('k90_cpsldaplic',17,$Ik90_cpsldaplic,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
   <!-- fechando tag fieldset(frame);-->
  </table></fieldset></td>

   <tr>
      <td colspan='2'><fieldset><legend><b>Prazo Fixo</b></legend><table>
    <td nowrap title="<?=@$Tk90_pfsldaplicant?>">
       <?=@$Lk90_pfsldaplicant?>
    </td>
    <td> 
<?
db_input('k90_pfsldaplicant',17,$Ik90_pfsldaplicant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_pfvlraplic?>">
       <?=@$Lk90_pfvlraplic?>
    </td>
    <td> 
<?
db_input('k90_pfvlraplic',17,$Ik90_pfvlraplic,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_pfvlrresg?>">
       <?=@$Lk90_pfvlrresg?>
    </td>
    <td> 
<?
db_input('k90_pfvlrresg',17,$Ik90_pfvlrresg,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_pfsldaplicf?>">
       <?=@$Lk90_pfsldaplicf?>
    </td>
    <td> 
<?
db_input('k90_pfsldaplicf',17,$Ik90_pfsldaplicf,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table></fieldset></td>

   <tr>
  <td  nowrap title="<?'Juros do dia'?>">
     <b> <?='Juros do dia:'?> </b>
    </td>
    <td>
<?
db_input('vlrjuros',17,'',true,'text',$db_opcao,"")
?>
    </td>
   </tr>

  <tr>
    <td nowrap title="<?=@$Tk90_vlrdisp?>">
       <?=@$Lk90_vlrdisp?>
    </td>
    <td> 
<?
db_input('k90_vlrdisp',17,$Ik90_vlrdisp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk90_sldtot?>">
       <?=@$Lk90_sldtot?>
    </td>
    <td> 
<?
db_input('k90_sldtot',17,$Ik90_sldtot,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="calcular" type="button" id="calcular" value="Calcular" onclick="js_calcula();" >
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> 
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_calcula(){
  cp_sldaplic    = eval("document.form1.k90_cpsldaplic.value");
  cp_sldant      = eval("document.form1.sldantcp.value");
  cp_vlraplic    = eval("document.form1.k90_cpvlraplic.value");
  cp_vlrresg     = eval("document.form1.k90_cpvlrresg.value");

  pf_sldaplicf   = eval("document.form1.k90_pfsldaplicf.value");
  pf_vlrresg     = eval("document.form1.k90_pfvlrresg.value");
  pf_vlraplic    = eval("document.form1.k90_pfvlraplic.value");
  pf_sldaplicant = eval("document.form1.k90_pfsldaplicant.value");

  vlrdispcc      = eval("document.form1.k90_vlrdisp.value");

  juros1         =  (cp_sldaplic-(cp_sldant+cp_vlraplic-cp_vlrresg));
  juros2         =  (pf_sldaplicf-(pf_sldaplicant+pf_vlraplic-pf_vlrresg));

  document.form1.vlrjuros.value   = (juros1+juros2);
  document.form1.k90_sldtot.value = (cp_sldaplic+pf_sldaplicf+vlrdispcc);
}
function js_pesquisak90_conta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
     if(document.form1.k90_conta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k90_conta.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.k90_conta.focus(); 
    document.form1.k90_conta.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k90_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
  // colocar na function da receita
  //location.href='cai1_bancoaplic001.php?k90_conta='+chave1+'&k13_descr='+chave2;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bancoaplic','func_bancoaplic.php?funcao_js=parent.js_preenchepesquisa|k90_id','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bancoaplic.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_pesquisatabrec(mostra){
     if(mostra==true){
       func_iframe.jan.location.href = 'func_tabrec.php?funcao_js=parent.js_mostratabrec1|0|3';
       func_iframe.setLargura(770);
       func_iframe.setAltura(430);
       func_iframe.mostraMsg();
       func_iframe.show();
       func_iframe.focus();
     }else{
       func_iframe.jan.location.href = 'func_tabrec.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec';
     }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave;
  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     func_iframe.hide();
}
</script>