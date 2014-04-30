<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libpessoal.php");
require_once("dbforms/db_funcoes.php");
$DB_SERVIDOR = '192.168.0.24';
$DB_BASE     = 'auto_bage_20110427_v2_2_51';
$DB_PORTA    = '5432';
$DB_USUARIO  = 'postgres';
$DB_SENHA    = '';
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<center>
  <form action="" method="post" name='form1'>
    <div style="display: table;margin-top: 25px">
      <fieldset>
        <legend>
          <b>Processar Arquivo Agilplan</b>
        </legend>
        <table>
          <tr>
            <td align="left" nowrap title="Digite o Ano / Mes de competência" >
            <strong>Ano / Mês :&nbsp;&nbsp;</strong>
            </td>
            <td>
              <?
               $DBtxt23 = db_anofolha();
               db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
              ?>
              &nbsp;/&nbsp;
              <?
               $DBtxt25 = db_mesfolha();
               db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <strong>Dia Vencimento:</strong>
            </td>
            <td>
            <?
              db_input('diavencimento',4, $IDBtxt25,true,'text',2,'');
            ?>
            </td>
           </tr>
         </table>
       </fieldset>
       <div style='text-align: center'>
         <input type="submit" value='Processar' name='processar'>
       </div> 
    </div>
    <div id='termometro' style='background-color: transparent;display: none'>
    <? 
     db_criatermometro('pessoal', 'concluido');
    ?>
    </div> 
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
if (isset($_POST["processar"])) {
  
  $oPost                 = db_utils::postMemory($_POST);
  $iMes                 = $oPost->DBtxt25;
  $iAno                 = $oPost->DBtxt23;
  $iInstit              = db_getsession("DB_instit");
  $iDiaVencimentoCartao = $oPost->diavencimento;
  echo "<script>document.getElementById('termometro').style.display=''</script>";
  
  $aEstadoCivil = array(
                      1 => "Solteiro",
                      0 => "Solteiro",
                        2 => "Casado",
                        3 => "Viuvo",
                        4 => 'Divorciado'
                       );
                       
  $sSqlTotalProventos  = "select sum(r53_valor) as salario ";
  $sSqlTotalProventos .= "  from gerffx    ";
  $sSqlTotalProventos .= " where r53_anousu = {$iAno} ";
  $sSqlTotalProventos .= "   and r53_mesusu = {$iMes} ";
  $sSqlTotalProventos .= "   and r53_pd     = 1 ";
  $sSqlTotalProventos .= "   and r53_regist = rh01_regist";
  
  
  $sSqlFuncionarios  = " select (cast(rh02_anousu::varchar||'-'||rh02_mesusu::varchar||'-01'::varchar as date))  as dataatualizacao, ";
  $sSqlFuncionarios .= "        rh01_regist                   as codigoregistrofuncionario, ";
  $sSqlFuncionarios .= "        z01_cgccpf                    as cpf, ";
  $sSqlFuncionarios .= "        z01_nome                      as nomefuncionario, ";
  $sSqlFuncionarios .= "        to_char(rh01_nasc,'dd mm YYYY')   as datanascimento, ";
  $sSqlFuncionarios .= "        to_char(rh01_admiss,'dd mm YYYY') as dataadmissao, ";
  $sSqlFuncionarios .= "        coalesce(to_char(rh05_recis, 'DDMMYYYYY')) as datademissao, ";
  $sSqlFuncionarios .= "        rh01_funcao                   as codigocargofuncionario, ";
  $sSqlFuncionarios .= "        rh37_descr                    as nomecargofuncionario, ";
  $sSqlFuncionarios .= "        rh02_lota                     as codigosetor, ";
  $sSqlFuncionarios .= "        r70_descr                     as nomesetor, ";
  $sSqlFuncionarios .= "        upper(rh01_sexo)               as sexofuncionario, ";
  $sSqlFuncionarios .= "        z01_ident                     as registrogeralindentificacao, ";
  $sSqlFuncionarios .= "        coalesce(z01_estciv, '1')      as estadocivil, ";
  $sSqlFuncionarios .= "        rh37_cbo                      as cbo, ";
  $sSqlFuncionarios .= "        rh16_pis                      as nitpispasep, ";
  $sSqlFuncionarios .= "        rh02_tpcont                   as categoriatrabalhador, ";
  $sSqlFuncionarios .= "        trim(z01_ender)               as endereco, ";
  $sSqlFuncionarios .= "        trim(z01_numero)              as numeroendereco, ";
  $sSqlFuncionarios .= "        trim(z01_bairro)              as bairrofunc, ";
  $sSqlFuncionarios .= "        z01_munic                     as cidadefunc, ";
  $sSqlFuncionarios .= "        z01_uf                        as unidadeferderacaouf, ";
  $sSqlFuncionarios .= "        z01_cep                       as cepfuncionario, ";
  $sSqlFuncionarios .= "        z01_telef                     as telefone, ";
  $sSqlFuncionarios .= "        z01_telcel                    as telefonecelular, ";
  $sSqlFuncionarios .= "        z01_nacion                    as nacionalidade, ";
  $sSqlFuncionarios .= "        z01_ident                     as identidade, ";
  $sSqlFuncionarios .= "        z01_pai                       as pai, ";
  $sSqlFuncionarios .= "        z01_mae                       as mae,  ";
  $sSqlFuncionarios .= "        z01_email                     as email,  ";
  $sSqlFuncionarios .= "        rh44_codban                   as banco,  ";
  $sSqlFuncionarios .= "        db90_descr                    as nomebanco,  ";
  $sSqlFuncionarios .= "        rh44_agencia                  as agenciabanco,  ";
  $sSqlFuncionarios .= "        rh44_agencia                  as agenciabanco,  ";
  $sSqlFuncionarios .= "        rh44_dvagencia                as dvagenciabanco,  ";
  $sSqlFuncionarios .= "        rh44_conta                    as contabanco,  ";
  $sSqlFuncionarios .= "        rh44_dvconta                  as dvcontabanco,  ";
  $sSqlFuncionarios .= "        db90_codban                   as codigobanco,  ";
  $sSqlFuncionarios .= "        coalesce(({$sSqlTotalProventos}), 0) as valorsalario,";
  $sSqlFuncionarios .= "        db_config.*";               
  $sSqlFuncionarios .= "   from rhpessoal ";
  $sSqlFuncionarios .= "        inner join cgm              on cgm.z01_numcgm                   = rhpessoal.rh01_numcgm ";
  $sSqlFuncionarios .= "        inner join rhpessoalmov     on rhpessoalmov.rh02_regist         = rhpessoal.rh01_regist ";
  $sSqlFuncionarios .= "                                   and rhpessoalmov.rh02_anousu         = {$iAno} ";
  $sSqlFuncionarios .= "                                   and rhpessoalmov.rh02_mesusu         = {$iMes} ";
  $sSqlFuncionarios .= "                                   and rhpessoalmov.rh02_instit         = {$iInstit} ";
  $sSqlFuncionarios .= "        left join rhfuncao         on rhfuncao.rh37_funcao             = rhpessoal.rh01_funcao ";
  $sSqlFuncionarios .= "                                   and rhfuncao.rh37_instit             = rh02_instit ";
  $sSqlFuncionarios .= "        left  join rhpesdoc         on rhpesdoc.rh16_regist             = rhpessoal.rh01_regist ";
  $sSqlFuncionarios .= "        left  join cfpess           on cfpess.r11_anousu                = rhpessoalmov.rh02_anousu ";
  $sSqlFuncionarios .= "                                   and cfpess.r11_mesusu                = rhpessoalmov.rh02_mesusu ";
  $sSqlFuncionarios .= "                                   and cfpess.r11_tbprev                = rhpessoalmov.rh02_tbprev ";
  $sSqlFuncionarios .= "                                   and cfpess.r11_instit                = rhpessoalmov.rh02_instit ";
  $sSqlFuncionarios .= "        inner join rhlota           on rhlota.r70_codigo                = rhpessoalmov.rh02_lota ";
  $sSqlFuncionarios .= "                                   and rhlota.r70_instit                = rh02_instit ";
  $sSqlFuncionarios .= "        inner join rhregime         on rhregime.rh30_codreg             = rhpessoalmov.rh02_codreg ";
  $sSqlFuncionarios .= "                                   and rhregime.rh30_instit             = rh02_instit ";
  $sSqlFuncionarios .= "        left  join rhnaturezaregime on rhnaturezaregime.rh71_sequencial = rhregime.rh30_naturezaregime ";
  $sSqlFuncionarios .= "        left  join rhpesrescisao    on rhpesrescisao.rh05_seqpes        = rhpessoalmov.rh02_seqpes ";
  $sSqlFuncionarios .= "        left  join rhpesbanco       on rhpessoalmov.rh02_seqpes         = rh44_seqpes ";
  $sSqlFuncionarios .= "        left  join  db_bancos       on db90_codban                      = rh44_codban ";
  $sSqlFuncionarios .= "        inner join db_config        on rh02_instit                      = codigo ";
  $sSqlFuncionarios .= "   where (rh05_seqpes is null or (extract(year from rh05_recis)  = '$iAno' and ";
  $sSqlFuncionarios .= "                                  extract(month from rh05_recis) = '$iMes')) ";
  $sSqlFuncionarios .= "     and  rh30_vinculo = 'A'";
                                                          
  $rsDados      = pg_query($sSqlFuncionarios);                                                        
  $iNumRows     = pg_num_rows($rsDados);
  $sNomeArquivo = "tmp/agilplan{$iMes}{$iAno}.txt";
  $rsArquivo    = fopen($sNomeArquivo, "w");
  $sFiller      = " ";
  $sHeader      = date('dmY').$sFiller."00001{$sFiller}\n";
  fputs($rsArquivo, $sHeader);
  for ($i = 0; $i < $iNumRows; $i++) {
    
    
    $sLinha  = str_repeat(" ", 15);//inicio em brancoidentificador
    $sLinha .= str_repeat(" ", 4);//identificador
    $sLinha .= $sFiller;
    $sLinha .= str_repeat(" ", 15);//identificador
    $oLinha  = db_utils::fieldsMemory($rsDados, $i);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->nomefuncionario, 0, 50), 50, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->nomefuncionario, 0, 20), 20, " ", STR_PAD_RIGHT);  
    $sLinha .= $sFiller;
    $sLinha .= $oLinha->datanascimento;
    $sLinha .= $sFiller;
    $sLinha .= str_pad($oLinha->cpf, 15, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->nacionalidade, 0, 13), 13, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= $oLinha->sexofuncionario;
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($aEstadoCivil[$oLinha->estadocivil], 0, 15), 15, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 2, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->identidade, 0, 20), 20, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 6, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 2, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->pai, 0, 50),  50, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->mae, 0, 50),  50, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->email, 0, 30),  30, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('',  3, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad($iDiaVencimentoCartao,  2, "0", STR_PAD_LEFT);
    /*
     * dados residenciais
     */
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->endereco.", {$oLinha->numeroendereco}", 0, 50),  50, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->bairrofunc, 0, 20),  20, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->cidadefunc, 0, 20),  20, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->unidadeferderacaouf, 0, 3),  3, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->cepfuncionario, 0, 10),  10, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->telefone, 0, 15),  15, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->telefonecelular, 0, 15),  15, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('',  1, " ", STR_PAD_RIGHT);
    /*
     * Referencia pessoal
     */
    $sLinha .= $sFiller;
    $sLinha .= str_pad('',  50, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 15, " ", STR_PAD_RIGHT);
    
    /**
     * referencia bancaria
     */
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->nomebanco, 0, 30), 30, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr("{$oLinha->agenciabanco}{$oLinha->dvagenciabanco}", 0, 6), 6, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr("{$oLinha->contabanco}{$oLinha->dvcontabanco}", 0, 12), 12, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(trim($oLinha->codigobanco), 3, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 2, " ", STR_PAD_LEFT);
    
    /*
     * outros dados de cartoes
     */
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 15, " ", STR_PAD_LEFT);
    
    /**
     * Dados Profissionais
     */
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->nomeinst, 0, 35), 35, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->ender.", ".$oLinha->numero, 0, 50), 50, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->cep, 0, 10), 10, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->bairro, 0, 20), 20, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->munic, 0, 20), 20, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->uf, 0, 20), 20, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->telef, 0, 15), 15, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 6, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 35, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->nomecargofuncionario, 0, 35), 35, " ", STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(substr($oLinha->dataadmissao, 7, 2)." ".substr($oLinha->dataadmissao, 7, 2), 5, " ", STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(str_replace(".","",number_format($oLinha->valorsalario, 2, "","")), 10, 0, STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(0, 10, 0, STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad(0, 10, 0, STR_PAD_LEFT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 50, ' ', STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    $sLinha .= str_pad('', 20, ' ', STR_PAD_RIGHT);
    $sLinha .= $sFiller;
    
    /**
     *outros cartoes
     * foi gerado em branco o total das posições.
     */
    $sLinha .= str_repeat(" ", 133);
    $sLinha .= $sFiller;
    $sLinha .= str_pad($oLinha->codigoregistrofuncionario, 15, "0", STR_PAD_LEFT);  
    fputs($rsArquivo, $sLinha."\n");
    db_atutermometro($i, $iNumRows, "pessoal");
  }
  fputs($rsArquivo, str_pad($iNumRows, 5, "0", STR_PAD_LEFT));
  fclose($rsArquivo);
  echo "<script>";
  echo "  listagem = '$sNomeArquivo#Download do arquivo';";
  echo "  js_montarlista(listagem,'form1');";
  echo "  document.getElementById('termometro').style.display='none'";
  echo "</script>";  
}