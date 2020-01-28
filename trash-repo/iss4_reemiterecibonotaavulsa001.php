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
include("classes/db_issnotaavulsanumpre_classe.php");
include("classes/db_issnotaavulsa_classe.php");
include("classes/db_recibopaga_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_parissqn_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label("q51_numnota");
$clrotulo->label("q63_issnotaavulsa");
$db_opcao              = 1;
$post                  = db_utils::postMemory($_POST);
$clissnotaavulsanumpre = new cl_issnotaavulsanumpre();
$clrecibopaga          = new cl_recibopaga();
$clparissqn            = new cl_parissqn;
$rsPar                 = $clparissqn->sql_record($clparissqn->sql_query(null,"*"));
$oPar                  = db_utils::fieldsMemory($rsPar,0); 
if (isset($post->reemite)){

   $rsNumpre = $clissnotaavulsanumpre->sql_record($clissnotaavulsanumpre->sql_query(null,"*",null,
                                      "q51_numnota = ".$post->q51_numnota));
   if ($clissnotaavulsanumpre->numrows > 0){


       $oNumpre  = db_utils::fieldsMemory($rsNumpre,0);
       $rsRecibo = $clrecibopaga->sql_record($clrecibopaga->sql_query(null,"*",null,"k00_numnov=".$oNumpre->q52_numnov));
       if ($clrecibopaga->numrows > 0){
         
          $oRecibo = db_utils::fieldsMemory($rsRecibo,0);
          if (db_strtotime(date("Y-m-d",db_getsession("DB_datausu"))) <= db_strtotime($oRecibo->k00_dtvenc)){
           
            $url   = "iss1_issnotaavulsarecibo.php?numpre=".$oNumpre->q52_numnov."&tipo=".$oPar->q60_tipo."&ver_inscr=".$oNumpre->q02_inscr;
			      $url  .= "&numcgm=".$oNumpre->q02_numcgm."&emrec=t&CHECK10=&tipo_debito=".$oPar->q60_tipo; 
            $url  .= "&k03_tipo=".$oPar->q60_tipo."&k03_parcelamento=f&k03_perparc=f&ver_numcgm=".$oNumpre->q02_numcgm;
            $url  .= "&totregistros=1&reemite_recibo=1&k03_numpre=".$oNumpre->q52_numnov."&k00_histtxt=";
            echo "<script>\n";
            echo "if (confirm('Reeemitir Recibo?')){\n";
            echo "   window.open('$url','','location=0');";    
            echo "}</script>";

       }else{

          $url   = "iss1_issnotaavulsarecibo.php?numpre=".$oNumpre->q52_numpre."&tipo=".$oPar->q60_tipo."&ver_inscr=".$oNumpre->q02_inscr;
			    $url  .= "&numcgm=".$oNumpre->q02_numcgm."&emrec=t&CHECK10=".$oNumpre->q52_numpre."P1&tipo_debito=".$oPar->q60_tipo; 
          $url  .= "&k03_tipo=".$oPar->q60_tipo."&k03_parcelamento=f&k03_perparc=f&ver_numcgm=".$oNumpre->q02_numcgm;
          $url  .= "&totregistros=1";
          echo "<script>\n";
          echo "if (confirm('O recibo está vencido.\\nReeemitir Recibo?')){\n";
          echo "   window.open('$url','','location=0');";    
          echo "}</script>";
         
       }

   }else{

        $url   = "iss1_issnotaavulsarecibo.php?numpre=".$oNumpre->q52_numpre."&tipo=".$oPar->q60_tipo."&ver_inscr=".$oNumpre->q02_inscr;
		    $url  .= "&numcgm=".$oNumpre->q02_numcgm."&emrec=t&CHECK10=".$oNumpre->q52_numpre."P1&tipo_debito=".$oPar->q60_tipo; 
        $url  .= "&k03_tipo=".$oPar->q60_tipo."&k03_parcelamento=f&k03_perparc=f&ver_numcgm=".$oNumpre->q02_numcgm;
        $url  .= "&totregistros=1";
        echo "<script>\n";
        echo "if (confirm(' Nâo Ha recibo lancado para essa nota.\\nGerar Recibo?')){\n";
        echo "   window.open('$url','','location=0');";    
        echo "}</script>";

   }

 }else{

    db_msgbox("Não é possivel a Reemissão do recibo para essa Nota.\\nNota sem Imposto");

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
<form name='form1' method="post">
<table>
<tr><td>
<fieldset><legend><b>Reemissão do Recibo</b></legend>
<table>
  <tr>
    <td nowrap title="<?=@$Tq51_numnota?>">
       <?
       db_ancora(@$Lq51_numnota,"js_pesquisaq63_issnotaavulsa(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q63_issnotaavulsa',10,$Iq63_issnotaavulsa,true,'hidden',$db_opcao,"onchange='js_pesquisaq63_issnotaavulsa(false);'");
db_input('q51_numnota',10,$Iq51_numnota,true,'text',$db_opcao,"onchange='js_pesquisaq63_issnotaavulsa(false);'");
?>
       <?
db_input('z01_nome',40,'',true,'text',3,'')
       ?>
    </td>
  </tr>

</table>
</fieldset>
</td></tr>
</table>
<input type='submit' name='reemite' value='Reemitir'>
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisaq63_issnotaavulsa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsacancelados.php?filtrabaixa=&funcao_js=parent.js_mostraissnotaavulsa1|q51_sequencial|z01_nome|q51_numnota','Pesquisa',true);
  }else{
     if(document.form1.q51_numnota.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issnotaavulsa','func_issnotaavulsacancelados.php?filtrabaixa=1&filtranumpre=1&pesquisa_chave='+document.form1.q51_numnota.value+'&funcao_js=parent.js_mostraissnotaavulsa','Pesquisa',false);
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
}
</script>