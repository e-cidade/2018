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
$clcaitransfseq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k91_descr");
$clrotulo->label("nome");




?>
<form name="form1" method="post" action="">
<center>
<br><br><br><br><br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk94_seqtransf?>"><?=@$Lk94_seqtransf?> </td>
    <td><? db_input('k94_seqtransf',10,$Ik94_seqtransf,true,'text',3,"") ?></td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tk94_transf?>"><? db_ancora(@$Lk94_transf,"",3); ?> </td>
    <td> 
       <?       
         $record = $clcaitransf->sql_record($clcaitransf->sql_query(null,'k91_transf,k91_descr'));         
         db_selectrecord("k94_transf",$record,'true',1);      

       ?>
    </td>
  </tr>


  
  <tr>
    <td nowrap title="<?=@$Tk94_data?>">
       <?=@$Lk94_data?>
    </td>
    <td> 
      <? db_inputdata('k94_data',@$k94_data_dia,@$k94_data_mes,@$k94_data_ano,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk94_valor?>">
       <?=@$Lk94_valor?>
    </td>
    <td> 
       <? db_input('k94_valor',20,$Ik94_valor,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk94_finalidade?>">
       <?=@$Lk94_finalidade?>
    </td>
    <td> 
       <? db_textarea('k94_finalidade',0,50,$Ik94_finalidade,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  </table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisak94_transf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_caitransf','func_caitransf.php?funcao_js=parent.js_mostracaitransf1|k91_transf|k91_descr','Pesquisa',true);
  }else{
     if(document.form1.k94_transf.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_caitransf','func_caitransf.php?pesquisa_chave='+document.form1.k94_transf.value+'&funcao_js=parent.js_mostracaitransf','Pesquisa',false);
     }else{
       document.form1.k91_descr.value = ''; 
     }
  }
}
function js_mostracaitransf(chave,erro){
  document.form1.k91_descr.value = chave; 
  if(erro==true){ 
    document.form1.k94_transf.focus(); 
    document.form1.k94_transf.value = ''; 
  }
}
function js_mostracaitransf1(chave1,chave2){
  alert('oi');
 //  document.form1.k94_transf.value = chave1;
//  document.form1.k91_descr.value = chave2;
//  db_iframe_caitransf.hide();
}
</script>