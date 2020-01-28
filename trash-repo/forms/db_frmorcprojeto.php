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

//MODULO: orcamento
$clorcprojeto->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o45_numlei");
?>
<form name="form1" method="post" action="">
<center>
<table border=0 style="border:1px solid #999999 ">
<tr>
<td valign=top>
    
    <table border="0">
    <tr>
      <td nowrap title="<?=@$To39_anousu?>"><?=@$Lo39_anousu?></td>
      <td><? $o39_anousu = db_getsession('DB_anousu');
              db_input('o39_anousu',4,$Io39_anousu,true,'text',3,"") ?>
      </td>
    </tr>

    <tr>
       <td nowrap title="<?=@$To39_codproj?>"><?=@$Lo39_codproj?></td>
       <td><? db_input('o39_codproj',8,$Io39_codproj,true,'text',3,"")?></td>
    </tr>
    <tr>
      <td nowrap title="<?=@$To39_descr?>"><?=@$Lo39_descr?></td>
      <td><? db_textarea('o39_descr',0,35,$Io39_descr,true,'text',$db_opcao,"") ?></td>
    </tr>
   <tr>
    <td nowrap title="<?=@$To39_codlei?>"><?db_ancora(@$Lo39_codlei,"js_pesquisao39_codlei(true);",$db_opcao);?></td>
    <td> 
       <? db_input('o39_codlei',8,$Io39_codlei,true,'text',$db_opcao," onchange='js_pesquisao39_codlei(false);'")?>
       <? db_input('o45_numlei',30,$Io45_numlei,true,'text',3,'')     ?>
    </td>
   </tr>
  <tr>
    <td nowrap title="<?=@$To39_tipoproj?>">
       <?=@$Lo39_tipoproj?>
    </td>
    <td> 
      <?  // $x = array('1'=>'DECRETO','2'=>'LEI','3'=>'PROJETO RETIFICADOR');
          $x = array('1'=>'DECRETO');
          if (!isset($o39_tipoproj)) {
            $o39_tipoproj = '1';
          }
          db_select('o39_tipoproj',$x,true,3,"");     ?>
    </td>
    </tr>
    <tr>
    <td nowrap title="<?=@$To39_usalimite?>">
       <?=@$Lo39_usalimite?>
    </td>
    <td> 
      <?  // $x = array('1'=>'DECRETO','2'=>'LEI','3'=>'PROJETO RETIFICADOR');
          $x = array('0'=> 'Nenhum','f'=>'Não','t'=>'Sim');
          db_select('o39_usalimite',$x,true,$db_opcao,"");     ?>
    </td>
    </tr>
 </table>
</td>
<td>
  <table border=0>
     <tr><td align=left colspan=2><fieldset><b>Decreto</b></fieldset></td></tr>
     <tr><td nowrap title="<?=@$To39_numero?>"><?=@$Lo39_numero?></td>
         <td><? db_input('o39_numero',22,$Io39_numero,true,'text',$db_opcao,"") ?> </td>
     </tr>
     <tr>
      <td nowrap title="<?=@$To39_data?>"><?=@$Lo39_data?> </td>
      <td><? db_inputdata('o39_data',@$o39_data_dia,@$o39_data_mes,@$o39_data_ano,true,'text',$db_opcao,"")?> </td>
     </tr>

     <tr><td colspan=2> &nbsp; </td></tr>

     <tr style='display: none'><td align=left colspan=2><fieldset><b>Lei </b></fieldset></td></tr>
     <tr style='display: none'>
        <td nowrap title="<?=@$To39_lei?>"><?=@$Lo39_lei?></td>
        <td><? db_input('o39_lei',22,$Io39_lei,true,'text',$db_opcao,"") ?> </td>
     </tr>
     <tr style='display: none'>
        <td nowrap title="<?=@$To39_leidata?>"><?=@$Lo39_leidata?> </td>
        <td><? db_inputdata('o39_leidata',@$o39_leidata_dia,@$o39_leidata_mes,@$o39_leidata_ano,true,'text',$db_opcao,"")?> </td>
     </tr>

     <tr><td colspan=2> &nbsp; </td></tr>

     <tr><td align=left colspan=2><fieldset><b>Informações </b></fieldset></td></tr>
     <tr>
        <td nowrap ><b>Data de Processamento</b></td>
        <td><? db_inputdata('o51_data',@$o51_data_dia,@$o51_data_mes,@$o51_data_ano,true,'text',3,"")?> </td>
     </tr>
     <tr>
        <td nowrap ><b>Usuario </b></td>
        <td><? db_input('nome',28,'',true,'text',3,"")?> </td>
     </tr>


  </table>
</td>
</tr>
<tr valign=botton >
  <td colspan=1 align=center>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
            onclick='return js_validalimite()' type="submit" id="db_opcao" 
            value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
            <?=($db_botao==false?"disabled":"")?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
   </td>
</tr> 


</table>

</center>
</form>
<script>
function js_validalimite() {
  
  if (document.getElementById('o39_usalimite').value == '0') {
  
    alert('Informe se o Decreto usa o limite definido na LOA.');
    return false;
  } else {
    return true;
  }
}
function js_pesquisao39_codlei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_projeto',
                        'db_iframe_orclei',
                        'func_orclei.php?funcao_js=parent.js_mostraorclei1|o45_codlei|o45_numlei&leimanual=1',
                        'Pesquisa',true);
  }else{
     if(document.form1.o39_codlei.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_projeto', 
                            'db_iframe_orclei',
                            'func_orclei.php?pesquisa_chave='+
                            document.form1.o39_codlei.value+
                            '&funcao_js=parent.js_mostraorclei','Pesquisa',false);
     }else{
       document.form1.o45_numlei.value = ''; 
     }
  }
}
function js_mostraorclei(chave,erro){
  document.form1.o45_numlei.value = chave; 
  if(erro==true){ 
    document.form1.o39_codlei.focus(); 
    document.form1.o39_codlei.value = ''; 
  }
}
function js_mostraorclei1(chave1,chave2){
  document.form1.o39_codlei.value = chave1;
  document.form1.o45_numlei.value = chave2;
  db_iframe_orclei.hide();
}
function js_pesquisa(){
  <?
 //  if($db_opcao==22){
     echo "js_OpenJanelaIframe('top.corpo.iframe_projeto','db_iframe_orcprojeto','func_orcprojeto001.php?funcao_js=parent.js_preenchepesquisa|o39_codproj','Pesquisa',true);";
 // }else {
 //    echo "js_OpenJanelaIframe('top.corpo','db_iframe_orcprojeto','func_orcprojeto.php?funcao_js=parent.js_preenchepesquisa|o39_codproj','Pesquisa',true);";
 // }
  ?>
}
function js_preenchepesquisa(chave){
  db_iframe_orcprojeto.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>