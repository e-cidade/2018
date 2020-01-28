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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  jan = window.open('pes2_alemenossm002.php?&ano='+document.form1.DBtxt23.value+
                                           '&mes='+document.form1.DBtxt25.value+
                                           '&minimo='+document.form1.minimo.value
					   ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
        <td align="left" nowrap  >
        <strong>Salário Mínimo :&nbsp;&nbsp;</strong>
        </td>
      <td>
      <?
      $result = pg_exec("select r07_valor from pesdiver where r07_anousu = $DBtxt23 and r07_mesusu = $DBtxt25 and r07_codigo = 'D912'" );
      db_fieldsmemory($result,0);
      $minimo = $r07_valor;
      db_input('minimo',15,$minimo,true,'text',2,"");
      ?>
      </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Relatório" onclick="js_emite();" >
          <input  name="proces" id="proces" type="submit" value="Lançar" onclick="js_emite1();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<?
if(isset($proces)){
  echo "
  <script>
    if(confirm('Incluir no ponto os valores menores que o mínimo?.\\nProcessar?')){
      obj=document.createElement('input');
      obj.setAttribute('name','confirma');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value','confirma');
      document.form1.appendChild(obj);
      document.form1.DBtxt25.value = '$mes';
      document.form1.submit();
    }
  </script>
  ";

}
if(isset($confirma)){
  //echo ("select * from pontofs where r10_anousu = $DBtxt23 and r10_anousu = $DBtxt25 and r10_rubric = '0290'");
  $res_cons = pg_exec("select * from pontofs where r10_anousu = $DBtxt23 and r10_mesusu = $DBtxt25 and r10_rubric = '0290'");
  
  if(pg_numrows($res_cons) > 0){
    pg_exec("delete from pontofs where  r10_anousu = $DBtxt23 and r10_mesusu = $DBtxt25 and r10_rubric = '0290'");
  }
  
 $sql = "
 select *, $minimo  - r14_valor as prov from
 (
  select * from
  (
  SELECT  p.r14_anousu,p.r14_mesusu,P.R14_REGIST,'0290' as r14_rubric,P.PROV as r14_valor, 0 as r14_quant, r14_lotac,null as r14_datlim,r14_instit
  FROM 
     (SELECT r14_anousu,r14_mesusu,R14_REGIST, r14_lotac,r14_instit,
             ROUND(SUM(R14_VALOR),2) AS PROV 
      FROM GERFSAL 
      WHERE R14_ANOUSU= $DBtxt23 AND 
            R14_MESUSU= $DBtxt25 AND
						R14_INSTIT= ".db_getsession("DB_instit")." AND 
            R14_RUBRIC != 3 AND 
            R14_RUBRIC NOT IN ('R928','R918','R919','R920','0032','0097','0060','0184','0185','0290','0293') AND 
            R14_PD = 1 
      GROUP BY  r14_anousu,r14_mesusu,R14_REGIST, r14_lotac, r14_instit) AS P 
      INNER JOIN RHPESSOAL 
            ON RH01_REGIST = P.R14_REGIST
      INNER JOIN CGM 
            ON Z01_NUMCGM = Rh01_NUMCGM 
  
  ) as x
  where r14_valor < $minimo
  ) as xx
  ";
  pg_exec("begin");
  $result = pg_exec($sql);
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     $sql_exec = "insert into pontofs values($r14_anousu,
                                  $r14_mesusu,
				  $r14_regist,
				  '$r14_rubric',
				  $prov,
				  $r14_quant,
				  '$r14_lotac',
				  null,
    				  $r14_instit)";
    $exec =  pg_exec($sql_exec);
    if($exec == false){
      echo "<script>
      alert('Erro : '+$sql);
      
      </script>";
      pg_exec("rollback");
    }else{
    pg_exec("commit");
    }
  }
}
?>