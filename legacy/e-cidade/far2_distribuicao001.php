<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);

$aux = new cl_arquivo_auxiliar;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <br><br>
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >
  <center>
  <fieldset style="width:40%"><legend>Estoque de Medicamento</legend>
  <table  border="0"  align="center">
    <form name="form1" method="post" action="">
      <tr>

      </tr>
      <tr >
        <td colspan="2"><?
                 // $aux = new cl_arquivo_auxiliar;
                 $cabecalho = "<strong>Medicamento</strong>";
                 $codigo = "coddepto"; //chave de retorno da func
                 $descr  = "descrdepto";   //chave de retorno
                 $nomeobjeto = 'departamentos';
                 $funcao_js = 'js_mostra';
                 $funcao_js_hide = 'js_mostra1';
                 $sql_exec  = "";
                 $func_arquivo = "func_db_depart.php";  //func a executar
                 $anomeiframe = "db_iframe_db_depart";
                 $localjan = "";
                 $onclick = "";
                 $db_opcao = 2;
                 $tipo = 2;
                 $top = 0;
                 $linhas = 10;
                 $vwhidth = 400;
                 $Labelancora = "";
		                 $tamanho_campo_descricao  = 30;
		                 $mostrar_botao_lancar = true;
		                 $vwidth = 350;
		                 $obrigarselecao  = true;
		                 $concatenar_codigo = false;
		                 $ordenar_itens = false;
		                 $executa_script_apos_incluir = "";
		                 $nomeiframe = "medicamentos";
		                 $passar_query_string_para_func = "";
		                 $parametros = "";
		                 $completar_com_zeros_codigo = false;
				 //$aux->funcao_gera_formulario();

		 echo "<tr>\n";
                 echo "<td colspan=\"4\">\n";
                 echo "<table border=\"0\" align=\"center\" >\n";
                 echo "  <tr>\n";
                 echo "    <td nowrap title=\"\" > \n";
                 echo "      <fieldset><Legend><strong>Medicamento</strong></legend>\n";
                 echo "      <table border=\"0\">\n";
                 echo "        <tr>\n";
                 echo "          <td nowrap >\n<b>";

                 $clrotulocampo = new rotulocampo;
                 $clrotulocampo->label($codigo);
                 $clrotulocampo->label($descr);
                 $codfilho = "L".$codigo;

                 $ancora = trim($Labelancora);
                 if( empty($ancora) ) {
                    $labelAncora = $GLOBALS["$codfilho"];
                 } else {
                    $labelAncora = $Labelancora;
                 }


                 db_ancora("Depósito","js_BuscaDados(true);",$db_opcao);
                 echo "          </td>\n";
		                 echo "          <td>\n";
		                 db_input($codigo,6,'',true,'text',$db_opcao," onchange='js_BuscaDados(false);' tabIndex='0'");

                 if($tipo==1)
                 echo "            <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
                 db_input($descr,$tamanho_campo_descricao,'',true,'text',3);
                 if($tipo==1)
                 echo "            <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";

                 if($mostrar_botao_lancar == true)
                 echo "          </td>\n";
				 echo "          <td>\n";
				 echo "            <input name=\"db_lanca_departamentos\" type=\"button\" value=\"Lançar\" >\n";
                 echo "        </b></td>\n";
                 echo "        </tr>\n";
                 echo "        <tr>\n ";
                 echo "          <td>\n";
				 echo "          </td>\n";
				 echo "          <td align=\"center\" colspan=\"2\" >\n";
				 echo "            <select name=\"".$nomeobjeto."[]\" id=\"".$nomeobjeto."\" size=\"".$linhas."\" style=\"width:".$vwidth."px\" multiple onDblClick=\"js_excluir_item".$nomeobjeto."()\">\n";
                 if (!empty($sql_exec)){
                          $result = db_query($sql_exec);
                   for($i=0;$i<pg_numrows($result);$i++){
                      echo "              <option value='".pg_result($result,$i,$codigo)."'>".pg_result($result,$i,$descr)."</option>\n";
                   }
                 }
                 echo "            </select> \n";
                 echo "          </td>\n";
                 echo "          <td>\n";
				 echo "          </td>\n";
				 echo "        </tr>\n";
                 echo "        </tr>\n";
                 echo "        </tr>\n";
                 echo "      </table>\n";
                 echo "    </td>\n";
                 echo "    <td>\n";
				 echo "    </td>\n";
				 echo "  </tr>\n";
                 echo "      </fieldset>\n";
         echo "</table>\n";
                 echo "</td>\n";
                 echo "</tr>	\n";

        	?>
       </td>
      </tr>
      <tr>
        <td align='left' >
            <table border="0">
              <tr>
			        <td><b> Distribuição Zerada</b></td>
                    <td><? $tipo_que = array("S"=>"Sim","N"=>"Não");
	                db_select("distribu",$tipo_que,true,2,""); ?></td>
              </tr>
			  <tr>
				 <td align="left"  title="Quebra" >
                    <strong>Quebra</strong>
	             </td>
				 <td>
					<?
			            $tipo_que = array("n"=>"Nenhuma","d"=>"Deposito");
	                db_select("quebra",$tipo_que,true,2,"onchange='js_testord(this.value);'"); ?>
                 </td>
	           </tr>
	           <tr>
                 <td align="left"  title="Ordem por  Codigo/Departamento/Material" >
                     <strong>Ordem &nbsp;&nbsp;</strong>
	             </td>
				 <td>
					 <?
	                 $tipo_ordem = array("a"=>"Alfabética","n"=>"Numerica");
	                 db_select("ordem",$tipo_ordem,true,2); ?>
                  </td>
	           </tr>
	           <td><b> Materiais</b></td>
	           <td><?
	           if(isset($lmater)){
	             $tipo_que = array("2"=>"Geral");
	           } else {
	             $tipo_que = array("1"=>"Somente Medicamentos", "2"=>"Geral");
	           }
	           db_select("materiais",$tipo_que,true,2,""); ?></td>
	           </tr>
	           <tr>
	             <td>
	               <b>Ano</b>
	             </td>
	             <td>
	               <?
	                  $iAno = date("Y",db_getsession("DB_datausu"));
	                  db_input("iAno",4,'',true,'text',1);
	               ?>
	             </td>
	           </tr>
	       </table>
	  </td>
    </tr>
  </form>
    </table>
</fieldset>
  <input  name="emite2" id="emite2" type="button" value="Imprimir" onclick="js_mandadados();" >
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_atualiza_itemdepartamentos(){
  var F = document.getElementById("departamentos").options;
  if(F.length==0){
    alert('Cadastre um ítem para prosseguir.');
    document.form1.coddepto.focus();
    return false;
  }else{
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
  }
  return true;
}
function js_campo_recebe_valores(){
  var F = document.getElementById("departamentos").options;
  variavel_recebe_valores = '';
  virgula = '';
  lengthcampo = F.length;
  if(lengthcampo==0){
    alert('Cadastre um ítem para prosseguir.');
    document.form1.coddepto.focus();
    return false;
  }else{
    for(var i = 0;i < F.length;i++) {
      variavel_recebe_valores += virgula+F[i].value;
      virgula = ',';
    }
  }
  return variavel_recebe_valores;
}
function js_excluir_itemdepartamentos(){
  var F = document.getElementById("departamentos");
  if(F.length == 1)
    F.options[0].selected = true;
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
    js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelectdepartamentos(){

  var texto=document.form1.descrdepto.value;
  var valor=document.form1.coddepto.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("departamentos");
    var valor_default_novo_option = F.length;
    var testa = false;
    for(var x = 0; x < F.length; x++){
      if(F.options[x].value == valor){
        testa = true;
        break;
      }
    }
    if(testa == false){
      F.options[valor_default_novo_option] = new Option(texto,valor);
      for(i=0;i<F.length;i++){
        F.options[i].selected = false;
      }
      F.options[valor_default_novo_option].selected = true;
      js_trocacordeselect();
    }
  }
  texto=document.form1.descrdepto.value="";
  valor=document.form1.coddepto.value="";
  document.form1.db_lanca_departamentos.onclick = '';

}
function js_BuscaDados(chave){
  document.form1.db_lanca_departamentos.onclick = '';
  if(chave){
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_almoxdepto.php?funcao_js=parent.js_mostra|coddepto|descrdepto','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_db_depart','func_db_almoxdepto.php?&pesquisa_chave='+document.form1.coddepto.value+'&funcao_js=parent.js_mostra1','Pesquisa',false);
  }
}
function js_mostra(chave1,chave2){
  document.form1.coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
  document.form1.db_lanca_departamentos.onclick = js_insSelectdepartamentos;
}
function js_mostra1(chave,erro){
  document.form1.descrdepto.value = chave;
  if(erro==true){
    document.form1.coddepto.focus();
    document.form1.coddepto.value = '';
  }
  db_iframe_db_depart.hide();
document.form1.db_lanca_departamentos.onclick = js_insSelectdepartamentos;
}
function js_testord(valor){
	if (valor=='S'){
		document.form1.ordem.value='b';
		document.form1.ordem.disabled=true;
	}else{
		document.form1.ordem.value='a';
		document.form1.ordem.disabled=false;
	}
}
function js_mandadados(){

  query       ="";
  vir         ="";
  listadepart ="";
  for(x=0;x<document.form1.departamentos.length;x++){
    listadepart+=vir+document.form1.departamentos.options[x].value;
    vir=",";
  }
  if (listadepart == '') {

    alert('Seleciona um Departamento!');
    return false;

  }
  if(document.form1.iAno.value == ''){
	  alert('entre com o ano!');
    return false;
  }
  query+='&listadepart='+listadepart;
  query+='&opcao=com';
  query+='&distribu='+document.form1.distribu.value;
  query+='&ordem='+document.form1.ordem.value;
  query+='&quebra='+document.form1.quebra.value;
  query+='&materiais='+document.form1.materiais.value;
  query+='&iAno='+document.form1.iAno.value;
  jan   = window.open('far2_distribuicao002.php?'+query,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}

</script>