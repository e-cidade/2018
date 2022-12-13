<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_lote_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
include(modification("classes/db_proced_classe.php"));

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$aux      = new cl_arquivo_auxiliar;
$clproced = new cl_proced;
$cllote   = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$clrotulo = new rotulocampo;
$clrotulo->label('v01_proced');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

<form class="container" name="form1" method="post"  action="div2_divipnome001.php">
  <fieldset style="width: 400px;">
    <legend>Dívida por Contribuinte</legend>
    <table class="form-container" >
    <tr>
        <td>
        Opções:
             <select id="ver" name="ver">
                 <option  value="com">Com as procedencias selecionadas</option>
                 <option  value="sem">Sem as procedencias selecionadas</option>
             </select>
       </td>
    </tr>
    <tr>
       <td colspan="2">
            <?php
              // $aux = new cl_arquivo_auxiliar;
              $aux->cabecalho = "<strong>Procedencias</strong>";
              $aux->codigo = "v03_codigo"; //chave de retorno da func
              $aux->descr  = "v03_descr";   //chave de retorno
              $aux->nomeobjeto = 'lista';
              $aux->funcao_js = 'js_mostra';
              $aux->funcao_js_hide = 'js_mostra1';
              $aux->sql_exec  = "";
              $aux->func_arquivo = "func_proced.php";  //func a executar
              $aux->nomeiframe = "db_iframe_cgm";
              $aux->localjan = "";
              $aux->onclick = "";
              $aux->db_opcao = 2;
              $aux->tipo = 2;
              $aux->top = 0;
              $aux->linhas = 5;
              $aux->vwhidth = 400;
						 if (isset($botao)  && count(@$lista) > 0) {

						 	$sWhere = "v03_codigo in(".implode(",",$lista).")";
						    $aux->sql_exec = $clproced->sql_query("",
							                                      "v03_codigo, v03_descr",
								                                  "v03_codigo",
																  "{$sWhere} and v03_instit=".db_getsession('DB_instit'));

						 }
              $aux->funcao_gera_formulario();

           ?>
       </td>
    </tr>
    <tr>
        <td><!-- iframe --><center>
		  <?
		        if (isset($botao)){
		        $vir = "";
		        $listas = "";
		        if (isset($lista)){
		          $tamanho= sizeof($lista);
		          for ($x=0;$x < $tamanho;$x++){
		            $listas .= $vir.$lista[$x];
		            $vir = ",";
		          }
		        }else {
			  $lista="";
			  }/*
		          $vir = "";
		          $listas = "";
			  $result2= db_query("select v01_proced from divida");
		          for ($x7=0;$x7< pg_numrows($result2);$x7++){
			      db_fieldsmemory($result2,$x7);
			      $listas.=$vir.$v01_proced;
			      $vir=",";
			  }
			  }*/
		           $where = "";
		         if ((isset($lista))&&($lista!="")){
		            if (isset($ver) and $ver=="com"){
		                 $where = " where  v01_proced in ($listas) ";
		          } else {
		            $where =  "  where v01_proced = v03_codigo and v01_proced not in ($listas) and v01_instit = ".db_getsession('DB_instit');

		           }
		         }
		         //echo $txt;exit;

		         $txt = " select distinct v01_exerc
		     	              from divida
		             		         inner join arrecad on v01_numpre = k00_numpre
		                   		                     and v01_numpar = k00_numpar
		                   			 inner join proced on divida.v01_proced = proced.v03_codigo
		         					$where and divida.v01_instit = ".db_getsession('DB_instit')." order by v01_exerc";

		          $result3 = db_query($txt);
		          //db_criatabela($result);

		          //$numrows = pg_numrows($result);

		          //if ($numrows==0){
		          //  echo "Não tem anos pra procedências selecionadas";
		         // }
			  $cliframe_seleciona->textocabec ="black";
			  $cliframe_seleciona->textocorpo ="black";
			//	  $cliframe_seleciona->fundocabec ="#999999";
			//	  $cliframe_seleciona->fundocorpo ="#cccccc";
			  $cliframe_seleciona->iframe_height ="250";
			  $cliframe_seleciona->iframe_width ="450";
			  $cliframe_seleciona->iframe_nome ="proced";
			  $cliframe_seleciona->fieldset =false;

			  $cliframe_seleciona->marcador = true;
			  //$cliframe_seleciona->dbscript = "onclick=\\\"\\\"";


			  $cliframe_seleciona->campos  = "v01_exerc";
			  $cliframe_seleciona->sql = $txt;
			  $cliframe_seleciona->input_hidden = true;
			  $cliframe_seleciona->chaves ="v01_exerc";
			  $cliframe_seleciona->iframe_seleciona(1);

		    }
		  ?>
          <!--- <iframe name="proced" src="div2_divipnome0011.php?lista=<?//=@$w?>&ver=<?//=@$ver?> "  width="600" align="top"  height="150" >
 	      </iframe>-->
        </center>
        </td>
      </tr>
    </table>
  </fieldset>
  <input type="submit" name="botao"  value="Escolher Anos" onClick='js_seleciona();'>
  <input type="hidden" name="botao1"  value="<?=@$lista?>">
  <? if ((isset($botao)) && (pg_numrows($result3)!=0)) {
  ?>
  <input type="button" value="Emitir Relatório" onclick='js_gera();' >
  <?}else{?>
  <input type="button" value="Emitir Relatório" disabled >
  <?}?>
</form>
 <!---  menu -->
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<!--- -->
<script>
variavel = 1;


function js_seleciona(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].name == "lista[]"){
      for(x=0;x< document.form1.elements[i].length;x++){
        document.form1.elements[i].options[x].selected = true;
      }
    }
  }
  //jan = window.open('','safo' + variavel,'scrollbars=yes resizable=1');
  //document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);

  return true;

}

function js_gera(){
   ver = document.form1.ver.value;
   chaves = js_retorna_chaves();
   if (chaves!=""){
     while (chaves.search(/\#/)!='-1') {
              chaves=chaves.replace(/\#/,'X');
	 }
     jan = window.open('div2_divipnome002.php?lista=<?=@$listas?>&chaves='+chaves+'&ver='+ver,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
  }else alert('Nenhum ano foi escolhido!');
}

</script>

</body>
</html>
<script>
$("ver").style.width = "87%";
$("fieldset_lista").addClassName("separator");
$("v03_codigo").addClassName("field-size1");
$("v03_descr").addClassName("field-size5");
$("lista").style.width = "100%";
</script>