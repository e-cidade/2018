<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

$clturma          = new cl_turma;
$clcalendario     = new cl_calendario;
$clturnoreferente = new cl_turnoreferente;

$sSqlCalendario = $clcalendario->sql_query( "", "ed52_d_inicio", "", "ed52_i_codigo = {$calendario}" );
$result         = $clcalendario->sql_record( $sSqlCalendario );
db_fieldsmemory( $result, 0 );

$sSqlTurnoReferente = $clturnoreferente->sql_query( "", "ed231_i_referencia", "", "ed231_i_turno = {$turno}" );
$result_ref         = $clturnoreferente->sql_record( $sSqlTurnoReferente );

$referencias = "";
$sep         = "";

for ( $t = 0; $t < $clturnoreferente->numrows; $t++ ) {

  db_fieldsmemory( $result_ref, $t );
  $referencias .= $sep.$ed231_i_referencia;
  $sep          = ",";
}

$sql    = " SELECT * ";
$sql   .= "   FROM turma ";
$sql   .= "        inner join turno          on ed15_i_codigo = ed57_i_turno ";
$sql   .= "        inner join calendario     on ed52_i_codigo = ed57_i_calendario ";
$sql   .= "        inner join turnoreferente on ed231_i_turno = ed15_i_codigo ";
$sql   .= "  WHERE ed57_i_sala        = {$sala} ";
$sql   .= "    AND ed57_i_escola      = {$escola} ";
$sql   .= "    AND ed231_i_referencia in ({$referencias}) ";
$sql   .= "    AND '{$ed52_d_inicio}' between ed52_d_inicio AND ed52_d_fim ";
$sql   .= "    AND ed57_i_codigo not in ({$turma}) ";
$result = $clturma->sql_record( $sql );

if ( $clturma->numrows > 0 ) {

  db_fieldsmemory( $result, 0 );
  ?>
  <script>
  sString = "Turma <?=$ed57_c_descr?> já está usando esta sala no turno <?=$ed15_c_nome?>, no calendário <?=$ed52_c_descr?>";
  sString += ".\n Confirmar esta sala para esta turma também";
   if ( !confirm( sString ) ) {

     parent.document.form1.ed57_i_sala.value       = "";
     parent.document.form1.ed16_c_descr.value      = "";
     parent.document.form1.ed16_i_capacidade.value = "0";
   }
 </script>
 <?
}
?>