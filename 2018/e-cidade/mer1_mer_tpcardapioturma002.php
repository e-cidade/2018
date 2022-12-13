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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_mer_tpcardapioturma_classe.php");
include("classes/db_mer_cardapioescola_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmer_tpcardapioturma = new cl_mer_tpcardapioturma;
$clmer_cardapioescola = new cl_mer_cardapioescola;
if(isset($coddisciplinas)){
 $coddisciplinas = explode(",",$coddisciplinas);
 for($r=0; $r<count($coddisciplinas); $r++){
  $teste = $coddisciplinas[$r];
  $db_opcao = 2;
  db_inicio_transacao();
  $clmer_tpcardapioturma->excluir("","me28_i_cardapioescola = $teste");
  $sql = "select ed11_i_codigo from mer_tpcardapioturma 
           inner join serie on serie.ed11_i_codigo = mer_tpcardapioturma.me28_i_serie
           where me28_i_cardapioescola =$codcardapioescola";
 
  $result = pg_query($sql);
  $linhas = pg_numrows($result);
  
  if ($linhas>0) {     
    for ($t=0;$t<$linhas;$t++) {  
    	db_fieldsmemory($result,$t);
      $clmer_tpcardapioturma->me28_i_serie = $ed11_i_codigo;
      $clmer_tpcardapioturma->me28_i_cardapioescola = $teste;
      $clmer_tpcardapioturma->incluir(null);
      
    }
  }
  db_fim_transacao();
 
 	
 }
 if ($clmer_tpcardapioturma->erro_status=="0") {
    $clmer_tpcardapioturma->erro(true,false);
  } else{?>
    <script>        
    parent.db_iframe_tpcardapioturma.hide();    
    top.corpo.iframe_a2.location.href      = 'mer1_mer_cardapioescola001.php?me32_i_tipocardapio=<?=$iCodCardapio?>&me27_c_nome=<?=$nome?>&db_opcao=<?=$db_opcao?>';
    </script>
       
 <? }
 db_msgbox("Alterações efetuadas com sucesso!");
 exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <?
   $result = $clmer_cardapioescola->sql_record($clmer_cardapioescola->sql_query("",
                                                                             "me32_i_codigo,ed18_c_nome",
                                                                             "",
                                                                             "me32_i_tipocardapio = $codcardapio and me32_i_codigo not in ($codcardapioescola)"
                                                                            ));
   $linhas = pg_num_rows($result);
   if($linhas>0){
    ?>
    <select name="outras_disc[]" id="outras_disc" size="10" style="width:300px;font-size:10px;padding:0px;" multiple>
    <?
    for($r=0;$r<$linhas;$r++){
     db_fieldsmemory($result,$r);
     ?>
      <option value="<?=$me32_i_codigo?>"> <?=$ed18_c_nome?></option>
     <?
    }
    ?>
    </select>
    <br><br>
    <input type="button" value="Confirmar" onClick="js_confirmaserie();">
    <input type="button" value="Cancelar" onClick="js_fechar();">
   <?}else{
    ?>
    <script>
     parent.db_iframe_tpcardapioturma.hide();
    </script>
    <?
    exit;
   }?>
  </td>
 </tr>
 <tr>
  <td align="center" valign="top">
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
 function js_confirmaserie(){
  qtd = document.form2.outras_disc.length;
  sel = 0;
  coddisciplinas = "";
  sep = "";
  for(i=0;i<qtd;i++){
   if(document.form2.outras_disc.options[i].selected==true){
    sel++;
    coddisciplinas += sep+document.form2.outras_disc.options[i].value;
    sep = ",";
   }
  }
  if(sel==0){
   alert("Seleciona alguma escola!");
  }else{
   location.href ='mer1_mer_tpcardapioturma002.php?coddisciplinas='+coddisciplinas+'&iCodCardapio=<?=$codcardapio?>&nome=<?=$nome?>&db_opcao=<?=$db_opcao?>&codcardapioescola=<?=$codcardapioescola?>';
  }
 }
 function js_fechar(){
  parent.db_iframe_tpcardapioturma.hide();
 }
</script>