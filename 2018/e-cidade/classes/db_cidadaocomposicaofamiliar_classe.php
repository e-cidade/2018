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

//MODULO: social
//CLASSE DA ENTIDADE cidadaocomposicaofamiliar
class cl_cidadaocomposicaofamiliar { 
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
   var $as03_sequencial = 0; 
   var $as03_cidadao = 0; 
   var $as03_cidadao_seq = 0; 
   var $as03_tipofamiliar = 0; 
   var $as03_cidadaofamilia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 as03_sequencial = int4 = Código 
                 as03_cidadao = int4 = Cidadão 
                 as03_cidadao_seq = int4 = Código Cidadão 
                 as03_tipofamiliar = int4 = Tipo Familiar 
                 as03_cidadaofamilia = int4 = Código Família 
                 ";
   //funcao construtor da classe 
   function cl_cidadaocomposicaofamiliar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cidadaocomposicaofamiliar"); 
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
       $this->as03_sequencial = ($this->as03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as03_sequencial"]:$this->as03_sequencial);
       $this->as03_cidadao = ($this->as03_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["as03_cidadao"]:$this->as03_cidadao);
       $this->as03_cidadao_seq = ($this->as03_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["as03_cidadao_seq"]:$this->as03_cidadao_seq);
       $this->as03_tipofamiliar = ($this->as03_tipofamiliar == ""?@$GLOBALS["HTTP_POST_VARS"]["as03_tipofamiliar"]:$this->as03_tipofamiliar);
       $this->as03_cidadaofamilia = ($this->as03_cidadaofamilia == ""?@$GLOBALS["HTTP_POST_VARS"]["as03_cidadaofamilia"]:$this->as03_cidadaofamilia);
     }else{
       $this->as03_sequencial = ($this->as03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["as03_sequencial"]:$this->as03_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($as03_sequencial){ 
      $this->atualizacampos();
     if($this->as03_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "as03_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as03_cidadao_seq == null ){ 
       $this->erro_sql = " Campo Código Cidadão nao Informado.";
       $this->erro_campo = "as03_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as03_tipofamiliar == null ){ 
       $this->erro_sql = " Campo Tipo Familiar nao Informado.";
       $this->erro_campo = "as03_tipofamiliar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->as03_cidadaofamilia == null ){ 
       $this->erro_sql = " Campo Código Família nao Informado.";
       $this->erro_campo = "as03_cidadaofamilia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($as03_sequencial == "" || $as03_sequencial == null ){
       $result = db_query("select nextval('cidadaocomposicaofamiliar_as03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cidadaocomposicaofamiliar_as03_sequencial_seq do campo: as03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->as03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cidadaocomposicaofamiliar_as03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $as03_sequencial)){
         $this->erro_sql = " Campo as03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->as03_sequencial = $as03_sequencial; 
       }
     }
     if(($this->as03_sequencial == null) || ($this->as03_sequencial == "") ){ 
       $this->erro_sql = " Campo as03_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cidadaocomposicaofamiliar(
                                       as03_sequencial 
                                      ,as03_cidadao 
                                      ,as03_cidadao_seq 
                                      ,as03_tipofamiliar 
                                      ,as03_cidadaofamilia 
                       )
                values (
                                $this->as03_sequencial 
                               ,$this->as03_cidadao 
                               ,$this->as03_cidadao_seq 
                               ,$this->as03_tipofamiliar 
                               ,$this->as03_cidadaofamilia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cidadaocomposicaofamiliar ($this->as03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cidadaocomposicaofamiliar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cidadaocomposicaofamiliar ($this->as03_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     if (!isset($_SESSION["DB_usaAccount"])) {

       $resaco = $this->sql_record($this->sql_query_file($this->as03_sequencial));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19076,'$this->as03_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3393,19076,'','".AddSlashes(pg_result($resaco,0,'as03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3393,19077,'','".AddSlashes(pg_result($resaco,0,'as03_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3393,19095,'','".AddSlashes(pg_result($resaco,0,'as03_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3393,19078,'','".AddSlashes(pg_result($resaco,0,'as03_tipofamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3393,19080,'','".AddSlashes(pg_result($resaco,0,'as03_cidadaofamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($as03_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cidadaocomposicaofamiliar set ";
     $virgula = "";
     if(trim($this->as03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as03_sequencial"])){ 
       $sql  .= $virgula." as03_sequencial = $this->as03_sequencial ";
       $virgula = ",";
       if(trim($this->as03_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "as03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as03_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as03_cidadao"])){ 
       $sql  .= $virgula." as03_cidadao = $this->as03_cidadao ";
       $virgula = ",";
       if(trim($this->as03_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "as03_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as03_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as03_cidadao_seq"])){ 
       $sql  .= $virgula." as03_cidadao_seq = $this->as03_cidadao_seq ";
       $virgula = ",";
       if(trim($this->as03_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo Código Cidadão nao Informado.";
         $this->erro_campo = "as03_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as03_tipofamiliar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as03_tipofamiliar"])){ 
       $sql  .= $virgula." as03_tipofamiliar = $this->as03_tipofamiliar ";
       $virgula = ",";
       if(trim($this->as03_tipofamiliar) == null ){ 
         $this->erro_sql = " Campo Tipo Familiar nao Informado.";
         $this->erro_campo = "as03_tipofamiliar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->as03_cidadaofamilia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["as03_cidadaofamilia"])){ 
       $sql  .= $virgula." as03_cidadaofamilia = $this->as03_cidadaofamilia ";
       $virgula = ",";
       if(trim($this->as03_cidadaofamilia) == null ){ 
         $this->erro_sql = " Campo Código Família nao Informado.";
         $this->erro_campo = "as03_cidadaofamilia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($as03_sequencial!=null){
       $sql .= " as03_sequencial = $this->as03_sequencial";
     }
     if (!isset($_SESSION["DB_usaAccount"])) {

       $resaco = $this->sql_record($this->sql_query_file($this->as03_sequencial));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19076,'$this->as03_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as03_sequencial"]) || $this->as03_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3393,19076,'".AddSlashes(pg_result($resaco,$conresaco,'as03_sequencial'))."','$this->as03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as03_cidadao"]) || $this->as03_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3393,19077,'".AddSlashes(pg_result($resaco,$conresaco,'as03_cidadao'))."','$this->as03_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as03_cidadao_seq"]) || $this->as03_cidadao_seq != "")
             $resac = db_query("insert into db_acount values($acount,3393,19095,'".AddSlashes(pg_result($resaco,$conresaco,'as03_cidadao_seq'))."','$this->as03_cidadao_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as03_tipofamiliar"]) || $this->as03_tipofamiliar != "")
             $resac = db_query("insert into db_acount values($acount,3393,19078,'".AddSlashes(pg_result($resaco,$conresaco,'as03_tipofamiliar'))."','$this->as03_tipofamiliar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["as03_cidadaofamilia"]) || $this->as03_cidadaofamilia != "")
             $resac = db_query("insert into db_acount values($acount,3393,19080,'".AddSlashes(pg_result($resaco,$conresaco,'as03_cidadaofamilia'))."','$this->as03_cidadaofamilia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaocomposicaofamiliar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->as03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaocomposicaofamiliar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->as03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->as03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($as03_sequencial=null,$dbwhere=null) { 

     if (!isset($_SESSION["DB_usaAccount"])) {
       
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($as03_sequencial));
       }else{ 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19076,'$as03_sequencial','E')");
           $resac = db_query("insert into db_acount values($acount,3393,19076,'','".AddSlashes(pg_result($resaco,$iresaco,'as03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3393,19077,'','".AddSlashes(pg_result($resaco,$iresaco,'as03_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3393,19095,'','".AddSlashes(pg_result($resaco,$iresaco,'as03_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3393,19078,'','".AddSlashes(pg_result($resaco,$iresaco,'as03_tipofamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,3393,19080,'','".AddSlashes(pg_result($resaco,$iresaco,'as03_cidadaofamilia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cidadaocomposicaofamiliar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($as03_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " as03_sequencial = $as03_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cidadaocomposicaofamiliar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$as03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cidadaocomposicaofamiliar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$as03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$as03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cidadaocomposicaofamiliar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $as03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaocomposicaofamiliar ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cidadaocomposicaofamiliar.as03_cidadao and  cidadao.ov02_seq = cidadaocomposicaofamiliar.as03_cidadao_seq";
     $sql .= "      inner join tipofamiliar  on  tipofamiliar.z14_sequencial = cidadaocomposicaofamiliar.as03_tipofamiliar";
     $sql .= "      inner join cidadaofamilia  on  cidadaofamilia.as04_sequencial = cidadaocomposicaofamiliar.as03_cidadaofamilia";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql2 = "";
     if($dbwhere==""){
       if($as03_sequencial!=null ){
         $sql2 .= " where cidadaocomposicaofamiliar.as03_sequencial = $as03_sequencial "; 
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
   function sql_query_file ( $as03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cidadaocomposicaofamiliar ";
     $sql2 = "";
     if($dbwhere==""){
       if($as03_sequencial!=null ){
         $sql2 .= " where cidadaocomposicaofamiliar.as03_sequencial = $as03_sequencial "; 
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
   function sql_query_cadunico ( $as03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from cidadaocomposicaofamiliar ";
    $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = cidadaocomposicaofamiliar.as03_cidadao and  cidadao.ov02_seq = cidadaocomposicaofamiliar.as03_cidadao_seq";
    $sql .= "      inner join tipofamiliar  on  tipofamiliar.z14_sequencial = cidadaocomposicaofamiliar.as03_tipofamiliar";
    $sql .= "      inner join cidadaofamilia  on  cidadaofamilia.as04_sequencial = cidadaocomposicaofamiliar.as03_cidadaofamilia";
    $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
    $sql .= "      inner join cidadaocadastrounico  on  cidadaocadastrounico.as02_cidadao = cidadao.ov02_sequencial";
    $sql2 = "";
    if($dbwhere==""){
      if($as03_sequencial!=null ){
        $sql2 .= " where cidadaocomposicaofamiliar.as03_sequencial = $as03_sequencial ";
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
  
 function sql_query_tipo_cidadao ( $as03_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
   
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
    $sql .= " from cidadaocomposicaofamiliar ";
    $sql .= "      inner join tipofamiliar  on  tipofamiliar.z14_sequencial = cidadaocomposicaofamiliar.as03_tipofamiliar";
    $sql .= "      left  join cidadao  on  cidadao.ov02_sequencial = cidadaocomposicaofamiliar.as03_cidadao and  cidadao.ov02_seq = cidadaocomposicaofamiliar.as03_cidadao_seq";
    $sql .= "      left  join cidadaocadastrounico  on  cidadaocadastrounico.as02_cidadao = cidadao.ov02_sequencial";
    $sql2 = "";
    if($dbwhere==""){
      if($as03_sequencial!=null ){
        $sql2 .= " where cidadaocomposicaofamiliar.as03_sequencial = $as03_sequencial ";
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