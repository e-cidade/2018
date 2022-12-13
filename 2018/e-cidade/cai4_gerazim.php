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

$result = pg_exec("
select o08_reduz,o02_descr,k12_receit,arrec, estorno 
from orcam 
  left outer join 
(
select o08_reduz,k12_receit,sum(k12_arrec) as arrec, sum(k12_estorno) as estorno
from 
(select o08_reduz,corrente.k12_conta,cornump.k12_receit,case when cornump.k12_valor > 0 then cornump.k12_valor else 0 end as k12_arrec, case when cornump.k12_valor < 0 then cornump.k12_valor else 0 end as k12_estorno
 from corrente
      inner join cornump   
           on corrente.k12_id     = cornump.k12_id 
           and corrente.k12_data   = cornump.k12_data 
           and corrente.k12_autent = cornump.k12_autent
     inner join tabrec 
           on cornump.k12_receit = k02_codigo 
     inner join taborc
	       on tabrec.k02_codigo = taborc.k02_codigo and taborc.k02_anousu = ".$GLOBALS["DB_anousu"]."
     inner join receitas on k02_estorc = o08_codigo		  
 where corrente.k12_instit = " . db_getsession("DB_instit") . " and corrente.k12_data between '$datai' and '$dataf'
 order by taborc.k02_estorc
) as x
group by k02_estorc, k12_receit
order by k02_estorc
) as receitas 
on k02_estorc::char(13) = o02_codigo and o02_anousu =  ".$GLOBALS["DB_anousu"]."
");

$numrows = pg_numrows($result);
set_time_limit(0);
$clabre_arquivo =  new cl_abre_arquivo();
if($clabre_arquivo->arquivo!=false){
  if($numrows!=false){
    for($i=0;$i<$numrows;$i++){
	   db_fieldsmemory($result,$i,0);
       fputs($clabre_arquivo->arquivo,str_pad($o08_reduz)."\n");
       fputs($clabre_arquivo->arquivo,str_pad($k12_receit)."\n");
       fputs($clabre_arquivo->arquivo,str_pad($arrec)."\n");
       fputs($clabre_arquivo->arquivo,str_pad($estorno));
    }
  }  
  fclose($clabre_arquivo->arquivo);
}
?>