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

$clRotulo = new rotulocampo();
$clRotulo->label('q104_mesusu');
$clRotulo->label('q104_anousu');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table  style="padding-top:25px;" align="center">
    <form name="form1" method="post" action="">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Relatório de Dados da Imporatação do MEI</b>
            </legend>
	          <table>
	            <tr>
		            <td> 
		              <b>
		                Competência :
		              </b>
		            </td>
		            <td>
		              <?
		                 db_input('q104_mesusu',2,$Iq104_mesusu,true,'text',1,'');
		                 echo "/";
		                 db_input('q104_anousu',4,$Iq104_anousu,true,'text',1,'');
		              ?>
		            </td>
	            </tr>
              <tr>
                <td> 
                  <b>
                    Tipo Registro :
                  </b>
                </td>
                <td>
                  <?
                    $aTipoReg = array( 1=>"Todos Registros",
								                       2=>"Apenas Registros com Inconsistênca",
								                       3=>"Apenas Registros sem Inconsistênca" );
								                    
                    db_select('tiporeg',$aTipoReg,true,1," style='width:350px;' ");
                  ?>
                </td>
              </tr>
              <tr>
                <td> 
                  <b>
                    Situação :
                  </b>
                </td>
                <td>
                  <?
                    $aSituacao = array( 1=>"Todos Registros",
		                                    2=>"Apenas Registros Processados",
		                                    3=>"Apenas Registros Descartados" );
                                    
                    db_select('situacao',$aSituacao,true,1," style='width:350px;' ");
                  ?>
                </td>
              </tr>
              <tr>
                <td> 
                  <b>
                    Posição Atual:
                  </b>
                </td>
                <td>
                  <?
                    $aPosicaoAtual = array( 1=>"Todos Registros",
				                                    2=>"Apenas Registros Pendentes para Processamento",
				                                    3=>"Apenas Registros Processados" );
                                    
                    db_select('posicaoatual',$aPosicaoAtual,true,1," style='width:350px;' ");
                  ?>
                </td>
              </tr>                                  	            
	          </table>
          </fieldset>
        </td>
      </tr>
      <tr align="center">
        <td>
          <input id="imprimir" type="button" value="Imprimir" onclick="js_imprimir();" >
        </td>
      </tr>
    </form>
  </table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_imprimir(){
  
  var iAnoCompetencia = new String(document.form1.q104_anousu.value); 
  var iMesCompetencia = new String(document.form1.q104_mesusu.value);
  var iSituacao       = new String(document.form1.situacao.value);
  var iTipoReg        = new String(document.form1.tiporeg.value);
  var iPosicaoAtual   = new String(document.form1.posicaoatual.value);

  if ( iAnoCompetencia == '' ) {
    alert('Campo Ano não informado. Verifique!');
    return false;
  }

  if ( iMesCompetencia == '' ) {
    alert('Campo Mês não informado. Verifique!');
    return false;
  } else {
    if ( iMesCompetencia < 1 || iMesCompetencia > 12 ) {
      alert('Valor informado para o campo Mês inválido!');
      return false;
    }
  }
  
  var sQuery  ="?iAnoCompetencia="+iAnoCompetencia
              +"&iMesCompetencia="+iMesCompetencia
              +"&iSituacao="      +iSituacao
              +"&iTipoReg="       +iTipoReg
              +"&iPosicaoAtual="  +iPosicaoAtual;
  
  var sUrl    = 'iss2_dadosimportacaomei002.php'+sQuery;
  var sParam  = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
  var jan     = window.open(sUrl,'',sParam);
      jan.moveTo(0,0);  
  
}

</script>
</html>