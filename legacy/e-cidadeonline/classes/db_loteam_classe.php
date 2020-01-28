<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: cadastro
//CLASSE DA ENTIDADE loteam
class cl_loteam { 
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
   var $j34_loteam = 0; 
   var $j34_descr = null; 
   var $j34_areacc = 0; 
   var $j34_areapc = 0; 
   var $j34_areato = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j34_loteam = int4 = Cód. Loteamento 
                 j34_descr = varchar(40) = Descrição 
                 j34_areacc = float8 = Área Construída 
                 j34_areapc = float8 = Área Pública 
                 j34_areato = float8 = Área Total 
                 ";
   //funcao construtor da classe 
   function cl_loteam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("loteam"); 
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
       $this->j34_loteam = ($this->j34_loteam == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_loteam"]:$this->j34_loteam);
       $this->j34_descr = ($this->j34_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_descr"]:$this->j34_descr);
       $this->j34_areacc = ($this->j34_areacc == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_areacc"]:$this->j34_areacc);
       $this->j34_areapc = ($this->j34_areapc == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_areapc"]:$this->j34_areapc);
       $this->j34_areato = ($this->j34_areato == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_areato"]:$this->j34_areato);
     }else{
       $this->j34_loteam = ($this->j34_loteam == ""?@$GLOBALS["HTTP_POST_VARS"]["j34_loteam"]:$this->j34_loteam);
     }
   }
   // funcao para inclusao
   function incluir ($j34_loteam){ 
      $this->atualizacampos();
     if($this->j34_areacc == null ){ 
       $this->erro_sql = " Campo Área Construída nao Informado.";
       $this->erro_campo = "j34_areacc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j34_areapc == null ){ 
       $this->erro_sql = " Campo Área Pública nao Informado.";
       $this->erro_campo = "j34_areapc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j34_areato == null ){ 
       $this->erro_sql = " Campo Área Total nao Informado.";
       $this->erro_campo = "j34_areato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j34_loteam == "" || $j34_loteam == null ){
       $result = db_query("select nextval('loteam_j34_loteam_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: loteam_j34_loteam_seq do campo: j34_loteam"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j34_loteam = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from loteam_j34_loteam_seq");
       if(($result != false) && (pg_result($result,0,0) < $j34_loteam)){
         $this->erro_sql = " Campo j34_loteam maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j34_loteam = $j34_loteam; 
       }
     }
     if(($this->j34_loteam == null) || ($this->j34_loteam == "") ){ 
       $this->erro_sql = " Campo j34_loteam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into loteam(
                                       j34_loteam 
                                      ,j34_descr 
                                      ,j34_areacc 
                                      ,j34_areapc 
                                      ,j34_areato 
                       )
                values (
                                $this->j34_loteam 
                               ,'$this->j34_descr' 
                               ,$this->j34_areacc 
                               ,$this->j34_areapc 
                               ,$this->j34_areato 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Loteamento ($this->j34_loteam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Loteamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Loteamento ($this->j34_loteam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j34_loteam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j34_loteam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,86,'$this->j34_loteam','I')");
       $resac = db_query("insert into db_acount values($acount,20,86,'','".AddSlashes(pg_result($resaco,0,'j34_loteam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,20,87,'','".AddSlashes(pg_result($resaco,0,'j34_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,20,88,'','".AddSlashes(pg_result($resaco,0,'j34_areacc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,20,89,'','".AddSlashes(pg_result($resaco,0,'j34_areapc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,20,90,'','".AddSlashes(pg_result($resaco,0,'j34_areato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j34_loteam=null) { 
      $this->atualizacampos();
     $sql = " update loteam set ";
     $virgula = "";
     if(trim($this->j34_loteam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_loteam"])){ 
        if(trim($this->j34_loteam)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j34_loteam"])){ 
           $this->j34_loteam = "0" ; 
        } 
       $sql  .= $virgula." j34_loteam = $this->j34_loteam ";
       $virgula = ",";
     }
     if(trim($this->j34_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_descr"])){ 
       $sql  .= $virgula." j34_descr = '$this->j34_descr' ";
       $virgula = ",";
     }
     if(trim($this->j34_areacc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_areacc"])){ 
       $sql  .= $virgula." j34_areacc = $this->j34_areacc ";
       $virgula = ",";
       if(trim($this->j34_areacc) == null ){ 
         $this->erro_sql = " Campo Área Construída nao Informado.";
         $this->erro_campo = "j34_areacc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j34_areapc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_areapc"])){ 
       $sql  .= $virgula." j34_areapc = $this->j34_areapc ";
       $virgula = ",";
       if(trim($this->j34_areapc) == null ){ 
         $this->erro_sql = " Campo Área Pública nao Informado.";
         $this->erro_campo = "j34_areapc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j34_areato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j34_areato"])){ 
       $sql  .= $virgula." j34_areato = $this->j34_areato ";
       $virgula = ",";
       if(trim($this->j34_areato) == null ){ 
         $this->erro_sql = " Campo Área Total nao Informado.";
         $this->erro_campo = "j34_areato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j34_loteam!=null){
       $sql .= " j34_loteam = $this->j34_loteam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j34_loteam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,86,'$this->j34_loteam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j34_loteam"]))
           $resac = db_query("insert into db_acount values($acount,20,86,'".AddSlashes(pg_result($resaco,$conresaco,'j34_loteam'))."','$this->j34_loteam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j34_descr"]))
           $resac = db_query("insert into db_acount values($acount,20,87,'".AddSlashes(pg_result($resaco,$conresaco,'j34_descr'))."','$this->j34_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j34_areacc"]))
           $resac = db_query("insert into db_acount values($acount,20,88,'".AddSlashes(pg_result($resaco,$conresaco,'j34_areacc'))."','$this->j34_areacc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j34_areapc"]))
           $resac = db_query("insert into db_acount values($acount,20,89,'".AddSlashes(pg_result($resaco,$conresaco,'j34_areapc'))."','$this->j34_areapc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j34_areato"]))
           $resac = db_query("insert into db_acount values($acount,20,90,'".AddSlashes(pg_result($resaco,$conresaco,'j34_areato'))."','$this->j34_areato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Loteamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j34_loteam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Loteamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j34_loteam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j34_loteam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j34_loteam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j34_loteam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,86,'$j34_loteam','E')");
         $resac = db_query("insert into db_acount values($acount,20,86,'','".AddSlashes(pg_result($resaco,$iresaco,'j34_loteam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,20,87,'','".AddSlashes(pg_result($resaco,$iresaco,'j34_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,20,88,'','".AddSlashes(pg_result($resaco,$iresaco,'j34_areacc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,20,89,'','".AddSlashes(pg_result($resaco,$iresaco,'j34_areapc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,20,90,'','".AddSlashes(pg_result($resaco,$iresaco,'j34_areato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from loteam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j34_loteam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j34_loteam = $j34_loteam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Loteamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j34_loteam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Loteamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j34_loteam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j34_loteam;
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
        $this->erro_sql   = "Record Vazio na Tabela:loteam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j34_loteam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from loteam ";
     $sql2 = "";
     if($dbwhere==""){
       if($j34_loteam!=null ){
         $sql2 .= " where loteam.j34_loteam = $j34_loteam "; 
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
   function sql_query_file ( $j34_loteam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from loteam ";
     $sql2 = "";
     if($dbwhere==""){
       if($j34_loteam!=null ){
         $sql2 .= " where loteam.j34_loteam = $j34_loteam "; 
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