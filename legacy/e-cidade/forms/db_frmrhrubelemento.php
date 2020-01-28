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

//MODULO: pessoal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrhrubelemento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh27_descr");
$clrotulo->label("o56_elemento");
if(isset($db_opcaoal)){
  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
  $db_botao=true;
  $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
  $db_opcao = 3;
  $db_botao=true;
}else{  
  $db_opcao = 1;
  $db_botao=true;
  if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
    $rh23_codele = "";
    $o56_elemento = "";
    $rh24_eleprinc = 0;
  }
} 
?>
<form name="form1" method="post" action="">
<center>
<table width="95%" height="70%" border="0">
  <tr>
    <td align="center" height="20%">
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Trh23_rubric?>">
            <?
            echo @$Lrh23_rubric;
            ?>
          </td>
          <td> 
            <?
            db_input('rh23_rubric',4,$Irh23_rubric,true,'text',3," onchange='js_pesquisarh23_rubric(false);'");
            $result_descricao = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rh23_rubric,"rh27_descr"));
            if($clrhrubricas->numrows > 0){
      	      db_fieldsmemory($result_descricao,0);
            }
            db_input('rh27_descr',30,$Irh27_descr,true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh23_codele?>">
            <?
            db_ancora(@$Lrh23_codele,"js_pesquisarh23_codele(true);",$db_opcao);
            ?>
          </td>
          <td> 
            <?
            db_input('rh23_codele',6,$Irh23_codele,true,'text',$db_opcao," onchange='js_pesquisarh23_codele(false);'")
            ?>
            <?
            db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
            ?>
          </td>
        </tr>
        <tr>
          <td><b>Principal</b></td>
          <td>
            <?
            $x   = array("0"=>"NAO","1"=>"SIM");
            $dbwhere = "rh23_rubric='$rh23_rubric'"; 
            if(isset($rh23_codele) && trim($rh23_codele)!=""){
              $dbwhere .= " and rh23_codele<>$rh23_codele";
              $result_rubelementoprinc = $clrhrubelementoprinc->sql_record($clrhrubelementoprinc->sql_query_file(null,null,"rh24_rubric,rh24_codele","","rh24_rubric = '$rh23_rubric' and rh24_codele = $rh23_codele"));
              if($clrhrubelementoprinc->numrows > 0){
                $rh24_eleprinc = 1;
              }else{
                $rh24_eleprinc = 0;
              }
            }
            db_select('rh24_eleprinc',$x,true,$db_opcao,"");
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
            <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td valign="top" height="80%" width="100%">
      <?
      // $s = $clrhrubelemento->sql_record($clrhrubelemento->sql_query(@$rh23_rubric));
      $chavepri= array("rh23_rubric"=>@$rh23_rubric,"rh23_codele"=>@$rh23_codele);
      $cliframe_alterar_excluir->chavepri      = $chavepri;
      $cliframe_alterar_excluir->sql           = $clrhrubelemento->sql_query(null,null,"rh23_rubric,rh23_codele,o56_codele,o56_elemento,o56_descr","o56_codele",$dbwhere);
      // echo $cliframe_alterar_excluir->sql;
      $cliframe_alterar_excluir->campos        = "o56_codele,o56_elemento,o56_descr";
      $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
      $cliframe_alterar_excluir->iframe_height = "300";
      $cliframe_alterar_excluir->iframe_width  = "100%";
      $cliframe_alterar_excluir->opcoes        = 3;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
</table>

</center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisarh23_rubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhrubelemento','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarhrubricas1|rh27_rubric|rh27_descr','Pesquisa',true,'0');
  }else{
    if(document.form1.rh23_rubric.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_rhrubelemento','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh23_rubric.value+'&funcao_js=parent.js_mostrarhrubricas','Pesquisa',false,0);
    }else{
      document.form1.rh27_descr.value = ''; 
    }
  }
}
function js_mostrarhrubricas(chave,erro){
  document.form1.rh27_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh23_rubric.focus(); 
    document.form1.rh23_rubric.value = ''; 
  }
}
function js_mostrarhrubricas1(chave1,chave2){
  document.form1.rh23_rubric.value = chave1;
  document.form1.rh27_descr.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisarh23_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_rhrubelemento','db_iframe_orcelemento','func_orcelementosub.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true,'0');
  }else{
    if(document.form1.rh23_codele.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_rhrubelemento','db_iframe_orcelemento','func_orcelementosub.php?pesquisa_chave='+document.form1.rh23_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false,0);
    }else{
      document.form1.o56_elemento.value = ''; 
    }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.rh23_codele.focus(); 
    document.form1.rh23_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.rh23_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
</script>