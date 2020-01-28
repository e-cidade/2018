<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("model/educacao/ArredondamentoNota.model.php");
require_once("model/educacao/DBEducacaoTermo.model.php");

$resultedu = eduparametros(db_getsession("DB_coddepto"));

$clmatricula         = new cl_matricula;
$clregencia          = new cl_regencia;
$clturma             = new cl_turma;
$claluno             = new cl_aluno;
$clregenciaperiodo   = new cl_regenciaperiodo;
$clprocavaliacao     = new cl_procavaliacao;
$cldiarioavaliacao   = new cl_diarioavaliacao;
$clperiodocalendario = new cl_periodocalendario;
$clpareceraval       = new cl_pareceraval;
$clparecerresult     = new cl_parecerresult;

$escola   = db_getsession("DB_coddepto");
$discglob = false;

$campos   = "ed29_i_codigo,ed29_c_descr,ed15_c_nome,ed52_c_descr,ed52_i_ano, ed57_c_descr";
$result_t = $clturma->sql_record( $clturma->sql_query_turmaserie( "", $campos, "", "ed220_i_codigo = {$turma}" ) );

if ( $clturma->numrows > 0 ) {

  db_fieldsmemory( $result_t, 0 );

  $sCamposMatricula  = "case when turma.ed57_i_tipoturma = 2 then fc_nomeetapaturma(ed60_i_turma)";
  $sCamposMatricula .= "else serie.ed11_c_descr end as ed11_c_descr";
  $sSqlMatricula     = $clmatricula->sql_query( "", $sCamposMatricula, "", "ed60_i_codigo = {$aluno}" );
  $result_m          = $clmatricula->sql_record( $sSqlMatricula );

  db_fieldsmemory( $result_m, 0 );
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td class="titulo">
      <?

      /**
       * Verifica se a turma � do tipo Integral e Infantil, alterando a forma como � apresentada a descri��o do
       * turno.
       * Por padr�o, mostra somente a descri��o do Turno (Ex.: MANH�)
       * No caso de turno Integral e Infantil, mostra tamb�m o turno referente o qual a matr�cula est� vinculada
       * Ex.: INTEGRAL - MANH� / TARDE
       */
      $oMatricula = MatriculaRepository::getMatriculaByCodigo( $aluno );
      if (    $oMatricula->getTurma()->getTurno()->isIntegral()
        && $oMatricula->getTurma()->getBaseCurricular()->getCurso()->getEnsino()->isInfantil()
      ) {

        $aDescricaoTurno = array();
        $aTurnoReferente = array( 1 => 'MANH�', 2 => 'TARDE', 3 => 'NOITE' );

        foreach ( $oMatricula->getTurnosVinculados() as $oTurnoReferente ) {
          $aDescricaoTurno[] = $aTurnoReferente[ $oTurnoReferente->ed336_turnoreferente ];
        }

        $ed15_c_nome = "INTEGRAL - " . implode( " / ", $aDescricaoTurno );
      }

      echo "Curso: {$ed29_i_codigo} - {$ed29_c_descr} &nbsp;&nbsp;&nbsp;";
      echo "Turno: {$ed15_c_nome} &nbsp;&nbsp;&nbsp;";
      echo "Calend�rio: {$ed52_c_descr} &nbsp;&nbsp;&nbsp;";
      echo "Turma: {$ed57_c_descr} &nbsp;&nbsp;&nbsp;";
      echo "Etapa: {$ed11_c_descr} &nbsp;&nbsp;&nbsp;";
     ?>
     </td>
    </tr>
    <tr>
     <td>
      <?GradeAproveitamentoHTML( $aluno, "S", $ed52_i_ano )?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<script>
function js_mostra(nomepar){
 document.getElementById(nomepar).style.visibility = "visible";
}
function js_oculta(nomepar){
 document.getElementById(nomepar).style.visibility = "hidden";
}
</script>
<?}?>