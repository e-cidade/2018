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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_bases_classe.php");
include("classes/db_basesr_classe.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clbases = new cl_bases;
$clbasesr = new cl_basesr;
$clrhrubricas = new cl_rhrubricas;
$db_opcao = 1;
$db_botao = true;
  $anousu = db_anofolha();
  $mesusu = db_mesfolha();
if(isset($incluir)){
  db_inicio_transacao();
  // db_msgbox($sselecionados);

  $sqlerro = false;
  $clbasesr->excluir($anousu,$mesusu,null,$r09_rubric,db_getsession("DB_instit"));
  $erro_msg = $clbasesr->erro_msg;
  if($clbasesr->erro_status==0){
    $sqlerro=true;
  }  
  if($sqlerro == false && trim($sselecionados) != ""){
    $arr_dados = split(",",$sselecionados);
    for($i=0;$i<sizeof($arr_dados);$i++){
      $base = $arr_dados[$i];
      $clbasesr->incluir($anousu,$mesusu,$base,$r09_rubric,db_getsession("DB_instit"));      
      $erro_msg = $clbasesr->erro_msg;
      if($clbasesr->erro_status==0){
        $sqlerro=true;
        break;
      }
    }
  }
  db_msgbox($erro_msg);
  db_fim_transacao($sqlerro);
}
?>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect();" >
<center>
<table width="60%" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post">
  <?
  db_input('sselecionados',20,0,true,'hidden',3);
  db_input('nselecionados',20,0,true,'hidden',3);
  ?>
  <tr>
    <td>
      <table>
        <tr>
          <td>
            <fieldset>
              <Legend align="left">
                <b>Demais Bases</b>
              </Legend>
<?
  $result_bases = $clbases->sql_record($clbases->sql_query(null,null,null,null,"r08_codigo,r08_descr","r08_codigo"," r08_instit = ".db_getsession("DB_instit")." and r08_anousu = ".db_anofolha()." and r08_mesusu = ".db_mesfolha()));
  $numrows_bases = $clbases->numrows;

    
  $result_selecionadas = $clbases->sql_record($clbases->sql_query_rubricas(null,null,null,null,"distinct  r08_codigo as bsel,r08_descr as dsel","r08_codigo","r09_rubric='$r09_rubric' and r08_instit = ".db_getsession("DB_instit")." and r08_anousu = ".db_anofolha()." and r08_mesusu = ".db_mesfolha()));
  $numrows_selecionadas = $clbases->numrows;

  if($numrows_bases > 0){   
    
    echo "    <select name='objeto1' id='objeto1' size='25' style='width:350px' multiple onDblClick='js_incluir_item(this,document.form1.objeto2);'>\n";    
    for($i=0;$i<$numrows_bases;$i++){
      db_fieldsmemory($result_bases,$i);      
      echo $clbasesr->sql_query_file(db_anofolha(),db_mesfolha(),$r08_codigo,$r09_rubric,db_getsession("DB_instit")).";<br>";
      $result_menosselecionadas = $clbasesr->sql_record($clbasesr->sql_query_file(db_anofolha(),db_mesfolha(),$r08_codigo,$r09_rubric,db_getsession("DB_instit")));
      if($clbasesr->numrows == 0){
        echo "      <option value='$r08_codigo'>$r08_codigo - $r08_descr</option>\n";
      }
    }
    echo "    </select>\n";
    echo "  </fieldset>\n";
    echo "  </td>\n";
    echo "  <td width='10%' align='center'>\n";
    echo "    <table>\n";
    echo "      <tr>\n";
    echo "        <td align='center'><input type='button' name='selecionD' title='Enviar selecionados para direita' value='&nbsp;>&nbsp;' onclick='js_incluir_item(document.form1.objeto1,document.form1.objeto2);'></td>\n";
    echo "      </tr>\n";
    echo "      <tr>\n";
    echo "        <td align='center'><input type='button' name='seltodosD' title='Enviar todos para direita' value='>>' onclick='js_incluir_todos(document.form1.objeto1,document.form1.objeto2);'></td>\n";
    echo "      </tr>\n";
    echo "      <tr>\n";
    echo "        <td align='center'><input type='button' name='seltodosE' title='Enviar selecionados para esquerda' value='&nbsp;<&nbsp;' onclick='js_incluir_item(document.form1.objeto2,document.form1.objeto1);'></td>\n";
    echo "      </tr>\n";
    echo "      <tr>\n";
    echo "        <td align='center'><input type='button' name='selecionE' title='Enviar todos para esquerda' value='<<' onclick='js_incluir_todos(document.form1.objeto2,document.form1.objeto1);'></td>\n";
    echo "      </tr>\n";
    echo "    </table>\n";
    echo "  </td>\n";
    echo "  <td>\n";
    echo "    <fieldset>\n";
    echo "      <Legend align='left'>\n";
    echo "        <b>Bases selecionadas</b>\n";
    echo "      </Legend>\n";
    echo "      <select name='objeto2' id='objeto2' size='25' style='width:350px' multiple onDblClick='js_incluir_item(this,document.form1.objeto1)'>\n";
    for($i=0;$i<$numrows_selecionadas;$i++){
      db_fieldsmemory($result_selecionadas,$i);
      echo "        <option value='$bsel'>$bsel - $dsel</option>\n";
    }
    echo "      </select>\n";
    echo "    </fieldset>\n";
  }
?>
          </td>
        </tr>
        <tr>
          <td colspan='6' align='center'>
            <b>Dois Clicks Movimenta as Bases</b>
          </td>
        </tr>
        <tr>
          <td colspan='6' align='center'>
            <input type="button" name="cadastrar" value="Cadastrar" onclick="js_retornacampos();">
          </td>
        </tr>
      </table>
    </td>
  </tr>
  </form>
</table>
</center>
</body>
<script>
function js_retornacampos(){
  obj1 = document.form1.objeto1;
  obj2 = document.form1.objeto2;
  
  txt1 = document.form1.sselecionados;
  txt2 = document.form1.nselecionados;
  
  txt1.value = "";
  txt2.value = "";
  
  txt22 = "";
  vir22 = "";
  
  txt11 = "";
  vir11 = "";

  for(i=0;i<obj1.length;i++){
    txt22 += vir22+obj1.options[i].value;
    vir22 = ",";
  }

  for(i=0;i<obj2.length;i++){
    txt11 += vir11+obj2.options[i].value;
    vir11 = ",";
  }
  
  obj=document.createElement('input');
  obj.setAttribute('name','incluir');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','incluir');
  document.form1.appendChild(obj);
  
  txt1.value = txt11;
  txt2.value = txt22;
  
  if (obj2.length > 0) { 
    document.form1.submit();
  } else {
  
    alert('Erro:\nNenhuma base selecionada.');
    return false;
    
  }
}

// Fun��o para incluir todos os elementos do SELECT MULTIPLE escolhido no outro 
// Esta fun��o selecionar� todos os elementos do SELECT e chamar� a fun��o js_incluir_item para enviar os itens 
// para o SELECT desejado. Quando retornar da fun��o js_incluir_item, ela limpar� o select remetente  
function js_incluir_todos(obj1,obj2){
  for(i=0;i<obj1.length;i++){    
    obj1.options[i].selected = true;
  }
  linhasoption = obj2.length; 
  js_incluir_item(obj1,obj2);
  obj1.length = 0;
  if(linhasoption == 0){
    for(i=0;i<obj2.length;i++){
      obj2.options[i].selected = false;
    }
  }
}

// Esta fun��o serve para passar os itens de um SELECT para o outro.
function js_incluir_item(obj1,obj2){
  var erro = 0;
  
  // Tirar o foco de todos os itens do select RECEPTOR
  for(i=0;i<obj2.length;i++){
    obj2.options[i].selected = false;
  }

  // Verifica a quantidade de itens no SELECT EMISSOR
  for(i=0;i<obj1.length;i++){
  
    // Testa se o item corrente esta selecionado
    if(obj1.options[i].selected){
    
      // Seta o valor defaul do novo item do SELECT RECEPTOR
      x = obj2.length;

      // Se a quantidade de itens do SELECT RECEPTOR for maior que zero, testa se encontra algum item que o valor
      // seja maior que o item corrente do SELECT EMISSOR 
      if(obj2.length > 0){
        for(x=0;x<obj2.length;x++){
          if(obj1.options[i].value < obj2.options[x].value){
            break;
          }
        }
        
        // Repete no SELECT RECEPTOR o seu �ltimo item
        obj2.options[obj2.length] = new Option(obj2.options[obj2.length-1].text,obj2.options[obj2.length-1].value);
        
        // Busca todos os itens que o valor � menor que o �ltimo item e reorganiza os dados dentro do SELECT
        for(y=obj2.length-1;x<y;y--){
          obj2.options[y] = new Option(obj2.options[y-1].text,obj2.options[y-1].value);        
        }
      }
      
      // Inclui o item que esta vindo do select EMISSOR
      obj2.options[x] = new Option(obj1.options[i].text,obj1.options[i].value);
      obj2.options[x].selected = true;
      erro ++;
    }
  }
  if(erro > 0){
  	// Tira a sele��o dos itens do SELECT EMISSOR
    for(i=0;i<obj1.length;i++){
      if(obj1.options[i].selected){
        obj1.options[i] = null;
        i = -1;
      }
    }
  }else{
    alert("Selecione um item");
  }
  js_trocacordeselect();
}
</script>
</html>