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
include("dbforms/db_classesgenericas.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");
$cldb_estrut = new cl_db_estrut;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(opcao,origem){
  if (opcao == 3){
     var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
     var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
     if(data1.valueOf() > data2.valueOf()){
       alert('Data inicial maior que data final. Verifique!');
       return false;
     }
     perini = document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value;
     perfin = document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;;
  }else if (opcao == 2){
     if(document.form1.mesfin.value == 0){
       mesfinal = 12;
     }else if(document.form1.mesfin.value < 10){
       mesfinal = '0'+document.form1.mesfin.value;
     }else if(document.form1.mesfin.value == 'mes'){
       alert('Mês final do intervalo invalido.Verifique!');
       return false
     }else{
       mesfinal = document.form1.mesfin.value;
     }

     if(document.form1.mesini.value == 0){
       mesinicial = 12;
     }else if(document.form1.mesini.value < 10){
       mesinicial = '0'+document.form1.mesini.value;
     }else{
       mesinicial = document.form1.mesini.value;
     }
    
     perini = <?=db_getsession("DB_anousu")?>+'-'+mesinicial+'-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-'+mesfinal+'-01';
  }else{
     perini = <?=db_getsession("DB_anousu")?>+'-01-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-01-01';
  }

  campo = document.form1.estrut.value;
  imprime_recurso = document.form1.imprime_recurso.value;
  jan = window.open('con2_balancdespele002.php?imprime_recurso='+imprime_recurso+'&elemento='+campo+'&perfin='+perfin+'&perini='+perini+'&opcao='+opcao+'&db_selinstit='+document.form1.db_selinstit.value+'&origem='+origem,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center" border=0>
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
         <td align="center" colspan=3>
         <?
           db_selinstit('',300,100);
         ?>
         </td>
      </tr>
     <tr>
            <td width="20%"><B>Elemento</B>
            </td>
             <td colspan=2>
             <table border=0>   
            <?
                    $cldb_estrut->autocompletar = false;
//                    $cldb_estrut->funcao_onchange = 'js_troca(this.value)';
                    $cldb_estrut->nomeform = 'form1';
                    $cldb_estrut->mascara = false;
                    $cldb_estrut->reload  = false;
                    $cldb_estrut->input   = false;
                    $cldb_estrut->size    = 22;
                    $cldb_estrut->nome    = "estrut";
                    $cldb_estrut->db_opcao= 1;
                    $cldb_estrut->db_mascara('9');
             ?>            
              </table>
              </td>
      <tr>
        <td><b>Quebra por Recurso </td>
        <td colspan=2><select  name="imprime_recurso">
                        <option value="nao">Não </option>
                        <option value="sim">Sim </option>
                </select>
         </td>
      </tr>
      <?
      db_selorcbalanco();
      ?>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>