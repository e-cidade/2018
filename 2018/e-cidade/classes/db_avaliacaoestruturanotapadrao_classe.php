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

//MODULO: escola
//CLASSE DA ENTIDADE avaliacaoestruturanotapadrao
class cl_avaliacaoestruturanotapadrao {
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
   var $ed139_sequencial = 0;
   var $ed139_db_estrutura = 0;
   var $ed139_ativo = 'f';
   var $ed139_arredondamedia = 'f';
   var $ed139_regraarredondamento = 0;
   var $ed139_observacao = null;
   var $ed139_ano = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed139_sequencial = int4 = Código
                 ed139_db_estrutura = int4 = Estrutural
                 ed139_ativo = bool = Ativo
                 ed139_arredondamedia = bool = Arredonda média
                 ed139_regraarredondamento = int4 = Regra de arredondamento
                 ed139_observacao = varchar(300) = Observação
                 ed139_ano = int4 = Ano
                 ";
   //funcao construtor da classe
   function cl_avaliacaoestruturanotapadrao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoestruturanotapadrao");
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
       $this->ed139_sequencial = ($this->ed139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_sequencial"]:$this->ed139_sequencial);
       $this->ed139_db_estrutura = ($this->ed139_db_estrutura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_db_estrutura"]:$this->ed139_db_estrutura);
       $this->ed139_ativo = ($this->ed139_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_ativo"]:$this->ed139_ativo);
       $this->ed139_arredondamedia = ($this->ed139_arredondamedia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_arredondamedia"]:$this->ed139_arredondamedia);
       $this->ed139_regraarredondamento = ($this->ed139_regraarredondamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_regraarredondamento"]:$this->ed139_regraarredondamento);
       $this->ed139_observacao = ($this->ed139_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_observacao"]:$this->ed139_observacao);
       $this->ed139_ano = ($this->ed139_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_ano"]:$this->ed139_ano);
     }else{
       $this->ed139_sequencial = ($this->ed139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed139_sequencial"]:$this->ed139_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($ed139_sequencial){
      $this->atualizacampos();
     if($this->ed139_db_estrutura == null ){
       $this->erro_sql = " Campo Estrutural não informado.";
       $this->erro_campo = "ed139_db_estrutura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed139_ativo == null ){
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "ed139_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed139_arredondamedia == null ){
       $this->erro_sql = " Campo Arredonda média não informado.";
       $this->erro_campo = "ed139_arredondamedia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed139_regraarredondamento == null ){
       $this->ed139_regraarredondamento = "0";
     }
     if($this->ed139_ano == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed139_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed139_sequencial == "" || $ed139_sequencial == null ){
       $result = db_query("select nextval('avaliacaoestruturanotapadrao_ed139_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoestruturanotapadrao_ed139_sequencial_seq do campo: ed139_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed139_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from avaliacaoestruturanotapadrao_ed139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed139_sequencial)){
         $this->erro_sql = " Campo ed139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed139_sequencial = $ed139_sequencial;
       }
     }
     if(($this->ed139_sequencial == null) || ($this->ed139_sequencial == "") ){
       $this->erro_sql = " Campo ed139_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoestruturanotapadrao(
                                       ed139_sequencial
                                      ,ed139_db_estrutura
                                      ,ed139_ativo
                                      ,ed139_arredondamedia
                                      ,ed139_regraarredondamento
                                      ,ed139_observacao
                                      ,ed139_ano
                       )
                values (
                                $this->ed139_sequencial
                               ,$this->ed139_db_estrutura
                               ,'$this->ed139_ativo'
                               ,'$this->ed139_arredondamedia'
                               ,$this->ed139_regraarredondamento
                               ,'$this->ed139_observacao'
                               ,$this->ed139_ano
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuração da Nota ($this->ed139_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuração da Nota já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuração da Nota ($this->ed139_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed139_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22148,'$this->ed139_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3990,22148,'','".AddSlashes(pg_result($resaco,0,'ed139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3990,22149,'','".AddSlashes(pg_result($resaco,0,'ed139_db_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3990,22150,'','".AddSlashes(pg_result($resaco,0,'ed139_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3990,22151,'','".AddSlashes(pg_result($resaco,0,'ed139_arredondamedia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3990,22152,'','".AddSlashes(pg_result($resaco,0,'ed139_regraarredondamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3990,22153,'','".AddSlashes(pg_result($resaco,0,'ed139_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3990,22154,'','".AddSlashes(pg_result($resaco,0,'ed139_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed139_sequencial=null) {
      $this->atualizacampos();
     $sql = " update avaliacaoestruturanotapadrao set ";
     $virgula = "";
     if(trim($this->ed139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_sequencial"])){
       $sql  .= $virgula." ed139_sequencial = $this->ed139_sequencial ";
       $virgula = ",";
       if(trim($this->ed139_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed139_db_estrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_db_estrutura"])){
       $sql  .= $virgula." ed139_db_estrutura = $this->ed139_db_estrutura ";
       $virgula = ",";
       if(trim($this->ed139_db_estrutura) == null ){
         $this->erro_sql = " Campo Estrutural não informado.";
         $this->erro_campo = "ed139_db_estrutura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed139_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_ativo"])){
       $sql  .= $virgula." ed139_ativo = '$this->ed139_ativo' ";
       $virgula = ",";
       if(trim($this->ed139_ativo) == null ){
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "ed139_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed139_arredondamedia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_arredondamedia"])){
       $sql  .= $virgula." ed139_arredondamedia = '$this->ed139_arredondamedia' ";
       $virgula = ",";
       if(trim($this->ed139_arredondamedia) == null ){
         $this->erro_sql = " Campo Arredonda média não informado.";
         $this->erro_campo = "ed139_arredondamedia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed139_regraarredondamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_regraarredondamento"])){
        if(trim($this->ed139_regraarredondamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed139_regraarredondamento"])){
           $this->ed139_regraarredondamento = "0" ;
        }
       $sql  .= $virgula." ed139_regraarredondamento = $this->ed139_regraarredondamento ";
       $virgula = ",";
     }
     if(trim($this->ed139_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_observacao"])){
       $sql  .= $virgula." ed139_observacao = '$this->ed139_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ed139_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed139_ano"])){
       $sql  .= $virgula." ed139_ano = $this->ed139_ano ";
       $virgula = ",";
       if(trim($this->ed139_ano) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed139_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed139_sequencial!=null){
       $sql .= " ed139_sequencial = $this->ed139_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed139_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22148,'$this->ed139_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_sequencial"]) || $this->ed139_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3990,22148,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_sequencial'))."','$this->ed139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_db_estrutura"]) || $this->ed139_db_estrutura != "")
             $resac = db_query("insert into db_acount values($acount,3990,22149,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_db_estrutura'))."','$this->ed139_db_estrutura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_ativo"]) || $this->ed139_ativo != "")
             $resac = db_query("insert into db_acount values($acount,3990,22150,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_ativo'))."','$this->ed139_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_arredondamedia"]) || $this->ed139_arredondamedia != "")
             $resac = db_query("insert into db_acount values($acount,3990,22151,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_arredondamedia'))."','$this->ed139_arredondamedia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_regraarredondamento"]) || $this->ed139_regraarredondamento != "")
             $resac = db_query("insert into db_acount values($acount,3990,22152,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_regraarredondamento'))."','$this->ed139_regraarredondamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_observacao"]) || $this->ed139_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3990,22153,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_observacao'))."','$this->ed139_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed139_ano"]) || $this->ed139_ano != "")
             $resac = db_query("insert into db_acount values($acount,3990,22154,'".AddSlashes(pg_result($resaco,$conresaco,'ed139_ano'))."','$this->ed139_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração da Nota não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configuração da Nota não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed139_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed139_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22148,'$ed139_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3990,22148,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3990,22149,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_db_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3990,22150,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3990,22151,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_arredondamedia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3990,22152,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_regraarredondamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3990,22153,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3990,22154,'','".AddSlashes(pg_result($resaco,$iresaco,'ed139_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoestruturanotapadrao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed139_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed139_sequencial = $ed139_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração da Nota não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configuração da Nota não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoestruturanotapadrao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed139_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaoestruturanotapadrao ";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = avaliacaoestruturanotapadrao.ed139_db_estrutura";
     $sql .= "      left  join regraarredondamento  on  regraarredondamento.ed316_sequencial = avaliacaoestruturanotapadrao.ed139_regraarredondamento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed139_sequencial)) {
         $sql2 .= " where avaliacaoestruturanotapadrao.ed139_sequencial = $ed139_sequencial ";
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
   public function sql_query_file ($ed139_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaoestruturanotapadrao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed139_sequencial)){
         $sql2 .= " where avaliacaoestruturanotapadrao.ed139_sequencial = $ed139_sequencial ";
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
