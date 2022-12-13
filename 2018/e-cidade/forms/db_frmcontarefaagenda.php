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
include("classes/db_db_usuarios_classe.php");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_usuarios            = new cl_db_usuarios;
$cltarefa_agenda->rotulo->label();
$clrotulo = new rotulocampo;
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
     $at13_sequencial = "";
     $at13_tarefa = "";
     $at13_dia = "";
     $at13_horaini = "";
     $at13_horafim = "";
   }
} 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
  	<td nowrap colspan="2" align="right">
		<input name="bt_voltar" type="button" value="Voltar" title="Voltar" onClick="js_voltar();">
  	</td>
  </tr>	
  <tr>
    <td nowrap title="<?=@$Tat13_tarefa?>">
       <?=@$Lat13_tarefa?>
    </td>
    <td> 
<?
db_input('at13_tarefa',10,$Iat13_tarefa,true,'text',3,"")
?>
    </td>
  </tr>
    <td colspan="2" align="center">
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("at13_sequencial"=>@$at13_sequencial,"at13_tarefa"=>@$at13_tarefa);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $cltarefa_agenda->sql_query(null,"*"," at13_dia, at13_horaini, at13_horafim","at13_tarefa=$at13_tarefa");
	 $cliframe_alterar_excluir->campos  ="at13_dia, at13_horaini, at13_horafim";
	 $cliframe_alterar_excluir->legenda="Agendamento";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
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
function js_voltar() {
  parent.mo_camada('tarefa')
  top.corpo.iframe_tarefa.document.form1.bt_voltar.click();	
}
</script>