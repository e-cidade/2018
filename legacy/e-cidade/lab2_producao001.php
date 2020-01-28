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
include("classes/db_lab_laboratorio_classe.php");
require_once('libs/db_utils.php');
$cllab_laboratorio = new cl_lab_laboratorio;
$clrotulo          = new rotulocampo;

$iUsuario = db_getsession('DB_login');
$iDepto = db_getsession('DB_coddepto');

$oDaolab_labusuario;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >

<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <center>
      <br><br>
      <fieldset style='width: 75%;'> <legend><b>Relatório de Exame</b></legend>
      <form name='form1'>
        <table border="0">
          <tr>
              <td align="right" nowrap>
                <b> Inicio:</b>
              </td>
              <td nowrap>
                <?db_inputdata('la02_d_datainicio',
                               @$la02_d_datainicio_dia,
                               @$la02_d_datainicio_mes,
                               @$la02_d_datainicio_ano,
                               true,
                               'text',
                               1);?>
              </td>
              <td align="right" nowrap>
                <b>Fim:</b>
              </td>
              <td nowrap>
                <?db_inputdata('la02_d_datafim',
                               @$la02_d_datafim_dia,
                               @$la02_d_datasaida_mes,
                               @$la02_d_datasaida_ano,
                               true,
                               'text',
                               1);?>
              </td>
          </tr> 
          <tr>
            <td align="right" colspan="4">
              <?
              $sSql           = $cllab_laboratorio->sql_query("","la02_i_codigo,la02_c_descr");
              $rsLaboratorios = $cllab_laboratorio->sql_record($sSql);
              db_multiploselect("la02_i_codigo",
                                "la02_c_descr",
                                "nselecionados",
                                "sselecionados",
                                $rsLaboratorios,
                                array(),
                                5,
                                250);
              ?>
            </td>
          </tr>
          <tr>
            <td align="right">
              <b>Tipo:</b>
            </td>
            <td colspan="3" align="left">
              <?$aX = array(1=>'SINTÉTICO',2=>'ANALÍTICO');
                db_select('tipo',$aX,true,1,"");?>
            </td>
          </tr>
        </table>
        <input type="button" name="gerar" value="Gerar" onclick="js_gerar()" >
      </form>
      </fieldset>
      </center>
    </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_gerar(){

    oF   = document.form1;
    iTam = oF.sselecionados.length;
    if(iTam==0){
      alert('Selecione um laboratorio');
      return false;
    }
    if ((oF.la02_d_datainicio.value == "") && (oF.la02_d_datafim.value == "")) {
      alert('Entre com o periodo!');
      return false;
    }
    
    sStr = '';
    sSep = '';
    for(iX=0; iX<iTam; iX++){

      sStr += sSep+oF.sselecionados.options[iX].value;
      sSep=",";

    }
    sDados  = 'dInicio='+oF.la02_d_datainicio.value+'&dFim='+oF.la02_d_datafim.value+'&sLaboratorios='+sStr;
    sDados += '&iTipo='+oF.tipo.value;
    Jan = window.open('lab2_producao002.php?'+sDados,'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJan.moveTo(0,0);

}
</script>