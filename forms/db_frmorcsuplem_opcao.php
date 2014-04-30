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
?>
<br><br><br>
<center>
<table border="0" width="60%">

<?  
  //-- lista dados do projeto

  $res=$clauxiliar->sql_record("select * from orcprojeto where o39_codproj=$projeto");
  db_fieldsmemory($res,0);
  echo "<tr> 
         <td><b>Projeto:</b>  $projeto </td>
         <td colspan=2 align=left>$o39_descr</td>     
       </tr>
       <tr>
         <td colspan=3> &nbsp; </td>	 
       </tr>
       ";
  
 // lista todos os tipos de suplementação da orcsplemtipo
 // desabilita os tipos que já tem suplementação

   $pesquisa= "select o48_tiposup, 
                      o48_descr,
		      o48_coddocred,
		      o48_arrecadmaior,
		      o46_codsup    
               from orcsuplemtipo
   	       left outer join orcsuplem on o46_tiposup=o48_tiposup and o46_codlei=$projeto
               order by o48_tiposup
           ";

   $res=$clauxiliar->sql_record($pesquisa);
   // db_criatabela($res);
      for($x=0;$x < $clauxiliar->numrows ;$x++){
       db_fieldsmemory($res,$x);
        if ($o48_coddocred =="0")
           $qt_abas=2;
        else 
	   $qt_abas=3;
	//-------------------------  
        if ($o46_codsup==""){
          echo "<tr>
	         <td align=center> disponível </td>
	         <td><a href=orc1_orcsuplem004.php?o48_arrecadmaior=$o48_arrecadmaior&o46_tiposup=$o48_tiposup&qt_abas=$qt_abas&o46_codsup=$o46_codsup>$o48_tiposup </a></td>
		 <td>$o48_descr </td></tr>"; 
        } else {
          echo "<tr>
	         <td align=center>  já cadastrado </td>
	         <td><a href=orc1_orcsuplem004.php?o48_arrecadmaior=$o48_arrecadmaior&o46_tiposup=$o48_tiposup&qt_abas=$qt_abas&o46_codsup=$o46_codsup>$o48_tiposup </a></td>
		 <td>$o48_descr </td></tr>"; 


        }	 
   } 
   
?>


</table>
</center>