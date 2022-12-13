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
$clatendusucliitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at80_id_usuario");
$clrotulo->label("at04_descr");
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
    $at81_seq = "";
    $at81_descr = "";
    $at81_codtipo = "";
    $at81_data = "";
    $at81_hora = "";
    $at81_data_dia = "";
    $at04_descr = "";
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat81_seq?>">
      <?=@$Lat81_seq?>
    </td>
    <td> 
      <?
      db_input('at81_seq',6,$Iat81_seq,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat81_codatendcli?>">
      <?
      db_ancora(@$Lat81_codatendcli,"js_pesquisaat81_codatendcli(true);",3);
      ?>
    </td>
    <td> 
      <?
      db_input('at81_codatendcli',6,$Iat81_codatendcli,true,'text',3," onchange='js_pesquisaat81_codatendcli(false);'")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat81_descr?>">
      <?=@$Lat81_descr?>
    </td>
    <td> 
      <?
      db_textarea('at81_descr',4,49,$Iat81_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat81_codtipo?>">
      <?
      db_ancora(@$Lat81_codtipo,"js_pesquisaat81_codtipo(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('at81_codtipo',6,$Iat81_codtipo,true,'text',$db_opcao," onchange='js_pesquisaat81_codtipo(false);'")
      ?>
      <?
      db_input('at04_descr',40,$Iat04_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat81_prioridade?>">
       <?=@$Lat81_prioridade?>
    </td>
    <td>
<?
  $x = array("1"=>"Baixa",
             "2"=>"Média",
             "3"=>"Alta"
           );
  db_select("at81_prioridade", $x,true,$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat81_data?>">
      <?=@$Lat81_data?>
    </td>
    <td> 
      <?
      if(!isset($at81_data_dia) || (isset($at81_data_dia) && trim($at81_data_dia) == "")){
        $at81_data_dia = date("d",db_getsession("DB_datausu"));
        $at81_data_mes = date("m",db_getsession("DB_datausu"));
        $at81_data_ano = date("Y",db_getsession("DB_datausu"));
      }
      db_inputdata('at81_data',@$at81_data_dia,@$at81_data_mes,@$at81_data_ano,true,'text',3,"")
      ?>
      <?
      if(!isset($at81_hora) || (isset($at81_hora) && trim($at81_hora) == "")){
        $at81_hora = db_hora();
      }
      db_input('at81_hora',5,$Iat81_hora,true,'hidden',3,"")
      ?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <?if($db_opcao != 1 && !isset($db_opcaoal)){?>
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();">
        <?if($db_opcao == 2){?>
        <input name="menus" type="button" id="envol" value="Lançar envolvidos" onclick="js_janelaenvolvidos();">
        <input name="menus" type="button" id="menus" value="Lançar menus" onclick="js_janelamenus();">
        <?}?>
      <?}?>
    </td>
  </tr>
</table>
<table>
  <tr>
    <td valign="top"  align="center">  
      <?
      $dbwhere = "at81_codatendcli = ".$at81_codatendcli;
      if(isset($at81_seq) && trim($at81_seq) != ""){
        $dbwhere.= " and at81_seq <> ".$at81_seq;
      }
      $chavepri= array("at81_seq"=>@$at81_seq);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->sql     = $clatendusucliitem->sql_query(null,"at81_seq,
                                                                               at81_codatendcli,
									       at81_descr,
									       at81_codtipo,
									       at04_descr,
									       at81_data,
									       at81_hora,
									       case when at81_prioridade = 1 then '".$x[1]."'
									            when at81_prioridade = 2 then '".$x[2]."'
									            when at81_prioridade = 3 then '".$x[3]."'
									       end as at81_prioridade
									      ","at81_seq",$dbwhere);
      $cliframe_alterar_excluir->campos  ="at81_descr,at81_prioridade,at04_descr,at81_data,at81_hora";
      $cliframe_alterar_excluir->legenda ="ITENS LANÇADOS";
      $cliframe_alterar_excluir->iframe_height ="160";
      $cliframe_alterar_excluir->iframe_width  ="700";
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_janelaenvolvidos(){
  js_OpenJanelaIframe('top.corpo.iframe_atendusucliitem','db_iframe_envolvidos','ate1_atendusucliitemid001.php?at83_usucliitem=<?=@$at81_seq?>','Cadastro',true,'0');
}
function js_janelamenus(){
  js_OpenJanelaIframe('top.corpo.iframe_atendusucliitem','db_iframe_menus','ate1_atendusucliproced001.php?at82_usucliitem=<?=@$at81_seq?>','Cadastro',true,'0');
}
function js_setatab(){
  campo = "at81_descr";
  <?
  if($db_opcao == 3 || $db_opcao == 33){
    echo "campo = 'excluir';";
  }
  ?>
  js_tabulacaoforms("form1",campo,true,1,campo,true);
}
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaat81_codatendcli(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atendusucliitem','db_iframe_atendusucli','func_atendusucli.php?funcao_js=parent.js_mostraatendusucli1|at80_codatendcli|at80_id_usuario','Pesquisa',true,'0');
  }else{
    if(document.form1.at81_codatendcli.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_atendusucliitem','db_iframe_atendusucli','func_atendusucli.php?pesquisa_chave='+document.form1.at81_codatendcli.value+'&funcao_js=parent.js_mostraatendusucli','Pesquisa',false);
    }else{
      document.form1.at80_id_usuario.value = ''; 
    }
  }
}
function js_mostraatendusucli(chave,erro){
  document.form1.at80_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.at81_codatendcli.focus(); 
    document.form1.at81_codatendcli.value = ''; 
  }
}
function js_mostraatendusucli1(chave1,chave2){
  document.form1.at81_codatendcli.value = chave1;
  document.form1.at80_id_usuario.value = chave2;
  db_iframe_atendusucli.hide();
}
function js_pesquisaat81_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_atendusucliitem','db_iframe_tipoatend','func_tipoatend.php?funcao_js=parent.js_mostratipoatend1|at04_codtipo|at04_descr','Pesquisa',true,'0');
  }else{
    if(document.form1.at81_codtipo.value != ''){ 
      js_OpenJanelaIframe('top.corpo.iframe_atendusucliitem','db_iframe_tipoatend','func_tipoatend.php?pesquisa_chave='+document.form1.at81_codtipo.value+'&funcao_js=parent.js_mostratipoatend','Pesquisa',false);
    }else{
      document.form1.at04_descr.value = ''; 
    }
  }
}
function js_mostratipoatend(chave,erro){
  document.form1.at04_descr.value = chave; 
  if(erro==true){ 
    document.form1.at81_codtipo.focus(); 
    document.form1.at81_codtipo.value = ''; 
  }
}
function js_mostratipoatend1(chave1,chave2){
  document.form1.at81_codtipo.value = chave1;
  document.form1.at04_descr.value = chave2;
  db_iframe_tipoatend.hide();
}
</script>