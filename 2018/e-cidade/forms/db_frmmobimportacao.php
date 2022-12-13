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
$clmobimportacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj95_codimporta?>">
       <?=@$Lj95_codimporta?>
    </td>
    <td> 
<?
db_input('j95_codimporta',8,$Ij95_codimporta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj95_pda?>">
       <?=@$Lj95_pda?>
    </td>
    <td> 
<?
$x = array('1'=>'Pda 1','2'=>'Pda 2','3'=>'Pda 3','4'=>'Pda 4','5'=>'Pda 5','6'=>'Pda 6','7'=>'Pda 7','8'=>'Pda 8','9'=>'Pda 9','10'=>'pda 10','11'=>'Pda 11','12'=>'Pda 12','13'=>'Pda 13','14'=>'Pda 14','15'=>'Pda 15');
db_select('j95_pda',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj95_data?>">
       <?=@$Lj95_data?>
    </td>
    <td> 
<?
db_inputdata('j95_data',@$j95_data_dia,@$j95_data_mes,@$j95_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj95_idusuario?>">
       <?
       db_ancora(@$Lj95_idusuario,"js_pesquisaj95_idusuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j95_idusuario',10,$Ij95_idusuario,true,'text',$db_opcao," onchange='js_pesquisaj95_idusuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaj95_idusuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.j95_idusuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.j95_idusuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.j95_idusuario.focus(); 
    document.form1.j95_idusuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.j95_idusuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_mobimportacao','func_mobimportacao.php?funcao_js=parent.js_preenchepesquisa|j95_codimporta','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_mobimportacao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>