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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Importar dados da escola para a secretaria</b></legend>
    <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table border="0">
     <tr>
      <td nowrap title="<?=@$Ted17_i_turno?>">
       <?db_ancora("<b>Escola:</b>","js_pesquisaed129_i_escola(true);",$db_opcao);?>
      </td>
      <td>
       <?db_input('ed129_i_escola',15,@$Ied129_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed129_i_escola(false);'")?>
       <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td colspan="2">
       <b>Arquivo:</b>&nbsp;&nbsp;&nbsp;
       <?db_input('arquivo',80,"",true,'file',$db_opcao,'')?><br><br>
       <input type="button" value="Importar" name="processar" onclick="js_processar();">
      </td>
     </tr>
    </table>
    <br>
    </form>
    <?
    if(isset($GLOBALS["_FILES"]["arquivo"]) && $GLOBALS["_FILES"]["arquivo"]!=""){
     db_postmemory($GLOBALS["_FILES"]["arquivo"]);
     $escola = $ed129_i_escola;
     $base = db_base_ativa();   ///DB_BASE_ATIVA();*******
     $sql = "select ed129_i_ultatualizes from escola_sequencias
             where ed129_i_escola = $escola
            ";
     $result = pg_query($sql);
     $ultima_atualizacaoes = trim(pg_result($result,0,'ed129_i_ultatualizes'));
     $array_arquivo = explode("_",$name);
     $escola_arquivo = trim($array_arquivo[0]);
     $base_arquivo = trim($array_arquivo[1]);
     $data_arquivo = trim($array_arquivo[2]);
     $tipo_arquivo = trim(substr($array_arquivo[3],0,2));
     $nome_arquivo = $escola_arquivo."_".$base_arquivo."_".$data_arquivo."_".$tipo_arquivo;
     $arquivo_tar = $nome_arquivo.".tar";
     $arquivo_sql = $nome_arquivo.".sql";
     $erro = false;
     if($tipo_arquivo!="ES"){
      db_msgbox("Arquivo inválido!");
      $erro = true;
      $ultima_atualizacaoes = 0;
     }
     if($data_arquivo<=$ultima_atualizacaoes){
      db_msgbox("Arquivo já importado! Selecione um arquivo mais recente!");
      $erro = true;
     }
     if($escola!=$escola_arquivo){
      db_msgbox("Arquivo inválido! Este arquivo pertence a outra escola!");
      $erro = true;
     }
     if($erro==true){
      db_redireciona("edu4_importar_se.php");
     }else{
      @copy($tmp_name,"tmp/".$name);
      system("bunzip2 tmp/".$name);
      echo "...Descompactado arquivo ";
      system("tar -xvf tmp/".$arquivo_tar);
      echo "<br>";
      $open = "tmp/".$arquivo_sql;
      $ponteiro = fopen($open, "r" );
      $erro = false;
      pg_exec("begin");
      while (!feof($ponteiro)) {
       $linha = trim(fgets($ponteiro,3000));
       if(empty($linha)){
        continue;
       }
       if(substr($linha,0,2)!="--"){
        if(substr($linha,12,10)=="rechumano "){
         $cod_rh = str_replace(");","",trim(substr($linha,29)));
         $sql2 = "select * from rechumano where ed20_i_codigo = $cod_rh";
         $result2 = pg_query($sql2);
         $linhas2 = pg_num_rows($result2);
         if($linhas2==0){
          $sql1 = $linha;
          $result1 = pg_query($sql1);
         }
        }
        /*
        elseif(substr($linha,12,10)=="atestvaga "){
         echo trim(substr($linha,0,6));
         if(trim(substr($linha,0,6))=="INSERT"){
          $cod = str_replace(");","",trim(substr($linha,29)));
          $cod = explode(",",$cod_atest);
          echo ">>>>>>>>>>>>>".$cod_atest = $cod[0];
         }else{
          $sql1 = $linha;
          $result1 = pg_query($sql1);
         }
        }
        */
        else{
         $sql1 = $linha;
         $result1 = pg_query($sql1);
        }
        if(!$result1){
         $erro = true;
         echo $sql1;
         break;
        }else{
         $array = explode(" ",$linha);
         if(substr($linha,0,6)=="UPDATE"){
          $tabela = $array[1];
         }else{
          $tabela = $array[2];
         }
         echo "...".substr($linha,0,6)." tabela $tabela(".pg_affected_rows($result1).")<br>";
        }
       }
      }
      if($erro==true ){
       pg_exec("rollback");
       db_msgbox("Erro ao importar dados");
      }else{
       pg_exec("commit");
       db_msgbox("Dados importados com sucesso!");
      }
      system("rm tmp/".$name);
      system("rm tmp/".$arquivo_tar);
      system("rm tmp/".$arquivo_sql);
      db_redireciona("edu4_importar_se.php");
     }
    }
    ?>
   </fieldset>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed129_i_escola",true,1,"ed129_i_escola",true);
function js_processar(){
 if(document.form1.ed129_i_escola.value==""){
  alert("Informe a escola para a importação!");
  document.form1.ed129_i_escola.focus();
 }else if(document.form1.arquivo.value==""){
  alert("Informe o arquivo de importação!");
  document.form1.arquivo.focus();
 }else{
  document.form1.submit();
 }
}
function js_pesquisaed129_i_escola(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escola','func_escola_sequencias.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_c_nome','Pesquisa Escolas Locais',true);
 }else{
  if(document.form1.ed129_i_escola.value != ''){
   js_OpenJanelaIframe('','db_iframe_escola','func_escola_sequencias.php?pesquisa_chave='+document.form1.ed129_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa Escolas Locais',false);
  }else{
   document.form1.ed18_c_nome.value = '';
  }
 }
}
function js_mostraescola(chave,erro){
 document.form1.ed18_c_nome.value = chave;
 if(erro==true){
  document.form1.ed129_i_escola.focus();
  document.form1.ed129_i_escola.value = '';
 }
}
function js_mostraescola1(chave1,chave2){
 document.form1.ed129_i_escola.value = chave1;
 document.form1.ed18_c_nome.value = chave2;
 db_iframe_escola.hide();
}
</script>