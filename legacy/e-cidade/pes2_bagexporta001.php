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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');

if (isset($gera)){

  if($exporta == 'S'){
    
  $arq = '/tmp/simba.ret';

  $arquivo = fopen($arq,'w');  
  $sql = "
          select
           lpad(r14_regist,5,'0')||
           lpad(translate(round(r14_valor,2)::text,'.',''),10,'0') as tipo
          from gerfsal
          where r14_anousu = $ano
            and r14_mesusu = $mes
            and r14_rubric = '0505'
            and r14_instit = ".db_getsession('DB_instit')."
	 ";
  }elseif($exporta == 'C'){
    
  $arq = '/tmp/cartao.txt';

  $arquivo = fopen($arq,'w');  

  $sql = "
          select '00'||
                 '00000'||
                 lpad(r14_regist,8,'0')||
                 rpad(trim(substr(z01_nome,1,40)),46,' ')||
                 lpad(translate(round(r14_valor,2)::text,'.',''),10,'0')||
                 r14_anousu||
                 lpad(r14_mesusu,2,'0')||
                 lpad(translate(round(r14_valor,2)::text,'.',''),10,'0')||
                 '0000000000'||
                 '0000000000'||
                 '00000000000'||
                 '00'||
                 '000'||
                 '000000000' as tipo
          from gerfsal
               inner join rhpessoal on rh01_regist = r14_regist
               inner join cgm       on rh01_numcgm = z01_numcgm
          where r14_anousu = $ano
            and r14_mesusu = $mes
            and r14_rubric = '0636'
            and r14_instit = ".db_getsession('DB_instit')."
          order by z01_nome
	 ";
  }
// echo "<br><br><br><br><br>".$sql;
 //exit;
  $result = pg_query($sql);
// db_criatabela($result);exit; 

  if($exporta == 'C'){
    $xano = $ano;
    $xmes = $mes;
    $conv = '0501';
    $espaco = str_repeat(' ',122);
    fputs($arquivo,$xano.$xmes.$conv.$espaco."\r\n");
  }
  for($x = 0;$x < pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    fputs($arquivo,$tipo."\r\n");
  }
  fclose($arquivo);

}


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

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
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           $ano = db_anofolha();
           db_input('ano',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $mes = db_mesfolha();
           db_input('mes',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Tipo de Arquivo :&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr = array('S'=>'Retorno Simba','C'=>'Cartão Servidor');
	  db_select("exporta",$arr,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Processar"  >
 <!--         <input name="verificar" type="submit" value="Download" > -->
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
  <?
  if(isset($gera)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
</script>