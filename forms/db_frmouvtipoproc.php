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

//MODULO: protocolo
$cltipoproc->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<legend><b>Tipo de Processo</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp51_codigo?>">
       <?=@$Lp51_codigo?>
    </td>
    <td> 
<?
db_input('p51_codigo',3,$Ip51_codigo,true,'text',3,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tp51_descr?>">
       <?=@$Lp51_descr?>
    </td>
    <td> 
<?
db_input('p51_descr',60,$Ip51_descr,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tp51_dtlimite?>">
       <?=@$Lp51_dtlimite?>
    </td>
    <td> 
<?
$matriz = array('t'=>"Sim",'f'=>"Nao");
db_inputdata('p51_dtlimite',@$p51_dtlimite_dia,@$p51_dtlimite_mes,@$p51_dtlimite_ano,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp51_identificado?>">
       <?=@$Lp51_identificado?>
    </td>
    <td> 
		<?
		$x = array('t'=>'Sim','f'=>'Não');
		db_select('p51_identificado',$x,true,$db_opcao,"");
		?>
    </td>
  </tr>  
  </table>
  </fieldset>
  </center>
  <br />
<?
  if (isset($db_opcao) && $db_opcao == 1) {
  	$sBtn = "Incluir";
  } else if (isset($db_opcao) && $db_opcao == 2 || $db_opcao == 22) {
  	$sBtn = "Alterar";
  } else if (isset($db_opcao) && $db_opcao == 3 || $db_opcao == 33) {
  	$sBtn = "Excluir";
  }

?>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=$sBtn?>" <?=($db_botao==false?"disabled":"")?> >
<?
  if (isset($db_opcao) && $db_opcao == 2) {
    echo "<input name='novo' type='button' id='novo' value='Novo Registro' onclick='js_novo();'>&nbsp;";
  }
  if (isset($db_opcao) && $db_opcao == 2 || $db_opcao == 22 || $db_opcao == 3 || $db_opcao == 33) {
    echo "<input name='pesquisar' type='button' id='pesquisar' value='Pesquisar' onclick='js_pesquisa();'>";
  }
?>
</form>
<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_tipoproc_todos.php?funcao_js=parent.js_preenchepesquisa|0&grupo=2';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_novo(){
  parent.location="ouv1_tipoproc001.php";
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
if($db_opcao == 22 || $db_opcao == 33){
?>
<script>
onLoad=js_pesquisa();
</script>
<?
}
?>