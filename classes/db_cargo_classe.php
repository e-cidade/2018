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
//CLASSE DA ENTIDADE cargo
class cl_cargo { 
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
   var $r65_anousu = 0; 
   var $r65_mesusu = 0; 
   var $r65_cargo = 0; 
   var $r65_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r65_anousu = float4 = Ano 
                 r65_mesusu = float4 = Mês 
                 r65_cargo = float4 = Função 
                 r65_descr = varchar(30) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_cargo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cargo"); 
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
       $this->r65_anousu = ($this->r65_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_anousu"]:$this->r65_anousu);
       $this->r65_mesusu = ($this->r65_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_mesusu"]:$this->r65_mesusu);
       $this->r65_cargo = ($this->r65_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_cargo"]:$this->r65_cargo);
       $this->r65_descr = ($this->r65_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_descr"]:$this->r65_descr);
     }else{
       $this->r65_anousu = ($this->r65_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_anousu"]:$this->r65_anousu);
       $this->r65_mesusu = ($this->r65_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_mesusu"]:$this->r65_mesusu);
       $this->r65_cargo = ($this->r65_cargo == ""?@$GLOBALS["HTTP_POST_VARS"]["r65_cargo"]:$this->r65_cargo);
     }
   }
   // funcao para inclusao
   function incluir ($r65_anousu,$r65_mesusu,$r65_cargo){ 
      $this->atualizacampos();
     if($this->r65_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "r65_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r65_anousu = $r65_anousu; 
       $this->r65_mesusu = $r65_mesusu; 
       $this->r65_cargo = $r65_cargo; 
     if(($this->r65_anousu == null) || ($this->r65_anousu == "") ){ 
       $this->erro_sql = " Campo r65_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r65_mesusu == null) || ($this->r65_mesusu == "") ){ 
       $this->erro_sql = " Campo r65_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r65_cargo == null) || ($this->r65_cargo == "") ){ 
       $this->erro_sql = " Campo r65_cargo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cargo(
                                       r65_anousu 
                                      ,r65_mesusu 
                                      ,r65_cargo 
                                      ,r65_descr 
                       )
                values (
                                $this->r65_anousu 
                               ,$this->r65_mesusu 
                               ,$this->r65_cargo 
                               ,'$this->r65_descr' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cargos ($this->r65_anousu."-".$this->r65_mesusu."-".$this->r65_cargo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cargos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cargos ($this->r65_anousu."-".$this->r65_mesusu."-".$this->r65_cargo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r65_anousu."-".$this->r65_mesusu."-".$this->r65_cargo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r65_anousu,$this->r65_mesusu,$this->r65_cargo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4562,'$this->r65_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4563,'$this->r65_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4564,'$this->r65_cargo','I')");
       $resac = db_query("insert into db_acount values($acount,604,4562,'','".AddSlashes(pg_result($resaco,0,'r65_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,604,4563,'','".AddSlashes(pg_result($resaco,0,'r65_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,604,4564,'','".AddSlashes(pg_result($resaco,0,'r65_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,604,4565,'','".AddSlashes(pg_result($resaco,0,'r65_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r65_anousu=null,$r65_mesusu=null,$r65_cargo=null) { 
      $this->atualizacampos();
     $sql = " update cargo set ";
     $virgula = "";
     if(trim($this->r65_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r65_anousu"])){ 
       $sql  .= $virgula." r65_anousu = $this->r65_anousu ";
       $virgula = ",";
       if(trim($this->r65_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "r65_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r65_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r65_mesusu"])){ 
       $sql  .= $virgula." r65_mesusu = $this->r65_mesusu ";
       $virgula = ",";
       if(trim($this->r65_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "r65_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r65_cargo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r65_cargo"])){ 
       $sql  .= $virgula." r65_cargo = $this->r65_cargo ";
       $virgula = ",";
       if(trim($this->r65_cargo) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "r65_cargo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r65_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r65_descr"])){ 
       $sql  .= $virgula." r65_descr = '$this->r65_descr' ";
       $virgula = ",";
       if(trim($this->r65_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "r65_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r65_anousu!=null){
       $sql .= " r65_anousu = $this->r65_anousu";
     }
     if($r65_mesusu!=null){
       $sql .= " and  r65_mesusu = $this->r65_mesusu";
     }
     if($r65_cargo!=null){
       $sql .= " and  r65_cargo = $this->r65_cargo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r65_anousu,$this->r65_mesusu,$this->r65_cargo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4562,'$this->r65_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4563,'$this->r65_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4564,'$this->r65_cargo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r65_anousu"]))
           $resac = db_query("insert into db_acount values($acount,604,4562,'".AddSlashes(pg_result($resaco,$conresaco,'r65_anousu'))."','$this->r65_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r65_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,604,4563,'".AddSlashes(pg_result($resaco,$conresaco,'r65_mesusu'))."','$this->r65_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r65_cargo"]))
           $resac = db_query("insert into db_acount values($acount,604,4564,'".AddSlashes(pg_result($resaco,$conresaco,'r65_cargo'))."','$this->r65_cargo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r65_descr"]))
           $resac = db_query("insert into db_acount values($acount,604,4565,'".AddSlashes(pg_result($resaco,$conresaco,'r65_descr'))."','$this->r65_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cargos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r65_anousu."-".$this->r65_mesusu."-".$this->r65_cargo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cargos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r65_anousu."-".$this->r65_mesusu."-".$this->r65_cargo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r65_anousu."-".$this->r65_mesusu."-".$this->r65_cargo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r65_anousu=null,$r65_mesusu=null,$r65_cargo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r65_anousu,$r65_mesusu,$r65_cargo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4562,'$r65_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4563,'$r65_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4564,'$r65_cargo','E')");
         $resac = db_query("insert into db_acount values($acount,604,4562,'','".AddSlashes(pg_result($resaco,$iresaco,'r65_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,604,4563,'','".AddSlashes(pg_result($resaco,$iresaco,'r65_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,604,4564,'','".AddSlashes(pg_result($resaco,$iresaco,'r65_cargo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,604,4565,'','".AddSlashes(pg_result($resaco,$iresaco,'r65_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cargo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r65_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r65_anousu = $r65_anousu ";
        }
        if($r65_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r65_mesusu = $r65_mesusu ";
        }
        if($r65_cargo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r65_cargo = $r65_cargo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cargos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r65_anousu."-".$r65_mesusu."-".$r65_cargo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cargos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r65_anousu."-".$r65_mesusu."-".$r65_cargo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r65_anousu."-".$r65_mesusu."-".$r65_cargo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cargo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r65_anousu=null,$r65_mesusu=null,$r65_cargo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cargo ";
     $sql2 = "";
     if($dbwhere==""){
       if($r65_anousu!=null ){
         $sql2 .= " where cargo.r65_anousu = $r65_anousu "; 
       } 
       if($r65_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cargo.r65_mesusu = $r65_mesusu "; 
       } 
       if($r65_cargo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cargo.r65_cargo = $r65_cargo "; 
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
   function sql_query_file ( $r65_anousu=null,$r65_mesusu=null,$r65_cargo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cargo ";
     $sql2 = "";
     if($dbwhere==""){
       if($r65_anousu!=null ){
         $sql2 .= " where cargo.r65_anousu = $r65_anousu "; 
       } 
       if($r65_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cargo.r65_mesusu = $r65_mesusu "; 
       } 
       if($r65_cargo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " cargo.r65_cargo = $r65_cargo "; 
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