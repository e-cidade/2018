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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhdirfgeracaodadospessoal
class cl_rhdirfgeracaodadospessoal { 
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
   var $rh96_sequencial = 0; 
   var $rh96_rhdirfgeracao = 0; 
   var $rh96_numcgm = 0; 
   var $rh96_regist = 0; 
   var $rh96_cpfcnpj = null; 
   var $rh96_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh96_sequencial = int4 = Sequencial 
                 rh96_rhdirfgeracao = int4 = Dirf 
                 rh96_numcgm = int4 = Numcgm 
                 rh96_regist = int4 = Regist 
                 rh96_cpfcnpj = varchar(50) = Cpf / Cnpj 
                 rh96_tipo = int4 = Tipo da geracao 
                 ";
   //funcao construtor da classe 
   function cl_rhdirfgeracaodadospessoal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdirfgeracaodadospessoal"); 
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
       $this->rh96_sequencial = ($this->rh96_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_sequencial"]:$this->rh96_sequencial);
       $this->rh96_rhdirfgeracao = ($this->rh96_rhdirfgeracao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_rhdirfgeracao"]:$this->rh96_rhdirfgeracao);
       $this->rh96_numcgm = ($this->rh96_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_numcgm"]:$this->rh96_numcgm);
       $this->rh96_regist = ($this->rh96_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_regist"]:$this->rh96_regist);
       $this->rh96_cpfcnpj = ($this->rh96_cpfcnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_cpfcnpj"]:$this->rh96_cpfcnpj);
       $this->rh96_tipo = ($this->rh96_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_tipo"]:$this->rh96_tipo);
     }else{
       $this->rh96_sequencial = ($this->rh96_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh96_sequencial"]:$this->rh96_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh96_sequencial){ 
      $this->atualizacampos();
     if($this->rh96_rhdirfgeracao == null ){ 
       $this->erro_sql = " Campo Dirf nao Informado.";
       $this->erro_campo = "rh96_rhdirfgeracao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh96_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "rh96_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh96_regist == null ){ 
       $this->erro_sql = " Campo Regist nao Informado.";
       $this->erro_campo = "rh96_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh96_cpfcnpj == null ){ 
       $this->erro_sql = " Campo Cpf / Cnpj nao Informado.";
       $this->erro_campo = "rh96_cpfcnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh96_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da geracao nao Informado.";
       $this->erro_campo = "rh96_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh96_sequencial == "" || $rh96_sequencial == null ){
       $result = db_query("select nextval('rhdirfgeracaodadospessoal_rh96_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdirfgeracaodadospessoal_rh96_sequencial_seq do campo: rh96_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh96_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdirfgeracaodadospessoal_rh96_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh96_sequencial)){
         $this->erro_sql = " Campo rh96_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh96_sequencial = $rh96_sequencial; 
       }
     }
     if(($this->rh96_sequencial == null) || ($this->rh96_sequencial == "") ){ 
       $this->erro_sql = " Campo rh96_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdirfgeracaodadospessoal(
                                       rh96_sequencial 
                                      ,rh96_rhdirfgeracao 
                                      ,rh96_numcgm 
                                      ,rh96_regist 
                                      ,rh96_cpfcnpj 
                                      ,rh96_tipo 
                       )
                values (
                                $this->rh96_sequencial 
                               ,$this->rh96_rhdirfgeracao 
                               ,$this->rh96_numcgm 
                               ,$this->rh96_regist 
                               ,'$this->rh96_cpfcnpj' 
                               ,$this->rh96_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhdirfgeracaodadospessoal ($this->rh96_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhdirfgeracaodadospessoal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhdirfgeracaodadospessoal ($this->rh96_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh96_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["ignoreAccount"])) {
       
       $resaco = $this->sql_record($this->sql_query_file($this->rh96_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17768,'$this->rh96_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3137,17768,'','".AddSlashes(pg_result($resaco,0,'rh96_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3137,17770,'','".AddSlashes(pg_result($resaco,0,'rh96_rhdirfgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3137,17772,'','".AddSlashes(pg_result($resaco,0,'rh96_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3137,17773,'','".AddSlashes(pg_result($resaco,0,'rh96_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3137,17774,'','".AddSlashes(pg_result($resaco,0,'rh96_cpfcnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3137,17782,'','".AddSlashes(pg_result($resaco,0,'rh96_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh96_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdirfgeracaodadospessoal set ";
     $virgula = "";
     if(trim($this->rh96_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh96_sequencial"])){ 
       $sql  .= $virgula." rh96_sequencial = $this->rh96_sequencial ";
       $virgula = ",";
       if(trim($this->rh96_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh96_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh96_rhdirfgeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh96_rhdirfgeracao"])){ 
       $sql  .= $virgula." rh96_rhdirfgeracao = $this->rh96_rhdirfgeracao ";
       $virgula = ",";
       if(trim($this->rh96_rhdirfgeracao) == null ){ 
         $this->erro_sql = " Campo Dirf nao Informado.";
         $this->erro_campo = "rh96_rhdirfgeracao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh96_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh96_numcgm"])){ 
       $sql  .= $virgula." rh96_numcgm = $this->rh96_numcgm ";
       $virgula = ",";
       if(trim($this->rh96_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "rh96_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh96_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh96_regist"])){ 
       $sql  .= $virgula." rh96_regist = $this->rh96_regist ";
       $virgula = ",";
       if(trim($this->rh96_regist) == null ){ 
         $this->erro_sql = " Campo Regist nao Informado.";
         $this->erro_campo = "rh96_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh96_cpfcnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh96_cpfcnpj"])){ 
       $sql  .= $virgula." rh96_cpfcnpj = '$this->rh96_cpfcnpj' ";
       $virgula = ",";
       if(trim($this->rh96_cpfcnpj) == null ){ 
         $this->erro_sql = " Campo Cpf / Cnpj nao Informado.";
         $this->erro_campo = "rh96_cpfcnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh96_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh96_tipo"])){ 
       $sql  .= $virgula." rh96_tipo = $this->rh96_tipo ";
       $virgula = ",";
       if(trim($this->rh96_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da geracao nao Informado.";
         $this->erro_campo = "rh96_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh96_sequencial!=null){
       $sql .= " rh96_sequencial = $this->rh96_sequencial";
     }
     if (!isset($_SESSION["ignoreAccount"])) {
     $resaco = $this->sql_record($this->sql_query_file($this->rh96_sequencial));
       if ($this->numrows>0) {
         
         for ($conresaco=0;$conresaco<$this->numrows;$conresaco++) {
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17768,'$this->rh96_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh96_sequencial"]) || $this->rh96_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3137,17768,'".AddSlashes(pg_result($resaco,$conresaco,'rh96_sequencial'))."','$this->rh96_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh96_rhdirfgeracao"]) || $this->rh96_rhdirfgeracao != "")
             $resac = db_query("insert into db_acount values($acount,3137,17770,'".AddSlashes(pg_result($resaco,$conresaco,'rh96_rhdirfgeracao'))."','$this->rh96_rhdirfgeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh96_numcgm"]) || $this->rh96_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,3137,17772,'".AddSlashes(pg_result($resaco,$conresaco,'rh96_numcgm'))."','$this->rh96_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh96_regist"]) || $this->rh96_regist != "")
             $resac = db_query("insert into db_acount values($acount,3137,17773,'".AddSlashes(pg_result($resaco,$conresaco,'rh96_regist'))."','$this->rh96_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh96_cpfcnpj"]) || $this->rh96_cpfcnpj != "")
             $resac = db_query("insert into db_acount values($acount,3137,17774,'".AddSlashes(pg_result($resaco,$conresaco,'rh96_cpfcnpj'))."','$this->rh96_cpfcnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh96_tipo"]) || $this->rh96_tipo != "")
             $resac = db_query("insert into db_acount values($acount,3137,17782,'".AddSlashes(pg_result($resaco,$conresaco,'rh96_tipo'))."','$this->rh96_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaodadospessoal nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh96_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaodadospessoal nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh96_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh96_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh96_sequencial=null,$dbwhere=null) {

     if (!isset($_SESSION["ignoreAccount"])) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($rh96_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17768,'$rh96_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3137,17768,'','".AddSlashes(pg_result($resaco,$iresaco,'rh96_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3137,17770,'','".AddSlashes(pg_result($resaco,$iresaco,'rh96_rhdirfgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3137,17772,'','".AddSlashes(pg_result($resaco,$iresaco,'rh96_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3137,17773,'','".AddSlashes(pg_result($resaco,$iresaco,'rh96_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3137,17774,'','".AddSlashes(pg_result($resaco,$iresaco,'rh96_cpfcnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3137,17782,'','".AddSlashes(pg_result($resaco,$iresaco,'rh96_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhdirfgeracaodadospessoal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh96_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh96_sequencial = $rh96_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhdirfgeracaodadospessoal nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh96_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhdirfgeracaodadospessoal nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh96_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh96_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdirfgeracaodadospessoal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh96_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdirfgeracaodadospessoal ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhdirfgeracaodadospessoal.rh96_numcgm";
     $sql .= "      inner join rhdirfgeracao  on  rhdirfgeracao.rh95_sequencial = rhdirfgeracaodadospessoal.rh96_rhdirfgeracao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhdirfgeracao.rh95_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($rh96_sequencial!=null ){
         $sql2 .= " where rhdirfgeracaodadospessoal.rh96_sequencial = $rh96_sequencial "; 
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
   function sql_query_file ( $rh96_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdirfgeracaodadospessoal ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh96_sequencial!=null ){
         $sql2 .= " where rhdirfgeracaodadospessoal.rh96_sequencial = $rh96_sequencial "; 
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