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

//MODULO: Escola
class cl_alunoaltera {
 // cria variaveis de erro
 var $rotulo     = null;
 var $pagina_retorno = null;
 // cria variaveis do arquivo
 var $ed47_i_codigo = 0;
 var $ed47_v_nome = null;
 var $ed47_v_ender = null;
 var $ed47_c_numero = null;
 var $ed47_v_compl = null;
 var $ed47_v_bairro = null;
 var $ed47_v_cep = null;
 var $ed47_c_raca = null;
 var $ed47_v_cxpostal = null;
 var $ed47_v_telef = null;
 var $ed47_v_ident = null;
 var $ed47_c_nomeresp = null;
 var $ed47_c_emailresp = null;
 var $ed47_c_atenddifer = null;
 var $ed47_t_obs = null;
 var $ed47_c_transporte = null;
 var $ed47_c_zona = null;
 var $ed47_c_certidaotipo = null;
 var $ed47_c_certidaonum = null;
 var $ed47_c_certidaolivro = null;
 var $ed47_c_certidaofolha = null;
 var $ed47_c_certidaocart = null;
 var $ed47_c_certidaodata_dia = null;
 var $ed47_c_certidaodata_mes = null;
 var $ed47_c_certidaodata_ano = null;
 var $ed47_c_certidaodata = null;
 var $ed47_c_nis = null;
 var $ed47_c_bolsafamilia = null;
 var $ed47_d_dtemissao_dia = null;
 var $ed47_d_dtemissao_mes = null;
 var $ed47_d_dtemissao_ano = null;
 var $ed47_d_dtemissao = null;
 var $ed47_d_dthabilitacao_dia = null;
 var $ed47_d_dthabilitacao_mes = null;
 var $ed47_d_dthabilitacao_ano = null;
 var $ed47_d_dthabilitacao = null;
 var $ed47_d_dtvencimento_dia = null;
 var $ed47_d_dtvencimento_mes = null;
 var $ed47_d_dtvencimento_ano = null;
 var $ed47_d_dtvencimento = null;
 var $ed47_d_nasc_dia = null;
 var $ed47_d_nasc_mes = null;
 var $ed47_d_nasc_ano = null;
 var $ed47_d_nasc = null;
 var $ed47_i_estciv = 0;
 var $ed47_i_nacion = 0;
 var $ed47_v_categoria = null;
 var $ed47_v_cnh = null;
 var $ed47_v_contato = null;
 var $ed47_v_cpf = null;
 var $ed47_v_email = null;
 var $ed47_v_fax = null;
 var $ed47_v_mae = null;
 var $ed47_v_pai = null;
 var $ed47_v_profis = null;
 var $ed47_v_sexo = null;
 var $ed47_v_telcel = null;
 var $ed47_c_codigoinep = null;
 var $ed47_i_pais = 0;
 var $ed47_d_identdtexp_dia = null;
 var $ed47_d_identdtexp_mes = null;
 var $ed47_d_identdtexp_ano = null;
 var $ed47_d_identdtexp = null;
 var $ed47_v_identcompl = null;
 var $ed47_c_passaporte = null;
 var $ed47_i_transpublico = 0;
 var $ed47_i_filiacao = 0;
 var $ed47_i_censoufend = 0;
 var $ed47_i_censomunicend = 0;
 var $ed47_i_censoorgemissrg = 0;
 var $ed47_i_censoufident = 0;
 var $ed47_i_censoufcert = 0;
 var $ed47_i_censomuniccert = 0;
 var $ed47_i_censoufnat = 0;
 var $ed47_i_censomunicnat = 0;
 function cl_alunoaltera() {
  $this->rotulo = new rotulo("aluno");
  $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
 }
 function Pais($pais){
  if($pais!=""){
   $result = db_query("SELECT ed228_c_descr FROM pais WHERE ed228_i_codigo = $pais");
   return trim(pg_result($result,0,0));
  }else{
   return "";
  }
 }
 function SiglaUF($uf){
  if($uf!=""){
   $result = db_query("SELECT ed260_c_sigla FROM censouf WHERE ed260_i_codigo = $uf");
   return trim(pg_result($result,0,0));
  }else{
   return "";
  }
 }
 function Municipio($municipio){
  if($municipio!=""){
   $result = db_query("SELECT ed261_c_nome FROM censomunic WHERE ed261_i_codigo = $municipio");
   return trim(pg_result($result,0,0));
  }else{
   return "";
  }
 }
 function OrgaoRG($orgao){
  if($orgao!=""){
   $result = db_query("SELECT ed132_c_descr FROM censoorgemissrg WHERE ed132_i_codigo = $orgao");
   return trim(pg_result($result,0,0));
  }else{
   return "";
  }
 }
 function atualizacamposlog() {
  $this->ed47_i_codigo = ($this->ed47_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_codigo"]:$this->ed47_i_codigo);
  $this->ed47_v_nome = ($this->ed47_v_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_nome"]:$this->ed47_v_nome);
  $this->ed47_v_ender = ($this->ed47_v_ender == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_ender"]:$this->ed47_v_ender);
  $this->ed47_c_numero = ($this->ed47_c_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_numero"]:$this->ed47_c_numero);
  $this->ed47_v_compl = ($this->ed47_v_compl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_compl"]:$this->ed47_v_compl);
  $this->ed47_v_bairro = ($this->ed47_v_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_bairro"]:$this->ed47_v_bairro);
  $this->ed47_v_cep = ($this->ed47_v_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cep"]:$this->ed47_v_cep);
  $this->ed47_c_raca = ($this->ed47_c_raca == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_raca"]:$this->ed47_c_raca);
  $this->ed47_v_cxpostal = ($this->ed47_v_cxpostal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cxpostal"]:$this->ed47_v_cxpostal);
  $this->ed47_v_telef = ($this->ed47_v_telef == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_telef"]:$this->ed47_v_telef);
  $this->ed47_v_ident = ($this->ed47_v_ident == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_ident"]:$this->ed47_v_ident);
  $this->ed47_c_nomeresp = ($this->ed47_c_nomeresp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_nomeresp"]:$this->ed47_c_nomeresp);
  $this->ed47_c_emailresp = ($this->ed47_c_emailresp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_emailresp"]:$this->ed47_c_emailresp);
  $this->ed47_c_atenddifer = ($this->ed47_c_atenddifer == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_atenddifer"]:$this->ed47_c_atenddifer);
  $this->ed47_t_obs = ($this->ed47_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_t_obs"]:$this->ed47_t_obs);
  $this->ed47_c_transporte = ($this->ed47_c_transporte == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_transporte"]:$this->ed47_c_transporte);
  $this->ed47_c_zona = ($this->ed47_c_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_zona"]:$this->ed47_c_zona);
  $this->ed47_c_certidaotipo = ($this->ed47_c_certidaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaotipo"]:$this->ed47_c_certidaotipo);
  $this->ed47_c_certidaonum = ($this->ed47_c_certidaonum == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaonum"]:$this->ed47_c_certidaonum);
  $this->ed47_c_certidaolivro = ($this->ed47_c_certidaolivro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaolivro"]:$this->ed47_c_certidaolivro);
  $this->ed47_c_certidaofolha = ($this->ed47_c_certidaofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaofolha"]:$this->ed47_c_certidaofolha);
  $this->ed47_c_certidaocart = ($this->ed47_c_certidaocart == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaocart"]:$this->ed47_c_certidaocart);
  if($this->ed47_c_certidaodata == ""){
   $this->ed47_c_certidaodata_dia = ($this->ed47_c_certidaodata_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"]:$this->ed47_c_certidaodata_dia);
   $this->ed47_c_certidaodata_mes = ($this->ed47_c_certidaodata_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_mes"]:$this->ed47_c_certidaodata_mes);
   $this->ed47_c_certidaodata_ano = ($this->ed47_c_certidaodata_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_ano"]:$this->ed47_c_certidaodata_ano);
   if($this->ed47_c_certidaodata_dia != ""){
    $this->ed47_c_certidaodata = $this->ed47_c_certidaodata_ano."-".$this->ed47_c_certidaodata_mes."-".$this->ed47_c_certidaodata_dia;
   }
  }
  $this->ed47_c_nis = ($this->ed47_c_nis == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_nis"]:$this->ed47_c_nis);
  $this->ed47_c_bolsafamilia = ($this->ed47_c_bolsafamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_bolsafamilia"]:$this->ed47_c_bolsafamilia);
  if($this->ed47_d_dtemissao == ""){
   $this->ed47_d_dtemissao_dia = ($this->ed47_d_dtemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"]:$this->ed47_d_dtemissao_dia);
   $this->ed47_d_dtemissao_mes = ($this->ed47_d_dtemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_mes"]:$this->ed47_d_dtemissao_mes);
   $this->ed47_d_dtemissao_ano = ($this->ed47_d_dtemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_ano"]:$this->ed47_d_dtemissao_ano);
   if($this->ed47_d_dtemissao_dia != ""){
    $this->ed47_d_dtemissao = $this->ed47_d_dtemissao_ano."-".$this->ed47_d_dtemissao_mes."-".$this->ed47_d_dtemissao_dia;
   }
  }
  if($this->ed47_d_dthabilitacao == ""){
   $this->ed47_d_dthabilitacao_dia = ($this->ed47_d_dthabilitacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"]:$this->ed47_d_dthabilitacao_dia);
   $this->ed47_d_dthabilitacao_mes = ($this->ed47_d_dthabilitacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_mes"]:$this->ed47_d_dthabilitacao_mes);
   $this->ed47_d_dthabilitacao_ano = ($this->ed47_d_dthabilitacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_ano"]:$this->ed47_d_dthabilitacao_ano);
   if($this->ed47_d_dthabilitacao_dia != ""){
    $this->ed47_d_dthabilitacao = $this->ed47_d_dthabilitacao_ano."-".$this->ed47_d_dthabilitacao_mes."-".$this->ed47_d_dthabilitacao_dia;
   }
  }
  if($this->ed47_d_dtvencimento == ""){
   $this->ed47_d_dtvencimento_dia = ($this->ed47_d_dtvencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"]:$this->ed47_d_dtvencimento_dia);
   $this->ed47_d_dtvencimento_mes = ($this->ed47_d_dtvencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_mes"]:$this->ed47_d_dtvencimento_mes);
   $this->ed47_d_dtvencimento_ano = ($this->ed47_d_dtvencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_ano"]:$this->ed47_d_dtvencimento_ano);
   if($this->ed47_d_dtvencimento_dia != ""){
    $this->ed47_d_dtvencimento = $this->ed47_d_dtvencimento_ano."-".$this->ed47_d_dtvencimento_mes."-".$this->ed47_d_dtvencimento_dia;
   }
  }
  if($this->ed47_d_nasc == ""){
   $this->ed47_d_nasc_dia = ($this->ed47_d_nasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"]:$this->ed47_d_nasc_dia);
   $this->ed47_d_nasc_mes = ($this->ed47_d_nasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_mes"]:$this->ed47_d_nasc_mes);
   $this->ed47_d_nasc_ano = ($this->ed47_d_nasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_ano"]:$this->ed47_d_nasc_ano);
   if($this->ed47_d_nasc_dia != ""){
    $this->ed47_d_nasc = $this->ed47_d_nasc_ano."-".$this->ed47_d_nasc_mes."-".$this->ed47_d_nasc_dia;
   }
  }
  $this->ed47_i_estciv = ($this->ed47_i_estciv == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_estciv"]:$this->ed47_i_estciv);
  $this->ed47_i_nacion = ($this->ed47_i_nacion == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_nacion"]:$this->ed47_i_nacion);
  $this->ed47_v_categoria = ($this->ed47_v_categoria == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_categoria"]:$this->ed47_v_categoria);
  $this->ed47_v_cnh = ($this->ed47_v_cnh == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cnh"]:$this->ed47_v_cnh);
  $this->ed47_v_contato = ($this->ed47_v_contato == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_contato"]:$this->ed47_v_contato);
  $this->ed47_v_cpf = ($this->ed47_v_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_cpf"]:$this->ed47_v_cpf);
  $this->ed47_v_email = ($this->ed47_v_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_email"]:$this->ed47_v_email);
  $this->ed47_v_fax = ($this->ed47_v_fax == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_fax"]:$this->ed47_v_fax);
  $this->ed47_v_mae = ($this->ed47_v_mae == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_mae"]:$this->ed47_v_mae);
  $this->ed47_v_pai = ($this->ed47_v_pai == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_pai"]:$this->ed47_v_pai);
  $this->ed47_v_profis = ($this->ed47_v_profis == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_profis"]:$this->ed47_v_profis);
  $this->ed47_v_sexo = ($this->ed47_v_sexo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_sexo"]:$this->ed47_v_sexo);
  $this->ed47_v_telcel = ($this->ed47_v_telcel == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_telcel"]:$this->ed47_v_telcel);
  $this->ed47_c_codigoinep = ($this->ed47_c_codigoinep == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_codigoinep"]:$this->ed47_c_codigoinep);
  $this->ed47_i_pais = ($this->ed47_i_pais == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_pais"]:$this->ed47_i_pais);
  if($this->ed47_d_identdtexp == ""){
   $this->ed47_d_identdtexp_dia = ($this->ed47_d_identdtexp_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"]:$this->ed47_d_identdtexp_dia);
   $this->ed47_d_identdtexp_mes = ($this->ed47_d_identdtexp_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_mes"]:$this->ed47_d_identdtexp_mes);
   $this->ed47_d_identdtexp_ano = ($this->ed47_d_identdtexp_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_ano"]:$this->ed47_d_identdtexp_ano);
   if($this->ed47_d_identdtexp_dia != ""){
    $this->ed47_d_identdtexp = $this->ed47_d_identdtexp_ano."-".$this->ed47_d_identdtexp_mes."-".$this->ed47_d_identdtexp_dia;
   }
  }
  $this->ed47_v_identcompl = ($this->ed47_v_identcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_v_identcompl"]:$this->ed47_v_identcompl);
  $this->ed47_c_passaporte = ($this->ed47_c_passaporte == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_c_passaporte"]:$this->ed47_c_passaporte);
  $this->ed47_i_transpublico = ($this->ed47_i_transpublico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_transpublico"]:$this->ed47_i_transpublico);
  $this->ed47_i_filiacao = ($this->ed47_i_filiacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_filiacao"]:$this->ed47_i_filiacao);
  $this->ed47_i_censoufend = ($this->ed47_i_censoufend == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufend"]:$this->ed47_i_censoufend);
  $this->ed47_i_censomunicend = ($this->ed47_i_censomunicend == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicend"]:$this->ed47_i_censomunicend);
  $this->ed47_i_censoorgemissrg = ($this->ed47_i_censoorgemissrg == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoorgemissrg"]:$this->ed47_i_censoorgemissrg);
  $this->ed47_i_censoufident = ($this->ed47_i_censoufident == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufident"]:$this->ed47_i_censoufident);
  $this->ed47_i_censoufcert = ($this->ed47_i_censoufcert == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufcert"]:$this->ed47_i_censoufcert);
  $this->ed47_i_censomuniccert = ($this->ed47_i_censomuniccert == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censomuniccert"]:$this->ed47_i_censomuniccert);
  $this->ed47_i_censoufnat = ($this->ed47_i_censoufnat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufnat"]:$this->ed47_i_censoufnat);
  $this->ed47_i_censomunicnat = ($this->ed47_i_censomunicnat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicnat"]:$this->ed47_i_censomunicnat);
 }
 // funcao para alteracao
 function logalterar ($ed47_i_codigo=null,$q_modulo){
  $this->atualizacamposlog();
  $n_registros = 0;
  $sql_ant = " SELECT * FROM aluno WHERE ed47_i_codigo = ".$this->ed47_i_codigo;
  $result_ant = db_query($sql_ant);
  if(trim($this->ed47_v_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_nome"])){
   if(trim($this->ed47_v_nome) != trim(pg_result($result_ant,0,'ed47_v_nome')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_nome";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_nome'));
    $campo_vlr_atual[] = trim($this->ed47_v_nome);
   }
  }
  if(trim($this->ed47_v_ender)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_ender"])){
   if(trim($this->ed47_v_ender) != trim(pg_result($result_ant,0,'ed47_v_ender')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_ender";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_ender'));
    $campo_vlr_atual[] = trim($this->ed47_v_ender);
   }
  }
  if(trim($this->ed47_c_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_numero"])){
   if(trim($this->ed47_c_numero) != trim(pg_result($result_ant,0,'ed47_c_numero')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_numero";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_numero'));
    $campo_vlr_atual[] = trim($this->ed47_c_numero);
   }
  }
  if(trim($this->ed47_v_compl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_compl"])){
   if(trim($this->ed47_v_compl) != trim(pg_result($result_ant,0,'ed47_v_compl')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_compl";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_compl'));
    $campo_vlr_atual[] = trim($this->ed47_v_compl);
   }
  }
  if(trim($this->ed47_v_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_bairro"])){
   if(trim($this->ed47_v_bairro) != trim(pg_result($result_ant,0,'ed47_v_bairro')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_bairro";
    $campo_vlr_ant[] = pg_escape_string(trim(pg_result($result_ant,0,'ed47_v_bairro')));
    $campo_vlr_atual[] = trim($this->ed47_v_bairro);
   }
  }
  if(trim($this->ed47_v_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cep"])){
   if(trim($this->ed47_v_cep) != trim(pg_result($result_ant,0,'ed47_v_cep')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_cep";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_cep'));
    $campo_vlr_atual[] = trim($this->ed47_v_cep);
   }
  }
  if(trim($this->ed47_c_raca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_raca"])){
   $array_raca = array(''=>'','NÃO DECLARADA'=>'NÃO DECLARADA','BRANCA'=>'BRANCA','PRETA'=>'PRETA','PARDA'=>'PARDA','AMARELA'=>'AMARELA','INDÍGENA'=>'INDÍGENA');
   if(trim($this->ed47_c_raca) != trim(pg_result($result_ant,0,'ed47_c_raca')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_raca";
    $campo_vlr_ant[] = $array_raca[trim(pg_result($result_ant,0,'ed47_c_raca'))];
    $campo_vlr_atual[] = $array_raca[trim($this->ed47_c_raca)];
   }
  }
  if(trim($this->ed47_v_cxpostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cxpostal"])){
   if(trim($this->ed47_v_cxpostal) != trim(pg_result($result_ant,0,'ed47_v_cxpostal')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_cxpostal";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_cxpostal'));
    $campo_vlr_atual[] = trim($this->ed47_v_cxpostal);
   }
  }
  if(trim($this->ed47_v_telef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_telef"])){
   if(trim($this->ed47_v_telef) != trim(pg_result($result_ant,0,'ed47_v_telef')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_telef";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_telef'));
    $campo_vlr_atual[] = trim($this->ed47_v_telef);
   }
  }
  if(trim($this->ed47_v_ident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_ident"])){
   if(trim($this->ed47_v_ident) != trim(pg_result($result_ant,0,'ed47_v_ident')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_ident";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_ident'));
    $campo_vlr_atual[] = trim($this->ed47_v_ident);
   }
  }
  if(trim($this->ed47_c_nomeresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_nomeresp"])){
   if(trim($this->ed47_c_nomeresp) != trim(pg_result($result_ant,0,'ed47_c_nomeresp')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_nomeresp";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_nomeresp'));
    $campo_vlr_atual[] = trim($this->ed47_c_nomeresp);
   }
  }
  if(trim($this->ed47_c_emailresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_emailresp"])){
   if(trim($this->ed47_c_emailresp) != trim(pg_result($result_ant,0,'ed47_c_emailresp')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_emailresp";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_emailresp'));
    $campo_vlr_atual[] = trim($this->ed47_c_emailresp);
   }
  }
  if(trim($this->ed47_c_atenddifer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_atenddifer"])){
   $array_recebe = array(""=>"","1"=>"EM HOSPITAL","2"=>"EM DOMICÍLIO","3"=>"NÃO RECEBE");
   if(trim($this->ed47_c_atenddifer) != trim(pg_result($result_ant,0,'ed47_c_atenddifer')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_atenddifer";
    $campo_vlr_ant[] = $array_recebe[trim(pg_result($result_ant,0,'ed47_c_atenddifer'))];
    $campo_vlr_atual[] = $array_recebe[trim($this->ed47_c_atenddifer)];
   }
  }
  if(trim($this->ed47_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_t_obs"])){
   if(trim($this->ed47_t_obs) != trim(pg_result($result_ant,0,'ed47_t_obs')) ){
    $n_registros++;
    $nome_campo[] = "ed47_t_obs";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_t_obs'));
    $campo_vlr_atual[] = trim($this->ed47_t_obs);
   }
  }
  if(trim($this->ed47_c_transporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_transporte"])){
   $array_transporte = array(''=>'','1'=>'ESTADUAL','2'=>'MUNICIPAL');
   if(trim($this->ed47_c_transporte) != trim(pg_result($result_ant,0,'ed47_c_transporte')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_transporte";
    $campo_vlr_ant[] = $array_transporte[trim(pg_result($result_ant,0,'ed47_c_transporte'))];
    $campo_vlr_atual[] = $array_transporte[trim($this->ed47_c_transporte)];
   }
  }
  if(trim($this->ed47_c_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_zona"])){
   if(trim($this->ed47_c_zona) != trim(pg_result($result_ant,0,'ed47_c_zona')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_zona";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_zona'));
    $campo_vlr_atual[] = trim($this->ed47_c_zona);
   }
  }
  if(trim($this->ed47_c_certidaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaotipo"])){
   $array_certtipo = array(''=>'','N'=>'NASCIMENTO','C'=>'CASAMENTO');
   if(trim($this->ed47_c_certidaotipo) != trim(pg_result($result_ant,0,'ed47_c_certidaotipo')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_certidaotipo";
    $campo_vlr_ant[] = $array_certtipo[trim(pg_result($result_ant,0,'ed47_c_certidaotipo'))];
    $campo_vlr_atual[] = $array_certtipo[trim($this->ed47_c_certidaotipo)];
   }
  }
  if(trim($this->ed47_c_certidaonum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaonum"])){
   if(trim($this->ed47_c_certidaonum) != trim(pg_result($result_ant,0,'ed47_c_certidaonum')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_certidaonum";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_certidaonum'));
    $campo_vlr_atual[] = trim($this->ed47_c_certidaonum);
   }
  }
  if(trim($this->ed47_c_certidaolivro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaolivro"])){
   if(trim($this->ed47_c_certidaolivro) != trim(pg_result($result_ant,0,'ed47_c_certidaolivro')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_certidaolivro";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_certidaolivro'));
    $campo_vlr_atual[] = trim($this->ed47_c_certidaolivro);
   }
  }
  if(trim($this->ed47_c_certidaofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaofolha"])){
   if(trim($this->ed47_c_certidaofolha) != trim(pg_result($result_ant,0,'ed47_c_certidaofolha')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_certidaofolha";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_certidaofolha'));
    $campo_vlr_atual[] = trim($this->ed47_c_certidaofolha);
   }
  }
  if(trim($this->ed47_c_certidaocart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaocart"])){
   if(trim($this->ed47_c_certidaocart) != trim(pg_result($result_ant,0,'ed47_c_certidaocart')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_certidaocart";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_certidaocart'));
    $campo_vlr_atual[] = trim($this->ed47_c_certidaocart);
   }
  }
  if(trim($this->ed47_c_certidaodata)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_c_certidaodata_dia"] !="") ){
   if(trim($this->ed47_c_certidaodata) != trim(pg_result($result_ant,0,'ed47_c_certidaodata')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_certidaodata";
    $campo_vlr_ant[] = db_formatar(trim(pg_result($result_ant,0,'ed47_c_certidaodata')),'d');
    $campo_vlr_atual[] = db_formatar(trim($this->ed47_c_certidaodata),'d');
   }
  }
  if(trim($this->ed47_c_nis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_nis"])){
   if(trim($this->ed47_c_nis) != trim(pg_result($result_ant,0,'ed47_c_nis')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_nis";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_nis'));
    $campo_vlr_atual[] = trim($this->ed47_c_nis);
   }
  }
  if(trim($this->ed47_c_bolsafamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_bolsafamilia"])){
   $array_bolsa = array(''=>'','N'=>'NÃO','S'=>'SIM');
   if(trim($this->ed47_c_bolsafamilia) != trim(pg_result($result_ant,0,'ed47_c_bolsafamilia')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_bolsafamilia";
    $campo_vlr_ant[] = $array_bolsa[trim(pg_result($result_ant,0,'ed47_c_bolsafamilia'))];
    $campo_vlr_atual[] = $array_bolsa[trim($this->ed47_c_bolsafamilia)];
   }
  }
  if(trim($this->ed47_d_dtemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtemissao_dia"] !="") ){
   if(trim($this->ed47_d_dtemissao) != trim(pg_result($result_ant,0,'ed47_d_dtemissao')) ){
    $n_registros++;
    $nome_campo[] = "ed47_d_dtemissao";
    $campo_vlr_ant[] = db_formatar(trim(pg_result($result_ant,0,'ed47_d_dtemissao')),'d');
    $campo_vlr_atual[] = db_formatar(trim($this->ed47_d_dtemissao),'d');
   }
  }
  if(trim($this->ed47_d_dthabilitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_dthabilitacao_dia"] !="") ){
   if(trim($this->ed47_d_dthabilitacao) != trim(pg_result($result_ant,0,'ed47_d_dthabilitacao')) ){
    $n_registros++;
    $nome_campo[] = "ed47_d_dthabilitacao";
    $campo_vlr_ant[] = db_formatar(trim(pg_result($result_ant,0,'ed47_d_dthabilitacao')),'d');
    $campo_vlr_atual[] = db_formatar(trim($this->ed47_d_dthabilitacao),'d');
   }
  }
  if(trim($this->ed47_d_dtvencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_dtvencimento_dia"] !="") ){
   if(trim($this->ed47_d_dtvencimento) != trim(pg_result($result_ant,0,'ed47_d_dtvencimento')) ){
    $n_registros++;
    $nome_campo[] = "ed47_d_dtvencimento";
    $campo_vlr_ant[] = db_formatar(trim(pg_result($result_ant,0,'ed47_d_dtvencimento')),'d');
    $campo_vlr_atual[] = db_formatar(trim($this->ed47_d_dtvencimento),'d');
   }
  }
  if(trim($this->ed47_d_nasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_nasc_dia"] !="") ){
   if(trim($this->ed47_d_nasc) != trim(pg_result($result_ant,0,'ed47_d_nasc')) ){
    $n_registros++;
    $nome_campo[] = "ed47_d_nasc";
    $campo_vlr_ant[] = db_formatar(trim(pg_result($result_ant,0,'ed47_d_nasc')),'d');
    $campo_vlr_atual[] = db_formatar(trim($this->ed47_d_nasc),'d');
   }
  }
  if(trim($this->ed47_i_estciv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_estciv"])){
   $array_estciv = array("0"=>"","1"=>"SOLTEIRO","2"=>"CASADO","3"=>"VIUVO","4"=>"DIVORCIADO");
   if(trim($this->ed47_i_estciv) != trim(pg_result($result_ant,0,'ed47_i_estciv')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_estciv";
    $campo_vlr_ant[] = $array_estciv[trim(pg_result($result_ant,0,'ed47_i_estciv'))];
    $campo_vlr_atual[] = $array_estciv[trim($this->ed47_i_estciv)];
   }
  }
  if(trim($this->ed47_i_nacion)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_nacion"])){
   $array_nacion = array("0"=>"","1"=>"BRASILEIRA","2"=>"BRASILEIRA NO EXTERIOR OU NATURALIZADO","3"=>"ESTRANGEIRA");
   if(trim($this->ed47_i_nacion) != trim(pg_result($result_ant,0,'ed47_i_nacion')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_nacion";
    $campo_vlr_ant[] = $array_nacion[trim(pg_result($result_ant,0,'ed47_i_nacion'))];
    $campo_vlr_atual[] = $array_nacion[trim($this->ed47_i_nacion)];
   }
  }
  if(trim($this->ed47_v_categoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_categoria"])){
   if(trim($this->ed47_v_categoria) != trim(pg_result($result_ant,0,'ed47_v_categoria')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_categoria";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_categoria'));
    $campo_vlr_atual[] = trim($this->ed47_v_categoria);
   }
  }
  if(trim($this->ed47_v_cnh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cnh"])){
   if(trim($this->ed47_v_cnh) != trim(pg_result($result_ant,0,'ed47_v_cnh')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_cnh";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_cnh'));
    $campo_vlr_atual[] = trim($this->ed47_v_cnh);
   }
  }
  if(trim($this->ed47_v_contato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_contato"])){
   if(trim($this->ed47_v_contato) != trim(pg_result($result_ant,0,'ed47_v_contato')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_contato";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_contato'));
    $campo_vlr_atual[] = trim($this->ed47_v_contato);
   }
  }
  if(trim($this->ed47_v_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_cpf"])){
   if(trim($this->ed47_v_cpf) != trim(pg_result($result_ant,0,'ed47_v_cpf')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_cpf";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_cpf'));
    $campo_vlr_atual[] = trim($this->ed47_v_cpf);
   }
  }
  if(trim($this->ed47_v_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_email"])){
   if(trim($this->ed47_v_email) != trim(pg_result($result_ant,0,'ed47_v_email')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_email";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_email'));
    $campo_vlr_atual[] = trim($this->ed47_v_email);
   }
  }
  if(trim($this->ed47_v_fax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_fax"])){
   if(trim($this->ed47_v_fax) != trim(pg_result($result_ant,0,'ed47_v_fax')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_fax";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_fax'));
    $campo_vlr_atual[] = trim($this->ed47_v_fax);
   }
  }
  if(trim($this->ed47_v_mae)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_mae"])){
   if(trim($this->ed47_v_mae) != trim(pg_result($result_ant,0,'ed47_v_mae')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_mae";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_mae'));
    $campo_vlr_atual[] = trim($this->ed47_v_mae);
   }
  }
  if(trim($this->ed47_v_pai)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_pai"])){
   if(trim($this->ed47_v_pai) != trim(pg_result($result_ant,0,'ed47_v_pai')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_pai";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_pai'));
    $campo_vlr_atual[] = trim($this->ed47_v_pai);
   }
  }
  if(trim($this->ed47_v_profis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_profis"])){
   if(trim($this->ed47_v_profis) != trim(pg_result($result_ant,0,'ed47_v_profis')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_profis";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_profis'));
    $campo_vlr_atual[] = trim($this->ed47_v_profis);
   }
  }
  if(trim($this->ed47_v_sexo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_sexo"])){
   $array_sexo = array(""=>"","M"=>"MASCULINO","F"=>"FEMININO");
   if(trim($this->ed47_v_sexo) != trim(pg_result($result_ant,0,'ed47_v_sexo')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_sexo";
    $campo_vlr_ant[] = $array_sexo[trim(pg_result($result_ant,0,'ed47_v_sexo'))];
    $campo_vlr_atual[] = $array_sexo[trim($this->ed47_v_sexo)];
   }
  }
  if(trim($this->ed47_v_telcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_telcel"])){
   if(trim($this->ed47_v_telcel) != trim(pg_result($result_ant,0,'ed47_v_telcel')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_telcel";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_telcel'));
    $campo_vlr_atual[] = trim($this->ed47_v_telcel);
   }
  }
  if(trim($this->ed47_c_codigoinep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_codigoinep"])){
   if(trim($this->ed47_c_codigoinep) != trim(pg_result($result_ant,0,'ed47_c_codigoinep')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_codigoinep";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_codigoinep'));
    $campo_vlr_atual[] = trim($this->ed47_c_codigoinep);
   }
  }
  if(trim($this->ed47_i_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_pais"])){
   if(trim($this->ed47_i_pais) != trim(pg_result($result_ant,0,'ed47_i_pais')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_pais";
    $campo_vlr_ant[] = $this->Pais(trim(pg_result($result_ant,0,'ed47_i_pais')));
    $campo_vlr_atual[] = $this->Pais(trim($this->ed47_i_pais));
   }
  }
  if(trim($this->ed47_d_identdtexp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed47_d_identdtexp_dia"] !="") ){
   if(trim($this->ed47_d_identdtexp) != trim(pg_result($result_ant,0,'ed47_d_identdtexp')) ){
    $n_registros++;
    $nome_campo[] = "ed47_d_identdtexp";
    $campo_vlr_ant[] = db_formatar(trim(pg_result($result_ant,0,'ed47_d_identdtexp')),'d');
    $campo_vlr_atual[] = db_formatar(trim($this->ed47_d_identdtexp),'d');
   }
  }
  if(trim($this->ed47_v_identcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_v_identcompl"])){
   if(trim($this->ed47_v_identcompl) != trim(pg_result($result_ant,0,'ed47_v_identcompl')) ){
    $n_registros++;
    $nome_campo[] = "ed47_v_identcompl";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_v_identcompl'));
    $campo_vlr_atual[] = trim($this->ed47_v_identcompl);
   }
  }
  if(trim($this->ed47_c_passaporte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_c_passaporte"])){
   if(trim($this->ed47_c_passaporte) != trim(pg_result($result_ant,0,'ed47_c_passaporte')) ){
    $n_registros++;
    $nome_campo[] = "ed47_c_passaporte";
    $campo_vlr_ant[] = trim(pg_result($result_ant,0,'ed47_c_passaporte'));
    $campo_vlr_atual[] = trim($this->ed47_c_passaporte);
   }
  }
  if(trim($this->ed47_i_transpublico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_transpublico"])){
   $array_trans = array("0"=>"NÃO UTILIZA","1"=>"UTILIZA");
   if(trim($this->ed47_i_transpublico) != trim(pg_result($result_ant,0,'ed47_i_transpublico')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_transpublico";
    $campo_vlr_ant[] = $array_trans[trim(pg_result($result_ant,0,'ed47_i_transpublico'))];
    $campo_vlr_atual[] = $array_trans[trim($this->ed47_i_transpublico)];
   }
  }
  if(trim($this->ed47_i_filiacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_filiacao"])){
   $array_filiacao = array("0"=>"NÃO DECLARADO / IGNORADO","1"=>"PAI E/OU MÃE");
   if(trim($this->ed47_i_filiacao) != trim(pg_result($result_ant,0,'ed47_i_filiacao')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_filiacao";
    $campo_vlr_ant[] = $array_filiacao[trim(pg_result($result_ant,0,'ed47_i_filiacao'))];
    $campo_vlr_atual[] = $array_filiacao[trim($this->ed47_i_filiacao)];
   }
  }
  if(trim($this->ed47_i_censoufend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufend"])){
   if(trim($this->ed47_i_censoufend) != trim(pg_result($result_ant,0,'ed47_i_censoufend')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censoufend";
    $campo_vlr_ant[] = $this->SiglaUF(trim(pg_result($result_ant,0,'ed47_i_censoufend')));
    $campo_vlr_atual[] = $this->SiglaUF(trim($this->ed47_i_censoufend));
   }
  }
  if(trim($this->ed47_i_censomunicend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicend"])){
   if(trim($this->ed47_i_censomunicend) != trim(pg_result($result_ant,0,'ed47_i_censomunicend')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censomunicend";
    $campo_vlr_ant[] = $this->Municipio(trim(pg_result($result_ant,0,'ed47_i_censomunicend')));
    $campo_vlr_atual[] = $this->Municipio(trim($this->ed47_i_censomunicend));
   }
  }
  if(trim($this->ed47_i_censoorgemissrg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoorgemissrg"])){
   if(trim($this->ed47_i_censoorgemissrg) != trim(pg_result($result_ant,0,'ed47_i_censoorgemissrg')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censoorgemissrg";
    $campo_vlr_ant[] = $this->OrgaoRG(trim(pg_result($result_ant,0,'ed47_i_censoorgemissrg')));
    $campo_vlr_atual[] = $this->OrgaoRG(trim($this->ed47_i_censoorgemissrg));
   }
  }
  if(trim($this->ed47_i_censoufident)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufident"])){
   if(trim($this->ed47_i_censoufident) != trim(pg_result($result_ant,0,'ed47_i_censoufident')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censoufident";
    $campo_vlr_ant[] = $this->SiglaUF(trim(pg_result($result_ant,0,'ed47_i_censoufident')));
    $campo_vlr_atual[] = $this->SiglaUF(trim($this->ed47_i_censoufident));
   }
  }
  if(trim($this->ed47_i_censoufcert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufcert"])){
   if(trim($this->ed47_i_censoufcert) != trim(pg_result($result_ant,0,'ed47_i_censoufcert')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censoufcert";
    $campo_vlr_ant[] = $this->SiglaUF(trim(pg_result($result_ant,0,'ed47_i_censoufcert')));
    $campo_vlr_atual[] = $this->SiglaUF(trim($this->ed47_i_censoufcert));
   }
  }
  if(trim($this->ed47_i_censomuniccert)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomuniccert"])){
   if(trim($this->ed47_i_censomuniccert) != trim(pg_result($result_ant,0,'ed47_i_censomuniccert')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censomuniccert";
    $campo_vlr_ant[] = $this->Municipio(trim(pg_result($result_ant,0,'ed47_i_censomuniccert')));
    $campo_vlr_atual[] = $this->Municipio(trim($this->ed47_i_censomuniccert));
   }
  }
  if(trim($this->ed47_i_censoufnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censoufnat"])){
   if(trim($this->ed47_i_censoufnat) != trim(pg_result($result_ant,0,'ed47_i_censoufnat')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censoufnat";
    $campo_vlr_ant[] = $this->SiglaUF(trim(pg_result($result_ant,0,'ed47_i_censoufnat')));
    $campo_vlr_atual[] = $this->SiglaUF(trim($this->ed47_i_censoufnat));
   }
  }
  if(trim($this->ed47_i_censomunicnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed47_i_censomunicnat"])){
   if(trim($this->ed47_i_censomunicnat) != trim(pg_result($result_ant,0,'ed47_i_censomunicnat')) ){
    $n_registros++;
    $nome_campo[] = "ed47_i_censomunicnat";
    $campo_vlr_ant[] = $this->Municipio(trim(pg_result($result_ant,0,'ed47_i_censomunicnat')));
    $campo_vlr_atual[] = $this->Municipio(trim($this->ed47_i_censomunicnat));
   }
  }
  if($n_registros>0){
   $result_pk = db_query("select nextval('alunoalt_ed275_i_codigo_seq')");
   $ed275_i_codigo_seq = pg_result($result_pk,0,0);
   $sql = "insert into alunoalt(ed275_i_codigo
                               ,ed275_i_usuario
                               ,ed275_i_aluno
                               ,ed275_i_modulo
                               ,ed275_i_data)
                         values
                               ($ed275_i_codigo_seq
                               ,".db_getsession('DB_id_usuario')."
                               ,$this->ed47_i_codigo
                               ,'$q_modulo'
                               ,".time().")";
   $result = db_query($sql);
   for($ww=0;$ww<$n_registros;$ww++){
    //db_msgbox(">>>".$nome_campo[$ww]."<<<");
    $result_pk = db_query("select nextval('alunoaltcampos_ed276_i_codigo_seq')");
    $ed276_i_codigo_seq = pg_result($result_pk,0,0);
    $sql1 = "insert into alunoaltcampos(ed276_i_codigo
                                       ,ed276_i_alunoalt
                                       ,ed276_c_campo
                                       ,ed276_c_contant
                                       ,ed276_c_contatual)
                                 values
                                       ($ed276_i_codigo_seq
                                       ,$ed275_i_codigo_seq
                                       ,'$nome_campo[$ww]'
                                       ,'$campo_vlr_ant[$ww]'
                                       ,'$campo_vlr_atual[$ww]')";
    $result1 = db_query($sql1);
   }
  }
 }
}
?>