<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

$iInstituicao = db_getsession("DB_instit");
$iAnousu      = db_getsession("DB_anousu");

if(isset($emite2)){

    db_query('begin;');

    $sql_del_nivel = "delete from clabensconplano where t86_anousu = $iAnousu and t86_clabens in 
                                                    (select t64_codcla from clabens 
                                                     where t64_class ilike '$estrutural%'
                                                       and t64_instit = $iInstituicao )";
    $res_del_nivel = db_query($sql_del_nivel) or die($sql_del_nivel);
    
    $sql_nivel = "select * 
                  from clabens 
                  where t64_class ilike '$estrutural%' and t64_instit = $iInstituicao ";
    $res_nivel = db_query($sql_nivel) or die($sql_nivel);
    $num_nivel = pg_numrows($res_nivel);
    for($yy = 0; $yy < $num_nivel ; $yy++){
      db_fieldsmemory($res_nivel, $yy);
      $sql_ins_nivel = "insert into clabensconplano values(nextval('clabensconplano_t86_sequencial_seq'),
                                                           $t64_codcla,
                                                           $conplano,
                                                           $iAnousu,
                                                           $depreciacao,
                                                           $iAnousu
                                                          )";
      $res_ins_nivel = db_query($sql_ins_nivel) or die($sql_ins_nivel);

    }
    db_query('commit;');

}

function nivel_conta($estrutura){

  $sql_nivel = "select db78_nivel, 
                       db77_estrut, 
                       db78_tamanho, 
                       db78_inicio 
                from db_estrutura 
                     inner join cfpatri           on db77_codestrut = t06_codcla 
                     inner join db_estruturanivel on db78_codestrut = t06_codcla
                order by db78_nivel ";

  global $db78_nivel, $db77_estrut, $db78_tamanho, $db78_inicio, $nivel;
  
  $res_nivel = db_query($sql_nivel);

  $num_nivel = pg_numrows($res_nivel);
     
  
  for($x = 0 ; $x < $num_nivel ; $x++ ){
     db_fieldsmemory($res_nivel,$x);
     $parte = substr($estrutura,$db78_inicio, $db78_tamanho);
     if($parte+0 > 0 ){
       $nivel = $db78_nivel+1;
     }
  }

  return $nivel;

}


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  jan = window.open('pes2_cadrubricas002.php?ativos='+document.form1.ativos.value+'&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="left" nowrap title="" >
        <strong>Conta Patrimonial:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
	         @$conplano=0;
           db_input('conplano',6,$conplano,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="left" nowrap title="" >
        <strong>Conta Depreciacao:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
	         @$depreciacao=0;
           db_input('depreciacao',6,$depreciacao,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="left" nowrap title="" >
        <strong>Conta Depreciacao:&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
	         @$estrutural='';
           db_input('estrutural',15,$estrutural,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="submit" value="Processar"  >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
