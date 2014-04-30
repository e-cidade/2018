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

//MODULO: empenho
//CLASSE DA ENTIDADE pagordemnota
class cl_pagordemnota { 
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
   var $e71_codord = 0; 
   var $e71_codnota = 0; 
   var $e71_anulado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e71_codord = int4 = Ordem 
                 e71_codnota = int4 = Nota 
                 e71_anulado = bool = Anulado 
                 ";
   //funcao construtor da classe 
   function cl_pagordemnota() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pagordemnota"); 
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
       $this->e71_codord = ($this->e71_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e71_codord"]:$this->e71_codord);
       $this->e71_codnota = ($this->e71_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e71_codnota"]:$this->e71_codnota);
       $this->e71_anulado = ($this->e71_anulado == "f"?@$GLOBALS["HTTP_POST_VARS"]["e71_anulado"]:$this->e71_anulado);
     }else{
       $this->e71_codord = ($this->e71_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["e71_codord"]:$this->e71_codord);
       $this->e71_codnota = ($this->e71_codnota == ""?@$GLOBALS["HTTP_POST_VARS"]["e71_codnota"]:$this->e71_codnota);
     }
   }
   // funcao para inclusao
   function incluir ($e71_codord,$e71_codnota){ 
      $this->atualizacampos();
     if($this->e71_anulado == null ){ 
       $this->erro_sql = " Campo Anulado nao Informado.";
       $this->erro_campo = "e71_anulado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->e71_codord = $e71_codord; 
       $this->e71_codnota = $e71_codnota; 
     if(($this->e71_codord == null) || ($this->e71_codord == "") ){ 
       $this->erro_sql = " Campo e71_codord nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->e71_codnota == null) || ($this->e71_codnota == "") ){ 
       $this->erro_sql = " Campo e71_codnota nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pagordemnota(
                                       e71_codord 
                                      ,e71_codnota 
                                      ,e71_anulado 
                       )
                values (
                                $this->e71_codord 
                               ,$this->e71_codnota 
                               ,'$this->e71_anulado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordens com suas notas ($this->e71_codord."-".$this->e71_codnota) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordens com suas notas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordens com suas notas ($this->e71_codord."-".$this->e71_codnota) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e71_codord."-".$this->e71_codnota;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e71_codord,$this->e71_codnota));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6091,'$this->e71_codord','I')");
       $resac = db_query("insert into db_acountkey values($acount,6092,'$this->e71_codnota','I')");
       $resac = db_query("insert into db_acount values($acount,979,6091,'','".AddSlashes(pg_result($resaco,0,'e71_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,979,6092,'','".AddSlashes(pg_result($resaco,0,'e71_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,979,6093,'','".AddSlashes(pg_result($resaco,0,'e71_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e71_codord=null,$e71_codnota=null) { 
      $this->atualizacampos();
     $sql = " update pagordemnota set ";
     $virgula = "";
     if(trim($this->e71_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e71_codord"])){ 
       $sql  .= $virgula." e71_codord = $this->e71_codord ";
       $virgula = ",";
       if(trim($this->e71_codord) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "e71_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e71_codnota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e71_codnota"])){ 
       $sql  .= $virgula." e71_codnota = $this->e71_codnota ";
       $virgula = ",";
       if(trim($this->e71_codnota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "e71_codnota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e71_anulado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e71_anulado"])){ 
       $sql  .= $virgula." e71_anulado = '$this->e71_anulado' ";
       $virgula = ",";
       if(trim($this->e71_anulado) == null ){ 
         $this->erro_sql = " Campo Anulado nao Informado.";
         $this->erro_campo = "e71_anulado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e71_codord!=null){
       $sql .= " e71_codord = $this->e71_codord";
     }
     if($e71_codnota!=null){
       $sql .= " and  e71_codnota = $this->e71_codnota";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e71_codord,$this->e71_codnota));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6091,'$this->e71_codord','A')");
         $resac = db_query("insert into db_acountkey values($acount,6092,'$this->e71_codnota','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e71_codord"]))
           $resac = db_query("insert into db_acount values($acount,979,6091,'".AddSlashes(pg_result($resaco,$conresaco,'e71_codord'))."','$this->e71_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e71_codnota"]))
           $resac = db_query("insert into db_acount values($acount,979,6092,'".AddSlashes(pg_result($resaco,$conresaco,'e71_codnota'))."','$this->e71_codnota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e71_anulado"]))
           $resac = db_query("insert into db_acount values($acount,979,6093,'".AddSlashes(pg_result($resaco,$conresaco,'e71_anulado'))."','$this->e71_anulado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordens com suas notas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e71_codord."-".$this->e71_codnota;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordens com suas notas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e71_codord."-".$this->e71_codnota;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e71_codord."-".$this->e71_codnota;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e71_codord=null,$e71_codnota=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e71_codord,$e71_codnota));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6091,'$e71_codord','E')");
         $resac = db_query("insert into db_acountkey values($acount,6092,'$e71_codnota','E')");
         $resac = db_query("insert into db_acount values($acount,979,6091,'','".AddSlashes(pg_result($resaco,$iresaco,'e71_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,979,6092,'','".AddSlashes(pg_result($resaco,$iresaco,'e71_codnota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,979,6093,'','".AddSlashes(pg_result($resaco,$iresaco,'e71_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pagordemnota
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e71_codord != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e71_codord = $e71_codord ";
        }
        if($e71_codnota != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e71_codnota = $e71_codnota ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordens com suas notas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e71_codord."-".$e71_codnota;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordens com suas notas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e71_codord."-".$e71_codnota;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e71_codord."-".$e71_codnota;
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
        $this->erro_sql   = "Record Vazio na Tabela:pagordemnota";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e71_codord=null,$e71_codnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemnota ";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = pagordemnota.e71_codord";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = pagordemnota.e71_codnota";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join empempenho  as a on   a.e60_numemp = empnota.e69_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($e71_codord!=null ){
         $sql2 .= " where pagordemnota.e71_codord = $e71_codord "; 
       } 
       if($e71_codnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pagordemnota.e71_codnota = $e71_codnota "; 
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
   function sql_query_file ( $e71_codord=null,$e71_codnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemnota ";
     $sql2 = "";
     if($dbwhere==""){
       if($e71_codord!=null ){
         $sql2 .= " where pagordemnota.e71_codord = $e71_codord "; 
       } 
       if($e71_codnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pagordemnota.e71_codnota = $e71_codnota "; 
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
  
 function sql_query_valorordem ( $e71_codord=null,$e71_codnota=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pagordemnota ";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = pagordemnota.e71_codord";
     $sql .= "      inner join empnota  on  empnota.e69_codnota = pagordemnota.e71_codnota";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empnota.e69_id_usuario";
     $sql .= "      inner join pagordemele  on  pagordem.e50_codord = pagordemele.e53_codord";
     $sql2 = "";
     if($dbwhere==""){
       if($e71_codord!=null ){
         $sql2 .= " where pagordemnota.e71_codord = $e71_codord "; 
       } 
       if($e71_codnota!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pagordemnota.e71_codnota = $e71_codnota "; 
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