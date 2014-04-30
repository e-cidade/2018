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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_turma_classe.php");
include("classes/db_calendario_classe.php");
include("classes/db_turnoreferente_classe.php");
$clturma = new cl_turma;
$clcalendario = new cl_calendario;
$clturnoreferente = new cl_turnoreferente;
$result = $clcalendario->sql_record($clcalendario->sql_query("","ed52_d_inicio",""," ed52_i_codigo = $calendario"));
db_fieldsmemory($result,0);
$result_ref = $clturnoreferente->sql_record($clturnoreferente->sql_query("","ed231_i_referencia",""," ed231_i_turno = $turno"));
$referencias = "";
$sep = "";
for($t=0;$t<$clturnoreferente->numrows;$t++){
 db_fieldsmemory($result_ref,$t);
 $referencias .= $sep.$ed231_i_referencia;
 $sep = ",";
}
$sql = "SELECT * from turma
         inner join turno on ed15_i_codigo = ed57_i_turno
         inner join calendario on ed52_i_codigo = ed57_i_calendario
         inner join turnoreferente on ed231_i_turno = ed15_i_codigo
        WHERE ed57_i_sala = $sala
        AND ed57_i_escola = $escola
        AND ed231_i_referencia in ($referencias)
        AND '$ed52_d_inicio' between ed52_d_inicio AND ed52_d_fim
        AND ed57_i_codigo not in ($turma)
       ";
$result = $clturma->sql_record($sql);
if($clturma->numrows>0){
 db_fieldsmemory($result,0);
 ?>
 <script>
  if(!confirm("Turma <?=$ed57_c_descr?> já está usando esta sala no turno <?=$ed15_c_nome?>, no calendário <?=$ed52_c_descr?>.\n Confirmar esta sala para esta turma também?")){
   parent.document.form1.ed57_i_sala.value = "";
   parent.document.form1.ed16_c_descr.value = "";
   parent.document.form1.ed16_i_capacidade.value = "0";
   parent.document.form1.ed57_i_numvagas.value = "0";
   parent.document.form1.ed57_i_nummatr.value = "0";
   parent.document.form1.restantes.value = "0";
  }
 </script>
 <?
}
?>