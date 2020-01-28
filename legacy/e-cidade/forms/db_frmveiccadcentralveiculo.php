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

//MODULO: veiculos
$clveiccadcentral->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ve01_codigo");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<?
//db_input("ve01_codigo",10,0,true,"hidden",3);
db_input("sequencial", 10,0,true,"hidden",3);


?>
  <tr>
    <td nowrap title="<?=@$Tve01_codigo?>">
    </td>
    <td> 
  </td>
  </tr>
    <td nowrap title="<?=@$Tve01_codigo?>">
       <?
       db_ancora(@$Lve01_codigo,"js_pesquisave01_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve01_codigo',10,$Ive01_codigo,true,'text',$db_opcao," onchange='js_pesquisave01_codigo(false);'");

db_input('ve01_placa',10,$Ive01_codigo,true,'text',3,'');

db_input('ve22_descr',40,$Ive01_codigo,true,'text',3,'');
       ?>
    </td>
  </tr>


 <tr>
  
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
       $dbwhere = "";
       if (isset($ve41_veiccadcentral) && trim($ve41_veiccadcentral)!=""){
         $dbwhere = " ve36_sequencial = $ve41_veiccadcentral";
       }

       $chavepri = array ("ve40_sequencial"=>@$ve41_veiccadcentral);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql = $clveiccentral->sql_query(null,"ve40_sequencial,ve40_veiccadcentral,ve40_veiculos,ve01_placa,ve22_descr",null,"$dbwhere");
       $cliframe_alterar_excluir->campos = "ve40_sequencial,ve40_veiccadcentral,ve40_veiculos,ve01_placa,ve22_descr";
       $cliframe_alterar_excluir->legenda = "Veiculos";
       $cliframe_alterar_excluir->opcoes = 3;
       $cliframe_alterar_excluir->iframe_height = "240";
       $cliframe_alterar_excluir->iframe_width = "100%";
       $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
  </tr>

</center>
</table>
</form>
<script>

function js_pesquisave01_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_veiccadcentralveiculo','db_iframe_veiculos','func_veiculos.php?funcao_js=parent.js_preenchepesquisa01|ve01_codigo|ve01_placa|ve22_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.ve01_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_veiccadcentralveiculo','db_iframe_veiculos','func_veiculos.php?pesquisa_chave='+document.form1.ve01_codigo.value+'&funcao_js=parent.js_mostraveiccentral','Pesquisa',false);
     }else{
       document.form1.ve01_placa.value = ''; 
       document.form1.ve22_descr.value = ''; 
     }
  }
}
function js_mostraveiccentral(erro,chave1,chave2,chave3){
  if (erro==false){
    document.form1.ve01_placa.value = chave2;
    document.form1.ve22_descr.value = chave3;
  }

  if(erro==true){ 
    document.form1.ve01_codigo.focus(); 
    document.form1.ve01_codigo.value = ""; 
  }
}
function js_preenchepesquisa01(chave1,chave2,chave3){
    document.form1.ve01_codigo.value = chave1;
    document.form1.ve01_placa.value = chave2;
    document.form1.ve22_descr.value = chave3;
    db_iframe_veiculos.hide();
      <?
        if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
         }
      ?>
}





</script>