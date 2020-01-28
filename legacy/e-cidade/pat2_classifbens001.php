<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cfpatri_classe.php");
db_postmemory($HTTP_POST_VARS);
$aux = new cl_arquivo_auxiliar;
$cldb_estrut = new cl_db_estrut;
$clcfpatri = new cl_cfpatri;
$clrotulo = new rotulocampo;
$clrotulo->label("t64_class");
$clrotulo->label("t64_descr");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_emite(){
  /*
  vir="";
  lista="";
  for(x=0;x<document.form1.class.length;x++){
  	listat+=vir+document.form1.class.options[x].v&opcao_baixados='+document.form1.opcao_baixados.valuealue;
  	vir=",";
  }
  */
  var query  = 	"?class="+document.form1.t64_class.value;
  		query +=	"&opcao_baixados="+document.form1.opcao_baixados.value;
  		query +=	"&class1="+document.form1.t64_class1.value;
  		query +=	"&quebra="+document.form1.quebra.value;
  		query +=	"&ordenar="+document.form1.ordenar.value;
  //query="?class="+document.form1.t64_class.value+"&opcao_baixados="+document.form1.opcao_baixados.value+"&class1="+document.form1.t64_class1.value+"&quebra="+document.form1.quebra.value;
  jan = window.open('pat2_classifbens002.php'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisat64_class(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?funcao_js=parent.js_mostraclabens1|t64_class|t64_descr','Pesquisa',true);
  }else{
     testa = new String(document.form1.t64_class.value);
     if(testa != '' && testa != 0){	    
       i = 0;
       for(i = 0;i < document.form1.t64_class.value.length;i++){
         testa = testa.replace('.','');
       }				      
       js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabens','Pesquisa',false);
     }else{    
       document.form1.t64_descr.value = ''; 
     }
  }
}
function js_mostraclabens(chave,erro){
  document.form1.t64_descr.value = chave; 
  if(erro==true){ 
    document.form1.t64_class.value = '';
    document.form1.t64_class.focus();
  }else{

  }
  if(document.form1.t64_class.value == ""){
  	document.form1.t64_descr.value = '';
  }
}
function js_mostraclabens1(chave1,chave2){
  document.form1.t64_class.value = chave1;
  document.form1.t64_descr.value = chave2; 
  db_iframe_clabens.hide();
}
function js_pesquisat64_class1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?funcao_js=parent.js_mostraclabens11|t64_class|t64_descr','Pesquisa',true);
  }else{
     testa = new String(document.form1.t64_class1.value);
     if(testa != '' && testa != 0){	    
       i = 0;
       for(i = 0;i < document.form1.t64_class1.value.length;i++){
         testa = testa.replace('.','');
       }				      
       js_OpenJanelaIframe('top.corpo','db_iframe_clabens','func_clabens.php?pesquisa_chave='+testa+'&funcao_js=parent.js_mostraclabens2','Pesquisa',false);
     }else{    
       document.form1.t64_descr1.value = ''; 
     }
  }
}
function js_mostraclabens2(chave,erro){
  document.form1.t64_descr1.value = chave; 
  if(erro==true){ 
    document.form1.t64_class1.value = '';
    document.form1.t64_class1.focus();
  }else{

  }
  if(document.form1.t64_class1.value == ""){
  	document.form1.t64_descr1.value = '';
  }
}
function js_mostraclabens11(chave1,chave2){
  document.form1.t64_class1.value = chave1;
  document.form1.t64_descr1.value = chave2; 
  db_iframe_clabens.hide();
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Relatórios - Bens por Classificação</legend>
    <table class="form-container">
      <!--    
      <tr> 
        <td colspan=2  align="center">
          <strong>Opções:</strong>
          <select name="ver">
            <option name="condicao1" value="com">Com as classificações selecionados</option>
            <option name="condicao1" value="sem">Sem as classificações selecionados</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan=2 >
        <?/*
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Classificações</strong>";
                 $aux->codigo = "t64_codcla"; //chave de retorno da func
                 $aux->descr  = "t64_descr";   //chave de retorno
                 $aux->nomeobjeto = 'class';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_clabens.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_clabens";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
       */ ?>
       </td>
      </tr>
      -->
      <tr>
      	<td colspan="2">
      	  <fieldset class="separator">
      	    <legend>Intervalo das Classificações</legend>
      	    <table class="form-container">
      	      <tr>
                <td align='right'>
                  <b>De:</b>
      	        </td>
      	        <td align='right'>
      	        </td>
              </tr>
              <tr>    
                <td align='right' nowrap title="<?=@$Tt64_class?>">
                  &emsp;
                  <?
                    db_ancora(@$Lt64_class,"js_pesquisat64_class(true);",1);
                  ?>
                </td>
                <td>
                  <?  
                    $result = $clcfpatri->sql_record($clcfpatri->sql_query_file());
                    $msg_erro = "";
                    
                    if($clcfpatri->numrows==0){
                      $msg_erro = _M('patrimonial.patrimonio.pat2_classifbens001.estrutural_nao_cadastrado');
                      $t06_codcla = 0;
                      $t06_confplcaca = 0;
                    }else{
                      db_fieldsmemory($result,0);
                    }
                        $cldb_estrut->autocompletar = true;
                        $cldb_estrut->mascara = false;
                        $cldb_estrut->input   = true;
                        $cldb_estrut->reload  = false;
                        $cldb_estrut->size    = 10;
                        $cldb_estrut->funcao_onchange ='js_pesquisat64_class(false);';
                        $cldb_estrut->nome    = "t64_class";
                        $cldb_estrut->db_opcao= 1;
                        if(isset($t06_codcla) && $t06_codcla!=""){
                          $cldb_estrut->db_mascara(@$t06_codcla);
                        }else{
                          db_msgbox($msg_erro);
                        }
                        
                    db_input('t64_descr',38,$It64_descr,true,'text',3,'')
                  ?>      
                </td>
              </tr>
              <tr>
                <td align='right'>
                  <b>Até:</b>
      	        </td>
      	        <td align='right'>
      	        </td>
              </tr>
              <tr>
                <td align='right' nowrap title="<?=@$Tt64_class?>">
                &emsp;
                  <?
                    db_ancora(@$Lt64_class,"js_pesquisat64_class1(true);",1);
                  ?>
                </td>
                <td>
                  <?
                    $cldb_estrut->autocompletar = true;
                    $cldb_estrut->mascara = false;
                    $cldb_estrut->input   = true;
                    $cldb_estrut->reload  = false;
                    $cldb_estrut->size    = 10;
                    $cldb_estrut->funcao_onchange ='js_pesquisat64_class1(false);';
                    $cldb_estrut->nome    = "t64_class1";
                    $cldb_estrut->db_opcao= 1;
                    if(isset($t06_codcla) && $t06_codcla!=""){
                      $cldb_estrut->db_mascara(@$t06_codcla);
                    }else{
                      db_msgbox($msg_erro);
                    }
                    
                    db_input('t64_descr1',38,$It64_descr,true,'text',3,'')
                  ?>      
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap title="Bens a serem listados"><b>Ordenar:</b></td>
        <td nowrap title="">
          <?
            $ordenar = array(1=>"Placa",2=>"Descrição do bem", 3=>"Código do bem"); 
            db_select("ordenar",$ordenar,true,1);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Bens a serem listados"><b>Listar:</b></td>
        <td nowrap title="">
          <?
            $matriz_baix = array("t"=>"Todos","n"=>"Não Baixados", "b"=>"Baixados"); 
            db_select("opcao_baixados",$matriz_baix,true,1);
          ?>
        </td>
      </tr>
      <tr>
      	<td>
          Quebrar página por Classificação:
      	</td>
      	<td>
          <?
            $tipo=array("s"=>"Sim","n"=>"Não");
            db_select("quebra",$tipo,true,"text",1);
          ?>
      	</td>
      </tr>
    </table>
  </fieldset>
  <input type="button" value="Emitir Relatório" onclick="js_emite();" >
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_limpacampo(){
  var t64_class_temp =   document.form1.t64_class.value;
  //Retirar os pontos converter para numero ai verifica o valor.
  t64_class_temp = new Number(t64_class_temp.replace(/\./g,''));
  if (t64_class_temp == 0){
    document.form1.t64_class.value='';  
    document.form1.t64_descr.value='';  
  }
}

sChange = document.form1.t64_class.getAttribute('onChange');
document.form1.t64_class.setAttribute('onChange',sChange+";js_limpacampo();");

function js_limpacampo1(){
  var t64_class_temp1 =   document.form1.t64_class1.value;
  //Retirar os pontos converter para numero ai verifica o valor.
  t64_class_temp1 = new Number(t64_class_temp1.replace(/\./g,''));
  if (t64_class_temp1 == 0){
    document.form1.t64_class1.value='';  
    document.form1.t64_descr1.value='';  
  }
}

sChange1 = document.form1.t64_class1.getAttribute('onChange');
document.form1.t64_class1.setAttribute('onChange',sChange1+";js_limpacampo1();"); 

</script>
<script>

$("ordenar").setAttribute("rel","ignore-css");
$("ordenar").style.width = "300px";

</script>