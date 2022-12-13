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

//MODULO: Acordos
include("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo                 = new rotulocampo;

$clacordogruponumeracao->rotulo->label();
$clrotulo->label("ac02_sequencial");
$clrotulo->label("ac02_descricao");

if (isset($db_opcaoal)) {
	
  $db_opcao = 33;
  $db_botao = false;
} else if (isset($opcao) && $opcao == "alterar") {
	
  $db_botao = true;
  $db_opcao = 2;
} else if (isset($opcao) && $opcao == "excluir") {
	
  $db_opcao = 3;
  $db_botao = true;
} else {
	  
  $db_opcao = 1;
  $db_botao = true;
  if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false ) ) {
  	
    $ac03_acordogrupo = "";
    $ac03_anousu      = "";
    $ac03_numero      = "";
    $ac03_instit      = "";
  }
} 
?>
<form name="form1" method="post" action="">
<fieldset>
<legend><b>Numeração</b></legend>
<table border="0" align="left">
  <tr>
    <td nowrap title="<?=@$Tac03_sequencia?>">
       <b>Grupo:</b>
    </td>
    <td> 
      <?
        db_input('ac03_sequencial',10,$Iac03_sequencial,true,'hidden',3,"");
        db_input('ac02_sequencial',10,$Iac02_sequencial,true,'text',3,"");
      ?>
    </td>
    <td>
      <?
        db_input('ac02_descricao',40,@$Iac02_descricao,true,'text',3,"");
      ?> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tac03_anousu?>">
       <b>Ano:</b>
    </td>
    <td> 
			<?
			  $ac03_anousu = db_getsession('DB_anousu');
			  db_input('ac03_anousu',10,$Iac03_anousu,true,'text',$db_opcao,"")
			?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tac03_numero?>">
       <?=@$Lac03_numero?>
    </td>
    <td> 
			<?
			  db_input('ac03_numero',10,$Iac03_numero,true,'text',$db_opcao,"")
			?>
    </td>
 </table>
 </fieldset>
 <table>
   <tr>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td>
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
              type="submit" id="db_opcao" 
              value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?>  >
     </td>
     <td>
       <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" 
              <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
     </td>
   </tr>
   <tr>
     <td>&nbsp;</td>
   </tr>
 </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
       $sWhere = "ac03_acordogrupo = {$ac02_sequencial}";
       $sSqlAcordoGrupoNumeracao = $clacordogruponumeracao->sql_query_file(null,"*","ac03_sequencial",$sWhere);
       
			 $chavepri= array("ac03_sequencial"=>@$ac03_sequencial);
			 $cliframe_alterar_excluir->chavepri      = $chavepri;
			 $cliframe_alterar_excluir->sql           = $sSqlAcordoGrupoNumeracao;
			 $cliframe_alterar_excluir->campos        = "ac03_sequencial,ac03_anousu,ac03_numero";
			 $cliframe_alterar_excluir->legenda       = "Numerações Lançadas";
			 $cliframe_alterar_excluir->iframe_height = "160";
			 $cliframe_alterar_excluir->iframe_width  = "530";
			 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>

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
</script>