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

//MODULO: fiscal
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clprocfiscalfiscais->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y100_coddepto");
$clrotulo->label("id_usuario");
$clrotulo->label("z01_nome");
$clrotulo->label("y106_principal");

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
     $nome = "";
     $y106_cadfiscais = "";
		 $y106_sequencial = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty106_sequencial?>">
       <?=@$Ly106_sequencial?>
    </td>
    <td> 
<?
db_input('y106_sequencial',10,$Iy106_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty106_procfiscal?>">
       <?=@$Ly106_procfiscal?>
      
    </td>
    <td> 
<?
db_input('y106_procfiscal',10,$Iy106_procfiscal,true,'text',3)
?>
     
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty106_cadfiscais?>">
       <?
       db_ancora(@$Ly106_cadfiscais,"js_pesquisay106_cadfiscais(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y106_cadfiscais',10,$Iy106_cadfiscais,true,'text',$db_opcao," onchange='js_pesquisay106_cadfiscais(false);'")
?>
       <?
db_input('nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
	<tr>
    <td nowrap title="<?=@$Ty106_principal?>">
       <?=@$Ly106_principal?>
    </td>
    <td> 
<?
 $prin = array("t"=>"Sim","f"=>"Não");
 db_select("y106_principal",$prin,true,2); 
 ?>
    </td>
  </tr>
	
	
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
		
		$sql = "select * from procfiscalfiscais where y106_procfiscal=$y106_procfiscal";
	//	echo $sql;
	 $chavepri= array("y106_sequencial"=>@$y106_sequencial);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $sql;
	 $cliframe_alterar_excluir->campos  ="y106_sequencial,y106_procfiscal,y106_cadfiscais,y106_principal";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
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

function js_pesquisay106_cadfiscais(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_procfiscalfiscais','db_iframe_cadfiscais','func_cadfiscaisalt.php?funcao_js=parent.js_mostracadfiscais1|id_usuario|nome','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.y106_cadfiscais.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_procfiscalfiscais','db_iframe_cadfiscais','func_cadfiscaisalt.php?pesquisa_chave='+document.form1.y106_cadfiscais.value+'&funcao_js=parent.js_mostracadfiscais','Pesquisa',false);
     }else{
       document.form1.id_usuario.value = ''; 
     }
  }
}
function js_mostracadfiscais(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.y106_cadfiscais.focus(); 
    document.form1.y106_cadfiscais.value = ''; 
  }
}
function js_mostracadfiscais1(chave1,chave2){
  document.form1.y106_cadfiscais.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_cadfiscais.hide();
}
</script>