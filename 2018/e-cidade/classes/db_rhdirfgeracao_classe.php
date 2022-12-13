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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhdirfgeracao
class cl_rhdirfgeracao {
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
   var $rh95_sequencial = 0;
   var $rh95_id_usuario = 0;
   var $rh95_ano = 0;
   var $rh95_datageracao_dia = null;
   var $rh95_datageracao_mes = null;
   var $rh95_datageracao_ano = null;
   var $rh95_datageracao = null;
   var $rh95_fontepagadora = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 rh95_sequencial = int4 = Sequencial
                 rh95_id_usuario = int4 = Cod. Usuário
                 rh95_ano = int4 = Ano
                 rh95_datageracao = date = Data da Geração
                 rh95_fontepagadora = varchar(100) = Fonte pagadora
                 ";
   //funcao construtor da classe
   function cl_rhdirfgeracao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracao");
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
       $this->rh95_sequencial = ($this->rh95_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_sequencial"]:$this->rh95_sequencial);
       $this->rh95_id_usuario = ($this->rh95_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_id_usuario"]:$this->rh95_id_usuario);
       $this->rh95_ano = ($this->rh95_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_ano"]:$this->rh95_ano);
       if($this->rh95_datageracao == ""){
         $this->rh95_datageracao_dia = ($this->rh95_datageracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_datageracao_dia"]:$this->rh95_datageracao_dia);
         $this->rh95_datageracao_mes = ($this->rh95_datageracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_datageracao_mes"]:$this->rh95_datageracao_mes);
         $this->rh95_datageracao_ano = ($this->rh95_datageracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_datageracao_ano"]:$this->rh95_datageracao_ano);
         if($this->rh95_datageracao_dia != ""){
            $this->rh95_datageracao = $this->rh95_datageracao_ano."-".$this->rh95_datageracao_mes."-".$this->rh95_datageracao_dia;
         }
       }
       $this->rh95_fontepagadora = ($this->rh95_fontepagadora == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_fontepagadora"]:$this->rh95_fontepagadora);
     }else{
       $this->rh95_sequencial = ($this->rh95_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh95_sequencial"]:$this->rh95_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh95_sequencial){
      $this->atualizacampos();
     if($this->rh95_id_usuario == null ){
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "rh95_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh95_ano == null ){
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh95_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh95_datageracao == null ){
       $this->erro_sql = " Campo Data da Geração nao Informado.";
       $this->erro_campo = "rh95_datageracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh95_fontepagadora == null ){
       $this->erro_sql = " Campo Fonte pagadora nao Informado.";
       $this->erro_campo = "rh95_fontepagadora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh95_sequencial == "" || $rh95_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracao_rh95_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracao_rh95_sequencial_seq do campo: rh95_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->rh95_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from rhdirfgeracao_rh95_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh95_sequencial)){
         $this->erro_sql = " Campo rh95_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh95_sequencial = $rh95_sequencial;
       }
     }
     if(($this->rh95_sequencial == null) || ($this->rh95_sequencial == "") ){
       $this->erro_sql = " Campo rh95_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracao(
                                       rh95_sequencial
                                      ,rh95_id_usuario
                                      ,rh95_ano
                                      ,rh95_datageracao
                                      ,rh95_fontepagadora
                       )
                values (
                                $this->rh95_sequencial
                               ,$this->rh95_id_usuario
                               ,$this->rh95_ano
                               ,".($this->rh95_datageracao == "null" || $this->rh95_datageracao == ""?"null":"'".$this->rh95_datageracao."'")."
                               ,'$this->rh95_fontepagadora'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhdirfgeracao ($this->rh95_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhdirfgeracao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhdirfgeracao ($this->rh95_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh95_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh95_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17764,'$this->rh95_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3136,17764,'','".AddSlashes(pg_result($resaco,0,'rh95_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3136,17765,'','".AddSlashes(pg_result($resaco,0,'rh95_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3136,17766,'','".AddSlashes(pg_result($resaco,0,'rh95_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3136,17767,'','".AddSlashes(pg_result($resaco,0,'rh95_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3136,17775,'','".AddSlashes(pg_result($resaco,0,'rh95_fontepagadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($rh95_sequencial=null) {
      $this->atualizacampos();
     $sql = " update rhdirfgeracao set ";
     $virgula = "";
     if(trim($this->rh95_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh95_sequencial"])){
       $sql  .= $virgula." rh95_sequencial = $this->rh95_sequencial ";
       $virgula = ",";
       if(trim($this->rh95_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh95_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh95_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh95_id_usuario"])){
       $sql  .= $virgula." rh95_id_usuario = $this->rh95_id_usuario ";
       $virgula = ",";
       if(trim($this->rh95_id_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "rh95_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh95_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh95_ano"])){
       $sql  .= $virgula." rh95_ano = $this->rh95_ano ";
       $virgula = ",";
       if(trim($this->rh95_ano) == null ){
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh95_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh95_datageracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh95_datageracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh95_datageracao_dia"] !="") ){
       $sql  .= $virgula." rh95_datageracao = '$this->rh95_datageracao' ";
       $virgula = ",";
       if(trim($this->rh95_datageracao) == null ){
         $this->erro_sql = " Campo Data da Geração nao Informado.";
         $this->erro_campo = "rh95_datageracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh95_datageracao_dia"])){
         $sql  .= $virgula." rh95_datageracao = null ";
         $virgula = ",";
         if(trim($this->rh95_datageracao) == null ){
           $this->erro_sql = " Campo Data da Geração nao Informado.";
           $this->erro_campo = "rh95_datageracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh95_fontepagadora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh95_fontepagadora"])){
       $sql  .= $virgula." rh95_fontepagadora = '$this->rh95_fontepagadora' ";
       $virgula = ",";
       if(trim($this->rh95_fontepagadora) == null ){
         $this->erro_sql = " Campo Fonte pagadora nao Informado.";
         $this->erro_campo = "rh95_fontepagadora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh95_sequencial!=null){
       $sql .= " rh95_sequencial = $this->rh95_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh95_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17764,'$this->rh95_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh95_sequencial"]) || $this->rh95_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3136,17764,'".AddSlashes(pg_result($resaco,$conresaco,'rh95_sequencial'))."','$this->rh95_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh95_id_usuario"]) || $this->rh95_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3136,17765,'".AddSlashes(pg_result($resaco,$conresaco,'rh95_id_usuario'))."','$this->rh95_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh95_ano"]) || $this->rh95_ano != "")
           $resac = db_query("insert into db_acount values($acount,3136,17766,'".AddSlashes(pg_result($resaco,$conresaco,'rh95_ano'))."','$this->rh95_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh95_datageracao"]) || $this->rh95_datageracao != "")
           $resac = db_query("insert into db_acount values($acount,3136,17767,'".AddSlashes(pg_result($resaco,$conresaco,'rh95_datageracao'))."','$this->rh95_datageracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh95_fontepagadora"]) || $this->rh95_fontepagadora != "")
           $resac = db_query("insert into db_acount values($acount,3136,17775,'".AddSlashes(pg_result($resaco,$conresaco,'rh95_fontepagadora'))."','$this->rh95_fontepagadora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh95_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh95_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh95_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($rh95_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh95_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17764,'$rh95_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3136,17764,'','".AddSlashes(pg_result($resaco,$iresaco,'rh95_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3136,17765,'','".AddSlashes(pg_result($resaco,$iresaco,'rh95_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3136,17766,'','".AddSlashes(pg_result($resaco,$iresaco,'rh95_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3136,17767,'','".AddSlashes(pg_result($resaco,$iresaco,'rh95_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3136,17775,'','".AddSlashes(pg_result($resaco,$iresaco,'rh95_fontepagadora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhdirfgeracao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh95_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh95_sequencial = $rh95_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh95_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh95_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh95_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $rh95_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhdirfgeracao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhdirfgeracao.rh95_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($rh95_sequencial!=null ){
         $sql2 .= " where rhdirfgeracao.rh95_sequencial = $rh95_sequencial ";
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
   // funcao do sql
   function sql_query_file ( $rh95_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhdirfgeracao ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh95_sequencial!=null ){
         $sql2 .= " where rhdirfgeracao.rh95_sequencial = $rh95_sequencial ";
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


  public function sql_dados_geracao_arquivo($lGerarContabil, $iAno, $sCnpj, $nValorLimite, $sMatriculaSelecionadas, $lAcima6000 ) {

    $sTipo = "1";

    if ($lGerarContabil) {
      $sTipo .= ", 2";
    }

    $sSqlTipoReceitas  = " SELECT distinct rh98_tipoirrf,                                                                                               " . PHP_EOL;
    $sSqlTipoReceitas .= "        length(trim(z01_cgccpf)) as tipopessoa,                                                                               " . PHP_EOL;
    $sSqlTipoReceitas .= "        rh96_numcgm,                                                                                                          " . PHP_EOL;
    $sSqlTipoReceitas .= "        rh96_sequencial,                                                                                                          " . PHP_EOL;
    $sSqlTipoReceitas .= "        trim(z01_cgccpf) as z01_cgccpf,                                                                                       " . PHP_EOL;
    $sSqlTipoReceitas .= "        z01_nome,                                                                                                             " . PHP_EOL;
    $sSqlTipoReceitas .= "        rh95_sequencial,                                                                                                      " . PHP_EOL;
    $sSqlTipoReceitas .= "        case                                                                                                                  " . PHP_EOL;
    $sSqlTipoReceitas .= "          when exists ( select 1                                                                                              " . PHP_EOL;
    $sSqlTipoReceitas .= "                           from rhdirfgeracaodadospessoalvalor z                                                              " . PHP_EOL;
    $sSqlTipoReceitas .= "                                inner join rhdirfgeracaodadospessoal x on x.rh96_sequencial = z.rh98_rhdirfgeracaodadospessoal" . PHP_EOL;
    $sSqlTipoReceitas .= "                          where x.rh96_rhdirfgeracao   = rhdirfgeracaodadospessoal.rh96_rhdirfgeracao                         " . PHP_EOL;
    $sSqlTipoReceitas .= "                            and z.rh98_rhdirftipovalor = rhdirfgeracaodadospessoalvalor.rh98_rhdirftipovalor                  " . PHP_EOL;
    $sSqlTipoReceitas .= "                            and x.rh96_numcgm          = rhdirfgeracaodadospessoal.rh96_numcgm                                " . PHP_EOL;
    $sSqlTipoReceitas .= "                            and x.rh96_tipo in (1,2)                                                                          " . PHP_EOL;
    $sSqlTipoReceitas .= "                            and z.rh98_tipoirrf < rhdirfgeracaodadospessoalvalor.rh98_tipoirrf                                " . PHP_EOL;
    $sSqlTipoReceitas .= "                       ) then false                                                                                           " . PHP_EOL;
    $sSqlTipoReceitas .= "          else true                                                                                                           " . PHP_EOL;
    $sSqlTipoReceitas .= "        end as sem_retencao                                                                                                   " . PHP_EOL;
    $sSqlTipoReceitas .= "   from rhdirfgeracaodadospessoalvalor                                                                                        " . PHP_EOL;
    $sSqlTipoReceitas .= "        inner join rhdirfgeracaodadospessoal  on rh98_rhdirfgeracaodadospessoal      = rh96_sequencial                        " . PHP_EOL;
    $sSqlTipoReceitas .= "        left  join rhdirfgeracaopessoalregist on rh99_rhdirfgeracaodadospessoalvalor = rh98_sequencial                        " . PHP_EOL;
    $sSqlTipoReceitas .= "        inner join rhdirfgeracao              on rh96_rhdirfgeracao                  = rh95_sequencial                        " . PHP_EOL;
    $sSqlTipoReceitas .= "        inner join cgm                        on z01_numcgm                          = rh96_numcgm                            " . PHP_EOL;
    $sSqlTipoReceitas .= "  where rh95_ano = {$iAno}                                                                                                    " . PHP_EOL;
    $sSqlTipoReceitas .= "    and (rh98_rhdirftipovalor in (6,16)                                                                                       " . PHP_EOL;

    if ($lAcima6000) {

      $sSqlTipoReceitas .= "      or  ((select sum(case when rh98_rhdirftipovalor <> 7 then z.rh98_valor else z.rh98_valor*(-1) end) as valor " . PHP_EOL;
      $sSqlTipoReceitas .= "          from  rhdirfgeracaodadospessoalvalor z                                                                  " . PHP_EOL;
      $sSqlTipoReceitas .= "                inner join rhdirfgeracaodadospessoal a                                                            " . PHP_EOL;
      $sSqlTipoReceitas .= "                          on z.rh98_rhdirfgeracaodadospessoal = a.rh96_sequencial                                 " . PHP_EOL;
      $sSqlTipoReceitas .= "           inner join rhdirfgeracao b            on a.rh96_rhdirfgeracao  = b.rh95_sequencial                     " . PHP_EOL;
      $sSqlTipoReceitas .= "          where a.rh96_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm                                             " . PHP_EOL;
      $sSqlTipoReceitas .= "            and b.rh95_fontepagadora   = '{$sCnpj}'                                                               " . PHP_EOL;
      $sSqlTipoReceitas .= "            and b.rh95_ano   = {$iAno}                                                                            " . PHP_EOL;
      $sSqlTipoReceitas .= "            and z.rh98_rhdirftipovalor  in (1, 7, 12)                                                             " . PHP_EOL;
      $sSqlTipoReceitas .= "            and a.rh96_tipo  = 1) >= {$nValorLimite} )                                                            " . PHP_EOL;

    } else {

      $sSqlTipoReceitas .= "      or  ((select sum(z.rh98_valor) as valor                                                  " . PHP_EOL;
      $sSqlTipoReceitas .= "          from  rhdirfgeracaodadospessoalvalor z                                               " . PHP_EOL;
      $sSqlTipoReceitas .= "                inner join rhdirfgeracaodadospessoal a                                         " . PHP_EOL;
      $sSqlTipoReceitas .= "                          on z.rh98_rhdirfgeracaodadospessoal = a.rh96_sequencial              " . PHP_EOL;
      $sSqlTipoReceitas .= "           inner join rhdirfgeracao b            on a.rh96_rhdirfgeracao  = b.rh95_sequencial  " . PHP_EOL;
      $sSqlTipoReceitas .= "          where a.rh96_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm                          " . PHP_EOL;
      $sSqlTipoReceitas .= "            and b.rh95_fontepagadora   = '{$sCnpj}'                                            " . PHP_EOL;
      $sSqlTipoReceitas .= "            and b.rh95_ano   = {$iAno}                                                         " . PHP_EOL;
      $sSqlTipoReceitas .= "            and a.rh96_tipo  = 1) >= 0.01                                                      " . PHP_EOL;
      $sSqlTipoReceitas .= "           )                                                                                   " . PHP_EOL;
    }

    $sSqlTipoReceitas .= "         )                                                                                       " . PHP_EOL;
    $sSqlTipoReceitas .= "    and rh96_tipo in({$sTipo})                                                                   " . PHP_EOL;
    $sSqlTipoReceitas .= "    and rh95_fontepagadora   = '{$sCnpj}'                                                        " . PHP_EOL;
    $sSqlTipoReceitas .= "    and rh95_ano             = {$iAno}                                                           " . PHP_EOL;

    // $sMatriculaSelecionadas = $this->oDirf->getMatriculas();

    if (!empty($sMatriculaSelecionadas)) {
      $sSqlTipoReceitas .= "  and rh99_regist in({$sMatriculaSelecionadas})                                                " . PHP_EOL;
    }

    $sSqlTipoReceitas .= "   order by rh98_tipoirrf,1,                                                                     " . PHP_EOL;
    $sSqlTipoReceitas .= "            z01_cgccpf                                                                           " . PHP_EOL;
    return $sSqlTipoReceitas;
  }


  public function sql_query_comprovante_rendimentos (DBCompetencia $competencia, $where = null, $orderBy = null) {

    $sSqlRendimento  = " select rh96_numcgm,                                                                                                                                                    \n";
    $sSqlRendimento .= "        x.rh96_sequencial,                                                                                                                                              \n";
    $sSqlRendimento .= "        z01_nome,                                                                                                                                                       \n";
    $sSqlRendimento .= "        (select array_agg( distinct rh99_regist order by rh99_regist asc)                                                                                               \n";
    $sSqlRendimento .= "           from rhdirfgeracaodadospessoal                                                                                                                               \n";
    $sSqlRendimento .= "                inner join rhdirfgeracaodadospessoalvalor on rh96_sequencial = rh98_rhdirfgeracaodadospessoal                                                           \n";
    $sSqlRendimento .= "                inner join rhdirfgeracaopessoalregist     on rh98_sequencial = rh99_rhdirfgeracaodadospessoalvalor                                                      \n";
    $sSqlRendimento .= "          where rhdirfgeracaodadospessoal.rh96_sequencial =   x.rh96_sequencial                                                                                         \n";
    $sSqlRendimento .= "          group by rh96_numcgm) as regist,                                                                                                                              \n";
    $sSqlRendimento .= "        rh96_cpfcnpj,                                                                                                                                                   \n";
    $sSqlRendimento .= "        x.r70_codigo,                                                                                                                                                   \n";
    $sSqlRendimento .= "        x.r70_estrut,                                                                                                                                                   \n";
    $sSqlRendimento .= "        x.rh95_fontepagadora,                                       ";
    $sSqlRendimento .= "        (select  z01_nome as nomeinst                               ";
    $sSqlRendimento .= "			     from orcunidade                                          ";
    $sSqlRendimento .= "				        inner join rhlotaexe on rh26_orgao   = o41_orgao    ";
    $sSqlRendimento .= "		                	              and rh26_unidade = o41_unidade  ";
    $sSqlRendimento .= "					                          and o41_anousu   = rh26_anousu  ";
    $sSqlRendimento .= "					      inner join rhlota    on r70_codigo   = rh26_codigo  ";
    $sSqlRendimento .= "					      inner join cgm       on r70_numcgm   = z01_numcgm   ";
    $sSqlRendimento .= "			    where o41_cnpj   = trim(x.rh95_fontepagadora)                 ";
    $sSqlRendimento .= "			      and z01_cgccpf = trim(x.rh95_fontepagadora) limit 1) as nome_fonte,   ";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 1                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                  ),0) as rendimento,                                                                                                                                   \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 1                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                  ),0) as rendimento_13,                                                                                                                                \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 2                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                  ),0) as prev_oficial,                                                                                                                                 \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 2                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                  ),0) as prev_oficial_13,                                                                                                                              \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 3                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                ), 0) as prev_privada,                                                                                                                                  \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 3                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                ), 0) as prev_privada_13,                                                                                                                               \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 4                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                ),0) as depend,                                                                                                                                         \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 4                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                ),0) as depend_13,                                                                                                                                      \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 5                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as pensao,                                                                                                                                        \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 5                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                 ),0) as pensao_13,                                                                                                                                     \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 6                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                ),0) as irrf,                                                                                                                                           \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 6                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                ),0) as irrf_13,                                                                                                                                        \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 7                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                  ),0) as aposentadoria_65,                                                                                                                             \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 7                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                  ),0) as aposentadoria_65_13,                                                                                                                          \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 8                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as diaria,                                                                                                                                        \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 9                                                                                                                      \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as ind_rescisao,                                                                                                                                  \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 10                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as abono,                                                                                                                                         \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 15                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as outros5,                                                                                                                                       \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 11                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                  ),0) as molestia_grave_inativos,                                                                                                                      \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 11                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                  ),0) as molestia_grave_inativos_13,                                                                                                                   \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 12                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                ),0) as molestia_grave_ativos,                                                                                                                          \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 12                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes = 13                                                                                                                                 \n";
    $sSqlRendimento .= "                ),0) as molestia_grave_ativos_13,                                                                                                                       \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor IN (13,14)                                                                                                               \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as plano_saude,                                                                                                                                   \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 17                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_rendimentos_tributaveis,                                                                                                                   \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 18                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_previdencia,                                                                                                                               \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 19                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_pensao,                                                                                                                                    \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 20                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_irrf,                                                                                                                                      \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 21                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_despesa_acao,                                                                                                                              \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 22                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_quantidade_meses,                                                                                                                          \n";
    $sSqlRendimento .= "        coalesce(( select sum(rh98_valor)                                                                                                                               \n";
    $sSqlRendimento .= "                     from rhdirfgeracaodadospessoalvalor                                                                                                                \n";
    $sSqlRendimento .= "                    where rh98_rhdirftipovalor = 23                                                                                                                     \n";
    $sSqlRendimento .= "                      and rh98_rhdirfgeracaodadospessoal = x.rh96_sequencial                                                                                            \n";
    $sSqlRendimento .= "                      and rh98_mes between 1 and 12                                                                                                                     \n";
    $sSqlRendimento .= "                 ),0) as rra_isentos                                                                                                                                    \n";
    $sSqlRendimento .= "   from ( select distinct                                                                                                                                               \n";
    $sSqlRendimento .= "                 rh96_sequencial,                                                                                                                                       \n";
    $sSqlRendimento .= "                 rh96_numcgm,                                                                                                                                           \n";
    $sSqlRendimento .= "                 z01_nome,                                                                                                                                              \n";
    $sSqlRendimento .= "                 rh96_cpfcnpj,                                                                                                                                          \n";
    $sSqlRendimento .= "                 rh95_fontepagadora,                                                                                                                                          \n";
    $sSqlRendimento .= "                 rh96_regist,                                                                                                                                           \n";
    $sSqlRendimento .= "                 r70_codigo,                                                                                                                                            \n";
    $sSqlRendimento .= "                 r70_estrut,                                                                                                                                            \n";
    $sSqlRendimento .= "                 r70_descr                                                                                                                                              \n";
    $sSqlRendimento .= "            from rhdirfgeracao                                                                                                                                          \n";
    $sSqlRendimento .= "                 inner join rhdirfgeracaodadospessoal      on rhdirfgeracaodadospessoal.rh96_rhdirfgeracao                  = rhdirfgeracao.rh95_sequencial             \n";
    $sSqlRendimento .= "                 inner join rhdirfgeracaodadospessoalvalor on rhdirfgeracaodadospessoalvalor.rh98_rhdirfgeracaodadospessoal = rhdirfgeracaodadospessoal.rh96_sequencial \n";
    $sSqlRendimento .= "                 inner join cgm                            on cgm.z01_numcgm                                                = rhdirfgeracaodadospessoal.rh96_numcgm     \n";
    $sSqlRendimento .= "                 inner join rhdirfgeracaopessoalregist     on rhdirfgeracaodadospessoalvalor.rh98_sequencial                = rh99_rhdirfgeracaodadospessoalvalor       \n";
    $sSqlRendimento .= "                 inner join rhpessoalmov                   on rh02_anousu                                                   = {$competencia->getAno()}                  \n";
    $sSqlRendimento .= "                                                          and rh02_mesusu                                                   = {$competencia->getMes()}                  \n";
    $sSqlRendimento .= "                                                          and rh02_regist                                                   = rh99_regist                               \n";
    $sSqlRendimento .= "                                                          and rh02_instit                                                   = ".db_getsession("DB_instit")."            \n";
    $sSqlRendimento .= "                 inner join rhlota                         on rhlota.r70_codigo                                             = rhpessoalmov.rh02_lota                    \n";
    $sSqlRendimento .= "                                                          and rhlota.r70_instit                                             = rhpessoalmov.rh02_instit                  \n";
    if (!empty($where)) {
      $sSqlRendimento .=  "where {$where} ";
    }
    $sSqlRendimento .= "        ) as x                                                                                                                                                          \n";
    if (!empty($orderBy)) {
      $sSqlRendimento .= " order by {$orderBy} ";
    }

    return $sSqlRendimento;

  }

}
