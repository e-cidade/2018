<?
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

session_start();

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("libs/db_utils.php");

validaUsuarioLogado();

$aRetorno = array();

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$iMatric  = $aRetorno['iMatric'];

$oRHPessoal = db_utils::getDao('rhpessoal');


$sSqlDadosServidor = "select *,
                             substr((select * from  db_fxxx(rh02_regist,rh02_anousu,rh02_mesusu,rh02_instit)),210,25) as padraoatual
											  from rhpessoal
											       inner join rhpessoalmov    on rh02_anousu                        = ".db_anofolha()." 
											                                 and rh02_mesusu                        = ".db_mesfolha()."
											                                 and rh02_regist                        = rh01_regist
											       left  join rhlota          on rhlota.r70_codigo                  = rhpessoalmov.rh02_lota
											                                 and rhlota.r70_instit                  = rhpessoalmov.rh02_instit
											       left  join rhpesbanco      on rh44_seqpes                        = rhpessoalmov.rh02_seqpes
											       inner join cgm             on cgm.z01_numcgm                     = rhpessoal.rh01_numcgm
											       left  join db_cgmruas      on db_cgmruas.z01_numcgm              = cgm.z01_numcgm
											       left  join ruas            on ruas.j14_codigo                    = db_cgmruas.j14_codigo
											       left  join ruastipo        on ruastipo.j88_codigo                = ruas.j14_tipo
											       inner join rhestcivil      on rhestcivil.rh08_estciv             = rhpessoal.rh01_estciv
											       left  join rhfuncao        on rhfuncao.rh37_funcao               = rhpessoal.rh01_funcao
											                                 and rhfuncao.rh37_instit               = rhpessoalmov.rh02_instit
											       left  join rhregime        on rhregime.rh30_codreg               = rhpessoalmov.rh02_codreg
											                                 and rhregime.rh30_instit               = rhpessoalmov.rh02_instit   
											       inner join rhinstrucao     on rhinstrucao.rh21_instru            = rhpessoal.rh01_instru
											       left  join rhpespadrao     on rhpespadrao.rh03_seqpes            = rhpessoalmov.rh02_seqpes 
											       left  join rhpesrescisao   on rh02_seqpes = rh05_seqpes
											 where rhpessoal.rh01_regist = {$iMatric}";
 
$rsDadosServidor = $oRHPessoal->sql_record($sSqlDadosServidor);
$oDadosServidor  = db_utils::fieldsMemory($rsDadosServidor,0);


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/estilos.css"        rel="stylesheet" type="text/css">
<link href="config/portalservidor.css" rel="stylesheet" type="text/css">

<script language="JavaScript" src="scripts/scripts.js"></script>
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <?mens_OnHelp()?>>
  <form name="form1" method="post" action="">
    <table  class="tableForm" width="750px;">
      <tr>
        <td class="tituloForm" colspan="6">
          <b>Dados Cadastrais</b>
        </td>
      </tr>
      <tr>
		    <td class="subTituloForm" colspan="6">
		      <b>Dados Pessoais</b>
		    </td>
		  </tr>
      <tr>
        <td class="labelForm" width="15%">
          Matrícula:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh01_regist?>
        </td>
        <td class="labelForm">
          CPF:
        </td>
        <td class="dadosForm">
          <?=db_formatar($oDadosServidor->z01_cgccpf,'cpf')?>
        </td>              
        <td class="labelForm">
          RG:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->z01_ident?>
        </td>                                                        
      </tr>
      <tr>
        <td class="labelForm">
          Nome:
        </td>
        <td colspan="5"  class="dadosForm">
          <?=$oDadosServidor->z01_nome?>
        </td>
      </tr>
      <tr>
        <td class="labelForm">
          Data Nascimento:
        </td>
        <td class="dadosForm">
          <?=db_formatar($oDadosServidor->rh01_nasc,'d')?>
        </td>              
        <td class="labelForm">
          Sexo:
        </td>
        <td class="dadosForm" colspan="3">
          <?
            if ( $oDadosServidor->z01_sexo == 'M' ) {
              echo 'Masculino';
            } else if ($oDadosServidor->z01_sexo == 'F') {
              echo 'Feminino';
            }
          ?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          Grau de Instrução:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh21_descr?>
        </td>              
        <td class="labelForm">
          Estado Civil:
        </td>
        <td class="dadosForm" colspan="3">
          <?=$oDadosServidor->rh08_descr?>
        </td>              
      </tr>            
		  <tr>
		    <td class="subTituloForm" colspan="6">
		      <b>Endereço</b>
		    </td>
		  </tr>          
      <tr>
        <td class="labelForm" width="15%">
          Tipo de Logradouro:
        </td>
        <td class="dadosForm" colspan="5">
          <?=$oDadosServidor->j88_descricao?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          Endereço:
        </td>
        <td class="dadosForm" colspan="5">
          <?=$oDadosServidor->z01_ender?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          Nº:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->z01_numero?>
        </td>              
        <td class="labelForm">
          Complemento:
        </td>
        <td class="dadosForm" colspan="3">
          <?=$oDadosServidor->z01_compl?>
        </td>              
      </tr>                        
      <tr>
        <td class="labelForm">
          Bairro:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->z01_bairro?>
        </td>              
        <td class="labelForm">
          Cidade:
        </td>
        <td class="dadosForm" colspan="3">
          <?=$oDadosServidor->z01_munic?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          UF:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->z01_uf?>
        </td>
        <td class="labelForm">
          CEP:
        </td>
        <td class="dadosForm" colspan="3">
          <?=$oDadosServidor->z01_cep?>
        </td>                                          
      </tr>
      <tr>
        <td class="labelForm">
          Telefone:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->z01_telef?>
        </td>              
        <td class="labelForm">
          Tel. Celular:
        </td>
        <td class="dadosForm" colspan="3">
          <?=$oDadosServidor->z01_telcel?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          Email:
        </td>
        <td class="dadosForm" colspan="5">
          <?=$oDadosServidor->z01_email?>
        </td>              
      </tr>                                                
		  <tr>
		    <td class="subTituloForm" colspan="6">
		      <b>Dados Admissionais</b>
		    </td>
		  </tr>          
      <tr>
        <td class="labelForm" width="15%">
          Data Admissão:
        </td>
        <td class="dadosForm">
          <?=db_formatar($oDadosServidor->rh01_admiss,'d')?>
        </td>              
        <td class="labelForm">
          Horas Semanais:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh02_hrssem?>
        </td>              
        <td class="labelForm">
          Horas Mensais:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh02_hrsmen?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          Lotação:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->r70_descr?>
        </td>              
        <td class="labelForm">
          Regime:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh30_descr?>
        </td>              
        <td class="labelForm">
          CBO:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh37_cbo?>
        </td>              
      </tr>
      <tr>
        <td class="labelForm">
          Cargo:
        </td>
        <td class="dadosForm" colspan="5">
          <?=$oDadosServidor->rh37_descr?>
        </td>              
      </tr>
      <tr>                            
        <td class="labelForm">
          Padrão Inicial:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh03_padrao?>
        </td>            
        <td class="labelForm">
          Padrão Atual:
        </td>
        <td class="dadosForm" colspan="3">
          <?=$oDadosServidor->padraoatual?>
        </td>              
      </tr>
      <tr>                            
        <td class="labelForm">
          Banco:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh44_codban?>
        </td>            
        <td class="labelForm">
          Agência:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh44_agencia."-".$oDadosServidor->rh44_dvagencia?>
        </td>
        <td class="labelForm">
          Conta:
        </td>
        <td class="dadosForm">
          <?=$oDadosServidor->rh44_conta."-".$oDadosServidor->rh44_dvconta?>
        </td>                 
      </tr>
      <tr>                                        
        <td class="tituloForm"  colspan="6'">
        </td>                 
      </tr>                  
    </table>
  </form>
</body>