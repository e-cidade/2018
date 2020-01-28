<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

db_postmemory($HTTP_POST_VARS);

$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clrotulo->label('q03_ativ');

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

 var vir        = "";
 var sListaRuas = "";
 
 for(x=0;x<document.form1.ruas.length;x++){
   sListaRuas = sListaRuas+vir+document.form1.ruas.options[x].value;
   vir   = ",";
 }
 
 var sQuery ='?baix=' +document.form1.baix.value
            +'&sListaRuas='+sListaRuas
            +'&ver='  +document.form1.ver.value
            +'&tipo=' +document.form1.tipo.value
            +'&tipoAtividade='+document.form1.tipoatividade.value;
 
 var jan = window.open('fis2_lograd002.php'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table style="padding-top:25px;" align="center" border="0">
    <form name="form1" method="post" action="">
       <tr>
         <td align="center">
           <b>Opções :&nbsp;&nbsp;&nbsp;</b>
            <?
               $aVer = array("com"=>"Com as Ruas selecionadas",
                             "sem"=>"Sem as Ruas selecionados");
               db_select("ver",$aVer,true,2); 
            ?>           
          </td>
       </tr>
      <tr>
        <td>
			    <?
			      $aux->cabecalho      = "<b>Ruas</b>";
			      $aux->codigo         = "j14_codigo"; //chave de retorno da func
			      $aux->descr          = "j14_nome";   //chave de retorno
			      $aux->nomeobjeto     = 'ruas';
			      $aux->funcao_js      = 'js_mostra';
			      $aux->funcao_js_hide = 'js_mostra1';
			      $aux->sql_exec       = "";
			      $aux->func_arquivo   = "func_ruas.php";  //func a executar
			      $aux->nomeiframe     = "db_iframe_ruas";
			      $aux->localjan       = "";
			      $aux->onclick        = "";
			      $aux->db_opcao       = 2;
			      $aux->tipo           = 2;
			      $aux->top            = 0;
			      $aux->linhas         = 10;
			      $aux->funcao_gera_formulario();
			    ?>              
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Filtros</b>
            </legend>
	          <table>
				      <tr>
				        <td align="left" nowrap title="Todos/Não Baixados/Baixados" >
				          <b>Selecionar Inscrições :</b>
				        </td>
				        <td>
				          <?
				            $aTipoInscr = array("t"=>"Todos",
				                                "c"=>"Não Baixados",
				                                "b"=>"Baixados");
				            db_select("baix",$aTipoInscr,true,2,"style='width:150px;'"); 
				          ?>
				        </td>
				      </tr>
				      <tr>
				        <td>
				          <b>Mostrar Atividades :</b>
				        </td>
				        <td>
				          <? 
				            $aTipoAtividade = array("a"=>"Ativas",
				                                    "b"=>"Baixadas",
				                                    "0"=>"Todas");
				            db_select("tipoatividade",$aTipoAtividade,true,2,"style='width:150px;'"); 
				          ?>
				        </td>
				      </tr>         
				      <tr>
				        <td>
				          <b>Tipo:</b>
				        </td>
				        <td>
				          <? 
				            $aTipo = array( "0"=>"Todos",
				                            "t"=>"Permanente",
				                            "f"=>"Provisório");
				            db_select("tipo",$aTipo,true,2,"style='width:150px;'"); 
				          ?>
				        </td>
				      </tr>
	          </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
    </form>
  </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

function js_mostra1(sNome, sDescr, lErro) {
  if (!lErro) {
    $("j14_nome").value = sNome;
  } else {
    $("j14_codigo").value = '';
    $("j14_nome").value   = '';
  }
  document.form1.db_lanca.onclick = js_insSelectruas;
}
</script>

<?
if(isset($ordem)){
  echo "<script>
          js_emite();
        </script>";
}



$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>