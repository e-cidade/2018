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

//MODULO: caixa
//CLASSE DA ENTIDADE listanotifica
class cl_listanotifica { 
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
   var $k63_codigo = 0; 
   var $k63_numpre = 0; 
   var $k63_notifica = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k63_codigo = int4 = C�digo da Lista 
                 k63_numpre = int4 = Numpre Notificado 
                 k63_notifica = int4 = N�mero da Notifica��o 
                 ";
   //funcao construtor da classe 
   function cl_listanotifica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("listanotifica"); 
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
       $this->k63_codigo = ($this->k63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k63_codigo"]:$this->k63_codigo);
       $this->k63_numpre = ($this->k63_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k63_numpre"]:$this->k63_numpre);
       $this->k63_notifica = ($this->k63_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k63_notifica"]:$this->k63_notifica);
     }else{
       $this->k63_codigo = ($this->k63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k63_codigo"]:$this->k63_codigo);
       $this->k63_numpre = ($this->k63_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k63_numpre"]:$this->k63_numpre);
       $this->k63_notifica = ($this->k63_notifica == ""?@$GLOBALS["HTTP_POST_VARS"]["k63_notifica"]:$this->k63_notifica);
     }
   }
   // funcao para inclusao
   function incluir ($k63_codigo,$k63_numpre,$k63_notifica){ 
      $this->atualizacampos();
       $this->k63_codigo = $k63_codigo; 
       $this->k63_numpre = $k63_numpre; 
       $this->k63_notifica = $k63_notifica; 
     if(($this->k63_codigo == null) || ($this->k63_codigo == "") ){ 
       $this->erro_sql = " Campo k63_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k63_numpre == null) || ($this->k63_numpre == "") ){ 
       $this->erro_sql = " Campo k63_numpre nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k63_notifica == null) || ($this->k63_notifica == "") ){ 
       $this->erro_sql = " Campo k63_notifica nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into listanotifica(
                                       k63_codigo 
                                      ,k63_numpre 
                                      ,k63_notifica 
                       )
                values (
                                $this->k63_codigo 
                               ,$this->k63_numpre 
                               ,$this->k63_notifica 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Listas Notificadas ($this->k63_codigo."-".$this->k63_numpre."-".$this->k63_notifica) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Listas Notificadas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Listas Notificadas ($this->k63_codigo."-".$this->k63_numpre."-".$this->k63_notifica) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k63_codigo."-".$this->k63_numpre."-".$this->k63_notifica;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k63_codigo,$this->k63_numpre,$this->k63_notifica));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4788,'$this->k63_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,4789,'$this->k63_numpre','I')");
       $resac = db_query("insert into db_acountkey values($acount,4787,'$this->k63_notifica','I')");
       $resac = db_query("insert into db_acount values($acount,644,4788,'','".AddSlashes(pg_result($resaco,0,'k63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,644,4789,'','".AddSlashes(pg_result($resaco,0,'k63_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,644,4787,'','".AddSlashes(pg_result($resaco,0,'k63_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k63_codigo=null,$k63_numpre=null,$k63_notifica=null) { 
      $this->atualizacampos();
     $sql = " update listanotifica set ";
     $virgula = "";
     if(trim($this->k63_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k63_codigo"])){ 
       $sql  .= $virgula." k63_codigo = $this->k63_codigo ";
       $virgula = ",";
       if(trim($this->k63_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo da Lista nao Informado.";
         $this->erro_campo = "k63_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k63_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k63_numpre"])){ 
       $sql  .= $virgula." k63_numpre = $this->k63_numpre ";
       $virgula = ",";
       if(trim($this->k63_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre Notificado nao Informado.";
         $this->erro_campo = "k63_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k63_notifica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k63_notifica"])){ 
       $sql  .= $virgula." k63_notifica = $this->k63_notifica ";
       $virgula = ",";
       if(trim($this->k63_notifica) == null ){ 
         $this->erro_sql = " Campo N�mero da Notifica��o nao Informado.";
         $this->erro_campo = "k63_notifica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k63_codigo!=null){
       $sql .= " k63_codigo = $this->k63_codigo";
     }
     if($k63_numpre!=null){
       $sql .= " and  k63_numpre = $this->k63_numpre";
     }
     if($k63_notifica!=null){
       $sql .= " and  k63_notifica = $this->k63_notifica";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k63_codigo,$this->k63_numpre,$this->k63_notifica));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4788,'$this->k63_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,4789,'$this->k63_numpre','A')");
         $resac = db_query("insert into db_acountkey values($acount,4787,'$this->k63_notifica','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k63_codigo"]))
           $resac = db_query("insert into db_acount values($acount,644,4788,'".AddSlashes(pg_result($resaco,$conresaco,'k63_codigo'))."','$this->k63_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k63_numpre"]))
           $resac = db_query("insert into db_acount values($acount,644,4789,'".AddSlashes(pg_result($resaco,$conresaco,'k63_numpre'))."','$this->k63_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k63_notifica"]))
           $resac = db_query("insert into db_acount values($acount,644,4787,'".AddSlashes(pg_result($resaco,$conresaco,'k63_notifica'))."','$this->k63_notifica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Listas Notificadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k63_codigo."-".$this->k63_numpre."-".$this->k63_notifica;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Listas Notificadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k63_codigo."-".$this->k63_numpre."-".$this->k63_notifica;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k63_codigo."-".$this->k63_numpre."-".$this->k63_notifica;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k63_codigo=null,$k63_numpre=null,$k63_notifica=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k63_codigo,$k63_numpre,$k63_notifica));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4788,'$k63_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,4789,'$k63_numpre','E')");
         $resac = db_query("insert into db_acountkey values($acount,4787,'$k63_notifica','E')");
         $resac = db_query("insert into db_acount values($acount,644,4788,'','".AddSlashes(pg_result($resaco,$iresaco,'k63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,644,4789,'','".AddSlashes(pg_result($resaco,$iresaco,'k63_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,644,4787,'','".AddSlashes(pg_result($resaco,$iresaco,'k63_notifica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from listanotifica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k63_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k63_codigo = $k63_codigo ";
        }
        if($k63_numpre != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k63_numpre = $k63_numpre ";
        }
        if($k63_notifica != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k63_notifica = $k63_notifica ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Listas Notificadas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k63_codigo."-".$k63_numpre."-".$k63_notifica;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Listas Notificadas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k63_codigo."-".$k63_numpre."-".$k63_notifica;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k63_codigo."-".$k63_numpre."-".$k63_notifica;
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
        $this->erro_sql   = "Record Vazio na Tabela:listanotifica";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k63_codigo=null,$k63_numpre=null,$k63_notifica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listanotifica ";
     $sql .= "      inner join notificacao  on  notificacao.k50_notifica = listanotifica.k63_notifica";
     $sql .= "      inner join lista  on  lista.k60_codigo = listanotifica.k63_codigo";
     $sql .= "      inner join notitipo  on  notitipo.k51_procede = notificacao.k50_procede";
     $sql2 = "";
     if($dbwhere==""){
       if($k63_codigo!=null ){
         $sql2 .= " where listanotifica.k63_codigo = $k63_codigo "; 
       } 
       if($k63_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listanotifica.k63_numpre = $k63_numpre "; 
       } 
       if($k63_notifica!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listanotifica.k63_notifica = $k63_notifica "; 
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
   function sql_query_file ( $k63_codigo=null,$k63_numpre=null,$k63_notifica=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from listanotifica ";
     $sql2 = "";
     if($dbwhere==""){
       if($k63_codigo!=null ){
         $sql2 .= " where listanotifica.k63_codigo = $k63_codigo "; 
       } 
       if($k63_numpre!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listanotifica.k63_numpre = $k63_numpre "; 
       } 
       if($k63_notifica!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " listanotifica.k63_notifica = $k63_notifica "; 
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