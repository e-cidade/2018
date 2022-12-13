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

$clveiccentral->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve40_veiccadcentral");

if (!isset($ve09_usuario)){
  $ve09_usuario = db_getsession("DB_id_usuario");
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="790">
 <?
db_input("sequencial",10,"",true,"hidden",3);


 ?>
<tr>
    <td nowrap title="<?=@$Tve40_veiccadcentral?>">
    <?
       db_ancora(@$Lve40_veiccadcentral,"js_pesquisave40_veiccadcentral(true);",$db_opcao);
    ?>
    </td>
    <td>
    <?
     db_input("ve40_veiccadcentral",10,$Ive40_veiccadcentral,true,"text",$db_opcao," onChange='js_pesquisave40_veiccadcentral(false);'");
     db_input("descrdepto",40,0,true,"text",3);
    ?>
    </td>
  </tr>


    <td nowrap colspan="3" height="50" align="center"> 
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==3||$db_opcao==33?"excluir":""))?>" 
          type="submit" id="db_opcao" 
          value="<?=($db_opcao==1?"Incluir":($db_opcao==3||$db_opcao==33?"Excluir":""))?>"
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
      $chavepri = array ("ve40_sequencial"=>@$ve40_sequencial);
      $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql = $clveiccentral->sql_query(null,"ve40_sequencial,ve40_veiccadcentral,ve40_veiculos",null,"ve40_veiculos = $ve09_veiculos");

       $cliframe_alterar_excluir->campos = "ve40_sequencial,ve40_veiccadcentral,ve40_veiculos";
       $cliframe_alterar_excluir->legenda = "Itens";
	     $cliframe_alterar_excluir->iframe_height = "240";
       $cliframe_alterar_excluir->iframe_width = "100%";
       $cliframe_alterar_excluir->opcoes = 3;
	     $cliframe_alterar_excluir->iframe_alterar_excluir(1);
       
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>

function js_pesquisave40_veiccadcentral(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veiccentral','db_iframe_veiccadcentral','func_veiccadcentral.php?funcao_js=parent.js_mostraveiccadcentral1|ve36_sequencial|descrdepto','Pesquisa',true);

}else{
     if(document.form1.ve40_veiccadcentral.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_veiccentral','db_iframe_veiccadcentral','func_veiccadcentral.php?pesquisa_chave='+document.form1.ve40_veiccadcentral.value+'&funcao_js=parent.js_mostraveiccadcentral','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
  
function js_mostraveiccadcentral(chave1,erro,chave2){
  document.form1.descrdepto.value = chave2; 
  if(erro==true){ 
    document.form1.ve40_veiccadcentral.focus();
    document.form1.ve40_veiccadcentral.value = '';
  }
} 
function js_mostraveiccadcentral1(chave1,chave2){
  
  document.form1.ve40_veiccadcentral.value = chave1;
  document.form1.descrdepto.value          = chave2;
  db_iframe_veiccadcentral.hide();
}



  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>

</script>