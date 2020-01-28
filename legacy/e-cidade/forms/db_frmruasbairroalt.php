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

//MODULO: atendimento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clruasbairro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
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
       $j16_codigo="";
       $j16_bairro="";
       $j13_descr="";     
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
    <td nowrap title="<?=@$Tj16_lograd?>">
    <b>
       <?
       db_ancora("Logradouro :","",3);
       ?>
       </b>
    </td>
    <td>
     
<?
if (isset($j16_lograd)&&$j16_lograd!=""){
	if (!isset($j14_nome)){
		$Result = $clruas->sql_record($clruas->sql_query_file($j16_lograd,"j14_nome"));
		if ($clruas->numrows>0){
			db_fieldsmemory($Result,0);
		}
		
	}
}
db_input('j16_lograd',10,$Ij16_lograd,true,'text',3,"");
db_input('j16_codigo',10,$Ij16_codigo,true,'hidden',3,"");
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
       
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tj16_bairro?>">
    <b>
       <?
       db_ancora("Bairro :","js_bairro(true);",$db_opcao);
       ?>
      </b>
    </td>
    <td> 
<?
db_input('j16_bairro',10,$Ij16_bairro,true,'text',$db_opcao," onchange='js_bairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
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
	 $chavepri= array("j16_codigo"=>@$j16_codigo,"j16_lograd"=>@$j16_lograd);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clruasbairro->sql_query(null,"*",null,"j16_lograd=$j16_lograd");
	 $cliframe_alterar_excluir->campos  ="j16_bairro,j13_descr";
	 $cliframe_alterar_excluir->legenda="BAIRROS";
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
function js_bairro(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?funcao_js=parent.js_preenchebairro1&pesquisa_chave='+document.form1.j16_bairro.value,'pesquisa',false);
  }
}
function js_preenchebairro(chave,chave1){
  document.form1.j16_bairro.value = chave;
  document.form1.j13_descr.value = chave1;
  db_iframe_bairros.hide();
}
function js_preenchebairro1(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro == true){
    document.form1.j16_bairro.focus();
    document.form1.j16_bairro.value='';
  }
  db_iframe_bairros.hide();
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>