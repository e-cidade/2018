<?php
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

//MODULO: orcamento
//CLASSE DA ENTIDADE cronogramaperspectiva
class cl_cronogramaperspectiva {
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
   var $o124_sequencial = 0;
   var $o124_ppaversao = 0;
   var $o124_descricao = null;
   var $o124_situacao = 0;
   var $o124_idusuario = 0;
   var $o124_datacriacao_dia = null;
   var $o124_datacriacao_mes = null;
   var $o124_datacriacao_ano = null;
   var $o124_datacriacao = null;
   var $o124_ano = 0;
   var $o124_tipo = 1;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 o124_sequencial = int4 = Código Sequencial
                 o124_ppaversao = int4 = Perspectiva do PPA
                 o124_descricao = varchar(100) = Descrição da Perspectiva
                 o124_situacao = int4 = Situação
                 o124_idusuario = int4 = Código do Usuário
                 o124_datacriacao = date = Data de Criação
                 o124_ano = int4 = Ano
                 o124_tipo = int4 = Tipo da Perspectiva
                 ";
   //funcao construtor da classe
   function cl_cronogramaperspectiva() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cronogramaperspectiva");
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
       $this->o124_sequencial = ($this->o124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_sequencial"]:$this->o124_sequencial);
       $this->o124_ppaversao = ($this->o124_ppaversao == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_ppaversao"]:$this->o124_ppaversao);
       $this->o124_descricao = ($this->o124_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_descricao"]:$this->o124_descricao);
       $this->o124_situacao = ($this->o124_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_situacao"]:$this->o124_situacao);
       $this->o124_idusuario = ($this->o124_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_idusuario"]:$this->o124_idusuario);
       if($this->o124_datacriacao == ""){
         $this->o124_datacriacao_dia = ($this->o124_datacriacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_datacriacao_dia"]:$this->o124_datacriacao_dia);
         $this->o124_datacriacao_mes = ($this->o124_datacriacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_datacriacao_mes"]:$this->o124_datacriacao_mes);
         $this->o124_datacriacao_ano = ($this->o124_datacriacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_datacriacao_ano"]:$this->o124_datacriacao_ano);
         if($this->o124_datacriacao_dia != ""){
            $this->o124_datacriacao = $this->o124_datacriacao_ano."-".$this->o124_datacriacao_mes."-".$this->o124_datacriacao_dia;
         }
       }
       $this->o124_ano = ($this->o124_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_ano"]:$this->o124_ano);
       $this->o124_tipo = ($this->o124_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_tipo"]:$this->o124_tipo);
     }else{
       $this->o124_sequencial = ($this->o124_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o124_sequencial"]:$this->o124_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($o124_sequencial){
      $this->atualizacampos();
     if($this->o124_ppaversao == null ){
       $this->erro_sql = " Campo Perspectiva do PPA não informado.";
       $this->erro_campo = "o124_ppaversao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o124_descricao == null ){
       $this->erro_sql = " Campo Descrição da Perspectiva não informado.";
       $this->erro_campo = "o124_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o124_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "o124_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o124_idusuario == null ){
       $this->erro_sql = " Campo Código do Usuário não informado.";
       $this->erro_campo = "o124_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o124_datacriacao == null ){
       $this->erro_sql = " Campo Data de Criação não informado.";
       $this->erro_campo = "o124_datacriacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o124_ano == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "o124_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o124_tipo == null ){
       $this->erro_sql = " Campo Tipo da Perspectiva não informado.";
       $this->erro_campo = "o124_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o124_sequencial == "" || $o124_sequencial == null ){
       $result = db_query("select nextval('cronogramaperspectiva_o124_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cronogramaperspectiva_o124_sequencial_seq do campo: o124_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->o124_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from cronogramaperspectiva_o124_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o124_sequencial)){
         $this->erro_sql = " Campo o124_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o124_sequencial = $o124_sequencial;
       }
     }
     if(($this->o124_sequencial == null) || ($this->o124_sequencial == "") ){
       $this->erro_sql = " Campo o124_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cronogramaperspectiva(
                                       o124_sequencial
                                      ,o124_ppaversao
                                      ,o124_descricao
                                      ,o124_situacao
                                      ,o124_idusuario
                                      ,o124_datacriacao
                                      ,o124_ano
                                      ,o124_tipo
                       )
                values (
                                $this->o124_sequencial
                               ,$this->o124_ppaversao
                               ,'$this->o124_descricao'
                               ,$this->o124_situacao
                               ,$this->o124_idusuario
                               ,".($this->o124_datacriacao == "null" || $this->o124_datacriacao == ""?"null":"'".$this->o124_datacriacao."'")."
                               ,$this->o124_ano
                               ,$this->o124_tipo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Perspectiva do Cronograma ($this->o124_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Perspectiva do Cronograma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Perspectiva do Cronograma ($this->o124_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o124_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o124_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14871,'$this->o124_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2618,14871,'','".AddSlashes(pg_result($resaco,0,'o124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,14872,'','".AddSlashes(pg_result($resaco,0,'o124_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,14873,'','".AddSlashes(pg_result($resaco,0,'o124_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,14874,'','".AddSlashes(pg_result($resaco,0,'o124_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,14876,'','".AddSlashes(pg_result($resaco,0,'o124_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,14877,'','".AddSlashes(pg_result($resaco,0,'o124_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,14912,'','".AddSlashes(pg_result($resaco,0,'o124_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2618,21180,'','".AddSlashes(pg_result($resaco,0,'o124_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($o124_sequencial=null) {
      $this->atualizacampos();
     $sql = " update cronogramaperspectiva set ";
     $virgula = "";
     if(trim($this->o124_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_sequencial"])){
       $sql  .= $virgula." o124_sequencial = $this->o124_sequencial ";
       $virgula = ",";
       if(trim($this->o124_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "o124_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o124_ppaversao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_ppaversao"])){
       $sql  .= $virgula." o124_ppaversao = $this->o124_ppaversao ";
       $virgula = ",";
       if(trim($this->o124_ppaversao) == null ){
         $this->erro_sql = " Campo Perspectiva do PPA não informado.";
         $this->erro_campo = "o124_ppaversao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o124_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_descricao"])){
       $sql  .= $virgula." o124_descricao = '$this->o124_descricao' ";
       $virgula = ",";
       if(trim($this->o124_descricao) == null ){
         $this->erro_sql = " Campo Descrição da Perspectiva não informado.";
         $this->erro_campo = "o124_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o124_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_situacao"])){
       $sql  .= $virgula." o124_situacao = $this->o124_situacao ";
       $virgula = ",";
       if(trim($this->o124_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "o124_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o124_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_idusuario"])){
       $sql  .= $virgula." o124_idusuario = $this->o124_idusuario ";
       $virgula = ",";
       if(trim($this->o124_idusuario) == null ){
         $this->erro_sql = " Campo Código do Usuário não informado.";
         $this->erro_campo = "o124_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o124_datacriacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_datacriacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o124_datacriacao_dia"] !="") ){
       $sql  .= $virgula." o124_datacriacao = '$this->o124_datacriacao' ";
       $virgula = ",";
       if(trim($this->o124_datacriacao) == null ){
         $this->erro_sql = " Campo Data de Criação não informado.";
         $this->erro_campo = "o124_datacriacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["o124_datacriacao_dia"])){
         $sql  .= $virgula." o124_datacriacao = null ";
         $virgula = ",";
         if(trim($this->o124_datacriacao) == null ){
           $this->erro_sql = " Campo Data de Criação não informado.";
           $this->erro_campo = "o124_datacriacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->o124_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_ano"])){
       $sql  .= $virgula." o124_ano = $this->o124_ano ";
       $virgula = ",";
       if(trim($this->o124_ano) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "o124_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o124_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o124_tipo"])){
       $sql  .= $virgula." o124_tipo = $this->o124_tipo ";
       $virgula = ",";
       if(trim($this->o124_tipo) == null ){
         $this->erro_sql = " Campo Tipo da Perspectiva não informado.";
         $this->erro_campo = "o124_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o124_sequencial!=null){
       $sql .= " o124_sequencial = $this->o124_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o124_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14871,'$this->o124_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_sequencial"]) || $this->o124_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2618,14871,'".AddSlashes(pg_result($resaco,$conresaco,'o124_sequencial'))."','$this->o124_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_ppaversao"]) || $this->o124_ppaversao != "")
             $resac = db_query("insert into db_acount values($acount,2618,14872,'".AddSlashes(pg_result($resaco,$conresaco,'o124_ppaversao'))."','$this->o124_ppaversao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_descricao"]) || $this->o124_descricao != "")
             $resac = db_query("insert into db_acount values($acount,2618,14873,'".AddSlashes(pg_result($resaco,$conresaco,'o124_descricao'))."','$this->o124_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_situacao"]) || $this->o124_situacao != "")
             $resac = db_query("insert into db_acount values($acount,2618,14874,'".AddSlashes(pg_result($resaco,$conresaco,'o124_situacao'))."','$this->o124_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_idusuario"]) || $this->o124_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,2618,14876,'".AddSlashes(pg_result($resaco,$conresaco,'o124_idusuario'))."','$this->o124_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_datacriacao"]) || $this->o124_datacriacao != "")
             $resac = db_query("insert into db_acount values($acount,2618,14877,'".AddSlashes(pg_result($resaco,$conresaco,'o124_datacriacao'))."','$this->o124_datacriacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_ano"]) || $this->o124_ano != "")
             $resac = db_query("insert into db_acount values($acount,2618,14912,'".AddSlashes(pg_result($resaco,$conresaco,'o124_ano'))."','$this->o124_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o124_tipo"]) || $this->o124_tipo != "")
             $resac = db_query("insert into db_acount values($acount,2618,21180,'".AddSlashes(pg_result($resaco,$conresaco,'o124_tipo'))."','$this->o124_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Perspectiva do Cronograma não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o124_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Perspectiva do Cronograma não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($o124_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($o124_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,14871,'$o124_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2618,14871,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,14872,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_ppaversao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,14873,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,14874,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,14876,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,14877,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,14912,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2618,21180,'','".AddSlashes(pg_result($resaco,$iresaco,'o124_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cronogramaperspectiva
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($o124_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " o124_sequencial = $o124_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Perspectiva do Cronograma não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o124_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Perspectiva do Cronograma não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o124_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o124_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cronogramaperspectiva";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($o124_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from cronogramaperspectiva ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cronogramaperspectiva.o124_idusuario";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = cronogramaperspectiva.o124_ppaversao";
     $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o124_sequencial)) {
         $sql2 .= " where cronogramaperspectiva.o124_sequencial = $o124_sequencial ";
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
   // funcao do sql
   public function sql_query_file ($o124_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cronogramaperspectiva ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o124_sequencial)){
         $sql2 .= " where cronogramaperspectiva.o124_sequencial = $o124_sequencial ";
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

  function sql_query_integracao ( $o124_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from cronogramaperspectiva ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cronogramaperspectiva.o124_idusuario";
    $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = cronogramaperspectiva.o124_ppaversao";
    $sql .= "      inner join ppaintegracao  on  ppaversao.o119_sequencial = o123_ppaversao";
    $sql .= "      inner join ppalei  on  ppalei.o01_sequencial = ppaversao.o119_ppalei";
    $sql2 = "";
    if($dbwhere==""){
      if($o124_sequencial!=null ){
        $sql2 .= " where cronogramaperspectiva.o124_sequencial = $o124_sequencial ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

}
