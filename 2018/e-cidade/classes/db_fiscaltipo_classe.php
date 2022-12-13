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
//CLASSE DA ENTIDADE fiscaltipo
class cl_fiscaltipo { 
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
   var $y31_codnoti = 0; 
   var $y31_codtipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y31_codnoti = int8 = Código da Notificação 
                 y31_codtipo = int8 = Código da Procedência 
                 ";
   //funcao construtor da classe 
   function cl_fiscaltipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fiscaltipo"); 
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
       $this->y31_codnoti = ($this->y31_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y31_codnoti"]:$this->y31_codnoti);
       $this->y31_codtipo = ($this->y31_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y31_codtipo"]:$this->y31_codtipo);
     }else{
       $this->y31_codnoti = ($this->y31_codnoti == ""?@$GLOBALS["HTTP_POST_VARS"]["y31_codnoti"]:$this->y31_codnoti);
       $this->y31_codtipo = ($this->y31_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y31_codtipo"]:$this->y31_codtipo);
     }
   }
   // funcao para inclusao
   function incluir ($y31_codnoti,$y31_codtipo){ 
      $this->atualizacampos();
       $this->y31_codnoti = $y31_codnoti; 
       $this->y31_codtipo = $y31_codtipo; 
     if(($this->y31_codnoti == null) || ($this->y31_codnoti == "") ){ 
       $this->erro_sql = " Campo y31_codnoti nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->y31_codtipo == null) || ($this->y31_codtipo == "") ){ 
       $this->erro_sql = " Campo y31_codtipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fiscaltipo(
                                       y31_codnoti 
                                      ,y31_codtipo 
                       )
                values (
                                $this->y31_codnoti 
                               ,$this->y31_codtipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "fiscaltipo ($this->y31_codnoti."-".$this->y31_codtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "fiscaltipo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "fiscaltipo ($this->y31_codnoti."-".$this->y31_codtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y31_codnoti."-".$this->y31_codtipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y31_codnoti,$this->y31_codtipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4949,'$this->y31_codnoti','I')");
       $resac = db_query("insert into db_acountkey values($acount,4950,'$this->y31_codtipo','I')");
       $resac = db_query("insert into db_acount values($acount,684,4949,'','".AddSlashes(pg_result($resaco,0,'y31_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,684,4950,'','".AddSlashes(pg_result($resaco,0,'y31_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y31_codnoti=null,$y31_codtipo=null) { 
      $this->atualizacampos();
     $sql = " update fiscaltipo set ";
     $virgula = "";
     if(trim($this->y31_codnoti)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y31_codnoti"])){ 
       $sql  .= $virgula." y31_codnoti = $this->y31_codnoti ";
       $virgula = ",";
       if(trim($this->y31_codnoti) == null ){ 
         $this->erro_sql = " Campo Código da Notificação nao Informado.";
         $this->erro_campo = "y31_codnoti";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y31_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y31_codtipo"])){ 
       $sql  .= $virgula." y31_codtipo = $this->y31_codtipo ";
       $virgula = ",";
       if(trim($this->y31_codtipo) == null ){ 
         $this->erro_sql = " Campo Código da Procedência nao Informado.";
         $this->erro_campo = "y31_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y31_codnoti!=null){
       $sql .= " y31_codnoti = $this->y31_codnoti";
     }
     if($y31_codtipo!=null){
       $sql .= " and  y31_codtipo = $this->y31_codtipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y31_codnoti,$this->y31_codtipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4949,'$this->y31_codnoti','A')");
         $resac = db_query("insert into db_acountkey values($acount,4950,'$this->y31_codtipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y31_codnoti"]))
           $resac = db_query("insert into db_acount values($acount,684,4949,'".AddSlashes(pg_result($resaco,$conresaco,'y31_codnoti'))."','$this->y31_codnoti',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y31_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,684,4950,'".AddSlashes(pg_result($resaco,$conresaco,'y31_codtipo'))."','$this->y31_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscaltipo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y31_codnoti."-".$this->y31_codtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscaltipo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y31_codnoti."-".$this->y31_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y31_codnoti."-".$this->y31_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y31_codnoti=null,$y31_codtipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y31_codnoti,$y31_codtipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4949,'$y31_codnoti','E')");
         $resac = db_query("insert into db_acountkey values($acount,4950,'$y31_codtipo','E')");
         $resac = db_query("insert into db_acount values($acount,684,4949,'','".AddSlashes(pg_result($resaco,$iresaco,'y31_codnoti'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,684,4950,'','".AddSlashes(pg_result($resaco,$iresaco,'y31_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from fiscaltipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y31_codnoti != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y31_codnoti = $y31_codnoti ";
        }
        if($y31_codtipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y31_codtipo = $y31_codtipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "fiscaltipo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y31_codnoti."-".$y31_codtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "fiscaltipo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y31_codnoti."-".$y31_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y31_codnoti."-".$y31_codtipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:fiscaltipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y31_codnoti=null,$y31_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscaltipo ";
     $sql .= "      inner join fiscalproc  on  fiscalproc.y29_codtipo = fiscaltipo.y31_codtipo";
     $sql .= "      inner join fiscal  on  fiscal.y30_codnoti = fiscaltipo.y31_codnoti";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = fiscalproc.y29_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = fiscalproc.y29_tipoandam";
     $sql .= "      inner join db_depart  as a on   a.coddepto = fiscal.y30_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($y31_codnoti!=null ){
         $sql2 .= " where fiscaltipo.y31_codnoti = $y31_codnoti "; 
       } 
       if($y31_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscaltipo.y31_codtipo = $y31_codtipo "; 
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
   function sql_query_file ( $y31_codnoti=null,$y31_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fiscaltipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($y31_codnoti!=null ){
         $sql2 .= " where fiscaltipo.y31_codnoti = $y31_codnoti "; 
       } 
       if($y31_codtipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " fiscaltipo.y31_codtipo = $y31_codtipo "; 
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