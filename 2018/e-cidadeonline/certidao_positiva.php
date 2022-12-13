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

include("libs/db_conecta.php");
include("classes/db_db_certidaoweb_classe.php");

db_mensagem("certidaopositiva","");
$meses = array("","JANEIRO", "FEVEREIRO", "MARÇO", "ABRIL", "MAIO", "JUNHO", "JULHO", "AGOSTO", "SETEMBRO", "OUTUBRO", "NOVEMBRO", "DEZEMBRO");
$data = getdate();
$mes1 = $data['mon'];
if((strlen($mes1)) == 1)
  $mes = $meses[$mes1];
  $mes1 = $data['mon'];
if((strlen($mes1)) == 1)
  $mes1 = "0".$mes1;  
  $dia = $data['mday'];
if((strlen($dia)) == 1)
  $dia = "0".$dia;  
  $ano = $data['year'];
  $hora = $data['hours'];
if((strlen($hora)) == 1)
  $hora = "0".$hora;  
  $min = $data['minutes'];
if((strlen($min)) == 1)
  $min = "0".$min;  
  $sec = $data['seconds']; 
if((strlen($sec)) == 1)
  $sec = "0".$sec;  
$mes2 = $mes1 + "3";
if($mes2 !=12){
  if($mes2%2 == 0 && $dia > 30 ){
    $dia = ($dia - 1);
  }elseif($mes2 == 2 && $dia > 28){
    $dia = 28;
  }
}
if($mes1==10){
  $ano = $ano + 1;
  $mes2 = 01;
}
if($mes1==11){
  $ano = $ano + 1;
  $mes2 = 02;
}
if($mes1==12){
  $ano = $ano + 1;
  $mes2 = 03;
}
if((strlen($mes2)) == 1)
  $mes2 = "0".$mes2;  
if ($tipo == 0){
  if($tipodados == "nome"){
    $tipo = "1";
  }elseif($tipodados == "matric"){
    $tipo = "2";
  }elseif($tipodados == "inscr"){
    $tipo = "3";
  }
}elseif ($tipo == 1){
  if($tipodados == "nome"){
    $tipo = "4";
  }elseif($tipodados == "matric"){
    $tipo = "5";
  }elseif($tipodados == "inscr"){
    $tipo = "6";
  }
}elseif ($tipo == 2){
  if($tipodados == "nome"){
    $tipo = "7";
  }elseif($tipodados == "matric"){
    $tipo = "8";
  }elseif($tipodados == "inscr"){
    $tipo = "9";
  }
}
$sequencia = db_query("select nextval('db_certidaoweb_codcert_seq')");
$seq2 = pg_result($sequencia,0,0);
$tamanho = strlen($seq2);
$seq = "";
for($i=0; $i<(7-$tamanho); $i++){
$seq .= "0";
}
$seq .= $seq2;
$sql = db_query("select cgc from db_config limit 1");
for ($i=0;$i<(pg_numfields($sql));$i++){
db_fieldsmemory($sql,0);
}
$nros = $seq.$cgc.$ano.$mes1.$dia.$hora.$min.$sec.$ano.$mes2.$dia;
$t1 = strrev($nros);


//////////////////////////////////////////////////////////////////////

 $HTTP_SERVER_VARS['SCRIPT_FILENAME'];
 $root = substr($HTTP_SERVER_VARS['SCRIPT_FILENAME'],0,strrpos($HTTP_SERVER_VARS['SCRIPT_FILENAME'],"/"));
 $cod = ereg_replace(" ","",$seq);
 $arquivo = ($root."/"."certidoes/certidao".$cod.".php");
 $fd = fopen($arquivo,"w");

 $ffputs = ('<html>'."\n");
 $ffputs .= ('<head>'."\n");
 $ffputs .= ('<title>Documento sem t&iacute;tulo</title>'."\n");
 $ffputs .= ('<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
 $ffputs .= ('</head>'."\n");
 $ffputs .= ('<STYLE>'."\n");
 $ffputs .= ('  .link color: white;'."\n");
 $ffputs .= ('  { text-decoration: none; }'."\n");
 $ffputs .= ('a:hover { color: black;'."\n");
 $ffputs .= ('  text-decoration: none;'."\n");
 $ffputs .= ('}'."\n");
 $ffputs .= (' .texto'."\n");
 $ffputs .= ('{'."\n");
 $ffputs .= (' font-family: courier new;'."\n");
 $ffputs .= (' font-size: 13px;'."\n");
 $ffputs .= (' color: #000000;'."\n");
 $ffputs .= (' text-decoration: none;'."\n");
 $ffputs .= ('}'."\n");
 $ffputs .= (' </STYLE>'."\n");
 $ffputs .= ('<body>'."\n");
 $ffputs .= ('<table width="650" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#CCCCCC">'."\n");
 $ffputs .= ('  <tr>'."\n");
 $ffputs .= ('    <td>'."\n");
 $ffputs .= ('      <table width="100%" border="0" cellspacing="0" cellpadding="0">'."\n");
 $ffputs .= ('        <tr>'."\n");
 $ffputs .= ('          <td width="9%">'."\n");
 $ffputs .= ('            <div align="center"><img src="imagens/'.$logo.'" width="54" height="70"></div>'."\n");
 $ffputs .= ('          </td>'."\n");
 $ffputs .= ('          <td width="88%">'."\n");
 $ffputs .= ('            <div align="center"><font size="6" face="Arial, Helvetica, sans-serif"><b><font size="5" face="Verdana, Arial, Helvetica, sans-serif">'.$nomeinst.'</font><font face="Verdana, Arial, Helvetica, sans-serif"><br>'."\n");
 $ffputs .= ('              </font></b><font face="Verdana, Arial, Helvetica, sans-serif"><font size="4">Secretaria'."\n");
 $ffputs .= ('              Municipal de Finan&ccedil;as</font></font></font></div>'."\n");
 $ffputs .= ('          </td>'."\n");
 $ffputs .= ('        </tr>'."\n");
 $ffputs .= ('      </table>'."\n");
 $ffputs .= ('    </td>'."\n");
 $ffputs .= ('  </tr>'."\n");
 $ffputs .= ('  <tr>'."\n");
 $ffputs .= ('    <td> <p><font face="Courier New, Courier, mono"><strong>'."\n");
 $ffputs .= ('        </strong></font></p>'."\n");
 $ffputs .= ('      <table width="100%" border="0" cellspacing="5" cellpadding="5">'."\n");
 $ffputs .= ('        <tr>'."\n");
 $ffputs .= ('            <p>&nbsp;</p>'."\n");
 $ffputs .= ('            <p>&nbsp;</p>'."\n");
 $ffputs .= ('            <p>&nbsp;</p>'."\n");
 $ffputs .= ('            <p>&nbsp;</p>'."\n");
 $ffputs .= ('            <p>&nbsp;</p>'."\n");
 $ffputs .= ('            <td height="300" valign="center">'."\n");
 $ffputs .= ('            <p align="center"><b>'.$DB_mens1.'</b></p><table width="500" border="0" cellspacing="1" cellpadding="1" class="texto">'."\n");
 $ffputs .= ('                            <tr>'."\n");
 $ffputs .= ('            <p align="right">&nbsp;</p>'."\n");
 $ffputs .= ('            <p align="right">&nbsp;</p>'."\n");
 $ffputs .= ('            <p align="right">&nbsp;</p>'."\n");
 $ffputs .= ('            <p align="right">&nbsp;</p>'."\n");
 $ffputs .= ('            <p align="right">&nbsp;</p>'."\n");
 $ffputs .= ('            <p align="right">'."\n");
 $ffputs .= ('<td width="200" align="right" nowrap>'."\n");
 $ffputs .= ('</td>'."\n");
 $ffputs .= ('                            </tr>'."\n");
 $ffputs .= (' </p>							          <font face="Verdana" size="2" color="#000000"><br><p align="right">'.$munic.',&nbsp;'.$dia.'&nbsp;DE&nbsp;'.$mes.'&nbsp;DE&nbsp;'.$ano.'</p></font><br><br>'."\n");
 $ffputs .= ('                       <table width="100%" border="0" cellspacing="5" cellpadding="5">'."\n");
 $ffputs .= ('                           <tr valign="top">'."\n");
 $ffputs .= ('                           <td width="50%">'."\n");
 $ffputs .= ('                           <div align="left"><font face="verdana" size="2"><b>ASPECTOS'."\n");
 $ffputs .= ('                              T&Eacute;CNICOS DE VALIDADE:</b><br>'."\n");
 $ffputs .= ('                  </font><font face="arial" size="2"> Emiss&atilde;o &agrave;s '."\n");
 $ffputs .= ('                  <b>'.$hora.":".$min.":".$sec.'</b> em <b>'.$dia."/".$mes1."/".$ano.'</b> '."\n");
 $ffputs .= ('                  .<br>'."\n");
 $ffputs .= ('                              C&oacute;digo de autenticidade da Certid&atilde;o: <br>'."\n");
 $ffputs .= ('                  <br>'."\n");
 $ffputs .= ('                  <font face="courier">'.@$t1.'</font><br>'."\n");
 $ffputs .= ( '                  <img src="boleto/int25.php?text='.@$t1.'">'."\n");
 $ffputs .= ('                           </td>'."\n");
 $ffputs .= ('                           <td width="43%">'."\n");
 $ffputs .= ('                            <div align="center"><font size="2" face="Verdana"><i> </i><br>'."\n");
 $ffputs .= ('                              <br>'."\n");
 $ffputs .= ('                              <br>'."\n");
 $ffputs .= ('                              </font></div>'."\n");
 $ffputs .= ('                           </td>'."\n");
 $ffputs .= ('                           </tr>'."\n");
 $ffputs .= ('              '."\n");
 $ffputs .= ('                           </table>'."\n");
 $ffputs .= ('                           <table>'."\n");
 $ffputs .= ('                           <tr>'."\n");
 $ffputs .= ('                           <td>'."\n");
 $ffputs .= ('                              <font face="arial" size="2">Tanto a veracidade da informa&ccedil;&atilde;o quanto a manuten&ccedil;&atilde;o'."\n");
 $ffputs .= (' da condi&ccedil;&atilde;o de n&atilde;o devedor poder&aacute; ser verificada '."\n");
 $ffputs .= (' na seguinte p&aacute;gina na Internet: <b>'.$url.'</b><br>'."\n");
 $ffputs .= ('                  <b>Aten&ccedil;&atilde;o:</b> Qualquer rasura ou emenda INVALIDAR&Aacute; '."\n");
 $ffputs .= ('                  este documento. </font><br>'."\n");
 $ffputs .= ('                  <br><center>'."\n");
 $ffputs .= ('                </td>'."\n");
 $ffputs .= ('              </tr>'."\n");
 $ffputs .= ('            </table>'."\n");
 $ffputs .= ('          </td>'."\n");
 $ffputs .= ('        </tr>'."\n");
 $ffputs .= ('      </table>'."\n");
 $ffputs .= ('    </td>'."\n");
 $ffputs .= ('  </tr>'."\n");
 $ffputs .= ('</table>'."\n");
 $ffputs .= ('</body>'."\n");
 $ffputs .= ('</html>'."\n");
 fputs($fd,$ffputs); 
 fclose($fd);

$clcertidao = new cl_db_certidaoweb;
$clcertidao->codcert = "$t1";
$clcertidao->tipocer = $tipo;
$clcertidao->cerdtemite = $ano."-".$mes1."-".$dia;
$clcertidao->cerhora = $hora.":".$min.":".$sec;
$clcertidao->cerdtvenc = $ano."-".$mes2."-".$dia;
$clcertidao->cerip="$ip";
$clcertidao->ceracesso=$acesso;
$clcertidao->cercertidao="1";
$clcertidao->cernomecontr="$z01_nome";
$clcertidao->cerweb="1";
$clcertidao->cerhtml="$ffputs";
$clcertidao->incluir(); 

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<style>
A:hover {color:gray; cursor:hand};
</style>
<script>
js_verificapagina("certidaonome.php,certidaoinscr.php,certidaomatric.php");
function imprimir() {
document.getElementById('botao').style.visibility = 'hidden';
//document.focus();
window.print();
}
</script>
    <body>
    <table width="650" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#CCCCCC">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="9%">
            <div align="center"><img src="imagens/<?=$logo?>" width="54" height="70"></div>
          </td>
          <td width="88%">
            <div align="center"><font size="6" face="Arial, Helvetica, sans-serif"><b><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><?=$nomeinst?></font><font face="Verdana, Arial, Helvetica, sans-serif"><br>
              </font></b><font face="Verdana, Arial, Helvetica, sans-serif"><font size="4">Secretaria
              Municipal de Finan&ccedil;as</font></font></font></div>
          </td>
        </tr>
      </table>
    </td>
<div align="center" id="botao"><a onClick="imprimir()">Clique aqui para imprimir a Certidão</a></div>

  </tr>
  <tr>
    <td> <table width="100%" height="740" border="0" cellpadding="5" cellspacing="5">
        <tr> 
          <td> <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p align="center">
              <?=$DB_mens1?>
              <br>
            </p>
            <p align='right'>&nbsp;</p>
            <p align='right'>&nbsp;</p>
            <p align='right'>&nbsp;</p>
            <p align='right'>&nbsp;</p>
            <p align='right'>&nbsp;</p>
            <p align='right'>&nbsp;</p>
            <p align='right'>&nbsp;</p>
            <p align='right'><font color="#000000" size="2" face="Verdana"> 
              <?=$munic.","?>
              <? echo $dia."&nbsp;DE&nbsp;".$mes."&nbsp;DE&nbsp;".$ano; ?></font></p>
            <font color="#000000" size="2" face="Verdana"><br>
            <br>
            </font>
            <table width='100%' border='0' cellspacing='5' cellpadding='5'>
              <tr valign='top'> 
                <td width='50%'> <div align='left'><font color="#000000" size='2' face='Verdana'><b>ASPECTOS 
                    T&Eacute;CNICOS DE VALIDADE:</b><br>
                    Emiss&atilde;o &agrave;s <b><? echo $hora.":".$min.":".$sec; ?></b> 
                    em <b><? echo $dia."/".$mes1."/".$ano; ?></b>.<br>
                    C&oacute;digo de autenticidade da Certid&atilde;o: <br>
                    <br>
                  <font face='courier'><?=$t1?></font><br>
                    <img src="boleto/int25.php?text=<?=$t1?>">
                    </font></div></td>
                <td width='43%'> <div align='center'><font color="#000000" size='2' face='Verdana'><br>
                    <br>
                    <br>
                    </font></div></td>
              </tr>
            </table>
            <table>
              <tr> 
                <td height="68"> <font color="#000000" size='2' face='Verdana'>Tanto a veracidade 
                  da informa&ccedil;&atilde;o quanto a manuten&ccedil;&atilde;o 
                  da condi&ccedil;&atilde;o de n&atilde;o devedor poder&aacute; 
                  ser verificada na seguinte p&aacute;gina na Internet: <b>
                  <?=$url?>
                  </b><br>
                  <b>Aten&ccedil;&atilde;o:</b> Qualquer rasura ou emenda INVALIDAR&Aacute; 
                  este documento. <br>
                  <br>
                  </font>
                  <center>
                  </center></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <p><font face="Courier New, Courier, mono"><strong> </strong></font></p> 
    </td>
  </tr>
</table>
</body>
</html>