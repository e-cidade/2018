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

//MODULO: fiscal
//CLASSE DA ENTIDADE fiscalultandam
class cl_fiscalultandam { 
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
   var $y19_codnoti = 0; 
   var $y19_codandam = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y19_codnoti = int8 = C�digo da Notifica��o 
                 y19_codandam = int8 = Codigo do Andamento Gerado 
                 ";
   //funcao construtor da classe 
   function cl_fiscalultandam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscalultandam"); 
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
       $this->y19_codnoti = ($this->y19_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y19_codnoti"]:$this->y19_codnoti);
       $this->y19_codandam = ($this->y19_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y19_codandam"]:$this->y19_codandam);
     }else{
       $this->y19_codnoti = ($this->y19_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y19_codnoti"]:$this->y19_codnoti);
       $this->y19_codandam = ($this->y19_codandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y19_codandam"]:$this->y19_codandam);
     }
   }
   // funcao para inclusao
   function incluir ($y19_codnoti,$y19_codandam){ 
      $this->atualizacampos();
       $this->y19_codnoti = $y19_codnoti; 
       $this->y19_codandam = $y19_codandam; 
     if(($this->y19_codnoti == null) || ($this->y19_codnoti == "") ){ 
       $this->erro_sql = " Campo y19_codnoti nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y19_codandam == null) || ($this->y19_codandam == "") ){ 
       $this->erro_sql = " Campo y19_codandam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscalultandam(
                                       y19_codnoti 
                                      ,y19_codandam 
                       )
                values (
                                $this->y19_codnoti 
                               ,$this->y19_codandam 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscalultandam ($this->y19_codnoti."-".$this->y19_codandam) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscalultandam j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscalultandam ($this->y19_codnoti."-".$this->y19_codandam) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y19_codnoti."-".$this->y19_codandam;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y19_codnoti,$this->y19_codandam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5071,'$this->y19_codnoti','I')");
       $resac = db_query("insert into db_acountkey values($acount,5072,'$this->y19_codandam','I')");
       $resac = db_query("insert into db_acount values($acount,722,5071,'','".AddSlashes(pg_result($resaco,0,'y19_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,722,5072,'','".AddSlashes(pg_result($resaco,0,'y19_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y19_codnoti=null,$y19_codandam=null) { 
      $this->atualizacampos();
     $sql = " update fiscalultandam set ";
     $virgula = "";
     if(trim($this->y19_codnoti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y19_codnoti"])){ 
       $sql  .= $virgula." y19_codnoti = $this->y19_codnoti ";
       $virgula = ",";
       if(trim($this->y19_codnoti) == null ){ 
         $this->erro_sql = " Campo C�digo da Notifica��o nao Informado.";
         $this->erro_campo = "y19_codnoti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y19_codandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y19_codandam"])){ 
       $sql  .= $virgula." y19_codandam = $this->y19_codandam ";
       $virgula = ",";
       if(trim($this->y19_codandam) == null ){ 
         $this->erro_sql = " Campo Codigo do Andamento Gerado nao Informado.";
         $this->erro_campo = "y19_codandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y19_codnoti!=null){
       $sql .= " y19_codnoti = $this->y19_codnoti";
     }
     if($y19_codandam!=null){
       $sql .= " and  y19_codandam = $this->y19_codandam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y19_codnoti,$this->y19_codandam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5071,'$this->y19_codnoti','A')");
         $resac = db_query("insert into db_acountkey values($acount,5072,'$this->y19_codandam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y19_codnoti"]))
           $resac = db_query("insert into db_acount values($acount,722,5071,'".AddSlashes(pg_result($resaco,$conresaco,'y19_codnoti'))."','$this->y19_codnoti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y19_codandam"]))
           $resac = db_query("insert into db_acount values($acount,722,5072,'".AddSlashes(pg_result($resaco,$conresaco,'y19_codandam'))."','$this->y19_codandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalultandam nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y19_codnoti."-".$this->y19_codandam;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalultandam nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y19_codnoti."-".$this->y19_codandam;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y19_codnoti."-".$this->y19_codandam;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y19_codnoti=null,$y19_codandam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y19_codnoti,$y19_codandam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5071,'$y19_codnoti','E')");
         $resac = db_query("insert into db_acountkey values($acount,5072,'$y19_codandam','E')");
         $resac = db_query("insert into db_acount values($acount,722,5071,'','".AddSlashes(pg_result($resaco,$iresaco,'y19_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,722,5072,'','".AddSlashes(pg_result($resaco,$iresaco,'y19_codandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscalultandam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y19_codnoti != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y19_codnoti = $y19_codnoti ";
        }
        if($y19_codandam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y19_codandam = $y19_codandam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscalultandam nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y19_codnoti."-".$y19_codandam;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscalultandam nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y19_codnoti."-".$y19_codandam;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y19_codnoti."-".$y19_codandam;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:fiscalultandam";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y19_codnoti=null,$y19_codandam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalultandam ";
     $sql .= "      inner join fandam  on  fandam.y39_codandam = fiscalultandam.y19_codandam";
     $sql .= "      inner join fiscal  on  fiscal.y30_codnoti = fiscalultandam.y19_codnoti";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = fandam.y39_id_usuario";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fandam.y39_codtipo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscal.y30_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y19_codnoti!=null ){
         $sql2 .= " where fiscalultandam.y19_codnoti = $y19_codnoti "; 
       } 
       if($y19_codandam!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscalultandam.y19_codandam = $y19_codandam "; 
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
   function sql_query_file ( $y19_codnoti=null,$y19_codandam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscalultandam ";
     $sql2 = "";
     if($dbwhere==""){
       if($y19_codnoti!=null ){
         $sql2 .= " where fiscalultandam.y19_codnoti = $y19_codnoti "; 
       } 
       if($y19_codandam!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscalultandam.y19_codandam = $y19_codandam "; 
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