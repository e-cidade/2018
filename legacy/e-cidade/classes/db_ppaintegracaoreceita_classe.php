<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE ppaintegracaoreceita
class cl_ppaintegracaoreceita { 
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
   var $o122_sequencial = 0; 
   var $o122_ppaintegracao = 0; 
   var $o122_codrec = 0; 
   var $o122_anousu = 0; 
   var $o122_ppaestimativareceita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o122_sequencial = int4 = Código Sequencial 
                 o122_ppaintegracao = int4 = Código da Integração 
                 o122_codrec = int4 = Código da Receita 
                 o122_anousu = int4 = Ano da Receita 
                 o122_ppaestimativareceita = int4 = Código da Estimativa 
                 ";
   //funcao construtor da classe 
   function cl_ppaintegracaoreceita() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ppaintegracaoreceita"); 
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
       $this->o122_sequencial = ($this->o122_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o122_sequencial"]:$this->o122_sequencial);
       $this->o122_ppaintegracao = ($this->o122_ppaintegracao == ""?@$GLOBALS["HTTP_POST_VARS"]["o122_ppaintegracao"]:$this->o122_ppaintegracao);
       $this->o122_codrec = ($this->o122_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["o122_codrec"]:$this->o122_codrec);
       $this->o122_anousu = ($this->o122_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o122_anousu"]:$this->o122_anousu);
       $this->o122_ppaestimativareceita = ($this->o122_ppaestimativareceita == ""?@$GLOBALS["HTTP_POST_VARS"]["o122_ppaestimativareceita"]:$this->o122_ppaestimativareceita);
     }else{
       $this->o122_sequencial = ($this->o122_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o122_sequencial"]:$this->o122_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o122_sequencial){ 
      $this->atualizacampos();
     if($this->o122_ppaintegracao == null ){ 
       $this->erro_sql = " Campo Código da Integração nao Informado.";
       $this->erro_campo = "o122_ppaintegracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o122_codrec == null ){ 
       $this->erro_sql = " Campo Código da Receita nao Informado.";
       $this->erro_campo = "o122_codrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o122_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Receita nao Informado.";
       $this->erro_campo = "o122_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o122_ppaestimativareceita == null ){ 
       $this->erro_sql = " Campo Código da Estimativa nao Informado.";
       $this->erro_campo = "o122_ppaestimativareceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o122_sequencial == "" || $o122_sequencial == null ){
       $result = db_query("select nextval('ppaintegracaoreceita_o122_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ppaintegracaoreceita_o122_sequencial_seq do campo: o122_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o122_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from ppaintegracaoreceita_o122_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o122_sequencial)){
         $this->erro_sql = " Campo o122_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o122_sequencial = $o122_sequencial; 
       }
     }
     if(($this->o122_sequencial == null) || ($this->o122_sequencial == "") ){ 
       $this->erro_sql = " Campo o122_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ppaintegracaoreceita(
                                       o122_sequencial 
                                      ,o122_ppaintegracao 
                                      ,o122_codrec 
                                      ,o122_anousu 
                                      ,o122_ppaestimativareceita 
                       )
                values (
                                $this->o122_sequencial 
                               ,$this->o122_ppaintegracao 
                               ,$this->o122_codrec 
                               ,$this->o122_anousu 
                               ,$this->o122_ppaestimativareceita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Integracao da receitas do ppa com orcamento ($this->o122_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Integracao da receitas do ppa com orcamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Integracao da receitas do ppa com orcamento ($this->o122_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o122_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o122_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14515,'$this->o122_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2558,14515,'','".AddSlashes(pg_result($resaco,0,'o122_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2558,14516,'','".AddSlashes(pg_result($resaco,0,'o122_ppaintegracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2558,14517,'','".AddSlashes(pg_result($resaco,0,'o122_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2558,14518,'','".AddSlashes(pg_result($resaco,0,'o122_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2558,14519,'','".AddSlashes(pg_result($resaco,0,'o122_ppaestimativareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o122_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update ppaintegracaoreceita set ";
     $virgula = "";
     if(trim($this->o122_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o122_sequencial"])){ 
       $sql  .= $virgula." o122_sequencial = $this->o122_sequencial ";
       $virgula = ",";
       if(trim($this->o122_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "o122_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o122_ppaintegracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o122_ppaintegracao"])){ 
       $sql  .= $virgula." o122_ppaintegracao = $this->o122_ppaintegracao ";
       $virgula = ",";
       if(trim($this->o122_ppaintegracao) == null ){ 
         $this->erro_sql = " Campo Código da Integração nao Informado.";
         $this->erro_campo = "o122_ppaintegracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o122_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o122_codrec"])){ 
       $sql  .= $virgula." o122_codrec = $this->o122_codrec ";
       $virgula = ",";
       if(trim($this->o122_codrec) == null ){ 
         $this->erro_sql = " Campo Código da Receita nao Informado.";
         $this->erro_campo = "o122_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o122_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o122_anousu"])){ 
       $sql  .= $virgula." o122_anousu = $this->o122_anousu ";
       $virgula = ",";
       if(trim($this->o122_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Receita nao Informado.";
         $this->erro_campo = "o122_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o122_ppaestimativareceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o122_ppaestimativareceita"])){ 
       $sql  .= $virgula." o122_ppaestimativareceita = $this->o122_ppaestimativareceita ";
       $virgula = ",";
       if(trim($this->o122_ppaestimativareceita) == null ){ 
         $this->erro_sql = " Campo Código da Estimativa nao Informado.";
         $this->erro_campo = "o122_ppaestimativareceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o122_sequencial!=null){
       $sql .= " o122_sequencial = $this->o122_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o122_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14515,'$this->o122_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o122_sequencial"]) || $this->o122_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2558,14515,'".AddSlashes(pg_result($resaco,$conresaco,'o122_sequencial'))."','$this->o122_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o122_ppaintegracao"]) || $this->o122_ppaintegracao != "")
           $resac = db_query("insert into db_acount values($acount,2558,14516,'".AddSlashes(pg_result($resaco,$conresaco,'o122_ppaintegracao'))."','$this->o122_ppaintegracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o122_codrec"]) || $this->o122_codrec != "")
           $resac = db_query("insert into db_acount values($acount,2558,14517,'".AddSlashes(pg_result($resaco,$conresaco,'o122_codrec'))."','$this->o122_codrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o122_anousu"]) || $this->o122_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2558,14518,'".AddSlashes(pg_result($resaco,$conresaco,'o122_anousu'))."','$this->o122_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o122_ppaestimativareceita"]) || $this->o122_ppaestimativareceita != "")
           $resac = db_query("insert into db_acount values($acount,2558,14519,'".AddSlashes(pg_result($resaco,$conresaco,'o122_ppaestimativareceita'))."','$this->o122_ppaestimativareceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integracao da receitas do ppa com orcamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o122_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Integracao da receitas do ppa com orcamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o122_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o122_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o122_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o122_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14515,'$o122_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2558,14515,'','".AddSlashes(pg_result($resaco,$iresaco,'o122_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2558,14516,'','".AddSlashes(pg_result($resaco,$iresaco,'o122_ppaintegracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2558,14517,'','".AddSlashes(pg_result($resaco,$iresaco,'o122_codrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2558,14518,'','".AddSlashes(pg_result($resaco,$iresaco,'o122_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2558,14519,'','".AddSlashes(pg_result($resaco,$iresaco,'o122_ppaestimativareceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ppaintegracaoreceita
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o122_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o122_sequencial = $o122_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Integracao da receitas do ppa com orcamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o122_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Integracao da receitas do ppa com orcamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o122_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o122_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:ppaintegracaoreceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o122_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaintegracaoreceita ";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_anousu = ppaintegracaoreceita.o122_anousu and  orcreceita.o70_codrec = ppaintegracaoreceita.o122_codrec";
     $sql .= "      inner join ppaestimativareceita  on  ppaestimativareceita.o06_sequencial = ppaintegracaoreceita.o122_ppaestimativareceita";
     $sql .= "      inner join ppaintegracao  on  ppaintegracao.o123_sequencial = ppaintegracaoreceita.o122_ppaintegracao";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and  orcfontes.o57_anousu = orcreceita.o70_anousu";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
     $sql .= "      inner join orcfontes  as a on   a.o57_codfon = ppaestimativareceita.o06_codrec and   a.o57_anousu = ppaestimativareceita.o06_anousu";
     $sql .= "      inner join concarpeculiar  as b on   b.c58_sequencial = ppaestimativareceita.o06_concarpeculiar";
     $sql .= "      inner join ppaestimativa  as c on   c.o05_sequencial = ppaestimativareceita.o06_ppaestimativa";
     $sql .= "      inner join ppaversao  on  ppaversao.o119_sequencial = ppaestimativareceita.o06_ppaversao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = ppaintegracao.o123_idusuario";
     $sql .= "      inner join ppaversao  as d on   d.o119_sequencial = ppaintegracao.o123_ppaversao";
     $sql2 = "";
     if($dbwhere==""){
       if($o122_sequencial!=null ){
         $sql2 .= " where ppaintegracaoreceita.o122_sequencial = $o122_sequencial "; 
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
   function sql_query_file ( $o122_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from ppaintegracaoreceita ";
     $sql2 = "";
     if($dbwhere==""){
       if($o122_sequencial!=null ){
         $sql2 .= " where ppaintegracaoreceita.o122_sequencial = $o122_sequencial "; 
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