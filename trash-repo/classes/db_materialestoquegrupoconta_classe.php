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

//MODULO: material
//CLASSE DA ENTIDADE materialestoquegrupoconta
class cl_materialestoquegrupoconta { 
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
   var $m66_sequencial = 0; 
   var $m66_materialestoquegrupo = 0; 
   var $m66_codcon = 0; 
   var $m66_anousu = 0; 
   var $m66_codconvpd = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m66_sequencial = int4 = Código Sequencial 
                 m66_materialestoquegrupo = int4 = Código do Grupo 
                 m66_codcon = int4 = Código da conta do plano de contas 
                 m66_anousu = int4 = Ano da Conta 
                 m66_codconvpd = int4 = Conta VPD 
                 ";
   //funcao construtor da classe 
   function cl_materialestoquegrupoconta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("materialestoquegrupoconta"); 
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
       $this->m66_sequencial = ($this->m66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m66_sequencial"]:$this->m66_sequencial);
       $this->m66_materialestoquegrupo = ($this->m66_materialestoquegrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["m66_materialestoquegrupo"]:$this->m66_materialestoquegrupo);
       $this->m66_codcon = ($this->m66_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["m66_codcon"]:$this->m66_codcon);
       $this->m66_anousu = ($this->m66_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["m66_anousu"]:$this->m66_anousu);
       $this->m66_codconvpd = ($this->m66_codconvpd == ""?@$GLOBALS["HTTP_POST_VARS"]["m66_codconvpd"]:$this->m66_codconvpd);
     }else{
       $this->m66_sequencial = ($this->m66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m66_sequencial"]:$this->m66_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m66_sequencial){ 
      $this->atualizacampos();
     if($this->m66_materialestoquegrupo == null ){ 
       $this->erro_sql = " Campo Código do Grupo nao Informado.";
       $this->erro_campo = "m66_materialestoquegrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m66_codcon == null ){ 
       $this->erro_sql = " Campo Código da conta do plano de contas nao Informado.";
       $this->erro_campo = "m66_codcon";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m66_anousu == null ){ 
       $this->erro_sql = " Campo Ano da Conta nao Informado.";
       $this->erro_campo = "m66_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m66_codconvpd == null ){ 
       $this->erro_sql = " Campo Conta VPD nao Informado.";
       $this->erro_campo = "m66_codconvpd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m66_sequencial == "" || $m66_sequencial == null ){
       $result = db_query("select nextval('materialestoquegrupoconta_m66_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: materialestoquegrupoconta_m66_sequencial_seq do campo: m66_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m66_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from materialestoquegrupoconta_m66_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m66_sequencial)){
         $this->erro_sql = " Campo m66_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m66_sequencial = $m66_sequencial; 
       }
     }
     if(($this->m66_sequencial == null) || ($this->m66_sequencial == "") ){ 
       $this->erro_sql = " Campo m66_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into materialestoquegrupoconta(
                                       m66_sequencial 
                                      ,m66_materialestoquegrupo 
                                      ,m66_codcon 
                                      ,m66_anousu 
                                      ,m66_codconvpd 
                       )
                values (
                                $this->m66_sequencial 
                               ,$this->m66_materialestoquegrupo 
                               ,$this->m66_codcon 
                               ,$this->m66_anousu 
                               ,$this->m66_codconvpd 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Conta Contabil do Grupo do material ($this->m66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Conta Contabil do Grupo do material já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Conta Contabil do Grupo do material ($this->m66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m66_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m66_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17972,'$this->m66_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3175,17972,'','".AddSlashes(pg_result($resaco,0,'m66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3175,17973,'','".AddSlashes(pg_result($resaco,0,'m66_materialestoquegrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3175,17974,'','".AddSlashes(pg_result($resaco,0,'m66_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3175,17975,'','".AddSlashes(pg_result($resaco,0,'m66_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3175,19923,'','".AddSlashes(pg_result($resaco,0,'m66_codconvpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m66_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update materialestoquegrupoconta set ";
     $virgula = "";
     if(trim($this->m66_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m66_sequencial"])){ 
       $sql  .= $virgula." m66_sequencial = $this->m66_sequencial ";
       $virgula = ",";
       if(trim($this->m66_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "m66_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m66_materialestoquegrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m66_materialestoquegrupo"])){ 
       $sql  .= $virgula." m66_materialestoquegrupo = $this->m66_materialestoquegrupo ";
       $virgula = ",";
       if(trim($this->m66_materialestoquegrupo) == null ){ 
         $this->erro_sql = " Campo Código do Grupo nao Informado.";
         $this->erro_campo = "m66_materialestoquegrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m66_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m66_codcon"])){ 
       $sql  .= $virgula." m66_codcon = $this->m66_codcon ";
       $virgula = ",";
       if(trim($this->m66_codcon) == null ){ 
         $this->erro_sql = " Campo Código da conta do plano de contas nao Informado.";
         $this->erro_campo = "m66_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m66_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m66_anousu"])){ 
       $sql  .= $virgula." m66_anousu = $this->m66_anousu ";
       $virgula = ",";
       if(trim($this->m66_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da Conta nao Informado.";
         $this->erro_campo = "m66_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m66_codconvpd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m66_codconvpd"])){ 
       $sql  .= $virgula." m66_codconvpd = $this->m66_codconvpd ";
       $virgula = ",";
       if(trim($this->m66_codconvpd) == null ){ 
         $this->erro_sql = " Campo Conta VPD nao Informado.";
         $this->erro_campo = "m66_codconvpd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m66_sequencial!=null){
       $sql .= " m66_sequencial = $this->m66_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m66_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17972,'$this->m66_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m66_sequencial"]) || $this->m66_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3175,17972,'".AddSlashes(pg_result($resaco,$conresaco,'m66_sequencial'))."','$this->m66_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m66_materialestoquegrupo"]) || $this->m66_materialestoquegrupo != "")
             $resac = db_query("insert into db_acount values($acount,3175,17973,'".AddSlashes(pg_result($resaco,$conresaco,'m66_materialestoquegrupo'))."','$this->m66_materialestoquegrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m66_codcon"]) || $this->m66_codcon != "")
             $resac = db_query("insert into db_acount values($acount,3175,17974,'".AddSlashes(pg_result($resaco,$conresaco,'m66_codcon'))."','$this->m66_codcon',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m66_anousu"]) || $this->m66_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3175,17975,'".AddSlashes(pg_result($resaco,$conresaco,'m66_anousu'))."','$this->m66_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["m66_codconvpd"]) || $this->m66_codconvpd != "")
             $resac = db_query("insert into db_acount values($acount,3175,19923,'".AddSlashes(pg_result($resaco,$conresaco,'m66_codconvpd'))."','$this->m66_codconvpd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conta Contabil do Grupo do material nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conta Contabil do Grupo do material nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m66_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($m66_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17972,'$m66_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3175,17972,'','".AddSlashes(pg_result($resaco,$iresaco,'m66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3175,17973,'','".AddSlashes(pg_result($resaco,$iresaco,'m66_materialestoquegrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3175,17974,'','".AddSlashes(pg_result($resaco,$iresaco,'m66_codcon'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3175,17975,'','".AddSlashes(pg_result($resaco,$iresaco,'m66_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3175,19923,'','".AddSlashes(pg_result($resaco,$iresaco,'m66_codconvpd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from materialestoquegrupoconta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m66_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m66_sequencial = $m66_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Conta Contabil do Grupo do material nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Conta Contabil do Grupo do material nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m66_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:materialestoquegrupoconta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from materialestoquegrupoconta ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = materialestoquegrupoconta.m66_codcon and  conplano.c60_anousu = materialestoquegrupoconta.m66_anousu";
     $sql .= "      inner join materialestoquegrupo  on  materialestoquegrupo.m65_sequencial = materialestoquegrupoconta.m66_materialestoquegrupo";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = materialestoquegrupo.m65_db_estruturavalor";
     $sql2 = "";
     if($dbwhere==""){
       if($m66_sequencial!=null ){
         $sql2 .= " where materialestoquegrupoconta.m66_sequencial = $m66_sequencial "; 
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
   function sql_query_file ( $m66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from materialestoquegrupoconta ";
     $sql2 = "";
     if($dbwhere==""){
       if($m66_sequencial!=null ){
         $sql2 .= " where materialestoquegrupoconta.m66_sequencial = $m66_sequencial "; 
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