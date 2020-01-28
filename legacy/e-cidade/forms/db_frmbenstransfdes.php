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

//MODULO: patrim
$clbenstransfdes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tt94_codtran?>">
       <?=(@$Lt94_codtran)?>
    </td>
    <td> 
<?
db_input('t94_codtran',8,$It94_codtran,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tt94_depart?>">
       <?
       db_ancora(@$Lt94_depart,"js_pesquisat94_depart(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('t94_depart',5,$It94_depart,true,'text',$db_opcao," onchange='js_pesquisat94_depart(false);'")
?>
<?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
<input name="<?=($db_opcao==1?"incluir":"alterar")?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":"Alterar")?>" <?=($db_botao==false?"disabled":"")?>>
<input name="excluir" type="submit" id="db_opcao" value="Excluir" <?=(($db_opcao==1||$db_opcao==22||$db_opcao==33)?"disabled":"")?>>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisat94_depart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_benstransfdes','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.t94_depart.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_benstransfdes','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.t94_depart.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.t94_depart.focus(); 
    document.form1.t94_depart.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.t94_depart.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
</script>