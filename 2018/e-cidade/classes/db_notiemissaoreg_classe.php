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

//MODULO: notificacoes
//CLASSE DA ENTIDADE notiemissaoreg
class cl_notiemissaoreg { 
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
   var $k137_sequencial = 0; 
   var $k137_notiemissao = 0; 
   var $k137_notificacao = 0; 
   var $k137_numpre = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k137_sequencial = int4 = Sequencial 
                 k137_notiemissao = int4 = Código da Emissão 
                 k137_notificacao = int4 = Notificação 
                 k137_numpre = int4 = Numpre 
                 ";
   //funcao construtor da classe 
   function cl_notiemissaoreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notiemissaoreg"); 
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
       $this->k137_sequencial = ($this->k137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k137_sequencial"]:$this->k137_sequencial);
       $this->k137_notiemissao = ($this->k137_notiemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["k137_notiemissao"]:$this->k137_notiemissao);
       $this->k137_notificacao = ($this->k137_notificacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k137_notificacao"]:$this->k137_notificacao);
       $this->k137_numpre = ($this->k137_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k137_numpre"]:$this->k137_numpre);
     }else{
       $this->k137_sequencial = ($this->k137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k137_sequencial"]:$this->k137_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k137_sequencial){ 
      $this->atualizacampos();
     if($this->k137_notiemissao == null ){ 
       $this->erro_sql = " Campo Código da Emissão nao Informado.";
       $this->erro_campo = "k137_notiemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k137_notificacao == null ){ 
       $this->erro_sql = " Campo Notificação nao Informado.";
       $this->erro_campo = "k137_notificacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k137_numpre == null ){ 
       $this->k137_numpre = "0";
     }
     if($k137_sequencial == "" || $k137_sequencial == null ){
       $result = db_query("select nextval('notiemissaoreg_k137_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notiemissaoreg_k137_sequencial_seq do campo: k137_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k137_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notiemissaoreg_k137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k137_sequencial)){
         $this->erro_sql = " Campo k137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k137_sequencial = $k137_sequencial; 
       }
     }
     if(($this->k137_sequencial == null) || ($this->k137_sequencial == "") ){ 
       $this->erro_sql = " Campo k137_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notiemissaoreg(
                                       k137_sequencial 
                                      ,k137_notiemissao 
                                      ,k137_notificacao 
                                      ,k137_numpre 
                       )
                values (
                                $this->k137_sequencial 
                               ,$this->k137_notiemissao 
                               ,$this->k137_notificacao 
                               ,$this->k137_numpre 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros de Notificações da Emissão
 ($this->k137_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros de Notificações da Emissão
 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros de Notificações da Emissão
 ($this->k137_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k137_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18546,'$this->k137_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3283,18546,'','".AddSlashes(pg_result($resaco,0,'k137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3283,18553,'','".AddSlashes(pg_result($resaco,0,'k137_notiemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3283,18552,'','".AddSlashes(pg_result($resaco,0,'k137_notificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3283,18554,'','".AddSlashes(pg_result($resaco,0,'k137_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k137_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update notiemissaoreg set ";
     $virgula = "";
     if(trim($this->k137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k137_sequencial"])){ 
       $sql  .= $virgula." k137_sequencial = $this->k137_sequencial ";
       $virgula = ",";
       if(trim($this->k137_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k137_notiemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k137_notiemissao"])){ 
       $sql  .= $virgula." k137_notiemissao = $this->k137_notiemissao ";
       $virgula = ",";
       if(trim($this->k137_notiemissao) == null ){ 
         $this->erro_sql = " Campo Código da Emissão nao Informado.";
         $this->erro_campo = "k137_notiemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k137_notificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k137_notificacao"])){ 
       $sql  .= $virgula." k137_notificacao = $this->k137_notificacao ";
       $virgula = ",";
       if(trim($this->k137_notificacao) == null ){ 
         $this->erro_sql = " Campo Notificação nao Informado.";
         $this->erro_campo = "k137_notificacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k137_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k137_numpre"])){ 
        if(trim($this->k137_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k137_numpre"])){ 
           $this->k137_numpre = "0" ; 
        } 
       $sql  .= $virgula." k137_numpre = $this->k137_numpre ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k137_sequencial!=null){
       $sql .= " k137_sequencial = $this->k137_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k137_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18546,'$this->k137_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k137_sequencial"]) || $this->k137_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3283,18546,'".AddSlashes(pg_result($resaco,$conresaco,'k137_sequencial'))."','$this->k137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k137_notiemissao"]) || $this->k137_notiemissao != "")
           $resac = db_query("insert into db_acount values($acount,3283,18553,'".AddSlashes(pg_result($resaco,$conresaco,'k137_notiemissao'))."','$this->k137_notiemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k137_notificacao"]) || $this->k137_notificacao != "")
           $resac = db_query("insert into db_acount values($acount,3283,18552,'".AddSlashes(pg_result($resaco,$conresaco,'k137_notificacao'))."','$this->k137_notificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k137_numpre"]) || $this->k137_numpre != "")
           $resac = db_query("insert into db_acount values($acount,3283,18554,'".AddSlashes(pg_result($resaco,$conresaco,'k137_numpre'))."','$this->k137_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros de Notificações da Emissão
 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros de Notificações da Emissão
 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k137_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k137_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18546,'$k137_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3283,18546,'','".AddSlashes(pg_result($resaco,$iresaco,'k137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3283,18553,'','".AddSlashes(pg_result($resaco,$iresaco,'k137_notiemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3283,18552,'','".AddSlashes(pg_result($resaco,$iresaco,'k137_notificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3283,18554,'','".AddSlashes(pg_result($resaco,$iresaco,'k137_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notiemissaoreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k137_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k137_sequencial = $k137_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros de Notificações da Emissão
 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros de Notificações da Emissão
 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:notiemissaoreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notiemissaoreg ";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = notiemissaoreg.k137_notificacao";
     $sql .= "      inner join notiemissao  on  notiemissao.k136_sequencial = notiemissaoreg.k137_notiemissao";
     $sql .= "      inner join db_config  on  db_config.codigo = notificacao.k50_instit";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql .= "      inner join notificatipogeracao  on  notificatipogeracao.k135_sequencial = notiemissao.k136_notificatipogeracao";
     $sql2 = "";
     if($dbwhere==""){
       if($k137_sequencial!=null ){
         $sql2 .= " where notiemissaoreg.k137_sequencial = $k137_sequencial "; 
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
   function sql_query_file ( $k137_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notiemissaoreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($k137_sequencial!=null ){
         $sql2 .= " where notiemissaoreg.k137_sequencial = $k137_sequencial "; 
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