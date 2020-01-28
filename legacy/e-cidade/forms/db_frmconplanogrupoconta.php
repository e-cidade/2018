<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clconplano->rotulo->label();

$sNomeLookup = "func_conplanogeral.php";
if (USE_PCASP) {
  $sNomeLookup = "func_conplanoorcamento.php";
}
$clrotulo = new rotulocampo;
$clrotulo->label("c52_descr");
$clrotulo->label("c61_reduz");
$clrotulo->label("c51_descr");
$clrotulo->label("codigo");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("c90_estrutsistema");
$clrotulo->label("c64_descr");
?>
<form name="form1" method="post" action="">
<?
   db_input("c21_congrupo",10,"",true,"hidden",3);
   db_input("sequencial",    10,0,true,"hidden",3);
?>
<center>
<table border="0" width="790">
  <tr>
    <td nowrap title="<?=@$Tc60_codcon?>"><? db_ancora(@$Lc60_codcon,"js_pesquisac60_codcon(true);",$db_opcao) ?></td>
    <td nowrap><? db_input('c60_codcon',6,$Ic60_codcon,true,'text',$db_opcao,"onChange='js_pesquisac60_codcon(false);'"); ?>&nbsp;&nbsp;
    <b>Ano:</b><? db_input("anousu",4,"",true,"text",3); ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc60_estrut?>"><?=@$Lc60_estrut?></td>
    <td nowrap><?
           db_input("c60_estrut",18,"",true,"text",3);
    ?>  
    </td>
  </tr>
	<tr>
	  <td width="120" nowrap title="<?=@$Tc60_descr?>"><?=@$Lc60_descr?></td>
	  <td nowrap><? db_input('c60_descr',52,$Ic60_descr,true,'text',3,"")?></td>
	</tr>
	<tr>
	  <td nowrap title="<?=@$Tc60_codsis?>"><?=@$Lc60_codsis?></td>
	  <td nowrap><? 
           db_input('c60_codsis',4,$Ic60_codsis,true,'text',3,"");
 	         db_input('c52_descr',46,@$Ic52_descr,true,'text',3,"");
        ?>
	  </td>
	</tr>
	<tr>
	  <td nowrap title="<?=@$Tc60_codcla?>"><?=@$Lc60_codcla?></td>
	  <td nowrap><?
          db_input('c60_codcla',4,$Ic60_codcla,true,'text',3,"");
          db_input('c51_descr',46,@$Ic51_descr,true,'text',3,"");
	     ?>
	  </td>
	</tr>
  <tr>
    <td nowrap colspan="2" height="50" align="center"> 
      <input
          name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
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
    <td nowrap colspan="2">
    <?
       if (!isset($c21_anousu)){
         $c21_anousu = $anousu;
       }

	     $chavepri = array ("c21_sequencial"=>@$c21_sequencial,"c21_anousu"=>$c21_anousu,"c21_congrupo"=>$c21_congrupo);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->sql = $clconplanogrupo->sql_query(null,"*","c60_estrut","c21_congrupo=$c21_congrupo and c21_anousu=$c21_anousu");
       $cliframe_alterar_excluir->campos = "c21_codcon,c21_anousu,c60_estrut,c60_descr,c60_codsis,c52_descr,c60_codcla,c51_descr";
       $cliframe_alterar_excluir->legenda = "Contas";
	     $cliframe_alterar_excluir->iframe_height = "240";
       $cliframe_alterar_excluir->iframe_width = "100%";
	     $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_pesquisac60_codcon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conplano','<?=$sNomeLookup;?>?funcao_js=parent.js_mostraconta1|c60_codcon|c60_descr|c60_estrut|DB_codsis|c52_descr|DB_codcla|c51_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.c60_codcon.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conplano','<?=$sNomeLookup;?>?chave_c60_codcon='+document.form1.c60_codcon.value+'&funcao_js=parent.js_mostraconta&ret_congrupo=true','Pesquisa',false);
     }else{
       document.form1.c60_descr.value = ''; 
     }
  }
}
function js_mostraconta(chave1,chave2,chave3,chave4,chave5,chave6,erro){
  document.form1.c60_descr.value = chave1; 

  if (erro==false){
    document.form1.c60_estrut.value = chave2;
    document.form1.c60_codsis.value = chave3;
    document.form1.c52_descr.value  = chave4;
    document.form1.c60_codcla.value = chave5;
    document.form1.c51_descr.value  = chave6;
  }

  if(erro==true){ 
    document.form1.c60_codcon.focus(); 
    document.form1.c60_codcon.value = ''; 
    document.form1.c60_estrut.value = "";
    document.form1.c60_codsis.value = "";
    document.form1.c52_descr.value  = "";
    document.form1.c60_codcla.value = "";
    document.form1.c51_descr.value  = "";
  }
}
function js_mostraconta1(chave1,chave2,chave3,chave4,chave5,chave6,chave7){
  document.form1.c60_codcon.value = chave1;
  document.form1.c60_descr.value  = chave2;
  document.form1.c60_estrut.value = chave3;
  document.form1.c60_codsis.value = chave4;
  document.form1.c52_descr.value  = chave5;
  document.form1.c60_codcla.value = chave6;
  document.form1.c51_descr.value  = chave7;

  db_iframe_conplano.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conplano','<?=$sNomeLookup;?>?funcao_js=parent.js_preenchepesquisa|c60_codcon','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_conplano.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>