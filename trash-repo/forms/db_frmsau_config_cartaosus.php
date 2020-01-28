<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: Ambulatorial
$oSauConfig->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("s103_i_tipodb");
$oRotulo->label("s103_c_sgdb");
$oRotulo->label("s103_c_ip");
$oRotulo->label("s103_c_ipauto");
$oRotulo->label("s103_i_porta");
$oRotulo->label("s103_c_usuario");
$oRotulo->label("s103_c_senha");
?>
<form name="form_cartaosus" method="post" action="">
  <fieldset style="width: 450px; margin-top: 40px;">
    <legend><b>Cart�o SUS</b></legend>
    <table>
      <tr>
        <td nowrap title="<?=@$Ts103_i_tipodb?>">
          <?=$Ls103_i_tipodb?>
         </td>
         <td>
           <?
           $x = array('1'=>'Interbase', '2'=>'Postgres');
           db_select('s103_i_tipodb', $x, true, $db_opcao, "");
           ?>
         </td>
       </tr>                                                                                                         
       <tr>
         <td nowrap title="<?=@$Ts103_c_sgdb?>">
           <?=$Ls103_c_sgdb?>
         </td>
         <td>
           <?
           if(isset($s103_c_sgdb)){ 
             $s103_c_sgdb = stripslashes($s103_c_sgdb);
           }
           ?>
           <input type="text" size="40" name="s103_c_sgdb" id="s103_c_sgdb" value="<?=@$s103_c_sgdb?>">
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Ts103_c_ip?>">
           <?=$Ls103_c_ip?>
         </td>
         <td>
           <?
           if (@$s103_i_ip == "") {
             $s103_i_ip = $_SERVER['REMOTE_ADDR'];
           }
           db_input('s103_c_ip', 20, $Is103_c_ip,true,'text',$db_opcao);
           if(!isset($s103_c_ipauto)){
             $s103_c_ipauto='f';
           }
           ?>
           &nbsp;
           &nbsp;
           <input type="checkbox" name="s103_c_ipauto" id="s103_c_ipauto" <?=($s103_c_ipauto=='t')?"checked":""?>>IP Autom�tico
         </td>
       </tr>
       <tr>   
         <td nowrap title="<?=@$Ts103_i_porta?>">
           <?=$Ls103_i_porta?>
         </td>
         <td>
           <?
           if (@$s103_i_porta == "") {
             $s103_i_porta = "3050";
           }
           db_input('s103_i_porta', 20, $Is103_i_porta, true, 'text', $db_opcao)
           ?>
         </td>
       </tr>
       <tr>   
         <td nowrap title="<?=@$Ts103_c_usuario?>">
           <?=$Ls103_c_usuario?>
         </td>
         <td>
           <?
           if (@$s103_c_usuario == "") {
             $s103_c_usuario = "SYSDBA";
           }
           db_input('s103_c_usuario', 20, $Is103_c_usuario, true, 'text', $db_opcao)
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Ts103_c_senha?>">
           <?=$Ls103_c_senha?>
         </td>
         <td>
           <?
           db_input('s103_c_senha', 20, $Is103_c_senha, true, 'password', $db_opcao)
           ?>
         </td>
       </tr>
       <tr>
        <td colspan="2" align="center">
          <input type="submit" value="<?=($db_opcao==1?'Incluir':'Alterar')?>" 
                 name="<?=($db_opcao==1?'incluir':'alterar')?>" style="margin-top: 10px;">
        </td>
      </tr>
     </table>
   </fieldset>
</form>