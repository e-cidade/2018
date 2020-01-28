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
 
$resultnoti = $clnotificacao->sql_record($clnotificacao->sql_query($notifi));
db_fieldsmemory($resultnoti,0);
?>
      <form name="form1" action="" method="post">  
  <tr>
     <td align="center"><strong>
     Recebimento de Notificação</strong>
     </td>
  </tr>
  <tr>
     <td>&nbsp;</td>
  </tr>
  <table  border="1" cellspacing="0" align="center">
    <tr> 
      <td nowrap title="<?=@$Tk50_notifica?>"><?=$Lk50_notifica?></td>
         <td >
	 <?
	  db_input('k50_notifica',6,$Ik50_notifica,true,'text',2,'')
	 ?>
         </td>
      <td nowrap title="<?=@$Tk50_dtemite?>"><?=$Lk50_dtemite?></td>
         <td>
        <?
	  db_inputdata("k50_dtemite",$k50_dtemite_dia,$k50_dtemite_mes,$k50_dtemite_ano,true,5,3);
	?>
      </td>
    </tr>
    <tr> 
      <td nowrap title="<?=@$Tk54_data?>"><?=$Lk54_data?></td>
         <td>
        <?
	 if ($k54_data == ''){
	     $k54_data_dia = date('d');
	     $k54_data_mes = date('m');
	     $k54_data_ano = date('Y');
	 }
	     db_inputdata("k54_data",$k54_data_dia,$k54_data_mes,$k54_data_ano,true,2,1);
	?>
      </td>
      <td nowrap title="<?=@$Tk54_hora?>"><?=$Lk54_hora?></td>
         <td>
	 <?
          if ($k54_hora == ''){
	     $k54_hora = date('H:i');
          }
	 db_input('k54_hora',5,$Ik54_hora,true,'text',2,'');
	 ?>
         </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tk54_assinante?>"><?=$Lk54_assinante?></td>
         <td colspan="3">
	 <?
	 db_input('k54_assinante',40,$Ik54_assinante,true,'text',2,'')
	 ?>
         </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tk54_obs?>"><?=$Lk54_obs?></td>
         <td colspan="3">
	 <?
	 db_textarea('k54_obs',5,40,$Ik54_obs,true,'text',4);
	 ?>
         </td>
    </tr>
    <tr>
       <td height="25" nowrap title="<?=$Tk54_codigo?>"><?=$Lk54_codigo?></td>
       <td colspan="3" height="25" nowrap>&nbsp; &nbsp;
       <?
         $clnotisitu = new cl_notisitu;
         $result = $clnotisitu->sql_record($clnotisitu->sql_query("","k59_codigo#k59_descr","k59_descr"));
         db_selectrecord("k54_codigo",$result,true,2);
       ?>
       </td>
    </tr>
    <tr>
       <td colspan="5" align="center">
          <input type="submit" name="incluir" value="Atualizar">
       </td>
    </tr>
  </table>
</form>