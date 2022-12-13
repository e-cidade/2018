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

//MODULO: saude
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
//$clundmedhorario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("nome");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Tsd04_i_medico?>">
   Médico:
  </td>
  <td>
   <?db_input('sd04_i_medico',10,$Isd04_i_medico,true,'text',3," onchange='js_pesquisasd04_i_medico(false);'")?>
   <?db_input('z01_nome',80,$Iz01_nome,true,'text',3,'parent.iframe_a1.document.form1.sd02_i_codigo.value')?>
  </td>
 </tr>
</table>
<br>
<table width="80%" border="1">
 <tr style="border:2px solid #999999;background-color:#f3f3f3;font-weight:bold" align="center">
  <td>Data</td>
  <td>Turno</td>
  <td>N° Fichas</td>
  <td>Agendadas</td>
  <td>Reservadas</td>
  <td>Observações</td>
  <td>Hora Inicial</td>
  <td>Hora Final</td>
 </tr>
 <tr>
  <td align="center" colspan="8">
  Nenhum registro encontrado.
  </td>
 </tr>
</table>
</center>
<br>
</form>