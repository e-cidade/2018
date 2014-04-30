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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_liborcamento.php");

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


function js_abre(opcao){

 sel_instit  = new Number(document.form1.db_selinstit.value);
 if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
 }
}


variavel = 1;
function js_emite(opcao,origem){

  // obtem os parametros que foram selecionados
  document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();


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

  valor_nivel = new Number(document.form1.orgaos.value);
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
 }else{
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    document.form1.action = "orc2_orcppaconf002.php?perfin="+perfin+"&perini="+perini+"&opcao="+opcao+"&origem="+origem;
    setTimeout("document.form1.submit()",1000);
    return true;
 }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

  <table  align="center">
    <form name="form1" method="post" action="orc2_orcppaconf002.php" >
      <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >

      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="3">
	<?
	db_selinstit('',300,100);
	?>
	</td>
      </tr>

      <tr>
        <td align="center" colspan="3">
	  <table>

	    <!--
	    <tr>
              <td align="right" ><strong>Filtro :</strong></td>
              <td align="left">
	        <?
	         $xy = array('1A'=>'Órgão','2A'=>'Unidade','3A'=>'Função','4A'=>'Subfunção','5A'=>'Programa','6A'=>'Proj/Ativ','7A'=>'Elemento','8A'=>'Recurso');
	         db_select('nivel',$xy,true,2,"");
	        ?>
              </td> 
              <td align="left">
                <input  name="seleciona" id="seleciona" type="button" value="Selecionar" onclick="js_abre();">
              </td>
            </tr>
            -->

            <tr>
              <td align="right" ><strong>Agrupar Por :</strong></td>
	       <td align="left">
                <?
                  $z = array("1"=>"Geral","2"=>"Órgão","3"=>"Unidade");
                  db_select('tipo_agrupa',$z,true,2,"");
                ?>
               </td>
	      <td>&nbsp;</td>
            </tr>
  <tr>
    <td nowrap align="right">
       <b>Tipo :</b>
    </td>
    <td> 
<?
$x = array('t'=>'Todos','a'=>'Atividade','p'=>'Projetos','o'=>'Operações especiais');
  db_select('o55_tipo',$x,true,2);
?>
    </td>
  </tr>
	  </table>
        </td>
      </tr>
      <tr>
        <td colspan="2" >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
       <?
        db_selorcbalanco(false);
       ?>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="orgaos" id="orgaos" type="hidden" value="" >
          <input  name="vernivel" id="vernivel" type="hidden" value="" >
        </td>
      </tr>
  </form>
  </table>

</body>
</html>