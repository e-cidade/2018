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

//MODULO: compras
$clpctipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc04_descrsubgrupo");
$clrotulo->label("o56_elemento");
$clrotulo->label("pc03_codgrupo");
$clrotulo->label("pc03_descrgrupo");
$clrotulo->label("pc03_ativo");
$clrotulo->label("pc04_codsubgrupo");

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

include("classes/db_orcelemento_classe.php"); // orcelemento
$clorcelemento = new cl_orcelemento;
$anousu=db_getsession("DB_anousu");

?>
<script>
function js_critica_dados(){
  if (document.form1.pc05_descr.value ==''){
		alert('Descrição não informada ! ');
		document.form1.pc05_descr.focus();
	} else {  
		js_gera_chaves();
        
		if(js_retorna_chaves() == '') {
			alert('Nenhum elemento selecionado para o grupo.');
			return false;
		}
		document.form1.submit();
	}    
}  
</script>
<form name="form1" method="post" action="">
  <center>
  <table border="0">
  <!--  -->
  <tr>
      <td align=right nowrap title="<?=@$Tpc03_codgrupo?>"><?=@$Lpc03_codgrupo?> </td>
      <td><? db_input('pc03_codgrupo',6,$Ipc03_codgrupo,true,'text',3,"","pc05_codtipo") ?>  </td>
  </tr>
  <!--  -->
  <tr>
      <td align=right nowrap title="<?=@$Tpc03_descrgrupo?>"><?=@$Lpc03_descrgrupo?> </td>
      <td><? db_input('pc03_descrgrupo',40,$Ipc03_descrgrupo,true,'text',$db_opcao,"",'pc05_descr') ?>  </td>
  </tr>
  <tr>
    <td nowrap align=right title="<?=@$Tpc03_ativo?>">
       <?=@$Lpc03_ativo?>
    </td>
    <td> 
<?
if ($db_opcao != 1 || $db_opcao != 11){
     if (isset($pc05_ativo) && trim(@$pc05_ativo) != ""){
          if ($pc05_ativo == "t"){
               $pc05_ativo = "true";
          }

          if ($pc05_ativo == "f"){
               $pc05_ativo = "false";
          }
     }
}
$xx = array("true"=>"SIM","false"=>"NAO");
db_select('pc05_ativo',$xx,true,$db_opcao,"");
?>
    </td>
  </tr>
  <!--  -->
  <tr>
   <td colspan=2 align=center>
    <?
       $sql = " select distinct o56_codele,o56_elemento,o56_descr
                from orcelemento  
 	            inner join orcdotacao on o58_codele=o56_codele and o58_anousu=o56_anousu
		where o56_anousu = $anousu
		order by o56_codele    
  	      ";
       $sql_marca="";	      
       if (isset($pc05_codtipo) && ($pc05_codtipo!="")){	      
            $sql_marca = " select distinct (pc06_codele) as o56_codele
	                   from pctipoelemento
			   where  pc06_codtipo = $pc05_codtipo
			   order by pc06_codele
                         ";
       }		   

       if ($db_opcao == "1"  || $sql_marca !=""){
           $cliframe_seleciona->campos  = "o56_codele,o56_elemento,o56_descr";
           $cliframe_seleciona->legenda="Elementos";
           $cliframe_seleciona->sql=$sql;	   
           $cliframe_seleciona->sql_marca=$sql_marca;
           $cliframe_seleciona->iframe_height ="250";
           $cliframe_seleciona->iframe_width ="700";
           $cliframe_seleciona->iframe_nome ="elementos"; 
           $cliframe_seleciona->chaves ="o56_codele";
           $cliframe_seleciona->iframe_seleciona($db_opcao);    
       }
     ?>
   </td>
   </tr>
  <!--  -->
  </table>
  </center>
 
  <input name="db_opcao" type="hidden" id="db_opcao"  value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 
  <input name="db_opcao" type="button" id="db_opcao"  value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="js_critica_dados()" >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>

<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcgrupo','func_pcgrupoalt.php?funcao_js=parent.js_preenchepesquisa|pc03_codgrupo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pcgrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>