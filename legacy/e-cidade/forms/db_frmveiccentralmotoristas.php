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

$clveicmotoristascentral->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
?>
<form name="form1" method="post" action="">
<?
   if ($db_opcao != 1){
     $db_tranca = 3;
   } else {
     $db_tranca = $db_opcao;
   }

   db_input("ve41_veicmotoristas",10,0,true,"hidden",3);
   db_input("sequencial",         10,0,true,"hidden",3);
?>
<center>
<table border="0" width="790">
  <tr>
    <td nowrap align="right" title="<?=@$Tve41_veicmotoristas?>">
    <? 
       db_ancora(@$Lve41_veiccadcentral,"js_pesquisave41_veiccadcentral(true);",$db_tranca) 
    ?>
    </td>
    <td nowrap width="10%">
    <? 
       db_input('ve41_veiccadcentral',10,$Ive41_veiccadcentral,true,'text',$db_tranca,"onChange='js_pesquisave41_veiccadcentral(false);'"); 
    ?>
    </td>
    <td nowrap>
    <? 
       db_input('descrdepto',40,0,true,'text',3); 
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap align="right" title="<?=@$Tve41_dtini?>"><?=@$Lve41_dtini?></td>
    <td nowrap>
    <?
       db_inputdata('ve41_dtini',@$ve41_dtini_dia,@$ve41_dtini_mes,@$ve41_dtini_ano,true,"text",$db_tranca,"")
    ?>
    </td>
    <td nowrap colspan="2" align="left" title="<?=@$Tve41_dtfim?>"><?=@$Lve41_dtfim?>
    <?
       db_inputdata('ve41_dtfim',@$ve41_dtfim_dia,@$ve41_dtfim_mes,@$ve41_dtfim_ano,true,"text",$db_opcao,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="3" height="50" align="center"> 
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
          type="submit" id="db_opcao" 
          value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
          <?=($db_botao==false?"disabled":"")?> 
       >
       <?
          if ($db_opcao != 1) {
       ?>
         <input name="novo" id="novo" type="submit" value="Novo">
       <?
          }
       ?>
    </td>
  </tr>	
  <tr>
    <td nowrap colspan="3">
    <?
       $dbwhere = "";
       if (isset($ve41_veicmotoristas) && trim($ve41_veicmotoristas)!=""){
         $dbwhere = "ve41_veicmotoristas = $ve41_veicmotoristas";
       }

	     $chavepri = array ("ve41_sequencial"=>@$ve41_sequencial);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql = $clveicmotoristascentral->sql_query(null,"ve41_sequencial,ve41_veiccadcentral,ve41_dtini,ve41_dtfim,descrdepto",null,"$dbwhere");
       $cliframe_alterar_excluir->campos = "ve41_sequencial,ve41_veiccadcentral,descrdepto,ve41_dtini,ve41_dtfim";
       $cliframe_alterar_excluir->legenda = "Centrais";
	     $cliframe_alterar_excluir->iframe_height = "240";
       $cliframe_alterar_excluir->iframe_width  = "100%";
       $cliframe_alterar_excluir->opcoes        = 2;
	     $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisave41_veiccadcentral(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veicmotoristascentral','db_iframe_veiccadcentral','func_veiccadcentral.php?funcao_js=parent.js_mostraveiccadcentral1|ve36_sequencial|descrdepto','Pesquisa',true,'0');
  }else{
     if(document.form1.ve41_veiccadcentral.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_veicmotoristascentral','db_iframe_veiccadcentral','func_veiccadcentral.php?pesquisa_chave='+document.form1.ve41_veiccadcentral.value+'&funcao_js=parent.js_mostraveiccadcentral','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostraveiccadcentral(chave1,erro,chave2){
  if (erro==false){
    document.form1.descrdepto.value = chave2; 
  }

  if(erro==true){ 
    document.form1.ve41_veiccadcentral.focus(); 
    document.form1.ve41_veiccadcentral.value = ""; 
  }
}
function js_mostraveiccadcentral1(chave1,chave2){
  document.form1.ve41_veiccadcentral.value = chave1;
  document.form1.descrdepto.value          = chave2;

  db_iframe_veiccadcentral.hide();
}
</script>