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
  if($mes2 != 12){
    if($mes2%2 == 0 && $dia > 30){
      $dia = ($dia - 1);      
    }
    if($mes2 == 2 && $dia > 28){
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
$ffputs = ( '<html>'."\n");
$ffputs .= ( '<head>'."\n");
$ffputs .= ( '<title>Documento sem t&iacute;tulo</title>'."\n");
$ffputs .= ( '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">'."\n");
$ffputs .= ( '</head>'."\n");
$ffputs .= ( '<STYLE>'."\n");
$ffputs .= ( '  .link color: white;'."\n");
$ffputs .= ( '  { text-decoration: none; }'."\n");
$ffputs .= ( 'a:hover { color: black;'."\n");
$ffputs .= ( '  text-decoration: none;'."\n");
$ffputs .= ( '}'."\n");
$ffputs .= ( ' .texto'."\n");
$ffputs .= ( '{'."\n");
$ffputs .= ( ' font-family: courier new;'."\n");
$ffputs .= ( ' font-size: 13px;'."\n");
$ffputs .= ( ' color: #000000;'."\n");
$ffputs .= ( ' text-decoration: none;'."\n");
$ffputs .= ( '}'."\n");
$ffputs .= ( ' </STYLE>'."\n");
$ffputs .= ( '<body>'."\n");
$ffputs .= ( '<table width="650" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#CCCCCC">'."\n");
$ffputs .= ( '  <tr>'."\n");
$ffputs .= ( '    <td>'."\n");
$ffputs .= ( '      <table width="100%" border="0" cellspacing="0" cellpadding="0">'."\n");
$ffputs .= ( '        <tr>'."\n");
$ffputs .= ( '          <td width="9%">'."\n");
$ffputs .= ( '            <div align="center"><img src="imagens/'.$logo.'" width="54" height="70"></div>'."\n");
$ffputs .= ( '          </td>'."\n");
$ffputs .= ( '          <td width="88%">'."\n");
$ffputs .= ( '            <div align="center"><font size="6" face="Arial, Helvetica, sans-serif"><b><font size="5" face="Verdana, Arial, Helvetica, sans-serif">'.$nomeinst.'</font><font face="Verdana, Arial, Helvetica, sans-serif"><br>'."\n");
$ffputs .= ( '              </font></b><font face="Verdana, Arial, Helvetica, sans-serif"><font size="4">Secretaria'."\n");
$ffputs .= ( '              Municipal de Finan&ccedil;as</font></font></font></div>'."\n");
$ffputs .= ( '          </td>'."\n");
$ffputs .= ( '        </tr>'."\n");
$ffputs .= ( '      </table>'."\n");
$ffputs .= ( '    </td>'."\n");
$ffputs .= ( '  </tr>'."\n");
$ffputs .= ( '  <tr>'."\n");
$ffputs .= ( '    <td> <p><font face="Courier New, Courier, mono"><strong>N&ordm;:'."\n");
$ffputs .= ( '        '.$seq.''."\n");
$ffputs .= ( '        </strong></font></p>'."\n");
$ffputs .= ( '      <table width="100%" border="0" cellspacing="5" cellpadding="5">'."\n");
$ffputs .= ( '        <tr>'."\n");
$ffputs .= ( '          <td height="647"><font face="Verdana" size="2" color="#000000">'."\n");
$ffputs .= ( '            <font size="5"><p align="center"><b>CERTIDÃO POSITIVA COM EFEITO NEGATIVO</b></p></font><br><b>IDENTIFICAÇÃO DO CONTRIBUINTE:</b><font face="courier new" size="2"><table width="500" border="0" cellspacing="1" cellpadding="1" class="texto">'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '<td width="200" align="right" nowrap>'."\n");
$ffputs .= ( '                              NÚMERO DE CADASTRO:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_numcgm.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              NOME:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_nome.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              ENDEREÇO:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_ender.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              CIDADE:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_munic.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              ESTADO:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_uf.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              CEP:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_cep.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              CNPJ/CPF:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.$z01_cgccpf.'</b>'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
$ffputs .= ( '                            <tr>'."\n");
$ffputs .= ( '                             <td width="200" align="right">'."\n");
$ffputs .= ( '                              IE/RG:'."\n");
$ffputs .= ( '                             </td>'."\n");
$ffputs .= ( '                             <td>'."\n");
$ffputs .= ( '                              <b>'.@$z01_ident.'</b>                             </td>'."\n");
$ffputs .= ( '                            </tr>'."\n");
if (isset($matric)){
$ffputs .= ( '  <tr>'."\n");
$ffputs .= ( '         <td width="200" align="right">SETOR/QUADRA/LOTE:</td>'."\n");
$ffputs .= ( '         <td><b>'.$j34_setor."/".$j34_quadra."/".$j34_lote.'</b></td>'."\n");
$ffputs .= ( '       </tr>'."\n");
$ffputs .= ( '       <tr> '."\n");
$ffputs .= ( '         <td width="200" align="right">MATR&Iacute;CULA:</td>'."\n");
$ffputs .= ( '         <td><b>'.$matric.'</b></td>'."\n");
$ffputs .= ( '       </tr>'."\n");
}elseif(isset($inscr)){
$ffputs .= ( ' <tr> '."\n");
$ffputs .= ( '         <td width="200" align="right">INSCRIÇÃO:</td>'."\n");
$ffputs .= ( '         <td><b>'.$inscr.'</b></td>'."\n");
$ffputs .= ( '       </tr>'."\n");
}
$ffputs .= ( '                           </table>'."\n");
$ffputs .= ( ''."\n");
$ffputs .= ( '                           </font><br><p align="left">'."\n");
$ffputs .= ( '                                &nbsp;&nbsp;&nbsp;Certifico, a requerimento da parte interessada, que o contribuinte'."\n");
$ffputs .= ( ' acima identificado, esta em dia com o pagamento dos débitos até a presente data.'."\n");
$ffputs .= ( ' A presente certidão servirá para fins de direito.'."\n");
$ffputs .= ( '<br>'."\n");
$ffputs .= ( '                                &nbsp;&nbsp;&nbsp;<b>OBS.: A Fazenda Municipal se reserva o direito de lançar débitos'."\n");
$ffputs .= ( ' independentemente da data desta certidão.'."\n");
$ffputs .= ( '<br>'."\n");
$ffputs .= ( '                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Validade: 90 dias da data de sua emissão.'."\n");
$ffputs .= ( ' </p>							                                 <p align="right">'.$munic.',&nbsp;'.$dia.'&nbsp;DE&nbsp;'.$mes.'&nbsp;DE&nbsp;'.$ano.'</p>'."\n");
$ffputs .= ( '                       <table width="100%" border="0" cellspacing="5" cellpadding="5">'."\n");
$ffputs .= ( '                           <tr valign="top">'."\n");
$ffputs .= ( '                           <td width="50%">'."\n");
$ffputs .= ( '                           <div align="left"><font face="verdana" size="2"><b>ASPECTOS'."\n");
$ffputs .= ( '                              T&Eacute;CNICOS DE VALIDADE:</b><br>'."\n");
$ffputs .= ( '                  </font><font face="arial" size="2"> Emiss&atilde;o &agrave;s '."\n");
$ffputs .= ( '                  <b>'.$hora.":".$min.":".$sec.'</b> em <b>'.$dia."/".$mes1."/".$ano.'</b> '."\n");
$ffputs .= ( '                  .<br>'."\n");
$ffputs .= ( '                              C&oacute;digo de autenticidade da Certid&atilde;o: <br>'."\n");
$ffputs .= ( '                  <br>'."\n");
$ffputs .= ( '                  <font face="courier">'.@$t1.'</font><br>'."\n");
$ffputs .= ( '                  <img src="boleto/int25.php?text='.@$t1.'">'."\n");
$ffputs .= ( '                           </td>'."\n");
$ffputs .= ( '                           <td width="43%">'."\n");
$ffputs .= ( '                            <div align="center"><font size="2" face="Verdana"><i> </i><br>'."\n");
$ffputs .= ( '                              <br>'."\n");
$ffputs .= ( '                              <br>'."\n");
$ffputs .= ( '                              </font></div>'."\n");
$ffputs .= ( '                           </td>'."\n");
$ffputs .= ( '                           </tr>'."\n");
$ffputs .= ( '              '."\n");
$ffputs .= ( '                           </table>'."\n");
$ffputs .= ( '                           <table>'."\n");
$ffputs .= ( '                           <tr>'."\n");
$ffputs .= ( '                           <td height="77">'."\n");
$ffputs .= ( '                              <font face="arial" size="1">Tanto a veracidade da informa&ccedil;&atilde;o quanto a manuten&ccedil;&atilde;o'."\n");
$ffputs .= ( ' da condi&ccedil;&atilde;o de n&atilde;o devedor poder&aacute; ser verificada '."\n");
$ffputs .= ( ' na seguinte p&aacute;gina na Internet: <b>'.$url.'</b><br>'."\n");
$ffputs .= ( '                  <b>Aten&ccedil;&atilde;o:</b> Qualquer rasura ou emenda INVALIDAR&Aacute; '."\n");
$ffputs .= ( '                  este documento. </font>'."\n");
$ffputs .= ( '                  <br><center>'."\n");
$ffputs .= ( '                 </td>'."\n");
$ffputs .= ( '               </tr>'."\n");
$ffputs .= ( '             </table>'."\n");
$ffputs .= ( '          </td>'."\n");
$ffputs .= ( '        </tr>'."\n");
$ffputs .= ( '      </table>'."\n");
$ffputs .= ( '    </td>'."\n");
$ffputs .= ( '  </tr>'."\n");
$ffputs .= ( '</table>'."\n");
$ffputs .= ( '</body>'."\n");
$ffputs .= ( '</html>'."\n");

fputs($fd,$ffputs);

fclose($fd);
///////////////////////////////////////////////////////////////////////////////////////////////////////////
$clcertidao = new cl_db_certidaoweb;
$clcertidao->codcert = $t1;
$clcertidao->tipocer = $tipo;
$clcertidao->cerdtemite = $ano."-".$mes1."-".$dia;
$clcertidao->cerhora = $hora.":".$min.":".$sec;
$clcertidao->cerdtvenc = $ano."-".$mes2."-".$dia;
$clcertidao->cerip=$ip;
$clcertidao->ceracesso=$acesso;
$clcertidao->cercertidao=1;
$clcertidao->cernomecontr=$z01_nome;
$clcertidao->cerhtml=$ffputs;
$clcertidao->cerweb=1;
$clcertidao->incluir(); 
////////////////////////////////////////////////////////////////////////////////////////////////////////////
db_mensagem("certidaoposneg","");
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
    <td> <p><font face="Courier New, Courier, mono"><strong>N&ordm;: 
        <?=$seq?>
        </strong></font></p>
      <table width="100%" border="0" cellspacing="5" cellpadding="5">
        <tr>
          <td height="647"> <font size='5'>
<p align='center'><b>CERTIDÃO POSITIVA COM EFEITO NEGATIVO</b></p>
            </font><b>IDENTIFICAÇÃO DO CONTRIBUINTE:</b><br>
            <font face='courier new' size='2'>                           <table width="500" border="0" cellspacing="1" cellpadding="1" class="texto">
                            <tr>
<td width="200" align="right">
 NÚMERO DE CADASTRO:
                             </td>
                             <td>
                              <b><?=$z01_numcgm?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              NOME:
                             </td>
                             <td>
                              <b><?=$z01_nome?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              ENDEREÇO:
                             </td>
                             <td>
                              <b><?=$z01_ender?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              CIDADE:
                             </td>
                             <td>
                              <b><?=$z01_munic?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              ESTADO:
                             </td>
                             <td>
                              <b><?=$z01_uf?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              CEP:
                             </td>
                             <td>
                              <b><?=$z01_cep?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              CNPJ/CPF:
                             </td>
                             <td>
                              <b><?=$z01_cgccpf?></b>
                             </td>
                            </tr>
                            <tr>
                             <td width="200" align="right">
                              IE/RG:
                             </td>
                             <td>
                              <b><?=@$z01_ident?></b>                             </td>
                            </tr>
              <?
			  if (isset($matric)){
			  echo "<tr> 
                      <td align=\"right\">SETOR/QUADRA/LOTE:</td>
                      <td><b>".$j34_setor."/".$j34_quadra."/".$j34_lote."</b></td>
                    </tr>
                    <tr> 
                      <td align=\"right\">MATR&Iacute;CULA:</td>
                      <td><b>".$matric."</b></td>
                    </tr>";
			  }elseif(isset($inscr)){
			    echo "<tr> 
                        <td align=\"right\">INSCRIÇÃO:</td>
                        <td><b>".$inscr."</b></td>
                      </tr>";
			  }
			  ?>
                           </table>

                           
            </font> <p align='left'><font size="1" face="Arial, Helvetica, sans-serif">
            <? echo $DB_mens1; ?>
            </font>  
            <br><b>
              <font size="1" face="Arial, Helvetica, sans-serif">
			  OBS.: A Fazenda Municipal se reserva o direito de lançar débitos 
              independentemente da data desta certidão. Validade: 90 dias da data 
              de sua emissão.</font> </p>							                                 
            <p align='right'><?=$munic.","?><? echo "&nbsp;".$dia."&nbsp;DE&nbsp;".$mes."&nbsp;DE&nbsp;".$ano; ?></p>
            <table width='100%' border='0' cellspacing='5' cellpadding='5'>
                           <tr valign='top'>
                           <td width='50%'>
                           <font face="Arial, Helvetica, sans-serif" size="-7"><b>ASPECTOS
                              T&Eacute;CNICOS DE VALIDADE:</b><br>Emiss&atilde;o &agrave;s <b><? echo $hora.":".$min.":".$sec; ?></b> 
                  em <b><? echo $dia."/".$mes1."/".$ano; ?></b>.<br>
                              C&oacute;digo de autenticidade da Certid&atilde;o: <br>
                  <br></font>
                  <font face='courier' size="-4"><?=$t1?></font><br>
                    <img src="boleto/int25.php?text=<?=$t1?>">
                           </td>
                           </tr>
                           </table>
                           <table>
                           <tr>
                           <td height="77">
                              <font face='arial' size='1'>Tanto a veracidade da informa&ccedil;&atilde;o quanto a manuten&ccedil;&atilde;o
da condi&ccedil;&atilde;o de n&atilde;o devedor poder&aacute; ser verificada
na seguinte p&aacute;gina na Internet: <b><?=$url?></b><br>
                  <b>Aten&ccedil;&atilde;o:</b> Qualquer rasura ou emenda INVALIDAR&Aacute; 
                  este documento. </font><br>
                  <br><center>
                           </td>
                           </tr>
                           </table>
                                       
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
<?
/*
$mat = "";
  for($i=0; $i<$tamanho;$i++){
    $mat = substr($seq, $i,1);
    if ($mat == "0"){
	  $ci = "4";
	}else{  
   	  $ci = ord($mat);
    }
	$tam = strlen($ci);  
    for($x=0; $x<=($tam);$x++){
      $mat = substr($ci, $x,1);
	echo "a soma de mat eh = ".$mat."<br>";  
	}


   }	

    /////////////////////////
/*	
	if (strlen($mat) == "2"){
      $vir = substr($mat,2,1);
	}else{
	  $vir = $mat;
	}  
	echo "esta eh o numero = ".$vir."<br>";
    $t .= $vir;
}
echo "total ".$t."<br>"; 
$numero = "";
$inverte = "";
  if ((strlen($t))== "1"){
    $numero = $t;    
  }else{
    $inverte = strrev($t);  
    $numero = $inverte;
  }
echo "numero q vai pro banco = ".$numero."<br><br>";   

//inversao da historia

  if ((strlen($numero))== "2"){
    $inverte = $numero;
  }else{
    $numero = strrev($inverte);  
  }
$num = $numero;
$tama = strlen($num);
$nume = "";
  for($j=0; $j<=($tama); $j++){
    $dig = substr($num,$j,1);
//     echo $dig."<br>";
	if($dig == "3"){ 
	  $dig = substr($dig,$j,2);
	  $dig .= "31";
	  echo $dig."<br>";
	}else{
	  $dig = $dig;
	}  
	$nume .= $dig;
  
  }
	echo $nume;
  for($f=0; $f<=($tama); $f++){
    $numi = substr($nume,$f,1);
    echo "numero eh ".$numi."<br>";
  }
  //      $dig = strrev($dig);/
//	}
//    $dig = $dig;
//    echo $dig;
//	echo $dig;
//passa de ascii pra normal
/*    if($dig == "0"){
	  $dig = str_replace("0","01",$dig);
//	  echo $dig;
    }elseif($dig == "1"){
	  $dig = str_replace("1","11",$dig);
//	  echo $dig;
    }elseif($dig == "2"){
	  $dig = str_replace("2","21",$dig);
//	  echo $dig;
    }elseif($dig == "3"){
	  $dig = str_replace("3","31",$dig);
//	  echo $dig;
    }
      $dig = $dig;
	  echo $dig;
  }
//passa de ascii pra normal
/*    if(($dig == "0")) && (substr($dig,$j,1) != 1)){
	    echo "denis";
/*	  if($dig == "0" ){ 
        $dig = "01";
	    echo $dig;      
	  }else{
	 // echo $dig;  || $dig == "1" || $dig == "2" || $dig == "3"
	  }
 */
?>