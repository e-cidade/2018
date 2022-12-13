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

//MODULO: cadastro
$clmoblevantamentoedi->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j95_pda");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj96_sequen?>">
       <?=@$Lj96_sequen?>
    </td>
    <td> 
<?
db_input('j96_sequen',8,$Ij96_sequen,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_codimporta?>">
       <?
       db_ancora(@$Lj96_codimporta,"js_pesquisaj96_codimporta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j96_codimporta',8,$Ij96_codimporta,true,'text',$db_opcao," onchange='js_pesquisaj96_codimporta(false);'")
?>
       <?
db_input('j95_pda',3,$Ij95_pda,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_matric?>">
       <?=@$Lj96_matric?>
    </td>
    <td> 
<?
db_input('j96_matric',8,$Ij96_matric,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_codigo?>">
       <?=@$Lj96_codigo?>
    </td>
    <td> 
<?
db_input('j96_codigo',8,$Ij96_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_numero?>">
       <?=@$Lj96_numero?>
    </td>
    <td> 
<?
db_input('j96_numero',10,$Ij96_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_compl?>">
       <?=@$Lj96_compl?>
    </td>
    <td> 
<?
db_input('j96_compl',50,$Ij96_compl,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_paredes?>">
       <?=@$Lj96_paredes?>
    </td>
    <td> 
<?
$x = array('14'=>'Metálica','15'=>'Alvenaria','16'=>'Mista','17'=>'Madeira','18'=>'Compensado)','19'=>'Nenhuma');
db_select('j96_paredes',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_cobertura?>">
       <?=@$Lj96_cobertura?>
    </td>
    <td> 
<?
$x = array('20'=>'Especial','21'=>'Lage/Concreto','22'=>'Telha Colonial','23'=>'Telha Francesa','24'=>'Fibra de Cimento','25'=>'Zinco/Papelão/Pau');
db_select('j96_cobertura',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_revexterno?>">
       <?=@$Lj96_revexterno?>
    </td>
    <td> 
<?
$x = array('26'=>'Especial','27'=>'Reboco','28'=>'Sem Reboco');
db_select('j96_revexterno',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_esquadrias?>">
       <?=@$Lj96_esquadrias?>
    </td>
    <td> 
<?
$x = array('29'=>'Aluminio/Vidro Temperado','30'=>'Madeira/Ferro','31'=>'Tampões');
db_select('j96_esquadrias',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_forro?>">
       <?=@$Lj96_forro?>
    </td>
    <td> 
<?
$x = array('32'=>'Lage/Concreto','33'=>'Chapas/Compensado/PVC','34'=>'Madeira','35'=>'Compensado','36'=>'Nenhuma');
db_select('j96_forro',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_pintura?>">
       <?=@$Lj96_pintura?>
    </td>
    <td> 
<?
$x = array('37'=>'Óleo/PVA','38'=>'Salpique','39'=>'Caiação','40'=>'Nenhuma');
db_select('j96_pintura',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_piso?>">
       <?=@$Lj96_piso?>
    </td>
    <td> 
<?
$x = array('41'=>'Especial','42'=>'Material Vinílico','43'=>'Parquê/Cerâmica','44'=>'Lajota','45'=>'Madeira','46'=>'Bruto','47'=>'Nenhum');
db_select('j96_piso',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_revinterno?>">
       <?=@$Lj96_revinterno?>
    </td>
    <td> 
<?
$x = array('48'=>'Massa Corrida','49'=>'Madeira','50'=>'Reboco Siples','51'=>'Nenhum');
db_select('j96_revinterno',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_instsanitario?>">
       <?=@$Lj96_instsanitario?>
    </td>
    <td> 
<?
$x = array('52'=>'Mais de uma Interna','53'=>'Interna','54'=>'Externa','55'=>'Latrina','56'=>'Nenhuma');
db_select('j96_instsanitario',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_insteletrica?>">
       <?=@$Lj96_insteletrica?>
    </td>
    <td> 
<?
$x = array('57'=>'Embutida','58'=>'Mista','59'=>'Exposta','60'=>'Nenhuma');
db_select('j96_insteletrica',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_idade?>">
       <?=@$Lj96_idade?>
    </td>
    <td> 
<?
$x = array('61'=>'Idade (0-03)','62'=>'Idade (04-06)','63'=>'idade (07-09)','64'=>'Idade (10-12)','65'=>'Idade (13-15)','66'=>'Idade (16-18)','67'=>'Idade (19-21)','68'=>'Idade (22-24)','69'=>'Idade (25-27)','70'=>'Idade (28-30)','71'=>'Idade (+ 30)');
db_select('j96_idade',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_tipoconstr?>">
       <?=@$Lj96_tipoconstr?>
    </td>
    <td> 
<?
$x = array('100'=>'CASA/SOBRADO','101'=>'LOJA','102'=>'SALA/CONJUNTO','103'=>'APARTAMENTO','104'=>'OUTROS');
db_select('j96_tipoconstr',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj96_subtitulo?>">
       <?=@$Lj96_subtitulo?>
    </td>
    <td> 
<?
$x = array('72'=>'Superposta','73'=>'Isolada','74'=>'Semi-Isolada','75'=>'Conjugada','76'=>'Geminada','77'=>'Qualquer','78'=>'Pavilhão','79'=>'Indústria','80'=>'Especial','81'=>'Galpão','82'=>'Garagem','83'=>'Telheiro','84'=>'Anexo');
db_select('j96_subtitulo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj96_codimporta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?funcao_js=parent.js_mostramobimportacao1|j95_codimporta|j95_pda','Pesquisa',true);
  }else{
     if(document.form1.j96_codimporta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?pesquisa_chave='+document.form1.j96_codimporta.value+'&funcao_js=parent.js_mostramobimportacao','Pesquisa',false);
     }else{
       document.form1.j95_pda.value = ''; 
     }
  }
}
function js_mostramobimportacao(chave,erro){
  document.form1.j95_pda.value = chave; 
  if(erro==true){ 
    document.form1.j96_codimporta.focus(); 
    document.form1.j96_codimporta.value = ''; 
  }
}
function js_mostramobimportacao1(chave1,chave2){
  document.form1.j96_codimporta.value = chave1;
  document.form1.j95_pda.value = chave2;
  db_iframe_mobimportacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_moblevantamentoedi','func_moblevantamentoedi.php?funcao_js=parent.js_preenchepesquisa|j96_sequen','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_moblevantamentoedi.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>