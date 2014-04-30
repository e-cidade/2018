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
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function termo(qual,total){
  // document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
}

function confirmaCalculo(){
  var logico = confirm("Você tem certeza que deseja processar o cálculo geral de IPTU?");
  return logico;	
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
<tr> <td width="360" height="18">&nbsp;</td> <td width="263">&nbsp;</td>  <td width="25">&nbsp;</td> <td width="140">&nbsp;</td> </tr>
</table>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
<tr> <td width="360" height="18">&nbsp;</td> <td width="263">&nbsp;</td> <td width="25">&nbsp;</td> <td width="140">&nbsp;</td> </tr>
</table>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
<tr> <td width="360" height="18">&nbsp;</td> <td width="263">&nbsp;</td> <td width="25">&nbsp;</td> <td width="140">&nbsp;</td> </tr>
</table>

<table width="790" height="" border="0" cellspacing="0" cellpadding="0">
<tr> <td height="" align="center" valign="top" bgcolor="#CCCCCC"> <form name="form1" action="" method="post" >
<? if(isset($calcular)){ ?> 
  <tr><td>
  <? 
  db_criatermometro('termometro','Concluido...','blue',1);
  ?>
  </td></tr>
<? } else {?>
  <table width="292" border="0" cellpadding="0" cellspacing="0">
  <tr> 
  <td width="" height="25" title="<?=$Tz01_nunmcgm?>"><strong>Calcular Financeiro:</strong></td>
  <td width="" height="25"> 
  <?
  $x = array("0"=>"SIM","1"=>"NAO");
  db_select("financeiro",$x,true,2);
  ?>
  </td>
  </tr>
  <tr>
  <td height="25"> <strong>Ano:</strong> </td>
  <td height="25">
  <? $result=db_query("select distinct j18_anousu from cfiptu order by j18_anousu desc");
  if(pg_numrows($result) > 0){?>
    <select name="anousu">
    <? for($i=0;$i<pg_numrows($result);$i++){
      db_fieldsmemory($result,$i); ?>
      <option value='<?=$j18_anousu?>'><?=$j18_anousu?></option>
    <? } ?>
    </select>
  <? } ?>
  </td>
  </tr>
  <tr>
  <td>
  </td>
  </tr>
  <tr align="center"> 
  <td height="25" colspan="2"> <input name="calcular"  type="submit" id="calcular" value="Calcular" onclick="return confirmaCalculo();"> </td>
  </tr>
  </table>
<?}?>
</form></td></tr>
</table>
<?

if(!isset($calcular)){
  db_msgbox("Esta é a rotina de cálculo geral de IPTU!");
}

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<?
flush();
if(isset($calcular)){
  $erro = false;
  set_time_limit(0);
  $result = db_query("begin;");
  echo "<br><br>Inicio: ".date("d/m/Y H:i:s") . "<br>";
  $xInicio = time();
  $sql = "select j01_matric from iptubase order by j01_matric";
  $result = db_query( $sql );
  $numrows = pg_numrows($result);
  if($numrows == 0){
    echo "<script>alert('Sem matrículas a calcular!');</script>";
  }else{
    $total_reg = $numrows;
    $sqlnextval = "select nextval('iptucalclog_j27_codigo_seq') as j27_codigo";
    $resultnextval = db_query($sqlnextval) or die($sqlnextval);
    if ($resultnextval == false) {
      echo "<script>alert('Erro ao gerar sequencia!');</script>";
    } else {
      db_fieldsmemory($resultnextval,0);
      $insert = "insert into iptucalclog values ($j27_codigo,$anousu,'".date('Y-m-d',db_getsession("DB_datausu"))."','".db_hora()."',".db_getsession('DB_id_usuario').",false," . $numrows . ")";
      $resultinsert = db_query($insert) or die($insert);
      if ($resultinsert == false) { 
        echo "<script>alert('Erro do gerar lancamento na tabela iptucalclog!');</script>";
      } else {
        
        db_query("commit;");
       
        $sListaMatricErro = "";
        $sVirgula         = "";
        for($ii=0;$ii<$total_reg;$ii++){
          db_fieldsmemory($result,$ii);
          db_atutermometro($ii,$total_reg,'termometro');

          db_inicio_transacao();

					$resultcfiptu = db_query("select distinct j18_anousu, j18_permvenc from cfiptu where j18_anousu = $anousu");
					$j18_permvenc = 1;
					if(pg_numrows($resultcfiptu) > 0){
						db_fieldsmemory($resultcfiptu,0);
					}
					if ($j18_permvenc == 0) {
						$j18_permvenc = 1;
					}
					
					if ($j18_permvenc == 1) {

						$sql = "select fc_calculoiptu($j01_matric::integer,$anousu::integer,".($financeiro==0?"true":"false")."::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0','0'])";
					} elseif ($j18_permvenc == 2) {
						$sql = "select fc_calculoiptu($j01_matric::integer,$anousu::integer,".($financeiro==0?"true":"false")."::boolean,false::boolean,false::boolean,false::boolean,false::boolean,array['0','0'])";
					}          

          //die($sql);
          //echo "<br>$sql";
          $erro = false;
          $resultcalc = db_query($sql);
  
          if ($resultcalc) {
            db_fieldsmemory($resultcalc,0);

            preg_match('/[0-9]*/', trim($fc_calculoiptu), $aTipoLogCalc);

            $insert = "insert into iptucalclogmat values ($j27_codigo,$j01_matric,".$aTipoLogCalc[0].",'".trim(preg_replace('/^[0-9]*/', '',trim($fc_calculoiptu)))."')";
            $resultinsert = db_query($insert) or die($insert);
            if ($resultinsert == false) {
              $erro              = true;
              $sListaMatricErro .= $sVirgula.$j01_matric;
              //echo "<br>A-$j01_matric";
              $sVirgula          = ", ";
            }
          } else {
            $erro              = true;
            $sListaMatricErro .= $sVirgula.$j01_matric;
            //echo "<br>B-$j01_matric";
            //echo "<br>".pg_last_error();
            $sVirgula          = ", ";
          }

          db_fim_transacao($erro);
        }
        
        echo "Fim: ".date("d/m/Y H:i:s") . "<br>";
        echo "Tempo Decorrido: ".db_formatatempodecorrido($xInicio, time())."<br>";
        if ($erro == true) {
          echo "<script>alert('Ocorreu um erro durante o processamento! Matricula(s): $sListaMatricErro');</script>";
        } else {
          echo "<script>alert('Cálculo Efetuado.');</script>";
        }
        flush();
      }
    }
  }
}
?>