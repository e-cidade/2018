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

//MODULO: recursos humanos
$cladmissao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh37_funcao");
$clrotulo->label("rh37_descr");
$clrotulo->label("h04_descr");
$arr_SouN = array("f"=>"NAO","t"=>"SIM");


?>
<form name="form1" method="post" action="">
  <br>
  <fieldset><Legend align="left"><b>DADOS DA ADMISSÃO</b></Legend>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th07_regist?>">
       <?
       db_ancora(@$Lh07_regist,"js_pesquisah07_regist(true);",($db_opcao == 1 ? 1 : 3));
       ?>
    </td>
    <td colspan="3"> 
	   <?
		 db_input('h07_regist',6,$Ih07_regist,true,'text',($db_opcao == 1 ? 1 : 3)," onchange='js_pesquisah07_regist(false);'");
	   ?>
       <?
		 db_input('z01_nome',70,$Iz01_nome,true,'text',3,'');
		 db_input('modeloposse',10,"",true,'hidden',3);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_tipadm?>">
       <?=@$Lh07_tipadm?>
    </td>
    <td> 
<?
$arr_tipadm = Array(
                    "01"=>"01 - Por concurso público",
                    "02"=>"02 - Por prazo determinado",
                    "03"=>"03 - Sem fundamentação legal",
                    "04"=>"04 - Por decisão judicial",
                    "05"=>"05 - Reenquadramento",
                    "06"=>"06 - Transferência município-mãe",
                    "07"=>"07 - Trasnposição reg jurídico",
                    "08"=>"08 - Transferência (outro órgão)",
                    "09"=>"09 - Readaptação",
                    "10"=>"10 - Readmissão",
                    "11"=>"11 - Recondução",
                    "12"=>"12 - Reintegração",
                    "13"=>"13 - Nomeação p/ conc interno"
                   );
db_select('h07_tipadm',$arr_tipadm,true,$db_opcao,"");
?>
    </td>
    <td nowrap title="<?=@$Th07_tempor?>">
       <?=@$Lh07_tempor?>
    </td>
    <td> 
<?
if(!isset($h07_tempor) || (isset($h07_tempor) && trim($h07_tempor) == "")){
  $h07_tempor = "t";
}
db_select('h07_tempor',$arr_SouN,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_dato?>">
       <?=@$Lh07_dato?>
    </td>
    <td> 
<?
db_inputdata('h07_dato',@$h07_dato_dia,@$h07_dato_mes,@$h07_dato_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh37_funcao?>">
       <?
       db_ancora("<b>Cargo atual:</b>","",3);
       ?>
    </td>
    <td colspan="3"> 
<?
db_input('rh37_funcao',6,$Irh37_funcao,true,'text',3,"")
?>
       <?
db_input('rh37_descr',70,$Irh37_descr,true,'text',3,'',"rh37_descr2")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_cant?>">
       <?
       db_ancora(@$Lh07_cant,"js_pesquisah07_cant(true);",$db_opcao);
       ?>
    </td>
    <td colspan="3"> 
<?
db_input('h07_cant',6,$Ih07_cant,true,'text',$db_opcao,"onchange='js_pesquisah07_cant(false);'")
?>
<?
db_input('rh37_descr',70,$Irh37_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_nrato?>">
       <?=@$Lh07_nrato?>
    </td>
    <td> 
<?
db_input('h07_nrato',12,$Ih07_nrato,true,'text',$db_opcao,"")
?>
    </td>
    <td nowrap title="<?=@$Th07_nrfich?>">
       <?=@$Lh07_nrfich?>
    </td>
    <td> 
<?
db_input('h07_nrfich',6,$Ih07_nrfich,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_fundam?>">
       <?
       db_ancora(@$Lh07_fundam,"js_pesquisah07_fundam(true)",$db_opcao);
       ?>
    </td>
    <td colspan=3> 
<?
db_input('h07_fundam',6,$Ih07_fundam,true,'text',$db_opcao,"onchange='js_pesquisah07_fundam(false)'")
?>
<?
db_input('h04_descr',70,$Ih04_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_impofi?>">
       <?=@$Lh07_impofi?>
    </td>
    <td> 
<?
db_input('h07_impofi',30,$Ih07_impofi,true,'text',$db_opcao,"")
?>
    </td>
    <td nowrap title="<?=@$Th07_dpubl?>">
       <?=@$Lh07_dpubl?>
    </td>
    <td align="right"> 
<?
db_inputdata('h07_dpubl',@$h07_dpubl_dia,@$h07_dpubl_mes,@$h07_dpubl_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_ddem?>">
       <?=@$Lh07_ddem?>
    </td>
    <td> 
<?
db_inputdata('h07_ddem',@$h07_ddem_dia,@$h07_ddem_mes,@$h07_ddem_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
<!--  <tr>
//    <td nowrap title="<?//=@$Th07_ires?>">
       <?//=@$Lh07_ires?>
    </td>
    <td> 
<?
//if(!isset($h07_ires) || (isset($h07_ires) && trim($h07_ires) == "")){
//  $h07_ires = "f";
//}
//db_select('h07_ires',$arr_SouN,true,$db_opcao,"");
?>
    </td>
    <td nowrap title="<?//=@$Th07_dhist?>">
       <?//=@$Lh07_dhist?>
    </td>
    <td> 
<?
//db_inputdata('h07_dhist',@$h07_dhist_dia,@$h07_dhist_mes,@$h07_dhist_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr> -->
  <tr>
    <td nowrap title="<?=@$Th07_defet?>">
       <?=@$Lh07_defet?>
    </td>
    <td> 
<?
db_inputdata('h07_defet',@$h07_defet_dia,@$h07_defet_mes,@$h07_defet_ano,true,'text',$db_opcao,"")
?>
    </td>
    <td nowrap title="<?=@$Th07_icon?>">
       <?=@$Lh07_icon?>
    </td>
    <td> 
<?
if(!isset($h07_icon) || (isset($h07_icon) && trim($h07_icon) == "")){
  $h07_icon = "t";
}
db_select('h07_icon',$arr_SouN,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_class?>">
       <?=@$Lh07_class?>
    </td>
    <td> 
<?
db_input('h07_class',6,$Ih07_class,true,'text',$db_opcao,"")
?>
    </td>
    <td nowrap title="<?=@$Th07_termin?>">
       <?=@$Lh07_termin?>
    </td>
    <td > 
<?
db_inputdata('h07_termin',@$h07_termin_dia,@$h07_termin_mes,@$h07_termin_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_refe?>">
       <?
       db_ancora(@$Lh07_refe,"js_pesquisah07_refe(true)",$db_opcao);
       ?>
    </td>
    <td colspan="3"> 
<?
db_input('h07_refe',6,$Ih07_refe,true,'text',$db_opcao,"onchange='js_pesquisah07_refe(false)'")
?>
<?
db_input('h06_concur',70,$Ih04_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_area?>">
       <?
       db_ancora(@$Lh07_area,"js_pesquisah07_area(true)",$db_opcao);
       ?>
    </td>
    <td colspan="3"> 
<?
db_input('h07_area',6,$Ih07_area,true,'text',$db_opcao,"onchange='js_pesquisah07_area(false)'")
?>
<?
db_input('h05_descr',70,$Ih04_descr,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th07_justif?>">
       <?=@$Lh07_justif?>
    </td>
    <td colspan = 3> 
<?
db_textarea('h07_justif',2,78,$Ih07_justif,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
</fieldset>  
<table>
 <tr>
 <td>&nbsp;</td>
 </tr>
 <tr>
 <td>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<input name="imprimir"  type="button" id="imprimir"  value="Imprimir" onclick="js_imprime();" <?=( $db_opcao==2||$db_opcao==22 ?"":"disabled")?>>
</td>
</tr>
</table>
  </center>
</form>
<script>

function js_imprime(){
 
  var oVariavel   = new js_criaObjetoVariavel("$matricula",document.form1.h07_regist.value);
  var iModelo     = document.form1.modeloposse.value;
  var aParametros = new Array();
      aParametros[0] = oVariavel;
  if ( iModelo == "") {
     alert('Configurar modelo de termo de posse!');
     return false;
  }
  
  
  js_imprimeRelatorio(iModelo,js_downloadArquivo,aParametros.toSource());
   
   
}

function js_pesquisah07_area(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_areas','func_areas.php?funcao_js=parent.js_mostraareas1|h05_codigo|h05_descr','Pesquisa',true);
  }else{
     if(document.form1.h07_area.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_areas','func_areas.php?pesquisa_chave='+document.form1.h07_area.value+'&funcao_js=parent.js_mostraareas','Pesquisa',false);
     }else{
       document.form1.h05_descr.value = '';
     }
  }
}
function js_mostraareas(chave,erro){
  document.form1.h05_descr.value = chave; 
  if(erro==true){ 
    document.form1.h07_area.focus(); 
    document.form1.h07_area.value = ''; 
  }
}
function js_mostraareas1(chave1,chave2){
  document.form1.h07_area.value = chave1;
  document.form1.h05_descr.value = chave2;
  db_iframe_areas.hide();
}
function js_pesquisah07_refe(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_concur','func_concur.php?funcao_js=parent.js_mostraconcur1|h06_refer|h06_concur','Pesquisa',true);
  }else{
     if(document.form1.h07_refe.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_concur','func_concur.php?pesquisa_chave='+document.form1.h07_refe.value+'&funcao_js=parent.js_mostraconcur','Pesquisa',false);
     }else{
       document.form1.h06_concur.value = '';
     }
  }
}
function js_mostraconcur(chave,erro){
  document.form1.h06_concur.value = chave; 
  if(erro==true){ 
    document.form1.h07_refe.focus(); 
    document.form1.h07_refe.value = ''; 
  }
}
function js_mostraconcur1(chave1,chave2){
  document.form1.h07_refe.value = chave1;
  document.form1.h06_concur.value = chave2;
  db_iframe_concur.hide();
}
function js_pesquisah07_fundam(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_flegal','func_flegal.php?funcao_js=parent.js_mostraflegal1|h04_codigo|h04_descr','Pesquisa',true);
  }else{
     if(document.form1.h07_fundam.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_flegal','func_flegal.php?pesquisa_chave='+document.form1.h07_fundam.value+'&funcao_js=parent.js_mostraflegal','Pesquisa',false);
     }else{
       document.form1.h04_descr.value = '';
     }
  }
}
function js_mostraflegal(chave,erro){
  document.form1.h04_descr.value = chave; 
  if(erro==true){ 
    document.form1.h07_fundam.focus(); 
    document.form1.h07_fundam.value = ''; 
  }
}
function js_mostraflegal1(chave1,chave2){
  document.form1.h07_fundam.value = chave1;
  document.form1.h04_descr.value = chave2;
  db_iframe_flegal.hide();
}
function js_pesquisah07_cant(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhfuncao','func_rhfuncao.php?funcao_js=parent.js_mostrarhfuncao1|rh37_funcao|rh37_descr','Pesquisa',true);
  }else{
     if(document.form1.h07_cant.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhfuncao','func_rhfuncao.php?pesquisa_chave='+document.form1.h07_cant.value+'&funcao_js=parent.js_mostrarhfuncao','Pesquisa',false);
     }else{
       document.form1.rh37_descr.value = '';
     }
  }
}
function js_mostrarhfuncao(chave,erro){
  document.form1.rh37_descr.value = chave; 
  if(erro==true){ 
    document.form1.h07_cant.focus(); 
    document.form1.h07_cant.value = ''; 
  }
}
function js_mostrarhfuncao1(chave1,chave2){
  document.form1.h07_cant.value = chave1;
  document.form1.rh37_descr.value = chave2;
  db_iframe_rhfuncao.hide();
}
function js_pesquisah07_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.h07_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.h07_regist.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
       <?
       echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."'";
       ?>
     }
  }
}
function js_mostrarhpessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h07_regist.focus(); 
    document.form1.h07_regist.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_mostrarhpessoal1(chave1,chave2){
  document.form1.h07_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_admissao','func_admissao.php?funcao_js=parent.js_preenchepesquisa|h07_regist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_admissao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>