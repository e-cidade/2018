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

//MODULO: agua
//CLASSE DA ENTIDADE aguacalcval
class cl_aguacalcval { 
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
   var $x23_codcalc = 0; 
   var $x23_codconsumotipo = 0; 
   var $x23_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x23_codcalc = int4 = Codigo 
                 x23_codconsumotipo = int4 = Codigo 
                 x23_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_aguacalcval() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguacalcval"); 
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
       $this->x23_codcalc = ($this->x23_codcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["x23_codcalc"]:$this->x23_codcalc);
       $this->x23_codconsumotipo = ($this->x23_codconsumotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x23_codconsumotipo"]:$this->x23_codconsumotipo);
       $this->x23_valor = ($this->x23_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["x23_valor"]:$this->x23_valor);
     }else{
       $this->x23_codcalc = ($this->x23_codcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["x23_codcalc"]:$this->x23_codcalc);
       $this->x23_codconsumotipo = ($this->x23_codconsumotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x23_codconsumotipo"]:$this->x23_codconsumotipo);
     }
   }
   // funcao para inclusao
   function incluir ($x23_codcalc,$x23_codconsumotipo){ 
      $this->atualizacampos();
     if($this->x23_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "x23_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->x23_codcalc = $x23_codcalc; 
       $this->x23_codconsumotipo = $x23_codconsumotipo; 
     if(($this->x23_codcalc == null) || ($this->x23_codcalc == "") ){ 
       $this->erro_sql = " Campo x23_codcalc nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->x23_codconsumotipo == null) || ($this->x23_codconsumotipo == "") ){ 
       $this->erro_sql = " Campo x23_codconsumotipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguacalcval(
                                       x23_codcalc 
                                      ,x23_codconsumotipo 
                                      ,x23_valor 
                       )
                values (
                                $this->x23_codcalc 
                               ,$this->x23_codconsumotipo 
                               ,$this->x23_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguacalcval ($this->x23_codcalc."-".$this->x23_codconsumotipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguacalcval já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguacalcval ($this->x23_codcalc."-".$this->x23_codconsumotipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x23_codcalc."-".$this->x23_codconsumotipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x23_codcalc,$this->x23_codconsumotipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8499,'$this->x23_codcalc','I')");
       $resac = db_query("insert into db_acountkey values($acount,8500,'$this->x23_codconsumotipo','I')");
       $resac = db_query("insert into db_acount values($acount,1444,8499,'','".AddSlashes(pg_result($resaco,0,'x23_codcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1444,8500,'','".AddSlashes(pg_result($resaco,0,'x23_codconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1444,8501,'','".AddSlashes(pg_result($resaco,0,'x23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x23_codcalc=null,$x23_codconsumotipo=null) { 
      $this->atualizacampos();
     $sql = " update aguacalcval set ";
     $virgula = "";
     if(trim($this->x23_codcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x23_codcalc"])){ 
       $sql  .= $virgula." x23_codcalc = $this->x23_codcalc ";
       $virgula = ",";
       if(trim($this->x23_codcalc) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x23_codcalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x23_codconsumotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x23_codconsumotipo"])){ 
       $sql  .= $virgula." x23_codconsumotipo = $this->x23_codconsumotipo ";
       $virgula = ",";
       if(trim($this->x23_codconsumotipo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x23_codconsumotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x23_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x23_valor"])){ 
       $sql  .= $virgula." x23_valor = $this->x23_valor ";
       $virgula = ",";
       if(trim($this->x23_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "x23_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x23_codcalc!=null){
       $sql .= " x23_codcalc = $this->x23_codcalc";
     }
     if($x23_codconsumotipo!=null){
       $sql .= " and  x23_codconsumotipo = $this->x23_codconsumotipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x23_codcalc,$this->x23_codconsumotipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8499,'$this->x23_codcalc','A')");
         $resac = db_query("insert into db_acountkey values($acount,8500,'$this->x23_codconsumotipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x23_codcalc"]))
           $resac = db_query("insert into db_acount values($acount,1444,8499,'".AddSlashes(pg_result($resaco,$conresaco,'x23_codcalc'))."','$this->x23_codcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x23_codconsumotipo"]))
           $resac = db_query("insert into db_acount values($acount,1444,8500,'".AddSlashes(pg_result($resaco,$conresaco,'x23_codconsumotipo'))."','$this->x23_codconsumotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x23_valor"]))
           $resac = db_query("insert into db_acount values($acount,1444,8501,'".AddSlashes(pg_result($resaco,$conresaco,'x23_valor'))."','$this->x23_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacalcval nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x23_codcalc."-".$this->x23_codconsumotipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacalcval nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x23_codcalc."-".$this->x23_codconsumotipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x23_codcalc."-".$this->x23_codconsumotipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x23_codcalc=null,$x23_codconsumotipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x23_codcalc,$x23_codconsumotipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8499,'$x23_codcalc','E')");
         $resac = db_query("insert into db_acountkey values($acount,8500,'$x23_codconsumotipo','E')");
         $resac = db_query("insert into db_acount values($acount,1444,8499,'','".AddSlashes(pg_result($resaco,$iresaco,'x23_codcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1444,8500,'','".AddSlashes(pg_result($resaco,$iresaco,'x23_codconsumotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1444,8501,'','".AddSlashes(pg_result($resaco,$iresaco,'x23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguacalcval
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x23_codcalc != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x23_codcalc = $x23_codcalc ";
        }
        if($x23_codconsumotipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x23_codconsumotipo = $x23_codconsumotipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguacalcval nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x23_codcalc."-".$x23_codconsumotipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguacalcval nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x23_codcalc."-".$x23_codconsumotipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x23_codcalc."-".$x23_codconsumotipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguacalcval";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x23_codcalc=null,$x23_codconsumotipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacalcval ";
     $sql .= "      inner join aguacalc  on  aguacalc.x22_codcalc = aguacalcval.x23_codcalc";
     $sql .= "      inner join aguaconsumotipo  on  aguaconsumotipo.x25_codconsumotipo = aguacalcval.x23_codconsumotipo";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguacalc.x22_matric";
     $sql .= "      inner join aguaconsumo  as a on   a.x19_codconsumo = aguacalc.x22_codconsumo";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = aguaconsumotipo.x25_codhist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguaconsumotipo.x25_receit";
     $sql2 = "";
     if($dbwhere==""){
       if($x23_codcalc!=null ){
         $sql2 .= " where aguacalcval.x23_codcalc = $x23_codcalc "; 
       } 
       if($x23_codconsumotipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguacalcval.x23_codconsumotipo = $x23_codconsumotipo "; 
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
   function sql_query_file ( $x23_codcalc=null,$x23_codconsumotipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguacalcval ";
     $sql2 = "";
     if($dbwhere==""){
       if($x23_codcalc!=null ){
         $sql2 .= " where aguacalcval.x23_codcalc = $x23_codcalc "; 
       } 
       if($x23_codconsumotipo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " aguacalcval.x23_codconsumotipo = $x23_codconsumotipo "; 
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