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

//MODULO: compras
$clpccontrdot->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p71_datalanc");
$clrotulo->label("o58_orgao");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_dotacao.location.href='com1_pccontrdot002.php?chavepesquisa=$p73_codcontr&chavepesquisa1=$p73_coddot'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_dotacao.location.href='com1_pccontrdot003.php?chavepesquisa=$p73_codcontr&chavepesquisa1=$p73_coddot'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp73_codcontr?>">
       <?
       db_ancora(@$Lp73_codcontr,"js_pesquisap73_codcontr(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('p73_codcontr',10,$Ip73_codcontr,true,'text',3," onchange='js_pesquisap73_codcontr(false);'")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp73_anousu?>">
       <?
       db_ancora(@$Lp73_anousu,"js_pesquisap73_anousu(true);",3);
       ?>
    </td>
    <td> 
<?
$p73_anousu = db_getsession('DB_anousu');
db_input('p73_anousu',4,$Ip73_anousu,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp73_coddot?>">
       <?
       db_ancora(@$Lp73_coddot,"js_pesquisao47_coddot(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p73_coddot',6,$Ip73_coddot,true,'text',$db_opcao," onchange='js_pesquisao47_coddot(false);'");
db_input('p73_coddot',6,$Ip73_coddot,true,'hidden',$db_opcao," onchange='js_pesquisao47_coddot(false);'",'p73_coddot_old')
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp73_valor?>">
       <?=@$Lp73_valor?>
    </td>
    <td> 
<?
db_input('p73_valor',20,$Ip73_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("p73_codcontr"=>@$p73_codcontr,"p73_coddot"=>@$p73_coddot);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="p73_codcontr,p73_coddot";
    $cliframe_alterar_excluir->sql=$clpccontrdot->sql_query_file("","","*",""," p73_codcontr = ".@$p73_codcontr."");
    $cliframe_alterar_excluir->legenda="Dotações";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhuma Dotação Cadastrada!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
   ?>
   </td>
 </tr>  
  </table>
  </center>
</form>
<script>
function js_pesquisao47_coddot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_dotacao','db_iframe_orcdotacao','func_permorcdotacao.php?funcao_js=parent.js_mostraorcdotacao1|o58_coddot','Pesquisa',true,0);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_dotacao','db_iframe_orcdotacao','func_permorcdotacao.php?pesquisa_chave='+document.form1.p73_coddot.value+'&funcao_js=parent.js_mostraorcdotacao','Pesquisa',false);
  }
}
function js_mostraorcdotacao(chave,erro){
  if(erro==true){ 
    document.form1.p73_coddot.focus(); 
    document.form1.p73_coddot.value = ''; 
  }
}

function js_mostraorcdotacao1(chave1){
  document.form1.p73_coddot.value = chave1;
  db_iframe_orcdotacao.hide();
}
</script>