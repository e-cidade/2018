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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE sau_turnoatend
class cl_sau_turnoatend { 
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
   var $sd43_cod_turnat = 0; 
   var $sd43_v_descricao = null; 
   var $sd43_c_horainicial = null; 
   var $sd43_c_horafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd43_cod_turnat = int4 = Código atendimento 
                 sd43_v_descricao = varchar(100) = Descrição 
                 sd43_c_horainicial = char(5) = Hora Inicial 
                 sd43_c_horafinal = char(5) = Hora Final 
                 ";
   //funcao construtor da classe 
   function cl_sau_turnoatend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_turnoatend"); 
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
       $this->sd43_cod_turnat = ($this->sd43_cod_turnat == ""?@$GLOBALS["HTTP_POST_VARS"]["sd43_cod_turnat"]:$this->sd43_cod_turnat);
       $this->sd43_v_descricao = ($this->sd43_v_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd43_v_descricao"]:$this->sd43_v_descricao);
       $this->sd43_c_horainicial = ($this->sd43_c_horainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["sd43_c_horainicial"]:$this->sd43_c_horainicial);
       $this->sd43_c_horafinal = ($this->sd43_c_horafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["sd43_c_horafinal"]:$this->sd43_c_horafinal);
     }else{
       $this->sd43_cod_turnat = ($this->sd43_cod_turnat == ""?@$GLOBALS["HTTP_POST_VARS"]["sd43_cod_turnat"]:$this->sd43_cod_turnat);
     }
   }
   // funcao para inclusao
   function incluir ($sd43_cod_turnat){ 
      $this->atualizacampos();
     if($this->sd43_v_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "sd43_v_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd43_c_horainicial == null ){ 
       $this->erro_sql = " Campo Hora Inicial nao Informado.";
       $this->erro_campo = "sd43_c_horainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd43_c_horafinal == null ){ 
       $this->erro_sql = " Campo Hora Final nao Informado.";
       $this->erro_campo = "sd43_c_horafinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->sd43_cod_turnat = $sd43_cod_turnat; 
     if(($this->sd43_cod_turnat == null) || ($this->sd43_cod_turnat == "") ){ 
       $this->erro_sql = " Campo sd43_cod_turnat nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_turnoatend(
                                       sd43_cod_turnat 
                                      ,sd43_v_descricao 
                                      ,sd43_c_horainicial 
                                      ,sd43_c_horafinal 
                       )
                values (
                                $this->sd43_cod_turnat 
                               ,'$this->sd43_v_descricao' 
                               ,'$this->sd43_c_horainicial' 
                               ,'$this->sd43_c_horafinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turnoaten ($this->sd43_cod_turnat) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turnoaten já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turnoaten ($this->sd43_cod_turnat) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd43_cod_turnat;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd43_cod_turnat));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11416,'$this->sd43_cod_turnat','I')");
       $resac = db_query("insert into db_acount values($acount,1960,11416,'','".AddSlashes(pg_result($resaco,0,'sd43_cod_turnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1960,11417,'','".AddSlashes(pg_result($resaco,0,'sd43_v_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1960,12506,'','".AddSlashes(pg_result($resaco,0,'sd43_c_horainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1960,12507,'','".AddSlashes(pg_result($resaco,0,'sd43_c_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd43_cod_turnat=null) { 
      $this->atualizacampos();
     $sql = " update sau_turnoatend set ";
     $virgula = "";
     if(trim($this->sd43_cod_turnat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd43_cod_turnat"])){ 
       $sql  .= $virgula." sd43_cod_turnat = $this->sd43_cod_turnat ";
       $virgula = ",";
       if(trim($this->sd43_cod_turnat) == null ){ 
         $this->erro_sql = " Campo Código atendimento nao Informado.";
         $this->erro_campo = "sd43_cod_turnat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd43_v_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd43_v_descricao"])){ 
       $sql  .= $virgula." sd43_v_descricao = '$this->sd43_v_descricao' ";
       $virgula = ",";
       if(trim($this->sd43_v_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "sd43_v_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd43_c_horainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd43_c_horainicial"])){ 
       $sql  .= $virgula." sd43_c_horainicial = '$this->sd43_c_horainicial' ";
       $virgula = ",";
       if(trim($this->sd43_c_horainicial) == null ){ 
         $this->erro_sql = " Campo Hora Inicial nao Informado.";
         $this->erro_campo = "sd43_c_horainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd43_c_horafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd43_c_horafinal"])){ 
       $sql  .= $virgula." sd43_c_horafinal = '$this->sd43_c_horafinal' ";
       $virgula = ",";
       if(trim($this->sd43_c_horafinal) == null ){ 
         $this->erro_sql = " Campo Hora Final nao Informado.";
         $this->erro_campo = "sd43_c_horafinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd43_cod_turnat!=null){
       $sql .= " sd43_cod_turnat = $this->sd43_cod_turnat";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd43_cod_turnat));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11416,'$this->sd43_cod_turnat','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd43_cod_turnat"]))
           $resac = db_query("insert into db_acount values($acount,1960,11416,'".AddSlashes(pg_result($resaco,$conresaco,'sd43_cod_turnat'))."','$this->sd43_cod_turnat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd43_v_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1960,11417,'".AddSlashes(pg_result($resaco,$conresaco,'sd43_v_descricao'))."','$this->sd43_v_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd43_c_horainicial"]))
           $resac = db_query("insert into db_acount values($acount,1960,12506,'".AddSlashes(pg_result($resaco,$conresaco,'sd43_c_horainicial'))."','$this->sd43_c_horainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd43_c_horafinal"]))
           $resac = db_query("insert into db_acount values($acount,1960,12507,'".AddSlashes(pg_result($resaco,$conresaco,'sd43_c_horafinal'))."','$this->sd43_c_horafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turnoaten nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd43_cod_turnat;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turnoaten nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd43_cod_turnat;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd43_cod_turnat;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd43_cod_turnat=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd43_cod_turnat));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11416,'$sd43_cod_turnat','E')");
         $resac = db_query("insert into db_acount values($acount,1960,11416,'','".AddSlashes(pg_result($resaco,$iresaco,'sd43_cod_turnat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1960,11417,'','".AddSlashes(pg_result($resaco,$iresaco,'sd43_v_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1960,12506,'','".AddSlashes(pg_result($resaco,$iresaco,'sd43_c_horainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1960,12507,'','".AddSlashes(pg_result($resaco,$iresaco,'sd43_c_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_turnoatend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd43_cod_turnat != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd43_cod_turnat = $sd43_cod_turnat ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turnoaten nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd43_cod_turnat;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "turnoaten nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd43_cod_turnat;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd43_cod_turnat;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_turnoatend";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd43_cod_turnat=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_turnoatend ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd43_cod_turnat!=null ){
         $sql2 .= " where sau_turnoatend.sd43_cod_turnat = $sd43_cod_turnat "; 
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
   function sql_query_file ( $sd43_cod_turnat=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from sau_turnoatend ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd43_cod_turnat!=null ){
         $sql2 .= " where sau_turnoatend.sd43_cod_turnat = $sd43_cod_turnat "; 
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