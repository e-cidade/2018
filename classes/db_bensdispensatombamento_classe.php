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

//MODULO: patrimonio
//CLASSE DA ENTIDADE bensdispensatombamento
class cl_bensdispensatombamento { 
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
   var $e139_sequencial = 0; 
   var $e139_empnotaitem = 0; 
   var $e139_matestoqueitem = 0; 
   var $e139_justificativa = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e139_sequencial = int4 = Codigo sequencial 
                 e139_empnotaitem = int4 = Item nota de empenho 
                 e139_matestoqueitem = int8 = Item da entrada da ordem de compra 
                 e139_justificativa = text = Justificativa 
                 ";
   //funcao construtor da classe 
   function cl_bensdispensatombamento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bensdispensatombamento"); 
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
       $this->e139_sequencial = ($this->e139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e139_sequencial"]:$this->e139_sequencial);
       $this->e139_empnotaitem = ($this->e139_empnotaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e139_empnotaitem"]:$this->e139_empnotaitem);
       $this->e139_matestoqueitem = ($this->e139_matestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["e139_matestoqueitem"]:$this->e139_matestoqueitem);
       $this->e139_justificativa = ($this->e139_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["e139_justificativa"]:$this->e139_justificativa);
     }else{
       $this->e139_sequencial = ($this->e139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e139_sequencial"]:$this->e139_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e139_sequencial){ 
      $this->atualizacampos();
     if($this->e139_empnotaitem == null ){ 
       $this->erro_sql = " Campo Item nota de empenho não informado.";
       $this->erro_campo = "e139_empnotaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e139_matestoqueitem == null ){ 
       $this->erro_sql = " Campo Item da entrada da ordem de compra não informado.";
       $this->erro_campo = "e139_matestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e139_justificativa == null ){ 
       $this->erro_sql = " Campo Justificativa não informado.";
       $this->erro_campo = "e139_justificativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e139_sequencial == "" || $e139_sequencial == null ){
       $result = db_query("select nextval('bensdispensatombamento_e139_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bensdispensatombamento_e139_sequencial_seq do campo: e139_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e139_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bensdispensatombamento_e139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e139_sequencial)){
         $this->erro_sql = " Campo e139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e139_sequencial = $e139_sequencial; 
       }
     }
     if(($this->e139_sequencial == null) || ($this->e139_sequencial == "") ){ 
       $this->erro_sql = " Campo e139_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bensdispensatombamento(
                                       e139_sequencial 
                                      ,e139_empnotaitem 
                                      ,e139_matestoqueitem 
                                      ,e139_justificativa 
                       )
                values (
                                $this->e139_sequencial 
                               ,$this->e139_empnotaitem 
                               ,$this->e139_matestoqueitem 
                               ,'$this->e139_justificativa' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dispensa de tombamento ($this->e139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dispensa de tombamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dispensa de tombamento ($this->e139_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e139_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20233,'$this->e139_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3634,20233,'','".AddSlashes(pg_result($resaco,0,'e139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3634,20234,'','".AddSlashes(pg_result($resaco,0,'e139_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3634,20235,'','".AddSlashes(pg_result($resaco,0,'e139_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3634,20236,'','".AddSlashes(pg_result($resaco,0,'e139_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e139_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bensdispensatombamento set ";
     $virgula = "";
     if(trim($this->e139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e139_sequencial"])){ 
       $sql  .= $virgula." e139_sequencial = $this->e139_sequencial ";
       $virgula = ",";
       if(trim($this->e139_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial não informado.";
         $this->erro_campo = "e139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e139_empnotaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e139_empnotaitem"])){ 
       $sql  .= $virgula." e139_empnotaitem = $this->e139_empnotaitem ";
       $virgula = ",";
       if(trim($this->e139_empnotaitem) == null ){ 
         $this->erro_sql = " Campo Item nota de empenho não informado.";
         $this->erro_campo = "e139_empnotaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e139_matestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e139_matestoqueitem"])){ 
       $sql  .= $virgula." e139_matestoqueitem = $this->e139_matestoqueitem ";
       $virgula = ",";
       if(trim($this->e139_matestoqueitem) == null ){ 
         $this->erro_sql = " Campo Item da entrada da ordem de compra não informado.";
         $this->erro_campo = "e139_matestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e139_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e139_justificativa"])){ 
       $sql  .= $virgula." e139_justificativa = '$this->e139_justificativa' ";
       $virgula = ",";
       if(trim($this->e139_justificativa) == null ){ 
         $this->erro_sql = " Campo Justificativa não informado.";
         $this->erro_campo = "e139_justificativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e139_sequencial!=null){
       $sql .= " e139_sequencial = $this->e139_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e139_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20233,'$this->e139_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e139_sequencial"]) || $this->e139_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3634,20233,'".AddSlashes(pg_result($resaco,$conresaco,'e139_sequencial'))."','$this->e139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e139_empnotaitem"]) || $this->e139_empnotaitem != "")
             $resac = db_query("insert into db_acount values($acount,3634,20234,'".AddSlashes(pg_result($resaco,$conresaco,'e139_empnotaitem'))."','$this->e139_empnotaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e139_matestoqueitem"]) || $this->e139_matestoqueitem != "")
             $resac = db_query("insert into db_acount values($acount,3634,20235,'".AddSlashes(pg_result($resaco,$conresaco,'e139_matestoqueitem'))."','$this->e139_matestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["e139_justificativa"]) || $this->e139_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,3634,20236,'".AddSlashes(pg_result($resaco,$conresaco,'e139_justificativa'))."','$this->e139_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dispensa de tombamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dispensa de tombamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e139_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($e139_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20233,'$e139_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3634,20233,'','".AddSlashes(pg_result($resaco,$iresaco,'e139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3634,20234,'','".AddSlashes(pg_result($resaco,$iresaco,'e139_empnotaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3634,20235,'','".AddSlashes(pg_result($resaco,$iresaco,'e139_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3634,20236,'','".AddSlashes(pg_result($resaco,$iresaco,'e139_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from bensdispensatombamento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e139_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e139_sequencial = $e139_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dispensa de tombamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dispensa de tombamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bensdispensatombamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensdispensatombamento ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = bensdispensatombamento.e139_matestoqueitem";
     $sql .= "      inner join empnotaitem  on  empnotaitem.e72_sequencial = bensdispensatombamento.e139_empnotaitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join empempitem  on  empempitem.e62_sequencial = empnotaitem.e72_empempitem";
     $sql .= "      inner join empnota  as a on   a.e69_codnota = empnotaitem.e72_codnota";
     $sql2 = "";
     if($dbwhere==""){
       if($e139_sequencial!=null ){
         $sql2 .= " where bensdispensatombamento.e139_sequencial = $e139_sequencial "; 
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
   function sql_query_file ( $e139_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bensdispensatombamento ";
     $sql2 = "";
     if($dbwhere==""){
       if($e139_sequencial!=null ){
         $sql2 .= " where bensdispensatombamento.e139_sequencial = $e139_sequencial "; 
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

  /**
   * Busca dados do item
   *
   * @param string $sCampos
   * @param string $sWhere
   * @access public
   * @return string
   */
  function sql_query_dadosItem($iCodigoDispensaTombamento = null, $sCampos = '*', $sOrdenacao = null, $sWhere = null) {

    $sSql  = "select $sCampos";
    $sSql .= " from bensdispensatombamento ";
    $sSql .= "      inner join matestoqueitem on matestoqueitem.m71_codlanc = bensdispensatombamento.e139_matestoqueitem ";
    $sSql .= "      inner join empnotaitem    on empnotaitem.e72_sequencial = bensdispensatombamento.e139_empnotaitem    ";
    $sSql .= "      inner join matestoque      on  matestoque.m70_codigo            = matestoqueitem.m71_codmatestoque";
    $sSql .= "      inner join empempitem      on  empempitem.e62_sequencial        = empnotaitem.e72_empempitem";
    $sSql .= "      inner join pcmater         on  pcmater.pc01_codmater  		       = e62_item";
    $sSql .= "      inner join empnota         on  empnota.e69_codnota              = empnotaitem.e72_codnota";
    $sSql .= "      left  join empnotaele      on  empnotaele.e70_codnota           = empnota.e69_codnota";
    $sSql .= "      inner join empempenho      on  empempenho.e60_numemp            = empnota.e69_numemp";
    $sSql .= "      left  join empnotaord      on  empnotaord.m72_codnota           = empnota.e69_codnota ";
    $sSql .= "      left  join matordemitem    on  matordemitem.m52_codordem        = empnotaord.m72_codordem ";

    if ( !empty($iCodigoDispensaTombamento) ) {
      $sSql .= " where e139_sequencial = $iCodigoDispensaTombamento";
    } elseif ( !empty($sWhere) ) {
      $sSql .= " where $sWhere";
    }

    if ( !empty($sOrdenacao) ) {
      $sSql .= " order by $sOrdenacao";
    }

    return $sSql;
  }

}
?>