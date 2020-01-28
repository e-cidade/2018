<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: material
//CLASSE DA ENTIDADE matestoqueitemnotafiscalmanual
class cl_matestoqueitemnotafiscalmanual { 
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
   var $m79_sequencial = 0; 
   var $m79_matestoqueitem = 0; 
   var $m79_notafiscal = null; 
   var $m79_data_dia = null; 
   var $m79_data_mes = null; 
   var $m79_data_ano = null; 
   var $m79_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m79_sequencial = int4 = Sequencial 
                 m79_matestoqueitem = int4 = Item 
                 m79_notafiscal = varchar(15) = Nota Fiscal 
                 m79_data = date = Data da Nota 
                 ";
   //funcao construtor da classe 
   function cl_matestoqueitemnotafiscalmanual() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoqueitemnotafiscalmanual"); 
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
       $this->m79_sequencial = ($this->m79_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_sequencial"]:$this->m79_sequencial);
       $this->m79_matestoqueitem = ($this->m79_matestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_matestoqueitem"]:$this->m79_matestoqueitem);
       $this->m79_notafiscal = ($this->m79_notafiscal == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_notafiscal"]:$this->m79_notafiscal);
       if($this->m79_data == ""){
         $this->m79_data_dia = ($this->m79_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_data_dia"]:$this->m79_data_dia);
         $this->m79_data_mes = ($this->m79_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_data_mes"]:$this->m79_data_mes);
         $this->m79_data_ano = ($this->m79_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_data_ano"]:$this->m79_data_ano);
         if($this->m79_data_dia != ""){
            $this->m79_data = $this->m79_data_ano."-".$this->m79_data_mes."-".$this->m79_data_dia;
         }
       }
     }else{
       $this->m79_sequencial = ($this->m79_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m79_sequencial"]:$this->m79_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m79_sequencial){ 
      $this->atualizacampos();
     if($this->m79_matestoqueitem == null ){ 
       $this->erro_sql = " Campo Item nao Informado.";
       $this->erro_campo = "m79_matestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m79_notafiscal == null ){ 
       $this->erro_sql = " Campo Nota Fiscal nao Informado.";
       $this->erro_campo = "m79_notafiscal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m79_data == null ){ 
       $this->erro_sql = " Campo Data da Nota nao Informado.";
       $this->erro_campo = "m79_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m79_sequencial == "" || $m79_sequencial == null ){
       $result = db_query("select nextval('matestoqueitemnotafiscalmanual_m79_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueitemnotafiscalmanual_m79_sequencial_seq do campo: m79_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m79_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoqueitemnotafiscalmanual_m79_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m79_sequencial)){
         $this->erro_sql = " Campo m79_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m79_sequencial = $m79_sequencial; 
       }
     }
     if(($this->m79_sequencial == null) || ($this->m79_sequencial == "") ){ 
       $this->erro_sql = " Campo m79_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemnotafiscalmanual(
                                       m79_sequencial 
                                      ,m79_matestoqueitem 
                                      ,m79_notafiscal 
                                      ,m79_data 
                       )
                values (
                                $this->m79_sequencial 
                               ,$this->m79_matestoqueitem 
                               ,'$this->m79_notafiscal' 
                               ,".($this->m79_data == "null" || $this->m79_data == ""?"null":"'".$this->m79_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro da Nota Fiscal de um Item ($this->m79_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro da Nota Fiscal de um Item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro da Nota Fiscal de um Item ($this->m79_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m79_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m79_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18807,'$this->m79_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3335,18807,'','".AddSlashes(pg_result($resaco,0,'m79_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3335,18808,'','".AddSlashes(pg_result($resaco,0,'m79_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3335,18809,'','".AddSlashes(pg_result($resaco,0,'m79_notafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3335,18810,'','".AddSlashes(pg_result($resaco,0,'m79_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m79_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matestoqueitemnotafiscalmanual set ";
     $virgula = "";
     if(trim($this->m79_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m79_sequencial"])){ 
       $sql  .= $virgula." m79_sequencial = $this->m79_sequencial ";
       $virgula = ",";
       if(trim($this->m79_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "m79_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m79_matestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m79_matestoqueitem"])){ 
       $sql  .= $virgula." m79_matestoqueitem = $this->m79_matestoqueitem ";
       $virgula = ",";
       if(trim($this->m79_matestoqueitem) == null ){ 
         $this->erro_sql = " Campo Item nao Informado.";
         $this->erro_campo = "m79_matestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m79_notafiscal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m79_notafiscal"])){ 
       $sql  .= $virgula." m79_notafiscal = '$this->m79_notafiscal' ";
       $virgula = ",";
       if(trim($this->m79_notafiscal) == null ){ 
         $this->erro_sql = " Campo Nota Fiscal nao Informado.";
         $this->erro_campo = "m79_notafiscal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m79_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m79_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m79_data_dia"] !="") ){ 
       $sql  .= $virgula." m79_data = '$this->m79_data' ";
       $virgula = ",";
       if(trim($this->m79_data) == null ){ 
         $this->erro_sql = " Campo Data da Nota nao Informado.";
         $this->erro_campo = "m79_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m79_data_dia"])){ 
         $sql  .= $virgula." m79_data = null ";
         $virgula = ",";
         if(trim($this->m79_data) == null ){ 
           $this->erro_sql = " Campo Data da Nota nao Informado.";
           $this->erro_campo = "m79_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($m79_sequencial!=null){
       $sql .= " m79_sequencial = $this->m79_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m79_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18807,'$this->m79_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m79_sequencial"]) || $this->m79_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3335,18807,'".AddSlashes(pg_result($resaco,$conresaco,'m79_sequencial'))."','$this->m79_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m79_matestoqueitem"]) || $this->m79_matestoqueitem != "")
           $resac = db_query("insert into db_acount values($acount,3335,18808,'".AddSlashes(pg_result($resaco,$conresaco,'m79_matestoqueitem'))."','$this->m79_matestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m79_notafiscal"]) || $this->m79_notafiscal != "")
           $resac = db_query("insert into db_acount values($acount,3335,18809,'".AddSlashes(pg_result($resaco,$conresaco,'m79_notafiscal'))."','$this->m79_notafiscal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m79_data"]) || $this->m79_data != "")
           $resac = db_query("insert into db_acount values($acount,3335,18810,'".AddSlashes(pg_result($resaco,$conresaco,'m79_data'))."','$this->m79_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da Nota Fiscal de um Item nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m79_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da Nota Fiscal de um Item nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m79_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m79_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18807,'$m79_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3335,18807,'','".AddSlashes(pg_result($resaco,$iresaco,'m79_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3335,18808,'','".AddSlashes(pg_result($resaco,$iresaco,'m79_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3335,18809,'','".AddSlashes(pg_result($resaco,$iresaco,'m79_notafiscal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3335,18810,'','".AddSlashes(pg_result($resaco,$iresaco,'m79_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoqueitemnotafiscalmanual
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m79_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m79_sequencial = $m79_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da Nota Fiscal de um Item nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m79_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da Nota Fiscal de um Item nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m79_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemnotafiscalmanual";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $m79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemnotafiscalmanual ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemnotafiscalmanual.m79_matestoqueitem";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if($dbwhere==""){
       if($m79_sequencial!=null ){
         $sql2 .= " where matestoqueitemnotafiscalmanual.m79_sequencial = $m79_sequencial "; 
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
   function sql_query_file ( $m79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoqueitemnotafiscalmanual ";
     $sql2 = "";
     if($dbwhere==""){
       if($m79_sequencial!=null ){
         $sql2 .= " where matestoqueitemnotafiscalmanual.m79_sequencial = $m79_sequencial "; 
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