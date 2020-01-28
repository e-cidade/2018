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

//MODULO: diversos
//CLASSE DA ENTIDADE diverimportaold
class cl_diverimportaold { 
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
   var $dv13_sequencial = 0; 
   var $dv13_diversos = 0; 
   var $dv13_numpre = 0; 
   var $dv13_numpar = 0; 
   var $dv13_receita = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 dv13_sequencial = int8 = Código 
                 dv13_diversos = int4 = Código do diversos 
                 dv13_numpre = int8 = Numpre 
                 dv13_numpar = int8 = Numpar 
                 dv13_receita = int4 = Receita 
                 ";
   //funcao construtor da classe 
   function cl_diverimportaold() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diverimportaold"); 
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
       $this->dv13_sequencial = ($this->dv13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv13_sequencial"]:$this->dv13_sequencial);
       $this->dv13_diversos = ($this->dv13_diversos == ""?@$GLOBALS["HTTP_POST_VARS"]["dv13_diversos"]:$this->dv13_diversos);
       $this->dv13_numpre = ($this->dv13_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["dv13_numpre"]:$this->dv13_numpre);
       $this->dv13_numpar = ($this->dv13_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["dv13_numpar"]:$this->dv13_numpar);
       $this->dv13_receita = ($this->dv13_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["dv13_receita"]:$this->dv13_receita);
     }else{
       $this->dv13_sequencial = ($this->dv13_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["dv13_sequencial"]:$this->dv13_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($dv13_sequencial){ 
      $this->atualizacampos();
     if($this->dv13_diversos == null ){ 
       $this->erro_sql = " Campo Código do diversos nao Informado.";
       $this->erro_campo = "dv13_diversos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv13_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "dv13_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv13_numpar == null ){ 
       $this->erro_sql = " Campo Numpar nao Informado.";
       $this->erro_campo = "dv13_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv13_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "dv13_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($dv13_sequencial == "" || $dv13_sequencial == null ){
       $result = db_query("select nextval('diverimportaold_dv13_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diverimportaold_dv13_sequencial_seq do campo: dv13_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->dv13_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diverimportaold_dv13_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $dv13_sequencial)){
         $this->erro_sql = " Campo dv13_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->dv13_sequencial = $dv13_sequencial; 
       }
     }
     if(($this->dv13_sequencial == null) || ($this->dv13_sequencial == "") ){ 
       $this->erro_sql = " Campo dv13_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diverimportaold(
                                       dv13_sequencial 
                                      ,dv13_diversos 
                                      ,dv13_numpre 
                                      ,dv13_numpar 
                                      ,dv13_receita 
                       )
                values (
                                $this->dv13_sequencial 
                               ,$this->dv13_diversos 
                               ,$this->dv13_numpre 
                               ,$this->dv13_numpar 
                               ,$this->dv13_receita 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "diverimportaold ($this->dv13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "diverimportaold já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "diverimportaold ($this->dv13_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv13_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->dv13_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18613,'$this->dv13_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3295,18613,'','".AddSlashes(pg_result($resaco,0,'dv13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3295,18614,'','".AddSlashes(pg_result($resaco,0,'dv13_diversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3295,18615,'','".AddSlashes(pg_result($resaco,0,'dv13_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3295,18616,'','".AddSlashes(pg_result($resaco,0,'dv13_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3295,18617,'','".AddSlashes(pg_result($resaco,0,'dv13_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($dv13_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update diverimportaold set ";
     $virgula = "";
     if(trim($this->dv13_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv13_sequencial"])){ 
       $sql  .= $virgula." dv13_sequencial = $this->dv13_sequencial ";
       $virgula = ",";
       if(trim($this->dv13_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "dv13_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv13_diversos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv13_diversos"])){ 
       $sql  .= $virgula." dv13_diversos = $this->dv13_diversos ";
       $virgula = ",";
       if(trim($this->dv13_diversos) == null ){ 
         $this->erro_sql = " Campo Código do diversos nao Informado.";
         $this->erro_campo = "dv13_diversos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv13_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv13_numpre"])){ 
       $sql  .= $virgula." dv13_numpre = $this->dv13_numpre ";
       $virgula = ",";
       if(trim($this->dv13_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "dv13_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv13_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv13_numpar"])){ 
       $sql  .= $virgula." dv13_numpar = $this->dv13_numpar ";
       $virgula = ",";
       if(trim($this->dv13_numpar) == null ){ 
         $this->erro_sql = " Campo Numpar nao Informado.";
         $this->erro_campo = "dv13_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv13_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv13_receita"])){ 
       $sql  .= $virgula." dv13_receita = $this->dv13_receita ";
       $virgula = ",";
       if(trim($this->dv13_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "dv13_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($dv13_sequencial!=null){
       $sql .= " dv13_sequencial = $this->dv13_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->dv13_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18613,'$this->dv13_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv13_sequencial"]) || $this->dv13_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3295,18613,'".AddSlashes(pg_result($resaco,$conresaco,'dv13_sequencial'))."','$this->dv13_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv13_diversos"]) || $this->dv13_diversos != "")
           $resac = db_query("insert into db_acount values($acount,3295,18614,'".AddSlashes(pg_result($resaco,$conresaco,'dv13_diversos'))."','$this->dv13_diversos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv13_numpre"]) || $this->dv13_numpre != "")
           $resac = db_query("insert into db_acount values($acount,3295,18615,'".AddSlashes(pg_result($resaco,$conresaco,'dv13_numpre'))."','$this->dv13_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv13_numpar"]) || $this->dv13_numpar != "")
           $resac = db_query("insert into db_acount values($acount,3295,18616,'".AddSlashes(pg_result($resaco,$conresaco,'dv13_numpar'))."','$this->dv13_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv13_receita"]) || $this->dv13_receita != "")
           $resac = db_query("insert into db_acount values($acount,3295,18617,'".AddSlashes(pg_result($resaco,$conresaco,'dv13_receita'))."','$this->dv13_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diverimportaold nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diverimportaold nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($dv13_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($dv13_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18613,'$dv13_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3295,18613,'','".AddSlashes(pg_result($resaco,$iresaco,'dv13_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3295,18614,'','".AddSlashes(pg_result($resaco,$iresaco,'dv13_diversos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3295,18615,'','".AddSlashes(pg_result($resaco,$iresaco,'dv13_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3295,18616,'','".AddSlashes(pg_result($resaco,$iresaco,'dv13_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3295,18617,'','".AddSlashes(pg_result($resaco,$iresaco,'dv13_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diverimportaold
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($dv13_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv13_sequencial = $dv13_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "diverimportaold nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv13_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "diverimportaold nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv13_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv13_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diverimportaold";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $dv13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diverimportaold ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = diverimportaold.dv13_receita";
     $sql .= "      inner join diversos  on  diversos.dv05_coddiver = diverimportaold.dv13_diversos";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = diversos.dv05_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = diversos.dv05_instit";
     $sql .= "      inner join procdiver  on  procdiver.dv09_procdiver = diversos.dv05_procdiver";
     $sql2 = "";
     if($dbwhere==""){
       if($dv13_sequencial!=null ){
         $sql2 .= " where diverimportaold.dv13_sequencial = $dv13_sequencial "; 
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
   function sql_query_file ( $dv13_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from diverimportaold ";
     $sql2 = "";
     if($dbwhere==""){
       if($dv13_sequencial!=null ){
         $sql2 .= " where diverimportaold.dv13_sequencial = $dv13_sequencial "; 
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