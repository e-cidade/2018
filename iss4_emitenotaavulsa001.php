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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_issnotaavulsaservico_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_issnotaavulsatomador_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_arrehist_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_parissqn_classe.php");
include("classes/db_issnotaavulsanumpre_classe.php");
include("dbforms/db_funcoes.php");

$clissnotaavulsaservico = new cl_issnotaavulsaservico;
$clissnotaavulsa        = new cl_issnotaavulsa;
$clissnotaavulsatomador = new cl_issnotaavulsatomador;
$clparissqn             = new cl_parissqn;
$get                    = db_utils::postmemory($_GET);
$post                   = db_utils::postmemory($_POST);
$clrotulo               = new rotulocampo;
$clrotulo->label("q51_numnota");
$clrotulo->label("q63_issnotaavulsa");
$rsPar                  = $clparissqn->sql_record($clparissqn->sql_query(null,"*"));
$oPar                   = db_utils::fieldsMemory($rsPar,0); 
(int)   $db_opcao       = 1;
(bool)  $lLiberaRecibo  = false;
(bool)  $lLiberaNota    = false;
(string)$sErroMsg       = null;
(string)$sLabelNota     = "Emite Nota Avulsa";
(string)$sNotaCall      = "notaavulsa";
(string)$sLabelRecibo   = "Emite Recibo";

/*
 verifica a situacao da nota
*/
if (isset($post->sender)){

  $rsNota    = $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query_emitidos($post->q63_issnotaavulsa,"distinct *"));
  $totalNota = $clissnotaavulsa->issqnAPagar($post->q63_issnotaavulsa);
  if ($clissnotaavulsa->numrows > 0){
     
     $oNota     = db_utils::fieldsMemory($rsNota,0);
     if ($oNota->q69_issnotaavulsa != null){
        //Essa nota ja foi emita. (apenas pode ser reemitida);     
        $sLabelNota  = "Reemitir Nota Avulsa";
        $sNotaCall   = "reemitenota";
        $lLiberaNota = true;
     }else if ($oNota->q69_issnotaavulsa == null && $oNota->q52_numpre != null){
        $lLiberaNota = true;
     }else if ($oNota->q52_numpre != null){

        $sLabelNota  = "Reemitir Nota Avulsa";
        $sNotaCall   = "reemitenota";
        $lLiberaNota = true;
        echo "aqui ";
     }else if ($totalNota >= $oPar->q60_notaavulsavlrmin){
 
        $lLiberaRecibo = true;

     }else {

      $lLiberaNota = true;
     }
  }
}
if (isset($post->recibo)){

    if ($clissnotaavulsa->emiteRecibo($post->q63_issnotaavulsa)){
    
       $lLiberaRecibo = false;
       $lLiberaNota   = true;
    }
}
//emite a nota avulsa usa o metodo emiteNotaavulsa da classe cl_notavulsa;
if (isset($post->notaavulsa)){

    if ($clissnotaavulsa->emiteNotaAvulsa($post->q63_issnotaavulsa)){
    
       $lLiberaRecibo = false;
       $lLiberaNota   = true;
       $sLabelNota    = "Reemitir Nota Avulsa";

    }else{

      db_msgbox("houve um erro ao Emitir a nota\\nErro:".$clissnotaavulsa->erro_msg);
      $lLiberaNota   = true;
      $sLabelNota    = "Reemitir Nota Avulsa";
    }
}
/*
** Faz apenas a reemissao.
*/
if (isset($post->reemitenota)){
    
   echo "<script>window.open('iss2_issnotaavulsanotafiscal002.php?q51_sequencial=".$post->q63_issnotaavulsa."','','location=0');</script>";
   $lLiberaNota   = true;
   $sLabelNota    = "Reemitir Nota Avulsa";
}

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table  border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <center>
  <form name='form1' method='post'>
  <table>
  <tr>
    <td>
       <fieldset>
         <legend><b>Emissão/Reemisão Nota Avulsa</b></legend>
         <table>
           <tr>
             <td>
              <?
              db_ancora(@$Lq51_numnota,"js_pesquisaq63_issnotaavulsa(true);",$db_opcao);
              ?>
              </td>
              <td> 
              <?
              db_input('q63_issnotaavulsa',10,$Iq63_issnotaavulsa,true,'hidden',$db_opcao,"onchange='js_pesquisaq63_issnotaavulsa(false);'");
              db_input('q51_numnota',10,$Iq51_numnota,true,'text',3,"onchange='js_pesquisaq63_issnotaavulsa(false);'");
              db_input('z01_nome',40,'',true,'text',3,'')
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
     </td>
    </tr>
    </table>
  <input name="recibo" type="submit"  id="recibo"  <?=$lLiberaRecibo == true?null:" disabled ";?>value="Emitir Recibo">
  <input name="<?=$sNotaCall;?>" type="submit" id="notaavulsa" <?=$lLiberaNota == true?null:" disabled ";?>value="<?=$sLabelNota;?>">
  <input name="sender" type="submit" id="sender"  style='display:none' value="enviar">
    </form>
  </center>
  </body>
</html>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisaq63_issnotaavulsa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsaalt.php?funcao_js=parent.js_mostraissnotaavulsa1|q51_sequencial|z01_nome|q51_numnota','Pesquisa',true);
  }else{
     if(document.form1.q51_numnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsaalt.php?pesquisa_chave='+document.form1.q51_numnota.value+'&funcao_js=parent.js_mostraissnotaavulsa','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraissnotaavulsa(chave,erro,chave2){
  document.form1.z01_nome.value       = chave; 
  document.form1.q63_sequencial.value = chave2; 
  if(erro==true){ 
    document.form1.q51_numnota.focus(); 
    document.form1.q51_numnota.value = ''; 
  }
}
function js_mostraissnotaavulsa1(chave1,chave2,chave3){
  document.form1.q63_issnotaavulsa.value = chave1;
  document.form1.z01_nome.value          = chave2;
  document.form1.q51_numnota.value       = chave3;
  db_iframe_issnotaavulsa.hide();
  $('sender').click();
}
</script>