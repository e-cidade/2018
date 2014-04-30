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

//MODULO: material
//CLASSE DA ENTIDADE matordemitem
class cl_matordemitem { 
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
   var $m52_codordem = 0; 
   var $m52_numemp = 0; 
   var $m52_sequen = 0; 
   var $m52_quant = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m52_codordem = int8 = Código da ordem de compra 
                 m52_numemp = int4 = Número 
                 m52_sequen = int4 = Sequencia 
                 m52_quant = float8 = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_matordemitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matordemitem"); 
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
       $this->m52_codordem = ($this->m52_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_codordem"]:$this->m52_codordem);
       $this->m52_numemp = ($this->m52_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_numemp"]:$this->m52_numemp);
       $this->m52_sequen = ($this->m52_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_sequen"]:$this->m52_sequen);
       $this->m52_quant = ($this->m52_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_quant"]:$this->m52_quant);
     }else{
       $this->m52_codordem = ($this->m52_codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_codordem"]:$this->m52_codordem);
       $this->m52_numemp = ($this->m52_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_numemp"]:$this->m52_numemp);
       $this->m52_sequen = ($this->m52_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["m52_sequen"]:$this->m52_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($m52_codordem,$m52_numemp,$m52_sequen){ 
      $this->atualizacampos();
     if($this->m52_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "m52_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->m52_codordem = $m52_codordem; 
       $this->m52_numemp = $m52_numemp; 
       $this->m52_sequen = $m52_sequen; 
     if(($this->m52_codordem == null) || ($this->m52_codordem == "") ){ 
       $this->erro_sql = " Campo m52_codordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m52_numemp == null) || ($this->m52_numemp == "") ){ 
       $this->erro_sql = " Campo m52_numemp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m52_sequen == null) || ($this->m52_sequen == "") ){ 
       $this->erro_sql = " Campo m52_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matordemitem(
                                       m52_codordem 
                                      ,m52_numemp 
                                      ,m52_sequen 
                                      ,m52_quant 
                       )
                values (
                                $this->m52_codordem 
                               ,$this->m52_numemp 
                               ,$this->m52_sequen 
                               ,$this->m52_quant 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da ordem de compra ($this->m52_codordem."-".$this->m52_numemp."-".$this->m52_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da ordem de compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da ordem de compra ($this->m52_codordem."-".$this->m52_numemp."-".$this->m52_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codordem."-".$this->m52_numemp."-".$this->m52_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m52_codordem,$this->m52_numemp,$this->m52_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,6219,'$this->m52_codordem','I')");
       $resac = pg_query("insert into db_acountkey values($acount,6220,'$this->m52_numemp','I')");
       $resac = pg_query("insert into db_acountkey values($acount,6221,'$this->m52_sequen','I')");
       $resac = pg_query("insert into db_acount values($acount,1008,6219,'','".AddSlashes(pg_result($resaco,0,'m52_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008,6220,'','".AddSlashes(pg_result($resaco,0,'m52_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008,6221,'','".AddSlashes(pg_result($resaco,0,'m52_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1008,6222,'','".AddSlashes(pg_result($resaco,0,'m52_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m52_codordem=null,$m52_numemp=null,$m52_sequen=null) { 
      $this->atualizacampos();
     $sql = " update matordemitem set ";
     $virgula = "";
     if(trim($this->m52_codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_codordem"])){ 
       $sql  .= $virgula." m52_codordem = $this->m52_codordem ";
       $virgula = ",";
       if(trim($this->m52_codordem) == null ){ 
         $this->erro_sql = " Campo Código da ordem de compra nao Informado.";
         $this->erro_campo = "m52_codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_numemp"])){ 
       $sql  .= $virgula." m52_numemp = $this->m52_numemp ";
       $virgula = ",";
       if(trim($this->m52_numemp) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "m52_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_sequen"])){ 
       $sql  .= $virgula." m52_sequen = $this->m52_sequen ";
       $virgula = ",";
       if(trim($this->m52_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "m52_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m52_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m52_quant"])){ 
       $sql  .= $virgula." m52_quant = $this->m52_quant ";
       $virgula = ",";
       if(trim($this->m52_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "m52_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m52_codordem!=null){
       $sql .= " m52_codordem = $this->m52_codordem";
     }
     if($m52_numemp!=null){
       $sql .= " and  m52_numemp = $this->m52_numemp";
     }
     if($m52_sequen!=null){
       $sql .= " and  m52_sequen = $this->m52_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m52_codordem,$this->m52_numemp,$this->m52_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,6219,'$this->m52_codordem','A')");
         $resac = pg_query("insert into db_acountkey values($acount,6220,'$this->m52_numemp','A')");
         $resac = pg_query("insert into db_acountkey values($acount,6221,'$this->m52_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_codordem"]))
           $resac = pg_query("insert into db_acount values($acount,1008,6219,'".AddSlashes(pg_result($resaco,$conresaco,'m52_codordem'))."','$this->m52_codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_numemp"]))
           $resac = pg_query("insert into db_acount values($acount,1008,6220,'".AddSlashes(pg_result($resaco,$conresaco,'m52_numemp'))."','$this->m52_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_sequen"]))
           $resac = pg_query("insert into db_acount values($acount,1008,6221,'".AddSlashes(pg_result($resaco,$conresaco,'m52_sequen'))."','$this->m52_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m52_quant"]))
           $resac = pg_query("insert into db_acount values($acount,1008,6222,'".AddSlashes(pg_result($resaco,$conresaco,'m52_quant'))."','$this->m52_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da ordem de compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codordem."-".$this->m52_numemp."-".$this->m52_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da ordem de compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codordem."-".$this->m52_numemp."-".$this->m52_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m52_codordem."-".$this->m52_numemp."-".$this->m52_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m52_codordem=null,$m52_numemp=null,$m52_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m52_codordem,$m52_numemp,$m52_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,6219,'$this->m52_codordem','E')");
         $resac = pg_query("insert into db_acountkey values($acount,6220,'$this->m52_numemp','E')");
         $resac = pg_query("insert into db_acountkey values($acount,6221,'$this->m52_sequen','E')");
         $resac = pg_query("insert into db_acount values($acount,1008,6219,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008,6220,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008,6221,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1008,6222,'','".AddSlashes(pg_result($resaco,$iresaco,'m52_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matordemitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m52_codordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m52_codordem = $m52_codordem ";
        }
        if($m52_numemp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m52_numemp = $m52_numemp ";
        }
        if($m52_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m52_sequen = $m52_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da ordem de compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m52_codordem."-".$m52_numemp."-".$m52_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens da ordem de compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m52_codordem."-".$m52_numemp."-".$m52_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m52_codordem."-".$m52_numemp."-".$m52_sequen;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:matordemitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m52_codordem=null,$m52_numemp=null,$m52_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitem ";
     $sql .= "      inner join empempitem  on  empempitem.e62_numemp = matordemitem.m52_numemp and  empempitem.e62_sequen = matordemitem.m52_sequen";
     $sql .= "      inner join matordem  on  matordem.m51_codordem = matordemitem.m52_codordem";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empempitem.e62_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join orcelemento  as a on   a.o56_codele = empempitem.e62_codele  and a.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  as b on   b.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  as c on   c.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matordem.m51_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codordem!=null ){
         $sql2 .= " where matordemitem.m52_codordem = $m52_codordem "; 
       } 
       if($m52_numemp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matordemitem.m52_numemp = $m52_numemp "; 
       } 
       if($m52_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matordemitem.m52_sequen = $m52_sequen "; 
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
   function sql_query_file ( $m52_codordem=null,$m52_numemp=null,$m52_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m52_codordem!=null ){
         $sql2 .= " where matordemitem.m52_codordem = $m52_codordem "; 
       } 
       if($m52_numemp!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matordemitem.m52_numemp = $m52_numemp "; 
       } 
       if($m52_sequen!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " matordemitem.m52_sequen = $m52_sequen "; 
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