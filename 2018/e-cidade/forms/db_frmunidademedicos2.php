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

//MODULO: saude
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clunidademedicos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");



$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd04_i_codigo?>">
       <?=@$Lsd04_i_codigo?>
    </td>
    <td>
       <?
         db_input('sd04_i_codigo',5,$Isd04_i_codigo,true,'text',3,"")
        ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd04_i_unidade?>">
       <?
       db_ancora(@$Lsd04_i_unidade,"js_pesquisasd04_i_unidade(true);",3);
       ?>
    </td>
    <td>
<?
db_input('sd04_i_unidade',10,$Isd04_i_unidade,true,'text',3," onchange='js_pesquisasd04_i_unidade(false);'")
?>
       <?
db_input('descrdepto',80,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd04_i_medico?>">
       <?
       db_ancora(@$Lsd04_i_medico,"js_pesquisasd04_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',$db_opcao," onchange='js_pesquisasd04_i_medico(false);'")
?>
       <?
db_input('z01_nome',80,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
      <td nowrap title="<?=@$Tsd04_i_medico?>">
              <?=$Lsd04_c_situacao?>
      </td>
      <td>
              <?
                 $x = array("A"=>"Ativo","D"=>"Desativado");
                 db_select('sd04_c_situacao',$x,true,$db_opcao, "","",2);
              ?>

     </td>
  </tr>
  </table>
 </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<table>
 <tr>
  <td valign="top"><br>
  <?
   $x = "<script>parent.iframe_a1.document.form1.sd04_i_unidade.value</script>";
   $x = $sd04_i_unidade;
   $chavepri= array("sd04_i_codigo"=>@$sd04_i_codigo,"sd04_i_unidade"=>@$sd04_i_unidade,"sd04_i_medico"=>@$sd04_i_medico,"z01_nome"=>@$z01_nome, "sd04_c_situacao"=>@$sd04_c_situacao);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clunidademedicos->sql_query($sd04_i_codigo,"sd04_i_codigo,sd04_i_unidade,sd04_i_medico,z01_nome,sd04_c_situacao","z01_nome","sd04_i_unidade = $x" );
   $cliframe_alterar_excluir->campos  ="sd04_i_medico,z01_nome,sd04_c_situacao";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="650";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->opcoes = 2;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
<script>
function js_pesquisasd04_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_unidade.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.sd04_i_unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = '';
     }
  }
}
function js_mostraunidades(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.sd04_i_unidade.focus();
    document.form1.sd04_i_unidade.value = '';
  }
}
function js_mostraunidades1(chave1,chave2){
  document.form1.sd04_i_unidade.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_unidades.hide();
}
function js_pesquisasd04_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.sd04_i_medico.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd04_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.sd04_i_medico.focus();
    document.form1.sd04_i_medico.value = '';
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd04_i_medico.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_medicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_preenchepesquisa|sd04_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_unidademedicos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

document.form1.sd04_i_unidade.value = parent.iframe_a1.document.form1.sd04_i_unidade.value;
document.form1.descrdepto.value = parent.iframe_a1.document.form1.descrdepto.value;

</script>