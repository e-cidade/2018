<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

//MODULO: issqn
//CLASSE DA ENTIDADE issarquivoretencao
class cl_issarquivoretencao {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $q90_sequencial = 0;
   var $q90_instit = 0;
   var $q90_data_dia = null;
   var $q90_data_mes = null;
   var $q90_data_ano = null;
   var $q90_data = null;
   var $q90_numeroremessa = 0;
   var $q90_versao = 0;
   var $q90_quantidaderegistro = 0;
   var $q90_valortotal = 0;
   var $q90_codigobanco = 0;
   var $q90_oidarquivo = 0;
   var $q90_nomearquivo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q90_sequencial = int4 = Código Arquivo de Retenção
                 q90_instit = int4 = Instituição
                 q90_data = date = Data do Arquivo
                 q90_numeroremessa = int4 = Número da Remessa
                 q90_versao = int4 = Versão do Arquivo
                 q90_quantidaderegistro = int4 = Quantidade de Registros
                 q90_valortotal = float4 = Valor Total
                 q90_codigobanco = int4 = Código do Banco
                 q90_oidarquivo = oid = Arquivo
                 q90_nomearquivo = varchar(50) = Nome do Arquivo
                 ";
   //funcao construtor da classe
   function cl_issarquivoretencao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issarquivoretencao");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->q90_sequencial = ($this->q90_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_sequencial"]:$this->q90_sequencial);
       $this->q90_instit = ($this->q90_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_instit"]:$this->q90_instit);
       if($this->q90_data == ""){
         $this->q90_data_dia = ($this->q90_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_data_dia"]:$this->q90_data_dia);
         $this->q90_data_mes = ($this->q90_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_data_mes"]:$this->q90_data_mes);
         $this->q90_data_ano = ($this->q90_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_data_ano"]:$this->q90_data_ano);
         if($this->q90_data_dia != ""){
            $this->q90_data = $this->q90_data_ano."-".$this->q90_data_mes."-".$this->q90_data_dia;
         }
       }
       $this->q90_numeroremessa = ($this->q90_numeroremessa == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_numeroremessa"]:$this->q90_numeroremessa);
       $this->q90_versao = ($this->q90_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_versao"]:$this->q90_versao);
       $this->q90_quantidaderegistro = ($this->q90_quantidaderegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_quantidaderegistro"]:$this->q90_quantidaderegistro);
       $this->q90_valortotal = ($this->q90_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_valortotal"]:$this->q90_valortotal);
       $this->q90_codigobanco = ($this->q90_codigobanco == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_codigobanco"]:$this->q90_codigobanco);
       $this->q90_oidarquivo = ($this->q90_oidarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_oidarquivo"]:$this->q90_oidarquivo);
       $this->q90_nomearquivo = ($this->q90_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_nomearquivo"]:$this->q90_nomearquivo);
     }else{
       $this->q90_sequencial = ($this->q90_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q90_sequencial"]:$this->q90_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q90_sequencial){
      $this->atualizacampos();
     if($this->q90_instit == null ){
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "q90_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_data == null ){
       $this->erro_sql = " Campo Data do Arquivo não informado.";
       $this->erro_campo = "q90_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_numeroremessa == null ){
       $this->erro_sql = " Campo Número da Remessa não informado.";
       $this->erro_campo = "q90_numeroremessa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_versao == null ){
       $this->erro_sql = " Campo Versão do Arquivo não informado.";
       $this->erro_campo = "q90_versao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_quantidaderegistro == null ){
       $this->erro_sql = " Campo Quantidade de Registros não informado.";
       $this->erro_campo = "q90_quantidaderegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_valortotal == null ){
       $this->erro_sql = " Campo Valor Total não informado.";
       $this->erro_campo = "q90_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_codigobanco == null ){
       $this->erro_sql = " Campo Código do Banco não informado.";
       $this->erro_campo = "q90_codigobanco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_oidarquivo == null ){
       $this->erro_sql = " Campo Arquivo não informado.";
       $this->erro_campo = "q90_oidarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q90_nomearquivo == null ){
       $this->erro_sql = " Campo Nome do Arquivo não informado.";
       $this->erro_campo = "q90_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q90_sequencial == "" || $q90_sequencial == null ){
       $result = db_query("select nextval('issarquivoretencao_q90_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issarquivoretencao_q90_sequencial_seq do campo: q90_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q90_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from issarquivoretencao_q90_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q90_sequencial)){
         $this->erro_sql = " Campo q90_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q90_sequencial = $q90_sequencial;
       }
     }
     if(($this->q90_sequencial == null) || ($this->q90_sequencial == "") ){
       $this->erro_sql = " Campo q90_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issarquivoretencao(
                                       q90_sequencial
                                      ,q90_instit
                                      ,q90_data
                                      ,q90_numeroremessa
                                      ,q90_versao
                                      ,q90_quantidaderegistro
                                      ,q90_valortotal
                                      ,q90_codigobanco
                                      ,q90_oidarquivo
                                      ,q90_nomearquivo
                       )
                values (
                                $this->q90_sequencial
                               ,$this->q90_instit
                               ,".($this->q90_data == "null" || $this->q90_data == ""?"null":"'".$this->q90_data."'")."
                               ,$this->q90_numeroremessa
                               ,$this->q90_versao
                               ,$this->q90_quantidaderegistro
                               ,$this->q90_valortotal
                               ,$this->q90_codigobanco
                               ,$this->q90_oidarquivo
                               ,'$this->q90_nomearquivo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de Retenção ($this->q90_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de Retenção já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de Retenção ($this->q90_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q90_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q90_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21049,'$this->q90_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3791,21049,'','".AddSlashes(pg_result($resaco,0,'q90_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21054,'','".AddSlashes(pg_result($resaco,0,'q90_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21055,'','".AddSlashes(pg_result($resaco,0,'q90_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21063,'','".AddSlashes(pg_result($resaco,0,'q90_numeroremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21062,'','".AddSlashes(pg_result($resaco,0,'q90_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21064,'','".AddSlashes(pg_result($resaco,0,'q90_quantidaderegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21069,'','".AddSlashes(pg_result($resaco,0,'q90_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21070,'','".AddSlashes(pg_result($resaco,0,'q90_codigobanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21075,'','".AddSlashes(pg_result($resaco,0,'q90_oidarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3791,21076,'','".AddSlashes(pg_result($resaco,0,'q90_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($q90_sequencial=null) {
      $this->atualizacampos();
     $sql = " update issarquivoretencao set ";
     $virgula = "";
     if(trim($this->q90_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_sequencial"])){
       $sql  .= $virgula." q90_sequencial = $this->q90_sequencial ";
       $virgula = ",";
       if(trim($this->q90_sequencial) == null ){
         $this->erro_sql = " Campo Código Arquivo de Retenção não informado.";
         $this->erro_campo = "q90_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_instit"])){
       $sql  .= $virgula." q90_instit = $this->q90_instit ";
       $virgula = ",";
       if(trim($this->q90_instit) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "q90_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q90_data_dia"] !="") ){
       $sql  .= $virgula." q90_data = '$this->q90_data' ";
       $virgula = ",";
       if(trim($this->q90_data) == null ){
         $this->erro_sql = " Campo Data do Arquivo não informado.";
         $this->erro_campo = "q90_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["q90_data_dia"])){
         $sql  .= $virgula." q90_data = null ";
         $virgula = ",";
         if(trim($this->q90_data) == null ){
           $this->erro_sql = " Campo Data do Arquivo não informado.";
           $this->erro_campo = "q90_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q90_numeroremessa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_numeroremessa"])){
       $sql  .= $virgula." q90_numeroremessa = $this->q90_numeroremessa ";
       $virgula = ",";
       if(trim($this->q90_numeroremessa) == null ){
         $this->erro_sql = " Campo Número da Remessa não informado.";
         $this->erro_campo = "q90_numeroremessa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_versao"])){
       $sql  .= $virgula." q90_versao = $this->q90_versao ";
       $virgula = ",";
       if(trim($this->q90_versao) == null ){
         $this->erro_sql = " Campo Versão do Arquivo não informado.";
         $this->erro_campo = "q90_versao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_quantidaderegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_quantidaderegistro"])){
       $sql  .= $virgula." q90_quantidaderegistro = $this->q90_quantidaderegistro ";
       $virgula = ",";
       if(trim($this->q90_quantidaderegistro) == null ){
         $this->erro_sql = " Campo Quantidade de Registros não informado.";
         $this->erro_campo = "q90_quantidaderegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_valortotal"])){
       $sql  .= $virgula." q90_valortotal = $this->q90_valortotal ";
       $virgula = ",";
       if(trim($this->q90_valortotal) == null ){
         $this->erro_sql = " Campo Valor Total não informado.";
         $this->erro_campo = "q90_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_codigobanco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_codigobanco"])){
       $sql  .= $virgula." q90_codigobanco = $this->q90_codigobanco ";
       $virgula = ",";
       if(trim($this->q90_codigobanco) == null ){
         $this->erro_sql = " Campo Código do Banco não informado.";
         $this->erro_campo = "q90_codigobanco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_oidarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_oidarquivo"])){
       $sql  .= $virgula." q90_oidarquivo = $this->q90_oidarquivo ";
       $virgula = ",";
       if(trim($this->q90_oidarquivo) == null ){
         $this->erro_sql = " Campo Arquivo não informado.";
         $this->erro_campo = "q90_oidarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q90_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q90_nomearquivo"])){
       $sql  .= $virgula." q90_nomearquivo = '$this->q90_nomearquivo' ";
       $virgula = ",";
       if(trim($this->q90_nomearquivo) == null ){
         $this->erro_sql = " Campo Nome do Arquivo não informado.";
         $this->erro_campo = "q90_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q90_sequencial!=null){
       $sql .= " q90_sequencial = $this->q90_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q90_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21049,'$this->q90_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_sequencial"]) || $this->q90_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3791,21049,'".AddSlashes(pg_result($resaco,$conresaco,'q90_sequencial'))."','$this->q90_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_instit"]) || $this->q90_instit != "")
             $resac = db_query("insert into db_acount values($acount,3791,21054,'".AddSlashes(pg_result($resaco,$conresaco,'q90_instit'))."','$this->q90_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_data"]) || $this->q90_data != "")
             $resac = db_query("insert into db_acount values($acount,3791,21055,'".AddSlashes(pg_result($resaco,$conresaco,'q90_data'))."','$this->q90_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_numeroremessa"]) || $this->q90_numeroremessa != "")
             $resac = db_query("insert into db_acount values($acount,3791,21063,'".AddSlashes(pg_result($resaco,$conresaco,'q90_numeroremessa'))."','$this->q90_numeroremessa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_versao"]) || $this->q90_versao != "")
             $resac = db_query("insert into db_acount values($acount,3791,21062,'".AddSlashes(pg_result($resaco,$conresaco,'q90_versao'))."','$this->q90_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_quantidaderegistro"]) || $this->q90_quantidaderegistro != "")
             $resac = db_query("insert into db_acount values($acount,3791,21064,'".AddSlashes(pg_result($resaco,$conresaco,'q90_quantidaderegistro'))."','$this->q90_quantidaderegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_valortotal"]) || $this->q90_valortotal != "")
             $resac = db_query("insert into db_acount values($acount,3791,21069,'".AddSlashes(pg_result($resaco,$conresaco,'q90_valortotal'))."','$this->q90_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_codigobanco"]) || $this->q90_codigobanco != "")
             $resac = db_query("insert into db_acount values($acount,3791,21070,'".AddSlashes(pg_result($resaco,$conresaco,'q90_codigobanco'))."','$this->q90_codigobanco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_oidarquivo"]) || $this->q90_oidarquivo != "")
             $resac = db_query("insert into db_acount values($acount,3791,21075,'".AddSlashes(pg_result($resaco,$conresaco,'q90_oidarquivo'))."','$this->q90_oidarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q90_nomearquivo"]) || $this->q90_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,3791,21076,'".AddSlashes(pg_result($resaco,$conresaco,'q90_nomearquivo'))."','$this->q90_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Retenção nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q90_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Retenção nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($q90_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q90_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21049,'$q90_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3791,21049,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21054,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21055,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21063,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_numeroremessa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21062,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21064,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_quantidaderegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21069,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21070,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_codigobanco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21075,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_oidarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3791,21076,'','".AddSlashes(pg_result($resaco,$iresaco,'q90_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issarquivoretencao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q90_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q90_sequencial = $q90_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Retenção nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q90_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Retenção nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q90_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:issarquivoretencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($q90_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from issarquivoretencao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q90_sequencial)) {
         $sql2 .= " where issarquivoretencao.q90_sequencial = $q90_sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
   }

    /**
     * Funçao que retorna query que busca somente os arquivos de retençao que ainda nao foram processados
     *
     * @param  int    $q90_sequencial
     * @param  string $campos
     * @param  string $ordem
     * @param  string $dbwhere
     *
     * @return string                 query
     */
    public function sql_query_nao_processado($q90_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

      $sql  = "select {$campos}";
      $sql .= "  from issarquivoretencao ";
      $sql .= "       left join issarquivoretencaodisarq on q145_issarquivoretencao = q90_sequencial";
      $sql .= " where q145_issarquivoretencao is null";
      $sql2 = "";
      if (empty($dbwhere)) {
        if (!empty($q90_sequencial)) {
          $sql2 .= " and issarquivoretencao.q90_sequencial = $q90_sequencial ";
        }
      } else if (!empty($dbwhere)) {
        $sql2 = " and $dbwhere";
      }
      $sql .= $sql2;
      if (!empty($ordem)) {
        $sql .= " order by {$ordem}";
      }
      return $sql;
    }

   // funcao do sql
   public function sql_query_file ($q90_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from issarquivoretencao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q90_sequencial)){
         $sql2 .= " where issarquivoretencao.q90_sequencial = $q90_sequencial ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}