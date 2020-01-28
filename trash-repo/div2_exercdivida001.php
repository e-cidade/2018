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
include("classes/db_proced_classe.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clpostgresqlutils = new PostgreSQLUtils;
$clproced          = new cl_proced;
$clrotulo          = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 4;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function js_marca() {

  var ID = document.getElementById('marca');
  //var BT = document.getElementById('btmarca');
  if(!ID)
     return false;
     var F = document.form1;
  if(ID.innerHTML == 'D') {
     var dis = false;
     ID.innerHTML = 'M';
  } else {
     var dis = true;
     ID.innerHTML = 'D';
  }
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox"){
        F.elements[i].checked = dis;
     }
  }
  js_verifica();
}

function js_verifica(){
  var marcas = false;
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
        marcas = true;
     }
  }
}

function js_emite(){
  var exerc = '';
  var xvirg = '';
  var F = document.form1;
  for(i = 0;i < F.elements.length;i++) {
     if(F.elements[i].type == "checkbox" &&  F.elements[i].checked ){
        exerc += xvirg+F.elements[i].value;
  xvirg  = '-';
     }
  }
  
  if (exerc != ''){
    exerc = 'exerc='+exerc;
  } 

   var H = document.getElementById("procedencia").options;
   if(H.length > 0){
      campo = 'proced=';
      virgula = '';
      for(var i = 0;i < H.length;i++) {
         campo += virgula+H[i].value;
         virgula = '-';
      }
   }else{
      campo = '';
   }
   if (exerc == ''){
     alert('Nenhum exercicio foi selecionado.Verifique!');
     return false
   }
  itemselecionado = 0;
  numElems = document.form1.ordemtipo.length;


  for (i=0;i<numElems;i++) {
      if (document.form1.ordemtipo[i].checked) itemselecionado = i;
  }
  ordemtipo = document.form1.ordemtipo[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordem.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordem[i].checked) itemselecionado = i;
  }
  ordem = document.form1.ordem[itemselecionado].value;


 jan = window.open('div2_exercdivida002.php?analitico='+document.form1.analitico.value+
                   '&considera_debitos='+document.form1.considera_debitos.value+
                   '&valor_inicial='+document.form1.valor_inicial.value+
                   '&valor_final='+document.form1.valor_final.value+
                   '&'+campo+
                   '&ordem='+ordem+
                   '&numerolista='+document.form1.numerolista2.value+
                   '&ordemtipo='+ordemtipo+
                   '&agproced='+document.form1.agproced.value+
                   '&agexerc='+document.form1.agexerc.value+
                   '&sele='+document.form1.sele.value+
                   '&tipo='+document.form1.tipo.value+
                   '&'+exerc,
                   '','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC  onLoad="a=1">
<form class="container" name="form1" method="post" action="" >
  <table border="1" align="center">    
      <tr>
        <td>
        <table  align="center" border="0" cellspacing="1" >
          <tr>
            <td>
              <strong>Exercícios</strong>
          </td>
          </tr>
          <tr height="20" bgcolor="#FFCC66">
             <th class="borda" align="center" style="font-size:12px" nowrap>
               <a id="marca" href="#" style="color:black" onclick="js_marca();return false">D</a>
             </th>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
          <tr>
         </tr>
         <?
           $cor = '#E4F471';
           $sql = "select distinct (v01_exerc) as exerc from divida where v01_instit = ".db_getsession('DB_instit');
           $rs  = db_query($sql);
         ?>
         <tr style="cursor: hand; height: 20px" bgcolor="<?=$cor?>">
         <? 
           for($x = 0;$x < pg_num_rows($rs);$x++){
             if ($cor == '#E4F471'){
               $cor = '#EFE029';
             } else if ($cor == '#EFE029') {
               $cor = '#E4F471';
             }                   

             $exercicio = pg_result($rs,$x,"exerc");
             if ($x % 3 == 0 && $x != 0) {
               echo "</tr><tr style='cursor: hand; height: 20px' bgcolor=$cor>";
             }
         ?>
           <td height="20px" width="33%" class="borda" style="font-size:11px" align="center" id="check<?=$i?>" nowrap>
             <input type="checkbox" value="<?=$exercicio?>" name="check<?=$i?>" 
                    checked onclick="js_verifica()"><?=$exercicio?>
           </td>
         <?
           }
         ?>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         </table>
         <fieldset class="separator">
         	<legend>Filtros</legend>
         <table class="form-container">
         <tr>
            <td title='Soma no valor os débitos posteriores ao execcio selecionado'>
              <strong>Considera Débitos Posteriores:</strong>
            </td>
            <td>
              <?
                $arr = array("0"=>"Não","1"=>"Sim");
                db_select('considera_debitos',$arr,true,$db_opcao,"");
              ?>&nbsp;
            </td>
         </tr>
         <tr>
            <td title="Escolha um tipo para emissão do relatório.">
              Tipo:
            </td>
            <td>
              <?
                $arr = array("0"=>"Sintético","1"=>"Analítico");
                db_select('analitico',$arr,true,$db_opcao,"");
              ?>
            </td>
         </tr>
         <tr>
            <td title='Faixa de valor'>
              <strong>Valores de:</strong>
            </td>
            <td>
              <?
                $valor_inicial = 0;
                db_input('valor_inicial',10,'',true,'text',$db_opcao);
              ?> A
              <?
                $valor_final   = 999999999999;
                db_input('valor_final',10,'',true,'text',$db_opcao);
              ?>&nbsp;
            </td>
         </tr>
         <tr>
            <td>             
              Tipo:
            </td>
            <td>
              <?
                $arr = array("numcgm"=>"Nome","matric"=>"Matrícula","inscr"=>"Inscrição");
                db_select('tipo',$arr,true,$db_opcao,"");
              ?>
            </td>
         </tr>
         <tr>
            <td>
              Agrupar :
            </td>
         </tr>
         <tr>
            <td>
              Exercício
            </td>
            <td>
              <?
                $arr1 = array("N"=>"Não","S"=>"Sim");
                db_select('agexerc',$arr1,true,$db_opcao,"");
              ?>
            </td>
         </tr>
         <tr>
            <td>
              Procedência
            </td>
            <td>
              <?
                $arr2 = array("N"=>"Não","S"=>"Sim");
                db_select('agproced',$arr2,true,$db_opcao,"");
              ?>
            </td>
         </tr>
         <tr>
            <td title="Quantidade de contribuintes a ser listado, ou zero para todos">
              Registros a Listar:
            </td>
            <td>
              <?
                db_input('numerolista2',10,'',true,'text',$db_opcao);
              ?>
            </td>
         </tr>
         <tr>
            <td nowrap title="Ordem do relatório">
              Ordenar por :
            </td>
            <td>
              <label for="ordem_valor1" id="lordem1">
                <input id="ordem_valor1" type="radio" name="ordem" value="z01_nome">Alfabética&nbsp;&nbsp;
              </label>
              <label for="ordem_valor3" id="lordem4">
                <input id="ordem_valor4" type="radio" name="ordem" value="numerica">Numérica&nbsp;&nbsp;
              </label>
              <label for="ordem_valor" id='lordem3' >
                <input type="radio" id="ordem_valor" name="ordem" value="valor" checked>Valor&nbsp;&nbsp;
              </label>
            </td>
         </tr>
         <tr>
           <td title="Tipo de ordem do relatório">
              Em ordem :
            </td>
            <td>
              <input type="radio" name="ordemtipo" value="asc">Ascendente&nbsp;&nbsp;&nbsp;
              <input type="radio" name="ordemtipo" value="desc" checked>Descendente
           </td>
         </tr>
        </table>
        </fieldset>
  </td>
  <td>
      <table class="form-container">
      <tr >
        <td> 
          Opção de Seleção :
        </td>
        <td>
          <?
            $x = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
            db_select('sele',$x,true,$db_opcao);
          ?>
        </td>
      </tr>
        <?
          include("dbforms/db_classesgenericas.php");
          $aux = new cl_arquivo_auxiliar;
          $aux->cabecalho      = "Selecione uma procedência";
          $aux->codigo         = "v03_codigo";
          $aux->descr          = "v03_descr";
          $aux->nomeobjeto     = 'procedencia';
          $aux->funcao_js      = 'js_funcaoproced';
          $aux->funcao_js_hide = 'js_funcaoproced1';
          $aux->sql_exec       = "";
          $aux->func_arquivo   = "func_proced.php";
          $aux->nomeiframe     = "iframa_proced";
          $aux->db_opcao       = 2;
          $aux->tipo           = 2;
          $aux->linhas         = 15;
          $aux->vwhidth        = 250;
          $aux->db_opcao       = $db_opcao;
          $aux->funcao_gera_formulario();         
        ?>
  
</table>

</td>
</tr>
</table>
<br>
<input name="db_opcao" type="button" id="db_opcao" value="Imprimir" onClick="js_emite();"
                <?=($db_botao ? '' : 'disabled')?>>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisadb02_idparag(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'cai2_emitenotif003.php?lista='+document.form1.k60_codigo.value+
                                  '&funcao_js=parent.js_mostradb_paragrafo1|1|3';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'cai2_emitenotif003.php?lista='+document.form1.k60_codigo.value+
                                  '&pesquisa_chave='+document.form1.codigo.value+
                                  '&funcao_js=parent.js_mostradb_paragrafo';
  }
}

function js_mostradb_paragrafo(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.codigo.focus(); 
    document.form1.codigo.value = ''; 
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }  
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;
  
}

function js_mostradb_paragrafo1(chave1,chave2){
  document.form1.codigo.value = chave1;
  document.form1.descr.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}

function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}

function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_pesquisanotitipo(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_notitipo.php?funcao_js=parent.js_mostranotitipo1|k51_procede|k51_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_notitipo.php?pesquisa_chave='+document.form1.k51_procede.value+
                                     '&funcao_js=parent.js_mostranotitipo';
     }
}

function js_mostranotitipo(chave,erro){
  document.form1.k51_descr.value = chave;
  if(erro==true){
     document.form1.k51_descr.focus();
     document.form1.k51_descr.value = '';
  }
}

function js_mostranotitipo1(chave1,chave2){
     document.form1.k51_procede.value = chave1;
     document.form1.k51_descr.value = chave2;
     db_iframe.hide();
}

function js_pesquisalista(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+
                                     '&funcao_js=parent.js_mostralista';
     }
}

function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  }
}

function js_mostralista1(chave1,chave2){
     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
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
?>

<script>

$("considera_debitos").setAttribute("rel","ignore-css");
$("considera_debitos").addClassName("field-size2");
$("considera_debitos").setAttribute("rel","ignore-css");
$("analitico").addClassName("field-size3");
$("valor_inicial").addClassName("field-size3");
$("valor_final").addClassName("field-size3");
$("agexerc").setAttribute("rel","ignore-css");
$("agexerc").addClassName("field-size2");
$("agproced").setAttribute("rel","ignore-css");
$("agproced").addClassName("field-size2");
$("numerolista2").addClassName("field-size2");

$("fieldset_procedencia").addClassName("separator");
$("procedencia").setAttribute("rel","ignore-css");
$("procedencia").style.width = "100%";
$("v03_codigo").addClassName("field-size2");
$("v03_descr").addClassName("field-size7");

</script>