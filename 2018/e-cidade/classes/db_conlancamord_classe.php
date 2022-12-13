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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conlancamord
class cl_conlancamord { 
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
   var $c80_codlan = 0; 
   var $c80_codord = 0; 
   var $c80_data_dia = null; 
   var $c80_data_mes = null; 
   var $c80_data_ano = null; 
   var $c80_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c80_codlan = int4 = C�digo Lan�amento 
                 c80_codord = int4 = Ordem 
                 c80_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_conlancamord() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conlancamord"); 
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
       $this->c80_codlan = ($this->c80_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c80_codlan"]:$this->c80_codlan);
       $this->c80_codord = ($this->c80_codord == ""?@$GLOBALS["HTTP_POST_VARS"]["c80_codord"]:$this->c80_codord);
       if($this->c80_data == ""){
         $this->c80_data_dia = ($this->c80_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c80_data_dia"]:$this->c80_data_dia);
         $this->c80_data_mes = ($this->c80_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c80_data_mes"]:$this->c80_data_mes);
         $this->c80_data_ano = ($this->c80_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c80_data_ano"]:$this->c80_data_ano);
         if($this->c80_data_dia != ""){
            $this->c80_data = $this->c80_data_ano."-".$this->c80_data_mes."-".$this->c80_data_dia;
         }
       }
     }else{
       $this->c80_codlan = ($this->c80_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c80_codlan"]:$this->c80_codlan);
     }
   }
   // funcao para inclusao
   function incluir ($c80_codlan){ 
      $this->atualizacampos();
     if($this->c80_codord == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "c80_codord";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c80_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c80_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c80_codlan = $c80_codlan; 
     if(($this->c80_codlan == null) || ($this->c80_codlan == "") ){ 
       $this->erro_sql = " Campo c80_codlan nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamord(
                                       c80_codlan 
                                      ,c80_codord 
                                      ,c80_data 
                       )
                values (
                                $this->c80_codlan 
                               ,$this->c80_codord 
                               ,".($this->c80_data == "null" || $this->c80_data == ""?"null":"'".$this->c80_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pagamentos da ordem ($this->c80_codlan) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pagamentos da ordem j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pagamentos da ordem ($this->c80_codlan) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c80_codlan;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c80_codlan));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5446,'$this->c80_codlan','I')");
       $resac = db_query("insert into db_acount values($acount,809,5446,'','".AddSlashes(pg_result($resaco,0,'c80_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,809,5442,'','".AddSlashes(pg_result($resaco,0,'c80_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,809,5903,'','".AddSlashes(pg_result($resaco,0,'c80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c80_codlan=null) { 
      $this->atualizacampos();
     $sql = " update conlancamord set ";
     $virgula = "";
     if(trim($this->c80_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c80_codlan"])){ 
       $sql  .= $virgula." c80_codlan = $this->c80_codlan ";
       $virgula = ",";
       if(trim($this->c80_codlan) == null ){ 
         $this->erro_sql = " Campo C�digo Lan�amento nao Informado.";
         $this->erro_campo = "c80_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c80_codord)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c80_codord"])){ 
       $sql  .= $virgula." c80_codord = $this->c80_codord ";
       $virgula = ",";
       if(trim($this->c80_codord) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "c80_codord";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c80_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c80_data_dia"] !="") ){ 
       $sql  .= $virgula." c80_data = '$this->c80_data' ";
       $virgula = ",";
       if(trim($this->c80_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c80_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c80_data_dia"])){ 
         $sql  .= $virgula." c80_data = null ";
         $virgula = ",";
         if(trim($this->c80_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c80_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($c80_codlan!=null){
       $sql .= " c80_codlan = $this->c80_codlan";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c80_codlan));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5446,'$this->c80_codlan','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c80_codlan"]))
           $resac = db_query("insert into db_acount values($acount,809,5446,'".AddSlashes(pg_result($resaco,$conresaco,'c80_codlan'))."','$this->c80_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c80_codord"]))
           $resac = db_query("insert into db_acount values($acount,809,5442,'".AddSlashes(pg_result($resaco,$conresaco,'c80_codord'))."','$this->c80_codord',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c80_data"]))
           $resac = db_query("insert into db_acount values($acount,809,5903,'".AddSlashes(pg_result($resaco,$conresaco,'c80_data'))."','$this->c80_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamentos da ordem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c80_codlan;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamentos da ordem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c80_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c80_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c80_codlan=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c80_codlan));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5446,'$c80_codlan','E')");
         $resac = db_query("insert into db_acount values($acount,809,5446,'','".AddSlashes(pg_result($resaco,$iresaco,'c80_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,809,5442,'','".AddSlashes(pg_result($resaco,$iresaco,'c80_codord'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,809,5903,'','".AddSlashes(pg_result($resaco,$iresaco,'c80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conlancamord
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c80_codlan != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c80_codlan = $c80_codlan ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamentos da ordem nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c80_codlan;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamentos da ordem nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c80_codlan;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c80_codlan;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamord";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c80_codlan=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamord ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamord.c80_codlan";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = conlancamord.c80_codord";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql2 = "";
     if($dbwhere==""){
       if($c80_codlan!=null ){
         $sql2 .= " where conlancamord.c80_codlan = $c80_codlan "; 
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
   function sql_query_file ( $c80_codlan=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conlancamord ";
     $sql2 = "";
     if($dbwhere==""){
       if($c80_codlan!=null ){
         $sql2 .= " where conlancamord.c80_codlan = $c80_codlan "; 
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