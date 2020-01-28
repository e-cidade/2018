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

//MODULO: pessoal
$clgerfcom->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r13_proati");
$clrotulo->label("r01_numcgm");
$clrotulo->label("r06_descr");
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="Ano / Mês de competência">
      <b>Ano / Mês:</b>
    </td>
    <td> 
      <?
      db_input('r48_anousu',4,$Ir48_anousu,true,'text',3)
      ?>
      &nbsp;<b>/</b>&nbsp;
      <?
      db_input('r48_mesusu',2,$Ir48_mesusu,true,'text',3)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr48_semest?>">
      <b>Complementar Atual:</b>
    </td>
    <td> 
      <?
      db_input('r48_semest',1,$Ir48_semest,true,'text',3,"","semestatual")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr48_semest?>">
      <b>Próxima Complementar:</b>
    </td>
    <td> 
      <?
      db_input('r48_semest',1,$Ir48_semest,true,'text',3,"")
      ?>
    </td>
  </tr>
</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="Incluir">
</form>