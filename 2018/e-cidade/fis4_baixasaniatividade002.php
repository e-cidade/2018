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
require_once("libs/db_usuariosonline.php");
require_once("classes/db_saniatividade_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_sanibaixa_classe.php");
require_once("classes/db_sanibaixaproc_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsaniatividade = new cl_saniatividade;
$clsanibaixa     = new cl_sanibaixa;
$clsanibaixaproc = new cl_sanibaixaproc;
$db_botao = false;
$cliframe_seleciona = new cl_iframe_seleciona;

$clsanitario = new cl_sanitario;
$clsaniatividade->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y80_numcgm");
$clrotulo->label("q03_descr");

if(isset($HTTP_POST_VARS["db_opcao"]) && $db_opcao == "Alterar"){
	$sqlerro = false;
  if(isset($chaves) && $chaves != ""){
    db_inicio_transacao();
    $chaves = split("#",$chaves);
    $db_opcao = 2;
    $HTTP_POST_VARS["y83_dtfim_dia"] = "";
    $HTTP_POST_VARS["y83_dtfim_mes"] = "";
    $HTTP_POST_VARS["y83_dtfim_ano"] = "";
    
    $HTTP_POST_VARS["y83_databx_dia"] = "";
    $HTTP_POST_VARS["y83_databx_mes"] = "";
    $HTTP_POST_VARS["y83_databx_ano"] = "";
    
    for($i=0;$i<sizeof($chaves);$i++){
      $seq = str_replace("-","",strstr($chaves[$i],"-"));
      $clsaniatividade->y83_dtfim = "";
      $clsaniatividade->y83_databx = "";
      $clsaniatividade->y83_seq = $seq;
      $clsaniatividade->alterar($y83_codsani,$seq);
			
      $clsanibaixaproc->excluir($y83_codsani,$seq);
      if ($clsanibaixaproc->erro_status==0){
      	$sqlerro==true;
      }
      $clsanibaixa->excluir($y83_codsani,$seq);
      if ($clsanibaixa->erro_status==0){
      	$sqlerro==true;
      }
    }
    $result = $clsanitario->sql_record($clsanitario->sql_query("","*",""," y80_codsani = $y83_codsani"));
    if($clsanitario->numrows > 0){
      db_fieldsmemory($result,0);
      $HTTP_POST_VARS["y80_dtbaixa_dia"] = "";
      $HTTP_POST_VARS["y80_dtbaixa_mes"] = "";
      $HTTP_POST_VARS["y80_dtbaixa_ano"] = "";
      if($y80_dtbaixa != ""){
        $clsanitario->y80_dtbaixa= "";
        $clsanitario->y80_codsani=$y83_codsani;
        $clsanitario->alterar($y83_codsani);
      }
    }    
    $clsaniatividade->erro(true,false);
    db_fim_transacao($sqlerro);
    db_redireciona("fis4_baixasaniatividade002.php?y83_codsani=$y83_codsani");
  }else{
    db_redireciona("fis4_baixasaniatividade002.php?erro=$y83_codsani");
  }
}elseif(isset($y83_codsani) && $y83_codsani != ""){
  $result = $clsaniatividade->sql_record($clsaniatividade->sql_query("","","*",""," y83_codsani = $y83_codsani and y83_dtfim is null"));
  if($clsaniatividade->numrows > 0){
    db_fieldsmemory($result,0);
    $db_botao = true;
  }
}
    
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" >
    <div class="container">

      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Excluir Baixa</legend>

          <table>
            <tr>
              <td nowrap title="<?php echo @$Ty83_codsani; ?>">
                <?php
                  db_ancora(@$Ly83_codsani,"js_pesquisay83_codsani(true);",1);
                ?>
              </td>
              <td> 
                <?php
                  db_input('y83_codsani',10,$Iy83_codsani,true,'text',1," onchange='js_pesquisay83_codsani(false);'");
                  db_input('z01_nome',35,$Iz01_nome,true,'text',3,'');
                ?>
              </td>
            </tr>

            <tr>
              <td align="top" colspan="2">
                <?php
                  if(isset($y83_codsani) && $y83_codsani != ""){
                    $cliframe_seleciona->campos  = "y83_seq,y83_dtini,y83_dtfim,y83_area,y83_codsani,q03_descr";
                    $cliframe_seleciona->legenda="ATIVIDADES BAIXADAS";
                    $cliframe_seleciona->sql=$clsaniatividade->sql_query("",""," saniatividade.*,q03_descr ",""," y83_codsani = {$y83_codsani} and y83_dtfim is not null ");
                    $cliframe_seleciona->textocabec ="darkblue";
                    $cliframe_seleciona->textocorpo ="black";
                    $cliframe_seleciona->fundocabec ="#aacccc";
                    $cliframe_seleciona->fundocorpo ="#ccddcc";
                    $cliframe_seleciona->iframe_height ="250";
                    $cliframe_seleciona->iframe_width ="700";
                    $cliframe_seleciona->iframe_nome ="atividades";
                    $cliframe_seleciona->chaves ="y83_codsani,y83_seq";
                    $cliframe_seleciona->iframe_seleciona(@$db_opcao);    
                  }  
                ?>
              </td>
            </tr>  
          </table>
        </fieldset>

        <input name="db_opcao" type="submit" value="Alterar" <?php echo (!isset($y83_codsani) || $y83_codsani == ""?'disabled':''); ?> onClick="return js_gera_chaves();">
      </form>

    </div>

    <?php
      db_menu( db_getsession("DB_id_usuario"),
               db_getsession("DB_modulo"),
               db_getsession("DB_anousu"),
               db_getsession("DB_instit") );
    ?>

    <script>
      function js_pesquisay83_codsani(mostra){
        if(mostra == true){
          js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_mostrasanitario1|y80_codsani|z01_nome','Pesquisa',true);
        }else{
          js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y83_codsani.value+'&funcao_js=parent.js_mostrasanitario','Pesquisa',false);
        }
      }

      function js_mostrasanitario(chave,erro){
        document.form1.z01_nome.value = erro;
        if(erro==true){ 
          document.form1.y83_codsani.focus(); 
          document.form1.y83_codsani.value = ''; 
        }
        document.form1.submit();
      }

      function js_mostrasanitario1(chave1,chave2){
        document.form1.y83_codsani.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_sanitario.hide();
        document.form1.submit();
      }
    </script>
  </body>
</html>
<?php
  if (isset($erro)) {
    echo "<script>alert('selecione uma atividade antes de dar baixa');</script>";
    echo "<script>location.href='fis4_baixasaniatividade001.php?y83_codsani={$erro}';</script>";
  }
?>