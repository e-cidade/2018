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

//MODULO: contabilidade
$clconparametro->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
  <fieldset>
    <legend>
      <b>Parâmetros da Contabilidade</b>
    </legend>
		<table border="0">
		  <tr>
		    <td nowrap title="<?=@$Tc90_estrutsistema?>">
		      <input name="oid" type="hidden" value="<?=@$oid?>">
		      <?=@$Lc90_estrutsistema?>
		    </td>
		    <td> 
					<?
					  db_input('c90_estrutsistema',50,$Ic90_estrutsistema,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tc90_estrutcontabil?>">
		      <?=@$Lc90_estrutcontabil?>
		    </td>
		    <td> 
					<?
				  	db_input('c90_estrutcontabil',50,$Ic90_estrutcontabil,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
		  <tr>
		    <td nowrap title="<?=@$Tc90_codestrut?>">
		      <?=@$Lc90_codestrut?>
		    </td>
		    <td> 
					<?
					  db_input('c90_codestrut',8,$Ic90_codestrut,true,'text',$db_opcao,"");
					?>
		    </td>
		  </tr>
      <tr>
        <td nowrap title="<?=@$Tc90_utilcontabancaria?>">
          <?=@$Lc90_utilcontabancaria?>
        </td>
        <td> 
          <?
            $aUtilContaBancaria = array( 'f'=>'Não',
                                         't'=>'Sim');
            
            db_select('c90_utilcontabancaria',$aUtilContaBancaria,true,1,"style='width:80px;'");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tc90_usapcasp?>">
          <?=@$Lc90_usapcasp?>
        </td>
        <td> 
          <?
            $aUsaPCASP = array( 'f'=>'Não',
                                't'=>'Sim');
            
            db_select('c90_usapcasp', $aUsaPCASP, true, 1, "style='width:80px;'");
          ?>
        </td>
      </tr>		  
	  </table>
  </fieldset> 
</center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>