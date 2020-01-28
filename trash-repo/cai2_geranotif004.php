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
include("classes/db_listanotifica_classe.php");
$cllistanotifica = new cl_listanotifica;

echo "lista = $lista";
$instit = db_getsession("DB_instit");
$minimo = 0;
$maximo = 0;
if($valor1 > 0){  
  $minimo = $valor1;
  $maximo = $valor2;
}elseif ($lista != '' ){
  $resultlistanotifica = $cllistanotifica->sql_record($cllistanotifica->sql_query("","*",""," min(k63_notifica), max(k63_notifica) ",""," k63_codigo =  $lista and k50_instit = $instit and k60_instit = $instit" ));
  db_fieldsmemory($resultlistanotifica,0);
  $minimo = $min;
  $maximo = $max;
}
if($minimo == 0){
  db_msgbox(_M('tributario.notificacoes.cai2_geranotif004.nao_existem_notificacoes'));
  echo "<script>parent.location.href='cai2_geranotif002.php'</script>";
  exit;
}
echo "<br> minimo = $minimo <br> maximo = $maximo";

$sqlSituacao = "select distinct k54_codigo,k59_descr 
                from noticonf 
                inner join notisitu on k54_codigo = k59_codigo 
                where k54_notifica between $minimo and $maximo";
$resultSituacao = db_query($sqlSituacao);
$linhasSituacao = pg_num_rows($resultSituacao);
if($linhasSituacao > 0){
  $situacao ="";
  $vir = "";
  for($i=0;$i<$linhasSituacao;$i++){
    db_fieldsmemory($resultSituacao,$i);
    $situacao .= $vir.$k59_descr;
    $vir = ", ";
  }

  echo" <script>
          parent.js_excluinoti(true,'$situacao');   
        </script>";
}else{
  echo" <script>
          parent.js_excluinoti(false,'');
        </script>";

}


?>