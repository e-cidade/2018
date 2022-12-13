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
//CLASSE DA ENTIDADE debcontaarquivoregcad
class cl_debcontaarquivoregcad { 
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
   var $d74_sequencial = 0; 
   var $d74_codigo = 0; 
   var $d74_tipomov = 0; 
   var $d74_data_dia = null; 
   var $d74_data_mes = null; 
   var $d74_data_ano = null; 
   var $d74_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d74_sequencial = int4 = Sequencial 
                 d74_codigo = int4 = Codigo sequencial 
                 d74_tipomov = int4 = Tipo de movimento 
                 d74_data = date = Data 
                 ";
   //funcao construtor da classe 
   function cl_debcontaarquivoregcad() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("debcontaarquivoregcad"); 
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
       $this->d74_sequencial = ($this->d74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_sequencial"]:$this->d74_sequencial);
       $this->d74_codigo = ($this->d74_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_codigo"]:$this->d74_codigo);
       $this->d74_tipomov = ($this->d74_tipomov == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_tipomov"]:$this->d74_tipomov);
       if($this->d74_data == ""){
         $this->d74_data_dia = ($this->d74_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_data_dia"]:$this->d74_data_dia);
         $this->d74_data_mes = ($this->d74_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_data_mes"]:$this->d74_data_mes);
         $this->d74_data_ano = ($this->d74_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_data_ano"]:$this->d74_data_ano);
         if($this->d74_data_dia != ""){
            $this->d74_data = $this->d74_data_ano."-".$this->d74_data_mes."-".$this->d74_data_dia;
         }
       }
     }else{
       $this->d74_sequencial = ($this->d74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["d74_sequencial"]:$this->d74_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($d74_sequencial){ 
      $this->atualizacampos();
     if($this->d74_codigo == null ){ 
       $this->erro_sql = " Campo Codigo sequencial nao Informado.";
       $this->erro_campo = "d74_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d74_tipomov == null ){ 
       $this->erro_sql = " Campo Tipo de movimento nao Informado.";
       $this->erro_campo = "d74_tipomov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d74_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "d74_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($d74_sequencial == "" || $d74_sequencial == null ){
       $result = db_query("select nextval('debcontaarquivoregcad_d74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: debcontaarquivoregcad_d74_sequencial_seq do campo: d74_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->d74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from debcontaarquivoregcad_d74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $d74_sequencial)){
         $this->erro_sql = " Campo d74_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->d74_sequencial = $d74_sequencial; 
       }
     }
     if(($this->d74_sequencial == null) || ($this->d74_sequencial == "") ){ 
       $this->erro_sql = " Campo d74_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into debcontaarquivoregcad(
                                       d74_sequencial 
                                      ,d74_codigo 
                                      ,d74_tipomov 
                                      ,d74_data 
                       )
                values (
                                $this->d74_sequencial 
                               ,$this->d74_codigo 
                               ,$this->d74_tipomov 
                               ,".($this->d74_data == "null" || $this->d74_data == ""?"null":"'".$this->d74_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "debcontaarquivoregcad ($this->d74_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "debcontaarquivoregcad j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "debcontaarquivoregcad ($this->d74_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d74_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d74_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7984,'$this->d74_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1341,7984,'','".AddSlashes(pg_result($resaco,0,'d74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1341,7985,'','".AddSlashes(pg_result($resaco,0,'d74_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1341,7986,'','".AddSlashes(pg_result($resaco,0,'d74_tipomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1341,7987,'','".AddSlashes(pg_result($resaco,0,'d74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update debcontaarquivoregcad set ";
     $virgula = "";
     if(trim($this->d74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d74_sequencial"])){ 
       $sql  .= $virgula." d74_sequencial = $this->d74_sequencial ";
       $virgula = ",";
       if(trim($this->d74_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "d74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d74_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d74_codigo"])){ 
       $sql  .= $virgula." d74_codigo = $this->d74_codigo ";
       $virgula = ",";
       if(trim($this->d74_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "d74_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d74_tipomov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d74_tipomov"])){ 
       $sql  .= $virgula." d74_tipomov = $this->d74_tipomov ";
       $virgula = ",";
       if(trim($this->d74_tipomov) == null ){ 
         $this->erro_sql = " Campo Tipo de movimento nao Informado.";
         $this->erro_campo = "d74_tipomov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d74_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d74_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["d74_data_dia"] !="") ){ 
       $sql  .= $virgula." d74_data = '$this->d74_data' ";
       $virgula = ",";
       if(trim($this->d74_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "d74_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["d74_data_dia"])){ 
         $sql  .= $virgula." d74_data = null ";
         $virgula = ",";
         if(trim($this->d74_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "d74_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($d74_sequencial!=null){
       $sql .= " d74_sequencial = $this->d74_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d74_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7984,'$this->d74_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d74_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1341,7984,'".AddSlashes(pg_result($resaco,$conresaco,'d74_sequencial'))."','$this->d74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d74_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1341,7985,'".AddSlashes(pg_result($resaco,$conresaco,'d74_codigo'))."','$this->d74_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d74_tipomov"]))
           $resac = db_query("insert into db_acount values($acount,1341,7986,'".AddSlashes(pg_result($resaco,$conresaco,'d74_tipomov'))."','$this->d74_tipomov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d74_data"]))
           $resac = db_query("insert into db_acount values($acount,1341,7987,'".AddSlashes(pg_result($resaco,$conresaco,'d74_data'))."','$this->d74_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "debcontaarquivoregcad nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d74_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "debcontaarquivoregcad nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d74_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d74_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7984,'$d74_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1341,7984,'','".AddSlashes(pg_result($resaco,$iresaco,'d74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1341,7985,'','".AddSlashes(pg_result($resaco,$iresaco,'d74_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1341,7986,'','".AddSlashes(pg_result($resaco,$iresaco,'d74_tipomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1341,7987,'','".AddSlashes(pg_result($resaco,$iresaco,'d74_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from debcontaarquivoregcad
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d74_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d74_sequencial = $d74_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "debcontaarquivoregcad nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d74_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "debcontaarquivoregcad nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d74_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d74_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:debcontaarquivoregcad";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivoregcad ";
     $sql .= "      inner join debcontaarquivoreg  on  debcontaarquivoreg.d73_sequencial = debcontaarquivoregcad.d74_codigo";
     $sql .= "      inner join debcontaarquivo  on  debcontaarquivo.d72_codigo = debcontaarquivoreg.d73_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($d74_sequencial!=null ){
         $sql2 .= " where debcontaarquivoregcad.d74_sequencial = $d74_sequencial "; 
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
   function sql_query_file ( $d74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from debcontaarquivoregcad ";
     $sql2 = "";
     if($dbwhere==""){
       if($d74_sequencial!=null ){
         $sql2 .= " where debcontaarquivoregcad.d74_sequencial = $d74_sequencial "; 
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