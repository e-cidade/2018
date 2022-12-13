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

//MODULO: custos
//CLASSE DA ENTIDADE custocriteriorateiobens
class cl_custocriteriorateiobens { 
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
   var $cc06_sequencial = 0; 
   var $cc06_custoplanoanaliticabens = 0; 
   var $cc06_custocriteriorateio = 0; 
   var $cc06_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cc06_sequencial = int4 = Sequêncial 
                 cc06_custoplanoanaliticabens = int4 = Custo plano analítico dos bens 
                 cc06_custocriteriorateio = int4 = Sequêncial 
                 cc06_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_custocriteriorateiobens() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("custocriteriorateiobens"); 
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
       $this->cc06_sequencial = ($this->cc06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc06_sequencial"]:$this->cc06_sequencial);
       $this->cc06_custoplanoanaliticabens = ($this->cc06_custoplanoanaliticabens == ""?@$GLOBALS["HTTP_POST_VARS"]["cc06_custoplanoanaliticabens"]:$this->cc06_custoplanoanaliticabens);
       $this->cc06_custocriteriorateio = ($this->cc06_custocriteriorateio == ""?@$GLOBALS["HTTP_POST_VARS"]["cc06_custocriteriorateio"]:$this->cc06_custocriteriorateio);
       $this->cc06_ativo = ($this->cc06_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["cc06_ativo"]:$this->cc06_ativo);
     }else{
       $this->cc06_sequencial = ($this->cc06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["cc06_sequencial"]:$this->cc06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($cc06_sequencial){ 
      $this->atualizacampos();
     if($this->cc06_custoplanoanaliticabens == null ){ 
       $this->erro_sql = " Campo Custo plano analítico dos bens nao Informado.";
       $this->erro_campo = "cc06_custoplanoanaliticabens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc06_custocriteriorateio == null ){ 
       $this->erro_sql = " Campo Sequêncial nao Informado.";
       $this->erro_campo = "cc06_custocriteriorateio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc06_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "cc06_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc06_sequencial == "" || $cc06_sequencial == null ){
       $result = db_query("select nextval('custocriteriorateiobens_cc06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: custocriteriorateiobens_cc06_sequencial_seq do campo: cc06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cc06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from custocriteriorateiobens_cc06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc06_sequencial)){
         $this->erro_sql = " Campo cc06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc06_sequencial = $cc06_sequencial; 
       }
     }
     if(($this->cc06_sequencial == null) || ($this->cc06_sequencial == "") ){ 
       $this->erro_sql = " Campo cc06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into custocriteriorateiobens(
                                       cc06_sequencial 
                                      ,cc06_custoplanoanaliticabens 
                                      ,cc06_custocriteriorateio 
                                      ,cc06_ativo 
                       )
                values (
                                $this->cc06_sequencial 
                               ,$this->cc06_custoplanoanaliticabens 
                               ,$this->cc06_custocriteriorateio 
                               ,'$this->cc06_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custo do critério rateio dos bens  ($this->cc06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custo do critério rateio dos bens  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custo do critério rateio dos bens  ($this->cc06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cc06_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12589,'$this->cc06_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2198,12589,'','".AddSlashes(pg_result($resaco,0,'cc06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2198,12590,'','".AddSlashes(pg_result($resaco,0,'cc06_custoplanoanaliticabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2198,12592,'','".AddSlashes(pg_result($resaco,0,'cc06_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2198,12593,'','".AddSlashes(pg_result($resaco,0,'cc06_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cc06_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update custocriteriorateiobens set ";
     $virgula = "";
     if(trim($this->cc06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc06_sequencial"])){ 
       $sql  .= $virgula." cc06_sequencial = $this->cc06_sequencial ";
       $virgula = ",";
       if(trim($this->cc06_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequêncial nao Informado.";
         $this->erro_campo = "cc06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc06_custoplanoanaliticabens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc06_custoplanoanaliticabens"])){ 
       $sql  .= $virgula." cc06_custoplanoanaliticabens = $this->cc06_custoplanoanaliticabens ";
       $virgula = ",";
       if(trim($this->cc06_custoplanoanaliticabens) == null ){ 
         $this->erro_sql = " Campo Custo plano analítico dos bens nao Informado.";
         $this->erro_campo = "cc06_custoplanoanaliticabens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc06_custocriteriorateio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc06_custocriteriorateio"])){ 
       $sql  .= $virgula." cc06_custocriteriorateio = $this->cc06_custocriteriorateio ";
       $virgula = ",";
       if(trim($this->cc06_custocriteriorateio) == null ){ 
         $this->erro_sql = " Campo Sequêncial nao Informado.";
         $this->erro_campo = "cc06_custocriteriorateio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc06_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc06_ativo"])){ 
       $sql  .= $virgula." cc06_ativo = '$this->cc06_ativo' ";
       $virgula = ",";
       if(trim($this->cc06_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "cc06_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cc06_sequencial!=null){
       $sql .= " cc06_sequencial = $this->cc06_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cc06_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12589,'$this->cc06_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc06_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2198,12589,'".AddSlashes(pg_result($resaco,$conresaco,'cc06_sequencial'))."','$this->cc06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc06_custoplanoanaliticabens"]))
           $resac = db_query("insert into db_acount values($acount,2198,12590,'".AddSlashes(pg_result($resaco,$conresaco,'cc06_custoplanoanaliticabens'))."','$this->cc06_custoplanoanaliticabens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc06_custocriteriorateio"]))
           $resac = db_query("insert into db_acount values($acount,2198,12592,'".AddSlashes(pg_result($resaco,$conresaco,'cc06_custocriteriorateio'))."','$this->cc06_custocriteriorateio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cc06_ativo"]))
           $resac = db_query("insert into db_acount values($acount,2198,12593,'".AddSlashes(pg_result($resaco,$conresaco,'cc06_ativo'))."','$this->cc06_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo do critério rateio dos bens  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo do critério rateio dos bens  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cc06_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cc06_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12589,'$cc06_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2198,12589,'','".AddSlashes(pg_result($resaco,$iresaco,'cc06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2198,12590,'','".AddSlashes(pg_result($resaco,$iresaco,'cc06_custoplanoanaliticabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2198,12592,'','".AddSlashes(pg_result($resaco,$iresaco,'cc06_custocriteriorateio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2198,12593,'','".AddSlashes(pg_result($resaco,$iresaco,'cc06_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from custocriteriorateiobens
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cc06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cc06_sequencial = $cc06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custo do critério rateio dos bens  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custo do critério rateio dos bens  nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:custocriteriorateiobens";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cc06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custocriteriorateiobens ";
     $sql .= "      inner join custoplanoanaliticabens  on  custoplanoanaliticabens.cc05_sequencial = custocriteriorateiobens.cc06_custoplanoanaliticabens";
     $sql .= "      inner join custocriteriorateio  on  custocriteriorateio.cc08_sequencial = custocriteriorateiobens.cc06_custocriteriorateio";
     $sql .= "      inner join bens  on  bens.t52_bem = custoplanoanaliticabens.cc05_bens";
     $sql .= "      inner join custoplanoanalitica  on  custoplanoanalitica.cc04_sequencial = custoplanoanaliticabens.cc05_custoplanoanalitica";
     $sql .= "      inner join db_config  on  db_config.codigo = custocriteriorateio.cc08_instit";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = custocriteriorateio.cc08_matunid";
     $sql2 = "";
     if($dbwhere==""){
       if($cc06_sequencial!=null ){
         $sql2 .= " where custocriteriorateiobens.cc06_sequencial = $cc06_sequencial "; 
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
   function sql_query_file ( $cc06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from custocriteriorateiobens ";
     $sql2 = "";
     if($dbwhere==""){
       if($cc06_sequencial!=null ){
         $sql2 .= " where custocriteriorateiobens.cc06_sequencial = $cc06_sequencial "; 
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