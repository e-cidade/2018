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

//MODULO: escola
//CLASSE DA ENTIDADE avaliacaoestruturanota
class cl_avaliacaoestruturanota {
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
   var $ed315_sequencial = 0;
   var $ed315_escola = 0;
   var $ed315_db_estrutura = 0;
   var $ed315_ativo = 'f';
   var $ed315_arredondamedia = 'f';
   var $ed315_observacao = null;
   var $ed315_ano = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed315_sequencial = int4 = Código Avaliação Estrutura
                 ed315_escola = int8 = Código da Escola
                 ed315_db_estrutura = int4 = Código da Estrutura da Nota
                 ed315_ativo = bool = Ativo
                 ed315_arredondamedia = bool = Arredondar a Média
                 ed315_observacao = varchar(300) = Observação
                 ed315_ano = int4 = Ano da Configuração
                 ";
   //funcao construtor da classe
   function cl_avaliacaoestruturanota() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoestruturanota");
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
       $this->ed315_sequencial = ($this->ed315_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed315_sequencial"]:$this->ed315_sequencial);
       $this->ed315_escola = ($this->ed315_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed315_escola"]:$this->ed315_escola);
       $this->ed315_db_estrutura = ($this->ed315_db_estrutura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed315_db_estrutura"]:$this->ed315_db_estrutura);
       $this->ed315_ativo = ($this->ed315_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed315_ativo"]:$this->ed315_ativo);
       $this->ed315_arredondamedia = ($this->ed315_arredondamedia == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed315_arredondamedia"]:$this->ed315_arredondamedia);
       $this->ed315_observacao = ($this->ed315_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed315_observacao"]:$this->ed315_observacao);
       $this->ed315_ano = ($this->ed315_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed315_ano"]:$this->ed315_ano);
     }else{
       $this->ed315_sequencial = ($this->ed315_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed315_sequencial"]:$this->ed315_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed315_sequencial){
      $this->atualizacampos();
     if($this->ed315_escola == null ){
       $this->erro_sql = " Campo Código da Escola nao Informado.";
       $this->erro_campo = "ed315_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed315_db_estrutura == null ){
       $this->erro_sql = " Campo Código da Estrutura da Nota nao Informado.";
       $this->erro_campo = "ed315_db_estrutura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed315_ativo == null ){
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ed315_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed315_arredondamedia == null ){
       $this->erro_sql = " Campo Arredondar a Média nao Informado.";
       $this->erro_campo = "ed315_arredondamedia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed315_ano == null ){
       $this->erro_sql = " Campo Ano da Configuração nao Informado.";
       $this->erro_campo = "ed315_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed315_sequencial == "" || $ed315_sequencial == null ){
       $result = db_query("select nextval('avaliacaoestruturanota_ed315_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoestruturanota_ed315_sequencial_seq do campo: ed315_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed315_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from avaliacaoestruturanota_ed315_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed315_sequencial)){
         $this->erro_sql = " Campo ed315_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed315_sequencial = $ed315_sequencial;
       }
     }
     if(($this->ed315_sequencial == null) || ($this->ed315_sequencial == "") ){
       $this->erro_sql = " Campo ed315_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoestruturanota(
                                       ed315_sequencial
                                      ,ed315_escola
                                      ,ed315_db_estrutura
                                      ,ed315_ativo
                                      ,ed315_arredondamedia
                                      ,ed315_observacao
                                      ,ed315_ano
                       )
                values (
                                $this->ed315_sequencial
                               ,$this->ed315_escola
                               ,$this->ed315_db_estrutura
                               ,'$this->ed315_ativo'
                               ,'$this->ed315_arredondamedia'
                               ,'$this->ed315_observacao'
                               ,$this->ed315_ano
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Estrutura da Nota ($this->ed315_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Estrutura da Nota já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Estrutura da Nota ($this->ed315_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed315_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed315_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18940,'$this->ed315_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3367,18940,'','".AddSlashes(pg_result($resaco,0,'ed315_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3367,18977,'','".AddSlashes(pg_result($resaco,0,'ed315_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3367,18941,'','".AddSlashes(pg_result($resaco,0,'ed315_db_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3367,18942,'','".AddSlashes(pg_result($resaco,0,'ed315_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3367,18943,'','".AddSlashes(pg_result($resaco,0,'ed315_arredondamedia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3367,18944,'','".AddSlashes(pg_result($resaco,0,'ed315_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3367,19734,'','".AddSlashes(pg_result($resaco,0,'ed315_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed315_sequencial=null) {
      $this->atualizacampos();
     $sql = " update avaliacaoestruturanota set ";
     $virgula = "";
     if(trim($this->ed315_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_sequencial"])){
       $sql  .= $virgula." ed315_sequencial = $this->ed315_sequencial ";
       $virgula = ",";
       if(trim($this->ed315_sequencial) == null ){
         $this->erro_sql = " Campo Código Avaliação Estrutura nao Informado.";
         $this->erro_campo = "ed315_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed315_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_escola"])){
       $sql  .= $virgula." ed315_escola = $this->ed315_escola ";
       $virgula = ",";
       if(trim($this->ed315_escola) == null ){
         $this->erro_sql = " Campo Código da Escola nao Informado.";
         $this->erro_campo = "ed315_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed315_db_estrutura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_db_estrutura"])){
       $sql  .= $virgula." ed315_db_estrutura = $this->ed315_db_estrutura ";
       $virgula = ",";
       if(trim($this->ed315_db_estrutura) == null ){
         $this->erro_sql = " Campo Código da Estrutura da Nota nao Informado.";
         $this->erro_campo = "ed315_db_estrutura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed315_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_ativo"])){
       $sql  .= $virgula." ed315_ativo = '$this->ed315_ativo' ";
       $virgula = ",";
       if(trim($this->ed315_ativo) == null ){
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ed315_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed315_arredondamedia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_arredondamedia"])){
       $sql  .= $virgula." ed315_arredondamedia = '$this->ed315_arredondamedia' ";
       $virgula = ",";
       if(trim($this->ed315_arredondamedia) == null ){
         $this->erro_sql = " Campo Arredondar a Média nao Informado.";
         $this->erro_campo = "ed315_arredondamedia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed315_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_observacao"])){
       $sql  .= $virgula." ed315_observacao = '$this->ed315_observacao' ";
       $virgula = ",";
     }
     if(trim($this->ed315_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed315_ano"])){
       $sql  .= $virgula." ed315_ano = $this->ed315_ano ";
       $virgula = ",";
       if(trim($this->ed315_ano) == null ){
         $this->erro_sql = " Campo Ano da Configuração nao Informado.";
         $this->erro_campo = "ed315_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed315_sequencial!=null){
       $sql .= " ed315_sequencial = $this->ed315_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed315_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18940,'$this->ed315_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_sequencial"]) || $this->ed315_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3367,18940,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_sequencial'))."','$this->ed315_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_escola"]) || $this->ed315_escola != "")
           $resac = db_query("insert into db_acount values($acount,3367,18977,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_escola'))."','$this->ed315_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_db_estrutura"]) || $this->ed315_db_estrutura != "")
           $resac = db_query("insert into db_acount values($acount,3367,18941,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_db_estrutura'))."','$this->ed315_db_estrutura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_ativo"]) || $this->ed315_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3367,18942,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_ativo'))."','$this->ed315_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_arredondamedia"]) || $this->ed315_arredondamedia != "")
           $resac = db_query("insert into db_acount values($acount,3367,18943,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_arredondamedia'))."','$this->ed315_arredondamedia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_observacao"]) || $this->ed315_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3367,18944,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_observacao'))."','$this->ed315_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed315_ano"]) || $this->ed315_ano != "")
           $resac = db_query("insert into db_acount values($acount,3367,19734,'".AddSlashes(pg_result($resaco,$conresaco,'ed315_ano'))."','$this->ed315_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estrutura da Nota nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed315_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estrutura da Nota nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed315_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed315_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed315_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed315_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18940,'$ed315_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3367,18940,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3367,18977,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3367,18941,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_db_estrutura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3367,18942,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3367,18943,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_arredondamedia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3367,18944,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3367,19734,'','".AddSlashes(pg_result($resaco,$iresaco,'ed315_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avaliacaoestruturanota
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed315_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed315_sequencial = $ed315_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Estrutura da Nota nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed315_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Estrutura da Nota nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed315_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed315_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoestruturanota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed315_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from avaliacaoestruturanota ";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = avaliacaoestruturanota.ed315_db_estrutura";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = avaliacaoestruturanota.ed315_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed315_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturanota.ed315_sequencial = $ed315_sequencial ";
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
   function sql_query_file ( $ed315_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from avaliacaoestruturanota ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed315_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturanota.ed315_sequencial = $ed315_sequencial ";
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
   function sql_query_configuracao_escola($ed315_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

     $sql = "select ";
     if ($campos != "*" ) {

       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from avaliacaoestruturanota ";
     $sql .= "      inner join db_estrutura on db_estrutura.db77_codestrut             = avaliacaoestruturanota.ed315_db_estrutura";
     $sql .= "      left  join avaliacaoestruturaregra on ed318_avaliacaoestruturanota = ed315_sequencial";
     $sql .= "      left  join regraarredondamento     on ed316_sequencial             = ed318_regraarredondamento";
     $sql2 = "";
     if($dbwhere==""){
       if($ed315_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturanota.ed315_sequencial = $ed315_sequencial ";
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
?>