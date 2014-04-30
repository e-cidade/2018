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

//MODULO: configuracoes
$cldb_ordem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("at01_codcli");
$clrotulo->label("descrdepto");
$clrotulo->label("descrimg");

if(empty($dataordem_dia)){
  $dataordem_dia = date("d",db_getsession("DB_datausu"));
  $dataordem_mes = date("m",db_getsession("DB_datausu"));
  $dataordem_ano = date("Y",db_getsession("DB_datausu"));
}

?>
<script>
function js_excluir_anexo(codimg){
  document.form1.codimg.value=codimg;   
  obj=document.createElement('input');
  obj.setAttribute('name','excluir_anexo');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',codimg);
  document.form1.appendChild(obj);
  
  document.form1.submit();
}
function js_troca(valor){
    obj=document.createElement('input');
    obj.setAttribute('name','libera_anexos');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value',valor);
    document.form1.appendChild(obj);

    document.form1.submit();
}
</script>

<form name="form1" method="post" action="">
<center>
<table border="0" >
  <tr>
    <td nowrap title="<?=@$Tcodordem?>">
       <?=@$Lcodordem?>
    </td>
    <td> 
<?
if(empty($alertado)){
  $alertado='false';
}
db_input('alertado',40,$Ialertado,true,'hidden',$db_opcao,"");
db_input('descrimg',40,$Idescrimg,true,'hidden',1);
db_input('codimg',40,'',true,'hidden',1);

db_input('codordem',8,$Icodordem,true,'text',3);
?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tid_usuario?>">
       <?=@$Lnome?>
    </td>
    <td> 
<?
if(empty($id_usuario)){
  $id_usuario = db_getsession("DB_id_usuario");
  $result=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($id_usuario,"nome"));
  db_fieldsmemory($result,0);
}
db_input('id_usuario',5,$Iid_usuario,true,'text',3);
db_input('nome',40,$Inome,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td> 
<?
  $usuario_fixo = 999999;
  db_input('usureceb',10,$usuario_fixo,true,'hidden',$db_opcao,"");
//  $result=$cldb_usuarios->sql_record($cldb_usuarios->sql_query_file($usuario_fixo,"id_usuario,nome","nome"));
//   db_selectrecord("usureceb",$result,true,$db_opcao,"","","",'0');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcoddepto?>">
       <?=@$Lcoddepto?>
    </td>
    <td> 
<?
  $result=$cldb_depart->sql_record(
     $cldb_depart->sql_query_file(null,"coddepto,descrdepto"));
     db_selectrecord("coddepto",$result,true,$db_opcao,"","","");
?>
    </td>
  </tr>


        <td nowrap>
          <b>Solicitante</b>
	  </td>
	  <td>
          <?
           $result=$clclientes->sql_record("select 0 as at01_codcli, 'DBSELLER' union " . $clclientes->sql_query_file(null,"at01_codcli, at01_nomecli","at01_codcli"));
           db_selectrecord("codcli",$result,true,$db_opcao);
          ?>   
        </td>






  
  <tr>
    <td nowrap title="<?=@$Tdataordem?>"><?=@$Ldataordem?></td>
    <td> 
     <? db_inputdata('dataordem',@$dataordem_dia,@$dataordem_mes,@$dataordem_ano,true,'text',$db_opcao,"") ?>
     <?=@$Ldataprev?>
     <? db_inputdata('dataprev',@$dataprev_dia,@$dataprev_mes,@$dataprev_ano,true,'text',$db_opcao,"") ?>
    </td>
  </tr>
  <tr> 
    <td valign='top'><b>Módulos</b> </td>
    <td valign='top'>
    <table border= 0 >
    <tr>
     <td>
      <table border=0>
      <tr>
     </tr>
      <tr>
        <td nowrap>
          <b>Origem da Ordem</b>
	  </td>
	  <td>
          <?
           $result=$cldb_ordemorigem->sql_record($cldb_ordemorigem->sql_query_file(null,"*","or11_codigo"));
           db_selectrecord("codorigem",$result,true,$db_opcao);
          ?>   
        </td>


	
     </tr>


        <td nowrap>
          <b>Prioridade</b>
	  </td>
	  <td>
          <?
           $result=$clclientes->sql_record("select 1, 'urgente' union select 2, 'normal' union select 3, 'sem urgencia'");
	   $prioridade = 2;
           db_selectrecord("prioridade",$result,true,$db_opcao);
          ?>   
        </td>
     
     </table>
     </td>    
     <td>
     
<?    
  $result=$cldb_modulos->sql_record($cldb_modulos->sql_query_file(null,"id_item,nome_modulo","nome_modulo"));
  if(isset($result_modulo)){
    db_selectmultiple("id_item",$result,"12",$db_opcao,"","","",$result_modulo);
  }else{
    db_selectmultiple("id_item",$result,"12",$db_opcao);
  }  
?>
       </td>
       <td valign='top'>
	   <input name="marcar" type="button" id="marcar17" value="+" onClick="js_marcar()"><small><b>Marcar todos</b></small><br>
	   <input name="desmarcar" type="button" id="desmarcar" value=" - " onClick="js_desmarcar()"><small><b>Desmarcar todos</b></small>
        </td>
	</tr>
       </table>	
    </td>
  </tr>
</table>   

<table border='0' cellspacing="0" cellpadding="0">   
<!--
<tr>
    <td nowrap title="Anexos de arquivos">
      <b>Permitir anexos:</b>
    </td>  
    <td>
  <?
  $xy = array("nao"=>"NÂO","sim"=>"SIM");
  db_select('tipo',$xy,true,$db_opcao,"onchange='js_troca(this.value);'");
  ?>
    </td> 
  </tr>  
-->  
<?
if(isset($libera_anexos) && $libera_anexos=="sim" || isset($libera_anexos02) && $tipo=="sim" || isset($excluir_anexo) ){
  /*
?>
 <tr>
  <td colspan='2' align='right'>
  <input name="libera_anexos02" type="hidden" value="">
<table border='1' width='100%' cellspacing="0" cellpadding="0">   
  <tr> 
    <td colspan="2"  valign="top" nowrap  > 
    
     <iframe src="con6_andamentoanexar.php?db_opcao=<?=$db_opcao?>" align="middle"  scrolling="no" hspace="0" name="iframe" width="100%" height="34" frameborder="0" marginwidth="0" marginheight="0"> 
      </iframe> 
    </td>
  </tr>
  <tr>
    <td align="left" valign='top' width='12%' nowrap><strong>Arquivos anexados:</strong></td>
    <td align="left" valign="top" nowrap  >
	<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
	  <tr> 
	    <td align="left" valign="top">
	      <table cellspacing="0" cellpadding="0">
	        <tr>
		  <td>
<?
    $result='';
    db_selectmultiple("arquivos",$result,"3",$db_opcao);
?>    
		  </td>  
		  <td valign='top'>
		    <input name="removerAnexado" type="button" id="removerAnexado" value="Remover" onClick="javascript:removeItemAnexado()">
		  </td> 
		</tr>  
	       </table>  	
	    </td>
	    <td valign='top'>
	    <?
	    if(isset($codordem)){
		  $result_anexo=$cldb_ordemimagens->sql_record($cldb_ordemimagens->sql_query_file($codordem));
		  $numrows_anexo=$cldb_ordemimagens->numrows;
	    }
	    if($numrows_anexo>0){
	    ?>
	      <table>
	        <tr>
		  <td valign='top'>
		    <b>Anexos:</b>
		  </td>
		  
		  <td>
		  <?
		    for($i=0; $i<$numrows_anexo; $i++){
		      db_fieldsmemory($result_anexo,$i);
		      if($db_botao==true && $db_opcao!=22){
   		         echo "$descrimg<a href='#' onclick=\"js_excluir_anexo('$codimg');\">Excluir</a><br>";
		      }else{
   		         echo "$descrimg<a href='#' disabled  onclick=\"return false;\">Excluir</a><br>";
	              }
		    } 
		  ?>
		  </td>
		</tr>
	      </table>
	      <?
	      }
	      ?>
	    </td>
	  </tr>
	</table>
    </td>
  </tr>
 </table>
   </td>
 </tr>  
<?
*/
}
?>
  <tr>
  </tr>
  <tr>
    <td>
      <b>Atendimento:</b>
    </td>
    <td nowrap>
    <?
         db_input('or10_codatend',10,0,true,'text',3);
         db_input('or10_seq',10,0,true,'hidden',3);
         db_input('data_dia',10,0,true,'hidden',3);
         db_input('data_mes',10,0,true,'hidden',3);
         db_input('data_ano',10,0,true,'hidden',3);
    ?>	 
      <input type='button' name='importar ' onclick="js_pesquisaor10_codatend(true);" value='Importar atendimento' <?=($db_botao==false?"disabled":"")?>>
      <input type='button' name='limpar' onclick="js_cancelar();" value='Cancelar importação com atendimento' <?=($db_botao==false?"disabled":"")?>> 
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdescricao?>" valign='top' colspan='1' align='left'>
       <?
       db_ancora(@$Ldescricao,"js_pesquisaor10_codatend(true);",$db_opcao);

       ?>
    </td>
    <td colspan='1'> 
<?
db_textarea('descricao',10,100,$Idescricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
    
  <tr>
    <td colspan='2' align='center'>
	<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick="return js_sel();">
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_sel(){
  var F = document.form1.elements['arquivos[]'];
  var vir='';
  var descr='';

  for(i = 0;i < F.length;i++) {
	F.options[i].selected = true;
	descr = descr+vir+F.options[i].text;
	vir='-';
  }
  js_trocacordeselect();
  document.form1.descrimg.value=descr; 
}
function js_cancelar(){
  document.form1.or10_codatend.value='';
  document.form1.or10_seq.value='';
  document.form1.data_dia.value='';
  document.form1.data_mes.value='';
  document.form1.data_ano.value='';
}  
function js_desmarcar() {
  var F = document.form1.elements['id_item[]'];
  if(F.selectedIndex != -1) {
	for(i = 0;i < F.length;i++) {
	  F.options[i] = new Option(F.options[i].text,F.options[i].value);
	}
	js_trocacordeselect();
  }
}

function js_marcar() {
  var F = document.form1.elements['id_item[]'];
  for(i = 0;i < F.length;i++) {
	F.options[i].selected = true;
  }
  js_trocacordeselect();
}
function js_pesquisaor10_codatend(mostra){
    js_OpenJanelaIframe('top.corpo','db_iframe_atenditem','func_atenditemordem.php?funcao_js=parent.js_mostraatenditem1|at05_seq|at05_codatend|at05_solicitado|at05_data','Pesquisa',true);
}
function js_mostraatenditem1(chave1,chave2,chave3,chave4){
  document.form1.or10_seq.value = chave1;
  document.form1.or10_codatend.value = chave2;
  document.form1.descricao.value = chave3;
  dia = chave4.substr(8,2);
  mes = chave4.substr(5,2);
  ano = chave4.substr(0,4);
  document.form1.dataprev_dia.value = dia;
  document.form1.dataprev_mes.value = mes;
  document.form1.dataprev_ano.value = ano;
  db_iframe_atenditem.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_ordem','func_db_ordem.php?funcao_js=parent.js_preenchepesquisa|codordem','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_ordem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function removeItemAnexado() {
  var F = document.form1.elements['arquivos[]'];
  if(F.selectedIndex != -1) {
	F.options[F.selectedIndex] = null;
  }
  js_trocacordeselect();
}

function js_marcarAnexados() {
  var F = document.form1.elements['arquivos[]'];
  for(i = 0;i < F.length;i++) {
	F.options[i].selected = true;
  }
  js_trocacordeselect();
}
  js_trocacordeselect();
</script>